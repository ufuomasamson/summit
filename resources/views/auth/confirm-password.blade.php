@extends('layouts.guest2')

@section('title', 'Confirm Password')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Security Information -->
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
                    Protected
                    <span class="bg-gradient-to-r from-primary-400 to-champagne-400 bg-clip-text text-transparent">
                        Area
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    This is a secure area of your account. Please confirm your password to continue.
                </p>
            </div>
            
            <!-- Security Features -->
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-6 h-6 text-primary-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Enhanced Security</p>
                        <p class="text-xs text-[#7A7A7A]">Additional verification protects sensitive account operations</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-champagne-500/20 flex items-center justify-center">
                        <i data-lucide="lock" class="w-6 h-6 text-champagne-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Your Privacy Matters</p>
                        <p class="text-xs text-[#7A7A7A]">We never store or transmit your password in plain text</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i data-lucide="clock" class="w-6 h-6 text-primary-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Session Protection</p>
                        <p class="text-xs text-[#7A7A7A]">Temporary confirmation for this session only</p>
                    </div>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="pt-8">
                <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="info" class="w-6 h-6 text-primary-500 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Why do we ask?</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                Password confirmation adds an extra layer of security when accessing critical account features or making important changes.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Password Confirmation Form -->
        <div class="w-full" x-data="{ showPassword: false }">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-10 w-auto mx-auto">
            </div>
            
            <!-- Auth Card -->
            <div class="auth-card rounded-3xl p-8 md:p-10 space-y-8">
                
                <!-- Header -->
                <div class="text-center space-y-3">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-100 mb-4">
                        <i data-lucide="shield-alert" class="w-8 h-8 text-primary-500"></i>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-[#0D1B23]">Confirm Password</h2>
                    <p class="text-[#7A7A7A]">This is a secure area. Please confirm your password before continuing.</p>
                </div>
                
                <!-- Confirmation Form -->
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-[#4A4A4A]">
                            Current Password
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
                                autocomplete="current-password"
                                autofocus
                                placeholder="Enter your password"
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
                    
                    <!-- Error Messages -->
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
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-[#0D1B23] text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Confirm & Continue</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </form>
                
                <!-- Footer Info -->
                <div class="pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-[#7A7A7A] text-center leading-relaxed">
                        Your password is encrypted and never stored in plain text. This verification is only valid for your current session.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
@endsection