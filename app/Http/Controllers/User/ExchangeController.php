<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CryptoAccount;
use App\Models\CryptoRecord;
use App\Models\User;
use App\Models\Settings;
use App\Traits\CreatesNotifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExchangeController extends Controller
{
    use CreatesNotifications;

    /**
     * Supported coins for swap
     */
    private const COINS = ['btc', 'eth', 'usdt'];
    
    /**
     * CoinGecko ID mapping
     */
    private const COINGECKO_IDS = [
        'btc' => 'bitcoin',
        'eth' => 'ethereum',
        'usdt' => 'tether',
    ];

    private const COIN_NAMES = [
        'btc' => 'Bitcoin',
        'eth' => 'Ethereum',
        'usdt' => 'USDT',
        'usd' => 'Main Balance',
    ];

    /**
     * Show the swap page
     */
    public function assetview()
    {
        $user = Auth::user();
        $cryptoAccount = CryptoAccount::where('user_id', $user->id)->first();
        $settings = Settings::where('id', '1')->first();
        $prices = $this->fetchLivePrices();

        return view('user.swap', [
            'title' => 'Crypto Swap',
            'cryptoAccount' => $cryptoAccount,
            'prices' => $prices,
            'recentSwaps' => CryptoRecord::where('user_id', $user->id)->orderByDesc('id')->take(5)->get(),
        ]);
    }

    /**
     * Show swap history
     */
    public function history()
    {
        $swapHistory = CryptoRecord::where('user_id', Auth::user()->id)->orderByDesc('id')->paginate(15);
        
        return view('user.swap-history', [
            'title' => 'Swap History',
            'transactions' => $swapHistory,
        ]);
    }

    /**
     * Get live price quote for a swap
     * Returns the amount user will receive after conversion
     */
    public function getprice($base, $quote, $amount)
    {
        $base = strtolower($base);
        $quote = strtolower($quote);
        $amount = floatval($amount);

        if ($amount <= 0) {
            return response()->json(['status' => 400, 'message' => 'Invalid amount']);
        }

        // Validate coins
        $validCoins = array_merge(self::COINS, ['usd']);
        if (!in_array($base, $validCoins) || !in_array($quote, $validCoins) || $base === $quote) {
            return response()->json(['status' => 400, 'message' => 'Invalid swap pair']);
        }

        try {
            $prices = $this->fetchLivePrices();
            $result = $this->calculateSwap($base, $quote, $amount, $prices);
            
            return response()->json([
                'status' => 200,
                'data' => round($result['receive_amount'], 8),
                'rate' => $result['rate'],
                'rate_display' => $result['rate_display'],
            ]);
        } catch (\Exception $e) {
            Log::error('Price fetch failed: ' . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Unable to fetch live rates. Please try again.']);
        }
    }

    /**
     * Execute a swap
     */
    public function exchange(Request $request)
    {
        $request->validate([
            'source' => 'required|string',
            'destination' => 'required|string',
            'amount' => 'required|numeric|min:0.00000001',
        ]);

        $source = strtolower($request->source);
        $destination = strtolower($request->destination);
        $amount = floatval($request->amount);

        // Validate coins
        $validCoins = array_merge(self::COINS, ['usd']);
        if (!in_array($source, $validCoins) || !in_array($destination, $validCoins) || $source === $destination) {
            return response()->json(['status' => 400, 'message' => 'Invalid swap pair']);
        }

        $user = User::find(Auth::user()->id);
        $cryptoAccount = CryptoAccount::where('user_id', $user->id)->first();
        $settings = Settings::where('id', '1')->first();

        // Check source balance
        if ($source === 'usd') {
            if ($user->account_bal < $amount) {
                return response()->json(['status' => 400, 'message' => 'Insufficient balance in your main account']);
            }
        } else {
            if (!$cryptoAccount || $cryptoAccount->$source < $amount) {
                $coinName = self::COIN_NAMES[$source] ?? strtoupper($source);
                return response()->json(['status' => 400, 'message' => "Insufficient {$coinName} balance"]);
            }
        }

        try {
            $prices = $this->fetchLivePrices();
            $calc = $this->calculateSwap($source, $destination, $amount, $prices);
            $receiveAmount = $calc['receive_amount'];
            $rate = $calc['rate'];

            DB::transaction(function () use ($source, $destination, $amount, $receiveAmount, $rate, $user, $cryptoAccount) {
                // Debit source
                if ($source === 'usd') {
                    User::where('id', $user->id)->update([
                        'account_bal' => $user->account_bal - $amount,
                    ]);
                } else {
                    DB::table('crypto_accounts')->where('user_id', $user->id)->update([
                        $source => $cryptoAccount->$source - $amount,
                    ]);
                }

                // Credit destination
                if ($destination === 'usd') {
                    User::where('id', $user->id)->update([
                        'account_bal' => DB::raw("account_bal + {$receiveAmount}"),
                    ]);
                } else {
                    $currentDest = $cryptoAccount->$destination ?? 0;
                    DB::table('crypto_accounts')->where('user_id', $user->id)->update([
                        $destination => $currentDest + $receiveAmount,
                    ]);
                }

                // Determine swap type
                if ($source === 'usd') {
                    $type = CryptoRecord::TYPE_MAIN_TO_CRYPTO;
                } elseif ($destination === 'usd') {
                    $type = CryptoRecord::TYPE_CRYPTO_TO_MAIN;
                } else {
                    $type = CryptoRecord::TYPE_CRYPTO_TO_CRYPTO;
                }

                // Log the swap
                CryptoRecord::create([
                    'user_id' => $user->id,
                    'source' => strtoupper($source),
                    'dest' => strtoupper($destination),
                    'amount' => $amount,
                    'quantity' => $receiveAmount,
                    'rate' => $rate,
                    'fee' => 0,
                    'type' => $type,
                    'status' => 'completed',
                ]);
            });

            // Send notification
            $sourceName = self::COIN_NAMES[$source] ?? strtoupper($source);
            $destName = self::COIN_NAMES[$destination] ?? strtoupper($destination);
            $this->createNotification(
                $user->id,
                'swap',
                'Crypto Swap Completed',
                "Swapped {$amount} {$sourceName} to {$receiveAmount} {$destName} at rate {$rate}.",
                ['source' => $source, 'destination' => $destination, 'amount' => $amount, 'received' => $receiveAmount]
            );

            return response()->json([
                'status' => 200,
                'success' => 'Swap completed successfully!',
                'received' => round($receiveAmount, 8),
            ]);

        } catch (\Exception $e) {
            Log::error('Swap failed: ' . $e->getMessage());
            return response()->json(['status' => 500, 'message' => 'Swap failed. Please try again.']);
        }
    }

    /**
     * Get balance of a specific coin in USD equivalent
     */
    public function getBalance($coin)
    {
        $coin = strtolower($coin);
        $user = Auth::user();
        $cryptoAccount = CryptoAccount::where('user_id', $user->id)->first();
        $balance = $cryptoAccount ? ($cryptoAccount->$coin ?? 0) : 0;
        
        $prices = $this->fetchLivePrices();
        $usdPrice = $prices[$coin] ?? 0;
        $usdValue = $balance * $usdPrice;
        $settings = Settings::where('id', '1')->first();

        return response()->json([
            'status' => 200,
            'balance' => $balance,
            'usd_value' => number_format(round($usdValue, 2)),
            'price' => $usdPrice,
        ]);
    }

    /**
     * API endpoint: get all crypto balances for current user
     */
    public function getAllBalances()
    {
        $user = Auth::user();
        $cryptoAccount = CryptoAccount::where('user_id', $user->id)->first();

        return response()->json([
            'status' => 200,
            'data' => [
                'usd' => $user->account_bal,
                'btc' => $cryptoAccount ? ($cryptoAccount->btc ?? 0) : 0,
                'eth' => $cryptoAccount ? ($cryptoAccount->eth ?? 0) : 0,
                'usdt' => $cryptoAccount ? ($cryptoAccount->usdt ?? 0) : 0,
            ],
        ]);
    }

    /**
     * Fetch live prices from CoinGecko (cached for 5 minutes) - internal use
     */
    private function fetchLivePrices(): array
    {
        return Cache::remember('coingecko_prices', 300, function () {
            try {
                $ids = implode(',', array_values(self::COINGECKO_IDS));
                $response = Http::timeout(10)->get("https://api.coingecko.com/api/v3/simple/price", [
                    'ids' => $ids,
                    'vs_currencies' => 'usd',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'btc' => $data['bitcoin']['usd'] ?? 0,
                        'eth' => $data['ethereum']['usd'] ?? 0,
                        'usdt' => $data['tether']['usd'] ?? 1,
                    ];
                }
            } catch (\Exception $e) {
                Log::warning('CoinGecko API failed: ' . $e->getMessage());
            }

            // Fallback prices if API fails
            return [
                'btc' => 50000,
                'eth' => 3000,
                'usdt' => 1,
            ];
        });
    }

    /**
     * Public API endpoint: get live crypto prices (called from JS)
     */
    public function getLivePrices()
    {
        try {
            $prices = $this->fetchLivePrices();
            return response()->json(['status' => 200, 'data' => $prices]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Unable to fetch prices']);
        }
    }

    /**
     * API endpoint: calculate swap preview (GET with query params)
     */
    public function calculateSwapPreview(Request $request)
    {
        $source = strtolower($request->query('source', ''));
        $destination = strtolower($request->query('destination', ''));
        $amount = floatval($request->query('amount', 0));

        $validCoins = array_merge(self::COINS, ['usd']);
        if (!in_array($source, $validCoins) || !in_array($destination, $validCoins) || $source === $destination || $amount <= 0) {
            return response()->json(['status' => 400, 'message' => 'Invalid parameters']);
        }

        try {
            $prices = $this->fetchLivePrices();
            $result = $this->calculateSwap($source, $destination, $amount, $prices);
            return response()->json(['status' => 200, 'data' => $result]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Unable to calculate rate']);
        }
    }

    /**
     * Calculate swap amounts (private helper)
     */
    private function calculateSwap(string $source, string $destination, float $amount, array $prices): array
    {
        // Get USD prices (usd = 1)
        $sourcePrice = ($source === 'usd') ? 1 : ($prices[$source] ?? 0);
        $destPrice = ($destination === 'usd') ? 1 : ($prices[$destination] ?? 0);

        if ($destPrice <= 0) {
            throw new \Exception('Invalid destination price');
        }

        // Convert source amount to USD, then to destination
        $usdValue = $amount * $sourcePrice;
        $receiveAmount = $usdValue / $destPrice;
        
        // Rate: how much destination per 1 source
        $rate = $sourcePrice / $destPrice;

        $sourceName = strtoupper($source);
        $destName = strtoupper($destination);
        $rateDisplay = "1 {$sourceName} = " . number_format($rate, 8) . " {$destName}";

        return [
            'receive_amount' => $receiveAmount,
            'rate' => round($rate, 8),
            'rate_display' => $rateDisplay,
        ];
    }
}