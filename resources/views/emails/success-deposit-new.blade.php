@extends('emails.layouts.master')

@section('subject', $deposit->status == 'Processed' ? 'Deposit Confirmed - Account Credited' : 'Deposit Received - Under Review')

@section('preheader', $deposit->status == 'Processed' ? 'Your deposit of ' . $settings->currency . number_format($deposit->amount, 2) . ' has been confirmed' : 'Your deposit of ' . $settings->currency . number_format($deposit->amount, 2) . ' is being reviewed')

@section('content')
<h1 class="greeting">
    Hello {{ $foramin ? 'Admin' : $user->name }},
</h1>

@if ($foramin)
    {{-- Admin Notification --}}
    <p class="intro-text">
        A deposit transaction from <strong>{{ $user->name }}</strong> requires your attention.
        {{ $deposit->status == "Processed" ? 'The deposit has been automatically confirmed.' : 'Please review and process this deposit.' }}
    </p>

    <!-- Amount Box -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background-color: #fdf2f2; border: 2px solid #c10e0e; border-radius: 8px;">
                    <tr>
                        <td style="padding: 18px 40px; text-align: center;">
                            <p style="color: #c10e0e; font-size: 28px; font-weight: 700; margin: 0;">
                                {{ $settings->currency }}{{ number_format($deposit->amount, 2) }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="details-card">
        <h3>Deposit Details</h3>
        <div class="detail-row">
            <span class="detail-label">Customer Name</span>
            <span class="detail-value">{{ $user->name }} {{ $user->middlename }} {{ $user->lastname }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Account Number</span>
            <span class="detail-value">{{ $user->usernumber }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value amount-credit">{{ $settings->currency }}{{ number_format($deposit->amount, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Payment Method</span>
            <span class="detail-value">{{ $deposit->payment_mode ?? 'Bank Transfer' }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date &amp; Time</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($deposit->created_at)->format('F j, Y \a\t g:i A') }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value">
                <span class="status-badge {{ $deposit->status == 'Processed' ? 'status-success' : 'status-pending' }}">
                    {{ $deposit->status }}
                </span>
            </span>
        </div>
    </div>

    @if($deposit->status != 'Processed')
    <div class="btn-container">
        <a href="{{ url('/admin/manage-deposits') }}" class="btn-primary">Review Deposit</a>
    </div>
    @endif

@else
    {{-- Customer Notification --}}
    @if ($deposit->status == 'Processed')
        <p class="intro-text">
            Your deposit has been successfully confirmed and your account has been credited.
        </p>

        <div class="notice notice-confirm">
            <strong>&#10003; Deposit Confirmed</strong><br>
            Your account balance has been updated with the deposited amount.
        </div>

        <!-- Amount Box -->
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
            <tr>
                <td align="center">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                        <tr>
                            <td style="padding: 20px 40px; text-align: center;">
                                <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">Amount Credited</p>
                                <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                                    {{ $settings->currency }}{{ number_format($deposit->amount, 2) }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="details-card">
            <h3>Transaction Details</h3>
            <div class="detail-row">
                <span class="detail-label">Transaction Type</span>
                <span class="detail-value">Deposit</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount Credited</span>
                <span class="detail-value amount-credit">{{ $settings->currency }}{{ number_format($deposit->amount, 2) }}</span>
            </div>
            @if($deposit->payment_mode)
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value">{{ $deposit->payment_mode }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Transaction Date</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($deposit->created_at)->format('F j, Y \a\t g:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    <span class="status-badge status-success">Confirmed</span>
                </span>
            </div>
        </div>

        <div class="btn-container">
            <a href="{{ route('dashboard') }}" class="btn-primary">View Account Balance</a>
        </div>

    @else
        <p class="intro-text">
            Thank you for your deposit. We have received your transaction and it is currently being processed.
        </p>

        <div class="notice notice-info">
            <strong>&#9202; Under Review</strong><br>
            @if($deposit->payment_mode == 'Cryptocurrency')
                Your crypto deposit is undergoing blockchain confirmation. This typically takes 15&ndash;30 minutes.
                You will be notified once your transaction is confirmed.
            @else
                Our team is verifying your deposit. This usually takes a few minutes to a few hours.
                You will receive a confirmation email once your account is credited.
            @endif
        </div>

        <!-- Amount Box -->
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
            <tr>
                <td align="center">
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                        <tr>
                            <td style="padding: 20px 40px; text-align: center;">
                                <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">Deposit Amount</p>
                                <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                                    {{ $settings->currency }}{{ number_format($deposit->amount, 2) }}
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="details-card">
            <h3>Transaction Details</h3>
            <div class="detail-row">
                <span class="detail-label">Transaction Type</span>
                <span class="detail-value">Deposit</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-value">{{ $settings->currency }}{{ number_format($deposit->amount, 2) }}</span>
            </div>
            @if($deposit->payment_mode)
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value">{{ $deposit->payment_mode }}</span>
            </div>
            @endif
            <div class="detail-row">
                <span class="detail-label">Submitted On</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($deposit->created_at)->format('F j, Y \a\t g:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="detail-value">
                    <span class="status-badge status-pending">Processing</span>
                </span>
            </div>
        </div>

        <div class="notice notice-caution">
            <strong>&#128337; What's Next?</strong><br>
            We are verifying your deposit. You will receive a confirmation email as soon as your account is credited. No action is required from you at this time.
        </div>
    @endif

    <div class="security-notice">
        <p class="security-notice-text">
            <strong>&#128274; Security Note</strong><br>
            <span style="font-size: 13px;">Always verify that deposit instructions come from official {{ $settings->site_name }} channels. We will never ask you to send funds to personal accounts or unofficial payment methods.</span>
        </p>
    </div>
@endif

<div class="closing">
    <p>If you have questions about this transaction, our support team is here to help.</p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
