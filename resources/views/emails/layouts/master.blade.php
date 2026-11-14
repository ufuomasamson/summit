<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>@yield('subject', 'Notification')</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:AllowPNG/>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        /* === Reset === */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }
        table {
            border-collapse: collapse !important;
        }
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            height: 100% !important;
        }

        /* === Base === */
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f1f5f9;
        }
        a { color: #c10e0e; text-decoration: none; }

        /* === Preheader === */
        .preheader {
            display: none !important;
            visibility: hidden;
            mso-hide: all;
            font-size: 1px;
            line-height: 1px;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
        }

        /* === Typography === */
        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 16px 0;
            padding: 0;
            line-height: 1.3;
        }
        .intro-text {
            font-size: 15px;
            color: #475569;
            margin: 0 0 16px 0;
            line-height: 1.7;
        }

        /* === Details Card (table-based) === */
        .details-card {
            margin: 24px 0;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            overflow: hidden;
        }
        .details-card h3 {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            padding: 12px 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background-color: #f8fafc;
            border-bottom: 2px solid #c10e0e;
        }

        /* Legacy div-based detail-row (backward compat) */
        .detail-row {
            padding: 11px 20px;
            border-bottom: 1px solid #f1f5f9;
            overflow: hidden;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .detail-label,
        .detail-row .detail-value {
            font-size: 14px;
        }
        .detail-row .detail-label {
            float: left;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }
        .detail-row .detail-value {
            float: right;
            color: #0f172a;
            font-weight: 600;
            text-align: right;
            max-width: 55%;
            word-wrap: break-word;
            word-break: break-word;
        }

        .highlight-amount { color: #c10e0e !important; font-weight: 700 !important; }
        .amount-highlight {
            font-size: 28px;
            font-weight: 700;
            color: #c10e0e;
            text-align: center;
            padding: 20px 0;
            margin: 20px 0;
        }
        .amount-credit { color: #16a34a !important; font-weight: 700 !important; }
        .amount-debit { color: #dc2626 !important; font-weight: 700 !important; }

        /* === Status Badges === */
        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-success { background-color: #dcfce7; color: #15803d; }
        .status-pending { background-color: #fef3c7; color: #92400e; }
        .status-failed  { background-color: #fee2e2; color: #991b1b; }

        /* === Notice Boxes === */
        .notice {
            padding: 14px 18px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
            line-height: 1.6;
        }
        .notice-info    { background-color: #eff6ff; border-left: 4px solid #3b82f6; color: #1e3a5f; }
        .notice-caution { background-color: #fffbeb; border-left: 4px solid #f59e0b; color: #78350f; }
        .notice-confirm { background-color: #f0fdf4; border-left: 4px solid #22c55e; color: #14532d; }
        .notice-urgent  { background-color: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; }

        /* === Security Notice === */
        .security-notice {
            background-color: #fffbeb;
            border-left: 4px solid #f59e0b;
            border-radius: 6px;
            padding: 14px 18px;
            margin: 24px 0;
        }
        .security-notice-text {
            font-size: 14px;
            color: #78350f;
            line-height: 1.6;
            margin: 0;
        }

        /* === Button === */
        .btn-primary {
            display: inline-block;
            padding: 13px 36px;
            background-color: #c10e0e;
            color: #ffffff !important;
            text-decoration: none !important;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            letter-spacing: 0.3px;
        }
        .btn-container { text-align: center; margin: 28px 0; }

        /* === Dividers === */
        .divider { height: 1px; background-color: #e2e8f0; margin: 24px 0; border: 0; }

        /* === Closing === */
        .closing { margin-top: 28px; font-size: 15px; color: #475569; }
        .signature { margin-top: 6px; font-weight: 600; color: #0f172a; }

        /* === Details Box (verify-email / reset-password) === */
        .details-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .section-title {
            font-size: 15px;
            font-weight: 600;
            color: #0f172a;
            margin: 0 0 12px 0;
        }

        /* === Responsive === */
        @media only screen and (max-width: 600px) {
            .email-container { width: 100% !important; }
            .email-body-td  { padding: 24px 20px !important; }
            .email-header-td { padding: 20px !important; }
            .email-footer-td { padding: 24px 20px !important; }
            .greeting { font-size: 20px !important; }
            .detail-row .detail-label,
            .detail-row .detail-value {
                float: none !important;
                display: block !important;
                text-align: left !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            .detail-row .detail-value { margin-top: 2px; }
            .amount-highlight { font-size: 24px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9;">
    <!-- Preheader (inbox preview text) -->
    <span class="preheader">@yield('preheader', '')</span>

    <!-- Outer wrapper -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9;">
        <tr>
            <td align="center" style="padding: 32px 16px;">

                <!--[if mso]>
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" align="center"><tr><td>
                <![endif]-->
                <table role="presentation" class="email-container" width="600" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; width: 100%; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">

                    <!-- Gold Accent Bar -->
                    <tr>
                        <td style="background-color: #c10e0e; height: 4px; font-size: 0; line-height: 0;">&nbsp;</td>
                    </tr>

                    <!-- Header -->
                    <tr>
                        <td class="email-header-td" style="background-color: #3e4e83; padding: 28px 40px; text-align: center;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center">
                                        <img src="{{ url('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" style="max-width: 160px; height: auto; display: block; margin: 0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 10px;">
                                        <p style="margin: 0; font-size: 11px; font-weight: 600; color: #ffffff; text-transform: uppercase; letter-spacing: 2px;">{{ $settings->site_name }}</p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Optional Banner -->
                    @hasSection('banner')
                    <tr>
                        <td style="background-color: #fdf2f2; padding: 10px 40px; border-bottom: 1px solid #e2e8f0;">
                            @yield('banner')
                        </td>
                    </tr>
                    @endif

                    <!-- Body -->
                    <tr>
                        <td class="email-body-td" style="padding: 36px 40px;">
                            @yield('content')
                        </td>
                    </tr>

                    <!-- Optional footer-note -->
                    @hasSection('footer-note')
                    <tr>
                        <td style="padding: 0 40px 20px 40px;">
                            @yield('footer-note')
                        </td>
                    </tr>
                    @endif

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td style="height: 1px; background-color: #e2e8f0; font-size: 0; line-height: 0;">&nbsp;</td></tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="email-footer-td" style="background-color: #34416d; padding: 32px 40px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <!-- Logo -->
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <img src="{{ url('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" style="max-width: 110px; height: auto; opacity: 0.8;">
                                    </td>
                                </tr>
                                <!-- Bank & Address -->
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: #e2e8f0;">{{ $settings->site_name }}</p>
                                        <p style="margin: 0; font-size: 13px; color: #94a3b8; line-height: 1.6;">
                                            {{ $settings->address }}<br>
                                            <a href="mailto:{{ $settings->contact_email }}" style="color: #ffffff; text-decoration: none;">{{ $settings->contact_email }}</a>
                                            @if($settings->phone)
                                            <span style="color: #475569;">&nbsp;&middot;&nbsp;</span>
                                            <span style="color: #94a3b8;">{{ $settings->phone }}</span>
                                            @endif
                                        </p>
                                    </td>
                                </tr>
                                <!-- Links -->
                                <tr>
                                    <td align="center" style="padding: 16px 0;">
                                        <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding: 0 10px;"><a href="{{ url('/') }}" style="color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 500;">Home</a></td>
                                                <td style="color: #8fa3c0; font-size: 13px;">&middot;</td>
                                                <td style="padding: 0 10px;"><a href="{{ url('/about') }}" style="color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 500;">About</a></td>
                                                <td style="color: #8fa3c0; font-size: 13px;">&middot;</td>
                                                <td style="padding: 0 10px;"><a href="{{ url('/contact') }}" style="color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 500;">Contact</a></td>
                                                <td style="color: #8fa3c0; font-size: 13px;">&middot;</td>
                                                <td style="padding: 0 10px;"><a href="{{ url('/faq') }}" style="color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 500;">FAQ</a></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Copyright & Regulatory -->
                                <tr>
                                    <td align="center" style="padding-top: 16px; border-top: 1px solid #1e293b;">
                                        <p style="margin: 0 0 8px 0; font-size: 11px; color: #64748b; line-height: 1.5;">
                                            &copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.
                                        </p>
                                        <p style="margin: 0; font-size: 11px; color: #475569; line-height: 1.6;">
                                            This email was sent to you as a registered member of {{ $settings->site_name }}.
                                            This is a transactional email &mdash; no unsubscribe is required.
                                        </p>
                                    </td>
                                </tr>
                                <!-- Confidentiality -->
                                <tr>
                                    <td align="center" style="padding-top: 14px;">
                                        <p style="margin: 0; font-size: 10px; color: #475569; line-height: 1.6; font-style: italic;">
                                            This email and any attachments are confidential and intended solely for the addressee.
                                            If you are not the intended recipient, please delete this email immediately and notify the sender.
                                            {{ $settings->site_name }} is a licensed financial services provider.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
                <!--[if mso]>
                </td></tr></table>
                <![endif]-->

            </td>
        </tr>
    </table>
</body>
</html>
