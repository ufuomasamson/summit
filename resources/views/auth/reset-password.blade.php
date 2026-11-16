@extends('layouts.guest2')

@section('title', 'Reset your password')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Branding & Security Info -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Security Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-[#0D1B23] leading-tight">
                    Secure Your
                    <span class="bg-gradient-to-r from-primary-400 to-champagne-400 bg-clip-text text-transparent">
                        Account
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    Create a strong, unique password to protect your {{ $settings->site_name }} account and financial information.
                </p>
            </div>
            
            <!-- Password Requirements -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-[#0D1B23] uppercase tracking-wider mb-4">Password Requirements</h3>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-primary-500"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">At least 8 characters long</p>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-primary-500"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">Mix of uppercase and lowercase letters</p>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-primary-500"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">Include numbers and special characters</p>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-6 h-6 rounded-full bg-primary-100 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-primary-500"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">Avoid common words or patterns</p>
                </div>
            </div>
            
            <!-- Security Tip -->
            <div class="pt-8">
                <div class="bg-champagne-500/10 border border-champagne-500/20 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="lightbulb" class="w-6 h-6 text-champagne-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Security Tip</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                Use a unique password that you don't use for other accounts. Consider using a password manager to keep track of strong passwords.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Reset Password Form -->
        <div class="w-full" x-data="{ showPassword: false, showPasswordConfirm: false }">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-10 w-auto mx-auto">
            </div>
            
            <!-- Auth Card -->
            <div class="auth-card rounded-3xl p-8 md:p-10 space-y-8">
                
                <!-- Header -->
                <div class="space-y-3">
                    <h2 class="font-serif text-3xl font-bold text-[#0D1B23]">Create New Password</h2>
                    <p class="text-[#7A7A7A]">Enter your email and choose a strong password for your account.</p>
                </div>
                
                <!-- Alert Messages -->
                @if(Session::has('message'))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ Session::get('message') }}</p>
                    </div>
                @endif

                @if (session('status'))
                    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="check-circle" class="w-5 h-5 text-[#28A745] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-green-300">{{ session('status') }}</p>
                    </div>
                @endif
                
                <!-- Reset Password Form -->
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    
                    <!-- Email Field -->
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
                                value="{{ $email ?? old('email') }}"
                                required 
                                readonly
                                placeholder="name@email.com"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm font-medium focus:ring-0 transition-all bg-gray-800/50 cursor-not-allowed"
                            />
                        </div>
                        @if ($errors->has('email'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    
                    <!-- Password Field -->
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
                                placeholder="••••••••••"
                                class="auth-input w-full pl-12 pr-12 py-3.5 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors"
                            >
                                <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @if ($errors->has('password'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>
                    
                    <!-- Confirm Password Field -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-[#4A4A4A]">
                            Confirm New Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="password_confirmation"
                                :type="showPasswordConfirm ? 'text' : 'password'" 
                                name="password_confirmation" 
                                required 
                                placeholder="••••••••••"
                                class="auth-input w-full pl-12 pr-12 py-3.5 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                            <button 
                                type="button"
                                @click="showPasswordConfirm = !showPasswordConfirm"
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors"
                            >
                                <i :data-lucide="showPasswordConfirm ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                            </button>
                        </div>
                        @if ($errors->has('password_confirmation'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-[#0D1B23] text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Reset Password</span>
                        <i data-lucide="check" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    </button>
                    
                </form>
                
                <!-- Footer Info -->
                <div class="pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-[#7A7A7A] text-center">
                        © Copyright {{ date('Y') }} {{ $settings->site_name }}. All Rights Reserved.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

