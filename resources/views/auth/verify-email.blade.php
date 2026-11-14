@extends('layouts.guest2')

@section('title', 'Verify Email Address')
@section('content')

<div class="w-full max-w-6xl mx-auto">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Email Verification Info -->
        <div class="hidden lg:block space-y-8">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Welcome Content -->
            <div class="space-y-6">
                <h1 class="text-4xl font-semibold text-[#0D1B23] leading-tight">
                    Almost There!
                </h1>
                <p class="text-base text-[#7A7A7A] leading-relaxed">
                    Thank you for registering with {{ $settings->site_name }}. We just need to verify your email address to complete your account setup.
                </p>
            </div>
            
            <!-- Why Verify -->
            <div class="space-y-4">
                <h3 class="text-xs font-semibold text-[#7A7A7A] uppercase tracking-wider mb-4">Why Verify Your Email?</h3>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mt-0.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23] mb-1">Account Security</p>
                        <p class="text-xs text-[#7A7A7A]">Protects your account from unauthorized access</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mt-0.5">
                        <i data-lucide="bell" class="w-4 h-4 text-[#7A7A7A]"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23] mb-1">Important Notifications</p>
                        <p class="text-xs text-[#7A7A7A]">Receive account updates and security alerts</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mt-0.5">
                        <i data-lucide="key" class="w-4 h-4 text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23] mb-1">Password Recovery</p>
                        <p class="text-xs text-[#7A7A7A]">Reset your password if you ever need to</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mt-0.5">
                        <i data-lucide="check-circle" class="w-4 h-4 text-[#7A7A7A]"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23] mb-1">Full Access</p>
                        <p class="text-xs text-[#7A7A7A]">Unlock all account features and services</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Verification Code Entry -->
        <div class="w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-10 w-auto mx-auto">
            </div>
            
            <!-- Auth Card -->
            <div class="auth-card rounded-2xl p-8 md:p-10 space-y-8">
                
                <!-- Header with Icon -->
                <div class="text-center space-y-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-emerald-500/15 border border-emerald-500/20 mb-3">
                        <i data-lucide="shield-check" class="w-8 h-8 text-emerald-400"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-[#0D1B23]">Enter Verification Code</h2>
                    <p class="text-sm text-[#7A7A7A]">We've sent a 6-digit code to your email address.</p>
                </div>
                
                <!-- Alert Messages -->
                @if (Session::has('message'))
                    <div class="bg-[#D55672]/10 border border-red-500/20 rounded-lg p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ Session::get('message') }}</p>
                    </div>
                @else
                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-emerald-300">Your registration is successful. A 6-digit verification code has been sent to your email address.</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-lg p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-emerald-300">{{ session('status') }}</p>
                    </div>
                @endif
                
                <!-- Verification Code Form -->
                <form action="{{ route('verification.verify') }}" method="POST" class="space-y-6" x-data="{
                    code: ['', '', '', '', '', ''],
                    handleInput(event, index) {
                        const value = event.target.value;
                        
                        // Only allow numbers
                        if (!/^\d*$/.test(value)) {
                            event.target.value = '';
                            this.code[index] = '';
                            return;
                        }
                        
                        this.code[index] = value;
                        
                        // Move to next input if value entered
                        if (value && index < 5) {
                            const nextInput = document.getElementById('code-' + (index + 1));
                            if (nextInput) {
                                nextInput.focus();
                            }
                        }
                    },
                    
                    handleBackspace(event, index) {
                        if (!event.target.value && index > 0) {
                            const prevInput = document.getElementById('code-' + (index - 1));
                            if (prevInput) {
                                prevInput.focus();
                            }
                        }
                    },
                    
                    handlePaste(event) {
                        event.preventDefault();
                        const pastedData = event.clipboardData.getData('text').trim();
                        
                        // Check if pasted data is 6 digits
                        if (/^\d{6}$/.test(pastedData)) {
                            const digits = pastedData.split('');
                            digits.forEach((digit, idx) => {
                                this.code[idx] = digit;
                                const input = document.getElementById('code-' + idx);
                                if (input) {
                                    input.value = digit;
                                }
                            });
                            // Focus the last input
                            const lastInput = document.getElementById('code-5');
                            if (lastInput) {
                                lastInput.focus();
                            }
                        }
                    }
                }">
                    @csrf
                    
                    <!-- OTP Input Fields -->
                    <div class="space-y-2">
                        <label class="block text-xs text-[#7A7A7A] uppercase tracking-wide text-center mb-4">
                            Enter 6-Digit Code
                        </label>
                        <div class="flex justify-center gap-2 md:gap-3">
                            <input type="hidden" name="code" :value="code.join('')">
                            
                            <template x-for="(digit, index) in code" :key="index">
                                <input 
                                    type="text" 
                                    maxlength="1"
                                    pattern="[0-9]"
                                    inputmode="numeric"
                                    :id="'code-' + index"
                                    x-model="code[index]"
                                    @input="handleInput($event, index)"
                                    @keydown.backspace="handleBackspace($event, index)"
                                    @paste="handlePaste($event)"
                                    class="w-12 h-14 md:w-14 md:h-16 text-center text-2xl font-semibold rounded-lg bg-[#F9F9F9]/60 border border-[#E0E5E5] text-[#0D1B23] focus:border-emerald-500/50 focus:ring-1 focus:ring-emerald-500/20 transition-colors"
                                    required
                                />
                            </template>
                        </div>
                        <p class="text-[11px] text-[#7A7A7A] text-center mt-4">Code expires in 15 minutes</p>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-lg font-medium text-[#0D1B23] text-sm flex items-center justify-center space-x-2"
                    >
                        <span>Verify Email</span>
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                    </button>
                </form>
                
                <!-- Troubleshooting -->
                <div class="space-y-4 pt-4">
                    <h3 class="text-sm font-medium text-[#0D1B23] text-center">Didn't receive the code?</h3>
                    <ul class="space-y-2 text-xs text-[#7A7A7A]">
                        <li class="flex items-start space-x-2">
                            <i data-lucide="circle" class="w-2 h-2 mt-1.5 flex-shrink-0 text-emerald-400"></i>
                            <span>Check your spam or junk folder</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i data-lucide="circle" class="w-2 h-2 mt-1.5 flex-shrink-0 text-emerald-400"></i>
                            <span>Make sure the email address is correct</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <i data-lucide="circle" class="w-2 h-2 mt-1.5 flex-shrink-0 text-emerald-400"></i>
                            <span>Wait a few minutes for email delivery</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Actions -->
                <div class="space-y-3">
                    <!-- Resend Code -->
                    <form id="resend-form" action="{{ route('verification.send') }}" method="POST">
                        @csrf
                    </form>
                    <button 
                        onclick="event.preventDefault(); document.getElementById('resend-form').submit();"
                        class="w-full py-3 rounded-lg font-medium text-sm text-center border border-emerald-500/20 hover:border-emerald-500/40 hover:bg-emerald-500/5 text-[#4A4A4A] hover:text-[#0D1B23] transition-colors"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            <span>Resend Verification Code</span>
                        </div>
                    </button>
                    
                    <!-- Sign Out -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <button 
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="w-full py-3 rounded-lg font-medium text-sm text-center border border-red-500/20 hover:border-red-500/40 hover:bg-red-500/5 text-[#7A7A7A] hover:text-[#0D1B23] transition-colors"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            <span>Sign Out</span>
                        </div>
                    </button>
                </div>
                
                <!-- Footer Help -->
                <div class="pt-6 border-t border-[#E0E5E5]">
                    <p class="text-xs text-[#7A7A7A] text-center leading-relaxed">
                        Need help? Contact our support team at 
                        <a href="mailto:{{ $settings->contact_email }}" class="auth-link">{{ $settings->contact_email }}</a>
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection
                       