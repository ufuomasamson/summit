<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GrantStatus extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user, $status, $settings, $grant_amount, $reference, $grant_name, $category;
    public $application_date, $approved_amount, $disbursement_date, $grant_period;
    public $terms, $previous_balance, $rejection_reason, $requirements;
    public $reporting_required, $submitted_documents, $usage_restrictions;
    public $contact_officer, $officer_email;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        User $user, 
        $status, 
        $settings = null,
        $grant_amount = null,
        $reference = null,
        $grant_name = null,
        $category = null,
        $application_date = null,
        $approved_amount = null,
        $disbursement_date = null,
        $grant_period = null,
        $terms = null,
        $previous_balance = null,
        $rejection_reason = null,
        $requirements = null,
        $reporting_required = false,
        $submitted_documents = null,
        $usage_restrictions = null,
        $contact_officer = null,
        $officer_email = null
    ) {
        $this->user = $user;
        $this->status = $status;
        $this->settings = $settings ?? \App\Models\Settings::first();
        $this->grant_amount = $grant_amount;
        $this->reference = $reference;
        $this->grant_name = $grant_name;
        $this->category = $category;
        $this->application_date = $application_date;
        $this->approved_amount = $approved_amount;
        $this->disbursement_date = $disbursement_date;
        $this->grant_period = $grant_period;
        $this->terms = $terms;
        $this->previous_balance = $previous_balance;
        $this->rejection_reason = $rejection_reason;
        $this->requirements = $requirements;
        $this->reporting_required = $reporting_required;
        $this->submitted_documents = $submitted_documents;
        $this->usage_restrictions = $usage_restrictions;
        $this->contact_officer = $contact_officer;
        $this->officer_email = $officer_email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.grant-status-new')
                    ->subject('Grant Application Status - ' . ucfirst($this->status ?? 'Update'));
    }
}
