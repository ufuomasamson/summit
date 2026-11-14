@extends('emails.layouts.master')

@section('subject', 'Loan Application Status - ' . ($status ?? 'Update'))

@section('preheader')
@if(isset($status))
@if($status == 'approved') Your loan application has been approved
@elseif($status == 'pending') Your loan application is under review
@elseif($status == 'rejected') Update on your loan application
@elseif($status == 'disbursed') Loan funds have been credited to your account
@endif
@endif
@endsection

@section('content')
<h1 class="greeting">
    Loan Application Update
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    This is regarding the status of your loan application with {{ $settings->site_name }}.
</p>

@if(isset($status))
    @if($status == 'approved')
        <div class="notice notice-confirm">
            <strong>&#10003; Loan Approved</strong><br>
            Your loan application has been reviewed and approved. Funds will be disbursed as per the terms below.
        </div>
    @elseif($status == 'pending')
        <div class="notice notice-caution">
            <strong>&#9202; Application Under Review</strong><br>
            Your loan application is being processed. We will notify you once a decision has been made.
        </div>
    @elseif($status == 'rejected')
        <div class="notice notice-urgent">
            <strong>Application Decision</strong><br>
            After careful review, we are unable to approve your loan application at this time.
        </div>
    @elseif($status == 'disbursed')
        <div class="notice notice-confirm">
            <strong>&#10003; Loan Disbursed</strong><br>
            Your approved loan amount has been credited to your account.
        </div>
    @endif
@endif

@if(isset($loan_amount))
<!-- Amount Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 20px 40px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">Loan Amount</p>
                        <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                            {{ $settings->currency }}{{ number_format($loan_amount, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

<div class="details-card">
    <h3>Loan Application Details</h3>
    <div class="detail-row">
        <span class="detail-label">Application Reference</span>
        <span class="detail-value">{{ $reference ?? 'N/A' }}</span>
    </div>
    @if(isset($loan_amount))
    <div class="detail-row">
        <span class="detail-label">Requested Amount</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($loan_amount, 2) }}</span>
    </div>
    @endif
    @if(isset($loan_type))
    <div class="detail-row">
        <span class="detail-label">Loan Type</span>
        <span class="detail-value">{{ $loan_type }}</span>
    </div>
    @endif
    @if(isset($duration))
    <div class="detail-row">
        <span class="detail-label">Loan Duration</span>
        <span class="detail-value">{{ $duration }}</span>
    </div>
    @endif
    @if(isset($interest_rate))
    <div class="detail-row">
        <span class="detail-label">Interest Rate</span>
        <span class="detail-value">{{ $interest_rate }}%</span>
    </div>
    @endif
    <div class="detail-row">
        <span class="detail-label">Application Date</span>
        <span class="detail-value">{{ isset($application_date) ? \Carbon\Carbon::parse($application_date)->format('F j, Y') : \Carbon\Carbon::now()->format('F j, Y') }}</span>
    </div>
    @if(isset($status))
    <div class="detail-row">
        <span class="detail-label">Current Status</span>
        <span class="detail-value">
            @if($status == 'approved')
                <span class="status-badge status-success">Approved</span>
            @elseif($status == 'pending')
                <span class="status-badge status-pending">Under Review</span>
            @elseif($status == 'rejected')
                <span class="status-badge status-failed">Not Approved</span>
            @elseif($status == 'disbursed')
                <span class="status-badge status-success">Disbursed</span>
            @else
                <span class="status-badge">{{ ucfirst($status) }}</span>
            @endif
        </span>
    </div>
    @endif
</div>

@if(isset($status) && $status == 'approved')
<div class="details-card">
    <h3>Loan Terms</h3>
    @if(isset($approved_amount))
    <div class="detail-row">
        <span class="detail-label">Approved Amount</span>
        <span class="detail-value amount-credit">{{ $settings->currency }}{{ number_format($approved_amount, 2) }}</span>
    </div>
    @endif
    @if(isset($monthly_payment))
    <div class="detail-row">
        <span class="detail-label">Monthly Payment</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($monthly_payment, 2) }}</span>
    </div>
    @endif
    @if(isset($total_repayment))
    <div class="detail-row">
        <span class="detail-label">Total Repayment</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($total_repayment, 2) }}</span>
    </div>
    @endif
    @if(isset($first_payment_date))
    <div class="detail-row">
        <span class="detail-label">First Payment Due</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($first_payment_date)->format('F j, Y') }}</span>
    </div>
    @endif
    @if(isset($disbursement_date))
    <div class="detail-row">
        <span class="detail-label">Disbursement Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($disbursement_date)->format('F j, Y') }}</span>
    </div>
    @endif
