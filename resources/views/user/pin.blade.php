@extends('layouts.guest2')

@section('title', 'PIN Verification')

@section('content')

<div class="w-full max-w-6xl mx-auto" 
     x-cloak
     x-data="{
         pin: '',
         isVerifying: false,
         showSuccess: false,
         showError: false,
         errorMessage: '',
         isMobile: window.innerWidth < 1024,
         showInactivityNotice: false,
         cachedReturnUrl: null,
         
         init() {
             // Check if user was redirected due to inactivity
             const returnUrl = sessionStorage.getItem('return_url');
             if (returnUrl) {
                 // Store in component and clear sessionStorage so it won't flash on reload
                 this.cachedReturnUrl = returnUrl;
                 sessionStorage.removeItem('return_url');
                 // Show notice without transition on initial load
                 this.$nextTick(() => {
                     this.showInactivityNotice = true;
                     setTimeout(() => {
                         this.showInactivityNotice = false;
                     }, 8000);
                 });
             }
         },
         
         addDigit(digit) {
             if (this.pin.length < 4) {
                 this.pin += digit;
                 this.vibrateDevice();
                 if (this.pin.length === 4) {
                     setTimeout(() => this.submitPin(), 300);
                 }
             }
         },
         
         removeDigit() {
             this.pin = this.pin.slice(0, -1);
             this.vibrateDevice();
         },
         
         vibrateDevice() {
             if (navigator.vibrate) {
                 navigator.vibrate(10);
             }
         },
         
         async submitPin() {
             if (this.pin.length !== 4) {
                 this.showErrorMessage('Please enter a 4-digit PIN');
                 return;
             }
             
             this.isVerifying = true;
             this.showError = false;
             
             try {
                 const formData = new FormData();
                 formData.append('pin', this.pin);
                 formData.append('_token', document.querySelector('meta[name=csrf-token]').content);
                 
                 // Get return URL from cached value (originally from sessionStorage)
                 if (this.cachedReturnUrl) {
                     formData.append('return_url', this.cachedReturnUrl);
                 }
                 
                 const response = await fetch('{{ route('pinstatus') }}', {
                     method: 'POST',
                     body: formData,
                     headers: {
                         'X-Requested-With': 'XMLHttpRequest'
                     }
                 });
                 
                 const data = await response.json();
                 
                 if (data.success) {
                     this.showSuccess = true;
                     
                     // Clear cached return URL
                     this.cachedReturnUrl = null;
                     
                     setTimeout(() => {
                         window.location.href = data.redirect || '{{ route('dashboard') }}';
                     }, 1000);
                 } else {
                     this.showErrorMessage(data.message || 'Invalid PIN. Please try again.');
                     this.shakeAnimation();
                     this.pin = '';
                 }
             } catch (error) {
                 this.showErrorMessage('Connection error. Please try again.');
                 this.pin = '';
             } finally {
                 this.isVerifying = false;
             }
         },
         
         showErrorMessage(message) {
             this.errorMessage = message;
             this.showError = true;
             setTimeout(() => { this.showError = false; }, 4000);
         },
         
         shakeAnimation() {
             const card = document.getElementById('pin-card');
             if (card) {
                 card.classList.add('animate-shake');
                 setTimeout(() => card.classList.remove('animate-shake'), 500);
             }
         }
     }"
     x-init="
         window.addEventListener('resize', () => {
             isMobile = window.innerWidth < 1024;
         });
     ">
    
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Security Information -->
        <div class="hidden lg:block space-y-8">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Security Content -->
            <div class="space-y-6">
                <h1 class="text-4xl font-semibold text-[#0D1B23] leading-tight">
                    Secure Access
                </h1>
                <p class="text-lg text-[#4A4A4A] leading-relaxed">
                    Two-factor authentication provides an additional layer of security for your banking operations. Enter your 4-digit PIN to continue.
                </p>
            </div>
            
            <!-- Security Features -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-[#0D1B23] uppercase tracking-wider mb-4">Why PIN Verification?</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="shield-check" class="w-5 h-5 text-[#49786B]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Enhanced Security</p>
                            <p class="text-xs text-[#7A7A7A]">Two-factor authentication protects your account from unauthorized access</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="lock-keyhole" class="w-5 h-5 text-[#7A7A7A]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Transaction Safety</p>
                            <p class="text-xs text-[#7A7A7A]">Verify your identity before accessing sensitive banking operations</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="fingerprint" class="w-5 h-5 text-[#49786B]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-1">Identity Confirmation</p>
                            <p class="text-xs text-[#7A7A7A]">Ensures only you can access your financial information</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Security Tip -->
            <div class="pt-8">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="info" class="w-5 h-5 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23] mb-2">Keep Your PIN Private</p>
                            <p class="text-xs text-[#7A7A7A] leading-relaxed">
                                Never share your PIN with anyone, including bank staff. We will never ask for your PIN via email or phone.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - PIN Verification -->
        <div class="w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-10 w-auto mx-auto">
            </div>
            
            <!-- Check if account is blocked -->
            @if (Auth::user()->status == 'blocked')
                <div class="auth-card rounded-xl p-8 md:p-10 space-y-8">
                    <div class="text-center space-y-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-[#D55672]/20 mb-4">
                            <i data-lucide="alert-octagon" class="w-10 h-10 text-[#D55672]"></i>
                        </div>
                        <div class="space-y-3">
                            <h2 class="text-2xl font-semibold text-[#0D1B23]">Account Blocked</h2>
                            <p class="text-[#7A7A7A] leading-relaxed">
                                We have blocked your {{ $settings->site_name }} account as a security protection.
                            </p>
                        </div>
                        
                        <div class="bg-[#D55672]/10 border border-[#D55672]/30 rounded-xl p-6 text-left">
                            <p class="text-sm text-[#4A4A4A] mb-4">
                                To unblock your account, please contact our support team:
                            </p>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <i data-lucide="mail" class="w-5 h-5 text-[#49786B]"></i>
                                    <a href="mailto:{{ $settings->contact_email }}" class="text-sm text-[#49786B] hover:text-[#3B645A]">
                                        {{ $settings->contact_email }}
                                    </a>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <i data-lucide="message-circle" class="w-5 h-5 text-[#7A7A7A]"></i>
                                    <span class="text-sm text-[#4A4A4A]">Live Chat Support Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- PIN Verification Card -->
                <div id="pin-card" class="auth-card rounded-xl p-8 md:p-10 space-y-8">
                    
                    <!-- User Profile -->
                    <div class="text-center space-y-4">
                        <div class="relative inline-block">
                            <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}" 
                                 alt="{{ Auth::user()->name }}"
                                 class="w-20 h-20 rounded-xl object-cover border-2 border-[#C8D1D1]"
                                 onerror="this.src='{{ asset('dash/images/avatar.svg') }}'">
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-lg bg-[#49786B] flex items-center justify-center border-2 border-white">
                                <i data-lucide="shield-check" class="w-4 h-4 text-white"></i>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-[#0D1B23]">
                                {{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }}
                            </h2>
                            <p class="text-sm text-[#7A7A7A] mt-1">Enter your 4-digit PIN to continue</p>
                        </div>
                    </div>
                    
                    <!-- Inactivity Notice -->
                    <div x-show="showInactivityNotice" 
                         x-transition
                         style="display: none;"
                         class="bg-[#3e4e83]/10 border border-[#3e4e83]/30 rounded-xl p-4">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <i data-lucide="clock" class="w-5 h-5 text-[#3e4e83]"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-[#3e4e83] font-medium mb-1">Session Timeout</p>
                                <p class="text-xs text-[#7A7A7A]">You were logged out due to inactivity. Please verify your PIN to continue where you left off.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <div x-show="showSuccess" 
                         x-transition
                         style="display: none;"
                         class="bg-[#28A745]/10 border border-[#28A745]/30 rounded-xl p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i data-lucide="check-circle" class="w-6 h-6 text-[#28A745]"></i>
                            </div>
                            <p class="text-sm text-[#28A745] font-medium">PIN verified! Redirecting...</p>
                        </div>
                    </div>
                    
                    <!-- Error Message -->
                    <div x-show="showError" 
                         x-transition
                         style="display: none;"
                         class="bg-[#D55672]/10 border border-[#D55672]/30 rounded-xl p-4">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <i data-lucide="alert-circle" class="w-6 h-6 text-[#D55672]"></i>
                            </div>
                            <p class="text-sm text-[#D55672] font-medium" x-text="errorMessage"></p>
                        </div>
                    </div>
                    
                    <!-- PIN Dots Indicator -->
                    <div class="flex justify-center space-x-4 py-6">
                        <template x-for="i in 4" :key="i">
                            <div class="w-4 h-4 rounded-full transition-all duration-200"
                                 :class="pin.length >= i ? 'bg-[#49786B] scale-110' : 'bg-[#DFE5E5] border-2 border-[#C8D1D1]'">
                            </div>
                        </template>
                    </div>
                    
                    <!-- Desktop PIN Input -->
                    <div x-show="!isMobile" x-cloak class="space-y-6">
                        <form @submit.prevent="submitPin" class="space-y-6">
                            <div class="space-y-2">
                                <label for="pin-input-desktop" class="block text-sm font-semibold text-[#4A4A4A] text-center">
                                    Enter Your PIN
                                </label>
                                <div class="relative">
                                    <input 
                                        id="pin-input-desktop"
                                        type="password" 
                                        x-model="pin"
                                        maxlength="4"
                                        inputmode="numeric"
                                        pattern="[0-9]{4}"
                                        placeholder="����"
                                        autofocus
                                        autocomplete="off"
                                        @input="pin = pin.replace(/[^0-9]/g, '')"
                                        :disabled="isVerifying"
                                        class="auth-input w-full px-4 py-4 rounded-xl text-2xl text-center tracking-[1em] font-bold"
                                    />
                                </div>
                            </div>
                            
                            <button 
                                type="submit"
                                :disabled="isVerifying || pin.length !== 4"
                                class="auth-button w-full py-4 rounded-xl font-semibold text-white text-sm flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed group"
                            >
                                <span x-show="!isVerifying">Verify PIN</span>
                                <span x-show="isVerifying" x-cloak class="flex items-center space-x-2">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span>Verifying...</span>
                                </span>
                                <i data-lucide="arrow-right" x-show="!isVerifying" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Mobile Numeric Keypad -->
                    <div x-show="isMobile" x-cloak class="space-y-6">
                        <div class="grid grid-cols-3 gap-4">
                            <template x-for="digit in [1,2,3,4,5,6,7,8,9]" :key="digit">
                                <button 
                                    type="button"
                                    @click="addDigit(digit.toString())"
                                    :disabled="isVerifying"
                                class="aspect-square rounded-xl bg-white hover:bg-[#F9F9F9] border border-[#E0E5E5] hover:border-[#49786B]/50 text-[#0D1B23] text-2xl font-semibold transition-colors active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
                                    x-text="digit">
                                </button>
                            </template>
                            
                            <!-- Empty space -->
                            <div></div>
                            
                            <!-- Zero button -->
                            <button 
                                type="button"
                                @click="addDigit('0')"
                                :disabled="isVerifying"
                                class="aspect-square rounded-xl bg-white hover:bg-[#F9F9F9] border border-[#E0E5E5] hover:border-[#49786B]/50 text-[#0D1B23] text-2xl font-semibold transition-colors active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                                0
                            </button>
                            
                            <!-- Backspace button -->
                            <button 
                                type="button"
                                @click="removeDigit()"
                                :disabled="isVerifying || pin.length === 0"
                                class="aspect-square rounded-xl bg-[#D55672]/10 hover:bg-[#D55672]/20 border border-[#D55672]/20 hover:border-[#D55672]/40 text-[#D55672] transition-colors active:scale-95 disabled:opacity-30 disabled:cursor-not-allowed flex items-center justify-center">
                                <i data-lucide="delete" class="w-6 h-6"></i>
                            </button>
                        </div>
                        
                        <!-- Loading indicator for mobile -->
                        <div x-show="isVerifying" x-cloak class="text-center py-4">
                            <div class="inline-flex items-center space-x-2 text-[#49786B]">
                                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm font-medium">Verifying PIN...</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- CSRF Token for AJAX -->
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    
                    <!-- Footer -->
                    <div class="pt-6 border-t border-[#E0E5E5]">
                        <div class="flex items-center justify-between text-xs text-[#7A7A7A]">
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="auth-link inline-flex items-center space-x-1">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                <span>Sign Out</span>
                            </a>
                            <span>Secure Connection</span>
                        </div>
                    </div>
                    
                    <!-- Logout Form -->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    
                </div>
            @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }
    
    .animate-shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
</style>
@endsection
