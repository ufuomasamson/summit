<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Settings;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $code, $settings = null)
    {
        $this->user = $user;
        $this->code = $code;
        $this->settings = $settings ?? Settings::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.verify-email')
            ->with([
                'user' => $this->user,
                'code' => $this->code,
                'settings' => $this->settings,
            ]);
    }
}
