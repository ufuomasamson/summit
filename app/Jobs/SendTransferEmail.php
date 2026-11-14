<?php

namespace App\Jobs;

use App\Models\{User, Withdrawal};
use App\Mail\WithdrawalStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendTransferEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $withdrawal;
    public $user;
    public $email;
    public $subject;
    public $isAdmin;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 60;

    /**
     * Create a new job instance.
     *
     * @param Withdrawal $withdrawal
     * @param User $user
     * @param string $email
     * @param string $subject
     * @param bool $isAdmin
     * @return void
     */
    public function __construct(Withdrawal $withdrawal, User $user, string $email, string $subject, bool $isAdmin = false)
    {
        $this->withdrawal = $withdrawal;
        $this->user = $user;
        $this->email = $email;
        $this->subject = $subject;
        $this->isAdmin = $isAdmin;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->email)->send(
                new WithdrawalStatus(
                    $this->withdrawal,
                    $this->user,
                    $this->subject,
                    $this->isAdmin
                )
            );

            Log::info('Transfer email sent successfully', [
                'email' => $this->email,
                'txn_id' => $this->withdrawal->txn_id,
                'is_admin' => $this->isAdmin
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send transfer email', [
                'email' => $this->email,
                'txn_id' => $this->withdrawal->txn_id,
                'error' => $e->getMessage()
            ]);

            // Re-throw to trigger retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param \Exception $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::critical('Transfer email job failed after all retries', [
            'email' => $this->email,
            'txn_id' => $this->withdrawal->txn_id,
            'error' => $exception->getMessage()
        ]);
    }
}
