@extends('emails.layouts.master')

@section('subject', 'Investment Plan Completed - ' . ($plan_name ?? 'Your Investment Plan'))

@section('preheader', 'Your investment plan has reached maturity. Total profit: ' . $settings->currency . number_format($total_roi ?? 0, 2))

@section('content')
<h1 class="greeting">
    Investment Plan Maturity
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    Your investment plan with {{ $settings->site_name }} has reached maturity. Below is a summary of your investment performance.
</p>

<div class="notice notice-confirm">
    <strong>&#10003; Investment Completed</strong><br>
    Your investment term has ended. All final returns have been credited to your account.
</div>

<div class="details-card">
    <h3>Investment Plan Details</h3>
    <div class="detail-row">
        <span class="detail-label">Plan Name</span>
        <span class="detail-value">{{ $plan_name ?? 'Investment Plan' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Plan Status</span>
        <span class="detail-value"><span class="status-badge status-success">Completed</span></span>
    </div>
    @if(isset($start_date))
    <div class="detail-row">
        <span class="detail-label">Start Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($start_date)->format('F j, Y') }}</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">Maturity Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::now()->format('F j, Y') }}</span>
    </div>
    @if(isset($duration))
    <div class="detail-row">
        <span class="detail-label">Investment Duration</span>
        <span class="detail-value">{{ $duration }}</span>
    </div>
    @endif
    @if(isset($roi_percentage))
    <div class="detail-row">
        <span class="detail-label">Return Rate</span>
        <span class="detail-value">{{ $roi_percentage }}%</span>
    </div>
    @endif
</div>

<div class="details-card">
    <h3>Financial Summary</h3>
    <div class="detail-row">
        <span class="detail-label">Initial Investment</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($plan_amount ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Total ROI Earned</span>
        <span class="detail-value amount-credit">+{{ $settings->currency }}{{ number_format($total_roi ?? 0, 2) }}</span>
    </div>
    @if(isset($total_payments))
    <div class="detail-row">
        <span class="detail-label">Number of Payments</span>
        <span class="detail-value">{{ $total_payments }}</span>
    </div>
    @endif
    <div style="border-top: 2px solid #c10e0e; margin: 16px 0; padding-top: 16px;">
        <div class="detail-row">
            <span class="detail-label"><strong>Total Value</strong></span>
            <span class="detail-value" style="font-weight: 700; font-size: 16px;">{{ $settings->currency }}{{ number_format(($plan_amount ?? 0) + ($total_roi ?? 0), 2) }}</span>
        </div>
    </div>
    @if(isset($plan_amount) && isset($total_roi) && $plan_amount > 0)
    <div class="detail-row">
        <span class="detail-label">Total Gain</span>
        <span class="detail-value amount-credit" style="font-weight: 700;">+{{ number_format((($total_roi ?? 0) / $plan_amount) * 100, 2) }}%</span>
    </div>
    @endif
</div>

<!-- Total Profit Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 20px 40px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">Total Profit Earned</p>
                        <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                            {{ $settings->currency }}{{ number_format($total_roi ?? 0, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="details-card">
    <h3>What Happens Next</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Funds Available</strong> &mdash; Principal and returns are in your account</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Withdraw Anytime</strong> &mdash; Transfer funds to your bank account</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Reinvest for Growth</strong> &mdash; Start a new plan with your returns</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>No Pending Obligations</strong> &mdash; Plan is fully settled</td></tr>
    </table>
</div>

<div class="btn-container">
    <a href="{{ route('user.myplans') }}" class="btn-primary">View Investment History</a>
</div>

<div class="notice notice-info">
    <strong>&#128200; Continue Growing</strong><br>
    Explore other investment plans with competitive returns and flexible terms. Compound your earnings by reinvesting your returns.
</div>

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Investment Statement</strong><br>
        <span style="font-size: 13px;">A detailed statement of your completed investment, including all ROI payments and transaction history, is available in your dashboard.</span>
    </p>
</div>

<div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin: 24px 0;">
    <p style="color: #64748b; font-size: 13px; line-height: 1.6; margin: 0; text-align: center;">
        <strong>Need Assistance?</strong><br>
        Contact us at <strong style="color: #c10e0e;">{{ $settings->contact_email }}</strong>
        @if($settings->phone)
        &nbsp;|&nbsp; <strong style="color: #c10e0e;">{{ $settings->phone }}</strong>
        @endif
    </p>
</div>

<div class="closing">
    <p>Congratulations on the successful completion of your investment. Thank you for choosing {{ $settings->site_name }}.</p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
