<?php

namespace App\Mail;

use App\Models\Tp_Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewRoi extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * Create a new message instance.
     *
     * @var Demo
     */
    public $plan, $amount, $plandate, $user, $subject, $settings;
    public $roi_amount, $plan_name, $previous_balance;

    public function __construct(User $user, $plan, $amount, $plandate, $subject, $settings = null)
    {
        $this->plan = $plan;
        $this->user = $user;
        $this->subject = $subject;
        $this->amount = $amount;
        $this->plandate = $plandate;
        $this->settings = $settings ?? \App\Models\Settings::first();
        
        // Map variables for new template
        $this->roi_amount = $amount;
        $this->plan_name = is_object($plan) ? $plan->name : $plan;
        $this->previous_balance = $user->balance - $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.roi-new')->subject($this->subject);
    }
}
