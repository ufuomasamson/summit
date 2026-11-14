<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Settings;
use App\Models\Plans;
use App\Models\Tp_Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Mail\NewNotification;
use App\Models\User_plans;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class LoanController extends Controller
{
    
    public function loan(Request $request){
        //get user
        $user=User::where('id',Auth::user()->id)->first();
        //get plan
        
        // Calculate end date based on duration (in months)
        $end_at = \Carbon\Carbon::now()->addMonths($request['duration']);
    
        //save user laon
        $userplanid = DB::table('user_plans')->insertGetId([
            
            'user' => Auth::user()->id,
            'amount' => $request['amount'],
            'income_range'=> $request['income'],
            'loan_purpose'=> $request['purpose'],
            'duration'=>$request['duration'],
            'plan_name' => $request['facility'],
            'active' => 'Pending',
            'inv_duration'=>$request['duration'],
            'expire_date' => $end_at,
            'activated_at' => \Carbon\Carbon::now(),
            'last_growth' => \Carbon\Carbon::now(),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        User::where('id',Auth::user()->id)
        ->update([
          
          'user_plan' => $userplanid,
          'entered_at'=>\Carbon\Carbon::now(),
        ]);

        // send notification
        $settings=Settings::where('id', '=', '1')->first();
        $message = "This is to inform you that $user->name just applied for a loan plan for $request->purpose";
        $subject ="Loan Application by $user->name ";
        Mail::to($settings->contact_email)->send(new NewNotification($message, $subject, 'Admin'));

        return redirect()->back()
          ->with('success', "You have successfully applied for a loan your loan is currently pending, you will be contacted soon.");
    }


    public function veiwloans(){

        $loans = User_plans::where('user', Auth::user()->id)->orderByDesc('id')->get();
        $settings = Settings::first();
      
        return view('user.loans',['loans'=>$loans, 'title' => 'Loan History', 'settings' => $settings]);
    }
    
    public function exportLoans(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,excel',
            'method' => 'required|in:download,email',
        ]);

        $loans = User_plans::where('user', Auth::user()->id)->orderByDesc('id')->get();
        $user = Auth::user();
        $settings = Settings::first();

        if ($request->format == 'csv') {
            return $this->exportToCsv($loans, $user, $request->method);
        } else {
            return $this->exportToExcel($loans, $user, $request->method);
        }
    }

    private function exportToCsv($loans, $user, $method)
    {
        $filename = 'loan_history_' . date('Y-m-d_His') . '.csv';
        
        $csvData = [];
        
        // Add headers
        $csvData[] = [
            'Loan ID',
            'Loan Type',
            'Amount',
            'Purpose',
            'Duration',
            'Monthly Income Range',
            'Status',
            'Date Applied',
            'Approved Date'
        ];
        
        // Add data rows
        foreach ($loans as $loan) {
            $duration = $loan->duration;
            if ($duration < 12) {
                $durationText = $duration . ($duration == 1 ? ' Month' : ' Months');
            } elseif ($duration % 12 == 0) {
                $years = $duration / 12;
                $durationText = $years . ($years == 1 ? ' Year' : ' Years');
            } else {
                $years = floor($duration / 12);
                $months = $duration % 12;
                $durationText = $years . ($years == 1 ? ' Year' : ' Years') . ' ' . $months . ($months == 1 ? ' Month' : ' Months');
            }
            
            $csvData[] = [
                $loan->id,
                $loan->plan_name ?? 'N/A',
                $user->currency . number_format($loan->amount, 2),
                $loan->loan_purpose ?? 'N/A',
                $durationText,
                $loan->income_range ?? 'N/A',
                $loan->active,
                Carbon::parse($loan->created_at)->format('M d, Y h:i A'),
                $loan->activated_at ? Carbon::parse($loan->activated_at)->format('M d, Y h:i A') : 'N/A'
            ];
        }
        
        if ($method == 'email') {
            // Save to temp file
            $path = storage_path('app/temp/' . $filename);
            $handle = fopen($path, 'w');
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
            
            // Send email
            Mail::raw("Please find attached your loan history export.", function($message) use ($user, $path, $filename) {
                $message->to($user->email)
                        ->subject('Loan History Export')
                        ->attach($path, ['as' => $filename]);
            });
            
            // Delete temp file
            unlink($path);
            
            return redirect()->back()->with('success', 'Export sent to your email successfully!');
        } else {
            // Direct download
            $handle = fopen('php://output', 'w');
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
            exit;
        }
    }

    private function exportToExcel($loans, $user, $method)
    {
        // For Excel, we'll use CSV format with .xlsx extension
        // In production, you'd use a package like PhpSpreadsheet
        $filename = 'loan_history_' . date('Y-m-d_His') . '.xlsx';
        
        $csvData = [];
        
        // Add headers
        $csvData[] = [
            'Loan ID',
            'Loan Type',
            'Amount',
            'Purpose',
            'Duration',
            'Monthly Income Range',
            'Status',
            'Date Applied',
            'Approved Date'
        ];
        
        // Add data rows
        foreach ($loans as $loan) {
            $duration = $loan->duration;
            if ($duration < 12) {
                $durationText = $duration . ($duration == 1 ? ' Month' : ' Months');
            } elseif ($duration % 12 == 0) {
                $years = $duration / 12;
                $durationText = $years . ($years == 1 ? ' Year' : ' Years');
            } else {
                $years = floor($duration / 12);
                $months = $duration % 12;
                $durationText = $years . ($years == 1 ? ' Year' : ' Years') . ' ' . $months . ($months == 1 ? ' Month' : ' Months');
            }
            
            $csvData[] = [
                $loan->id,
                $loan->plan_name ?? 'N/A',
                $user->currency . number_format($loan->amount, 2),
                $loan->loan_purpose ?? 'N/A',
                $durationText,
                $loan->income_range ?? 'N/A',
                $loan->active,
                Carbon::parse($loan->created_at)->format('M d, Y h:i A'),
                $loan->activated_at ? Carbon::parse($loan->activated_at)->format('M d, Y h:i A') : 'N/A'
            ];
        }
        
        if ($method == 'email') {
            // Save to temp file
            $path = storage_path('app/temp/' . $filename);
            
            // Ensure temp directory exists
            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }
            
            $handle = fopen($path, 'w');
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
            
            // Send email
            Mail::raw("Please find attached your loan history export.", function($message) use ($user, $path, $filename) {
                $message->to($user->email)
                        ->subject('Loan History Export')
                        ->attach($path, ['as' => $filename]);
            });
            
            // Delete temp file
            unlink($path);
            
            return redirect()->back()->with('success', 'Export sent to your email successfully!');
        } else {
            // Direct download
            $handle = fopen('php://output', 'w');
            
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            foreach ($csvData as $row) {
                fputcsv($handle, $row);
            }
            
            fclose($handle);
            exit;
        }
    }
    
}
