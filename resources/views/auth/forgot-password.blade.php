@extends('layouts.guest2')

@section('title', 'Forgot Password')
@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Branding & Support Info -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Support Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-[#0D1B23] leading-tight">
                    We're Here to
                    <span class="bg-gradient-to-r from-forest-400 to-champagne-400 bg-clip-text text-transparent">
                        Help You
                    </span>
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    Forgot your password? No worries. We'll send you a secure reset link to get you back to your account quickly.
                </p>
            </div>
            
            <!-- Help Steps -->
            <div class="space-y-4">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                        <span class="font-serif text-lg font-bold text-primary-500">1</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Enter Your Email</p>
                        <p class="text-xs text-[#7A7A7A]">Provide the email address associated with your account</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-champagne-500/20 flex items-center justify-center">
                        <span class="font-serif text-lg font-bold text-champagne-400">2</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Check Your Inbox</p>
                        <p class="text-xs text-[#7A7A7A]">We'll send a secure password reset link to your email</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center">
                        <span class="font-serif text-lg font-bold text-primary-500">3</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23] mb-1">Create New Password</p>
                        <p class="text-xs text-[#7A7A7A]">Follow the link and set up your new secure password</p>
                    </div>
                </div>
            </div>
            
            <!-- Security Note -->
            <div class="pt-8">
                <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="shield-check" class="w-6 h-6 text-primary-500 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Secure & Private</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                Your security is our priority. Password reset links expire after 60 minutes and can only be used once.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Reset Form -->
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
                <div class="space-y-3">
                    <h2 class="font-serif text-3xl font-bold text-[#0D1B23]">Reset Password</h2>
                    <p class="text-[#7A7A7A]">Enter the email you used to create your account and we'll send you a reset link.</p>
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
                
                <!-- Reset Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                                placeholder="name@email.com"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                        @if ($errors->has('email'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-[#0D1B23] text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Email Password Reset Link</span>
                        <i data-lucide="send" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <!-- Back to Login -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-slate-850 text-[#7A7A7A]">or</span>
                        </div>
                    </div>
                    
                    <a 
                        href="{{ route('login') }}" 
                        class="block w-full py-3.5 rounded-xl font-semibold text-sm text-center border border-primary-300 hover:border-forest-500/50 hover:bg-primary-500/5 text-[#4A4A4A] hover:text-white transition-all"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            <span>Back to Login</span>
                        </div>
                    </a>
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

@section('scripts')
@parent
@endsection