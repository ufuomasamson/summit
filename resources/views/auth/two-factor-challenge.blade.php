@extends('layouts.guest2')

@section('title', 'Two-Factor Challenge')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in" x-data="{ recovery: false }">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - 2FA Challenge Info -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Security Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-white leading-tight">
                    Secure
                    <span class="bg-gradient-to-r from-primary-400 to-champagne-400 bg-clip-text text-transparent">
                        Authentication
                    </span>
                </h1>
                <p class="text-lg text-gray-300 leading-relaxed">
                    Your account is protected with Two-Factor Authentication. Please verify your identity to continue.
                </p>
            </div>
            
            <!-- Authentication Methods -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Authentication Methods</h3>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-primary-500/20 flex items-center justify-center">
                        <i data-lucide="smartphone" class="w-6 h-6 text-primary-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Authenticator App</p>
                        <p class="text-xs text-gray-400">Use the 6-digit code from your authenticator app (Google Authenticator, Authy, etc.)</p>
                    </div>
                </div>
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-champagne-500/20 flex items-center justify-center">
                        <i data-lucide="key-round" class="w-6 h-6 text-champagne-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Recovery Code</p>
                        <p class="text-xs text-gray-400">Use one of your emergency recovery codes if you don't have access to your device</p>
                    </div>
                </div>
            </div>
            
            <!-- Security Tip -->
            <div class="pt-8">
                <div class="bg-primary-500/10 border border-primary-500/20 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="shield-check" class="w-6 h-6 text-primary-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-white mb-2">Your Security Matters</p>
                            <p class="text-xs text-gray-400 leading-relaxed">
                                Two-Factor Authentication adds an extra layer of protection to your account, ensuring only you can access your funds and information.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - 2FA Challenge Form -->
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
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-primary-500/20 mb-4">
                        <i data-lucide="shield-check" class="w-8 h-8 text-primary-400"></i>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-white">Two-Factor Authentication</h2>
                    <div x-show="!recovery">
                        <p class="text-gray-400">Please confirm access to your account by entering the authentication code from your authenticator app.</p>
                    </div>
                    <div x-show="recovery" style="display: none;">
                        <p class="text-gray-400">Please confirm access to your account by entering one of your emergency recovery codes.</p>
                    </div>
                </div>
                
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 space-y-2" role="alert">
                        <div class="flex items-center space-x-2 mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-red-400"></i>
                            <p class="text-sm font-semibold text-red-300">Please fix the following errors:</p>
                        </div>
                        <ul class="space-y-1 ml-7">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-300">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- 2FA Challenge Form -->
                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Authentication Code Field -->
                    <div x-show="!recovery" class="space-y-2">
                        <label for="code" class="block text-sm font-semibold text-gray-300">
                            Authentication Code
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="key" class="w-5 h-5 text-gray-500"></i>
                            </div>
                            <input 
                                id="code"
                                type="text" 
                                name="code" 
                                inputmode="numeric"
                                autofocus
                                x-ref="code"
                                autocomplete="one-time-code"
                                placeholder="Enter 6-digit code"
                                maxlength="6"
                                pattern="[0-9]*"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-xl font-medium focus:ring-0 transition-all tracking-widest text-center"
                            />
                        </div>
                        <p class="text-xs text-gray-500 text-center">Enter the code from your authenticator app</p>
                    </div>
                    
                    <!-- Recovery Code Field -->
                    <div x-show="recovery" style="display: none;" class="space-y-2">
                        <label for="recovery_code" class="block text-sm font-semibold text-gray-300">
                            Recovery Code
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="key-round" class="w-5 h-5 text-gray-500"></i>
                            </div>
                            <input 
                                id="recovery_code"
                                type="text" 
                                name="recovery_code" 
                                x-ref="recovery_code"
                                autocomplete="one-time-code"
                                placeholder="Enter recovery code"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                        <p class="text-xs text-gray-500 text-center">Enter one of your emergency recovery codes</p>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-white text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Log In</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <!-- Toggle Between Methods -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-700"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-slate-850 text-gray-500">or</span>
                        </div>
                    </div>
                    
                    <!-- Use Recovery Code Button -->
                    <button 
                        type="button"
                        x-show="!recovery"
                        @click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })"
                        class="w-full py-3.5 rounded-xl font-semibold text-sm text-center border border-champagne-500/30 hover:border-champagne-500/50 hover:bg-champagne-500/5 text-gray-300 hover:text-white transition-all"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <i data-lucide="key-round" class="w-4 h-4"></i>
                            <span>Use a Recovery Code</span>
                        </div>
                    </button>
                    
                    <!-- Use Authentication Code Button -->
                    <button 
                        type="button"
                        x-show="recovery"
                        style="display: none;"
                        @click="recovery = false; $nextTick(() => { $refs.code.focus() })"
                        class="w-full py-3.5 rounded-xl font-semibold text-sm text-center border border-primary-500/30 hover:border-primary-500/50 hover:bg-primary-500/5 text-gray-300 hover:text-white transition-all"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                            <span>Use an Authentication Code</span>
                        </div>
                    </button>
                </form>
                
                <!-- Footer Info -->
                <div class="pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-gray-500 text-center leading-relaxed">
                        Lost access to your authenticator? Use a recovery code or contact support for assistance.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    // Auto-format the authentication code input
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        if (codeInput) {
            codeInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        }
    });
</script>
@endsection