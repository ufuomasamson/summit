@extends('emails.layouts.master')

@section('subject', 'Welcome to ' . $settings->site_name)

@section('preheader', 'Your account is ready. Here\'s everything you need to get started.')

@section('content')
<h1 class="greeting">
    Welcome, {{ $user->name }}!
</h1>

<p class="intro-text">
    Thank you for choosing {{ $settings->site_name }}. Your account has been created and is ready to use.
</p>

<div class="notice notice-confirm">
    <strong>&#10003; Account Successfully Created</strong><br>
    You now have access to all {{ $settings->site_name }} banking services.
</div>

<div class="details-card">
    <h3>Your Account Details</h3>
    <div class="detail-row">
        <span class="detail-label">Full Name</span>
        <span class="detail-value">{{ $user->name }} {{ $user->middlename }} {{ $user->lastname }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Account Number</span>
        <span class="detail-value">{{ $user->usernumber }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Account Type</span>
        <span class="detail-value">{{ $user->accounttype ?? 'Savings Account' }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Email Address</span>
        <span class="detail-value">{{ $user->email }}</span>
    </div>
    <div class="detail-row">
        <span class="detail-label">Registration Date</span>
        <span class="detail-value">{{ \Carbon\Carbon::parse($user->created_at)->format('F j, Y') }}</span>
    </div>
</div>

<div class="details-card">
    <h3>Getting Started</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding: 14px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #ffffff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">1</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Complete Your Profile</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Verify your identity to unlock all features.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 14px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #ffffff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">2</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Make Your First Deposit</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Fund your account using any of our secure payment methods.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 14px 0;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #ffffff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">3</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: top;">
                            <strong style="color: #0f172a;">Explore Our Services</strong>
                            <p style="color: #64748b; margin: 4px 0 0 0; font-size: 13px;">Send money worldwide, apply for virtual cards, and start investing.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="btn-container">
    <a href="{{ route('dashboard') }}" class="btn-primary">Access My Dashboard</a>
</div>

<div class="details-card">
    <h3>Available Services</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="color: #475569; font-size: 14px;">
        <tr>
            <td style="padding: 8px 0;">&#10003;&nbsp; <strong>Instant Transfers</strong> &mdash; Send money globally in minutes</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">&#10003;&nbsp; <strong>Virtual Cards</strong> &mdash; Secure payment cards for online purchases</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">&#10003;&nbsp; <strong>Investment Plans</strong> &mdash; Grow your wealth with competitive returns</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">&#10003;&nbsp; <strong>24/7 Support</strong> &mdash; Dedicated team always ready to help</td>
        </tr>
        <tr>
            <td style="padding: 8px 0;">&#10003;&nbsp; <strong>Bank-Level Security</strong> &mdash; Enterprise-grade protection for your funds</td>
        </tr>
    </table>
</div>

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Security Recommendations</strong>
    </p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; color: #78350f; font-size: 13px;">
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Never share your password or PIN with anyone</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Always log out after using shared devices</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Enable two-factor authentication for added security</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Contact us immediately if you notice suspicious activity</td></tr>
    </table>
</div>

<div class="notice notice-info">
    <strong>&#128172; Need Assistance?</strong><br>
    Our support team is available around the clock:
    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; font-size: 14px;">
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Email: {{ $settings->contact_email }}</td></tr>
        @if($settings->phone)
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Phone: {{ $settings->phone }}</td></tr>
        @endif
        <tr><td style="padding: 3px 0;">&#8226;&nbsp; Live Chat available on our website</td></tr>
    </table>
</div>

<div class="closing">
    <p>We look forward to serving your financial needs.</p>
    <p class="signature">
        Warm regards,<br>
        <strong>The {{ $settings->site_name }} Team</strong>
    </p>
</div>
@endsection
