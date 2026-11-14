@extends('layouts.guest2')

@section('title', 'Account Login')
@section('content')

<div class="w-full max-w-6xl mx-auto">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Branding & Welcome -->
        <div class="hidden lg:block space-y-8">
            <!-- Logo -->
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                         alt="{{ $settings->site_name }}" 
                         class="h-12 w-auto mb-6">
                </a>
            </div>
            
            <!-- Welcome Content -->
            <div class="space-y-6">
                <h1 class="text-4xl font-semibold text-[#0D1B23] leading-tight">
                    Welcome to Your
                    <span class="text-primary-500">
                        Financial Future
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    Swift and Secure Money Transfer to any bank account will become a breeze with {{ $settings->site_name }}.
                </p>
            </div>
            
            <!-- Trust Indicators -->
            <div class="grid grid-cols-3 gap-4 pt-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white mb-3">
                        <i data-lucide="shield-check" class="w-5 h-5 text-primary-500"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">Bank-Level Security</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white mb-3">
                        <i data-lucide="zap" class="w-5 h-5 text-[#7A7A7A]"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">Instant Transfers</p>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-white mb-3">
                        <i data-lucide="globe" class="w-5 h-5 text-primary-500"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">Global Reach</p>
                </div>
            </div>
            
            <!-- Decorative Illustration -->
            
        </div>
        
        <!-- Right Side - Login Form -->
        <div class="w-full" x-data="{ showPassword: false }">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                         alt="{{ $settings->site_name }}" 
                         class="h-10 w-auto mx-auto">
                </a>
            </div>
            
            <!-- Auth Card -->
            <div class="auth-card rounded-xl p-8 md:p-10 space-y-8">
                
                <!-- Header -->
                <div class="space-y-3">
                    <a href="{{ route('home') }}" class="auth-link inline-flex items-center space-x-1 text-sm mb-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Back to home</span>
                    </a>
                    <h2 class="text-2xl font-semibold text-[#0D1B23]">Sign In</h2>
                    <p class="text-[#7A7A7A]">Access the {{ $settings->site_name }} panel using your email and password.</p>
                </div>
                
                <!-- Alert Messages -->
                @if (session('message'))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ session('message') }}</p>
                    </div>
                @endif
                
                @if (Session::has('status'))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ session('status') }}</p>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 space-y-2" role="alert">
                        <div class="flex items-center space-x-2 mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672]"></i>
                            <p class="text-sm font-semibold text-[#D55672]">Please fix the following errors:</p>
                        </div>
                        <ul class="space-y-1 ml-7">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-[#D55672]">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Email or Username Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-[#4A4A4A]">
                            Email Address or Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="user" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="email"
                                type="text" 
                                name="email" 
                                autocomplete="username" 
                                required 
                                placeholder="email@example.com or username"
                                value="{{ old('email') }}"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                    </div>
                    
                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-semibold text-[#4A4A4A]">
                                Password
                            </label>
                            <a href="{{ route('password.request') }}" class="auth-link text-sm font-medium">
                                Forgot Password?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="lock" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="password"
                                :type="showPassword ? 'text' : 'password'" 
                                name="password" 
                                autocomplete="current-password" 
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
                    </div>
                    
                    <!-- Remember Me -->
                    <div class="flex items-center space-x-2">
                        <input 
                            type="checkbox" 
                            id="remember_me" 
                            name="remember_me"
                            checked
                            class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-primary-500 focus:ring-primary-500 focus:ring-offset-0 focus:ring-2 cursor-pointer"
                        />
                        <label for="remember_me" class="text-sm text-[#7A7A7A] cursor-pointer select-none">
                            Stay signed in for 30 days
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-white text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Sign In to Account</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <!-- Register Link -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-slate-850 text-[#7A7A7A]">or</span>
                        </div>
                    </div>
                    
                    <a 
                        href="{{ route('register') }}" 
                        class="block w-full py-3.5 rounded-xl font-semibold text-sm text-center border border-primary-300 hover:border-primary-500/50 hover:bg-primary-500/5 text-[#4A4A4A] hover:text-[#4A4A4A] transition-all"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            <span>Not enrolled? Create Account</span>
                        </div>
                    </a>
                </form>
                
                <!-- Footer Info -->
                <div class="pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-[#7A7A7A] text-center leading-relaxed">
                        By signing in, you agree to our 
                        <a href="{{ route('terms') }}" class="auth-link">Terms of Service</a> and 
                        <a href="{{ route('privacy') }}" class="auth-link">Privacy Policy</a>
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection
