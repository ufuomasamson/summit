<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\CPTrait;
use App\Mail\NewNotification;
use App\Models\Activity;
use App\Models\Admin;
use App\Models\Agent;
use App\Models\Asset;
use App\Models\Card;
use App\Models\Content;
use App\Models\Cp_transaction;
use App\Models\Deposit;
use App\Models\Faq;
use App\Models\Images;
use App\Models\Kyc;
use App\Models\Mt4Details;
use App\Models\Notification;
use App\Models\Plans;
use App\Models\Settings;
use App\Models\Testimony;
use App\Models\Tp_Transaction;
use App\Models\User;
use App\Models\User_plans;
use App\Models\Wdmethod;
use App\Models\Withdrawal;
use App\Models\AccountBlock;
use App\Models\CryptoAccount;
use App\Traits\PingServer;
use App\Traits\CreatesNotifications;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ManageUsersController extends Controller
{
    use PingServer, CreatesNotifications;

    // See user wallet balances
    public function loginactivity($id)
    {

        $user = User::where('id', $id)->first();

        return view('admin.Users.loginactivity', [
            'activities' => Activity::where('user', $id)->orderByDesc('id')->get(),
            'title' => "$user->name login activities",
            'user' => $user,
        ]);
    }

    public function showUsers($id)
    {
        $user = User::where('id', $id)->first();
        $ref = User::whereNull('ref_by')->where('id', '!=', $id)->get();

        return view('admin.Users.referral', [
            'title' => "Add users to $user->name referral list",
            'user' => $user,
            'ref' => $ref,
        ]);
    }

    public function fetchUsers()
    {
        $users = User::orderByDesc('id')->get();
        return response()->json([
            'message' => 'Success',
            'data' => $users,
            'code' => 200
        ]);
    }


    public function addReferral(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $ref = User::where('id', $request->ref_id)->first();

        $ref->ref_by = $user->id;
        $ref->save();
        return redirect()->back()
            ->with('success', "$ref->name is now referred by $user->name successfully");
    }

    public function clearactivity($id)
    {
        $activities = Activity::where('user', $id)->get();

        if (count($activities) > 0) {
            foreach ($activities as $act) {
                Activity::where('id', $act->id)->delete();
            }
            return redirect()->back()
                ->with('success', 'Activity Cleared Successfully!');
        }
    }

    /**
     * Update user account limits
     */
    public function updateAccountLimits(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'daily_limit' => 'nullable|numeric|min:0',
            'weekly_limit' => 'nullable|numeric|min:0',
            'monthly_limit' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($request->user_id);
        
        $user->daily_limit = $request->daily_limit;
        $user->weekly_limit = $request->weekly_limit;
        $user->monthly_limit = $request->monthly_limit;
        $user->daily_limit_enabled = $request->has('daily_limit_enabled');
        $user->weekly_limit_enabled = $request->has('weekly_limit_enabled');
        $user->monthly_limit_enabled = $request->has('monthly_limit_enabled');
        
        $user->save();

        return redirect()->back()
            ->with('success', 'Account limits updated successfully!');
    }

    /**
     * Update user account status
     */
    public function updateAccountStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'account_status' => 'required|in:active,dormant,daily_limit_exceeded,weekly_limit_exceeded,monthly_limit_exceeded,transaction_declined',
        ]);

        $user = User::findOrFail($request->user_id);
        $oldStatus = $user->account_status;
        $user->account_status = $request->account_status;
        $user->save();

        // Send notification email if status changed
        if ($request->notifyuser == '1') {
            $statusMessages = [
                'active' => 'Your account has been activated and all transactions are now enabled.',
                'dormant' => 'Your account has been marked as dormant. Please contact support to reactivate.',
                'daily_limit_exceeded' => 'Your daily transaction limit has been reached. Limit will reset tomorrow.',
                'weekly_limit_exceeded' => 'Your weekly transaction limit has been reached. Limit will reset next week.',
                'monthly_limit_exceeded' => 'Your monthly transaction limit has been reached. Limit will reset next month.',
                'transaction_declined' => 'Transactions from your account have been temporarily restricted. Please contact support.',
            ];

            try {
                $settings = Settings::first();
                Mail::to($user->email)->send(new NewNotification(
                    'Account Status Update',
                    $statusMessages[$request->account_status],
                    $user->name
                ));
            } catch (\Exception $e) {
                // Log error but don't fail the status update
            }
        }

        return redirect()->back()
            ->with('success', "Account status changed from {$oldStatus} to {$request->account_status} successfully!");

        return redirect()->back()
            ->with('message', 'No Activity to clear!');
    }

    /**
     * Update user-specific transfer code settings
     */
    public function updateUserTransferCodes(Request $request, $userId)
    {
        $request->validate([
            'code1_enabled' => 'nullable|in:0,1',
            'code2_enabled' => 'nullable|in:0,1',
            'code3_enabled' => 'nullable|in:0,1',
            'otp_enabled' => 'nullable|in:0,1',
            'code1_value' => 'nullable|string|max:255',
            'code2_value' => 'nullable|string|max:255',
            'code3_value' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($userId);

        // Update code enabled/disabled status
        // NULL means use global settings, 0 = disabled, 1 = enabled
        $user->code1_enabled = $request->input('code1_enabled') === '' ? null : $request->input('code1_enabled');
        $user->code2_enabled = $request->input('code2_enabled') === '' ? null : $request->input('code2_enabled');
        $user->code3_enabled = $request->input('code3_enabled') === '' ? null : $request->input('code3_enabled');
        $user->otp_enabled = $request->input('otp_enabled') === '' ? null : $request->input('otp_enabled');

        // Update code values
        $user->code1 = $request->input('code1_value');
        $user->code2 = $request->input('code2_value');
        $user->code3 = $request->input('code3_value');

        $user->save();

        return redirect()->back()
            ->with('success', 'Transfer code settings updated successfully for ' . $user->name . '!');
    }

    public function markplanas($status, $id)
    {
        User_plans::where('id', $id)->update([
            'active' => $status,
        ]);
        return redirect()->back()
            ->with('success', "Loan state changed to $status");
    }

    public function viewuser($id)
    {
        $user = User::where('id', $id)->first();
        $card = Card::where('user_id', $user->id)->first();
        $withdrawals = \App\Models\Withdrawal::where('user', $id)->orderByDesc('id')->get();
        $cryptoAccount = \App\Models\CryptoAccount::where('user_id', $user->id)->first();

    
        return view('admin.Users.userdetails', [
            'user' => $user,
            'pl' => Plans::orderByDesc('id')->get(),
            'card'=>$card,
            'withdrawals' => $withdrawals,
            'cryptoAccount' => $cryptoAccount,
            'title' => "Manage $user->name",
        ]);
    }
    //block user
    public function ublock($id)
    {
        User::where('id', $id)->update([
            'status' => 'blocked',
        ]);
        return redirect()->back()->with('success', 'Action Sucessful!');
    }

//update card details

public function adminCardupdate(Request $request)
{


    Card::where('user_id', $request['user_id'])

        ->update([
            'name' => $request['name'],
            'card_number' => $request['card_number'],
            'cvv' => $request['cvv'],
            'expiry' => $request['expiry'],
            'status' => $request['status'],
            'amount' => $request['amount'],

        ]);


    return redirect()->back()->with('success', 'Card details updated Successfully!');
}
//end
//card
public function card_activate($id)
    {

        $card = Card::where('user_id', $id)->first();
       $user = User::where('id', $id)->first();
        $card_number = $this->RandomStringGenerator(14);
        $expmonth = $this->RandomStringGeneratord(1);
        $expyear = $this->RandomStringGeneratord(1);
        $cvv =  $this->RandomStringGenerator(3);

        Card::where('user_id', $card->user_id)
            ->update([
                'amount' => $card->amount + 0,
                'card_number' => $card_number,
                'name' => "$user->name $user->lastname $user->middlename",
                'cvv' => $cvv,
                'type' => 'Virtual Card',
                'card_status' => '1',
                'status' => 'Active',
                'expiry' => "0$expmonth/2$expyear"


            ]);



        
        return redirect()->back()->with('success', 'Card Activated Sucessfully');
    }


    //deactivate card

    public function card_deactivate($id)
    {


        Card::where('user_id', $id)
        ->update([
            'amount' =>  0,
            'card_number' => "***********",
            'name' => "*************",
            'cvv' => "**********",
            'type' => '***********',
            'status' => 'Not active',
            'expiry' => "***********",
            'card_status' => '0',

        ]);
       
        return redirect()->back()->with('success', 'Card Deactivated Sucessfully');
    }

    /**
     * Block user account with reason (replaces ublock)
     */
    public function blockUser(Request $request, $userId)
    {
        $request->validate([
            'block_type' => 'required|in:blocked,permanently_banned',
            'block_reason' => 'required|string|min:10|max:1000',
            'unblock_days' => 'nullable|integer|min:1|max:365',
            'notify_user' => 'nullable|boolean',
            'notify_admins' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($userId);
        $oldStatus = $user->account_status;
        
        $unblockAt = null;
        if ($request->block_type === 'blocked' && $request->unblock_days) {
            $unblockAt = Carbon::now()->addDays($request->unblock_days);
        }
        
        $user->account_status = $request->block_type;
        $user->block_reason = $request->block_reason;
        $user->blocked_at = now();
        $user->blocked_by_admin_id = auth('admin')->id();
        $user->unblock_at = $unblockAt;
        $user->save();
        
        AccountBlock::create([
            'user_id' => $userId,
            'block_type' => $request->block_type,
            'reason' => $request->block_reason,
            'blocked_by_admin_id' => auth('admin')->id(),
            'blocked_at' => now(),
            'admin_notified' => $request->notify_admins ?? false,
        ]);
        
        if ($request->notify_user) {
            $this->notifyBlock(
                $userId, 
                $request->block_reason, 
                $request->block_type === 'permanently_banned',
                $unblockAt
            );
        }
        
        if ($request->notify_admins) {
            $this->notifyAdminsOfBlock($user, $request->block_type, $request->block_reason);
        }
        
        $blockTypeLabel = $request->block_type === 'permanently_banned' ? 'Permanently Banned' : 'Blocked';
        
        return redirect()->back()
            ->with('success', "{$user->name}'s account has been {$blockTypeLabel} successfully!");
    }

    /**
     * Unblock user account (replaces unblock)
     */
    public function unblockUser(Request $request, $userId)
    {
        $request->validate([
            'unblock_note' => 'nullable|string|max:500',
            'notify_user' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($userId);
        
        if (!in_array($user->account_status, ['blocked', 'permanently_banned'])) {
            return redirect()->back()
                ->with('message', 'This user account is not currently blocked.');
        }
        
        AccountBlock::where('user_id', $userId)
            ->whereNull('unblocked_at')
            ->update([
                'unblocked_at' => now(),
                'unblocked_by_admin_id' => auth('admin')->id(),
                'unblock_note' => $request->unblock_note,
            ]);
        
        $user->account_status = 'active';
        $user->block_reason = null;
        $user->blocked_at = null;
        $user->blocked_by_admin_id = null;
        $user->unblock_at = null;
        $user->save();
        
        if ($request->notify_user) {
            $this->notifyUnblock($userId, $request->unblock_note);
        }
        
        return redirect()->back()
            ->with('success', "{$user->name}'s account has been unblocked successfully!");
    }

    /**
     * Notify other admins about account blocks
     */
    private function notifyAdminsOfBlock($user, $blockType, $reason)
    {
        try {
            $settings = Settings::first();
            $admins = Admin::where('type', 'Super Admin')->get();
            
            foreach ($admins as $admin) {
                if ($admin->id != Auth('admin')->id()) {
                    $message = "Admin " . Auth('admin')->user()->firstName . " has " . 
                              ($blockType === 'permanently_banned' ? 'permanently banned' : 'blocked') . 
                              " user account: {$user->name} ({$user->email}). Reason: {$reason}";
                    
                    Mail::to($admin->email)->send(new NewNotification(
                        $message,
                        "Account Block Alert - {$settings->site_name}",
                        $admin->firstName
                    ));
                }
            }
        } catch (\Exception $e) {
            Log::error('Admin block notification failed: ' . $e->getMessage());
        }
    }

    public function dormant($id)
    {

        User::where('id', $id)->update([
            'allowtransfer' => '1',
        ]);
        return redirect()->back()->with('success', 'Action Sucessful! Account turned to a dormant account');
    }

    public function undormant($id)
    {
        User::where('id', $id)->update([
            'allowtransfer' => '0',
        ]);
        return redirect()->back()->with('success', 'Action Sucessful! Dormant account turned off');
    }
    //Return create users route

    //unblock user
    public function unblock($id)
    {
        User::where('id', $id)->update([
            'status' => 'active',
        ]);
        return redirect()->back()->with('success', 'Action Sucessful!');
    }

    //Turn on/off user trade
    public function usertrademode($id, $action)
    {
        if ($action == "on") {
            $action = "on";
        } elseif ($action == "off") {
            $action = "off";
        } else {
            return redirect() - back()->with('message', "Unknown action!");
        }

        User::where('id', $id)->update([
            'trade_mode' => $action,
        ]);
        return redirect()->back()->with('success', "User trade mode has been turned $action.");
    }

    //Manually Verify users email
    public function emailverify($id)
    {
        User::where('id', $id)->update([
            'email_verified_at' => \Carbon\Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'User Email have been verified');
    }

    //Reset Password
    public function resetpswd($id)
    {
        User::where('id', $id)
            ->update([
                'password' => Hash::make('user01236'),
            ]);
        return redirect()->back()->with('success', 'Password has been reset to default');
    }

    //Clear user Account
    public function clearacct(Request $request, $id)
    {
        $settings = Settings::where('id', 1)->first();

        $deposits = Deposit::where('user', $id)->get();
        if (!empty($deposits)) {
            foreach ($deposits as $deposit) {
                Deposit::where('id', $deposit->id)->delete();
            }
        }

        $withdrawals = Withdrawal::where('user', $id)->get();
        if (!empty($withdrawals)) {
            foreach ($withdrawals as $withdrawals) {
                Withdrawal::where('id', $withdrawals->id)->delete();
            }
        }

        User::where('id', $id)->update([
            'account_bal' => '0',
            'roi' => '0',
            'bonus' => '0',
            'ref_bonus' => '0',
        ]);
        return redirect()->back()->with('success', "Account cleared to $settings->currency 0.00");
    }

    //Access users account
    public function switchuser($id)
    {
        $user = User::where('id', $id)->first();
        Auth::loginUsingId($user->id, true);
        return redirect()->route('dashboard')->with('success', "You are logged in as $user->name !");
    }

    //Manually Add Trading History to Users Route
    public function addHistory(Request $request)
    {
        Tp_Transaction::create([
            'user' => $request->user_id,
            'plan' => $request->plan,
            'amount' => $request->amount,
            'type' => $request->type,
        ]);
        $user = User::where('id', $request->user_id)->first();
        $user_bal = $user->account_bal;

        if (isset($request['amount']) > 0) {
            User::where('id', $request->user_id)
                ->update([
                    'account_bal' => $user_bal + $request->amount,
                ]);
        }
        $user_roi = $user->roi;
        if (isset($request['type']) == "ROI") {
            User::where('id', $request->user_id)
                ->update([
                    'roi' => $user_roi + $request->amount,
                ]);
        }

        return redirect()->back()
            ->with('success', 'Action Sucessful!');
    }


    //Delete user
    public function delsystemuser($id)
    {
        //delete the user's withdrawals and deposits
        $deposits = Deposit::where('user', $id)->get();
        if (!empty($deposits)) {
            foreach ($deposits as $deposit) {
                Deposit::where('id', $deposit->id)->delete();
            }
        }
        $withdrawals = Withdrawal::where('user', $id)->get();
        if (!empty($withdrawals)) {
            foreach ($withdrawals as $withdrawals) {
                Withdrawal::where('id', $withdrawals->id)->delete();
            }
        }
        //delete the user plans
        $userp = User_plans::where('user', $id)->get();
        if (!empty($userp)) {
            foreach ($userp as $p) {
                //delete plans that their owner does not exist 
                User_plans::where('id', $p->id)->delete();
            }
        }
        //delete the user from agent model if exists
        $agent = Agent::where('agent', $id)->first();
        if (!empty($agent)) {
            Agent::where('id', $agent->id)->delete();
        }

        // delete user from verification list
        if (DB::table('kycs')->where('user_id', $id)->exists()) {
            Kyc::where('user_id', $id)->delete();
        }

        User::where('id', $id)->delete();
        return redirect()->route('manageusers')
            ->with('success', 'User Account deleted successfully!');
    }

    //update users info
    public function edituser(Request $request)
    {


        User::where('id', $request['user_id'])

            ->update([
                'name' => $request['name'],
                'email' => $request['email'],
                'country' => $request['country'],
                'username' => $request['username'],
                'phone' => $request['phone'],
                'dob' => $request['dob'],
                'ref_link' => Null,
                'currency' => $request['currency'],
                'usernumber' => $request['usernumber'],
                'code1' => $request['code1'],
                'code2' => $request['code2'],
                'code3' => $request['code3'],
                'accounttype' => $request['accounttype'],
                'pin' => $request['pin'],
                'country' => $request['country'],
                'address' => $request['address'],
                'limit' => $request['limit'],

            ]);


        return redirect()->back()->with('success', 'User details updated Successfully!');
    }

    // Update user account balance and crypto balances
    public function updateBalances(Request $request, $id)
    {
        $request->validate([
            'account_bal' => 'required|numeric|min:0',
            'btc' => 'required|numeric|min:0',
            'eth' => 'required|numeric|min:0',
            'usdt' => 'required|numeric|min:0',
        ]);

        // Update fiat balance
        User::where('id', $id)->update([
            'account_bal' => $request->account_bal,
        ]);

        // Update crypto balances
        $cryptoAccount = CryptoAccount::firstOrCreate(['user_id' => $id]);
        $cryptoAccount->update([
            'btc' => $request->btc,
            'eth' => $request->eth,
            'usdt' => $request->usdt,
        ]);

        return redirect()->back()->with('success', 'Account balances updated successfully!');
    }

    //Send mail to one user
    public function sendmailtooneuser(Request $request)
    {

        $mailduser = User::where('id', $request->user_id)->first();
        Mail::to($mailduser->email)->send(new NewNotification($request->message, $request->subject, $mailduser->name));
        return redirect()->back()->with('success', 'Your message was sent successfully!');
    }

    // Send Mail to all users
    public function sendmailtoall(Request $request)
    {

        if ($request->category == "All") {
            $users = User::all();
        } elseif ($request->category == "No active plans") {
            $users = User::whereDoesntHave('plans', function (Builder $query) {
                $query->where('active', '!=', 'yes');
            })->get();
        } elseif ($request->category == "No deposit") {
            $users = User::doesntHave('dp')->get();
        } elseif ($request->category == "Select Users") {
            $users = DB::table('users')
                ->whereIn('id', array_column($request->users, null))
                ->get();
        }
        if (count($users) > 0) {
            Mail::to($users)->send(new NewNotification($request->message, $request->subject, $request->title, null, null, $request->greet));
            return redirect()->back()->with('success', 'Your message was sent successfully!');
        } else {
            return redirect()->back()->with("success", "No user under selected category to send mail to");
        }
    }

    // Delete User investment Plan
    public function deleteplan($id)
    {
        User_plans::where('id', $id)->delete();
        return redirect()->back()->with('success', 'User Loan deleted successfully!');
    }


    //action 
    public function action(Request $request)
    {

        $user = User::where('id', $request->user_id)->first();
        User::where('id', $request['user_id'])
            ->update([
                'amount' => $request['amount'],
                'action' => $request->type,
            ]);

        return redirect()->back()->with('success', 'Action added Successful!');
    }



    //action 
    public function signalaction(Request $request)
    {

        $user = User::where('id', $request->user_id)->first();
        User::where('id', $request['user_id'])
            ->update([
                'signalamount' => $request['signalamount'],
                'signalname' => $request['signalname'],
                'signalstatus' => $request->signalstatus,
            ]);

        return redirect()->back()->with('success', 'signal action added Successful!');
    }

    public function saveuser(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|max:255',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:8|confirmed',
            'photo' => 'mimes:jpg,jpeg,png|max:4000|image',
        ]);

        $strtxt = $this->RandomStringGenerator(6);

        $thisid = DB::table('users')->insertGetId([
            'name' => $request['name'],
            'email' => $request['email'],
            'phone' => $request['phone'],
            'ref_by' => NULL,
            'username' => $request['username'],
            'password' => Hash::make($request->password),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
            'lastname' => $request['lastname'],
            'middlename' => $request['middlename'],
            'accounttype' => $request['accounttype'],
            'pin' => $request['pin'],
            'dob' => $request['dob'],
            'country' => $request['country'],
            'address' => $request['address'],
            'usernumber' => $request['usernumber'],
            'code1'  => $request['code1'],
            'code2' => $request['code2'],
            'code3' => $request['code3'],
            'account_verify' => 'Verified',
            'email_verified_at' => \Carbon\Carbon::now(),

        ]);

        //assign referal link to user
        $settings = Settings::where('id', '=', '1')->first();
        $user = User::where('id', $thisid)->first();


        User::where('id', $thisid)
            ->update([
                'ref_link' => $settings->site_address . '/ref/' . $user->username,


            ]);

        

        if ($request->hasfile('photo')) {
            $document1 = $request->file('photo');
            $ext = strtolower($document1->getClientOriginalExtension() ?: $document1->extension() ?: '');
            $whitelist = ['jpeg', 'jpg', 'png', 'webp'];

            if (!in_array($ext, $whitelist, true)) {
                return redirect()->back()
                    ->with('message', 'Unaccepted Profile Image Uploaded, try renaming the image file');
            }

            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('photos');
            $stored = $document1->store('photos', 'public');
            $cardname = $stored ? basename($stored) : null;

            if ($cardname) {
                User::where('id', $thisid)
                    ->update([
                        'profile_photo_path' => $cardname,
                    ]);
            }
        }


        return redirect()->back()->with('success', 'User Registered Sucessful!');
    }

    //changing user profile image



    function profileimage(Request $request)
    {
        $this->validate($request, [
            'photo' => 'mimes:jpg,jpeg,png,webp|max:4096|image',
        ]);

        if ($request->hasfile('photo')) {
            $document1 = $request->file('photo');
            $ext = strtolower($document1->getClientOriginalExtension() ?: $document1->extension() ?: '');
            $whitelist = ['jpeg', 'jpg', 'png', 'webp'];

            if (!in_array($ext, $whitelist, true)) {
                return redirect()->back()
                    ->with('message', 'Unaccepted Profile Image Uploaded, try renaming the image file');
            }

            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('photos');
            $stored = $document1->store('photos', 'public');
            if (!$stored) {
                return redirect()->back()
                    ->with('message', 'Could not save profile photo. Check storage permissions for www-data.');
            }

            User::where('id', $request->user_id)
                ->update([
                    'profile_photo_path' => basename($stored),
                ]);
        }
        return redirect()->back()
            ->with('success', 'Action Sucessful! Profile Photo Uploaded Successfully.');
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

   
    function RandomStringGenerator($n)
    {
        $generated_string = "";
        $domain = "12345678900123456789023456789034567890456789056789067890890";
        $len = strlen($domain);
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, $len - 1);
            $generated_string = $generated_string . $domain[$index];
        }
        // Return the random generated string 
        return $generated_string;
    }
}
