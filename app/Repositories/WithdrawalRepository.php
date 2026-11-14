<?php

namespace App\Repositories;

use App\Models\Withdrawal;
use Illuminate\Support\Collection;

class WithdrawalRepository
{
    /**
     * Create a new withdrawal record
     *
     * @param array $data
     * @return Withdrawal
     */
    public function create(array $data): Withdrawal
    {
        return Withdrawal::create($data);
    }

    /**
     * Get all withdrawals for a user
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserWithdrawals(int $userId): Collection
    {
        return Withdrawal::where('user', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get pending withdrawals for a user
     *
     * @param int $userId
     * @return Collection
     */
    public function getPendingWithdrawals(int $userId): Collection
    {
        return Withdrawal::where('user', $userId)
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Update withdrawal status
     *
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool
    {
        return Withdrawal::where('id', $id)->update(['status' => $status]);
    }

    /**
     * Find withdrawal by transaction ID
     *
     * @param string $txnId
     * @return Withdrawal|null
     */
    public function findByTxnId(string $txnId): ?Withdrawal
    {
        return Withdrawal::where('txn_id', $txnId)->first();
    }

    /**
     * Get total pending amount for user
     *
     * @param int $userId
     * @return float
     */
    public function getPendingAmount(int $userId): float
    {
        return Withdrawal::where('user', $userId)
            ->where('status', 'Pending')
            ->sum('amount');
    }

    /**
     * Get withdrawal by ID
     *
     * @param int $id
     * @return Withdrawal|null
     */
    public function find(int $id): ?Withdrawal
    {
        return Withdrawal::find($id);
    }
}
