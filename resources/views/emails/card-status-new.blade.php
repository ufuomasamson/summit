@extends('emails.layouts.master')

@section('subject', 'Virtual Card Status - ' . ($status ?? 'Update'))

@section('preheader')
@if(isset($status))
@if($status == 'issued') Your virtual card is active and ready to use
@elseif($status == 'approved') Your virtual card application has been approved
@elseif($status == 'pending') Your virtual card application is being reviewed
@elseif($status == 'rejected') Update on your virtual card application
@elseif($status == 'blocked') Important: Your virtual card has been blocked
@elseif($status == 'expired') Your virtual card has expired
@endif
@endif
@endsection

@section('content')
<h1 class="greeting">
    Virtual Card {{ isset($status) && $status == 'issued' ? 'Issued' : 'Status Update' }}
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    This notification is regarding your virtual card application with {{ $settings->site_name }}.
</p>

@if(isset($status))
    @if($status == 'approved')
        <div class="notice notice-confirm">
            <strong>&#10003; Virtual Card Approved</strong><br>
            Your application has been approved. Card details will be available shortly.
        </div>
    @elseif($status == 'issued')
        <div class="notice notice-confirm">
            <strong>&#10003; Virtual Card Issued</strong><br>
            Your virtual card is active and ready for online transactions worldwide.
        </div>
    @elseif($status == 'pending')
        <div class="notice notice-caution">
            <strong>&#9202; Application Under Review</strong><br>
            Your virtual card application is being processed. We will notify you once it is ready.
        </div>
    @elseif($status == 'rejected')
        <div class="notice notice-urgent">
            <strong>Application Update</strong><br>
            We are unable to process your virtual card application at this time.
        </div>
    @elseif($status == 'blocked')
        <div class="notice notice-urgent">
            <strong>&#9888; Card Blocked</strong><br>
            Your virtual card has been temporarily blocked for security reasons.
        </div>
    @elseif($status == 'expired')
        <div class="notice notice-caution">
            <strong>&#9888; Card Expired</strong><br>
            Your virtual card has expired. Apply for a new card to continue making transactions.
        </div>
    @endif
@endif

@if(isset($status) && in_array($status, ['approved', 'issued']))
<div class="details-card">
    <h3>Card Information</h3>
    @if(isset($card_type))
    <div class="detail-row">
        <span class="detail-label">Card Type</span>
        <span class="detail-value">{{ $card_type }}</span>
    </div>
    @endif
    @if(isset($card_brand))
    <div class="detail-row">
        <span class="detail-label">Card Brand</span>
        <span class="detail-value">{{ $card_brand }}</span>
    </div>
    @endif
    @if(isset($card_name))
    <div class="detail-row">
        <span class="detail-label">Cardholder Name</span>
        <span class="detail-value">{{ $card_name }}</span>
    </div>
    @endif
    @if(isset($card_number) && $status == 'issued')
    <div class="detail-row">
        <span class="detail-label">Card Number</span>
        <span class="detail-value" style="font-family: 'Courier New', monospace; letter-spacing: 2px;">{{ $card_number }}</span>
    </div>
    @endif
    @if(isset($expiry_date))
    <div class="detail-row">
        <span class="detail-label">Expiry Date</span>
        <span class="detail-value">{{ $expiry_date }}</span>
    </div>
    @endif
    @if(isset($cvv) && $status == 'issued')
    <div class="detail-row">
        <span class="detail-label">CVV</span>
        <span class="detail-value" style="font-family: 'Courier New', monospace;">{{ $cvv }}</span>
    </div>
    @endif
    @if(isset($card_limit))
    <div class="detail-row">
        <span class="detail-label">Card Limit</span>
        <span class="detail-value highlight-amount">{{ $settings->currency }}{{ number_format($card_limit, 2) }}</span>
    </div>
    @endif
    @if(isset($available_balance))
    <div class="detail-row">
        <span class="detail-label">Available Balance</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($available_balance, 2) }}</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">Card Status</span>
        <span class="detail-value">
            @if($status == 'issued')
                <span class="status-badge status-success">Active</span>
            @else
                <span class="status-badge status-pending">{{ ucfirst($status) }}</span>
            @endif
        </span>
    </div>
</div>

@if($status == 'issued')
<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Keep Your Card Details Secure</strong>
    </p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; color: #78350f; font-size: 13px;">
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; <strong>Never share</strong> your card number, CVV, or expiry date</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Use your card only on <strong>secure websites</strong> (HTTPS)</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Enable <strong>transaction alerts</strong> to monitor all activities</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Report <strong>unauthorized transactions</strong> immediately</td></tr>
    </table>
</div>

<div class="details-card">
    <h3>Card Features</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Global Acceptance</strong> &mdash; Millions of websites worldwide</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Instant Processing</strong> &mdash; Real-time payment confirmation</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>3D Secure</strong> &mdash; Additional authentication layer</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>Transaction History</strong> &mdash; Track all purchases in your dashboard</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; <strong>24/7 Monitoring</strong> &mdash; Fraud detection and prevention</td></tr>
    </table>
