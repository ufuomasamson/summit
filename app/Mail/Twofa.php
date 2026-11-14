<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Twofa extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @var Demo
     */

    public $demo, $settings, $user, $code;

    public function __construct($demo, $settings = null)
    {
        $this->demo = $demo;
        $this->settings = $settings ?? \App\Models\Settings::first();
        
        // Map variables for new template
        $this->user = $demo->user ?? $demo;
        $this->code = $demo->code ?? $demo->token ?? '';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.2fa-new')->subject($this->demo->subject);
    }
}
