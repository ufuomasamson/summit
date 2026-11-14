<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use App\Models\User_plans;
use App\Models\User;
use App\Models\Settings;
use App\Models\Tp_Transaction;
use App\Mail\NewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvestmentController extends Controller
{
    /**
     * Display available investment plans
     */
    public function index()
    {
        $plans = Plans::where('type', 'investment')
                     ->where('is_active', true)
                     ->orderBy('min_price')
                     ->get();
        
        $settings = Settings::first();
        
        $myInvestments = User_plans::where('user', Auth::id())
                                   ->where('investment_type', 'investment')
                                   ->where('active', 'yes')
                                   ->count();
        
        $totalInvested = User_plans::where('user', Auth::id())
                                   ->where('investment_type', 'investment')
                                   ->sum('amount');
        
        $totalReturns = User_plans::where('user', Auth::id())
                                  ->where('investment_type', 'investment')
                                  ->sum('roi_received');

        return view('user.investments.index', [
            'title' => 'Investment Plans',
            'plans' => $plans,
            'settings' => $settings,
            'myInvestments' => $myInvestments,
            'totalInvested' => $totalInvested,
            'totalReturns' => $totalReturns,
        ]);
    }

    /**
     * Show investment form
     */
    public function invest($planId)
    {
        $plan = Plans::where('is_active', true)->findOrFail($planId);
        $settings = Settings::first();

        return view('user.investments.invest', [
            'title' => 'Invest in ' . $plan->name,
            'plan' => $plan,
            'settings' => $settings,
        ]);
    }

    /**
     * Process investment
     */
    public function processInvestment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
        ]);

        $user = User::findOrFail(Auth::id());
        $plan = Plans::where('is_active', true)->findOrFail($request->plan_id);
        $settings = Settings::first();

        // Validate amount range
        if ($request->amount < $plan->min_price || $request->amount > $plan->max_price) {
            return back()->with('error', "Investment amount must be between {$settings->currency}{$plan->min_price} and {$settings->currency}{$plan->max_price}");
        }

        // Check user balance
        if ($user->account_bal < $request->amount) {
            return back()->with('error', 'Insufficient account balance!');
        }

        DB::beginTransaction();
        
        try {
            // Deduct from user balance
            $user->account_bal = $user->account_bal - $request->amount;
            $user->save();

            // Create investment record
            $investment = new User_plans();
            $investment->user = $user->id;
            $investment->plan = $plan->id;
            $investment->plan_name = $plan->name;
            $investment->amount = $request->amount;
            $investment->active = 'yes';
            $investment->investment_type = 'investment';
            $investment->activated_at = now();
            $investment->last_growth = now();
            
            // Calculate expiration date
            $duration = $plan->duration;
            $durationType = $plan->duration_type;
            
            if ($durationType === 'days') {
                $investment->expire_date = now()->addDays($duration);
            } elseif ($durationType === 'weeks') {
                $investment->expire_date = now()->addWeeks($duration);
            } else {
                $investment->expire_date = now()->addMonths($duration);
            }
            
            $investment->inv_duration = $duration . ' ' . $durationType;
            $investment->save();

            // Create transaction history
            Tp_Transaction::create([
                'user' => $user->id,
                'plan' => "Investment in {$plan->name}",
                'amount' => $request->amount,
                'type' => 'Investment',
            ]);

            DB::commit();

            // Send notification email
            $message = "Your investment of {$settings->currency}{$request->amount} in {$plan->name} has been successfully processed. You will receive returns according to the plan schedule.";
            Mail::to($user->email)->send(new NewNotification($message, 'Investment Successful', $user->name));

            return redirect()->route('investments.my-investments')
                           ->with('success', 'Investment successful! Your returns will be credited automatically.');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing investment: ' . $e->getMessage());
        }
    }

    /**
     * Display user's investments
     */
    public function myInvestments()
    {
        $investments = User_plans::where('user', Auth::id())
                                 ->where('investment_type', 'investment')
                                 ->with('plan_details')
                                 ->orderByDesc('id')
                                 ->get();
        
        $settings = Settings::first();
        
        $stats = [
            'active' => User_plans::where('user', Auth::id())
                                 ->where('investment_type', 'investment')
                                 ->where('active', 'yes')
                                 ->count(),
            'total_invested' => User_plans::where('user', Auth::id())
                                         ->where('investment_type', 'investment')
                                         ->sum('amount'),
            'total_returns' => User_plans::where('user', Auth::id())
                                        ->where('investment_type', 'investment')
                                        ->sum('roi_received'),
            'pending' => User_plans::where('user', Auth::id())
                                  ->where('investment_type', 'investment')
                                  ->where('active', 'Pending')
                                  ->count(),
        ];

        return view('user.investments.my-investments', [
            'title' => 'My Investments',
            'investments' => $investments,
            'settings' => $settings,
            'stats' => $stats,
        ]);
    }

    /**
     * View single investment details
     */
    public function viewInvestment($id)
    {
        $investment = User_plans::where('user', Auth::id())
                                ->where('investment_type', 'investment')
                                ->with('plan_details')
                                ->findOrFail($id);
        
        $settings = Settings::first();

        return view('user.investments.view', [
            'title' => 'Investment Details',
            'investment' => $investment,
            'settings' => $settings,
        ]);
    }
}
