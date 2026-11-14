<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Account Statement</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #1a1a1a;
            line-height: 1.5;
        }

        /* Header */
        .header {
            border-bottom: 3px solid #1a3a2a;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .bank-name {
            font-size: 22px;
            font-weight: 700;
            color: #1a3a2a;
            letter-spacing: 1px;
        }
        .bank-tagline {
            font-size: 9px;
            color: #777;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 2px;
        }
        .statement-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a3a2a;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            text-align: right;
            margin-top: 5px;
        }
        .statement-period {
            font-size: 10px;
            color: #555;
            text-align: right;
            margin-top: 3px;
        }

        /* Account Info */
        .account-info {
            background-color: #f7f9f8;
            border: 1px solid #e0e7e3;
            border-radius: 4px;
            padding: 15px 18px;
            margin-bottom: 25px;
        }
        .account-info table {
            width: 100%;
        }
        .account-info td {
            padding: 3px 0;
            vertical-align: top;
        }
        .info-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
            width: 130px;
        }
        .info-value {
            font-size: 11px;
            color: #1a1a1a;
            font-weight: 500;
        }

        /* Summary Row */
        .summary-row {
            margin-bottom: 20px;
        }
        .summary-row table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-box {
            text-align: center;
            padding: 12px 10px;
            border: 1px solid #e0e7e3;
            background-color: #fafbfa;
        }
        .summary-box .label {
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #888;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .summary-box .value {
            font-size: 13px;
            font-weight: 700;
            color: #1a3a2a;
        }
        .summary-box .value.debit {
            color: #991b1b;
        }

        /* Transactions Table */
        .transactions-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .transactions-table thead th {
            background-color: #1a3a2a;
            color: #ffffff;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 8px 10px;
            text-align: left;
        }
        .transactions-table thead th.right {
            text-align: right;
        }
        .transactions-table tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
            vertical-align: top;
        }
        .transactions-table tbody tr:nth-child(even) {
            background-color: #fafbfa;
        }
        .transactions-table tbody td.right {
            text-align: right;
            font-family: 'Courier New', monospace;
            font-size: 10px;
        }
        .transactions-table tbody td.debit {
            color: #991b1b;
        }
        .transactions-table tbody td.credit {
            color: #065f46;
        }
        .transactions-table tbody td.desc {
            max-width: 200px;
            word-wrap: break-word;
        }
        .transactions-table tbody td.ref {
            font-family: 'Courier New', monospace;
            font-size: 9px;
            color: #666;
        }

        /* No transactions */
        .no-transactions {
            text-align: center;
            padding: 40px 20px;
            color: #888;
            font-size: 12px;
        }

        /* Footer */
        .footer {
            border-top: 2px solid #1a3a2a;
            padding-top: 15px;
            margin-top: 30px;
        }
        .footer-text {
            font-size: 8px;
            color: #999;
            line-height: 1.6;
        }
        .generated-at {
            font-size: 8px;
            color: #aaa;
            text-align: right;
            margin-top: 10px;
        }

        /* Page number */
        .page-number {
            text-align: center;
            font-size: 8px;
            color: #aaa;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <table style="width: 100%;" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 60%; vertical-align: bottom;">
                @if($settings->logo)
                    <img src="{{ public_path('storage/app/public/' . $settings->logo) }}" alt="{{ $settings->site_name }}" style="max-height: 45px; max-width: 180px; margin-bottom: 6px; display: block;">
                @endif
                <div class="bank-name">{{ $settings->site_name ?? 'FinCore' }}</div>
                <div class="bank-tagline">{{ $settings->address ?? 'Secure Online Banking' }}</div>
            </td>
            <td style="width: 40%; vertical-align: bottom;">
                <div class="statement-title">Statement of Account</div>
                <div class="statement-period">{{ $from->format('d M Y') }} — {{ $to->format('d M Y') }}</div>
            </td>
        </tr>
    </table>
    <div class="header"></div>

    <!-- Account Information -->
    <div class="account-info">
        <table>
            <tr>
                <td class="info-label">Account Holder</td>
                <td class="info-value">{{ $user->name }} {{ $user->middlename }} {{ $user->lastname }}</td>
                <td class="info-label" style="width: 110px;">Account Number</td>
                <td class="info-value">{{ $user->usernumber }}</td>
            </tr>
            <tr>
                <td class="info-label">Email</td>
                <td class="info-value">{{ $user->email }}</td>
                <td class="info-label">Account Type</td>
                <td class="info-value">{{ $user->accounttype ?? 'Savings Account' }}</td>
            </tr>
            <tr>
                <td class="info-label">Currency</td>
                <td class="info-value">{{ $settings->currency ?? 'USD' }}</td>
                <td class="info-label">Statement Date</td>
                <td class="info-value">{{ $generatedAt->format('d M Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Summary -->
    <div class="summary-row">
        <table>
            <tr>
                <td style="width: 25%; padding-right: 5px;">
                    <div class="summary-box">
                        <div class="label">Opening Balance</div>
                        <div class="value">{{ $settings->currency }}{{ number_format($openingBalance, 2) }}</div>
                    </div>
                </td>
                <td style="width: 25%; padding: 0 3px;">
                    <div class="summary-box">
                        <div class="label">Total Credits</div>
                        <div class="value">{{ $settings->currency }}{{ number_format($totalCredits, 2) }}</div>
                    </div>
                </td>
                <td style="width: 25%; padding: 0 3px;">
                    <div class="summary-box">
                        <div class="label">Total Debits</div>
                        <div class="value debit">{{ $settings->currency }}{{ number_format($totalDebits, 2) }}</div>
                    </div>
                </td>
                <td style="width: 25%; padding-left: 5px;">
                    <div class="summary-box" style="border: 1px solid #1a3a2a; background-color: #f0f7f3;">
                        <div class="label">Closing Balance</div>
                        <div class="value">{{ $settings->currency }}{{ number_format($closingBalance, 2) }}</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Transactions -->
    @if($transactions->count() > 0)
        <table class="transactions-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Date</th>
                    <th style="width: 35%;">Description</th>
                    <th style="width: 13%;">Reference</th>
                    <th class="right" style="width: 13%;">Debit</th>
                    <th class="right" style="width: 13%;">Credit</th>
                    <th class="right" style="width: 14%;">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $txn)
                <tr>
                    <td>{{ $txn->date->format('d/m/Y') }}</td>
                    <td class="desc">{{ $txn->description }}</td>
                    <td class="ref">{{ Str::limit($txn->reference, 14) }}</td>
                    <td class="right debit">
                        @if($txn->debit)
                            {{ $settings->currency }}{{ number_format($txn->debit, 2) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="right credit">
                        @if($txn->credit)
                            {{ $settings->currency }}{{ number_format($txn->credit, 2) }}
                        @else
                            —
                        @endif
                    </td>
                    <td class="right" style="font-weight: 600;">
                        {{ $settings->currency }}{{ number_format($txn->balance, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; font-size: 9px; color: #888; margin-bottom: 10px;">
            Total Entries: {{ $transactions->count() }}
        </div>
    @else
        <div class="no-transactions">
            No processed transactions found for the selected period.
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div class="footer-text">
            This statement is generated electronically and is valid without a signature. It provides a summary of all processed 
            transactions on your account for the specified period. If you believe any transaction is incorrect, please contact 
            customer support immediately. {{ $settings->site_name ?? 'FinCore' }} is not responsible for discrepancies not reported 
            within 30 days of the statement date.
        </div>
        <div class="generated-at">
            Generated on {{ $generatedAt->format('d M Y \a\t H:i') }} | {{ $settings->site_name ?? 'FinCore' }}
        </div>
    </div>

</body>
</html>
