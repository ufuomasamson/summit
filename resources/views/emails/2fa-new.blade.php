@extends('emails.layouts.master')

@section('subject', 'Your Transaction Verification Code')

@section('preheader', 'Your one-time verification code for ' . $settings->site_name)

@section('content')
<h1 class="greeting">
    Transaction Verification
</h1>

<p class="intro-text">
    Hello {{ $user->name }},
</p>

<p class="intro-text">
    A one-time verification code has been requested for your {{ $settings->site_name }} account. Enter the code below to authorize your transaction.
</p>

<div class="notice notice-info">
    <strong>&#128274; Verification Required</strong><br>
    This code was requested to verify your identity and authorize your transaction.
</div>

<!-- OTP Code Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 28px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 24px 48px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 10px 0; text-transform: uppercase; letter-spacing: 2px;">
                            YOUR OTP CODE
                        </p>
                        <p style="color: #ffffff; font-size: 38px; font-weight: 700; margin: 0; letter-spacing: 10px; font-family: 'Courier New', monospace;">
                            {{ $code ?? '000000' }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="details-card">
    <h3>Verification Details</h3>
    <div class="detail-row">
        <span class="detail-label">Account</span>
        <span class="detail-value">{{ $user->email }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Requested On</span>
        <span class="detail-value">{{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">IP Address</span>
        <span class="detail-value">{{ request()->ip() ?? 'Not Available' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Valid For</span>
        <span class="detail-value">10 minutes</span>
    </div>
</div>

<div class="notice notice-caution">
    <strong>&#9201; Time-Sensitive</strong><br>
    This code expires in 10 minutes. If it expires, you will need to request a new one.
</div>

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Important Security Information</strong>
    </p>
    <ul style="margin-top: 6px; padding-left: 20px; color: #78350f; font-size: 13px; line-height: 1.7;">
        <li><strong>Never share this code</strong> with anyone, including {{ $settings->site_name }} support staff</li>
        <li>This code is for one-time use only</li>
        <li>Only enter this code on the official {{ $settings->site_name }} website</li>
        <li>We will never ask for your code via email or phone</li>
    </ul>
</div>

<div class="notice notice-urgent">
    <strong>&#9888; Didn't Request This Code?</strong><br>
    If you did not initiate this transaction, someone may be trying to access your account. Please:
    <ul style="margin-top: 6px; padding-left: 20px;">
        <li>Change your password immediately</li>
        <li>Contact our security team at {{ $settings->contact_email }}</li>
        <li>Review your recent account activity</li>
    </ul>
</div>

<div class="closing">
    <p>Your security is our priority.</p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }} Security Team</strong>
    </p>
</div>
@endsection