</div>

<div class="details-card">
    <h3>Next Steps</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #fff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">1</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Accept Loan Terms</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Review and accept the loan agreement in your dashboard.</p>
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
                            <strong style="color: #0f172a;">Await Disbursement</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Funds will be credited within 24&ndash;48 hours after acceptance.</p>
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
                            <strong style="color: #0f172a;">Set Up Repayment</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Configure automatic payments for timely loan repayment.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="btn-container">
    <a href="{{ route('user.loans') }}" class="btn-primary">View Loan Details</a>
</div>
@endif

@if(isset($status) && $status == 'pending')
<div class="details-card">
    <h3>Review Progress</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Application and documents under review</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Credit history and financial verification</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Risk assessment in progress</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Decision within 3&ndash;5 business days</td></tr>
    </table>
</div>

<div class="notice notice-info">
    <strong>&#128203; Documents</strong><br>
    @if(isset($required_documents))
        Please ensure you have submitted all required documents:
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; font-size: 14px;">
            @foreach($required_documents as $doc)
            <tr><td style="padding: 3px 0;">&#8226;&nbsp; {{ $doc }}</td></tr>
            @endforeach
        </table>
    @else
        If additional documents are needed, we will contact you directly.
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
            Your application was declined based on current lending criteria including credit history, income verification, and risk assessment.
        @endif
    </p>
</div>

<div class="details-card">
    <h3>Recommended Actions</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Review and improve your credit score</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Reduce your debt-to-income ratio</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Consider applying for a smaller amount</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Reapply after 90 days with improved financials</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Contact our loan advisor for guidance</td></tr>
    </table>
</div>
@endif

@if(isset($status) && $status == 'disbursed')
<div class="details-card">
    <h3>Balance Summary</h3>
    <div class="detail-row">
        <span class="detail-label">Previous Balance</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($previous_balance ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Loan Disbursed</span>
        <span class="detail-value amount-credit">+{{ $settings->currency }}{{ number_format($loan_amount ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">New Balance</span>
        <span class="detail-value" style="font-weight: 700;">{{ $settings->currency }}{{ number_format(($previous_balance ?? 0) + ($loan_amount ?? 0), 2) }}</span>
    </div>
</div>

<div class="notice notice-caution">
    <strong>&#9888; Repayment Reminder</strong><br>
    Your first repayment of <strong>{{ $settings->currency }}{{ number_format($monthly_payment ?? 0, 2) }}</strong> is due on <strong>{{ isset($first_payment_date) ? \Carbon\Carbon::parse($first_payment_date)->format('F j, Y') : 'TBD' }}</strong>. Set up automatic payments to avoid late fees.
</div>

<div class="btn-container">
    <a href="{{ route('user.loans') }}" class="btn-primary">Manage Loan Repayment</a>
</div>
@endif

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Important</strong><br>
        <span style="font-size: 13px;">All loan communications are sent from official {{ $settings->site_name }} channels. Never share your account credentials or make payments to unofficial accounts. Contact us at {{ $settings->contact_email }} with any questions.</span>
    </p>
</div>

<div class="closing">
    <p>
        @if(isset($status) && $status == 'approved')
            Congratulations on your loan approval. We are here to support your financial goals.
        @elseif(isset($status) && $status == 'rejected')
            We appreciate your interest. Please reach out for guidance on alternative options.
        @else
            Thank you for choosing {{ $settings->site_name }} for your lending needs.
        @endif
    </p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
