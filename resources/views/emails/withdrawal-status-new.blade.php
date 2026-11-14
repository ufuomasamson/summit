@extends('emails.layouts.master')

@section('subject', $withdrawal->status == 'Processed' ? 'Transfer Completed Successfully' : ($withdrawal->status == 'Reversed' || $withdrawal->status == 'Refunded' ? 'Transfer ' . $withdrawal->status : ($withdrawal->status == 'Failed' ? 'Transfer Failed' : 'Transfer Confirmation')))

@section('preheader', ($withdrawal->status == 'Processed' ? 'Your transfer of ' : 'Transfer of ') . $user->currency . number_format($withdrawal->amount, 2) . ($withdrawal->status == 'Processed' ? ' has been completed' : ($withdrawal->status == 'Reversed' || $withdrawal->status == 'Refunded' ? ' has been ' . strtolower($withdrawal->status) : ' is being processed')))

@section('content')
<h1 class="greeting">
    Hello {{ $foramin ? 'Admin' : $user->name . ' ' . $user->middlename . ' ' . $user->lastname }},
</h1>

@if ($foramin)
    {{-- Admin Notification --}}
    <p class="intro-text">
        A transfer request has been initiated by <strong>{{ $user->name }}</strong>. Please review the transaction details below and take the necessary action.
    </p>

    <!-- Amount Box -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background-color: #fdf2f2; border: 2px solid #c10e0e; border-radius: 8px;">
                    <tr>
                        <td style="padding: 18px 40px; text-align: center;">
                            <p style="color: #c10e0e; font-size: 28px; font-weight: 700; margin: 0;">
                                {{ $user->currency }}{{ number_format($withdrawal->amount, 2) }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="details-card">
        <h3>Transfer Details</h3>
        <div class="detail-row">
            <span class="detail-label">Customer Name</span>
            <span class="detail-value">{{ $user->name }} {{ $user->middlename }} {{ $user->lastname }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Account Number</span>
            <span class="detail-value">{{ $user->usernumber }}</span>
        </div>
        @if(in_array($withdrawal->payment_mode, ['Cryptocurrency', 'Crytocurrency', 'Bitcoin', 'Ethereum', 'USDT']))
        <div class="detail-row">
            <span class="detail-label">Cryptocurrency</span>
            <span class="detail-value">{{ $withdrawal->bankname ?? $withdrawal->payment_mode }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Wallet Address</span>
            <span class="detail-value" style="word-break: break-all;">{{ $withdrawal->accountnumber }}</span>
        </div>
        @else
        <div class="detail-row">
            <span class="detail-label">Beneficiary</span>
            <span class="detail-value">{{ $withdrawal->accountname }}</span>
        </div>
        @endif
        <div class="detail-row">
            <span class="detail-label">Transfer Method</span>
            <span class="detail-value">{{ $withdrawal->payment_mode }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value amount-debit">{{ $user->currency }}{{ number_format($withdrawal->amount, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Date &amp; Time</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('F j, Y \a\t g:i A') }}</span>
        </div>
    </div>

    <div class="btn-container">
        <a href="{{ url('/admin/manage-withdrawals') }}" class="btn-primary">Review Transaction</a>
    </div>

@else
    {{-- Customer Notification --}}
    @if ($withdrawal->status == 'Processed')
        <p class="intro-text">
            We are pleased to confirm that your transfer has been successfully processed and sent to the beneficiary.
        </p>

        <div class="notice notice-confirm">
            <strong>&#10003; Transfer Completed</strong><br>
            Your funds have been sent successfully.
        </div>

    @elseif($withdrawal->status == 'Failed')
        <p class="intro-text">
            We regret to inform you that your transfer could not be processed due to a technical error. Your account has been refunded.
        </p>

        <div class="notice notice-error" style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 12px 16px; margin: 16px 0;">
            <strong>&#10007; Transfer Failed</strong><br>
            Please try again or contact our support team for assistance.
        </div>

    @elseif($withdrawal->status == 'Reversed' || $withdrawal->status == 'Refunded')
        <p class="intro-text">
            Your transfer has been {{ strtolower($withdrawal->status) }}. The amount has been credited back to your account.
        </p>

        <div class="notice notice-info">
            <strong>&#8634; Transfer {{ $withdrawal->status }}</strong><br>
            {{ $user->currency }}{{ number_format($withdrawal->amount, 2) }} has been returned to your account.
        </div>

    @elseif($withdrawal->status == 'Cancelled')
        <p class="intro-text">
            Your transfer request has been cancelled. Your account balance has been restored.
        </p>

        <div class="notice notice-info">
            <strong>&#10007; Transfer Cancelled</strong><br>
            No funds have been deducted from your account.
        </div>

    @elseif($withdrawal->status == 'Frozen')
        <p class="intro-text">
            Your transfer has been temporarily frozen pending further verification. Please contact our support team.
        </p>

        <div class="notice notice-error" style="background-color: #fef2f2; border-left: 4px solid #ef4444; padding: 12px 16px; margin: 16px 0;">
            <strong>&#10052; Transaction Frozen</strong><br>
            This is a security measure. Please contact support for assistance.
        </div>

    @elseif($withdrawal->status == 'Under Review')
        <p class="intro-text">
            Your transfer is currently under review by our compliance team. This is a standard procedure.
        </p>

        <div class="notice notice-info">
            <strong>&#128269; Under Review</strong><br>
            Your transaction will be updated once the review is completed.
        </div>

    @elseif($withdrawal->status == 'Expired')
        <p class="intro-text">
            Your transfer request has expired and could not be processed in time. Your account has been refunded.
        </p>

        <div class="notice notice-info">
            <strong>&#9202; Transfer Expired</strong><br>
            Please submit a new transfer request if you still wish to proceed.
        </div>

    @elseif($withdrawal->status == 'Scheduled')
        <p class="intro-text">
            Your transfer has been scheduled for processing.
        </p>

        <div class="notice notice-info">
            <strong>&#128197; Transfer Scheduled</strong><br>
            Your transaction will be processed as scheduled.
        </div>

    @else
        <p class="intro-text">
            Your transfer request has been received and confirmed. We are processing your transaction.
        </p>

        <div class="notice notice-info">
            <strong>&#9202; Processing</strong><br>
            @if($withdrawal->payment_mode == 'Wire Transfer')
                International wire transfers typically take 2&ndash;5 business days to complete.
            @elseif(in_array($withdrawal->payment_mode, ['Cryptocurrency', 'Crytocurrency', 'Bitcoin', 'Ethereum', 'USDT']))
                Cryptocurrency transfers are usually completed within 1 hour.
            @else
                Your beneficiary should receive the funds within 24 hours.
            @endif
        </div>
    @endif

    <!-- Amount Box -->
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellpadding="0" cellspacing="0" border="0" style="background: linear-gradient(135deg, #c10e0e 0%, #a30c0c 100%); border-radius: 8px;">
                    <tr>
                        <td style="padding: 20px 40px; text-align: center;">
                            <p style="color: #c10e0e; font-size: 11px; font-weight: 700; margin: 0 0 6px 0; text-transform: uppercase; letter-spacing: 2px;">Amount Sent</p>
                            <p style="color: #ffffff; font-size: 32px; font-weight: 700; margin: 0; font-family: 'Georgia', serif;">
                                {{ $user->currency }}{{ number_format($withdrawal->amount, 2) }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="details-card">
        <h3>Transaction Summary</h3>

        @if($withdrawal->payment_mode == 'Wire Transfer')
            <div class="detail-row">
                <span class="detail-label">Beneficiary Name</span>
                <span class="detail-value">{{ $withdrawal->accountname }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Account Number</span>
                <span class="detail-value">{{ $withdrawal->accountnumber }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bank Name</span>
                <span class="detail-value">{{ $withdrawal->bankname }}</span>
            </div>
            @if($withdrawal->routingnumber)
            <div class="detail-row">
                <span class="detail-label">Routing Number</span>
                <span class="detail-value">{{ $withdrawal->routingnumber }}</span>
            </div>
            @endif
            @if($withdrawal->swiftcode)
            <div class="detail-row">
                <span class="detail-label">SWIFT Code</span>
                <span class="detail-value">{{ $withdrawal->swiftcode }}</span>
            </div>
            @endif
        @elseif(in_array($withdrawal->payment_mode, ['Cryptocurrency', 'Crytocurrency', 'Bitcoin', 'Ethereum', 'USDT']))
            <div class="detail-row">
                <span class="detail-label">Cryptocurrency</span>
                <span class="detail-value">{{ $withdrawal->bankname ?? $withdrawal->payment_mode }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Wallet Address</span>
                <span class="detail-value" style="word-break: break-all;">{{ $withdrawal->accountnumber }}</span>
            </div>
        @elseif($withdrawal->payment_mode == 'Western Union')
            <div class="detail-row">
                <span class="detail-label">Recipient Name</span>
                <span class="detail-value">{{ $withdrawal->accountname }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Country</span>
                <span class="detail-value">{{ $withdrawal->country }}</span>
            </div>
            @if($withdrawal->city)
            <div class="detail-row">
                <span class="detail-label">City</span>
                <span class="detail-value">{{ $withdrawal->city }}</span>
            </div>
            @endif
            @if($withdrawal->zipcode)
            <div class="detail-row">
                <span class="detail-label">ZIP Code</span>
                <span class="detail-value">{{ $withdrawal->zipcode }}</span>
            </div>
            @endif
        @else
            <div class="detail-row">
                <span class="detail-label">Recipient</span>
                <span class="detail-value">{{ $withdrawal->accountname }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value">{{ $withdrawal->bankname }}</span>
            </div>
        @endif

        <div class="detail-row">
            <span class="detail-label">Transfer Method</span>
            <span class="detail-value">{{ $withdrawal->payment_mode }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Amount Sent</span>
            <span class="detail-value amount-debit">{{ $user->currency }}{{ number_format($withdrawal->amount, 2) }}</span>
        </div>
        @if($withdrawal->Description)
        <div class="detail-row">
            <span class="detail-label">Description</span>
            <span class="detail-value">{{ $withdrawal->Description }}</span>
        </div>
        @endif
        <div class="detail-row">
            <span class="detail-label">Transaction Date</span>
            <span class="detail-value">{{ \Carbon\Carbon::parse($withdrawal->created_at)->format('F j, Y \a\t g:i A') }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Available Balance</span>
            <span class="detail-value">{{ $user->currency }}{{ number_format($withdrawal->bal, 2) }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="detail-value">
                <span class="status-badge {{ in_array($withdrawal->status, ['Processed']) ? 'status-success' : (in_array($withdrawal->status, ['Reversed', 'Refunded']) ? 'status-info' : (in_array($withdrawal->status, ['Rejected', 'Failed', 'Frozen', 'Expired']) ? 'status-danger' : 'status-pending')) }}">
                    {{ $withdrawal->status }}
                </span>
            </span>
        </div>
    </div>

    <div class="security-notice">
        <p class="security-notice-text">
            <strong>&#128274; Security Reminder</strong><br>
            <span style="font-size: 13px;">{{ $settings->site_name }} will never ask for your password, PIN, or security codes via email. If you did not authorize this transaction, contact our support team immediately.</span>
        </p>
    </div>

    <div class="btn-container">
        <a href="{{ route('accounthistory') }}" class="btn-primary">View Transaction History</a>
    </div>
@endif

<div class="closing">
    <p>If you have questions regarding this transaction, please contact our support team.</p>
    <p class="signature">
        Best regards,<br>
        <strong>{{ $settings->site_name }}</strong>
    </p>
</div>
@endsection
