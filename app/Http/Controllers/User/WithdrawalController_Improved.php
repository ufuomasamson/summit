<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Withdrawal Controller - Refactored Version
 * 
 * Key Improvements:
 * 1. DRY principle - Eliminated code duplication
 * 2. Better error handling with try-catch blocks
 * 3. Database transactions for data integrity
 * 4. Consistent validation and response patterns
 * 5. Better method organization and naming
 * 6. Proper use of Laravel helpers (Str::random instead of custom function)
 * 7. Type hints and return types for better code clarity
 * 8. Meaningful variable names
 * 9. Separation of concerns - private helper methods
 * 10. Better session key naming ('transfer_data' instead of 'data')
 */
class WithdrawalController extends Controller
{
    /**
     * Display confirmation page
     */
    public function confirm()
    {
        $confirmData = Session::get('transfer_data');
        
        if (!$confirmData) {
            return redirect()->route('dashboard')->with('message', 'No transfer data found.');
        }

        return view('user.confirm', [
            'title' => 'Confirm Transfer Transaction',
            'confirmdata' => $confirmData
        ]);
    }

    /**
     * Cancel confirmation and clear session
     */
    public function cancelconfirm()
    {
        Session::forget('transfer_data');
        return redirect()->route('dashboard')->with('message', 'Transfer cancelled.');
    }

    /**
     * Proceed with transaction verification
     */
    public function proceedtranscation()
    {
        $settings = Settings::first();

        if (!$settings) {
            return redirect()->route('dashboard')->with('message', 'System configuration error.');
        }

        // Check verification requirements in order of priority
        if ($settings->code1status == 1) {
            return redirect()->route('code1verification');
        }

        if ($settings->code2status == 1) {
            return redirect()->route('verificationcode2');
        }

        if ($settings->code3status == 1) {
            return redirect()->route('verification3code');
        }

        if ($settings->otp == 1) {
            return redirect()->route('getotp');
        }

        return redirect()->route('previewtransfer');
    }

    /**
     * Generate and send OTP
     */
    public function getotp()
    {
        $user = Auth::user();

        if ($user->transferaction == 1) {
            return back()->with('message', 'A transaction is already in progress.');
        }

        $otp = $this->generateOTP(6);
        
        $user->update(['withdrawotp' => $otp]);
        
        $settings = Settings::first();

        $message = "You recently initiated a transfer from your {$settings->site_name} {$user->accounttype} account.\n\n"
                 . "If this was you, use the code below to complete your transaction:\n\n"
                 . "OTP: {$otp}\n\n"
                 . "If you did not initiate this transaction, please ignore this email or contact support immediately.";

        try {
            Mail::to($user->email)->send(new NewNotification(
                $message,
                "Transaction OTP Verification",
                $user->name
            ));
        } catch (\Exception $e) {
            Log::error('OTP email failed: ' . $e->getMessage());
        }

        return redirect()->route('otpview');
    }

    /**
     * Display OTP input view
     */
    public function otpview()
    {
        return view('user.otp')->with('success', 'OTP has been sent to your email.');
    }

    /**
     * Handle loan request
     */
    public function loan(Request $request)
    {
        return redirect()->route('dashboard')
            ->with('success', 'Loan request submitted successfully. We will process it shortly.');
    }

    /**
     * Handle local transfer
     */
    public function localtransfer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'accountname' => 'required|string',
            'accountnumber' => 'required|string',
            'bankname' => 'required|string',
            'Accounttype' => 'required|string',
            'pin' => 'required|string',
            'Description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $settings = Settings::first();

        // Validate transfer eligibility
        $validationError = $this->validateTransferEligibility($user, $settings, $request->amount, $request->pin);
        if ($validationError) {
            return back()->with('message', $validationError);
        }

        // Prepare transfer data
        $transferData = [
            'bankname' => $request->bankname,
            'amount' => $request->amount,
            'accountname' => $request->accountname,
            'accountnumber' => $request->accountnumber,
            'Accounttype' => $request->Accounttype,
            'Description' => $request->Description ?? 'Local bank transfer',
            'user' => $user->id,
            'payment_mode' => 'Domestic Transfer',
            'date' => Carbon::now(),
            'txn_id' => $this->generateTransactionId($settings->site_name),
        ];

        Session::put('transfer_data', $transferData);

