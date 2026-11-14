<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $url, $attachment, $body, $subject, $recipient, $salutaion, $settings, $user, $message, $button_text, $button_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($body, $subject, $recipient, $url = null, $attachment = null, $salutaion = null, $settings = null)
    {
        $this->url = $url;
        $this->attachment = $attachment;
        $this->body = $body;
        $this->subject = $subject;
        $this->recipient = $recipient;
        $this->salutaion = $salutaion;
        $this->settings = $settings ?? \App\Models\Settings::first();
        
        // Map variables for new template
        $this->message = $body;
        $this->button_text = $url ? 'View Details' : null;
        $this->button_url = $url;
        
        // Try to get user from recipient email
        $this->user = \App\Models\User::where('email', $recipient)->first() ?? (object)['name' => 'User', 'email' => $recipient];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.general-notification-new',[
            'url' => $this->url,
            'attachment' => $this->attachment,
            'body' => $this->body,
            'recipient' => $this->recipient,
        ])
        ->subject($this->subject);
    }
}
