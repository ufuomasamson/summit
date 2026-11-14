@extends('layouts.bankapp')
@section('title', $title)
@section('content')

    <div x-data="{ showTerms: false, acceptedTerms: false }">
        <x-danger-alert />
        <x-success-alert />
        
        @if (Auth::user()->account_verify == 'Under review')
            <!-- Account Under Review -->
            <div class="mb-6">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-8">
                    <div class="text-center">
                        <div class="mb-5">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-[#3e4e83]/10 border border-[#3e4e83]/20 mb-3">
                                <i data-lucide="clock" class="w-8 h-8 text-[#3e4e83]"></i>
                            </div>
                        </div>
                        <h2 class="text-2xl font-semibold text-[#0D1B23] mb-3">Account Under Review</h2>
                        <p class="text-sm text-[#7A7A7A] mb-5 leading-relaxed max-w-lg mx-auto">
                            Hi <span class="text-[#0D1B23] font-medium">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</span>, Your {{ $settings->site_name }} 
                            internet banking account is currently being reviewed. Our team is working to verify your information.
                        </p>
                        <div class="inline-flex items-center text-xs text-[#7A7A7A] bg-[#F9F9F9] border border-[#E0E5E5] px-5 py-2.5 rounded-lg mb-5">
                            <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-[#3e4e83]"></i>
                            <span>This process typically takes 24-48 hours</span>
                        </div>
                        
                        <div class="mt-6 pt-5 border-t border-[#E0E5E5]">
                            <a href="{{ route('support') }}" class="inline-flex items-center space-x-2 px-6 py-3 rounded-lg bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] transition-colors font-medium text-sm text-[#4A4A4A]">
                                <i data-lucide="message-circle" class="w-4 h-4"></i>
                                <span>Contact Support</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif (Auth::user()->account_verify == 'Verified')
            <!-- Account Verified -->
            <div class="mb-6">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-8">
                    <div class="text-center">
                        <div class="mb-5">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-xl bg-primary-50 border border-primary-200 mb-3">
                                <i data-lucide="check-circle" class="w-8 h-8 text-primary-500"></i>
                            </div>
                        </div>
                        <h2 class="text-2xl font-semibold text-[#0D1B23] mb-3">Account Verified!</h2>
                        <p class="text-sm text-[#7A7A7A] mb-6 max-w-lg mx-auto">
                            Your account has been successfully verified. You now have full access to all banking features.
                        </p>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center space-x-2 px-6 py-3 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-medium text-sm text-white">
                            <span>Go to Dashboard</span>
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Welcome Message -->
            <div class="mb-5">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
                    <div class="flex items-start mb-4">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                                <i data-lucide="hand" class="w-5 h-5 text-primary-500"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-[#0D1B23] mb-1">Welcome to {{ $settings->site_name }}!</h3>
                            <p class="text-sm text-[#7A7A7A]">
                                Dear <span class="text-[#0D1B23] font-medium">{{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }}</span>
                            </p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 text-sm text-[#7A7A7A] leading-relaxed">
                        <p>
                            Welcome aboard! {{ $settings->site_name }} is the market's most innovative and fastest-growing 
                            company in the financial industry. We look forward to helping you achieve your banking goals.
                        </p>
                        <p>
                            We are committed to providing a wide variety of savings, investment and loan products designed 
                            to meet your specific needs. Our services are used by over two million customers worldwide.
                        </p>
                    </div>
                    
                    <div class="mt-4 p-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg">
                        <div class="flex items-center text-sm">
                            <i data-lucide="headphones" class="w-4 h-4 text-primary-500 mr-2"></i>
                            <div>
                                <p class="text-[11px] text-[#A0ACAC] mb-0.5">24/7 Customer Support</p>
                                <a href="mailto:{{ $settings->contact_email }}" class="text-sm text-primary-500 font-medium hover:text-primary-600 transition-colors">
                                    {{ $settings->contact_email }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notice -->
            <div class="mb-5">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-3">
                            <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                                <i data-lucide="info" class="w-4 h-4 text-primary-500"></i>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-[#0D1B23] mb-1">Complete Your Verification</h4>
                            <p class="text-sm text-[#7A7A7A] leading-relaxed">
                                To activate your account and access all banking features, please complete the AML/KYC verification 
                                process. This helps us ensure the security of your account.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions Section -->
            <div class="mb-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-semibold text-[#0D1B23]">Terms and Conditions</h2>
                    <button @click="showTerms = !showTerms" type="button" class="text-xs font-medium text-primary-500 hover:text-primary-600 transition-colors flex items-center space-x-1">
                        <span x-show="!showTerms">Read Full Terms</span>
                        <span x-show="showTerms">Hide Terms</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5" x-show="!showTerms"></i>
                        <i data-lucide="chevron-up" class="w-3.5 h-3.5" x-show="showTerms"></i>
                    </button>
                </div>
                
                <div class="bg-white border border-[#E0E5E5] rounded-xl overflow-hidden">
                    <!-- Terms Summary -->
                    <div class="p-5" x-show="!showTerms">
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                                    <i data-lucide="shield-check" class="w-4 h-4 text-primary-500"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-[#0D1B23] mb-0.5">Security First</h5>
                                    <p class="text-xs text-[#A0ACAC]">Your account is protected with bank-level security measures</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                                    <i data-lucide="lock" class="w-4 h-4 text-primary-500"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-[#0D1B23] mb-0.5">Privacy Protected</h5>
                                    <p class="text-xs text-[#A0ACAC]">We never share your information without consent</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                                    <i data-lucide="users" class="w-4 h-4 text-[#7A7A7A]"></i>
                                </div>
                                <div>
                                    <h5 class="text-sm font-medium text-[#0D1B23] mb-0.5">24/7 Support</h5>
                                    <p class="text-xs text-[#A0ACAC]">Our team is always here to help you</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Full Terms -->
                    <div class="p-4" x-show="showTerms" x-transition>
                        <div class="bg-[#F9F9F9] rounded-lg p-4 max-h-96 overflow-y-auto" style="scrollbar-width: thin;">
                            <div class="text-xs text-[#4A4A4A] space-y-3 leading-relaxed">
                                <h4 class="text-[#0D1B23] font-semibold text-sm mb-3">{{ $settings->site_name }} Terms and Conditions</h4>
                                
                                <p class="text-justify">
                                    Before you can start using our online service you must agree to be bound by the conditions below. 
                                    You must read the conditions before you decide whether to accept them. If you agree to be bound by 
                                    these conditions, please click the "I Accept" button below.
                                </p>

                                <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">1. DEFINITIONS</h5>
                                <ul class="list-disc list-inside space-y-1 ml-2">
                                    <li><strong>ACCOUNT:</strong> Any account which you hold and access via our online service.</li>
                                    <li><strong>IDENTITY DETAILS:</strong> The access code we may provide you with.</li>
                                    <li><strong>{{ strtoupper($settings->site_name) }} ACCOUNT:</strong> Your unique account number, password and PIN.</li>
                                </ul>

                                <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">2. USING THE ONLINE SERVICE</h5>
                                <p class="text-justify">
                                    These conditions apply to your use of our online service and in relation to any accounts. They explain 
                                    the relationship between you and us in relation to our online service. You should read these conditions 
                                    carefully to understand how these services work.
                                </p>
                                <p class="text-justify">
                                    By registering to use our online service, you accept these conditions and agree that we may communicate 
                                    with you by e-mail or through our website.
                                </p>
                                <p class="text-justify">
                                    Our online sites are secure. You must always use the log off facility when you are finished and never 
                                    leave your machine unattended while you are logged in.
                                </p>

                                <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">3. SECURITY RULES</h5>
                                <p class="text-justify">
                                    You must not write down, store, or let anyone else know your password, identity details or additional 
                                    security details. If you think someone else knows your password, you must change it online immediately 
                                    or contact us at +1 234 567 8910.
                                </p>
                                <p class="text-justify">
                                    We may stop you using our online service if we think someone else is trying to use it, or if you're 
                                    not keeping to these conditions.
                                </p>

                                <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">4. LIABILITY</h5>
                                <p class="text-justify">
                                    If you are a victim of online fraud, we guarantee that you won't lose any money on your accounts and 
                                    will always be reimbursed in full.
                                </p>
                                <p class="text-justify">
                                    Unless you are a victim of fraud, you are responsible for all instructions sent using your username, 
                                    password or additional security details.
                                </p>

                                <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">5. CHANGES TO CONDITIONS</h5>
                                <p class="text-justify">
                                    {{ $settings->site_name }} may change these conditions for any reason by giving you written notice or 
                                    publishing the change on our website.
                                </p>

                                <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">6. GENERAL</h5>
                                <p class="text-justify">
                                    {{ $settings->site_name }} service is available to you if you are 18 years of age or over.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acceptance Checkbox -->
            <div class="mb-5">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
                    <label class="flex items-start cursor-pointer">
                        <input 
                            type="checkbox" 
                            x-model="acceptedTerms" 
                            class="mt-1 mr-3 w-4 h-4 rounded border border-[#C8D1D1] bg-white text-primary-500 
                                   focus:ring-1 focus:ring-primary-500/20 focus:ring-offset-0 cursor-pointer transition-colors"
                        >
                        <span class="text-sm text-[#7A7A7A] leading-relaxed">
                            I have read and agree to the <button @click="showTerms = true" type="button" class="text-primary-500 hover:text-primary-600 font-medium transition-colors">Terms and Conditions</button> 
                            and understand that I must complete the AML/KYC verification to activate my account.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3 mb-6">
                <!-- Complete Verification -->
                <a href="{{ route('kycform') }}" 
                   :class="!acceptedTerms && 'opacity-50 pointer-events-none'"
                   @click="if(!acceptedTerms) { $event.preventDefault(); alert('Please accept the terms and conditions first'); }"
                   class="block bg-white border border-[#E0E5E5] rounded-xl p-5 hover:bg-[#F9F9F9] transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center mr-4">
                            <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Complete AML/KYC Verification</h4>
                            <p class="text-xs text-[#A0ACAC]">Required to activate your account</p>
                        </div>
                        <div class="px-3 py-1.5 rounded-md bg-primary-50 border border-primary-200">
                            <span class="text-xs font-medium text-primary-500">Start Now</span>
                        </div>
                    </div>
                </a>

                <!-- Get Support -->
                <a href="{{ route('support') }}" class="block bg-white border border-[#E0E5E5] rounded-xl p-5 hover:bg-[#F9F9F9] transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-4">
                            <i data-lucide="help-circle" class="w-5 h-5 text-[#7A7A7A]"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Get Support</h4>
                            <p class="text-xs text-[#A0ACAC]">Ask questions or report issues</p>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#7A7A7A] group-hover:text-[#4A4A4A] transition-colors"></i>
                    </div>
                </a>

                <!-- Sign Out -->
                <a href="#" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="block bg-white border border-[#E0E5E5] rounded-xl p-5 hover:bg-[#F9F9F9] transition-colors group">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-lg bg-[#D55672]/10 flex items-center justify-center mr-4">
                            <i data-lucide="log-out" class="w-5 h-5 text-[#D55672]"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Decline & Sign Out</h4>
                            <p class="text-xs text-[#A0ACAC]">Exit without completing verification</p>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#7A7A7A] group-hover:text-[#D55672] transition-colors"></i>
                    </div>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>

            <!-- Help Section -->
            <div class="mb-6">
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                            <i data-lucide="message-square" class="w-5 h-5 text-[#7A7A7A]"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Need Help?</h4>
                            <p class="text-xs text-[#A0ACAC]">We're here to assist you</p>
                        </div>
                    </div>
                    <p class="text-sm text-[#7A7A7A] mb-5 leading-relaxed">
                        Have questions or need assistance with the verification process? Our support team is available 24/7 to help you.
                    </p>
                    <a href="{{ route('support') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] transition-colors font-medium text-sm text-[#4A4A4A]">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <span>Contact Support Team</span>
                    </a>
                </div>
            </div>
        @endif
    </div>

@endsection

@section('scripts')
    @parent
    <script>
        // Initialize Lucide icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>
@endsection

