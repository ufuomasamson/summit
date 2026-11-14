<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Settings;
use App\Models\Withdrawal;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatementController extends Controller
{
    /**
     * Show the statement request form.
     */
    public function index()
    {
        return view('user.statement', [
            'title' => 'Account Statement',
        ]);
    }

    /**
     * Generate and download the PDF statement.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $user     = Auth::user();
        $settings = Settings::first();

        $from = Carbon::parse($request->from_date)->startOfDay();
        $to   = Carbon::parse($request->to_date)->endOfDay();

        // Limit range to 1 year max
        if ($from->diffInDays($to) > 366) {
            return back()->with('error', 'Statement range cannot exceed 12 months. Please select a shorter period.');
        }

        // Fetch withdrawals (transfers) within date range
        $withdrawals = Withdrawal::where('user', $user->id)
            ->where('status', 'Processed')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($w) {
                return (object) [
                    'date'        => Carbon::parse($w->created_at),
                    'description' => $this->buildWithdrawalDescription($w),
                    'reference'   => $w->reference_number ?? $w->txn_id ?? '-',
                    'debit'       => $w->type === 'Debit' ? $w->amount : null,
                    'credit'      => $w->type === 'Credit' ? $w->amount : null,
                    'balance'     => $w->bal,
                    'source'      => 'withdrawal',
                ];
            });

        // Fetch deposits within date range
        $deposits = Deposit::where('user', $user->id)
            ->where('status', 'Processed')
            ->whereBetween('created_at', [$from, $to])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($d) {
                return (object) [
                    'date'        => Carbon::parse($d->created_at),
                    'description' => 'Deposit via ' . ($d->payment_mode ?? 'Bank Transfer'),
                    'reference'   => $d->txn_id ?? '-',
                    'debit'       => null,
                    'credit'      => $d->amount,
                    'balance'     => null,
                    'source'      => 'deposit',
                ];
            });

        // Merge and sort by date
        $transactions = $withdrawals->concat($deposits)
            ->sortBy('date')
            ->values();

        // Calculate running balance for entries that don't have one
        $runningBalance = null;
        foreach ($transactions as $txn) {
            if ($txn->balance !== null) {
                $runningBalance = (float) $txn->balance;
            } else {
                if ($runningBalance === null) {
                    $runningBalance = (float) $user->balance;
                }
                if ($txn->credit) {
                    $runningBalance += (float) $txn->credit;
                }
                if ($txn->debit) {
                    $runningBalance -= (float) $txn->debit;
                }
                $txn->balance = $runningBalance;
            }
        }

        // Summary totals
        $totalCredits = $transactions->sum('credit');
        $totalDebits  = $transactions->sum('debit');
        $closingBalance = $transactions->last() ? $transactions->last()->balance : $user->balance;
        $openingBalance = $transactions->first()
            ? ($transactions->first()->balance - ($transactions->first()->credit ?? 0) + ($transactions->first()->debit ?? 0))
            : $user->balance;

        $data = [
            'user'           => $user,
            'settings'       => $settings,
            'transactions'   => $transactions,
            'from'           => $from,
            'to'             => $to,
            'totalCredits'   => $totalCredits,
            'totalDebits'    => $totalDebits,
            'openingBalance' => $openingBalance,
            'closingBalance' => $closingBalance,
            'generatedAt'    => Carbon::now(),
        ];

        $pdf = Pdf::loadView('user.statement-pdf', $data);
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Statement_' . $user->usernumber . '_' . $from->format('Ymd') . '_' . $to->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Build a human-readable description for a withdrawal/transfer.
     */
    private function buildWithdrawalDescription(Withdrawal $w): string
    {
        $mode = $w->payment_mode ?? 'Transfer';
        $name = $w->accountname ?? '';

        if ($w->type === 'Credit') {
            $sender = $w->sender_name ?? 'Unknown';
            return "Credit — {$mode} from {$sender}";
        }

        return "Transfer — {$mode}" . ($name ? " to {$name}" : '');
    }
}
