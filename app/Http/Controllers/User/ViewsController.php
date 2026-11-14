<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\CryptoAccount;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Plans;
use App\Models\Card;
use App\Models\Card_record;
use App\Models\User_plans;
use App\Models\Mt4Details;
use App\Models\Deposit;
use App\Models\SettingsCont;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use App\Models\Tp_Transaction;
use App\Traits\PingServer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ViewsController extends Controller
{
    use PingServer;

    public function dashboard(Request $request)
    {

        $settings = Settings::where('id', '1')->first();
        $user = User::find(auth()->user()->id);

        //check if user does not have ref link then update his link
        if ($user->ref_link == '') {
            User::where('id', $user->id)
                ->update([
                    'ref_link' => $settings->site_address . '/ref/' . $user->username,
                ]);
        }

        //give reg bonus if new
        if ($user->signup_bonus != "received" && ($settings->signup_bonus != NULL && $settings->signup_bonus > 0)) {
            User::where('id', $user->id)
                ->update([
                    'bonus' => $user->bonus + $settings->signup_bonus,
                    'account_bal' => $user->account_bal + $settings->signup_bonus,
                    'signup_bonus' => "received",
                ]);
            //create history
            Tp_Transaction::create([
                'user' => Auth::user()->id,
                'plan' => "SignUp Bonus",
                'amount' => $settings->signup_bonus,
                'type' => "Bonus",
            ]);
        }

        if (DB::table('crypto_accounts')->where('user_id', Auth::user()->id)->doesntExist()) {
            $cryptoaccnt = new CryptoAccount();
            $cryptoaccnt->user_id = Auth::user()->id;
            $cryptoaccnt->save();
        }

        //sum total deposited
        $total_deposited = DB::table('deposits')->where('user', $user->id)->where('status', 'Processed')->sum('amount');
        $total_deposited_pending = DB::table('deposits')->where('user', $user->id)->where('status', 'Pending')->sum('amount');
        $total_withdrawal = DB::table('withdrawals')->where('user', $user->id)->where('status', 'Processed')->sum('amount');
        $total_withdrawal_pending = DB::table('withdrawals')->where('user', $user->id)->where('status', 'Pending')->sum('amount');
        //log user out if not blocked by admin

        $paymethod = Wdmethod::where(function ($query) {
            $query->where('type', '=', 'deposit')
                ->orWhere('type', '=', 'both');
        })->where('status', 'enabled')->orderByDesc('id')->get();
        if ($user->status != "active") {
            $request->session()->flush();
            return redirect()->route('dashboard');
        }
        // Fetch crypto account & live prices for dashboard
        $cryptoAccount = CryptoAccount::where('user_id', Auth::user()->id)->first();
        $cryptoPrices = [];
        try {
            $cryptoPrices = \Illuminate\Support\Facades\Cache::remember('coingecko_prices', 300, function () {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(10)
                        ->withHeaders(['Accept' => 'application/json'])
                        ->get('https://api.coingecko.com/api/v3/simple/price', [
                            'ids' => 'bitcoin,ethereum,tether',
                            'vs_currencies' => 'usd',
                        ]);

                    if ($response->successful()) {
                        $data = $response->json();
                        return [
                            'btc' => $data['bitcoin']['usd'] ?? 0,
                            'eth' => $data['ethereum']['usd'] ?? 0,
                            'usdt' => $data['tether']['usd'] ?? 1,
                        ];
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning('CoinGecko API failed: ' . $e->getMessage());
                }

                // Fallback prices if API fails
                return [
                    'btc' => 50000,
                    'eth' => 3000,
                    'usdt' => 1,
                ];
            });
        } catch (\Exception $e) {
            $cryptoPrices = ['btc' => 50000, 'eth' => 3000, 'usdt' => 1];
        }

        return view('user.dashboard', [
            'title' => 'Customer Dashboard',
            'total_deposited' => $total_deposited,
            'total_withdrawal' => $total_withdrawal,
            'total_withdrawal_pending' => $total_withdrawal_pending,
            'total_deposited_pending' => $total_deposited_pending,
            'withdrawals' => Withdrawal::where('user', Auth::user()->id)->orderBy('id', 'desc')->take(6)
                ->get(),
            'deposits' => Deposit::where('user', Auth::user()->id)->orderBy('id', 'desc')->take(4)
                ->get(),
            'dmethods' =>  $paymethod,
            'trading_accounts' => Mt4Details::where('client_id', Auth::user()->id)->count(),
            'plans' => User_plans::where('user', Auth::user()->id)->where('active', 'yes')->orderByDesc('id')->skip(0)->take(2)->get(),
            't_history' => Tp_Transaction::where('user', Auth::user()->id)
                ->where('type', '<>', 'ROI')
                ->orderByDesc('id')->skip(0)->take(10)
                ->get(),
            'cards' => Card::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->take(2)->get(),
            'cryptoAccount' => $cryptoAccount,
            'cryptoPrices' => $cryptoPrices,
        ]);
    }

    //Profile route
    public function profile()
    {
        $userinfo = User::where('id', Auth::user()->id)->first();
        return view('user.profile')->with(array(
            'userinfo' => $userinfo,
            'title' => 'Profile',
        ));
    }

    // editpass

    public function editpass()
    {
        $userinfo = User::where('id', Auth::user()->id)->first();
        return view('user.editpass')->with(array(
            'userinfo' => $userinfo,
            'title' => 'Reset Password',
        ));
    }

    //return add withdrawal account form view
    public function accountdetails()
    {
        return view('user.updateacct')->with(array(
            'title' => 'Update account details',
        ));
    }

    //return localtransfer account form view
    public function localtransfer()
    {
        return view('user.localtransfer')->with(array(
            'title' => 'Local Transfer',
        ));
    }
    //return code1 account form view
    public function code1()

    {
        if (auth::user()->transferaction == 1) {
            return back();
        }
        return view('user.code1')->with(array(
            'title' => 'Transfer Code',
        ));
    }

    //return code2 account form view
    public function code2()
    {
        if (auth::user()->transferaction == 1) {
            return back();
        }
        return view('user.code2')->with(array(
            'title' => 'Transfer Code',
        ));
    }


    //return code2 account form view
    public function code3()
    {
        if (auth::user()->transferaction == 1) {
            return back();
        }
        return view('user.code3')->with(array(
            'title' => 'Transfer Code',
        ));
    }


    //return add international Transfer account form view
    public function internationaltransfer()
    {
        return view('user.international')->with(array(
            'title' => 'International Transfer',
        ));
    }

    //return internal transfer form view
    public function internaltransfer()
    {
        return view('user.internaltransfer')->with(array(
            'title' => 'Internal Transfer',
        ));
    }

    //support route
    public function support()
    {
        return view('user.support')
            ->with(array(
                'title' => 'Support',
            ));
    }


    //pin route
    public function pin()
    {
        return view('user.pin')
            ->with(array(
                'title' => 'Verify Pin',
            ));
    }

    //Trading history route
    public function tradinghistory()
    {
        return view('user.thistory')
            ->with(array(
                't_history' => Tp_Transaction::where('user', Auth::user()->id)
                    ->where('type', 'ROI')
                    ->orderByDesc('id')
                    ->paginate(15),
                'title' => 'Trading History',
            ));
    }

    //Account transactions history route
    public function accounthistory()
    {
        return view('user.transactions')
            ->with(array(
                't_history' => Tp_Transaction::where('user', Auth::user()->id)
                    ->where('type', '<>', 'ROI')
                    ->orderByDesc('id')
                    ->get(),

                'withdrawals' => Withdrawal::where('user', Auth::user()->id)->orderBy('id', 'desc')
                    ->get(),
                'deposits' => Deposit::where('user', Auth::user()->id)->orderBy('id', 'desc')
                    ->get(),
                'title' => 'Account Transactions History',

            ));
    }

    //Return deposit route
    public function deposits()
    {
        $paymethod = Wdmethod::where(function ($query) {
            $query->where('type', '=', 'deposit')
                ->orWhere('type', '=', 'both');
        })->where('status', 'enabled')->orderByDesc('id')->get();

        //sum total deposited
        $total_deposited = DB::table('deposits')->where('user', auth()->user()->id)->where('status', 'Processed')->sum('amount');

        return view('user.deposits')
            ->with(array(
                'title' => 'Fund your account',
                'dmethods' => $paymethod,
                'deposits' => Deposit::where(['user' => Auth::user()->id])
                    ->orderBy('id', 'desc')
                    ->get(),
                'deposited' => $total_deposited,
            ));
    }

    //Return withdrawals route
    public function withdrawals()
    {
        $withdrawals =  Wdmethod::where(function ($query) {
            $query->where('type', '=', 'withdrawal')
                ->orWhere('type', '=', 'both');
        })->where('status', 'enabled')->orderByDesc('id')->get();

        return view('user.withdrawals')
            ->with(array(
                'title' => 'Withdraw Your funds',
                'wmethods' => $withdrawals,
            ));
    }

    public function transferview()
    {
        $settings = SettingsCont::find(1);
        if (!$settings->use_transfer) {
            abort(404);
        }
        return view('user.transfer', [
            'title' => 'Send funds to a friend',
        ]);
    }

    //Subscription Trading 
    public function subtrade()
    {
        $settings = Settings::where('id', 1)->first();
        $mod = $settings->modules;
        if (!$mod['subscription']) {
            abort(404);
        }
        return view('user.subtrade')
            ->with(array(
                'title' => 'Subscription Trade',
                'subscriptions' => Mt4Details::where('client_id', auth::user()->id)->orderBy('id', 'desc')->get(),
            ));
    }


    //Main Plans route
    public function mplans()
    {
        return view('user.mplans')
            ->with(array(
                'title' => 'Main Plans',
                'plans' => Plans::where('type', 'investment')->get(),
                'settings' => Settings::where('id', '1')->first(),
            ));
    }

    //My Plans route
    public function myplans($sort)
    {
        if ($sort == 'All') {
            return view('user.myplans')
                ->with(array(
                    'numOfPlan' => User_plans::where('user', Auth::user()->id)->count(),
                    'title' => 'Your packages',
                    'plans' => User_plans::where('user', Auth::user()->id)->orderByDesc('id')->paginate(10),
                    'settings' => Settings::where('id', '1')->first(),
                ));
        } else {
            return view('user.myplans')
                ->with(array(
                    'numOfPlan' => User_plans::where('user', Auth::user()->id)->count(),
                    'title' => 'Your packages',
                    'plans' => User_plans::where('user', Auth::user()->id)->where('active', $sort)->orderByDesc('id')->paginate(10),
                    'settings' => Settings::where('id', '1')->first(),
                ));
        }
    }


    public function sortPlans($sort)
    {
        return redirect()->route('myplans', ['sort' => $sort]);
    }

    public function planDetails($id)
    {
        $plan = User_plans::find($id);
        return view('user.plandetails', [
            'title' => $plan->dplan->name,
            'plan' => $plan,
            'transactions' => Tp_Transaction::where('type', 'ROI')->where('user_plan_id', $plan->id)->orderByDesc('id')->paginate(10),
        ]);
    }


    public function transactiondetails($id)
    {
        $transaction = Withdrawal::find($id);
        return view('user.transactiondetails', [
            'title' => "Transfer Transaction detail",
            'transaction' =>  $transaction,

        ]);
    }


    public function depositdetails($id)
    {
        $transaction = Deposit::find($id);
        return view('user.depositdetails', [
            'title' => "Deposit Transaction detail",
            'transaction' =>  $transaction,

        ]);
    }


    function twofa()
    {
        return view('profile.show', [
            'title' => 'Advance Security Settings',
        ]);
    }

    // Referral Page
    public function referuser()
    {
        return view('user.referuser', [
            'title' => 'Refer user',
        ]);
    }

    public function verifyaccount()
    {
        if (Auth::user()->account_verify == 'Verified') {
            return redirect()->route('dashboard')->with('success', 'Congratulations!! Your account has been verified.');
        }
        return view('user.verify', [
            'title' => 'Verify your Account',
        ]);
    }

    public function verificationForm()
    {
        if (Auth::user()->account_verify == 'Verified') {
            return redirect()->route('dashboard')->with('success', 'Congratulations!! Your account has been verified.');
        }
        return view('user.verification', [
            'title' => 'KYC Application'
        ]);
    }



    public function tradeSignals()
    {
        $settings = Settings::where('id', 1)->first();
        $mod = $settings->modules;
        if (!$mod['signal']) {
            abort(404);
        }

        $response = $this->fetctApi('/subscription', [
            'id' => auth()->user()->id
        ]);
        $res = json_decode($response);

        $responseSt = $this->fetctApi('/signal-settings');
        $info = json_decode($responseSt);

        return view('user.signals.subscribe', [
            'title' => 'Trade signals',
            'subscription' => $res->data,
            'set' => $info->data->settings,
        ]);
    }

    //return code1 account form view
    public function loan()
    {
        $user_plans = User_plans::where('user', Auth::user()->id)->get();
        
        return view('user.loan')->with(array(
            'title' => 'Loan Application',
            'user_plans' => $user_plans,
        ));
    }



    //return code1 account form view
    public function card()
    {
        $settings = Settings::where('id', '1')->first();
        $card = Card::where('user', Auth::user()->id)->first();
        return view('user.card')->with(array(
            'title' => 'Credit/Debit Card',
            'card' => $card,
            'setting'=>$settings,
            'card_records' => Card_record::where('user', Auth::user()->id)
                ->orderByDesc('id')
                ->paginate(15),
        ));
    }
    public function binanceSuccess()
    {
        return redirect()->route('deposits')->with('success', 'Your Deposit was successful, please wait while it is confirmed. You will receive a notification regarding the status of your deposit.');
    }

    public function binanceError()
    {
        return redirect()->route('deposits')->with('message', 'Something went wrong please try again. Contact our support center if problem persist');
    }


    public function pinstatus(Request $request)
    {
        $user = Auth::user();
        
        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            if ($request->pin != $user->pin) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid PIN. Please try again.'
                ], 200);
            }
            
            User::where('id', Auth::user()->id)
                ->update([
                    'pinstatus' => '0',
                ]);
            
            // Check if there's a return URL (from inactivity timeout)
            $returnUrl = $request->input('return_url');
            $redirectUrl = $returnUrl ?: route('dashboard');
            
            return response()->json([
                'success' => true,
                'message' => 'PIN verified successfully!',
                'redirect' => $redirectUrl
            ], 200);
        }
        
        // Handle non-AJAX requests (fallback) - redirect without flash messages
        if ($request->pin != $user->pin) {
            return redirect()->back();
        }
        
        User::where('id', Auth::user()->id)
            ->update([
                'pinstatus' => '0',
            ]);
            
        return redirect('dashboard');
    }

    public function createcard(Request $request)
    {
        $user = Auth::user();

        if ($request->amount > $user->account_bal) {
            return redirect()->route('card')->with('message', 'Insufficent Account balance');
        }
        
        
                if ($request->pin > $user->pin) {
            return redirect()->route('card')->with('message', 'Transaction pin is incorrect!!');
        }
        $card = Card::where('user', $user->id)->first();

        $card_number = $this->RandomStringGenerator(14);
        $expmonth = $this->RandomStringGeneratord(1);
        $expyear = $this->RandomStringGeneratord(1);
        $cvv =  $this->RandomStringGenerator(3);

        Card::where('user', $card->user)
            ->update([
                'amount' => $card->amount + $request->amount,
                'card_number' => $card_number,
                'name' => $user->name,
                'cvv' => $cvv,
                'type' => 'Virtual Card',
                'card_status' => '1',
                'status' => 'Active',
                'expiry' => "0$expmonth/2$expyear"


            ]);


        User::where('id', Auth::user()->id)
            ->update([
                'account_bal' => $user->account_bal - $request->amount,

            ]);
        $card_ref = $this->RandomStringGenerator(4);
        Card_record::create([
            'user' => Auth::user()->id,
            'amount' => $request->amount,
            'reference' => "RUX-$card_ref",
            'type' => "Credit",
        ]);


        return redirect()->route('card')->with('success', 'Virtual Card Created Successfully');
    }



    public function fundcard(Request $request)
    {
        
          $user = Auth::user();
           $card = Card::where('user', $user->id)->first();
       
        
                if (   $card->status != 'Active') {
            return redirect()->route('card')->with('message', 'Card is not active, Please contact support.');
        }
      
        
        
        if ($request->amount > $user->account_bal) {
            return redirect()->route('card')->with('message', 'Insufficent Account balance');
        }
       



        Card::where('user', $card->user)
            ->update([
                'amount' => $card->amount + $request->amount,


            ]);

        User::where('id', Auth::user()->id)
            ->update([
                'account_bal' => $user->account_bal - $request->amount,

            ]);


        $card_ref = $this->RandomStringGenerator(4);
        Card_record::create([
            'user' => Auth::user()->id,
            'amount' => $request->amount,
            'reference' => "RUX-$card_ref",
            'type' => "Credit",
        ]);



        return redirect()->route('card')->with('success', 'Virtual Card Funded Successfully');
    }


    public function withdrawcard(Request $request)
    {
        $user = Auth::user();
           $card = Card::where('user', $user->id)->first();
         if (   $card->status != 'Active') {
            return redirect()->route('card')->with('message', 'Card is not active, Please contact support.');
        }
       
        

        if ($request->amount > $card->amount) {
            return redirect()->route('card')->with('message', 'Insufficent Card balance');
        }




        Card::where('user', $card->user)
            ->update([
                'amount' => $card->amount - $request->amount,


            ]);

        User::where('id', Auth::user()->id)
            ->update([
                'account_bal' => $user->account_bal + $request->amount,

            ]);


        $card_ref = $this->RandomStringGenerator(4);
        Card_record::create([
            'user' => Auth::user()->id,
            'amount' => $request->amount,
            'reference' => "RUX-$card_ref",
            'type' => "Debit",
        ]);


        return redirect()->route('card')->with('success', 'Card Withdrawal is Successful');
    }



    public function freeze()
    {
        
           

        Card::where('user', Auth::user()->id)->update([
            'status' => 'Blocked',
        ]);
        return redirect()->back()->with('success', 'Card Blocked Sucessfully!');
    }



    public function sucesstransfer()
    {


        return redirect()->route('dashboard')->with('success', 'Transaction was Successful and will be processed soon.');
    }


    public function unfreeze()
    {
       
        Card::where('user', Auth::user()->id)->update([
            'status' => 'Active',
        ]);
        return redirect()->back()->with('success', 'Card Unblocked Sucessfully!');
    }


    public function RandomStringGeneratord($n)
    {
        $generated_string = "";
        $domain = "789789789789";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        // Return the random generated string 
        return $generated_string;
    }

    public function RandomStringGenerator($n)
    {
        $generated_string = "";
        $domain = "1234567890012345678921345678903214567890456789012544343577666568799897867566555";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        // Return the random generated string 
        return $generated_string;
    }
}
