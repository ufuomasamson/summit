<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Tp_Transaction;
use App\Models\User;
use App\Models\CryptoAccount;
use App\Traits\PingServer;
use App\Traits\CreatesNotifications;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\FundAccountRequest;
use App\Models\Withdrawal;
use App\Models\Settings;
use App\Mail\NewNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Twilio\Rest\Client;

class TopupController extends Controller
{
   use PingServer, CreatesNotifications;

    //top up route
    public function topup(FundAccountRequest $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $userdpo = Deposit::where('user', $request['user_id'])->first();
        $settings = Settings::where('id', '1')->first();
        $user_bal = $user->account_bal;
        $user_bonus = $user->bonus;
        $user_roi = $user->roi;
        $user_Ref = $user->ref_bonus;

        $subtxn = substr(strtoupper($settings->site_name), 0, 4);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        $txn_id = "$subtxn/$codetxn1-$codetxn2";
        
        $transactionDate = $request->date ? Carbon::parse($request->date) : Carbon::now();

        if($request['t_type'] == "Credit") {
            if ($request['type'] == "balance") {
        
                $dp = new Withdrawal();
                $dp->amount = $request['amount'];
                $dp->payment_mode = $request['scope'];
                $dp->Description = $request['Description'];
                $dp->created_at = $transactionDate;
                $dp->type = 'Credit';
                $dp->accountname = 'Self';
                $dp->bankname = $settings->site_name;
                $dp->accountnumber = $user->usernumber;
                $dp->Accounttype = $user->accounttype;
                $dp->country = $user->country;
                $dp->bankaddress = $settings->address;
                $dp->status = 'Processed';
                
                // Sender information for credits
                $dp->sender_name = $request->sender_name;
                $dp->sender_bank = $request->sender_bank;
                $dp->sender_account_number = $request->sender_account_number;
                $dp->sender_bank_address = $request->sender_bank_address;
                $dp->reference_number = $request->reference_number;
                $dp->admin_note = $request->admin_note;
                $dp->processed_at = Carbon::now();
                $dp->processed_by = Auth::guard('admin')->id();
                
                $dp->user = $request['user_id'];
                $dp->txn_id = $txn_id;
                $dp->save();

                $new_balance = $user_bal + $request->amount;
                
                User::where('id', $request['user_id'])
                ->update([
                    'account_bal' => $new_balance,
                ]);
              
               if($request['notifymailuser'] == 1){
                 // Send professional credit alert email
                Mail::to($user->email)->send(new \App\Mail\AccountCreditAlert(
                    $user,
                    $request->amount,
                    $settings,
                    $user_bal,
                    $request->reference_number ?? $txn_id,
                    $request->Description,
                    $request->sender_name,
                    $request->sender_account_number
                ));
                
                // Create in-app notification
                $this->notifyDeposit($user->id, $request->amount, $settings->currency, $request->scope);
                
                // SMS notification if enabled
                if($settings->sms == '1' && $user->phone){
                    $receiverNumber = $user->phone;
                    $message = "Credit Alert: {$settings->currency}{$request->amount} received from {$request->sender_bank}. Ref: {$request->reference_number}. New Balance: {$settings->currency}" . number_format($new_balance, 2);
          
                    try {
                        $account_sid = getenv("TWILIO_SID");
                        $auth_token = getenv("TWILIO_TOKEN");
                        $twilio_number = getenv("TWILIO_FROM");
  
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => $twilio_number, 
                'body' => $message]);
  
          
  
        } catch (Exception $e) {
            
        }

        }




               }

            }
            
            // Handle crypto account funding (Credit)
            if (str_starts_with($request['type'], 'crypto_')) {
                $cryptoCoin = str_replace('crypto_', '', $request['type']);
                $coinNames = CryptoAccount::COIN_NAMES;
                $coinName = $coinNames[$cryptoCoin] ?? strtoupper($cryptoCoin);
                
                // Ensure crypto account exists
                $cryptoAccount = CryptoAccount::firstOrCreate(
                    ['user_id' => $request['user_id']],
                    ['btc' => 0, 'eth' => 0, 'usdt' => 0]
                );
                
                $dp = new Withdrawal();
                $dp->amount = $request['amount'];
                $dp->payment_mode = $request['scope'] ?? 'Crypto Deposit';
                $dp->Description = $request['Description'] ?? "{$coinName} credit";
                $dp->created_at = $transactionDate;
                $dp->type = 'Credit';
                $dp->accountname = 'Self';
                $dp->bankname = $settings->site_name;
                $dp->accountnumber = $user->usernumber;
                $dp->Accounttype = $user->accounttype;
                $dp->country = $user->country;
                $dp->bankaddress = $settings->address ?? '';
                $dp->status = 'Processed';
                $dp->crypto_currency = $cryptoCoin;
                $dp->crypto_wallet_address = $request->crypto_wallet_address;
                $dp->crypto_txn_hash = $request->crypto_txn_hash;
                $dp->admin_note = $request->admin_note;
                $dp->processed_at = Carbon::now();
                $dp->processed_by = Auth::guard('admin')->id();
                $dp->user = $request['user_id'];
                $dp->txn_id = $txn_id;
                $dp->save();

                // Update crypto balance
                $cryptoAccount->$cryptoCoin = $cryptoAccount->$cryptoCoin + $request->amount;
                $cryptoAccount->save();

                if ($request['notifymailuser'] == 1) {
                    $this->notifyDeposit($user->id, $request->amount . ' ' . $coinName, $settings->currency, 'Crypto Funding');
                }
            }
            
