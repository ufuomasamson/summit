<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use App\Mail\WithdrawalStatus;
use App\Traits\Coinpayment;
use App\Traits\CreatesNotifications;
use App\Services\SmsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

// New imports for refactoring
use App\Services\TransferService;
use App\Repositories\{WithdrawalRepository, UserRepository};
use App\Jobs\SendTransferEmail;
use App\Http\Requests\Transfer\{
    InternalTransferRequest,
    LocalTransferRequest,
    InternationalTransferRequest,
    ThirdPartyTransferRequest,
    CryptocurrencyTransferRequest
};


class WithdrawalController extends Controller
{
    use Coinpayment, CreatesNotifications;

    protected $transferService;
    protected $withdrawalRepo;
    protected $userRepo;
    protected $settings;

    public function __construct(
        TransferService $transferService,
        WithdrawalRepository $withdrawalRepo,
        UserRepository $userRepo
    ) {
        $this->transferService = $transferService;
        $this->withdrawalRepo = $withdrawalRepo;
        $this->userRepo = $userRepo;
        $this->settings = Settings::first();
    }


    public function confirm()
    {
        $condata = Session::get('data');
        $title = 'Confirm Transfer Transaction';
        $user = Auth::user();
        
        return view('user.confirm', [
            'title' => $title,
            'confirmdata' => $condata,
            'user' => $user
        ]);
    }


    public function cancelconfirm()
    {
        Session::forget('data');
        return redirect('dashboard');
    }


    public function  proceedtranscation()
    {
        sleep(2);
        $settings = Settings::where('id', 1)->first();
        $user = Auth::user();
        
        // Check user-specific settings first, fall back to global if null
        $code1Enabled = $user->code1_enabled ?? $settings->code1status;
        $code2Enabled = $user->code2_enabled ?? $settings->code2status;
        $code3Enabled = $user->code3_enabled ?? $settings->code3status;
        $otpEnabled = $user->otp_enabled ?? $settings->otp;
        
        if ($code1Enabled == 1) {
            return redirect()->route('code1verification');
        }

        if ($code2Enabled == 1) {
            return redirect()->route('verificationcode2');
        }

        if ($code3Enabled == 1) {
            return redirect()->route('verification3code');
        }
        
        if ($otpEnabled == 1) {
            return redirect()->route('getotp');
        }

        return redirect()->route('previewtransfer');
    }


    public function loan(Request $request)
    {

        return redirect()->route('dashboard')
            ->with('success', 'Action Sucessful! Please wait while we process your loan request.');
    }




    /**
     * Generate and send OTP to user's email
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getotp()
    {
        // Check if user has transfer action enabled
        if (Auth::user()->transferaction == 1) {
            return back()->with('error', 'Transfer action is currently restricted for your account.');
        }

        try {
            // Generate 6-digit numeric OTP
            $code = $this->generateNumericOTP(6);
            
            $user = Auth::user();
            $settings = Settings::where('id', '1')->first();

            // Store OTP with timestamp for expiration tracking (optional: add expires_at field)
            User::where('id', $user->id)->update([
                'withdrawotp' => $code,
                // 'otp_generated_at' => now(), // Uncomment if you add this field to track expiration
            ]);

            // Send OTP email with improved formatting
            $this->sendOTPEmail($user, $code, $settings);

            return redirect()->route('otpview')
                ->with('success', 'Verification code sent successfully! Please check your email.');
                
        } catch (\Exception $e) {
            \Log::error('OTP Generation Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }

    /**
     * Show OTP verification view
     * 
     * @return \Illuminate\View\View
     */
    function otpview()
    {
        return view('user.otp')->with('success', 'Action Successful! OTP has been sent to your email');
    }

    /**
     * Generate numeric OTP code
     * 
     * @param int $length
     * @return string
     */
    private function generateNumericOTP(int $length = 6): string
    {
        $otp = '';
        for ($i = 0; $i < $length; $i++) {
            $otp .= random_int(0, 9);
        }
        return $otp;
    }

    /**
     * Send OTP email to user with professional formatting
     * 
     * @param User $user
     * @param string $code
     * @param Settings $settings
     * @return void
     */
    private function sendOTPEmail($user, string $code, $settings): void
    {
        $demo = new \stdClass();
        $demo->user = $user;
        $demo->code = $code;
        $demo->subject = "Your Transaction Verification Code";
        
        Mail::to($user->email)->send(new \App\Mail\Twofa($demo, $settings));
    }

    /**
     * Verify account number for internal transfer
     */
    /**
     * Verify account number for internal transfer (AJAX)
     */
    public function verifyAccount(Request $request)
    {
        $request->validate([
            'accountnumber' => 'required|string'
        ]);

        $recipient = $this->userRepo->findByAccountNumber($request->accountnumber);

        if ($recipient) {
            return response()->json([
                'success' => true,
                'accountname' => $recipient->name,
                'email' => $recipient->email
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Account not found'
        ], 404);
    }

    /**
     * Handle internal transfer between users (REFACTORED)
     */
    public function internaltransfer(InternalTransferRequest $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();

            // Process transfer through service
            $result = $this->transferService->processInternalTransfer($validated, $user->id);

            if (!$result['success']) {
                return back()->with('message', $result['message'])->withInput();
            }

            $withdrawal = $result['withdrawal'];

            // Find recipient for email
            $recipient = $this->userRepo->findByAccountNumber($validated['accountnumber']);

            // Queue email notifications
            SendTransferEmail::dispatch(
                $withdrawal,
                $user,
                $user->email,
                'Successful Internal Transfer'
            );

            SendTransferEmail::dispatch(
                $withdrawal,
                $user,
                $this->settings->contact_email,
                'Internal Transfer Notification',
                true
            );

            return redirect()->route('dashboard')->with(
                'success',
                "Transfer of {$this->settings->currency}" . number_format($validated['amount'], 2) . 
                " to {$recipient->name} was successful!"
            );

        } catch (\Exception $e) {
            Log::error('Internal transfer failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('message', $e->getMessage())->withInput();
        }
    }

    /**
     * Handle local/domestic transfer (REFACTORED - stores in session for verification flow)
     */
    public function localtransfer_new(LocalTransferRequest $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();

            // Generate transaction ID
            $txnId = $this->generateTransactionId('LOCAL');

            // Store data in session for verification flow
            $transferData = array_merge($validated, [
                'payment_mode' => 'Domestic Transfer',
                'txn_id' => $txnId,
                'date' => Carbon::now(),
                'user' => $user->id
            ]);

            Session::put('data', $transferData);

            // Redirect to confirmation flow
            return redirect()->route('confirm');

        } catch (\Exception $e) {
            Log::error('Local transfer validation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('message', $e->getMessage())->withInput();
        }
    }

    /**
     * Handle international/wire transfer (REFACTORED - stores in session for verification flow)
     */
    public function internationaltransfer_new(InternationalTransferRequest $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();

            // Generate transaction ID
            $txnId = $this->generateTransactionId('WIRE');

            // Store data in session for verification flow
            $transferData = array_merge($validated, [
                'payment_mode' => 'Wire Transfer',
                'txn_id' => $txnId,
                'date' => Carbon::now(),
                'user' => $user->id
            ]);

            Session::put('data', $transferData);

            return redirect()->route('confirm');

        } catch (\Exception $e) {
            Log::error('International transfer validation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('message', $e->getMessage())->withInput();
        }
    }

    /**
     * Handle third-party transfer (REFACTORED - stores in session for verification flow)
     * Used for PayPal, Skrill, Payoneer, TransferWise
     */
    public function thirdpartytransfer_new(ThirdPartyTransferRequest $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();

            // Generate transaction ID
            $txnId = $this->generateTransactionId(strtoupper(substr($validated['bankname'], 0, 3)));

            // Store data in session for verification flow
            $transferData = array_merge($validated, [
                'payment_mode' => $validated['bankname'],
                'txn_id' => $txnId,
                'date' => Carbon::now(),
                'user' => $user->id
            ]);

            Session::put('data', $transferData);

            return redirect()->route('confirm');

        } catch (\Exception $e) {
            Log::error('Third-party transfer validation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('message', $e->getMessage())->withInput();
        }
    }

    /**
     * Handle cryptocurrency transfer (REFACTORED - stores in session for verification flow)
     */
    public function cryptocurrency_new(CryptocurrencyTransferRequest $request)
    {
        try {
            $user = Auth::user();
            $validated = $request->validated();

            // Generate transaction ID
            $txnId = $this->generateTransactionId('CRYPTO');

            // Store data in session for verification flow
            $transferData = array_merge($validated, [
                'payment_mode' => 'Cryptocurrency',
                'txn_id' => $txnId,
                'date' => Carbon::now(),
                'user' => $user->id
            ]);

            Session::put('data', $transferData);

            return redirect()->route('confirm');

        } catch (\Exception $e) {
            Log::error('Cryptocurrency transfer validation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('message', $e->getMessage())->withInput();
        }
    }



    //payoneer
    public function payoneer(Request $request)
    {


        $this->validate($request, [


            'amount' => 'required|numeric',
            'email' => 'required',
        ]);


        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        if ($request->amount < 1) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can transfer is $settings->currency 1, please Enter correct amount .");
        }

        if (Auth::user()->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }
        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['bankname'] = $request->method;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->email;
        $data['accountnumber'] = $request->email;
        $data['Description'] = "$request->method transaction";
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        // if ($settings->code1status == 1) {
        //     return redirect()->route('code1verification');
        // }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }

    //trandfer wise
    public function transferwise(Request $request)
    {


        $this->validate($request, [


            'amount' => 'required|numeric',
            'email' => 'required',
        ]);


        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        if ($request->amount < 1) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can transfer is $settings->currency 1, please Enter correct amount .");
        }

        if (Auth::user()->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }
        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['bankname'] = $request->method;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->email;
        $data['accountnumber'] = $request->email;
        $data['Description'] = "$request->method transaction";
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        // if ($settings->code1status == 1) {
        //     return redirect()->route('code1verification');
        // }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }
    ///google pay
    public function googlepay(Request $request)
    {


        $this->validate($request, [


            'amount' => 'required|numeric',
            'email' => 'required',
        ]);


        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        if ($request->amount < 1) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can transfer is $settings->currency 1, please Enter correct amount .");
        }

        if (Auth::user()->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }
        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['bankname'] = $request->method;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->email;
        $data['accountnumber'] = $request->email;
        $data['Description'] = "$request->method transaction";
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        // if ($settings->code1status == 1) {
        //     return redirect()->route('code1verification');
        // }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }

    //skrill payment
    public function skrill(Request $request)
    {


        $this->validate($request, [


            'amount' => 'required|numeric',
            'email' => 'required',
        ]);


        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        if ($request->amount < 1) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can transfer is $settings->currency 1, please Enter correct amount .");
        }

        if (Auth::user()->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }
        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['bankname'] = $request->method;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->email;
        $data['accountnumber'] = $request->email;
        $data['Description'] = "$request->method transaction";
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        // if ($settings->code1status == 1) {
        //     return redirect()->route('code1verification');
        // }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }

    public function crypto(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'wallet' => 'required',
        ]);

        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        if ($request->amount <= 0) {
            return redirect()->back()
                ->with("message", "Sorry, please enter a valid amount greater than zero.");
        }

        // Determine which crypto coin is being sent
        $method = $request->method;
        $cryptoCoinMap = [
            'Bitcoin' => 'btc',
            'Ethereum' => 'eth',
            'USDT' => 'usdt',
        ];
        $cryptoCoin = $cryptoCoinMap[$method] ?? null;

        if ($cryptoCoin) {
            // Check crypto balance instead of main account balance
            $cryptoAccount = \App\Models\CryptoAccount::where('user_id', $user->id)->first();
            if (!$cryptoAccount || $cryptoAccount->getCoinBalance($cryptoCoin) < $request->amount) {
                $coinName = \App\Models\CryptoAccount::COIN_NAMES[$cryptoCoin] ?? $method;
                return redirect()->back()
                    ->with('message', "Sorry, your {$coinName} balance is insufficient for this transfer.");
            }
        } else {
            // Fallback to main balance for unknown crypto
            if (Auth::user()->account_bal < $request->amount) {
                return redirect()->back()
                    ->with('message', 'Sorry, your account balance is insufficient for this request.');
            }
        }

        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,
            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['bankname'] = $request->paydetails;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->wallet;
        $data['accountnumber'] = $request->wallet;
        $data['Description'] = 'Cryptocurrency';
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
        $data['crypto_currency'] = $cryptoCoin; // Store the coin column name
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        if ($settings->code1status == 1) {
            return redirect()->route('code1verification');
        }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }
    public function paypal(Request $request)
    {
        $this->validate($request, [

            'email' => 'required|email',
            'amount' => 'required|numeric',
        ]);
        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        if ($request->amount < 1) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can transfer is $settings->currency 1, please Enter correct amount .");
        }

