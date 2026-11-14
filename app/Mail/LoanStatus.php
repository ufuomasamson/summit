<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanStatus extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user, $status, $settings, $loan_amount, $reference, $loan_type, $duration;
    public $interest_rate, $application_date, $approved_amount, $monthly_payment;
    public $total_repayment, $first_payment_date, $disbursement_date, $previous_balance;
    public $rejection_reason, $required_documents;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user, 
        $status, 
        $settings = null,
        $loan_amount = null,
        $reference = null,
        $loan_type = null,
        $duration = null,
        $interest_rate = null,
        $application_date = null,
        $approved_amount = null,
        $monthly_payment = null,
        $total_repayment = null,
        $first_payment_date = null,
        $disbursement_date = null,
        $previous_balance = null,
        $rejection_reason = null,
        $required_documents = null
    ) {
        $this->user = $user;
        $this->status = $status;
        $this->settings = $settings ?? \App\Models\Settings::first();
        $this->loan_amount = $loan_amount;
        $this->reference = $reference;
        $this->loan_type = $loan_type;
        $this->duration = $duration;
        $this->interest_rate = $interest_rate;
        $this->application_date = $application_date;
        $this->approved_amount = $approved_amount;
        $this->monthly_payment = $monthly_payment;
        $this->total_repayment = $total_repayment;
        $this->first_payment_date = $first_payment_date;
        $this->disbursement_date = $disbursement_date;
        $this->previous_balance = $previous_balance;
        $this->rejection_reason = $rejection_reason;
        $this->required_documents = $required_documents;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.loan-status-new')
                    ->subject('Loan Application Status - ' . ucfirst($this->status ?? 'Update'));
    }
}