            //add history
            Tp_Transaction::create([
            'user' => $request->user_id,
            'plan' => "Credit",
            'amount'=>$request->amount,
            'type'=>$request->type,
            ]);
        
        }elseif($request['t_type']=="Debit") {
          if ($request['type']=="Bonus") {
            User::where('id', $request['user_id'])
              ->update([
                'bonus'=> $user_bonus - $request['amount'],
                'account_bal'=> $user_bal - $request->amount,
              ]);
          }elseif ($request['type']=="Profit") {
              User::where('id', $request->user_id)
                ->update([
                  'roi'=> $user_roi - $request->amount,
                  'account_bal'=> $user_bal - $request->amount,
                ]);
            }elseif($request['type']=="Ref_Bonus"){
              User::where('id', $request->user_id)
                ->update([
                  'Ref_Bonus'=> $user_Ref - $request->amount,
                  'account_bal'=> $user_bal - $request->amount,
                ]);
            }
            elseif($request['type']=="balance"){

                $dp=new Withdrawal();
                $dp->amount= $request['amount'];
                $dp->payment_mode = $request['scope'];
                $dp->Description = $request['Description'];
                $dp->created_at  = $request['date'];
                $dp->type    = $request['t_type'];
                $dp->accountname = 'Self';
                $dp->status= 'Processed';
                $dp->user= $request['user_id'];
                 $dp->bankname = $settings->bankname;
              $dp->accountnumber = $user->usernumber;
                $dp->Accounttype = $user->Accountype;
                $dp->country  = $user->country;
                $dp->bankaddress = $settings->address;
                $dp->txn_id = "$subtxn/$codetxn1-$codetxn2";
                
                // Recipient information for debits (stored in sender_* fields)
                $dp->sender_name = $request->sender_name;
                $dp->sender_bank = $request->sender_bank;
                $dp->sender_account_number = $request->sender_account_number;
                $dp->sender_bank_address = $request->sender_bank_address;
                $dp->reference_number = $request->reference_number;
                $dp->admin_note = $request->admin_note;
                $dp->processed_at = Carbon::now();
                $dp->processed_by = Auth::guard('admin')->id();
                
                $dp->save();
                
                User::where('id', $request->user_id)
                  ->update([
                    'account_bal'=> $user_bal - $request->amount,
                  ]);

                $bala =  $user_bal - $request->amount;
                $dp->bal = $bala;
                $dp->save();
                
                  if($request['notifymailuser']==1){
                    // Send professional debit alert email
                    Mail::to($user->email)->send(new \App\Mail\WithdrawalStatus($dp, $user, 'Account Debit Notification', false, $settings));
                    
                    // Create in-app notification
                    $this->notifyTransfer(
                        $user->id,
                        number_format($request->amount, 2),
                        $settings->currency,
                        $request->Description ?? 'debit',
                        'processed'
                    );
   
   
           if($settings->sms=='1' && $user->phone){
               $date = Carbon::parse($dp->created_at)->toDayDateTimeString();
               $receiverNumber = $user->phone;
           $message = "Your account have been Debited.
           \nDetails of the transaction are shown below;
        \nAccount Number: $dp->accountnumber
        \nAccount Name: $user->name $user->middlename $user->lastname
        \nDescription: $request->Description
        \nTotal Amount: $request->amount$settings->s_currency
        \nDate:$date
        \nAvailable Balance:$bala$settings->s_currency";
     
           try {
     
               $account_sid = getenv("TWILIO_SID");
               $auth_token = getenv("TWILIO_TOKEN");
               $twilio_number = getenv("TWILIO_FROM");
     
               $client = new Client($account_sid, $auth_token);
               $client->messages->create($receiverNumber, [
                   'from' => $twilio_number, 
                   'body' => $message]);
     
             
     
           } catch (Exception $e) {
               
           }
   
           }
   
   
   
   
                  }
   

              }
            
            // Handle crypto account debit
            if (str_starts_with($request['type'], 'crypto_')) {
                $cryptoCoin = str_replace('crypto_', '', $request['type']);
                $coinNames = CryptoAccount::COIN_NAMES;
                $coinName = $coinNames[$cryptoCoin] ?? strtoupper($cryptoCoin);
                
                $cryptoAccount = CryptoAccount::where('user_id', $request['user_id'])->first();
                if (!$cryptoAccount || $cryptoAccount->$cryptoCoin < $request->amount) {
                    return redirect()->back()->with('message', "Insufficient {$coinName} balance for this debit.");
                }
                
                $dp = new Withdrawal();
                $dp->amount = $request['amount'];
                $dp->payment_mode = $request['scope'] ?? 'Crypto Withdrawal';
                $dp->Description = $request['Description'] ?? "{$coinName} debit";
                $dp->created_at = $transactionDate;
                $dp->type = 'Debit';
                $dp->accountname = 'Self';
                $dp->status = 'Processed';
                $dp->crypto_currency = $cryptoCoin;
                $dp->bankname = $settings->site_name ?? '';
                $dp->accountnumber = $user->usernumber;
                $dp->Accounttype = $user->accounttype;
                $dp->country = $user->country;
                $dp->bankaddress = $settings->address ?? '';
                $dp->txn_id = "$subtxn/$codetxn1-$codetxn2";
                $dp->crypto_wallet_address = $request->crypto_wallet_address;
                $dp->crypto_txn_hash = $request->crypto_txn_hash;
                $dp->admin_note = $request->admin_note;
                $dp->processed_at = Carbon::now();
                $dp->processed_by = Auth::guard('admin')->id();
                $dp->user = $request['user_id'];
                $dp->save();

                // Update crypto balance
                $cryptoAccount->$cryptoCoin = $cryptoAccount->$cryptoCoin - $request->amount;
                $cryptoAccount->save();

                if ($request['notifymailuser'] == 1) {
                    $this->notifyTransfer(
                        $user->id,
                        $request->amount . ' ' . $coinName,
                        $settings->currency,
                        $request->Description ?? 'crypto debit',
                        'processed'
                    );
                }
            }
            
             //add history
            Tp_Transaction::create([
                'user' => $request->user_id,
                'plan' => "Credit reversal",
                'amount'=>$request->amount,
                'type'=>$request->type,
            ]);
        
        } elseif (in_array($request['t_type'], ['Failed', 'Pending', 'Error'], true)) {
            // History-only statuses — appear on user account history, no balance change
            $direction = in_array($request->history_direction, ['Credit', 'Debit'], true)
                ? $request->history_direction
                : 'Debit';

            $isCrypto = str_starts_with($request['type'], 'crypto_');
            $cryptoCoin = $isCrypto ? str_replace('crypto_', '', $request['type']) : null;

            $dp = new Withdrawal();
            $dp->amount = $request['amount'];
            $dp->payment_mode = $request['scope'];
            $dp->Description = $request['Description'] ?? ($request['t_type'] . ' transaction');
            $dp->created_at = $transactionDate;
            $dp->type = $direction;
            $dp->accountname = 'Self';
            $dp->bankname = $settings->site_name ?? ($settings->bankname ?? '');
            $dp->accountnumber = $user->usernumber;
            $dp->Accounttype = $user->accounttype ?? $user->Accountype ?? null;
            $dp->country = $user->country;
            $dp->bankaddress = $settings->address ?? '';
            $dp->status = $request['t_type']; // Failed | Pending | Error
            $dp->sender_name = $request->sender_name;
            $dp->sender_bank = $request->sender_bank;
            $dp->sender_account_number = $request->sender_account_number;
            $dp->sender_bank_address = $request->sender_bank_address;
            $dp->reference_number = $request->reference_number;
            $dp->admin_note = $request->admin_note;
            $dp->processed_at = Carbon::now();
            $dp->processed_by = Auth::guard('admin')->id();
            $dp->user = $request['user_id'];
            $dp->txn_id = $txn_id;
            $dp->bal = $user_bal;

            if ($isCrypto) {
                $dp->crypto_currency = $cryptoCoin;
                $dp->crypto_wallet_address = $request->crypto_wallet_address;
                $dp->crypto_txn_hash = $request->crypto_txn_hash;
            }

            $dp->save();

            Tp_Transaction::create([
                'user' => $request->user_id,
                'plan' => $request['t_type'] . ' Transaction',
                'amount' => $request->amount,
                'type' => $request->type,
            ]);

            if ($request['notifymailuser'] == 1) {
                $this->notifyTransfer(
                    $user->id,
                    number_format($request->amount, 2),
                    $settings->currency,
                    $request->Description ?? ($request['t_type'] . ' transaction'),
                    strtolower($request['t_type'])
                );
            }

            return redirect()->back()->with('success', $request['t_type'] . ' transaction added to user history.');
        }
        return redirect()->back()->with('success', 'Action Successful!');
    }
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
