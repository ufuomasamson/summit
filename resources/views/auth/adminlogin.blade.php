@extends('layouts.guest2')

@section('title', 'Manager Login')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in" x-data="{ showPassword: false }">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Admin Portal Branding -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Welcome Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-[#0D1B23] leading-tight">
                    Management
                    <span class="bg-gradient-to-r from-forest-400 to-champagne-400 bg-clip-text text-transparent">
                        Portal
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    Secure access for authorized administrators and managers. Please authenticate to continue to the management dashboard.
                </p>
            </div>
            
            <!-- Admin Features -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-[#0D1B23] uppercase tracking-wider mb-4">Admin Capabilities</h3>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i data-lucide="users" class="w-6 h-6 text-primary-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">User Management</p>
                        <p class="text-xs text-[#7A7A7A]">Full control over customer accounts and permissions</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-champagne-500/20 flex items-center justify-center">
                        <i data-lucide="activity" class="w-6 h-6 text-champagne-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Transaction Oversight</p>
                        <p class="text-xs text-[#7A7A7A]">Monitor and manage all financial transactions</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i data-lucide="settings" class="w-6 h-6 text-primary-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">System Configuration</p>
                        <p class="text-xs text-[#7A7A7A]">Configure platform settings and preferences</p>
                    </div>
                </div>
            </div>
            
            <!-- Security Notice -->
            <div class="pt-8">
                <div class="bg-champagne-500/10 border border-champagne-500/20 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="shield-alert" class="w-6 h-6 text-champagne-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Secure Access Required</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                This portal is for authorized personnel only. All login attempts are logged and monitored for security purposes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Admin Login Form -->
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
                    <h2 class="font-serif text-3xl font-bold text-[#0D1B23]">Manager Login</h2>
                    <p class="text-[#7A7A7A]">Sign in to access the management dashboard</p>
                </div>
                
                <!-- Alerts -->
                <x-danger-alert />
                <x-success-alert />
                
                <!-- Admin Login Form -->
                <form method="POST" action="{{ route('adminlogin') }}" class="space-y-6">
                    @csrf
                    
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
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="admin@example.com"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm"
                            />
                        </div>
                        @error('email')
                            <p class="text-xs text-[#D55672] mt-1 flex items-center space-x-1">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>
                    
                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-[#4A4A4A]">
                            Password
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
                                placeholder="Enter your password"
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
                    </div>
                    
                    <!-- Forgot Password Link -->
                    <div class="flex items-center justify-end">
                        <a href="{{ route('admin.forgetpassword') }}" class="auth-link text-sm">
                            Forgot your password?
                        </a>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-white text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Sign In to Dashboard</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
                
                <!-- Footer -->
                <div class="pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-[#7A7A7A] text-center">
                        &copy; Copyright {{ date('Y') }} {{ $settings->site_name }}. All Rights Reserved.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection
