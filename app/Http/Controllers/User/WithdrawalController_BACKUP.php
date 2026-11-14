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
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;


class WithdrawalController extends Controller
{
    use Coinpayment;



    public function confirm()
    {
        $condata = Session::get('data');
        $title = 'Confirm Transfer Transaction';
        return view('user.confirm', ['title' => $title, 'confirmdata' => $condata]);
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
        if ($settings->code1status == 1) {
            return redirect()->route('code1verification');
        }

        if ($settings->code2status == 1) {
            return redirect()->route('verificationcode2');
        }

        if ($settings->code1status == 3) {
            return redirect()->route('verification3code');
        }
        if ($settings->otp == 1) {
            return redirect()->route('getotp');
        }

        return redirect()->route('previewtransfer');
    }


    public function loan(Request $request)
    {

        return redirect()->route('dashboard')
            ->with('success', 'Action Sucessful! Please wait while we process your loan request.');
    }




    public function getotp()
    {

        if (auth::user()->transferaction == 1) {
            return back();
        }
        $code = $this->RandomStringGenerator(6);

        $user = Auth::user();
        User::where('id', $user->id)->update([
            'withdrawotp' => $code,
        ]);
        $settings = Settings::where('id', '1')->first();

        $message = "You recently initiated  transfer from your $settings->site_name  $user->accounttype via our online banking channel.\n
        If this was legitimate activity from you and were expecting this email, consider using the code below to complete your transaction:\n
                                                 $code
           
        \nIf you do not use $settings->site_name or did not attempted to carry out a transaction via your $settings->site_name internet banking channel, please ignore this email or contact support if you have questions.";
        $subject = "Confirm transction";
        Mail::bcc($user->email)->send(new NewNotification($message, $subject, $user->name));

        return redirect()->route('otpview');
    }


    function otpview()
    {
        return view('user.otp')->with('success', 'Action Sucessful! OTP have been sent to your email');
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
        $data['bankname'] = $request->paydetails;
        $data['amount'] = $request->amount;
        $data['accountname'] = $request->wallet;
        $data['accountnumber'] = $request->wallet;
        $data['Description'] = 'Cryptocurrency';
        $data['user'] = $user->id;
        $data['payment_mode'] = $request->method;
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

    public function previewtransfer(Request $request)
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
            $dp->payment_mode = 'Domestic Transfer';
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
        }

        //turn on transfer action
        User::where('id', $user->id)->update([
            'transferaction' => 1,

        ]);

        sleep(2);

        Session::forget('data');
        return view('user.preview', compact('settings', 'dp', 'code'));
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
}