        if (Auth::user()->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }
        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session

        $data['amount'] = $request->amount;
        $data['bankname'] = $request->method;
        $data['accountname'] = $request->email;
        $data['accountnumber'] = $request->email;
        $data['Accounttype'] = $request->method;
        $data['Description'] = $request->Description;
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        // if ($settings->code1status == 1) {
        //     return redirect()->route('code1verification');
        // }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }

    public function localtransfer(Request $request)
    {

        $user = User::where('id', Auth::user()->id)->first();

        $settings = Settings::where('id', '1')->first();
        //check if transction pin is on
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }
        //check if transaction pin match
        if ($user->pin != $request->pin) {
            return redirect()->back()
                ->with("message", "Sorry, incorrect transaction pin");
        }
        if (Auth::user()->account_bal < $request['amount']) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }


        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['bankname'] = $request->bankname;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->accountname;
        $data['bankname'] = $request->bankname;
        $data['accountnumber'] = $request->accountnumber;
        $data['Accounttype'] = $request->Accounttype;
        $data['Description'] = $request->Description;
        $data['user'] = $user->id;
        $data['payment_mode'] = "Domestic Transfer";
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }



        // Session::put('data', $data);
        return redirect()->route('confirm');
        if ($settings->otp == 1) {
            return redirect()->route('getotp', 'data');
        }


        sleep(3);
        return redirect()->route('previewtransfer', 'data');
    }



    //Start Western union transfer

    public function westernunion(Request $request)
    {
        $this->validate($request, [

            'amount' => 'required|numeric',
            'country' => 'required',
            'city' => 'required',
            'zipcode' => 'required',
            'name' => 'required',
            'description' => 'required',

        ]);


        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        //check if transaction pin match
        // if ($user->pin != $request->pin) {
        //     return redirect()->back()
        //         ->with("message", "Sorry, incorrect transaction pin");
        // }

        if ($request->amount < 1) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can transfer is $settings->currency 1, please Enter correct amount .");
        }

        if (Auth::user()->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }
        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['country'] = $request->country;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->name;
        $data['bankname'] = $request->method;
        $data['accountnumber'] = $request->name;
        $data['Accounttype'] = $request->method;
        $data['Description'] = $request->description;
        $data['zipcode'] = $request->zipcode;
        $data['city'] = $request->city;
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        // if ($settings->code1status == 1) {
        //     return redirect()->route('code1verification');
        // }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }

    //end western union transction

    // international transfer
    public function internationaltransfer(Request $request)
    {
        $this->validate($request, [

            'accountname' => 'required',
            'accountnumber' => 'required',
            'amount' => 'required|numeric',
            'bankname' => 'required',
        ]);


        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        //check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        //check if transaction pin match
        if ($user->pin != $request->pin) {
            return redirect()->back()
                ->with("message", "Sorry, incorrect transaction pin");
        }

        if ($request->amount < 1) {
            return redirect()->back()
                ->with("message", "Sorry, The minimum amount you can transfer is $settings->currency 1, please Enter correct amount .");
        }

        if (Auth::user()->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }
        //assignin/turn off transanction number 
        if (Auth::user()->transferaction == '1') {
            User::where('id', $user->id)->update([
                'transferaction' => 0,

            ]);
        }
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        //grabing evereything submitted by user and put it in session
        $data['bankname'] = $request->bankname;
        $data['country'] = $request->country;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->accountname;
        $data['bankname'] = $request->bankname;
        $data['accountnumber'] = $request->accountnumber;
        $data['Accounttype'] = $request->Accounttype;
        $data['Description'] = $request->description;
        $data['iban'] = $request->iban;
        $data['bankaddress'] = $request->address;
        $data['user'] = $user->id;
        $data['payment_mode'] = "Wire Transfer";
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        Session::put('data', $data);

        return redirect()->route('confirm');
        // if ($settings->code1status == 1) {
        //     return redirect()->route('code1verification');
        // }

        // if ($settings->code2status == 1) {
        //     return redirect()->route('verificationcode2');
        // }

        // if ($settings->code1status == 3) {
        //     return redirect()->route('verification3code');
        // }
        // if ($settings->otp == 1) {
        //     return redirect()->route('getotp');
        // }

        // return redirect()->route('previewtransfer');
    }

    //codecomfirm 
    function codecomfirm(Request $request)
    {

        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();
        $data = Session::get('data');

        //code1 request
        if ($request->code1) {
            $this->validate($request, [

                'code1' => 'required',

            ]);
            if (Auth::user()->code1 != $request->code1) {
                return redirect()->back()
                    ->with("message", "Sorry, Invalid $settings->code1 code!!! contact support on $settings->contact_email for the appropriate $settings->code1 for this transaction  ");
            }

            if ($settings->code2status == 1) {
                sleep(3);
                return redirect()->route('verificationcode2');
            }

            if ($settings->code3status == 1) {
                sleep(3);
                return redirect()->route('verification3code');
            }

            if ($settings->otp == 1) {
                sleep(3);
                return redirect()->route('getotp');
            }
        }


        // checkin if code2

        if ($request->code2) {
            $this->validate($request, [

                'code2' => 'required',

            ]);

            if (Auth::user()->code2 != $request->code2) {
                return redirect()->back()
                    ->with("message", "Sorry, Invalid $settings->code2 code!!! contact support on $settings->contact_email for the appropriate $settings->code2 for this transaction  ");
            }



            if ($settings->code3status == 1) {
                sleep(3);
                return redirect()->route('verification3code');
            }

            if ($settings->otp == 1) {
                sleep(3);
                return redirect()->route('getotp');
            }
        }


        if ($request->code3) {

            $this->validate($request, [

                'code3' => 'required',

            ]);

            if (Auth::user()->code3 != $request->code3) {
                return redirect()->back()
                    ->with("message", "Sorry, Invalid $settings->code3 code!!! contact support on $settings->contact_email for the appropriate $settings->code3 for this transaction  ");
            }




            if ($settings->otp == 1) {
                sleep(3);
                return redirect()->route('getotp');
            }
        }


        //otp request

        if ($request->otp) {



            if (Auth::user()->withdrawotp != $request->otp) {


                return redirect()->back()
                    ->with("message", "Sorry, Invalid OTP code!!!  ");
            }
        }

        sleep(3);

        return redirect()->route('previewtransfer');
    }


    //  International preview international transfer

    /**
     * Process the final transfer from session data (REFACTORED)
     */
    public function previewtransfer(Request $request)
    {
        $data = Session::get('data');
        
        if (!$data) {
            return redirect()->route('dashboard')->with('message', 'Session expired. Please try again.');
        }

        $user = Auth::user();
        
        // Check account status
        if ($user->account_status !== 'active') {
            $statusMessages = [
                'dormant' => "Your account is dormant. Please contact support to reactivate your account. Email: {$this->settings->contact_email} or use live chat below for more information.",
                'daily_limit_exceeded' => "You have exceeded your daily transfer limit. Please try again tomorrow or contact admin on {$this->settings->contact_email} or live chat below for more information.",
                'weekly_limit_exceeded' => "You have exceeded your weekly transfer limit. Please try again next week or contact admin on {$this->settings->contact_email} or live chat below for more information.",
                'monthly_limit_exceeded' => "You have exceeded your monthly transfer limit. Please try again next month or contact admin on {$this->settings->contact_email} or live chat below for more information.",
                'transaction_declined' => "Transactions from your account have been declined. Please contact admin on {$this->settings->contact_email} or use live chat below for more information.",
                'blocked' => "Your account has been temporarily blocked. Reason: {$user->block_reason}. Please contact admin on {$this->settings->contact_email} or use live chat below for more information.",
                'permanently_banned' => "Your account has been permanently banned. Reason: {$user->block_reason}. Please contact admin on {$this->settings->contact_email} for assistance.",
            ];
            
            $message = $statusMessages[$user->account_status] ?? "Your account is restricted. Please contact admin on {$this->settings->contact_email} or use live chat below for more information.";
            
            return redirect()->route('dashboard')->with('message', $message);
        }

        // Check transfer limits
        $transferAmount = $data['to_deduct'] ?? $data['amount'];
        $now = now();

        // Check daily limit
        if ($user->daily_limit_enabled && $user->daily_limit > 0) {
            $dailyTotal = Withdrawal::where('user', $user->id)
                ->whereDate('created_at', $now->toDateString())
                ->whereIn('status', ['Pending', 'Processed'])
                ->sum('to_deduct');
            
            if (($dailyTotal + $transferAmount) > $user->daily_limit) {
                $remaining = max(0, $user->daily_limit - $dailyTotal);
                return redirect()->route('dashboard')->with('message', 
                    "Daily limit exceeded. You have {$user->currency}" . number_format($remaining, 2) . " remaining today. Daily limit: {$user->currency}" . number_format($user->daily_limit, 2));
            }
        }

        // Check weekly limit
        if ($user->weekly_limit_enabled && $user->weekly_limit > 0) {
            $weekStart = $now->copy()->startOfWeek();
            $weeklyTotal = Withdrawal::where('user', $user->id)
                ->where('created_at', '>=', $weekStart)
                ->whereIn('status', ['Pending', 'Processed'])
                ->sum('to_deduct');
            
            if (($weeklyTotal + $transferAmount) > $user->weekly_limit) {
                $remaining = max(0, $user->weekly_limit - $weeklyTotal);
                return redirect()->route('dashboard')->with('message', 
                    "Weekly limit exceeded. You have {$user->currency}" . number_format($remaining, 2) . " remaining this week. Weekly limit: {$user->currency}" . number_format($user->weekly_limit, 2));
            }
        }

        // Check monthly limit
        if ($user->monthly_limit_enabled && $user->monthly_limit > 0) {
            $monthStart = $now->copy()->startOfMonth();
            $monthlyTotal = Withdrawal::where('user', $user->id)
                ->where('created_at', '>=', $monthStart)
                ->whereIn('status', ['Pending', 'Processed'])
                ->sum('to_deduct');
            
            if (($monthlyTotal + $transferAmount) > $user->monthly_limit) {
                $remaining = max(0, $user->monthly_limit - $monthlyTotal);
                return redirect()->route('dashboard')->with('message', 
                    "Monthly limit exceeded. You have {$user->currency}" . number_format($remaining, 2) . " remaining this month. Monthly limit: {$user->currency}" . number_format($user->monthly_limit, 2));
            }
        }


        try {
            $user = Auth::user();
            $paymentMode = $data['payment_mode'];

            // Check if already processed
            if ($user->transferaction == '1') {
                return redirect()->route('dashboard')
                    ->with('success', 'Transaction was successful and will be processed soon.');
            }

            // Route to appropriate service method based on payment mode
            $result = match ($paymentMode) {
                'Wire Transfer' => $this->transferService->processInternationalTransfer($data, $user->id),
                'Local transfer', 'Domestic Transfer' => $this->transferService->processLocalTransfer($data, $user->id),
                'Paypal', 'Skrill', 'Payoneer', 'Transfer Wise', 'Google Pay' => $this->transferService->processThirdPartyTransfer($data, $user->id),
                'Cryptocurrency', 'Crytocurrency', 'Bitcoin', 'Ethereum', 'USDT' => $this->transferService->processCryptocurrencyTransfer($data, $user->id),
                'Western Union' => $this->processWesternUnionTransfer($data, $user->id),
                default => throw new \Exception('Invalid payment mode: ' . $paymentMode)
            };

            // Check if transfer was successful
            if (!$result['success']) {
                return redirect()->back()->with('message', $result['message']);
            }

            $withdrawal = $result['withdrawal'];

            // Queue email notifications
            SendTransferEmail::dispatch(
                $withdrawal,
                $user,
                $user->email,
                'Transfer Request Received'
            );

            SendTransferEmail::dispatch(
                $withdrawal,
                $user,
                $this->settings->contact_email,
                'New Transfer Request',
                true
            );

            // Clear session
            Session::forget('data');

            // Return preview page
            return view('user.preview', [
                'settings' => $this->settings,
                'dp' => $withdrawal,
                'code' => $withdrawal->txn_id
            ]);

        } catch (\Exception $e) {
            Log::error('Transfer processing failed', [
                'user_id' => Auth::id(),
                'payment_mode' => $data['payment_mode'] ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('message', 'Transfer failed: ' . $e->getMessage());
        }
    }

    /**
     * Process Western Union transfer (legacy method until refactored)
     */
    private function processWesternUnionTransfer(array $data, int $userId): array
    {
        return DB::transaction(function () use ($data, $userId) {
            try {
                $toDeduct = $data['to_deduct'] ?? $data['amount'];
                
                // Debit user account
                if (!$this->userRepo->debitAccount($userId, $toDeduct)) {
                    return ['success' => false, 'message' => 'Insufficient funds'];
                }
                
                $user = $this->userRepo->find($userId);

                // Create withdrawal record
                $withdrawal = $this->withdrawalRepo->create([
                    'user' => $userId,
                    'amount' => $data['amount'],
                    'to_deduct' => $toDeduct,
                    'payment_mode' => 'Western Union',
                    'type' => 'Debit',
                    'status' => 'Pending',
                    'accountname' => $data['accountname'],
                    'accountnumber' => $data['accountnumber'],
                    'bankname' => $data['bankname'] ?? 'Western Union',
                    'Accounttype' => $data['Accounttype'] ?? null,
                    'Description' => $data['Description'] ?? 'Western Union transfer',
                    'bankaddress' => $data['bankaddress'] ?? null,
                    'country' => $data['country'] ?? null,
                    'zipcode' => $data['zipcode'] ?? null,
                    'city' => $data['city'] ?? null,
                    'date' => Carbon::now(),
                    'txn_id' => $data['txn_id'],
                    'bal' => $user->account_bal,
                ]);

                // Lock account temporarily
                $this->userRepo->updateTransferAction($userId, 0);

                // Create notification
                $this->notifyTransfer(
                    $userId,
                    number_format($data['amount'], 2),
                    $this->settings->currency,
                    $data['accountname'],
                    'pending'
                );
                
                // Send SMS notification for Western Union transfer
                try {
                    $smsService = new SmsService();
                    $message = "Western Union Transfer Initiated: " . $this->settings->currency . number_format($data['amount'], 2) . " to " . $data['accountname'] . ". Transaction ID: " . $data['txn_id'] . ". Status: Pending approval.";
                    $smsService->sendSms($userId, $message, 'transfer');
                } catch (\Exception $e) {
                    \Log::error('Transfer SMS failed: ' . $e->getMessage());
                }

                Log::info("Western Union transfer initiated: {$data['txn_id']}", [
                    'user_id' => $userId,
                    'amount' => $data['amount']
                ]);

                return [
                    'success' => true,
                    'message' => 'Western Union transfer initiated successfully',
                    'withdrawal' => $withdrawal
                ];
            } catch (\Exception $e) {
                Log::error('Western Union transfer failed', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
                return ['success' => false, 'message' => 'Transfer failed. Please try again.'];
            }
        });
    }

    /**
     * OLD previewtransfer - keeping temporarily for reference
     */
    public function previewtransfer_old(Request $request)
    {

        ///catch session
        $data = Session::get('data');
        $settings = Settings::where('id', '1')->first();


        $user = User::where('id', Auth::user()->id)->first();
        $balance = $user->account_bal - $data['amount'];
        $to_withdraw = $data['amount'];

        // check if transferction is on
        if (Auth::user()->transferaction == '1') {
            return redirect()->route('dashboard')
                ->with('success', 'Transaction was Successful and will be processed soon.');
        }


        if ($user->account_bal < $data['amount']) {
            return redirect()->back()
                ->with("message", "Sorry, Your balance is low for this transaction.");
        }

        //storing International wire transfer
        if ($data['payment_mode'] == 'Wire Transfer') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);




            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Wire Transfer';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Accounttype = $data['Accounttype'];
            $dp->Description = $data['Description'];
            $dp->bankaddress = $data['address'];
            $dp->country = $data['country'];
            $dp->swiftcode = $data['swiftcode'];
            $dp->iban = $data['iban'];
            $dp->date = Carbon::now();
            $dp->paydetails = $data['details'];
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data['amount'], 2),
                $settings->currency,
                $data['accountname'],
                'pending'
            );
            
            // Send SMS notification for wire transfer
            try {
                $smsService = new SmsService();
                $message = "Wire Transfer Initiated: " . $settings->currency . number_format($data['amount'], 2) . " to " . $data['accountname'] . ". Transaction ID: " . $code . ". Status: Pending approval.";
                $smsService->sendSms($user->id, $message, 'transfer');
            } catch (\Exception $e) {
                \Log::error('Transfer SMS failed: ' . $e->getMessage());
            }
        } elseif ($data['payment_mode'] == 'Crytocurrency') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);




            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = $data['payment_mode'];
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Description = $data['Description'];
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
        }

        //storing crypto currency
        elseif ($data['payment_mode'] == 'Cryptocurrency') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);
            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Cryptocurrency';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Description = $data['Description'];
            $dp->paydetails = $data['details'];
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data['amount'], 2) . ' ' . ($data['bankname'] ?? 'Crypto'),
                $settings->currency,
                $data['accountnumber'],
                'pending',
                'crypto'
            );
            
            // Send SMS notification for cryptocurrency transfer
            try {
                $smsService = new SmsService();
                $cryptoName = $data['bankname'] ?? 'Cryptocurrency';
                $message = "Cryptocurrency Transfer Initiated: " . number_format($data['amount'], 2) . " {$cryptoName} to wallet " . $data['accountnumber'] . ". Transaction ID: " . $code . ". Status: Pending approval.";
                $smsService->sendSms($user->id, $message, 'transfer');
            } catch (\Exception $e) {
                \Log::error('Transfer SMS failed: ' . $e->getMessage());
            }
        }
        //end storing crypto currency

        //storing transfer wise


        elseif ($data['payment_mode'] == 'Transfer Wise') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);
            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Transfer Wise';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Description = $data['Description'];
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data['amount'], 2),
                $settings->currency,
                $data['accountname'],
                'pending'
            );
        }
        //end storing transfer wise

        //storing payoner
        elseif ($data['payment_mode'] == 'Payoneer') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);




            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Payoneer';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Description = $data['Description'];
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data->amount, 2),
                $settings->currency,
                $data->accountname,
                'pending'
            );
        }
        //end storing payoner

        //storing google pay


        elseif ($data['payment_mode'] == 'Google Pay') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);




            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Google Pay';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Description = $data['Description'];
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data->amount, 2),
                $settings->currency,
                $data->accountnumber,
                'pending'
            );
        }
        //end storing googl pay
        // Western Union strats here

        elseif ($data['payment_mode'] == 'Western Union') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);




            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Western Union';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Accounttype = $data['Accounttype'];
            $dp->Description = $data['Description'];
            $dp->bankaddress = $data['bankaddress'];
            $dp->country = $data['country'];
            $dp->zipcode = $data['zipcode'];
            $dp->city = $data['city'];
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data->amount, 2),
                $settings->currency,
                $data->accountname,
                'pending'
            );
        }
        //Western Union Ends here

        //Storing Skrill transfer

        elseif ($data['payment_mode'] == 'Skrill') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);




            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Skrill';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->Description = $data['Description'];
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data->amount, 2),
                $settings->currency,
                $data->accountnumber,
                'pending'
            );
        } elseif ($data['payment_mode'] == 'Paypal') {
            //debit user
            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);




            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount = $data['amount'];
            $dp->to_deduct = $to_withdraw;
            $dp->payment_mode = 'Paypal';
            $dp->bankname = 'Paypal';
            $dp->status = 'Pending';
            $dp->accountnumber = $data['accountnumber'];
            $dp->accountname = $data['accountname'];
            $dp->Description = 'Paypal';
            $dp->date = Carbon::now();
            $dp->txn_id = $data['txn_id'];
            $dp->user = $user->id;
            $dp->bal =  $balance;
            $dp->save();


            $code = $dp->txn_id;
            $data = $dp;

            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Transfer Request', true));

            // //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($data->amount, 2),
                $settings->currency,
                $data->accountnumber,
                'pending'
            );
        } else {


            User::where('id', $user->id)->update([
                'account_bal' => $user->account_bal - $data['amount'],
                'withdrawotp' => NULL,
            ]);


            $subtxn = substr(strtoupper($settings->site_name), 0, 4);
            $codetxn1 = $this->RandomStringGenerator(8);
            $codetxn2 =  $this->RandomStringGenerator(5);
            //save withdrawal info
            $dp = new Withdrawal();
            $dp->amount =  $data['amount'];
            $dp->to_deduct = $data['amount'];
            $dp->payment_mode = 'Cryptocurrency';
            $dp->status = 'Pending';
            $dp->accountname = $data['accountname'];
            $dp->accountnumber = $data['accountnumber'];
            $dp->bankname = $data['bankname'];
            $dp->type = 'Debit';
            $dp->Accounttype = $data['Accounttype'];
            $dp->Description = $data['Description'];
            $dp->paydetails = $data['details'];
            $dp->date = Carbon::now();
            $dp->user = $user->id;
            $dp->txn_id = "$subtxn/$codetxn1-$codetxn2";
            $dp->bal =  $balance;
            $dp->save();

            $code = $dp->txn_id;


            // send mail to admin
            Mail::to($settings->contact_email)->send(new WithdrawalStatus($dp, $user, 'Withdrawal Request', true));

            //send notification to user
            Mail::to($user->email)->send(new WithdrawalStatus($dp, $user, 'Successful Local Transfer Request'));
            
            // Create transfer notification
            $this->notifyTransfer(
                $user->id,
                number_format($dp->amount, 2),
                $settings->currency,
                $dp->accountname,
                'pending'
            );
        }

        //turn on transfer action
        User::where('id', $user->id)->update([
            'transferaction' => 1,

        ]);

        sleep(2);

        Session::forget('data');
        return view('user.preview', compact('settings', 'dp', 'code'));
    }


    /**
     * Handle cryptocurrency transfers (Bitcoin, Ethereum, USDT)
     */
    public function cryptocurrency(Request $request)
    {
        $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'accountnumber' => 'required|string',
            'method' => 'required|string',
            'pin' => 'required|string',
        ]);

        $user = User::where('id', Auth::user()->id)->first();
        $settings = Settings::where('id', '1')->first();

        // Check if transfer is allowed
        if ($user->allowtransfer == '1') {
            return redirect()->back()
                ->with("message", "Sorry, your account is dormant. Contact support on $settings->contact_email for more details.");
        }

        // Verify transaction PIN
        if ($user->pin != $request->pin) {
            return redirect()->back()
                ->with("message", "Sorry, incorrect transaction PIN");
        }

        // Check balance
        if ($user->account_bal < $request->amount) {
            return redirect()->back()
                ->with('message', 'Sorry, your account balance is insufficient for this request.');
        }

        // Generate transaction codes
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        $subtxn = str_replace(' ', '', $request->method);
        
        $data = [];
        $data['amount'] = $request->amount;
        $data['payment_method'] = $request->method;
        $data['walletaddress'] = $request->walletaddress;
        $data['description'] = $request->description ?? "Cryptocurrency transfer via {$request->method}";
        $data['date'] = Carbon::now();
        $data['txn_id'] = "$subtxn$codetxn1$codetxn2";
        
        Session::put('data', $data);

        return redirect()->route('confirm');
    }

    // for front end content management
    function RandomStringGenerator($n)
    {
        $generated_string = "";
        $domain = "ABCDEFGHIJKLMNOPQRSTUVWXYZABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        // Return the random generated string 
        return $generated_string;
    }

    /**
     * Generate unique transaction ID
     *
     * @param string $prefix
     * @return string
     */
    private function generateTransactionId(string $prefix): string
    {
        $siteName = substr(strtoupper($this->settings->site_name), 0, 2);
        $random1 = $this->RandomStringGenerator(8);
        $random2 = $this->RandomStringGenerator(5);
        
        return "{$siteName}{$prefix}{$random1}{$random2}";
    }
}
