<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardStatus extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user, $status, $settings, $card_type, $card_brand, $card_name;
    public $card_number, $expiry_date, $cvv, $card_limit, $available_balance;
    public $rejection_reason, $block_reason, $requirements_met;
    public $transaction_summary;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user, 
        $status, 
        $settings = null,
        $card_type = null,
        $card_brand = null,
        $card_name = null,
        $card_number = null,
        $expiry_date = null,
        $cvv = null,
        $card_limit = null,
        $available_balance = null,
        $rejection_reason = null,
        $block_reason = null,
        $requirements_met = true,
        $transaction_summary = null
    ) {
        $this->user = $user;
        $this->status = $status;
        $this->settings = $settings ?? \App\Models\Settings::first();
        $this->card_type = $card_type;
        $this->card_brand = $card_brand;
        $this->card_name = $card_name;
        $this->card_number = $card_number;
        $this->expiry_date = $expiry_date;
        $this->cvv = $cvv;
        $this->card_limit = $card_limit;
        $this->available_balance = $available_balance;
        $this->rejection_reason = $rejection_reason;
        $this->block_reason = $block_reason;
        $this->requirements_met = $requirements_met;
        $this->transaction_summary = $transaction_summary;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $statusText = $this->status == 'issued' ? 'Issued' : ucfirst($this->status ?? 'Update');
        return $this->markdown('emails.card-status-new')
                    ->subject('Virtual Card Status - ' . $statusText);
    }
}
