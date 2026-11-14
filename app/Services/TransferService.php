<?php

namespace App\Services;

use App\Models\{Settings, Withdrawal};
use App\Repositories\{WithdrawalRepository, UserRepository};
use App\Traits\CreatesNotifications;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransferService
{
    use CreatesNotifications;

    protected $withdrawalRepo;
    protected $userRepo;
    protected $settings;

    public function __construct(
        WithdrawalRepository $withdrawalRepo,
        UserRepository $userRepo
    ) {
        $this->withdrawalRepo = $withdrawalRepo;
        $this->userRepo = $userRepo;
        $this->settings = Settings::first();
    }

    /**
     * Process internal transfer (instant between accounts)
     *
     * @param array $data
     * @param int $userId
     * @return array ['success' => bool, 'message' => string, 'withdrawal' => Withdrawal|null]
     */
    public function processInternalTransfer(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            try {
                // Find recipient
                $recipient = $this->userRepo->findByAccountNumber($data['accountnumber']);
                if (!$recipient) {
                    return ['success' => false, 'message' => 'Recipient account not found'];
                }

                // Debit sender
                if (!$this->userRepo->debitAccount($userId, $data['to_deduct'])) {
                    return ['success' => false, 'message' => 'Insufficient funds'];
                }

                // Credit recipient
                $this->userRepo->creditAccount($recipient->id, $data['amount']);

                // Create debit record (sender)
                $debitData = array_merge($data, [
                    'user' => $userId,
                    'type' => 'Debit',
                    'status' => 'Processed',
                    'date' => now(),
                    'bal' => $this->userRepo->find($userId)->account_bal
                ]);
                $debitWithdrawal = $this->withdrawalRepo->create($debitData);

                // Create credit record (recipient)
                $creditData = array_merge($data, [
                    'user' => $recipient->id,
                    'type' => 'Credit',
                    'status' => 'Processed',
                    'date' => now(),
                    'bal' => $recipient->account_bal
                ]);
                $this->withdrawalRepo->create($creditData);

                // Send notifications
                $this->notifyTransfer($userId, $data['amount'], $this->settings->currency, $data['accountname'], 'completed');
                $this->notifyTransfer($recipient->id, $data['amount'], $this->settings->currency, 'Internal Transfer', 'received');

                return [
                    'success' => true,
                    'message' => 'Internal transfer completed successfully',
                    'withdrawal' => $debitWithdrawal
                ];
            } catch (\Exception $e) {
                Log::error('Internal transfer failed', [
                    'user_id' => $userId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return ['success' => false, 'message' => 'Transfer failed. Please try again.'];
            }
        });
    }

    /**
     * Process local/domestic transfer (pending, requires approval)
     *
     * @param array $data
     * @param int $userId
     * @return array
     */
    public function processLocalTransfer(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            try {
                $toDeduct = $data['to_deduct'] ?? $data['amount'];
                
                // Debit user
                if (!$this->userRepo->debitAccount($userId, $toDeduct)) {
                    return ['success' => false, 'message' => 'Insufficient funds'];
                }

                // Create pending withdrawal
                $withdrawalData = array_merge($data, [
                    'user' => $userId,
                    'type' => 'Debit',
                    'status' => 'Pending',
                    'date' => now(),
                    'bal' => $this->userRepo->find($userId)->account_bal,
                    'to_deduct' => $toDeduct
                ]);
                $withdrawal = $this->withdrawalRepo->create($withdrawalData);

                // Lock transfer action
                $this->userRepo->updateTransferAction($userId, 0);

                // Send notification
                $this->notifyTransfer($userId, $data['amount'], $this->settings->currency, $data['accountname'], 'pending');

                return [
                    'success' => true,
                    'message' => 'Local transfer initiated successfully',
                    'withdrawal' => $withdrawal
                ];
            } catch (\Exception $e) {
                Log::error('Local transfer failed', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                return ['success' => false, 'message' => 'Transfer failed. Please try again.'];
            }
        });
    }

    /**
     * Process international/wire transfer
     *
     * @param array $data
     * @param int $userId
     * @return array
     */
    public function processInternationalTransfer(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            try {
                $toDeduct = $data['to_deduct'] ?? $data['amount'];
                
                if (!$this->userRepo->debitAccount($userId, $toDeduct)) {
                    return ['success' => false, 'message' => 'Insufficient funds'];
                }

                $withdrawalData = array_merge($data, [
                    'user' => $userId,
                    'type' => 'Debit',
                    'status' => 'Pending',
                    'date' => now(),
                    'bal' => $this->userRepo->find($userId)->account_bal,
                    'to_deduct' => $toDeduct,
                    'swiftcode' => $data['swiftcode'] ?? null,
                    'iban' => $data['iban'] ?? null,
                    'bankaddress' => $data['address'] ?? null,
                    'country' => $data['country'] ?? null
                ]);
                $withdrawal = $this->withdrawalRepo->create($withdrawalData);

                $this->userRepo->updateTransferAction($userId, 0);
                $this->notifyTransfer($userId, $data['amount'], $this->settings->currency, $data['accountname'], 'pending');

                return [
                    'success' => true,
                    'message' => 'International transfer initiated successfully',
                    'withdrawal' => $withdrawal
                ];
            } catch (\Exception $e) {
                Log::error('International transfer failed', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                return ['success' => false, 'message' => 'Transfer failed. Please try again.'];
            }
        });
    }

    /**
     * Process third-party transfer (PayPal, Skrill, etc.)
     *
     * @param array $data
     * @param int $userId
     * @return array
     */
    public function processThirdPartyTransfer(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            try {
                $toDeduct = $data['to_deduct'] ?? $data['amount'];
                
                if (!$this->userRepo->debitAccount($userId, $toDeduct)) {
                    return ['success' => false, 'message' => 'Insufficient funds'];
                }

                $withdrawalData = array_merge($data, [
                    'user' => $userId,
                    'type' => 'Debit',
                    'status' => 'Pending',
                    'date' => now(),
                    'bal' => $this->userRepo->find($userId)->account_bal,
                    'to_deduct' => $toDeduct
                ]);
                $withdrawal = $this->withdrawalRepo->create($withdrawalData);

                $this->userRepo->updateTransferAction($userId, 0);
                $this->notifyTransfer($userId, $data['amount'], $this->settings->currency, $data['accountname'], 'pending');

                return [
                    'success' => true,
                    'message' => ucfirst(strtolower($data['bankname'])) . ' transfer initiated successfully',
                    'withdrawal' => $withdrawal
                ];
            } catch (\Exception $e) {
                Log::error('Third-party transfer failed', [
                    'user_id' => $userId,
                    'payment_method' => $data['bankname'] ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
                return ['success' => false, 'message' => 'Transfer failed. Please try again.'];
            }
        });
    }

    /**
     * Process cryptocurrency transfer
     *
     * @param array $data
     * @param int $userId
     * @return array
     */
    public function processCryptocurrencyTransfer(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            try {
                $toDeduct = $data['to_deduct'] ?? $data['amount'];
                $cryptoCoin = $data['crypto_currency'] ?? null;

                if ($cryptoCoin && in_array($cryptoCoin, \App\Models\CryptoAccount::SUPPORTED_COINS)) {
                    // Debit from crypto balance
                    $cryptoAccount = \App\Models\CryptoAccount::where('user_id', $userId)->first();
                    if (!$cryptoAccount || !$cryptoAccount->hasSufficientBalance($cryptoCoin, $toDeduct)) {
                        return ['success' => false, 'message' => 'Insufficient crypto balance'];
                    }
                    
                    DB::table('crypto_accounts')
                        ->where('user_id', $userId)
                        ->update([
                            $cryptoCoin => $cryptoAccount->$cryptoCoin - $toDeduct,
                        ]);
                } else {
                    // Fallback: debit from main account balance
                    if (!$this->userRepo->debitAccount($userId, $toDeduct)) {
                        return ['success' => false, 'message' => 'Insufficient funds'];
                    }
                }

                $withdrawalData = array_merge($data, [
                    'user' => $userId,
                    'type' => 'Debit',
                    'status' => 'Pending',
                    'date' => now(),
                    'bal' => $this->userRepo->find($userId)->account_bal,
                    'to_deduct' => $toDeduct,
                    'paydetails' => $data['details'] ?? null,
                    'crypto_currency' => $cryptoCoin,
                ]);
                $withdrawal = $this->withdrawalRepo->create($withdrawalData);

                $this->userRepo->updateTransferAction($userId, 0);
                
                $coinName = $cryptoCoin ? (\App\Models\CryptoAccount::COIN_NAMES[$cryptoCoin] ?? strtoupper($cryptoCoin)) : 'Crypto';
                $this->notifyTransfer($userId, $data['amount'] . ' ' . $coinName, $this->settings->currency, $data['accountname'], 'pending', 'crypto');

                return [
                    'success' => true,
                    'message' => 'Cryptocurrency transfer initiated successfully',
                    'withdrawal' => $withdrawal
                ];
            } catch (\Exception $e) {
                Log::error('Cryptocurrency transfer failed', [
                    'user_id' => $userId,
                    'crypto_type' => $data['bankname'] ?? 'unknown',
                    'error' => $e->getMessage()
                ]);
                return ['success' => false, 'message' => 'Transfer failed. Please try again.'];
            }
        });
    }
}
