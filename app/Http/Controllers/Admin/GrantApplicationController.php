<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GrantApplication;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GrantApplicationController extends Controller
{
    /**
     * Show all grant applications dashboard
     */
    public function index()
    {
        $settings = Settings::latest()->first();
        $title = "Grant Applications";
        
        $applications = GrantApplication::with('user')->orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total' => GrantApplication::count(),
            'processing' => GrantApplication::where('status', 'processing')->count(),
            'approved' => GrantApplication::where('status', 'approved')->count(),
            'rejected' => GrantApplication::where('status', 'rejected')->count(),
            'disbursed' => GrantApplication::where('status', 'disbursed')->count(),
            'total_requested' => GrantApplication::sum('requested_amount'),
            'total_approved' => GrantApplication::whereIn('status', ['approved', 'disbursed'])->sum('approved_amount'),
            'total_disbursed' => GrantApplication::where('status', 'disbursed')->sum('approved_amount'),
        ];
        
        return view('admin.grants.index', compact('settings', 'title', 'applications', 'stats'));
    }

    /**
     * Show pending applications
     */
    public function pending()
    {
        $settings = Settings::latest()->first();
        $title = "Pending Grant Applications";
        
        $applications = GrantApplication::with('user')
            ->where('status', 'processing')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.grants.pending', compact('settings', 'title', 'applications'));
    }

    /**
     * Show approved applications
     */
    public function approved()
    {
        $settings = Settings::latest()->first();
        $title = "Approved Grant Applications";
        
        $applications = GrantApplication::with('user')
            ->where('status', 'approved')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('admin.grants.approved', compact('settings', 'title', 'applications'));
    }

    /**
     * Show rejected applications
     */
    public function rejected()
    {
        $settings = Settings::latest()->first();
        $title = "Rejected Grant Applications";
        
        $applications = GrantApplication::with('user')
            ->where('status', 'rejected')
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('admin.grants.rejected', compact('settings', 'title', 'applications'));
    }

    /**
     * Show disbursed applications
     */
    public function disbursed()
    {
        $settings = Settings::latest()->first();
        $title = "Disbursed Grants";
        
        $applications = GrantApplication::with('user')
            ->where('status', 'disbursed')
            ->orderBy('disbursal_date', 'desc')
            ->get();
        
        return view('admin.grants.disbursed', compact('settings', 'title', 'applications'));
    }

    /**
     * View single application
     */
    public function view($id)
    {
        $settings = Settings::latest()->first();
        $title = "View Grant Application";
        
        $application = GrantApplication::with('user')->findOrFail($id);
        
        return view('admin.grants.view', compact('settings', 'title', 'application'));
    }

    /**
     * Approve application
     */
    public function approve(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'approved_amount' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Please enter a valid approved amount.');
        }

        $application = GrantApplication::findOrFail($id);
        
        if ($application->status !== 'processing') {
            return back()->with('error', 'This application cannot be approved as it is not in processing status.');
        }

        $application->status = 'approved';
        $application->approved_amount = $request->approved_amount;
        $application->save();
        
        $adminName = auth()->guard('admin')->user()->firstName . ' ' . auth()->guard('admin')->user()->lastName;
        $application->addNote("Admin ({$adminName}) approved application with amount: " . number_format($request->approved_amount, 2));

        return back()->with('success', 'Grant application approved successfully.');
    }

    /**
     * Reject application
     */
    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Please provide a rejection reason (minimum 10 characters).');
        }

        $application = GrantApplication::findOrFail($id);
        
        if ($application->status !== 'processing') {
            return back()->with('error', 'This application cannot be rejected as it is not in processing status.');
        }

        $application->status = 'rejected';
        $application->save();
        
        $adminName = auth()->guard('admin')->user()->firstName . ' ' . auth()->guard('admin')->user()->lastName;
        $application->addNote("Admin ({$adminName}) rejected application. Reason: {$request->rejection_reason}");

        return back()->with('success', 'Grant application rejected.');
    }

    /**
     * Disburse funds
     */
    public function disburse(Request $request, $id)
    {
        $application = GrantApplication::with('user')->findOrFail($id);
        
        if ($application->status !== 'approved') {
            return back()->with('error', 'Only approved applications can be disbursed.');
        }

        if (!$application->approved_amount || $application->approved_amount <= 0) {
            return back()->with('error', 'Invalid approved amount for disbursement.');
        }

        DB::beginTransaction();
        
        try {
            // Add funds to user account
            $user = $application->user;
            $user->account_bal += $application->approved_amount;
            $user->save();

            // Update application status
            $application->status = 'disbursed';
            $application->disbursal_date = now();
            $application->save();
            
            $adminName = auth()->guard('admin')->user()->firstName . ' ' . auth()->guard('admin')->user()->lastName;
            $application->addNote("Admin ({$adminName}) disbursed funds: " . number_format($application->approved_amount, 2) . " to user account.");

            DB::commit();
            
            return back()->with('success', 'Funds disbursed successfully to user account.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to disburse funds: ' . $e->getMessage());
        }
    }

    /**
     * Add admin note
     */
    public function addNote(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'note' => 'required|string|min:5',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Please provide a valid note (minimum 5 characters).');
        }

        $application = GrantApplication::findOrFail($id);
        
        $adminName = auth()->guard('admin')->user()->firstName . ' ' . auth()->guard('admin')->user()->lastName;
        $application->addNote("Admin ({$adminName}): {$request->note}");

        return back()->with('success', 'Note added successfully.');
    }

    /**
     * Delete application
     */
    public function delete($id)
    {
        $application = GrantApplication::findOrFail($id);
        
        // Prevent deletion of disbursed applications
        if ($application->status === 'disbursed') {
            return back()->with('error', 'Cannot delete disbursed applications.');
        }
        
        $application->delete();
        
        return redirect()->route('admin.grants.index')->with('success', 'Grant application deleted successfully.');
    }
}