</div>

<div class="btn-container">
    <a href="{{ route('user.cards') }}" class="btn-primary">View Card Details</a>
</div>

<div class="details-card">
    <h3>Getting Started</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #fff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">1</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Fund Your Card</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Transfer funds from your main account to your card balance.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #fff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">2</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Make a Purchase</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Use your card details at checkout on any website accepting {{ $card_brand ?? 'your card brand' }}.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 12px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #fff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">3</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Monitor Transactions</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Track all card activities in real-time through your dashboard.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="notice notice-info">
    <strong>&#128161; Tip</strong><br>
    Set up transaction alerts for instant notifications on every card purchase. You can also manage spending limits and block/unblock your card from your dashboard.
</div>
@endif
@endif

@if(isset($status) && $status == 'pending')
<div class="details-card">
    <h3>What Happens Next</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Account information verification</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Card application processing</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Unique card details generation</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Estimated completion: 24&ndash;48 hours</td></tr>
    </table>
</div>

<div class="notice notice-info">
    <strong>&#128203; Requirements</strong><br>
    @if(isset($requirements_met) && $requirements_met)
        All requirements have been met. Your application is being processed.
    @else
        Please ensure you have completed all required steps:
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; font-size: 14px;">
            <tr><td style="padding: 3px 0;">&#8226;&nbsp; KYC verification completed</td></tr>
            <tr><td style="padding: 3px 0;">&#8226;&nbsp; Account in good standing</td></tr>
            <tr><td style="padding: 3px 0;">&#8226;&nbsp; Sufficient balance for card fee (if applicable)</td></tr>
        </table>
    @endif
</div>
@endif

@if(isset($status) && $status == 'rejected')
<div class="details-card">
    <h3>Reason for Decision</h3>
    <p style="color: #475569; line-height: 1.8; padding: 12px 0;">
        @if(isset($rejection_reason))
            {{ $rejection_reason }}
        @else
            Your virtual card application could not be processed. Common reasons include incomplete KYC verification, insufficient account balance, or account restrictions. Please contact support for details.
        @endif
    </p>
</div>

<div class="details-card">
    <h3>Recommended Actions</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Complete your KYC verification if pending</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Ensure your account has sufficient balance</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Review and resolve any account restrictions</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Contact support for specific guidance</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Reapply once all requirements are met</td></tr>
    </table>
</div>
@endif

@if(isset($status) && $status == 'blocked')
<div class="details-card">
    <h3>Security Block Information</h3>
    <p style="color: #475569; line-height: 1.8; padding: 12px 0;">
        @if(isset($block_reason))
            <strong>Reason:</strong> {{ $block_reason }}
        @else
            Your card has been temporarily blocked as a security precaution. This may be due to suspicious activity, multiple failed transaction attempts, or a security alert on your account.
        @endif
    </p>
</div>

<div class="notice notice-urgent">
    <strong>&#9888; Immediate Action Required</strong><br>
    To unblock your virtual card:
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; font-size: 14px;">
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Contact our security team immediately</td></tr>
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Verify recent transactions on your account</td></tr>
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Provide any requested identity verification</td></tr>
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Review your account security settings</td></tr>
    </table>
</div>

<div class="btn-container">
    <a href="{{ route('user.cards') }}" class="btn-primary">Contact Support</a>
</div>
@endif

@if(isset($status) && $status == 'expired')
<div class="notice notice-info">
    <strong>&#128260; Renew Your Virtual Card</strong><br>
    Your card has reached its expiration date. Apply for a new virtual card to continue enjoying seamless online payments. The application process is quick and your new card will be ready within 24 hours.
</div>

<div class="btn-container">
    <a href="{{ route('user.cards') }}" class="btn-primary">Apply for New Card</a>
</div>
@endif

@if(isset($transaction_summary))
<div class="details-card">
    <h3>Recent Activity Summary</h3>
    <div class="detail-row">
        <span class="detail-label">Total Transactions</span>
        <span class="detail-value">{{ $transaction_summary['total_count'] ?? 0 }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Total Spent</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($transaction_summary['total_spent'] ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Last Transaction</span>
        <span class="detail-value">{{ $transaction_summary['last_transaction_date'] ?? 'N/A' }}</span>
    </div>
</div>
@endif

<div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin: 24px 0;">
    <p style="color: #64748b; font-size: 13px; line-height: 1.6; margin: 0; text-align: center;">
        <strong>Need Help with Your Virtual Card?</strong><br>
        Email: <strong style="color: #c10e0e;">{{ $settings->contact_email }}</strong>
        @if($settings->phone)
        &nbsp;|&nbsp; Phone: <strong style="color: #c10e0e;">{{ $settings->phone }}</strong>
        @endif
    </p>
</div>

<div class="closing">
    <p>
        @if(isset($status) && $status == 'issued')
            Enjoy convenient and secure online shopping with your new virtual card.
        @else
            Thank you for choosing {{ $settings->site_name }} virtual card services.
        @endif
    </p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
