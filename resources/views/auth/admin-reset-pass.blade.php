@extends('layouts.guest2')

@section('title', 'Reset Manager Password')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in" x-data="{ showPassword: false, showPasswordConfirm: false }">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Reset Password Info -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Security Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-[#0D1B23] leading-tight">
                    Secure
                    <span class="bg-gradient-to-r from-primary-400 to-champagne-400 bg-clip-text text-transparent">
                        Password Reset
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    Reset your administrator password securely. Create a strong new password to protect your management access.
                </p>
            </div>
            
            <!-- Password Requirements -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-[#0D1B23] uppercase tracking-wider mb-4">Password Requirements</h3>
                <div class="space-y-3">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#4A4A4A]">At least 8 characters long</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#4A4A4A]">Mix of uppercase and lowercase letters</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#4A4A4A]">Include numbers and special characters</p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#4A4A4A]">Avoid common words or patterns</p>
                    </div>
                </div>
            </div>
            
            <!-- Security Tip -->
            <div class="pt-8">
                <div class="bg-champagne-500/10 border border-champagne-500/20 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="lightbulb" class="w-6 h-6 text-champagne-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Admin Security Tip</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                As a manager, use a unique password that you don't use anywhere else. Consider using a password manager for enhanced security.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Reset Password Form -->
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
                        <i data-lucide="key-round" class="w-8 h-8 text-primary-500"></i>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-[#0D1B23]">Reset Manager Password</h2>
                    <p class="text-[#7A7A7A]">Enter your reset token and create a new secure password</p>
                </div>
                
                <!-- Alerts -->
                <x-error-alert />
                <x-danger-alert/>
                <x-success-alert />
                
                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('restpass') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email Field (readonly) -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-[#4A4A4A]">
                            Email Address
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="mail" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="email"
                                type="email" 
                                name="email" 
                                value="{{ $email }}"
                                readonly
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm bg-gray-800/50 cursor-not-allowed"
                            />
                        </div>
                        @error('email')
                            <p class="text-xs text-[#D55672] mt-1 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Token Field -->
                    <div class="space-y-2">
                        <label for="token" class="block text-sm font-semibold text-[#4A4A4A]">
                            Reset Token
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="hash" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="token"
                                type="number" 
                                name="token" 
                                required
                                autofocus
                                placeholder="Enter the token from your email"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm"
                            />
                        </div>
                        @error('token')
                            <p class="text-xs text-[#D55672] mt-1 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                        <p class="text-xs text-[#7A7A7A]">Check your email for the reset token</p>
                    </div>
                    
                    <!-- New Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-[#4A4A4A]">
                            New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password" 
                                required
                                placeholder="Create a strong password"
                                class="auth-input w-full pl-12 pr-12 py-3.5 rounded-xl text-sm"
                            />
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors"
                            >
                                <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-[#D55672] mt-1 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#4A4A4A]">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock-keyhole" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="password_confirmation"
                                :type="showPasswordConfirm ? 'text' : 'password'"
                                name="password_confirmation" 
                                required
                                placeholder="Re-enter your new password"
                                class="auth-input w-full pl-12 pr-12 py-3.5 rounded-xl text-sm"
                            />
                            <button 
                                type="button"
                                @click="showPasswordConfirm = !showPasswordConfirm"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors"
                            >
                                <i :data-lucide="showPasswordConfirm ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-xs text-[#D55672] mt-1 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        name="submit"
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-[#0D1B23] text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Reset Password</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
                
                <!-- Footer -->
                <div class="pt-6 border-t border-gray-700/50">
                    <div class="text-center">
                        <a href="{{ route('admin.login') }}" class="auth-link text-sm inline-flex items-center space-x-1">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>Back to Login</span>
                        </a>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection
