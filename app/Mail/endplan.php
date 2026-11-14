<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class endplan extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @var Demo
     */
    public $demo, $settings, $user, $plan_name, $plan_amount, $total_roi;

    public function __construct($demo, $settings = null)
    {
        $this->demo = $demo;
        $this->settings = $settings ?? \App\Models\Settings::first();
        
        // Map variables for new template
        $this->user = $demo->user ?? $demo;
        $this->plan_name = $demo->plan ?? 'Investment Plan';
        $this->plan_amount = $demo->amount ?? 0;
        $this->total_roi = $demo->profit ?? 0;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.plan-completed-new')->subject($this->demo->subject);
    }
}
