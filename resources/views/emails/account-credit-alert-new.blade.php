@extends('emails.layouts.master')

@section('subject', 'Credit Alert - ' . $settings->currency . number_format($amount, 2) . ' Received')

@section('preheader', 'You received ' . $settings->currency . number_format($amount, 2) . ' on ' . \Carbon\Carbon::now()->format('M j, Y'))

@section('content')
<h1 class="greeting">
    Account Credit Alert
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    A credit of <strong>{{ $settings->currency }}{{ number_format($amount, 2) }}</strong> has been posted to your {{ $settings->site_name }} account. Details of this transaction are provided below for your records.
</p>

<div class="notice notice-confirm">
    <strong>&#10003; Credit Successful</strong><br>
    Your account balance has been updated with the credited amount.
</div>

<!-- Amount Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 28px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 24px 48px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 2px;">
                            Amount Credited
                        </p>
                        <p style="color: #ffffff; font-size: 40px; font-weight: 700; margin: 0; letter-spacing: 1px; font-family: 'Georgia', serif;">
                            {{ $settings->currency }}{{ number_format($amount, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="details-card">
    <h3>Transaction Details</h3>
    <div class="detail-row">
        <span class="detail-label">Account Holder</span>
        <span class="detail-value">{{ $user->name }} {{ $user->lastname }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Account Number</span>
        <span class="detail-value">{{ $user->usernumber }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Amount Credited</span>
        <span class="detail-value amount-credit">{{ $settings->currency }}{{ number_format($amount, 2) }}</span>
    </div>
    @if(isset($reference))
    <div class="detail-row">
        <span class="detail-label">Reference</span>
        <span class="detail-value">{{ $reference }}</span>
    </div>
    @endif
    @if(isset($narration))
    <div class="detail-row">
        <span class="detail-label">Narration</span>
        <span class="detail-value">{{ $narration }}</span>
    </div>
    @endif
    @if(isset($sender_name))
    <div class="detail-row">
        <span class="detail-label">Sender</span>
        <span class="detail-value">{{ $sender_name }}</span>
    </div>
    @endif
    @if(isset($sender_account))
    <div class="detail-row">
        <span class="detail-label">Sender Account</span>
        <span class="detail-value">{{ $sender_account }}</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">Transaction Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</span>
    </div>
</div>

<div class="details-card">
    <h3>Balance Summary</h3>
    <div class="detail-row">
        <span class="detail-label">Previous Balance</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($previous_balance ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Credit</span>
        <span class="detail-value amount-credit">+{{ $settings->currency }}{{ number_format($amount, 2) }}</span>
    </div>
    <div class="detail-row" style="border-top: 2px solid #e2e8f0; padding-top: 14px;">
        <span class="detail-label"><strong>Available Balance</strong></span>
        <span class="detail-value highlight-amount"><strong>{{ $settings->currency }}{{ number_format(($previous_balance ?? 0) + $amount, 2) }}</strong></span>
    </div>
</div>

<div class="btn-container">
    <a href="{{ route('dashboard') }}" class="btn-primary">View Account</a>
</div>

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Security Reminder</strong>
    </p>
    <p style="margin-top: 6px; color: #78350f; font-size: 13px; line-height: 1.6;">
        If you did not authorize this transaction or notice suspicious activity, contact our support team immediately at <strong>{{ $settings->contact_email }}</strong>@if($settings->phone) or call <strong>{{ $settings->phone }}</strong>@endif.
    </p>
</div>

<div class="closing">
    <p>Thank you for banking with {{ $settings->site_name }}.</p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
