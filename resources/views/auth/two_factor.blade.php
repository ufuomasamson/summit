@extends('layouts.guest2')

@section('title', 'Two Factor Authentication')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - 2FA Information -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- 2FA Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-[#0D1B23] leading-tight">
                    Extra Layer of
                    <span class="bg-gradient-to-r from-primary-400 to-champagne-400 bg-clip-text text-transparent">
                        Security
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    Two-Factor Authentication (2FA) adds an extra layer of security to your account by requiring a verification code.
                </p>
            </div>
            
            <!-- How It Works -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-[#0D1B23] uppercase tracking-wider mb-4">How It Works</h3>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                        <span class="font-serif text-lg font-bold text-primary-500">1</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Code Sent to Email</p>
                        <p class="text-xs text-[#7A7A7A]">A unique verification code has been sent to your registered email address</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-champagne-500/20 flex items-center justify-center">
                        <span class="font-serif text-lg font-bold text-champagne-400">2</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Check Your Inbox</p>
                        <p class="text-xs text-[#7A7A7A]">Look for an email from {{ $settings->site_name }} with your verification code</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                        <span class="font-serif text-lg font-bold text-primary-500">3</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Enter & Verify</p>
                        <p class="text-xs text-[#7A7A7A]">Input the code below to complete your secure login</p>
                    </div>
                </div>
            </div>
            
            <!-- Security Note -->
            <div class="pt-8">
                <div class="bg-champagne-500/10 border border-champagne-500/20 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="mail-check" class="w-6 h-6 text-champagne-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Didn't receive the code?</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed mb-3">
                                Check your spam folder or wait a few moments. Codes typically arrive within 1-2 minutes.
                            </p>
                            <p class="text-xs text-champagne-400">
                                If you're still having trouble, you can restart the login process.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - 2FA Verification Form -->
        <div class="w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-10 w-auto mx-auto">
            </div>
            
            <!-- Auth Card -->
            <div class="auth-card rounded-3xl p-8 md:p-10 space-y-8">
                
                <!-- Header -->
                <div class="text-center space-y-3">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-100 mb-4">
                        <i data-lucide="shield-check" class="w-8 h-8 text-primary-500"></i>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-[#0D1B23]">Two-Factor Authentication</h2>
                    <p class="text-[#7A7A7A]">A verification code has been sent to your email. Please enter it below to continue.</p>
                </div>
                
                <!-- Alert Message -->
                @if(Session::has('message'))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ Session::get('message') }}</p>
                    </div>
                @endif
                
                <!-- 2FA Form -->
                <form method="POST" action="{{ route('twofalogin') }}" class="space-y-6">
                    @csrf
                    
                    <!-- 2FA Code Field -->
                    <div class="space-y-2">
                        <label for="twofa" class="block text-sm font-semibold text-[#4A4A4A]">
                            Verification Code
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="key" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="twofa"
                                type="text" 
                                name="twofa" 
                                required 
                                autofocus
                                placeholder="Enter 6-digit code"
                                maxlength="6"
                                pattern="[0-9]*"
                                inputmode="numeric"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-xl font-medium focus:ring-0 transition-all tracking-widest text-center"
                            />
                        </div>
                        @if ($errors->has('twofa'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('twofa') }}</p>
                        @endif
                        <p class="text-xs text-[#7A7A7A] text-center">Enter the 6-digit code from your email</p>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-[#0D1B23] text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Verify & Continue</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <!-- Divider -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-slate-850 text-[#7A7A7A]">Need help?</span>
                        </div>
                    </div>
                    
                    <!-- Restart Login Link -->
                    <div class="text-center">
                        <p class="text-sm text-[#7A7A7A] mb-3">Having trouble accessing your account?</p>
                        <a 
                            href="{{ route('adminlogout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="inline-flex items-center space-x-2 text-sm font-medium auth-link"
                        >
                            <i data-lucide="refresh-ccw" class="w-4 h-4"></i>
                            <span>Restart Login Process</span>
                        </a>
                    </div>
                </form>
                
                <!-- Hidden Logout Form -->
                <form id="logout-form" action="{{ route('adminlogout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                
                <!-- Footer Info -->
                <div class="pt-6 border-t border-gray-700/50">
                    <div class="flex items-center justify-center space-x-2 text-xs text-[#7A7A7A]">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                        <p>Verification codes expire after 10 minutes</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    // Auto-focus and format the 2FA input
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('twofa');
        if (input) {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
</script>
@endsection