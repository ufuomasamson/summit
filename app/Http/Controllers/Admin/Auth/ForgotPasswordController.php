<?php

namespace App\Http\Controllers\Admin\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\NewNotification;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class ForgotPasswordController extends Controller
{
    public function forgotPassword(){
        return view('auth.admin-forgot-password',[

        ]);
    }
public function sendPasswordRequest(Request $request){
    $request->validate([
        'email' => 'required|email|exists:admins,email'
    ]);

    // Generate a strong random token
    $rawToken = Str::random(64);

    $admin = Admin::where('email', $request->email)->first();
    $admin->password_token = Hash::make($rawToken);
    $admin->password_token_expires = now()->addMinutes(15); // token valid for 15 minutes
    $admin->save();

    $message = "Password reset request received.  
    Use this token to reset your password: $rawToken  
    Token expires in 15 minutes.";

    $subject = "Reset Password Request";

    Mail::to($request->email)->send(new NewNotification(
        $message,
        $subject,
        "$admin->firstName $admin->lastName"
    ));

    return redirect()->route('resetview', $admin->email)
        ->with('success', 'A reset token has been sent to your email.');
}

    // Validate token
    public function validateResetPasswordToken(Request $request){

        $request->validate([
            'email' => 'required|email',
            'token' => 'required|exists:admins,password_token',
            'password' => 'required|confirmed',
        ]);

        $user = Admin::where('email', $request->email)->first();
        if (!$user) {
            return redirect()->back()
            ->with('message', 'This Email does not exist');
        }

        if ($user->password_token != $request->token) {
            return redirect()->back()
            ->with('message', 'Incorrect token');
        }

        Admin::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
            'password_token' => NULL,
        ]);

        return redirect()->route('adminloginform')->with('success', 'Password Reset successful, login now');

    }
}
