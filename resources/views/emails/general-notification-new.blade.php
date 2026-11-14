@extends('emails.layouts.master')

@section('subject', $subject ?? 'New Notification from ' . $settings->site_name)

@section('preheader', isset($message) ? Str::limit(strip_tags($message), 100) : 'You have a new notification from ' . $settings->site_name)

@section('content')
<h1 class="greeting">
    @if(isset($title))
        {{ $title }}
    @else
        Notification
    @endif
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

@if(isset($message))
<p class="intro-text">
    {!! nl2br(e($message)) !!}
</p>
@endif

@if(isset($type))
    @if($type == 'success')
        <div class="notice notice-confirm">
            <strong>&#10003; Success</strong><br>
            @if(isset($alert_message))
                {{ $alert_message }}
            @else
                Your request has been processed successfully.
            @endif
        </div>
    @elseif($type == 'warning')
        <div class="notice notice-caution">
            <strong>&#9888; Attention Required</strong><br>
            @if(isset($alert_message))
                {{ $alert_message }}
            @else
                This notification requires your attention.
            @endif
        </div>
    @elseif($type == 'error')
        <div class="notice notice-urgent">
            <strong>&#9888; Action Required</strong><br>
            @if(isset($alert_message))
                {{ $alert_message }}
            @else
                Immediate action is required on your account.
            @endif
        </div>
    @elseif($type == 'info')
        <div class="notice notice-info">
            <strong>&#8505; Information</strong><br>
            @if(isset($alert_message))
                {{ $alert_message }}
            @else
                This is an informational notification.
            @endif
        </div>
    @endif
@endif

@if(isset($details) && is_array($details) && count($details) > 0)
<div class="details-card">
    <h3>Details</h3>
    @foreach($details as $label => $value)
    <div class="detail-row">
        <span class="detail-label">{{ $label }}</span>
        <span class="detail-value">{{ $value }}</span>
    </div>
    @endforeach
</div>
@endif

@if(isset($amount))
<!-- Amount Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 20px 40px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">Amount</p>
                        <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                            {{ $settings->currency }}{{ number_format($amount, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

@if(isset($transaction_details) && is_array($transaction_details) && count($transaction_details) > 0)
<div class="details-card">
    <h3>Transaction Information</h3>
    @foreach($transaction_details as $label => $value)
    <div class="detail-row">
        <span class="detail-label">{{ $label }}</span>
        <span class="detail-value">{{ $value }}</span>
    </div>
    @endforeach
    <div class="detail-row">
        <span class="detail-label">Date &amp; Time</span>
        <span class="detail-value">{{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</span>
    </div>
</div>
@endif

@if(isset($action_required) && $action_required)
<div class="notice notice-caution">
    <strong>&#9888; Action Required</strong><br>
    @if(isset($action_message))
        {{ $action_message }}
    @else
        Please review this notification and take necessary action.
    @endif
</div>
@endif

@if(isset($button_text) && isset($button_url))
<div class="btn-container">
    <a href="{{ $button_url }}" class="btn-primary">{{ $button_text }}</a>
</div>
@endif

@if(isset($list_items) && is_array($list_items) && count($list_items) > 0)
<div class="details-card">
    <h3>{{ $list_title ?? 'Important Information' }}</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        @foreach($list_items as $item)
        <tr>
            <td style="padding: 6px 0;">&#10003;&nbsp; {{ $item }}</td>
        </tr>
        @endforeach
    </table>
</div>
@endif

@if(isset($steps) && is_array($steps) && count($steps) > 0)
<div class="details-card">
    <h3>{{ $steps_title ?? 'Next Steps' }}</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        @foreach($steps as $index => $step)
        <tr>
            <td style="padding: 12px 0;{{ !$loop->last ? ' border-bottom: 1px solid #f1f5f9;' : '' }}">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #ffffff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">{{ $index + 1 }}</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">{{ $step['title'] ?? $step }}</strong>
                            @if(isset($step['description']))
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">{{ $step['description'] }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endif

@if(isset($security_notice))
<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Security Reminder</strong><br>
        <span style="font-size: 13px;">{{ $security_notice }}</span>
    </p>
</div>
@endif

@if(isset($additional_info))
<div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin: 24px 0;">
    <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0;">
        {!! nl2br(e($additional_info)) !!}
    </p>
</div>
@endif

@if(isset($contact_support) && $contact_support)
<div class="notice notice-info">
    <strong>&#128172; Need Assistance?</strong><br>
    Our support team is available to help:
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; font-size: 14px;">
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Email: {{ $settings->contact_email }}</td></tr>
        @if($settings->phone)
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Phone: {{ $settings->phone }}</td></tr>
        @endif
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Live Chat available from your dashboard</td></tr>
    </table>
</div>
@endif

<div class="closing">
    <p>
        @if(isset($closing_message))
            {{ $closing_message }}
        @else
            Thank you for banking with {{ $settings->site_name }}.
        @endif
    </p>
    <p class="signature">
        @if(isset($signature))
            {!! $signature !!}
        @else
            Best regards,<br>
            <strong>{{ $settings->site_name }}</strong>
        @endif
    </p>
</div>
@endsection
