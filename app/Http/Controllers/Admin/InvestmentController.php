<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plans;
use App\Models\User_plans;
use App\Models\User;
use App\Models\Settings;
use App\Models\Tp_Transaction;
use App\Mail\NewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvestmentController extends Controller
{
    /**
     * Display investment dashboard
     */
    public function index()
    {
        $total_plans = Plans::where('type', 'investment')->count();
        $active_plans = Plans::where('type', 'investment')->where('is_active', true)->count();
        
        $total_investments = User_plans::where('investment_type', 'investment')->sum('amount');
        $active_investments = User_plans::where('investment_type', 'investment')
                                        ->where('active', 'yes')
                                        ->count();
        
        $pending_investments = User_plans::where('investment_type', 'investment')
                                         ->where('active', 'Pending')
                                         ->count();
        
        $total_roi_paid = User_plans::where('investment_type', 'investment')->sum('roi_received');
        
        $recent_investments = User_plans::where('investment_type', 'investment')
                                        ->with(['user', 'plan_details'])
                                        ->orderByDesc('id')
                                        ->take(10)
                                        ->get();

        return view('admin.investments.index', [
            'title' => 'Investment Management',
            'total_plans' => $total_plans,
            'active_plans' => $active_plans,
            'total_investments' => $total_investments,
            'active_investments' => $active_investments,
            'pending_investments' => $pending_investments,
            'total_roi_paid' => $total_roi_paid,
            'recent_investments' => $recent_investments,
        ]);
    }

    /**
     * Display all investment plans
     */
    public function plans()
    {
        $plans = Plans::where('type', 'investment')
                     ->orderByDesc('created_at')
                     ->get();

        return view('admin.investments.plans', [
            'title' => 'Investment Plans',
            'plans' => $plans,
        ]);
    }

    /**
     * Show create plan form
     */
    public function createPlan()
    {
        return view('admin.investments.create-plan', [
            'title' => 'Create Investment Plan',
        ]);
    }

    /**
     * Store new investment plan
     */
    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|gt:min_price',
            'roi_type' => 'required|in:percentage,fixed',
            'roi_value' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_type' => 'required|in:days,weeks,months',
            'return_capital' => 'required|boolean',
        ]);

        $plan = new Plans();
        $plan->name = $request->name;
        $plan->min_price = $request->min_price;
        $plan->max_price = $request->max_price;
        $plan->roi_type = $request->roi_type;
        $plan->roi_value = $request->roi_value;
        $plan->duration = $request->duration;
        $plan->duration_type = $request->duration_type;
        $plan->return_capital = $request->return_capital;
        $plan->is_active = $request->has('is_active');
        $plan->type = 'investment';
        
        // Calculate expected return for display
        $expected = $request->roi_type === 'percentage' 
                  ? ($request->min_price * $request->roi_value / 100)
                  : $request->roi_value;
        $plan->expected_return = $expected;
        
        $plan->save();

        return redirect()->route('admin.investments.plans')
                        ->with('success', 'Investment plan created successfully!');
    }

    /**
     * Show edit plan form
     */
    public function editPlan($id)
    {
        $plan = Plans::findOrFail($id);

        return view('admin.investments.edit-plan', [
            'title' => 'Edit Investment Plan',
            'plan' => $plan,
        ]);
    }

    /**
     * Update investment plan
     */
    public function updatePlan(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'min_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|gt:min_price',
            'roi_type' => 'required|in:percentage,fixed',
            'roi_value' => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'duration_type' => 'required|in:days,weeks,months',
            'return_capital' => 'required|boolean',
        ]);

        $plan = Plans::findOrFail($id);
        $plan->name = $request->name;
        $plan->min_price = $request->min_price;
        $plan->max_price = $request->max_price;
        $plan->roi_type = $request->roi_type;
        $plan->roi_value = $request->roi_value;
        $plan->duration = $request->duration;
        $plan->duration_type = $request->duration_type;
        $plan->return_capital = $request->return_capital;
        $plan->is_active = $request->has('is_active');
        
        // Calculate expected return
        $expected = $request->roi_type === 'percentage' 
                  ? ($request->min_price * $request->roi_value / 100)
                  : $request->roi_value;
        $plan->expected_return = $expected;
        
        $plan->save();

        return redirect()->route('admin.investments.plans')
                        ->with('success', 'Investment plan updated successfully!');
    }

    /**
     * Delete investment plan
     */
    public function deletePlan($id)
    {
        $plan = Plans::findOrFail($id);
        
        // Check if plan has active investments
        $activeInvestments = User_plans::where('plan', $id)
                                      ->where('active', 'yes')
                                      ->count();
        
        if ($activeInvestments > 0) {
            return back()->with('error', 'Cannot delete plan with active investments!');
        }
        
        $plan->delete();
        
        return redirect()->route('admin.investments.plans')
                        ->with('success', 'Investment plan deleted successfully!');
    }

    /**
     * Display all user investments
     */
    public function userInvestments()
    {
        $investments = User_plans::where('investment_type', 'investment')
                                 ->with(['user', 'plan_details'])
                                 ->orderByDesc('id')
                                 ->get();

        return view('admin.investments.user-investments', [
            'title' => 'User Investments',
            'investments' => $investments,
        ]);
    }

    /**
     * Show form to add manual investment for user
     */
    public function addManualInvestment()
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        $plans = Plans::where('type', 'investment')->where('is_active', true)->get();

        return view('admin.investments.add-manual', [
            'title' => 'Add Manual Investment',
            'users' => $users,
            'plans' => $plans,
        ]);
    }

    /**
     * Store manual investment
     */
    public function storeManualInvestment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'amount' => 'required|numeric|min:0',
            'roi_amount' => 'required|numeric|min:0',
            'add_to_balance' => 'required|in:yes,no',
        ]);

        $user = User::findOrFail($request->user_id);
        $plan = Plans::findOrFail($request->plan_id);
        $settings = Settings::first();

        DB::beginTransaction();
        
        try {
            // Create investment record
            $investment = new User_plans();
            $investment->user = $user->id;
            $investment->plan = $plan->id;
            $investment->plan_name = $plan->name;
            $investment->amount = $request->amount;
            $investment->roi_received = $request->roi_amount;
            $investment->active = 'yes';
            $investment->investment_type = 'investment';
            $investment->activated_at = now();
            $investment->last_growth = now();
            
            // Calculate expiration
            $duration = $plan->duration;
            $durationType = $plan->duration_type;
            
            if ($durationType === 'days') {
                $investment->expire_date = now()->addDays($duration);
            } elseif ($durationType === 'weeks') {
                $investment->expire_date = now()->addWeeks($duration);
            } else {
                $investment->expire_date = now()->addMonths($duration);
            }
            
            $investment->save();

            // Add to user balance if requested
            if ($request->add_to_balance === 'yes') {
                $user->account_bal = $user->account_bal + $request->roi_amount;
                $user->save();

                // Create transaction history
                Tp_Transaction::create([
                    'user' => $user->id,
                    'plan' => "Investment ROI - {$plan->name}",
                    'amount' => $request->roi_amount,
                    'type' => 'Investment ROI',
                ]);
            }

            DB::commit();

            // Send notification email
            $message = "An investment of {$settings->currency}{$request->amount} has been added to your account for the {$plan->name} plan. ROI: {$settings->currency}{$request->roi_amount}";
            Mail::to($user->email)->send(new NewNotification($message, 'Investment Added', $user->name));

            return redirect()->route('admin.investments.user-investments')
                           ->with('success', 'Manual investment added successfully!');
                           
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error adding investment: ' . $e->getMessage());
        }
    }

    /**
     * Manually process investment return
     */
    public function processReturn($id)
    {
        $investment = User_plans::findOrFail($id);
        $user = User::findOrFail($investment->user);
        $plan = Plans::findOrFail($investment->plan);
        $settings = Settings::first();

        DB::beginTransaction();
        
        try {
            // Calculate ROI
            $roi = 0;
            if ($plan->roi_type === 'percentage') {
                $roi = ($investment->amount * $plan->roi_value) / 100;
            } else {
                $roi = $plan->roi_value;
            }

            // Add ROI to user balance
            $user->account_bal = $user->account_bal + $roi;
            
            // Return capital if applicable
            if ($plan->return_capital) {
                $user->account_bal = $user->account_bal + $investment->amount;
            }
            
            $user->save();

            // Update investment record
            $investment->roi_received = $investment->roi_received + $roi;
            $investment->last_growth = now();
            
            if ($plan->return_capital) {
                $investment->capital_returned = true;
                $investment->active = 'expired';
            }
            
            $investment->save();

            // Create transaction history
            Tp_Transaction::create([
                'user' => $user->id,
                'plan' => "Investment ROI - {$plan->name}",
                'amount' => $roi,
                'type' => 'ROI',
            ]);

            if ($plan->return_capital) {
                Tp_Transaction::create([
                    'user' => $user->id,
                    'plan' => "Capital Return - {$plan->name}",
                    'amount' => $investment->amount,
                    'type' => 'Capital Return',
                ]);
            }

            DB::commit();

            // Send notification
            $totalReceived = $roi + ($plan->return_capital ? $investment->amount : 0);
            $message = "Your investment in {$plan->name} has matured. You received {$settings->currency}{$totalReceived}";
            Mail::to($user->email)->send(new NewNotification($message, 'Investment Matured', $user->name));

            return back()->with('success', 'Investment return processed successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error processing return: ' . $e->getMessage());
        }
    }

    /**
     * Cancel investment
     */
    public function cancelInvestment($id)
    {
        $investment = User_plans::findOrFail($id);
        $investment->active = 'cancelled';
        $investment->save();

        return back()->with('success', 'Investment cancelled successfully!');
    }
}
