@extends('layouts.guest2')
@section('title', 'OTP Verification')
@section('content')

<div class="w-full max-w-6xl mx-auto" 
     x-cloak
     x-data="{ 
         otp: '',
         timer: 30,
         canResend: false,
         
         getOtpValue() {
             return this.otp;
         },
         
         startTimer() {
             const interval = setInterval(() => {
                 if (this.timer > 0) {
                     this.timer--;
                 } else {
                     this.canResend = true;
                     clearInterval(interval);
                 }
             }, 1000);
         }
     }" 
     x-init="startTimer()">

    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Security Information -->
        <div class="hidden lg:block space-y-8">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Security Content -->
            <div class="space-y-6">
                <h1 class="text-4xl font-semibold text-[#0D1B23] leading-tight">
                    Identity Verification
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    We've sent a one-time verification code to your registered email. Enter the 6-digit code to verify your identity and continue.
                </p>
            </div>
            
            <!-- Security Features -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-[#0D1B23] uppercase tracking-wider mb-4">Why OTP Verification?</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-[#49786B]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Two-Factor Authentication</p>
                            <p class="text-xs text-[#7A7A7A]">An additional layer of security to protect your account from unauthorized access</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="mail-check" class="w-5 h-5 text-[#7A7A7A]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Email Verification</p>
                            <p class="text-xs text-[#7A7A7A]">Confirms access to your registered email before proceeding</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="timer" class="w-5 h-5 text-[#49786B]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Time-Limited Code</p>
                            <p class="text-xs text-[#7A7A7A]">Codes expire after 10 minutes for maximum security</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security Tip -->
            <div class="pt-8">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="info" class="w-5 h-5 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Keep Your Code Private</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                Never share your verification code with anyone, including bank staff. We will never ask for your code via phone or chat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - OTP Verification -->
        <div class="w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-10 w-auto mx-auto">
            </div>
            
            <!-- OTP Verification Card -->
            <div class="auth-card rounded-xl p-8 md:p-10 space-y-8">
                
                <!-- Header -->
                <div class="text-center space-y-4">
                    <div class="relative inline-block">
                        <div class="w-16 h-16 rounded-xl bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-8 h-8 text-[#49786B]"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-[#0D1B23]">Verify Your Identity</h2>
                        <p class="text-sm text-[#7A7A7A] mt-1">
                            We've sent a 6-digit code to<br>
                            <span class="text-[#49786B] font-semibold">{{ Auth::user()->email }}</span>
                        </p>
                    </div>
                </div>
                
                <!-- Alert Messages -->
                <div>
                    <x-danger-alert />
                    <x-success-alert />
                </div>
                
                <!-- OTP Form -->
                <form action="{{ route('codecomfirm') }}" method="post" class="space-y-6">
                    @csrf

                    <!-- OTP Input -->
                    <div class="space-y-2">
                        <label class="block text-sm font-semibold text-[#4A4A4A] text-center">
                            Enter Verification Code
                        </label>
                        <input 
                            type="text" 
                            name="otp"
                            x-model="otp"
                            @input="otp = otp.replace(/\D/g, '').slice(0, 6)"
                            maxlength="6"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            placeholder="000000"
                            autocomplete="one-time-code"
                            autofocus
                            class="auth-input w-full text-center text-2xl font-bold tracking-[0.4em] rounded-xl py-4 px-4"
                            required>
                    </div>

                    <!-- Timer & Resend -->
                    <div class="text-center">
                        <div x-show="!canResend" class="text-sm text-[#7A7A7A]">
                            <span>Didn't receive the code? Resend in </span>
                            <span class="font-bold text-[#49786B]" x-text="timer + 's'"></span>
                        </div>
                        <div x-show="canResend" class="text-sm">
                            <span class="text-[#7A7A7A]">Didn't receive the code? </span>
                            <a href="{{ route('getotp') }}" 
                               class="auth-link font-semibold inline-flex items-center space-x-1">
                                <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                                <span>Resend Code</span>
                            </a>
                        </div>
                    </div>

                    <!-- Info Banner -->
                    <div class="bg-[#49786B]/5 border border-[#49786B]/20 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <i data-lucide="info" class="w-5 h-5 text-[#49786B] flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-xs text-[#4A4A4A]">
                                    This code will expire in 10 minutes. For your security, never share this code with anyone.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            :disabled="otp.length < 6"
                            class="auth-button w-full py-4 rounded-xl font-semibold text-white text-sm flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed group">
                        <i data-lucide="check-circle" class="w-5 h-5"></i>
                        <span>Verify Code</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </button>

                    <p class="text-xs text-center text-[#7A7A7A]">
                        <i data-lucide="lock" class="w-3 h-3 inline mr-1"></i>
                        Your security is protected with end-to-end encryption
                    </p>
                </form>

                <!-- Footer -->
                <div class="pt-6 border-t border-[#E0E5E5]">
                    <div class="flex items-center justify-between text-xs text-[#7A7A7A]">
                        <a href="{{ route('dashboard') }}" 
                           class="auth-link inline-flex items-center space-x-1 group">
                            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                            <span>Back to Dashboard</span>
                        </a>
                        <span>Secure Connection</span>
                    </div>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="mt-6 bg-white border border-[#E0E5E5] rounded-xl p-5">
                <div class="flex items-start space-x-3">
                    <i data-lucide="help-circle" class="w-5 h-5 text-[#49786B] flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-2">Need Help?</p>
                        <p class="text-xs text-[#7A7A7A] mb-3">
                            If you're having trouble receiving your verification code, please contact our support team.
                        </p>
                        <a href="mailto:{{ $settings->contact_email }}" 
                           class="auth-link text-sm font-semibold inline-flex items-center space-x-2">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                            <span>Contact Support</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
