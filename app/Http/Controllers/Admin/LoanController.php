<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User_plans;
use App\Models\User;
use App\Models\Settings;
use App\Notifications\LoanNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanApproved;
use App\Mail\LoanRejected;

class LoanController extends Controller
{
    /**
     * Display a listing of loan applications.
     */
    public function index(Request $request)
    {
        $settings = Settings::first();
        $query = User_plans::with('user');

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('active', $request->status);
        }

        // Search by user or loan details
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('usernumber', 'like', "%{$search}%");
                })
                ->orWhere('plan_id', 'like', "%{$search}%")
                ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        // Sort
        $orderBy = $request->get('orderBy', 'desc');
        $perPage = $request->get('perPage', 25);

        $loans = $query->orderBy('created_at', $orderBy)->paginate($perPage);

        // Statistics
        $total_loans = User_plans::count();
        $pending_loans = User_plans::where('active', 'Pending')->count();
        $approved_loans = User_plans::where('active', 'Processed')->count();
        $rejected_loans = User_plans::where('active', 'No')->count();
        $total_amount = User_plans::where('active', 'Processed')->sum('amount');
        $pending_amount = User_plans::where('active', 'Pending')->sum('amount');

        return view('admin.loans.index', [
            'title' => 'Loan Management',
            'loans' => $loans,
            'settings' => $settings,
            'total_loans' => $total_loans,
            'pending_loans' => $pending_loans,
            'approved_loans' => $approved_loans,
            'rejected_loans' => $rejected_loans,
            'total_amount' => $total_amount,
            'pending_amount' => $pending_amount,
        ]);
    }

    /**
     * Display the specified loan application.
     */
    public function show($id)
    {
        $loan = User_plans::with('user')->findOrFail($id);
        
        return view('admin.loans.show', [
            'title' => 'Loan Application Details',
            'loan' => $loan,
        ]);
    }

    /**
     * Display pending loans.
     */
    public function pending(Request $request)
    {
        $perPage = $request->get('perPage', 15);
        $search = $request->get('search', '');
        $settings = Settings::first();

        $query = User_plans::with('user')->where('active', 'Pending');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('usernumber', 'like', "%{$search}%");
                })
                ->orWhere('plan_name', 'like', "%{$search}%")
                ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $total_loans = User_plans::where('active', 'Pending')->count();
        $pending_loans = $total_loans;
        $approved_loans = User_plans::where('active', 'Processed')->count();
        $rejected_loans = User_plans::where('active', 'No')->count();
        $total_amount = User_plans::where('active', 'Processed')->sum('amount');
        $pending_amount = User_plans::where('active', 'Pending')->sum('amount');
        $title = 'Pending Loan Applications';

        return view('admin.loans.index', compact(
            'loans',
            'settings',
            'total_loans',
            'pending_loans',
            'approved_loans',
            'rejected_loans',
            'total_amount',
            'pending_amount',
            'title'
        ));
    }

    /**
     * Display approved loans.
     */
    public function approved(Request $request)
    {
        $perPage = $request->get('perPage', 15);
        $search = $request->get('search', '');
        $settings = Settings::first();

        $query = User_plans::with('user')->where('active', 'Processed');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('usernumber', 'like', "%{$search}%");
                })
                ->orWhere('plan_name', 'like', "%{$search}%")
                ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $total_loans = User_plans::where('active', 'Processed')->count();
        $pending_loans = User_plans::where('active', 'Pending')->count();
        $approved_loans = $total_loans;
        $rejected_loans = User_plans::where('active', 'No')->count();
        $total_amount = User_plans::where('active', 'Processed')->sum('amount');
        $pending_amount = User_plans::where('active', 'Pending')->sum('amount');
        $title = 'Approved Loans';

        return view('admin.loans.index', compact(
            'loans',
            'settings',
            'total_loans',
            'pending_loans',
            'approved_loans',
            'rejected_loans',
            'total_amount',
            'pending_amount',
            'title'
        ));
    }

    /**
     * Display rejected loans.
     */
    public function rejected(Request $request)
    {
        $perPage = $request->get('perPage', 15);
        $search = $request->get('search', '');
        $settings = Settings::first();

        $query = User_plans::with('user')->where('active', 'No');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('usernumber', 'like', "%{$search}%");
                })
                ->orWhere('plan_name', 'like', "%{$search}%")
                ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $total_loans = User_plans::where('active', 'No')->count();
        $pending_loans = User_plans::where('active', 'Pending')->count();
        $approved_loans = User_plans::where('active', 'Processed')->count();
        $rejected_loans = $total_loans;
        $total_amount = User_plans::where('active', 'Processed')->sum('amount');
        $pending_amount = User_plans::where('active', 'Pending')->sum('amount');
        $title = 'Rejected Loans';

        return view('admin.loans.index', compact(
            'loans',
            'settings',
            'total_loans',
            'pending_loans',
            'approved_loans',
            'rejected_loans',
            'total_amount',
            'pending_amount',
            'title'
        ));
    }

    /**
     * Display active loans.
     */
    public function active(Request $request)
    {
        $perPage = $request->get('perPage', 15);
        $search = $request->get('search', '');
        $settings = Settings::first();

        $query = User_plans::with('user')->where('active', 'yes');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('usernumber', 'like', "%{$search}%");
                })
                ->orWhere('plan_name', 'like', "%{$search}%")
                ->orWhere('amount', 'like', "%{$search}%");
            });
        }

        $loans = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $total_loans = User_plans::where('active', 'yes')->count();
        $pending_loans = User_plans::where('active', 'Pending')->count();
        $approved_loans = User_plans::where('active', 'Processed')->count();
        $rejected_loans = User_plans::where('active', 'No')->count();
        $total_amount = User_plans::where('active', 'Processed')->sum('amount');
        $pending_amount = User_plans::where('active', 'Pending')->sum('amount');
        $title = 'Active Loans';

        return view('admin.loans.index', compact(
            'loans',
            'settings',
            'total_loans',
            'pending_loans',
            'approved_loans',
            'rejected_loans',
            'total_amount',
            'pending_amount',
            'title'
        ));
    }

    /**
     * Approve a loan application.
     */
    public function approve(Request $request, $id)
    {
        $loan = User_plans::findOrFail($id);
        $user = User::findOrFail($loan->user);
        $settings = Settings::first();

        // Update loan status
        $loan->update([
            'active' => 'Processed',
        ]);

        // Credit user account
        $user->update([
            'account_bal' => $user->account_bal + $loan->amount,
        ]);

        return redirect()->back()->with('success', 'Loan approved successfully and funds credited to user account.');
    }

    /**
     * Reject a loan application.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $loan = User_plans::findOrFail($id);
        $user = User::findOrFail($loan->user);
        $settings = Settings::first();

        // Update loan status
        $loan->update([
            'active' => 'No',
            'rejection_reason' => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Loan application rejected successfully.');
    }

    /**
     * Delete a loan application.
     */
    public function destroy($id)
    {
        $loan = User_plans::findOrFail($id);
        $loan->delete();

        return redirect()->route('admin.loans.index')->with('success', 'Loan application deleted successfully.');
    }

    /**
     * Export loans to CSV or Excel.
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,excel',
            'status' => 'nullable|string',
        ]);

        $query = User_plans::with('user');

        if ($request->has('status') && $request->status != '') {
            $query->where('active', $request->status);
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        if ($request->format == 'csv') {
            return $this->exportToCsv($loans);
        } else {
            return $this->exportToExcel($loans);
        }
    }

    /**
     * Export loans to CSV.
     */
    private function exportToCsv($loans)
    {
        $filename = 'loans_' . date('Y-m-d_H-i-s') . '.csv';
        $handle = fopen('php://output', 'w');

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        // Add CSV headers
        fputcsv($handle, [
            'ID',
            'User Name',
            'Email',
            'Account Number',
            'Loan Type',
            'Amount',
            'Duration',
            'Purpose',
            'Monthly Income',
            'Status',
            'Date Applied',
        ]);

        // Add data
        foreach ($loans as $loan) {
            fputcsv($handle, [
                $loan->id,
                $loan->user->name ?? 'N/A',
                $loan->user->email ?? 'N/A',
                $loan->user->usernumber ?? 'N/A',
                $loan->plan_id ?? 'N/A',
                $loan->amount,
                $this->formatDuration($loan->duration),
                $loan->purpose ?? 'N/A',
                $loan->income ?? 'N/A',
                $loan->active,
                $loan->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($handle);
        exit;
    }

    /**
     * Format duration display.
     */
    private function formatDuration($months)
    {
        if ($months < 12) {
            return $months . ($months == 1 ? ' Month' : ' Months');
        }
        
        $years = floor($months / 12);
        $remainingMonths = $months % 12;
        
        $result = $years . ($years == 1 ? ' Year' : ' Years');
        
        if ($remainingMonths > 0) {
            $result .= ' ' . $remainingMonths . ($remainingMonths == 1 ? ' Month' : ' Months');
        }
        
        return $result;
    }
}
