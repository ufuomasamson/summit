<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Settings;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $actionUrl;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $actionUrl, $settings = null)
    {
        $this->user = $user;
        $this->actionUrl = $actionUrl;
        $this->settings = $settings ?? Settings::first();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.reset-password')
            ->with([
                'user' => $this->user,
                'actionUrl' => $this->actionUrl,
                'settings' => $this->settings,
            ]);
    }
}
