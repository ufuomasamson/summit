<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\NewNotification;
use App\Mail\WithdrawalStatus;
use App\Models\Settings;
use App\Models\User;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use App\Traits\PingServer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ManageWithdrawalController extends Controller
{
    use PingServer;

    //process withdrawals
    public function pwithdrawal(Request $request)
    {
        $withdrawal = Withdrawal::where('id', $request->id)->first();
        $user = User::where('id', $withdrawal->user)->first();

        if ($request->date != ' ') {
            $created_at = $request->date;
        } else {
            $created_at = $withdrawal->created_at;
        }

        $settings = Settings::where('id', '=', '1')->first();

        if ($request->action == "Paid") {
            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Processed',
                    'created_at' => $created_at,
                    'processed_by' => auth('admin')->id(),
                    'processed_at' => now(),
                ]);

            $withdrawal = Withdrawal::where('id', $request->id)->first();
            Mail::to($user->email)->send(new WithdrawalStatus($withdrawal, $user, 'Successful Transfer'));

        } elseif ($request->action == "On-hold") {
            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'On-hold',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer request of $settings->currency$withdrawal->amount to $withdrawal->accountname is currently On-hold; contact support on $settings->contact_email for more details.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transfer On-hold', $user->name));

        } elseif ($request->action == "Under-review") {
            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Under Review',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer request of $settings->currency$withdrawal->amount to $withdrawal->accountname is currently under compliance review. This is a standard procedure and your transaction will be updated shortly.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transaction Under Review', $user->name));

        } elseif ($request->action == "Frozen") {
            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Frozen',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer request of $settings->currency$withdrawal->amount to $withdrawal->accountname has been temporarily frozen pending further verification. Please contact support at $settings->contact_email for more information.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transaction Frozen', $user->name));

        } elseif ($request->action == "Scheduled") {
            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Scheduled',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer request of $settings->currency$withdrawal->amount to $withdrawal->accountname has been scheduled for processing.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transfer Scheduled', $user->name));

        } elseif ($request->action == "Failed") {
            // Failed = technical failure, refund balance
            if ($withdrawal->user == $user->id && $withdrawal->type === 'Debit') {
                User::where('id', $user->id)
                    ->update([
                        'account_bal' => $user->account_bal + $withdrawal->to_deduct,
                    ]);
            }

            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Failed',
                    'created_at' => $created_at,
                ]);

            $message = "We regret to inform you that your transfer request of $settings->currency$withdrawal->amount to $withdrawal->accountname has failed due to a processing error. Your account has been refunded. Please try again or contact support.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transfer Failed', $user->name));

        } elseif ($request->action == "Cancelled") {
            // Cancelled = user/admin cancellation, refund balance
            if ($withdrawal->user == $user->id && $withdrawal->type === 'Debit') {
                User::where('id', $user->id)
                    ->update([
                        'account_bal' => $user->account_bal + $withdrawal->to_deduct,
                    ]);
            }

            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Cancelled',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer request of $settings->currency$withdrawal->amount to $withdrawal->accountname has been cancelled. Your account balance has been restored.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transfer Cancelled', $user->name));

        } elseif ($request->action == "Reversed") {
            // Reversed = completed transaction reversed, refund balance
            if ($withdrawal->user == $user->id && $withdrawal->type === 'Debit') {
                User::where('id', $user->id)
                    ->update([
                        'account_bal' => $user->account_bal + $withdrawal->to_deduct,
                    ]);
            }

            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Reversed',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer of $settings->currency$withdrawal->amount to $withdrawal->accountname has been reversed. The amount has been credited back to your account.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transfer Reversed', $user->name));

        } elseif ($request->action == "Refunded") {
            // Refunded = money returned to sender
            if ($withdrawal->user == $user->id && $withdrawal->type === 'Debit') {
                User::where('id', $user->id)
                    ->update([
                        'account_bal' => $user->account_bal + $withdrawal->to_deduct,
                    ]);
            }

            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Refunded',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer of $settings->currency$withdrawal->amount to $withdrawal->accountname has been refunded. The amount has been credited back to your account.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transfer Refunded', $user->name));

        } elseif ($request->action == "Expired") {
            // Expired = timed out, refund balance
            if ($withdrawal->user == $user->id && $withdrawal->type === 'Debit') {
                User::where('id', $user->id)
                    ->update([
                        'account_bal' => $user->account_bal + $withdrawal->to_deduct,
                    ]);
            }

            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Expired',
                    'created_at' => $created_at,
                ]);

            $message = "This is to inform you that your transfer request of $settings->currency$withdrawal->amount to $withdrawal->accountname has expired and could not be processed in time. Your account has been refunded.";
            Mail::to($user->email)->send(new NewNotification($message, 'Transfer Expired', $user->name));

        } else {
            // Default: Reject
            if ($withdrawal->user == $user->id && $withdrawal->type === 'Debit') {
                User::where('id', $user->id)
                    ->update([
                        'account_bal' => $user->account_bal + $withdrawal->to_deduct,
                    ]);
            }

            Withdrawal::where('id', $request->id)
                ->update([
                    'status' => 'Rejected',
                    'created_at' => $created_at,
                ]);

            if ($request->emailsend == "true") {
                Mail::to($user->email)->send(new NewNotification($request->reason, $request->subject, $user->name));
            }
        }

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Action Successful!');
        }
        return redirect()->route('mwithdrawals')->with('success', 'Action Successful!');
    }


    public function processwithdraw($id)
    {
        $with = Withdrawal::where('id', $id)->first();
        $method = Wdmethod::where('name', $with->payment_mode)->first();
        $user = User::where('id', $with->user)->first();
        return view('admin.Withdrawals.pwithrdawal', [
            'withdrawal' => $with,
            'method' => $method,
            'user' => $user,
            'title' => 'Process withdrawal Request',
        ]);
    }

    public function updateWithdrawal(Request $request, $id)
    {
        // Validate request
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0',
            'type' => 'required|in:Credit,Debit',
            'status' => 'required|' . Withdrawal::getStatusValidationRule(),
            'payment_mode' => 'required|string|max:255',
            'accountname' => 'required|string|max:255',
            'accountnumber' => 'required|string|max:255',
            'Description' => 'nullable|string|max:500',
            'bankname' => 'nullable|string|max:255',
            'Accounttype' => 'nullable|string|max:50',
            'bankaddress' => 'nullable|string|max:500',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'zipcode' => 'nullable|string|max:20',
            'swiftcode' => 'nullable|string|max:11',
            'iban' => 'nullable|string|max:34',
            'routingnumber' => 'nullable|string|max:50',
            'paydetails' => 'nullable|string|max:1000',
            'sender_name' => 'nullable|string|max:255',
            'sender_bank' => 'nullable|string|max:255',
            'sender_account_number' => 'nullable|string|max:100',
            'sender_bank_address' => 'nullable|string|max:500',
            'reference_number' => 'nullable|string|max:100',
            'admin_note' => 'nullable|string|max:1000',
            'bal' => 'nullable|numeric',
            'to_deduct' => 'nullable|numeric',
            'date' => 'nullable|date',
        ]);

        // Find withdrawal
        $withdrawal = Withdrawal::findOrFail($id);

        // Update all fields
        $withdrawal->amount = $request->amount;
        $withdrawal->type = $request->type;
        $withdrawal->status = $request->status;
        $withdrawal->payment_mode = $request->payment_mode;
        $withdrawal->accountname = $request->accountname;
        $withdrawal->accountnumber = $request->accountnumber;
        $withdrawal->Description = $request->Description;
        $withdrawal->bankname = $request->bankname;
        $withdrawal->Accounttype = $request->Accounttype;
        $withdrawal->bankaddress = $request->bankaddress;
        $withdrawal->country = $request->country;
        $withdrawal->city = $request->city;
        $withdrawal->zipcode = $request->zipcode;
        $withdrawal->swiftcode = $request->swiftcode;
        $withdrawal->iban = $request->iban;
        $withdrawal->routingnumber = $request->routingnumber;
        $withdrawal->paydetails = $request->paydetails;
        
        // Sender/Party information
        $withdrawal->sender_name = $request->sender_name;
        $withdrawal->sender_bank = $request->sender_bank;
        $withdrawal->sender_account_number = $request->sender_account_number;
        $withdrawal->sender_bank_address = $request->sender_bank_address;
        $withdrawal->reference_number = $request->reference_number;
        
        // Admin tracking
        $withdrawal->admin_note = $request->admin_note;
        $withdrawal->processed_by = auth('admin')->id();
        $withdrawal->processed_at = now();
        
        // Additional fields
        if ($request->filled('bal')) {
            $withdrawal->bal = $request->bal;
        }
        if ($request->filled('to_deduct')) {
            $withdrawal->to_deduct = $request->to_deduct;
        }
        if ($request->filled('date')) {
            $withdrawal->created_at = $request->date;
        }

        // Handle balance adjustments based on status changes
        $oldStatus = $withdrawal->getOriginal('status');
        $newStatus = $request->status;
        
        if ($oldStatus !== $newStatus) {
            $user = User::find($withdrawal->user);
            
            if ($user) {
                // If changing FROM a processed state TO a refund/reversal state, return balance
                if ($oldStatus === 'Processed' && Withdrawal::shouldRefundBalance($newStatus) && $withdrawal->type === 'Debit') {
                    $user->update(['account_bal' => $user->account_bal + $withdrawal->to_deduct]);
                }
                
                // If changing FROM a refund/reversal state TO processed, deduct balance again
                if (Withdrawal::shouldRefundBalance($oldStatus) && $newStatus === 'Processed' && $withdrawal->type === 'Debit') {
                    $user->update(['account_bal' => $user->account_bal - $withdrawal->to_deduct]);
                }
            }
        }

        $withdrawal->save();

        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->with('success', 'Withdrawal updated successfully!');
        }
        return redirect()->route('mwithdrawals')->with('success', 'Withdrawal updated successfully!');
    }

    public function deleteWithdrawal(Request $request, $id)
    {
        $withdrawal = Withdrawal::findOrFail($id);
        $txnId = $withdrawal->txn_id;
        
        $withdrawal->delete();
        
        if ($request->filled('redirect_to')) {
            return redirect($request->redirect_to)->with('success', "Transaction {$txnId} deleted successfully!");
        }
        return redirect()->route('mwithdrawals')->with('success', "Transaction {$txnId} deleted successfully!");
    }
}
