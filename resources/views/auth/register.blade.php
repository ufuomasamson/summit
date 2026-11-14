@extends('layouts.guest2')

@section('title', 'Create an Account')

@section('head')
    <!-- intl-tel-input CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
@endsection

@section('content')

<div class="w-full max-w-7xl mx-auto">
    <div class="grid lg:grid-cols-5 gap-8 items-start">
        
        <!-- Left Side - Branding (Narrower) -->
        <div class="hidden lg:block lg:col-span-2 space-y-8 sticky top-8">
            <!-- Logo -->
            <div>
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                         alt="{{ $settings->site_name }}" 
                         class="h-12 w-auto mb-8">
                </a>
            </div>
            
            <!-- Welcome Content -->
            <div class="space-y-6">
                <h1 class="text-3xl font-semibold text-[#0D1B23] leading-tight">
                    Start Your 
                    <span class="text-primary-500">
                        Banking Journey
                    </span>
                </h1>
                <p class="text-base text-[#4A4A4A] leading-relaxed">
                    Join thousands of satisfied customers who trust {{ $settings->site_name }} for secure, swift money transfers worldwide.
                </p>
            </div>
            
            <!-- Benefits List -->
            <div class="space-y-4">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-white flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-4 h-4 text-primary-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23]">Bank-Level Security</p>
                        <p class="text-xs text-[#7A7A7A]">256-bit encryption protects your data</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-white flex items-center justify-center mt-0.5">
                        <i data-lucide="zap" class="w-4 h-4 text-[#7A7A7A]"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23]">Instant Transfers</p>
                        <p class="text-xs text-[#7A7A7A]">Send money in seconds, not days</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-white flex items-center justify-center mt-0.5">
                        <i data-lucide="shield-check" class="w-4 h-4 text-primary-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23]">Fraud Protection</p>
                        <p class="text-xs text-[#7A7A7A]">24/7 monitoring keeps you safe</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-white flex items-center justify-center mt-0.5">
                        <i data-lucide="headphones" class="w-4 h-4 text-[#7A7A7A]"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23]">Expert Support</p>
                        <p class="text-xs text-[#7A7A7A]">Get help whenever you need it</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Registration Form (Wider) -->
        <div class="w-full lg:col-span-3" x-data="{ showPassword: false, showPasswordConfirm: false }">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <a href="{{ route('home') }}" class="inline-block">
                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}" 
                         alt="{{ $settings->site_name }}" 
                         class="h-10 w-auto mx-auto">
                </a>
            </div>
            
            <!-- Auth Card -->
            <div class="auth-card rounded-xl p-6 md:p-8 space-y-6">
                
                <!-- Header -->
                <div class="space-y-2">
                    <a href="{{ route('home') }}" class="auth-link inline-flex items-center space-x-1 text-sm mb-1">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Back to home</span>
                    </a>
                    <h2 class="text-2xl font-semibold text-[#0D1B23]">Create Account</h2>
                    <p class="text-sm text-[#7A7A7A]">Please enter your information to build your account.</p>
                </div>
                
                <!-- Alert Message -->
                @if (Session::has('status'))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ session('status') }}</p>
                    </div>
                @endif
                
                <!-- Registration Form -->
                <form action="{{ route('register') }}" method="post" class="space-y-5">
                    @csrf
                    
                    <!-- Name Fields Row -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="name" class="block text-xs font-semibold text-[#4A4A4A]">
                                Legal First Name <span class="text-[#D55672]">*</span>
                            </label>
                            <input 
                                id="name"
                                type="text" 
                                name="name" 
                                value="{{ old('name') }}"
                                required 
                                placeholder="John"
                                class="auth-input w-full px-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                            @if ($errors->has('name'))
                                <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        
                        <div class="space-y-2">
                            <label for="middlename" class="block text-xs font-semibold text-[#4A4A4A]">
                                Middle Name <span class="text-[#D55672]">*</span>
                            </label>
                            <input 
                                id="middlename"
                                type="text" 
                                name="middlename" 
                                required 
                                placeholder="Michael"
                                class="auth-input w-full px-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                    </div>
                    
                    <!-- Last Name & Username Row -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="lastname" class="block text-xs font-semibold text-[#4A4A4A]">
                                Legal Last Name <span class="text-[#D55672]">*</span>
                            </label>
                            <input 
                                id="lastname"
                                type="text" 
                                name="lastname" 
                                value="{{ old('lastname') }}"
                                required 
                                placeholder="Doe"
                                class="auth-input w-full px-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                        
                        <div class="space-y-2">
                            <label for="username" class="block text-xs font-semibold text-[#4A4A4A]">
                                Username <span class="text-[#D55672]">*</span>
                            </label>
                            <input 
                                id="username"
                                type="text" 
                                name="username" 
                                required 
                                placeholder="johndoe"
                                class="auth-input w-full px-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                            @if ($errors->has('username'))
                                <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('username') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-xs font-semibold text-[#4A4A4A]">
                            Email Address <span class="text-[#D55672]">*</span>
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
                                placeholder="john.doe@email.com"
                                class="auth-input w-full pl-12 pr-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                        @if ($errors->has('email'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('email') }}</p>
                        @endif
                    </div>
                    
                    <!-- Phone Field -->
                    <div class="space-y-2">
                        <label for="phone" class="block text-xs font-semibold text-[#4A4A4A]">
                            Phone Number <span class="text-[#D55672]">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                id="phone"
                                type="tel" 
                                name="phone" 
                                required 
                                placeholder="+1 234 567 8900"
                                class="auth-input w-full px-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                        <p id="phone-error" class="text-xs text-[#D55672] mt-1 hidden"></p>
                        <p class="text-xs text-[#7A7A7A]">Enter your phone number with country code</p>
                        @if ($errors->has('phone'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('phone') }}</p>
                        @endif
                    </div>
                    
                    <!-- Country Field -->
                    <div class="space-y-2">
                        <label for="country" class="block text-xs font-semibold text-[#4A4A4A]">
                            Country <span class="text-[#D55672]">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none z-10">
                                <i data-lucide="map-pin" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <select 
                                id="country"
                                name="country" 
                                required
                                class="auth-input w-full pl-12 pr-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all appearance-none cursor-pointer"
                            >
                                <option value="" selected disabled>Choose Country</option>
                                @include('auth.countries')
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                <i data-lucide="chevron-down" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                        </div>
                        @if ($errors->has('country'))
                            <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('country') }}</p>
                        @endif
                    </div>
                    
                    <!-- Account Type & Currency Row -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="accounttype" class="block text-xs font-semibold text-[#4A4A4A]">
                                Account Type <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <select 
                                    id="accounttype"
                                    name="accounttype" 
                                    required
                                    class="auth-input w-full px-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all appearance-none cursor-pointer"
                                >
                                    <option value="">Select Account Type</option>
                                    <option value="Checking Account">Checking Account</option>
                                    <option value="Savings Account">Savings Account</option>
                                    <option value="Fixed Deposit Account">Fixed Deposit</option>
                                    <option value="Current Account">Current Account</option>
                                    <option value="Money Market Account">Money Market Account</option>
                                    <option value="Certificate of Deposit Account">Certificate of Deposit (CD)</option>
                                    <option value="Crypto Currency Account">Crypto Currency</option>
                                    <option value="Business Account">Business Account</option>
                                    <option value="Corporate Business Account">Corporate Business</option>
                                    <option value="Non Resident Account">Non Resident</option>
                                    <option value="Investment Account">Investment Account</option>
                                    <option value="Retirement Account">Retirement Account (IRA)</option>
                                    <option value="Joint Account">Joint Account</option>
                                    <option value="Trust Account">Trust Account</option>
                                    <option value="Student Account">Student Account</option>
                                    <option value="Senior Citizen Account">Senior Citizen Account</option>
                                    <option value="Premium Account">Premium/VIP Account</option>
                                    <option value="Foreign Currency Account">Foreign Currency Account</option>
                                    <option value="Escrow Account">Escrow Account</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-[#7A7A7A]"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="currency" class="block text-xs font-semibold text-[#4A4A4A]">
                                Currency <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <select 
                                    id="currency"
                                    name="currency" 
                                    required
                                    class="auth-input w-full px-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all appearance-none cursor-pointer"
                                >
                                    <option value="">Choose Currency</option>
                                    <option value="$" selected>United States Dollars (USD)</option>
                                    <option value="€">Euro (EUR)</option>
                                    <option value="£">British Pounds (GBP)</option>
                                    <option value="¥">Japanese Yen (JPY)</option>
                                    <option value="¥">Chinese Yuan (CNY)</option>
                                    <option value="C$">Canadian Dollar (CAD)</option>
                                    <option value="A$">Australian Dollar (AUD)</option>
                                    <option value="CHF">Swiss Franc (CHF)</option>
                                    <option value="₹">Indian Rupee (INR)</option>
                                    <option value="R$">Brazilian Real (BRL)</option>
                                    <option value="MX$">Mexican Peso (MXN)</option>
                                    <option value="R">South African Rand (ZAR)</option>
                                    <option value="S$">Singapore Dollar (SGD)</option>
                                    <option value="HK$">Hong Kong Dollar (HKD)</option>
                                    <option value="kr">Swedish Krona (SEK)</option>
                                    <option value="kr">Norwegian Krone (NOK)</option>
                                    <option value="kr">Danish Krone (DKK)</option>
                                    <option value="NZ$">New Zealand Dollar (NZD)</option>
                                    <option value="₩">South Korean Won (KRW)</option>
                                    <option value="₺">Turkish Lira (TRY)</option>
                                    <option value="₽">Russian Ruble (RUB)</option>
                                    <option value="د.إ">UAE Dirham (AED)</option>
                                    <option value="﷼">Saudi Riyal (SAR)</option>
                                    <option value="zł">Polish Zloty (PLN)</option>
                                    <option value="฿">Thai Baht (THB)</option>
                                    <option value="Rp">Indonesian Rupiah (IDR)</option>
                                    <option value="RM">Malaysian Ringgit (MYR)</option>
                                    <option value="₱">Philippine Peso (PHP)</option>
                                    {{-- <option value="₦">Nigerian Naira (NGN)</option> --}}
                                    <option value="KSh">Kenyan Shilling (KES)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-5 h-5 text-[#7A7A7A]"></i>
                                </div>
                            </div>
                            @if ($errors->has('currency'))
                                <p class="text-xs text-[#D55672] mt-1">{{ $errors->first('currency') }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Transaction PIN -->
                    <div class="space-y-2">
                        <label for="pin" class="block text-xs font-semibold text-[#4A4A4A]">
                            4-Digit Transaction PIN <span class="text-[#D55672]">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="hash" class="w-5 h-5 text-[#7A7A7A]"></i>
                            </div>
                            <input 
                                id="pin"
                                type="password" 
                                name="pin" 
                                required 
                                maxlength="4"
                                pattern="[0-9]{4}"
                                placeholder="••••"
                                class="auth-input w-full pl-12 pr-4 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                        <p class="text-xs text-[#7A7A7A]">Used to authorize transactions</p>
                    </div>
                    
                    <!-- Password Fields -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="password" class="block text-xs font-semibold text-[#4A4A4A]">
                                Password <span class="text-[#D55672]">*</span>
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
                                    class="auth-input w-full pl-12 pr-12 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
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
                        
                        <div class="space-y-2">
                            <label for="password_confirmation" class="block text-xs font-semibold text-[#4A4A4A]">
                                Confirm Password <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i data-lucide="lock" class="w-5 h-5 text-[#7A7A7A]"></i>
                                </div>
                                <input 
                                    id="password_confirmation"
                                    :type="showPasswordConfirm ? 'text' : 'password'" 
                                    name="password_confirmation" 
                                    value="{{ old('password_confirmation') }}"
                                    required 
                                    placeholder="••••••••••"
                                    class="auth-input w-full pl-12 pr-12 py-3 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                                />
                                <button 
                                    type="button"
                                    @click="showPasswordConfirm = !showPasswordConfirm"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors"
                                >
                                    <i :data-lucide="showPasswordConfirm ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms Checkbox -->
                    <div class="flex items-start space-x-3">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            name="terms"
                            required
                            class="mt-1 w-4 h-4 rounded border-[#C8D1D1] bg-white text-primary-500 focus:ring-primary-500 focus:ring-offset-0 focus:ring-2 cursor-pointer"
                        />
                        <label for="terms" class="text-sm text-[#7A7A7A] cursor-pointer select-none">
                            I agree to {{ $settings->site_name }}'s 
                            <a href="{{ route('terms') }}" class="auth-link">Terms & Conditions</a> and 
                            <a href="{{ route('privacy') }}" class="auth-link">Privacy Policy</a>
                        </label>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-white text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Create My Account</span>
                        <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                    
                    <!-- Login Link -->
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-[#E0E5E5]"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-[#7A7A7A]">Already have an account?</span>
                        </div>
                    </div>
                    
                    <a 
                        href="{{ route('login') }}" 
                        class="block w-full py-3.5 rounded-xl font-semibold text-sm text-center border border-[#E0E5E5] hover:border-primary-500 hover:bg-primary-50 text-[#0D1B23] hover:text-primary-600 transition-all"
                    >
                        <div class="flex items-center justify-center space-x-2">
                            <i data-lucide="log-in" class="w-4 h-4"></i>
                            <span>Sign In</span>
                        </div>
                    </a>
                </form>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    @parent
    <!-- intl-tel-input JS -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
    
    <script>
        $('#username').on('keypress', function(e) {
            return e.which !== 32;
        });
        
        // Initialize phone input with validation
        const phoneInput = document.querySelector("#phone");
        const phoneError = document.querySelector("#phone-error");
        const form = phoneInput.closest('form');
        
        const iti = window.intlTelInput(phoneInput, {
            initialCountry: "us",
            preferredCountries: ["us", "gb", "ng", "ca", "au"],
            separateDialCode: true,
            utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js",
            autoPlaceholder: "aggressive",
        });
        
        // Validate on blur
        phoneInput.addEventListener('blur', function() {
            validatePhone();
        });
        
        // Validate on country change
        phoneInput.addEventListener('countrychange', function() {
            validatePhone();
        });
        
        function validatePhone() {
            phoneError.classList.add('hidden');
            
            if (phoneInput.value.trim()) {
                if (iti.isValidNumber()) {
                    phoneInput.classList.remove('border-red-500');
                    phoneInput.classList.add('border-green-500');
                    phoneError.classList.add('hidden');
                    return true;
                } else {
                    phoneInput.classList.remove('border-green-500');
                    phoneInput.classList.add('border-red-500');
                    
                    const errorCode = iti.getValidationError();
                    const errorMessages = {
                        0: 'Invalid phone number',
                        1: 'Invalid country code',
                        2: 'Phone number too short',
                        3: 'Phone number too long',
                        4: 'Invalid phone number',
                    };
                    
                    phoneError.textContent = errorMessages[errorCode] || 'Invalid phone number format';
                    phoneError.classList.remove('hidden');
                    return false;
                }
            }
            return true;
        }
        
        // On form submit, validate and set full international number
        form.addEventListener('submit', function(e) {
            if (phoneInput.value.trim()) {
                if (!iti.isValidNumber()) {
                    e.preventDefault();
                    validatePhone();
                    phoneInput.focus();
                    return false;
                }
                
                // Set the full international number in E.164 format (+1234567890)
                phoneInput.value = iti.getNumber();
            }
        });
    </script>
@endsection
