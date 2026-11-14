@extends('emails.layouts.master')

@section('subject', 'Verify Your Email Address - ' . $settings->site_name)

@section('preheader', 'Your verification code for ' . $settings->site_name . ' is ready')

@section('content')
<h1 class="greeting">
    Email Verification Required
</h1>

<p class="intro-text">
    Dear {{ $user->name }},
</p>

<p class="intro-text">
    To complete your registration and access all features, please verify your email address using the code below.
</p>

<!-- Verification Code Box -->
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                <tr>
                    <td style="padding: 20px 40px; text-align: center;">
                        <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 8px 0; text-transform: uppercase; letter-spacing: 2px;">Verification Code</p>
                        <p style="color: #ffffff; font-size: 36px; font-weight: 700; margin: 0; letter-spacing: 8px; font-family: 'Courier New', monospace;">
                            {{ $code }}
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<div class="details-card">
    <h3>How to Verify</h3>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td style="padding: 12px 0; border-bottom: 1px solid #f1f5f9;">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="width: 32px; vertical-align: top;">
                            <span style="display: inline-block; width: 26px; height: 26px; background-color: #c10e0e; color: #fff; border-radius: 50%; text-align: center; line-height: 26px; font-size: 13px; font-weight: 700;">1</span>
                        </td>
                        <td style="padding-left: 10px; vertical-align: middle; color: #475569; font-size: 14px;">Copy the verification code above</td>
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
                        <td style="padding-left: 10px; vertical-align: middle; color: #475569; font-size: 14px;">Return to the verification page</td>
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
                        <td style="padding-left: 10px; vertical-align: middle; color: #475569; font-size: 14px;">Enter the code and click &ldquo;Verify Email&rdquo;</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>

<div class="notice notice-caution">
    <strong>&#9202; Code Expires in 15 Minutes</strong><br>
    This verification code is valid for 15 minutes. If it expires, you can request a new one from the verification page.
</div>

<div class="security-notice">
    <p class="security-notice-text">
        <strong>&#128274; Security Notice</strong><br>
        <span style="font-size: 13px;">If you did not create an account with {{ $settings->site_name }}, please disregard this email. No further action is required.</span>
    </p>
</div>

<p class="intro-text" style="margin-top: 24px; font-size: 14px;">
    If you need assistance, our support team is available around the clock.
</p>
@endsection

@section('footer-note')
<p style="margin: 0; color: #94a3b8; font-size: 13px; line-height: 1.6;">
    This is an automated security message from {{ $settings->site_name }}. Verification codes are valid for 15 minutes.
</p>
@endsection
