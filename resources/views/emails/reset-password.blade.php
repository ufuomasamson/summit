@extends('emails.layouts.master')

@section('subject', 'Reset Your Password - ' . $settings->site_name)

@section('preheader', 'A password reset was requested for your ' . $settings->site_name . ' account')

@section('content')
<h1 class="greeting">
    Password Reset Request
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    We received a password reset request for your {{ $settings->site_name }} account.
</p>

@if(isset($actionUrl) && $actionUrl)
<div class="btn-container">
    <a href="{{ $actionUrl }}" class="btn-primary">Reset Your Password</a>
</div>

<p style="color: #64748b; font-size: 13px; margin-top: 16px;">
    Or copy and paste this link into your browser:
</p>
<div style="background: #f8fafc; padding: 12px; border-radius: 6px; word-break: break-all; margin-bottom: 24px;">
    <p style="margin: 0; color: #c10e0e; font-size: 12px; font-family: 'Courier New', monospace;">
        {{ $actionUrl }}
    </p>
</div>
@endif

<div class="notice notice-caution">
    <strong>&#9202; Link Expires in 60 Minutes</strong><br>
    This reset link is valid for 60 minutes. After expiration, you will need to request a new one.
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
                        <td style="padding-left: 10px; vertical-align: middle; color: #475569; font-size: 14px;">Click &ldquo;Reset Your Password&rdquo; above</td>
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
                        <td style="padding-left: 10px; vertical-align: middle; color: #475569; font-size: 14px;">Enter and confirm your new password</td>
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
                        <td style="padding-left: 10px; vertical-align: middle; color: #475569; font-size: 14px;">Log in with your new credentials</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="notice notice-urgent">
    <strong>&#9888; Didn't Request This?</strong><br>
    If you did not request a password reset, ignore this email. Your password will remain unchanged. Consider enabling two-factor authentication if you suspect unauthorized access.
</div>

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Password Tips</strong>
    </p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 8px; color: #78350f; font-size: 13px;">
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Use a unique password for your {{ $settings->site_name }} account</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Include uppercase, lowercase, numbers, and symbols</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Avoid personal information or common words</td></tr>
        <tr><td style="padding: 4px 0;">&#8226;&nbsp; Enable two-factor authentication for added security</td></tr>
    </table>
</div>

<p class="intro-text" style="margin-top: 24px; font-size: 14px;">
    If you need assistance with your account, our support team is available around the clock.
</p>
@endsection

@section('footer-note')
<p style="margin: 0; color: #94a3b8; font-size: 13px; line-height: 1.6;">
    This is an automated security message from {{ $settings->site_name }}. Password reset links are single-use and expire after 60 minutes. Never share reset links with anyone.
</p>
@endsection
