@extends('emails.layouts.master')

@section('subject', 'Investment Return - ' . $settings->currency . number_format($roi_amount ?? 0, 2) . ' Earned')

@section('preheader', 'Your investment has earned ' . $settings->currency . number_format($roi_amount ?? 0, 2) . ' in returns')

@section('content')
<h1 class="greeting">
    Investment Return Notification
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    Your investment with {{ $settings->site_name }} has generated returns. The ROI has been credited to your account.
</p>

<div class="notice notice-confirm">
    <strong>&#10003; ROI Credited</strong><br>
    Your investment returns have been added to your account balance.
</div>

<!-- Amount Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 20px 40px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">ROI Earned</p>
                        <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                            {{ $settings->currency }}{{ number_format($roi_amount ?? 0, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="details-card">
    <h3>Investment Performance</h3>
    <div class="detail-row">
        <span class="detail-label">Plan Name</span>
        <span class="detail-value">{{ $plan_name ?? 'Investment Plan' }}</span>
    </div>
    @if(isset($plan_amount))
    <div class="detail-row">
        <span class="detail-label">Investment Amount</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($plan_amount, 2) }}</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">ROI Amount</span>
        <span class="detail-value amount-credit">{{ $settings->currency }}{{ number_format($roi_amount ?? 0, 2) }}</span>
    </div>
    @if(isset($roi_percentage))
    <div class="detail-row">
        <span class="detail-label">Return Rate</span>
        <span class="detail-value">{{ $roi_percentage }}%</span>
    </div>
    @endif
    @if(isset($duration))
    <div class="detail-row">
        <span class="detail-label">Investment Period</span>
        <span class="detail-value">{{ $duration }}</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">Credit Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::now()->format('F j, Y \a\t g:i A') }}</span>
    </div>
    @if(isset($next_roi_date))
    <div class="detail-row">
        <span class="detail-label">Next ROI Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($next_roi_date)->format('F j, Y') }}</span>
    </div>
    @endif
</div>

<div class="details-card">
    <h3>Balance Summary</h3>
    <div class="detail-row">
        <span class="detail-label">Previous Balance</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($previous_balance ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">ROI Credited</span>
        <span class="detail-value amount-credit">+{{ $settings->currency }}{{ number_format($roi_amount ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">New Balance</span>
        <span class="detail-value" style="font-weight: 700;">{{ $settings->currency }}{{ number_format(($previous_balance ?? 0) + ($roi_amount ?? 0), 2) }}</span>
    </div>
</div>

<div class="btn-container">
    <a href="{{ route('user.myplans') }}" class="btn-primary">View My Investments</a>
</div>

@if(isset($plan_status) && $plan_status == 'active')
<div class="notice notice-info">
    <strong>&#128200; Investment Active</strong><br>
    Your investment plan is still running. You will continue to receive ROI payments according to your plan schedule until maturity.
</div>
@endif

<div class="details-card">
    <h3>Options</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Reinvest Returns</strong> &mdash; Compound your earnings</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Withdraw to Bank</strong> &mdash; Transfer ROI to your external account</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Explore New Plans</strong> &mdash; Discover other investment opportunities</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Track Performance</strong> &mdash; View detailed reports in your dashboard</td></tr>
    </table>
</div>

@if(isset($total_earned))
<div style="background: #f8fafc; padding: 16px; border-radius: 8px; text-align: center; margin: 24px 0;">
    <p style="color: #64748b; font-size: 11px; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 1px;">
        Total Earnings to Date
    </p>
    <p style="color: #c10e0e; font-size: 28px; font-weight: 700; margin: 0;">
        {{ $settings->currency }}{{ number_format($total_earned, 2) }}
    </p>
</div>
@endif

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Investment Security</strong><br>
        <span style="font-size: 13px;">Your investments are managed with the highest level of security and transparency. All ROI payments are automatically processed according to your plan terms. Contact us at {{ $settings->contact_email }} with any questions.</span>
    </p>
</div>

<div class="closing">
    <p>Thank you for investing with {{ $settings->site_name }}.</p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
