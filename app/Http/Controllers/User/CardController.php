<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardSettings;
use App\Models\CardTransaction;
use App\Models\User;
use App\Helpers\CardGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    /**
     * Display user's card dashboard
     */
    public function index()
    {
        $user = Auth::user();
        $cards = Card::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pendingCount = $cards->where('status', 'pending')->count();
        $activeCards = $cards->where('status', 'active');
        
        $totalBalance = $activeCards->sum('balance');
        $totalTransactions = CardTransaction::whereIn('card_id', $activeCards->pluck('id'))
            ->completed()
            ->count();
        
        return view('user.cards.index', compact('cards', 'pendingCount', 'totalBalance', 'totalTransactions'));
    }

    /**
     * Show card application form
     */
    public function showApplicationForm()
    {
        $settings = CardSettings::getSettings();
        
        if (!$settings->cards_enabled) {
            return redirect()->route('user.cards.index')
                ->with('error', 'Card applications are currently disabled.');
        }
        
        return view('user.cards.apply', compact('settings'));
    }

    /**
     * Apply for a new card
     */
    public function applyCard(Request $request)
    {
        $request->validate([
            'card_type' => 'required|in:Visa,Mastercard,American Express,Discover',
            'card_level' => 'required|in:Standard,Gold,Platinum,Black',
            'cardholder_name' => 'required|string|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_country' => 'required|string|max:100',
            'billing_zipcode' => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $settings = CardSettings::getSettings();

        if (!$settings->cards_enabled) {
            return back()->with('error', 'Card applications are currently disabled.');
        }

        // Check if user already has a pending application
        $pendingCard = Card::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if ($pendingCard) {
            return back()->with('error', 'You already have a pending card application.');
        }

        // Get issuance fee
        $issuanceFee = $settings->getIssuanceFee($request->card_level);

        // Check user balance
        if ($user->account_bal < $issuanceFee) {
            return back()->with('error', 'Insufficient balance. Card issuance fee: $' . number_format($issuanceFee, 2));
        }

        DB::beginTransaction();
        try {
            // Deduct fee from user balance
            $user->account_bal -= $issuanceFee;
            $user->save();

            // Create card application
            $card = Card::create([
                'user_id' => $user->id,
                'card_type' => $request->card_type,
                'card_level' => $request->card_level,
                'cardholder_name' => $request->cardholder_name,
                'billing_address' => $request->billing_address,
                'billing_city' => $request->billing_city,
                'billing_country' => $request->billing_country,
                'billing_zipcode' => $request->billing_zipcode,
                'status' => 'pending',
                'balance' => 0,
                'daily_limit' => $settings->max_daily_limit,
                'amount' => $issuanceFee,
            ]);

            // Record fee transaction
            CardTransaction::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'type' => 'fee',
                'amount' => $issuanceFee,
                'description' => 'Card issuance fee - ' . $request->card_level,
                'status' => 'completed',
                'balance_before' => $user->account_bal + $issuanceFee,
                'balance_after' => $user->account_bal,
            ]);

            DB::commit();

            return redirect()->route('cards.index')
                ->with('success', 'Card application submitted successfully. Issuance fee of $' . number_format($issuanceFee, 2) . ' has been deducted.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit application. Please try again.');
        }
    }

    /**
     * View single card details
     */
    public function viewCard($id)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $recentTransactions = CardTransaction::where('card_id', $card->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('user.cards.view', compact('card', 'recentTransactions'));
    }

    /**
     * Show card transactions
     */
    public function cardTransactions($id, Request $request)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $query = CardTransaction::where('card_id', $card->id);
        
        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('from') && $request->from) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->has('to') && $request->to) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        
        $transactions = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('user.cards.transactions', compact('card', 'transactions'));
    }

    /**
     * Fund card (top up)
     */
    public function fundCard(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $card = Card::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        $settings = CardSettings::getSettings();
        $amount = $request->amount;
        $fee = $settings->calculateTopupFee($amount);
        $totalDeduction = $amount + $fee;

        // Check user balance
        if ($user->account_bal < $totalDeduction) {
            return back()->with('error', 'Insufficient balance. Required: $' . number_format($totalDeduction, 2) . ' (Amount: $' . number_format($amount, 2) . ' + Fee: $' . number_format($fee, 2) . ')');
        }

        DB::beginTransaction();
        try {
            // Deduct from user balance
            $user->account_bal -= $totalDeduction;
            $user->save();

            // Add to card balance
            $balanceBefore = $card->balance;
            $card->balance += $amount;
            $card->save();

            // Record top-up transaction
            CardTransaction::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'type' => 'topup',
                'amount' => $amount,
                'description' => 'Card top-up',
                'status' => 'completed',
                'balance_before' => $balanceBefore,
                'balance_after' => $card->balance,
            ]);

            // Record fee transaction if fee > 0
            if ($fee > 0) {
                CardTransaction::create([
                    'card_id' => $card->id,
                    'user_id' => $user->id,
                    'type' => 'fee',
                    'amount' => $fee,
                    'description' => 'Top-up fee',
                    'status' => 'completed',
                    'balance_before' => $card->balance,
                    'balance_after' => $card->balance,
                ]);
            }

            DB::commit();

            return back()->with('success', 'Card funded successfully. $' . number_format($amount, 2) . ' added to card.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to fund card. Please try again.');
        }
    }

    /**
     * Withdraw from card
     */
    public function withdrawFromCard(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $user = Auth::user();
        $card = Card::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->firstOrFail();

        $amount = $request->amount;

        // Check card balance
        if ($card->balance < $amount) {
            return back()->with('error', 'Insufficient card balance.');
        }

        DB::beginTransaction();
        try {
            // Deduct from card balance
            $balanceBefore = $card->balance;
            $card->balance -= $amount;
            $card->save();

            // Add to user balance
            $user->account_bal += $amount;
            $user->save();

            // Record withdrawal transaction
            CardTransaction::create([
                'card_id' => $card->id,
                'user_id' => $user->id,
                'type' => 'withdrawal',
                'amount' => $amount,
                'description' => 'Withdrawal to account',
                'status' => 'completed',
                'balance_before' => $balanceBefore,
                'balance_after' => $card->balance,
            ]);

            DB::commit();

            return back()->with('success', 'Withdrawal successful. $' . number_format($amount, 2) . ' transferred to your account.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process withdrawal. Please try again.');
        }
    }

    /**
     * Block/Unblock card
     */
    public function toggleCardStatus($id)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'pending')
            ->firstOrFail();

        if ($card->status === 'active') {
            $card->status = 'blocked';
            $card->blocked_at = now();
            $message = 'Card blocked successfully.';
        } else if ($card->status === 'blocked') {
            $card->status = 'active';
            $card->blocked_at = null;
            $message = 'Card unblocked successfully.';
        } else {
            return back()->with('error', 'Cannot change status of this card.');
        }

        $card->save();

        return back()->with('success', $message);
    }

    /**
     * Reveal full card number (requires authentication)
     */
    public function revealCardNumber($id)
    {
        $card = Card::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->firstOrFail();

        if (!$card->card_pan) {
            return response()->json(['error' => 'Card details not available'], 400);
        }

        $fullCardNumber = CardGenerator::decryptPAN($card->card_pan);

        return response()->json([
            'card_number' => CardGenerator::formatCardNumber($fullCardNumber),
            'cvv' => $card->cvv,
            'expiry' => $card->expiry_month . '/' . substr($card->expiry_year, -2)
        ]);
    }
}
