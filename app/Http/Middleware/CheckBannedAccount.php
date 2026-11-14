<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBannedAccount
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if account is permanently banned
            if ($user->account_status === 'permanently_banned') {
                $message = 'Your account has been permanently banned. Please contact support for more information.';
                
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $request->session()->flash('message', $message);
                
                return redirect()->route('login');
            }
            
            // Check if account is temporarily blocked
            if ($user->account_status === 'blocked') {
                $message = 'Your account has been temporarily blocked.';
                if ($user->block_reason) {
                    $message .= ' Reason: ' . $user->block_reason;
                }
                if ($user->unblock_at) {
                    $message .= ' Your account will be automatically unblocked on ' . \Carbon\Carbon::parse($user->unblock_at)->format('M d, Y \a\t h:i A') . '.';
                }
                $message .= ' Please contact support for more information.';
                
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                $request->session()->flash('message', $message);
                
                return redirect()->route('login');
            }
        }

        return $next($request);
    }
}
