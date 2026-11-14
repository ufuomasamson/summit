<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BulkTransactionController extends Controller
{
    // Sample data for random generation
    private $banks = [
        'Chase Bank', 'Bank of America', 'Wells Fargo', 'Citibank', 
        'TD Bank', 'PNC Bank', 'Capital One', 'HSBC', 'Barclays',
        'US Bank', 'Truist Bank', 'Citizens Bank', 'Fifth Third Bank',
        'KeyBank', 'Santander Bank', 'Ally Bank', 'American Express Bank'
    ];

    private $firstNames = [
        'James', 'Mary', 'John', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda',
        'William', 'Barbara', 'David', 'Elizabeth', 'Richard', 'Susan', 'Joseph', 'Jessica',
        'Thomas', 'Sarah', 'Charles', 'Karen', 'Christopher', 'Nancy', 'Daniel', 'Lisa',
        'Matthew', 'Betty', 'Anthony', 'Margaret', 'Mark', 'Sandra', 'Donald', 'Ashley'
    ];

    private $lastNames = [
        'Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis',
        'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas',
        'Taylor', 'Moore', 'Jackson', 'Martin', 'Lee', 'Perez', 'Thompson', 'White',
        'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson', 'Walker', 'Young'
    ];

    /**
     * Generate bulk transactions for a user
     */
    public function generateBulk(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'transaction_type' => 'required|in:Credit,Debit',
            'min_amount' => 'required|numeric|min:1',
            'max_amount' => 'required|numeric|gt:min_amount',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'count' => 'required|integer|min:1|max:500',
            'payment_mode' => 'required|string',
        ]);

        $user = User::find($validated['user_id']);
        $settings = Settings::first();
        $generated = 0;
        $skipped = 0;

        // Calculate total debit amount if debit type
        if ($validated['transaction_type'] === 'Debit') {
            $estimatedTotalDebit = ($validated['min_amount'] + $validated['max_amount']) / 2 * $validated['count'];
            
            if ($user->account_bal < $estimatedTotalDebit) {
                return redirect()->back()->with('message', 
                    'Insufficient balance. User has ' . $settings->currency . number_format($user->account_bal, 2) . 
                    ' but estimated total debits: ' . $settings->currency . number_format($estimatedTotalDebit, 2));
            }
        }

        for ($i = 0; $i < $validated['count']; $i++) {
            // Generate random data
            $amount = $this->randomAmount($validated['min_amount'], $validated['max_amount']);
            $date = $this->randomDateBetween($validated['from_date'], $validated['to_date']);
            $accountName = $this->generateRandomName();
            $accountNumber = $this->generateRandomAccountNumber();
            $bankName = $this->generateRandomBankName();
            $txnId = $this->generateTxnId($settings);

            // Check balance for debit transactions
            if ($validated['transaction_type'] === 'Debit') {
                if ($user->account_bal < $amount) {
                    $skipped++;
                    continue; // Skip this transaction
                }
            }

            // Create withdrawal record with backdated timestamps
            $withdrawal = new Withdrawal();
            $withdrawal->timestamps = false; // Disable auto timestamps
            $withdrawal->user = $user->id;
            $withdrawal->amount = $amount;
            $withdrawal->to_deduct = $amount;
            $withdrawal->type = $validated['transaction_type'];
            $withdrawal->status = 'Processed';
            $withdrawal->payment_mode = $validated['payment_mode'];
            $withdrawal->accountname = $accountName;
            $withdrawal->accountnumber = $accountNumber;
            $withdrawal->bankname = $bankName;
            $withdrawal->Description = $validated['transaction_type'] === 'Credit' 
                ? "Incoming transfer from {$accountName}" 
                : "Outgoing transfer to {$accountName}";
            $withdrawal->txn_id = $txnId;
            $withdrawal->date = $date;
            $withdrawal->created_at = $date;
            $withdrawal->updated_at = $date;
            $withdrawal->bal = $user->account_bal;
            $withdrawal->save();

            // Update user balance
            if ($validated['transaction_type'] === 'Credit') {
                $user->increment('account_bal', $amount);
            } else {
                $user->decrement('account_bal', $amount);
            }

            $generated++;
        }

        // Refresh user to get updated balance
        $user->refresh();

        $message = "{$generated} transactions generated successfully!";
        if ($skipped > 0) {
            $message .= " ({$skipped} skipped due to insufficient balance)";
        }
        $message .= " New balance: {$settings->currency}" . number_format($user->account_bal, 2);

        return redirect()->back()->with('success', $message);
    }

    /**
     * Generate random amount between min and max
     */
    private function randomAmount($min, $max)
    {
        // Convert to cents to avoid float precision issues
        $minCents = $min * 100;
        $maxCents = $max * 100;
        $randomCents = rand($minCents, $maxCents);
        return round($randomCents / 100, 2);
    }

    /**
     * Generate random date between start and end
     */
    private function randomDateBetween($start, $end)
    {
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        $randomTimestamp = rand($startTimestamp, $endTimestamp);
        return Carbon::createFromTimestamp($randomTimestamp);
    }

    /**
     * Generate random full name
     */
    private function generateRandomName()
    {
        $firstName = $this->firstNames[array_rand($this->firstNames)];
        $lastName = $this->lastNames[array_rand($this->lastNames)];
        return "{$firstName} {$lastName}";
    }

    /**
     * Generate random 11-digit account number
     */
    private function generateRandomAccountNumber()
    {
        return str_pad(rand(10000000000, 99999999999), 11, '0', STR_PAD_LEFT);
    }

    /**
     * Generate random bank name
     */
    private function generateRandomBankName()
    {
        return $this->banks[array_rand($this->banks)];
    }

    /**
     * Generate unique transaction ID
     */
    private function generateTxnId($settings)
    {
        $subtxn = substr(strtoupper($settings->site_name), 0, 2);
        $codetxn1 = $this->RandomStringGenerator(8);
        $codetxn2 = $this->RandomStringGenerator(5);
        return "{$subtxn}{$codetxn1}{$codetxn2}";
    }

    /**
     * Random string generator (from existing codebase pattern)
     */
    private function RandomStringGenerator($n)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}
