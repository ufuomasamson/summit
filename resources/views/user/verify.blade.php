@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ showTerms: false, acceptedTerms: false }">
    <!-- Alert Messages -->
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
                    <h2 class="text-2xl font-semibold text-[#0D1B23] mb-3">Verification In Progress</h2>
                    <p class="text-sm text-[#7A7A7A] mb-5 leading-relaxed max-w-lg mx-auto">
                        Dear <span class="text-[#0D1B23] font-medium">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</span>, 
                        Thank you for submitting your account verification. Our compliance team is currently reviewing your application 
                        to ensure the highest level of security for your banking experience.
                    </p>
                    <div class="inline-flex items-center text-xs text-[#7A7A7A] bg-[#F9F9F9] border border-[#E0E5E5] px-5 py-2.5 rounded-lg mb-5">
                        <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-[#3e4e83]"></i>
                        <span>Verification typically completes within 24-48 business hours</span>
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
                    <h2 class="text-2xl font-semibold text-[#0D1B23] mb-3">Verification Complete</h2>
                    <p class="text-sm text-[#7A7A7A] mb-6 leading-relaxed max-w-lg mx-auto">
                        Your account has been successfully verified and activated. You now have complete access to all {{ $settings->site_name }} banking services and features.
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
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6">
                <div class="flex items-start mb-5">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-11 h-11 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="hand" class="w-5 h-5 text-primary-500"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-[#0D1B23] mb-1">Welcome to {{ $settings->site_name }}!</h3>
                        <p class="text-sm text-[#7A7A7A]">
                            Dear <span class="text-[#0D1B23] font-medium">{{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }}</span>
                        </p>
                    </div>
                </div>
                
                <div class="space-y-3 text-sm text-[#7A7A7A] leading-relaxed">
                    <p>
                        Thank you for choosing {{ $settings->site_name }} as your trusted financial partner. We provide 
                        secure, innovative banking solutions designed to help you achieve your financial objectives with confidence.
                    </p>
                    <p>
                        Our comprehensive suite of banking services—including savings accounts, investment portfolios, and lending solutions—are 
                        tailored to meet your unique financial needs. Join over two million satisfied customers who trust us with their financial future.
                    </p>
                </div>
                
                <div class="mt-5 p-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg">
                    <div class="flex items-center">
                        <i data-lucide="headphones" class="w-4 h-4 text-primary-500 mr-3 flex-shrink-0"></i>
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
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-10 h-10 rounded-lg bg-[#3e4e83]/10 flex items-center justify-center">
                            <i data-lucide="info" class="w-5 h-5 text-[#3e4e83]"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-[#0D1B23] mb-1.5">Identity Verification Required</h4>
                        <p class="text-sm text-[#7A7A7A] leading-relaxed">
                            To comply with financial regulations and protect your account, please complete our secure Anti-Money Laundering (AML) 
                            and Know Your Customer (KYC) verification process. This ensures the highest level of security for your banking transactions.
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
                                <h5 class="text-sm font-medium text-[#0D1B23] mb-0.5">Enterprise-Grade Security</h5>
                                <p class="text-xs text-[#A0ACAC]">Military-grade encryption and multi-factor authentication protect your assets</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                                <i data-lucide="lock" class="w-4 h-4 text-primary-500"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-medium text-[#0D1B23] mb-0.5">Data Privacy Guarantee</h5>
                                <p class="text-xs text-[#A0ACAC]">Your personal information is never shared with third parties without explicit consent</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                                <i data-lucide="users" class="w-4 h-4 text-[#7A7A7A]"></i>
                            </div>
                            <div>
                                <h5 class="text-sm font-medium text-[#0D1B23] mb-0.5">Dedicated Client Support</h5>
                                <p class="text-xs text-[#A0ACAC]">Professional assistance available around the clock for all your banking needs</p>
                            </div>
                        </div>
                    </div>
                </div>
                        <!-- Full Terms -->
                        <div class="p-4" x-show="showTerms" x-transition>
                            <div class="bg-[#F9F9F9] rounded-lg p-4 max-h-96 overflow-y-auto" style="scrollbar-width: thin;">
                                <div class="text-xs text-[#4A4A4A] space-y-3 leading-relaxed">
                                    <h4 class="text-[#0D1B23] font-semibold text-sm mb-3">{{ $settings->site_name }} Terms of Service</h4>
                                    
                                    <p class="text-justify">
                                        By accessing and utilizing {{ $settings->site_name }} online banking services, you acknowledge and agree to be legally bound 
                                        by the terms and conditions outlined herein. Please review these terms carefully before proceeding. Your continued use of our 
                                        services constitutes acceptance of these terms in their entirety.
                                    </p>

                                    <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">1. DEFINITIONS AND TERMINOLOGY</h5>
                                    <ul class="list-disc list-inside space-y-1 ml-2">
                                        <li><strong>ACCOUNT:</strong> Refers to any banking account(s) you maintain with {{ $settings->site_name }} and access through our digital banking platform.</li>
                                        <li><strong>AUTHENTICATION CREDENTIALS:</strong> Your unique identification code, password, PIN, and any additional security measures provided by {{ $settings->site_name }}.</li>
                                        <li><strong>ONLINE BANKING SERVICES:</strong> The comprehensive suite of digital banking services accessible through {{ $settings->site_name }}'s secure online platform.</li>
                                    </ul>

                                    <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">2. SERVICE USAGE AND RESPONSIBILITIES</h5>
                                    <p class="text-justify">
                                        These terms govern your access to and use of {{ $settings->site_name }} online banking services. They establish the contractual 
                                        relationship between you as the account holder and {{ $settings->site_name }} as the service provider. We recommend reviewing these 
                                        terms thoroughly to ensure full understanding of your rights and obligations.
                                    </p>
                                    <p class="text-justify">
                                        By registering for our online banking services, you consent to receive official communications from {{ $settings->site_name }} 
                                        via electronic mail or through secure messaging within our digital banking platform.
                                    </p>
                                    <p class="text-justify">
                                        {{ $settings->site_name }} employs industry-leading security protocols to safeguard your account. For optimal security, always utilize 
                                        the secure logout feature upon completing your session and never leave your device unattended while logged into your account.
                                    </p>

                                    <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">3. SECURITY PROTOCOLS AND CONFIDENTIALITY</h5>
                                    <p class="text-justify">
                                        You are required to maintain the confidentiality of your authentication credentials, including passwords, PINs, and security codes. 
                                        Never disclose these credentials to any third party or store them in an unsecured manner. Should you suspect unauthorized access to 
                                        your credentials, immediately update them through our secure platform or contact our security team at {{ $settings->contact_email }}.
                                    </p>
                                    <p class="text-justify">
                                        {{ $settings->site_name }} reserves the right to suspend or terminate access to online banking services if we detect suspicious activity, 
                                        unauthorized access attempts, or if you fail to comply with these terms and conditions.
                                    </p>

                                    <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">4. LIABILITY AND FRAUD PROTECTION</h5>
                                    <p class="text-justify">
                                        {{ $settings->site_name }} is committed to protecting our clients from fraudulent activities. In the event you become a victim 
                                        of unauthorized online banking fraud, {{ $settings->site_name }} guarantees full reimbursement of any losses incurred, subject to 
                                        verification and investigation procedures.
                                    </p>
                                    <p class="text-justify">
                                        You acknowledge that you are fully responsible for all transactions and instructions executed using your authentication credentials. 
                                        {{ $settings->site_name }} shall not be held liable for transactions authorized through legitimate use of your credentials, unless 
                                        determined to be fraudulent through our verification process.
                                    </p>

                                    <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">5. AMENDMENTS TO TERMS</h5>
                                    <p class="text-justify">
                                        {{ $settings->site_name }} reserves the right to modify, update, or amend these terms and conditions at any time to reflect changes 
                                        in our services, regulatory requirements, or industry best practices. Any modifications will be communicated to you through written 
                                        notice via email or publication on our official website. Continued use of our services following such notification constitutes 
                                        acceptance of the revised terms.
                                    </p>

                                    <h5 class="text-[#0D1B23] font-semibold text-sm mt-4 mb-2">6. GENERAL PROVISIONS</h5>
                                    <p class="text-justify">
                                        {{ $settings->site_name }} online banking services are available exclusively to individuals who have reached the age of majority 
                                        (18 years or older) in their jurisdiction of residence. By accessing these services, you confirm that you meet this eligibility 
                                        requirement and have the legal capacity to enter into binding financial agreements.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Acceptance Checkbox -->
        <div class="mb-5">
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
                <label class="flex items-start cursor-pointer group">
                    <input 
                        type="checkbox" 
                        x-model="acceptedTerms" 
                        class="mt-1 mr-3 w-4 h-4 rounded border border-[#C8D1D1] bg-white text-primary-500 
                               focus:ring-1 focus:ring-primary-500/20 focus:ring-offset-0 cursor-pointer transition-colors"
                    >
                    <span class="text-sm text-[#7A7A7A] leading-relaxed flex-1">
                        I have read and agree to the <button @click="showTerms = true" type="button" class="text-primary-500 hover:text-primary-600 font-medium transition-colors">Terms and Conditions</button> 
                        and understand that I must complete the AML/KYC verification to activate my account.
                    </span>
                </label>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-3 mb-6">
            <!-- Complete Verification Button -->
            <a href="{{ route('kycform') }}" 
               :class="!acceptedTerms && 'opacity-50 pointer-events-none'"
               @click="if(!acceptedTerms) { $event.preventDefault(); swal('Accept Terms', 'Please accept the terms and conditions first', 'warning'); }"
               class="block bg-white border border-[#E0E5E5] rounded-xl p-5 hover:bg-[#F9F9F9] transition-colors group">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center mr-4">
                        <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Complete Identity Verification</h4>
                        <p class="text-xs text-[#A0ACAC]">Secure AML/KYC compliance required for account activation</p>
                    </div>
                    <div class="px-3 py-1.5 rounded-md bg-primary-50 border border-primary-200">
                        <span class="text-xs font-medium text-primary-500">Start Now</span>
                    </div>
                </div>
            </a>

            <!-- Get Support Button -->
            <a href="{{ route('support') }}" class="block bg-white border border-[#E0E5E5] rounded-xl p-5 hover:bg-[#F9F9F9] transition-colors group">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-4">
                        <i data-lucide="help-circle" class="w-5 h-5 text-[#7A7A7A]"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Customer Support</h4>
                        <p class="text-xs text-[#A0ACAC]">Contact our dedicated support team for assistance</p>
                    </div>
                    <i data-lucide="chevron-right" class="w-4 h-4 text-[#7A7A7A] group-hover:text-[#4A4A4A] transition-colors"></i>
                </div>
            </a>

            <!-- Decline & Sign Out Button -->
            <a href="#" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="block bg-white border border-[#E0E5E5] rounded-xl p-5 hover:bg-[#F9F9F9] transition-colors group">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-lg bg-[#D55672]/10 flex items-center justify-center mr-4">
                        <i data-lucide="log-out" class="w-5 h-5 text-[#D55672]"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-[#0D1B23] mb-0.5">Sign Out</h4>
                        <p class="text-xs text-[#A0ACAC]">Exit your session securely</p>
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
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6">
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
                    Should you require assistance with the verification process or have any questions regarding your account, our dedicated customer support team is available 24/7 to provide expert guidance.
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
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        
        // Reinitialize icons when Alpine updates DOM
        document.addEventListener('alpine:initialized', () => {
            setInterval(() => {
                lucide.createIcons();
            }, 1000);
        });
    </script>
@endsection

