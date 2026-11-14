@extends('layouts.guest2')

@section('title', 'Admin Password Reset')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Password Reset Info -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Reset Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-[#0D1B23] leading-tight">
                    Forgot Your
                    <span class="bg-gradient-to-r from-primary-400 to-champagne-400 bg-clip-text text-transparent">
                        Password?
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    No worries! Enter your administrator email address and we'll send you a reset token to regain access to your management account.
                </p>
            </div>
            
            <!-- Reset Process Steps -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-[#0D1B23] uppercase tracking-wider mb-4">Password Reset Process</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-500 font-bold text-sm">1</span>
                        </div>
                        <div class="pt-1">
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Enter Your Email</p>
                            <p class="text-xs text-[#7A7A7A]">Provide the email associated with your admin account</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-champagne-500/20 flex items-center justify-center">
                            <span class="text-champagne-400 font-bold text-sm">2</span>
                        </div>
                        <div class="pt-1">
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Check Your Email</p>
                            <p class="text-xs text-[#7A7A7A]">We'll send a reset token to your email address</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center">
                            <span class="text-primary-500 font-bold text-sm">3</span>
                        </div>
                        <div class="pt-1">
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Create New Password</p>
                            <p class="text-xs text-[#7A7A7A]">Use the token to set a new secure password</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="pt-8">
                <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="shield-check" class="w-6 h-6 text-primary-500 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Secure Reset Process</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                Your security is our priority. All reset requests are verified and tokens expire after a short period to protect your administrator account.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Forgot Password Form -->
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
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-champagne-500/20 mb-4">
                        <i data-lucide="mail-search" class="w-8 h-8 text-champagne-400"></i>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-[#0D1B23]">Reset Manager Password</h2>
                    <p class="text-[#7A7A7A]">Enter your email to receive a password reset token</p>
                </div>
                
                <!-- Alerts -->
                <x-danger-alert/>
                <x-success-alert/>
                
                @if (session('status'))
                    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4" role="alert">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="check-circle" class="w-5 h-5 text-[#28A745]"></i>
                            <p class="text-sm text-green-300">{{ session('status') }}</p>
                        </div>
                    </div>
                @endif
                
                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('sendpasswordrequest') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-[#4A4A4A]">
                            Administrator Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="email"
                                type="email" 
                                name="email" 
                                required
                                autofocus
                                placeholder="admin@example.com"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm"
                            />
                        </div>
                        @if ($errors->has('email'))
                            <p class="text-xs text-[#D55672] mt-1 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $errors->first('email') }}</span>
                            </p>
                        @endif
                        <p class="text-xs text-[#7A7A7A]">Enter the email address associated with your admin account</p>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-[#0D1B23] text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Send Reset Token</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
                
                <!-- Footer Links -->
                <div class="pt-6 border-t border-gray-700/50 space-y-4">
                    <div class="text-center">
                        <a href="{{ route('adminloginform') }}" class="auth-link text-sm inline-flex items-center space-x-1">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>Back to Login</span>
                        </a>
                    </div>
                    <p class="text-xs text-[#7A7A7A] text-center">
                        &copy; Copyright {{ date('Y') }} {{ $settings->site_name }}. All Rights Reserved.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection