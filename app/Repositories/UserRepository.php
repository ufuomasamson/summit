<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    /**
     * Find user by account number
     *
     * @param string $accountNumber
     * @return User|null
     */
    public function findByAccountNumber(string $accountNumber): ?User
    {
        return User::where('usernumber', $accountNumber)->first();
    }

    /**
     * Debit user account with row-level locking
     *
     * @param int $userId
     * @param float $amount
     * @return bool
     */
    public function debitAccount(int $userId, float $amount): bool
    {
        return DB::transaction(function () use ($userId, $amount) {
            $user = User::where('id', $userId)->lockForUpdate()->first();
            
            if (!$user || $user->account_bal < $amount) {
                return false;
            }

            $user->account_bal -= $amount;
            return $user->save();
        });
    }

    /**
     * Credit user account with row-level locking
     *
     * @param int $userId
     * @param float $amount
     * @return bool
     */
    public function creditAccount(int $userId, float $amount): bool
    {
        return DB::transaction(function () use ($userId, $amount) {
            $user = User::where('id', $userId)->lockForUpdate()->first();
            
            if (!$user) {
                return false;
            }

            $user->account_bal += $amount;
            return $user->save();
        });
    }

    /**
     * Update user transfer action status
     *
     * @param int $userId
     * @param int $status (0 = locked, 1 = active)
     * @return bool
     */
    public function updateTransferAction(int $userId, int $status): bool
    {
        return User::where('id', $userId)->update(['transferaction' => $status]);
    }

    /**
     * Store OTP for user
     *
     * @param int $userId
     * @param string $otp
     * @return bool
     */
    public function storeOTP(int $userId, string $otp): bool
    {
        return User::where('id', $userId)->update(['otp' => $otp]);
    }

    /**
     * Clear OTP for user
     *
     * @param int $userId
     * @return bool
     */
    public function clearOTP(int $userId): bool
    {
        return User::where('id', $userId)->update(['otp' => null]);
    }

    /**
     * Find user by ID
     *
     * @param int $userId
     * @return User|null
     */
    public function find(int $userId): ?User
    {
        return User::find($userId);
    }
}
