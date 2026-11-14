<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailCodeVerificationController extends Controller
{
    /**
     * Show the email verification notice.
     *
     * @return \Illuminate\View\View
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Verify the email address with the provided code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $user = Auth::user();

        // Check if the code matches
        if ($user->email_verification_code !== $request->code) {
            return back()->with('message', 'Invalid verification code. Please check and try again.');
        }

        // Check if the code has expired
        if ($user->email_code_expires_at < now()) {
            return back()->with('message', 'Verification code has expired. Please request a new one.');
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->email_verification_code = null;
        $user->email_code_expires_at = null;
        $user->save();

        return redirect('/dashboard')->with('status', 'Email verified successfully!');
    }

    /**
     * Resend the verification code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        $user->sendEmailVerificationNotification();

        return back()->with('status', 'A new verification code has been sent to your email address.');
    }
}
