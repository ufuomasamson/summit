<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountCreditAlert extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user, $amount, $settings, $previous_balance, $reference, $narration, $sender_name, $sender_account;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $amount, $settings = null, $previous_balance = null, $reference = null, $narration = null, $sender_name = null, $sender_account = null)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->settings = $settings ?? \App\Models\Settings::first();
        $this->previous_balance = $previous_balance ?? $user->account_bal;
        $this->reference = $reference;
        $this->narration = $narration;
        $this->sender_name = $sender_name;
        $this->sender_account = $sender_account;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.account-credit-alert-new')
                    ->subject('Account Credit Alert - ' . $this->settings->currency . number_format($this->amount, 2) . ' Received');
    }
}
