@extends('emails.layouts.master')

@section('subject', 'Grant Application Status - ' . ($status ?? 'Update'))

@section('preheader')
@if(isset($status))
@if($status == 'approved') Your grant application has been approved
@elseif($status == 'pending') Your grant application is under review
@elseif($status == 'rejected') Update on your grant application
@elseif($status == 'disbursed') Grant funds have been credited to your account
@endif
@endif
@endsection

@section('content')
<h1 class="greeting">
    Grant Application Update
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    This is regarding your grant application submitted to {{ $settings->site_name }}.
</p>

@if(isset($status))
    @if($status == 'approved')
        <div class="notice notice-confirm">
            <strong>&#10003; Grant Approved</strong><br>
            Your grant application has been approved. The grant amount will be credited to your account shortly.
        </div>
    @elseif($status == 'pending')
        <div class="notice notice-caution">
            <strong>&#9202; Application Under Review</strong><br>
            Your grant application is being evaluated by our review committee. We will notify you once a decision has been made.
        </div>
    @elseif($status == 'rejected')
        <div class="notice notice-urgent">
            <strong>Application Decision</strong><br>
            After thorough evaluation, we regret to inform you that your grant application has not been selected at this time.
        </div>
    @elseif($status == 'disbursed')
        <div class="notice notice-confirm">
            <strong>&#10003; Grant Disbursed</strong><br>
            Your approved grant has been successfully credited to your {{ $settings->site_name }} account.
        </div>
    @endif
@endif

@if(isset($grant_amount))
<!-- Amount Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 20px 40px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">Grant Amount</p>
                        <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                            {{ $settings->currency }}{{ number_format($grant_amount, 2) }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif

<div class="details-card">
    <h3>Application Details</h3>
    <div class="detail-row">
        <span class="detail-label">Application Reference</span>
        <span class="detail-value">{{ $reference ?? 'N/A' }}</span>
    </div>
    @if(isset($grant_name))
    <div class="detail-row">
        <span class="detail-label">Grant Program</span>
        <span class="detail-value">{{ $grant_name }}</span>
    </div>
    @endif
    @if(isset($grant_amount))
    <div class="detail-row">
        <span class="detail-label">Applied Amount</span>
        <span class="detail-value">{{ $settings->currency }}{{ number_format($grant_amount, 2) }}</span>
    </div>
    @endif
    @if(isset($category))
    <div class="detail-row">
        <span class="detail-label">Category</span>
        <span class="detail-value">{{ $category }}</span>
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
    <h3>Grant Award Details</h3>
    @if(isset($approved_amount))
    <div class="detail-row">
        <span class="detail-label">Approved Amount</span>
        <span class="detail-value amount-credit">{{ $settings->currency }}{{ number_format($approved_amount, 2) }}</span>
    </div>
    @endif
    @if(isset($disbursement_date))
    <div class="detail-row">
        <span class="detail-label">Disbursement Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($disbursement_date)->format('F j, Y') }}</span>
    </div>
    @endif
    @if(isset($grant_period))
    <div class="detail-row">
        <span class="detail-label">Grant Period</span>
        <span class="detail-value">{{ $grant_period }}</span>
    </div>
    @endif
    @if(isset($terms))
    <div class="detail-row">
        <span class="detail-label">Terms &amp; Conditions</span>
        <span class="detail-value">View in dashboard</span>
    </div>
    @endif
</div>

<div class="details-card">
    <h3>What Happens Next</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #fff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">1</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Review Grant Terms</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Read the grant agreement and terms of use in your dashboard.</p>
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
                            <strong style="color: #0f172a;">Accept Grant Award</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Acknowledge acceptance to proceed with disbursement.</p>
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
                            <strong style="color: #0f172a;">Receive Funds</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Funds will be credited within the specified timeline.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="btn-container">
    <a href="{{ route('user.grants') }}" class="btn-primary">View Grant Details</a>
</div>

@if(isset($requirements) && is_array($requirements) && count($requirements) > 0)
<div class="notice notice-info">
    <strong>&#128203; Grant Requirements</strong><br>
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; font-size: 14px;">
        @foreach($requirements as $requirement)
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; {{ $requirement }}</td></tr>
        @endforeach
    </table>
</div>
@endif
@endif

@if(isset($status) && $status == 'pending')
<div class="details-card">
    <h3>Review Process</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Grants committee is evaluating your application</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Documents and eligibility criteria being verified</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Alignment with grant objectives assessed</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Decision within 7&ndash;14 business days</td></tr>
    </table>
</div>

<div class="notice notice-info">
    <strong>&#128203; Application Checklist</strong><br>
    @if(isset($submitted_documents))
        Documents submitted:
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; font-size: 14px;">
            @foreach($submitted_documents as $doc)
            <tr><td style="padding: 3px 0;">&#10003;&nbsp; {{ $doc }}</td></tr>
            @endforeach
        </table>
    @else
        All required documents have been received. No further action needed at this time.
    @endif
</div>
@endif

@if(isset($status) && $status == 'rejected')
<div class="details-card">
    <h3>Decision Explanation</h3>
    <p style="color: #475569; line-height: 1.8; padding: 12px 0;">
        @if(isset($rejection_reason))
            {{ $rejection_reason }}
        @else
            Your application was not selected due to high competition and limited available funds. This decision does not reflect on the merit of your proposal. We encourage you to apply for future grant opportunities.
        @endif
    </p>
</div>

<div class="details-card">
    <h3>Future Opportunities</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Watch for new grant programs throughout the year</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Strengthen your application with additional documentation</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Explore alternative funding options on our platform</td></tr>
        <tr><td style="padding: 6px 0;">&#10003;&nbsp; Contact our grants team for feedback</td></tr>
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
        <span class="detail-label">Grant Disbursed</span>
        <span class="detail-value amount-credit">+{{ $settings->currency }}{{ number_format($grant_amount ?? 0, 2) }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">New Balance</span>
        <span class="detail-value" style="font-weight: 700;">{{ $settings->currency }}{{ number_format(($previous_balance ?? 0) + ($grant_amount ?? 0), 2) }}</span>
    </div>
</div>

@if(isset($usage_restrictions))
<div class="notice notice-caution">
    <strong>&#9888; Fund Usage Guidelines</strong><br>
    {{ $usage_restrictions }}
</div>
@else
<div class="notice notice-info">
    <strong>&#128203; Fund Management</strong><br>
    Grant funds are now available in your account. Please use them in accordance with your approved application and grant terms.
</div>
@endif

<div class="btn-container">
    <a href="{{ route('dashboard') }}" class="btn-primary">Access My Account</a>
</div>
@endif

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Grant Integrity</strong><br>
        <span style="font-size: 13px;">All grant communications are sent from official {{ $settings->site_name }} email addresses. We will never request payment or personal financial information via email. Report suspicious communications to {{ $settings->contact_email }}.</span>
    </p>
</div>

@if(isset($contact_officer))
<div style="background: #f8fafc; padding: 16px; border-radius: 8px; margin: 24px 0;">
    <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0;">
        <strong>Your Grant Officer:</strong> {{ $contact_officer }}
        @if(isset($officer_email))
        <br>Email: {{ $officer_email }}
        @endif
    </p>
</div>
@endif

<div class="closing">
    <p>
        @if(isset($status) && $status == 'approved')
            Congratulations on receiving this grant. We look forward to supporting your goals.
        @elseif(isset($status) && $status == 'rejected')
            We encourage you to apply for future opportunities.
        @else
            Thank you for choosing {{ $settings->site_name }} grant programs.
        @endif
    </p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
