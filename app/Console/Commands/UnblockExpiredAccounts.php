<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AccountBlock;
use App\Traits\CreatesNotifications;
use Carbon\Carbon;

class UnblockExpiredAccounts extends Command
{
    use CreatesNotifications;

    protected $signature = 'accounts:unblock-expired';
    protected $description = 'Automatically unblock accounts that have reached their unblock date';

    public function handle()
    {
        $now = Carbon::now();
        
        $expiredBlocks = User::where('account_status', 'blocked')
            ->whereNotNull('unblock_at')
            ->where('unblock_at', '<=', $now)
            ->get();

        $count = 0;
        
        foreach ($expiredBlocks as $user) {
            // Update account block history
            AccountBlock::where('user_id', $user->id)
                ->whereNull('unblocked_at')
                ->update([
                    'unblocked_at' => $now,
                    'unblock_note' => 'Automatically unblocked (scheduled expiration)'
                ]);

            // Restore user account
            $user->update([
                'account_status' => 'active',
                'block_reason' => null,
                'blocked_at' => null,
                'blocked_by_admin_id' => null,
                'unblock_at' => null
            ]);

            // Notify user
            $this->notifyUnblock($user->id, 'Your temporary block has expired and your account has been automatically restored.');
            
            $count++;
        }

        $this->info("Unblocked {$count} expired account(s)");
        
        return 0;
    }
}