        // Reset transfer action flag
        $user->update(['transferaction' => 0]);

        return redirect()->route('confirm');
    }

    /**
     * Handle international transfer
     */
    public function internationaltransfer(Request $request)
    {
        $request->validate([
            'accountname' => 'required|string',
            'accountnumber' => 'required|string',
            'amount' => 'required|numeric|min:1',
            'bankname' => 'required|string',
            'bankaddress' => 'nullable|string',
            'country' => 'nullable|string',
            'Accounttype' => 'nullable|string',
            'pin' => 'required|string',
            'swiftcode' => 'nullable|string|max:11',
            'iban' => 'nullable|string|max:34',
            'Description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $settings = Settings::first();

        // Validate transfer eligibility
        $validationError = $this->validateTransferEligibility($user, $settings, $request->amount, $request->pin);
        if ($validationError) {
            return back()->with('message', $validationError);
        }

        // Prepare transfer data
        $transferData = [
            'bankname' => $request->bankname,
            'country' => $request->country,
            'amount' => $request->amount,
            'accountname' => $request->accountname,
            'accountnumber' => $request->accountnumber,
            'Accounttype' => $request->Accounttype ?? 'Online Banking',
            'Description' => $request->Description ?? 'International wire transfer',
            'iban' => $request->iban,
            'swiftcode' => $request->swiftcode,
            'bankaddress' => $request->bankaddress,
            'user' => $user->id,
            'payment_mode' => 'Wire Transfer',
            'date' => Carbon::now(),
            'txn_id' => $this->generateTransactionId($settings->site_name),
        ];

        Session::put('transfer_data', $transferData);

        // Reset transfer action flag
        $user->update(['transferaction' => 0]);

        return redirect()->route('confirm');
    }

    /**
     * Handle cryptocurrency transfer
     */
    public function cryptocurrency(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'accountnumber' => 'required|string',
            'method' => 'required|string',
            'accountname' => 'nullable|string',
            'Accounttype' => 'nullable|string',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $settings = Settings::first();

        // Validate transfer eligibility (no PIN for crypto)
        if ($user->allowtransfer == 1) {
            return back()->with('message', "Your account is dormant. Contact support at {$settings->contact_email}.");
        }

        if ($user->account_bal < $request->amount) {
            return back()->with('message', 'Insufficient account balance.');
        }

        // Prepare transfer data
        $transferData = [
            'bankname' => $request->method,
            'amount' => $request->amount,
            'accountname' => $request->accountname ?? $request->method . ' Transfer',
            'accountnumber' => $request->accountnumber,
            'Accounttype' => $request->Accounttype ?? 'Cryptocurrency',
            'Description' => $request->description ?? 'Cryptocurrency transfer',
            'user' => $user->id,
            'payment_mode' => 'Cryptocurrency',
            'date' => Carbon::now(),
            'txn_id' => $this->generateTransactionId($settings->site_name),
        ];

        Session::put('transfer_data', $transferData);

        // Reset transfer action flag
        $user->update(['transferaction' => 0]);

        return redirect()->route('confirm');
    }

    /**
     * Handle PayPal transfer
     */
    public function paypal(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string',
            'Description' => 'nullable|string|max:500',
        ]);

        return $this->handleThirdPartyTransfer($request, 'Paypal');
    }

    /**
     * Handle Payoneer transfer
     */
    public function payoneer(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'method' => 'required|string',
        ]);

        return $this->handleThirdPartyTransfer($request, 'Payoneer');
    }

    /**
     * Handle TransferWise transfer
     */
    public function transferwise(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'method' => 'required|string',
        ]);

        return $this->handleThirdPartyTransfer($request, 'Transfer Wise');
    }

    /**
     * Handle Google Pay transfer
     */
    public function googlepay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'method' => 'required|string',
        ]);

        return $this->handleThirdPartyTransfer($request, 'Google Pay');
    }

    /**
     * Handle Skrill transfer
     */
    public function skrill(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'email' => 'required|email',
            'method' => 'required|string',
        ]);

        return $this->handleThirdPartyTransfer($request, 'Skrill');
    }

    /**
     * Handle Western Union transfer
     */
    public function westernunion(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'country' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string|max:500',
            'method' => 'required|string',
        ]);

        $user = Auth::user();
        $settings = Settings::first();

        // Validate transfer eligibility (no PIN for Western Union)
        if ($user->allowtransfer == 1) {
            return back()->with('message', "Your account is dormant. Contact support at {$settings->contact_email}.");
        }

        if ($user->account_bal < $request->amount) {
            return back()->with('message', 'Insufficient account balance.');
        }

        // Prepare transfer data
        $transferData = [
            'country' => $request->country,
            'amount' => $request->amount,
            'accountname' => $request->name,
            'bankname' => $request->method,
            'accountnumber' => $request->name,
            'Accounttype' => $request->method,
            'Description' => $request->description,
            'zipcode' => $request->zipcode,
            'city' => $request->city,
            'user' => $user->id,
            'payment_mode' => 'Western Union',
            'date' => Carbon::now(),
            'txn_id' => $this->generateTransactionId($settings->site_name),
        ];

        Session::put('transfer_data', $transferData);

        // Reset transfer action flag
        $user->update(['transferaction' => 0]);

        return redirect()->route('confirm');
    }

    /**
     * Verify codes (code1, code2, code3, OTP)
     */
    public function codecomfirm(Request $request)
    {
        $user = Auth::user();
        $settings = Settings::first();

        // Verify Code 1
        if ($request->filled('code1')) {
            $request->validate(['code1' => 'required']);

            if ($user->code1 != $request->code1) {
                return back()->with('message', "Invalid {$settings->code1}. Contact support at {$settings->contact_email}.");
            }

            return $this->proceedToNextVerification($settings);
        }

        // Verify Code 2
        if ($request->filled('code2')) {
            $request->validate(['code2' => 'required']);

            if ($user->code2 != $request->code2) {
                return back()->with('message', "Invalid {$settings->code2}. Contact support at {$settings->contact_email}.");
            }

            // Check for next verification step
            if ($settings->code3status == 1) {
                return redirect()->route('verification3code');
            }

            if ($settings->otp == 1) {
                return redirect()->route('getotp');
            }

            return redirect()->route('previewtransfer');
        }

        // Verify Code 3
        if ($request->filled('code3')) {
            $request->validate(['code3' => 'required']);

            if ($user->code3 != $request->code3) {
                return back()->with('message', "Invalid {$settings->code3}. Contact support at {$settings->contact_email}.");
            }

            if ($settings->otp == 1) {
                return redirect()->route('getotp');
            }

            return redirect()->route('previewtransfer');
        }

        // Verify OTP
        if ($request->filled('otp')) {
            if ($user->withdrawotp != $request->otp) {
                return back()->with('message', 'Invalid OTP code.');
            }
        }

        return redirect()->route('previewtransfer');
    }

    /**
     * Preview and process transfer
     */
    public function previewtransfer(Request $request)
    {
        $transferData = Session::get('transfer_data');

        if (!$transferData) {
            return redirect()->route('dashboard')->with('message', 'No transfer data found.');
        }

        $user = Auth::user();
        $settings = Settings::first();

        // Check if transaction already processed
        if ($user->transferaction == 1) {
            return redirect()->route('dashboard')
                ->with('success', 'Transaction was successful and will be processed soon.');
        }

        // Final balance check
        if ($user->account_bal < $transferData['amount']) {
            return back()->with('message', 'Insufficient balance for this transaction.');
        }

        // Process the transfer in a database transaction
        DB::beginTransaction();
        try {
            $withdrawal = $this->processWithdrawal($user, $transferData, $settings);
            
            // Mark transaction as completed
            $user->update([
                'transferaction' => 1,
                'withdrawotp' => null
            ]);

            DB::commit();

            // Send notifications
            $this->sendTransferNotifications($withdrawal, $user, $settings);

            // Clear session data
            Session::forget('transfer_data');

            return view('user.preview', [
                'settings' => $settings,
                'dp' => $withdrawal,
                'code' => $withdrawal->txn_id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Transfer processing failed: ' . $e->getMessage());
            
            return back()->with('message', 'Transfer failed. Please try again or contact support.');
        }
    }

    // =====================================================
    // PRIVATE HELPER METHODS
    // =====================================================

    /**
     * Process withdrawal and debit user account
     */
    private function processWithdrawal(User $user, array $data, Settings $settings): Withdrawal
    {
        $newBalance = $user->account_bal - $data['amount'];

        // Debit user account
        $user->update(['account_bal' => $newBalance]);

        // Create withdrawal record
        $withdrawal = new Withdrawal();
        $withdrawal->amount = $data['amount'];
        $withdrawal->to_deduct = $data['amount'];
        $withdrawal->payment_mode = $data['payment_mode'];
        $withdrawal->status = 'Pending';
        $withdrawal->accountname = $data['accountname'];
        $withdrawal->accountnumber = $data['accountnumber'];
        $withdrawal->bankname = $data['bankname'] ?? null;
        $withdrawal->Accounttype = $data['Accounttype'] ?? null;
        $withdrawal->Description = $data['Description'];
        $withdrawal->date = $data['date'];
        $withdrawal->txn_id = $data['txn_id'];
        $withdrawal->user = $user->id;
        $withdrawal->bal = $newBalance;
        $withdrawal->type = 'Debit';

        // Add optional fields based on payment mode
        $optionalFields = ['country', 'bankaddress', 'swiftcode', 'iban', 'zipcode', 'city'];
        foreach ($optionalFields as $field) {
            if (isset($data[$field])) {
                $withdrawal->$field = $data[$field];
            }
        }

        $withdrawal->save();

        return $withdrawal;
    }

    /**
     * Send transfer notifications to user and admin
     */
    private function sendTransferNotifications(Withdrawal $withdrawal, User $user, Settings $settings): void
    {
        try {
            // Notify admin
            Mail::to($settings->contact_email)->send(
                new WithdrawalStatus($withdrawal, $user, 'Transfer Request', true)
            );

            // Notify user
            Mail::to($user->email)->send(
                new WithdrawalStatus($withdrawal, $user, 'Successful Transfer Request')
            );
        } catch (\Exception $e) {
            // Log email failure but don't stop the transaction
            Log::error('Transfer notification email failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle third-party transfers (PayPal, Skrill, Payoneer, etc.)
     */
    private function handleThirdPartyTransfer(Request $request, string $paymentMode)
    {
        $user = Auth::user();
        $settings = Settings::first();

        // Validate transfer eligibility
        if ($user->allowtransfer == 1) {
            return back()->with('message', "Your account is dormant. Contact support at {$settings->contact_email}.");
        }

        if ($user->account_bal < $request->amount) {
            return back()->with('message', 'Insufficient account balance.');
        }

        // Prepare transfer data
        $transferData = [
            'bankname' => $request->method,
            'amount' => $request->amount,
            'accountname' => $request->email,
            'accountnumber' => $request->email,
            'Accounttype' => $paymentMode,
            'Description' => $request->Description ?? "{$paymentMode} transaction",
            'user' => $user->id,
            'payment_mode' => $paymentMode,
            'date' => Carbon::now(),
            'txn_id' => $this->generateTransactionId($settings->site_name),
        ];

        Session::put('transfer_data', $transferData);

        // Reset transfer action flag
        $user->update(['transferaction' => 0]);

        return redirect()->route('confirm');
    }

    /**
     * Validate transfer eligibility
     */
    private function validateTransferEligibility(User $user, Settings $settings, float $amount, ?string $pin = null): ?string
    {
        if ($user->allowtransfer == 1) {
            return "Your account is dormant. Contact support at {$settings->contact_email}.";
        }

        if ($pin && $user->pin != $pin) {
            return "Incorrect transaction PIN.";
        }

        if ($amount < 1) {
            return "Minimum transfer amount is {$settings->currency}1.";
        }

        if ($user->account_bal < $amount) {
            return "Insufficient account balance.";
        }

        return null;
    }

    /**
     * Proceed to next verification step
     */
    private function proceedToNextVerification(Settings $settings)
    {
        if ($settings->code2status == 1) {
            return redirect()->route('verificationcode2');
        }

        if ($settings->code3status == 1) {
            return redirect()->route('verification3code');
        }

        if ($settings->otp == 1) {
            return redirect()->route('getotp');
        }

        return redirect()->route('previewtransfer');
    }

    /**
     * Generate transaction ID
     */
    private function generateTransactionId(string $siteName): string
    {
        $prefix = strtoupper(substr($siteName, 0, 2));
        $random1 = Str::upper(Str::random(8));
        $random2 = Str::upper(Str::random(5));
        
        return "{$prefix}{$random1}{$random2}";
    }

    /**
     * Generate OTP code
     */
    private function generateOTP(int $length = 6): string
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }
}
