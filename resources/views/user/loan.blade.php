@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ 
    showApplicationForm: false,
    isSubmitting: false,
    hasActiveLoan: {{ $user_plans->where('active', '!=', 'No')->count() > 0 ? 'true' : 'false' }},
    loanAmount: '',
    calculateMonthly() {
        const amount = parseFloat(this.loanAmount) || 0;
        const duration = parseInt(document.querySelector('[name=duration]').value) || 12;
        const rate = 0.05; // 5% annual rate
        const monthly = (amount * (1 + (rate * duration / 12))) / duration;
        return monthly.toFixed(2);
    }
}" class="space-y-6">

    <!-- Alerts -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fade-in">
        <div>
            <h1 class="text-2xl md:text-3xl font-display font-bold mb-2">
                Loan Services
            </h1>
            <p class="text-[#7A7A7A]">Access flexible financing solutions tailored to your needs</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard') }}" class="glass-effect px-4 py-2.5 rounded-xl hover:bg-primary-50 transition-all text-sm font-semibold flex items-center space-x-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('veiwloan') }}" class="gradient-primary text-white px-4 py-2.5 rounded-xl hover:shadow-medium transition-all text-sm font-semibold flex items-center space-x-2">
                <i data-lucide="history" class="w-4 h-4"></i>
                <span>Loan History</span>
            </a>
        </div>
    </div>

    <!-- Active Loan Warning -->
    <div x-show="hasActiveLoan" class="glass-effect rounded-2xl p-6 border-2 border-champagne-300 animate-slide-up">
        <div class="flex items-start space-x-4">
            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-champagne-100 flex items-center justify-center">
                <i data-lucide="alert-circle" class="w-6 h-6 text-champagne-600"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-display font-bold text-champagne-600 mb-2">Active Loan in Progress</h3>
                <p class="text-[#4A4A4A] text-sm mb-3">You currently have an active loan application. Our policy allows one active loan per customer to ensure responsible lending.</p>
                <a href="{{ route('veiwloan') }}" class="inline-flex items-center space-x-2 text-sm font-semibold text-champagne-600 hover:text-champagne-500 transition-colors">
                    <span>View your active loans</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Information Section -->
    <div x-show="!showApplicationForm" x-transition class="space-y-6">
        
        <!-- Loan Benefits -->
        <div class="grid md:grid-cols-4 gap-4 animate-slide-up">
            <div class="glass-effect rounded-2xl p-6 hover:bg-primary-50 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="zap" class="w-6 h-6 text-primary-500"></i>
                </div>
                <h3 class="font-display font-bold text-slate-900 mb-2">Quick Approval</h3>
                <p class="text-sm text-[#7A7A7A]">Fast processing with decisions in 24-48 hours</p>
            </div>
            <div class="glass-effect rounded-2xl p-6 hover:bg-champagne-50 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-champagne-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="percent" class="w-6 h-6 text-champagne-600"></i>
                </div>
                <h3 class="font-display font-bold text-slate-900 mb-2">Competitive Rates</h3>
                <p class="text-sm text-[#7A7A7A]">Flexible terms starting from 5% annually</p>
            </div>
            <div class="glass-effect rounded-2xl p-6 hover:bg-primary-50 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="file-check" class="w-6 h-6 text-primary-500"></i>
                </div>
                <h3 class="font-display font-bold text-slate-900 mb-2">Simple Process</h3>
                <p class="text-sm text-[#7A7A7A]">Minimal documentation with online application</p>
            </div>
            <div class="glass-effect rounded-2xl p-6 hover:bg-champagne-50 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-champagne-100 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="shield-check" class="w-6 h-6 text-champagne-600"></i>
                </div>
                <h3 class="font-display font-bold text-slate-900 mb-2">Secure</h3>
                <p class="text-sm text-[#7A7A7A]">Bank-grade security for your information</p>
            </div>
        </div>

        <!-- Available Loan Types -->
        <div class="glass-effect rounded-2xl p-6 md:p-8">
            <h2 class="text-xl font-display font-bold text-slate-900 mb-6">Available Loan Types</h2>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="border border-primary-200 rounded-xl p-4 hover:border-primary-400 hover:bg-primary-50 transition-all cursor-pointer">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="home" class="w-5 h-5 text-primary-500 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-slate-900 mb-1">Personal Home Loans</h3>
                            <p class="text-xs text-[#7A7A7A]">Finance your dream home with flexible terms</p>
                        </div>
                    </div>
                </div>
                <div class="border border-champagne-200 rounded-xl p-4 hover:border-champagne-400 hover:bg-champagne-50 transition-all cursor-pointer">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="car" class="w-5 h-5 text-champagne-600 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-slate-900 mb-1">Automobile Loans</h3>
                            <p class="text-xs text-[#7A7A7A]">Drive your desired vehicle today</p>
                        </div>
                    </div>
                </div>
                <div class="border border-primary-200 rounded-xl p-4 hover:border-primary-400 hover:bg-primary-50 transition-all cursor-pointer">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="briefcase" class="w-5 h-5 text-primary-500 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-slate-900 mb-1">Business Loans</h3>
                            <p class="text-xs text-[#7A7A7A]">Grow your enterprise with capital</p>
                        </div>
                    </div>
                </div>
                <div class="border border-champagne-200 rounded-xl p-4 hover:border-champagne-400 hover:bg-champagne-50 transition-all cursor-pointer">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="users" class="w-5 h-5 text-champagne-600 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-slate-900 mb-1">Joint Mortgage</h3>
                            <p class="text-xs text-[#7A7A7A]">Co-ownership property financing</p>
                        </div>
                    </div>
                </div>
                <div class="border border-primary-200 rounded-xl p-4 hover:border-primary-400 hover:bg-primary-50 transition-all cursor-pointer">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="shield" class="w-5 h-5 text-primary-500 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-slate-900 mb-1">Secured Overdraft</h3>
                            <p class="text-xs text-[#7A7A7A]">Flexible credit line with collateral</p>
                        </div>
                    </div>
                </div>
                <div class="border border-champagne-200 rounded-xl p-4 hover:border-champagne-400 hover:bg-champagne-50 transition-all cursor-pointer">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="heart-pulse" class="w-5 h-5 text-champagne-600 mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-slate-900 mb-1">Health Finance</h3>
                            <p class="text-xs text-[#7A7A7A]">Medical expenses covered</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="glass-effect rounded-2xl p-6 md:p-8">
            <h2 class="text-xl font-display font-bold text-slate-900 mb-6">How It Works</h2>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="relative">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center font-display font-bold text-sm shadow-medium">1</div>
                        <div>
                            <h3 class="font-display font-bold text-slate-900 mb-2">Apply Online</h3>
                            <p class="text-sm text-[#7A7A7A]">Complete our simple application form with your loan requirements and financial details</p>
                        </div>
                    </div>
                    <div class="hidden md:block absolute top-5 left-5 w-full h-0.5 bg-gradient-to-r from-primary-300 to-transparent"></div>
                </div>
                <div class="relative">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-champagne-500 to-champagne-700 flex items-center justify-center font-display font-bold text-sm shadow-medium">2</div>
                        <div>
                            <h3 class="font-display font-bold text-slate-900 mb-2">Quick Review</h3>
                            <p class="text-sm text-[#7A7A7A]">Our team reviews your application and verifies your information within 24-48 hours</p>
                        </div>
                    </div>
                    <div class="hidden md:block absolute top-5 left-5 w-full h-0.5 bg-gradient-to-r from-champagne-300 to-transparent"></div>
                </div>
                <div>
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center font-display font-bold text-sm shadow-medium">3</div>
                        <div>
                            <h3 class="font-display font-bold text-slate-900 mb-2">Get Funded</h3>
                            <p class="text-sm text-[#7A7A7A]">Upon approval, funds are disbursed directly to your account quickly and securely</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="glass-effect rounded-2xl p-8 text-center">
            <h2 class="text-2xl font-display font-bold text-slate-900 mb-3">Ready to Get Started?</h2>
            <p class="text-[#7A7A7A] mb-6">Apply now and get a response within 24-48 hours</p>
            <button 
                @click="showApplicationForm = true" 
                :disabled="hasActiveLoan"
                :class="hasActiveLoan ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-medium hover:-translate-y-0.5'"
                class="gradient-primary text-white px-8 py-4 rounded-xl transition-all text-base font-display font-bold inline-flex items-center space-x-3">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span>Apply for a Loan</span>
                <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    <!-- Application Form Section -->
    <div x-show="showApplicationForm && !hasActiveLoan" x-transition class="space-y-6">
        
        <!-- Form Header -->
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-display font-bold text-slate-900 mb-1">Loan Application Form</h2>
                <p class="text-sm text-[#7A7A7A]">Complete all fields to submit your application</p>
            </div>
            <button @click="showApplicationForm = false" class="glass-effect px-4 py-2 rounded-xl hover:bg-primary-50 transition-all text-sm font-semibold flex items-center space-x-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                <span>Back to Information</span>
            </button>
        </div>

        <!-- Application Form -->
        <form action="{{ route('loan') }}" method="POST" class="glass-effect rounded-2xl p-6 md:p-8 space-y-6" @submit="isSubmitting = true" x-ref="loanForm">
            @csrf

            <!-- Loan Amount -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Loan Amount <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <span class="text-slate-400 font-semibold">{{ Auth::user()->currency }}</span>
                    </div>
                    <input 
                        type="number" 
                        name="amount" 
                        x-model="loanAmount"
                        required
                        step="0.01"
                        min="100"
                        placeholder="Enter loan amount"
                        class="w-full pl-16 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all">
                </div>
                <p class="mt-2 text-xs text-slate-400">Minimum loan amount: {{ Auth::user()->currency }}100</p>
            </div>

            <!-- Credit Facility -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Credit Facility Type <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <select 
                        name="facility" 
                        required
                        class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all appearance-none">
                        <option value="" disabled selected>Select loan type</option>
                        <option value="Personal Home Loans">Personal Home Loans</option>
                        <option value="Joint Mortgage">Joint Mortgage</option>
                        <option value="Automobile Loans">Automobile Loans</option>
                        <option value="Salary loans">Salary Loans</option>
                        <option value="Secured Overdraft">Secured Overdraft</option>
                        <option value="Contract Finance">Contract Finance</option>
                        <option value="Secured Term Loans">Secured Term Loans</option>
                        <option value="StartUp/Products Financing">StartUp/Products Financing</option>
                        <option value="Local Purchase Orders Finance">Local Purchase Orders Finance</option>
                        <option value="Operational Vehicles">Operational Vehicles</option>
                        <option value="Revenue Loans and Overdraft">Revenue Loans and Overdraft</option>
                        <option value="Retail TOD">Retail TOD</option>
                        <option value="Commercial Mortgage">Commercial Mortgage</option>
                        <option value="Office Equipment">Office Equipment</option>
                        <option value="Health Finance Product Guideline">Health Finance Product Guideline</option>
                        <option value="Health Finance">Health Finance</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Loan Duration <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <select 
                        name="duration" 
                        required
                        class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all appearance-none">
                        <option value="6">6 Months</option>
                        <option value="12" selected>12 Months (1 Year)</option>
                        <option value="24">24 Months (2 Years)</option>
                        <option value="36">36 Months (3 Years)</option>
                        <option value="48">48 Months (4 Years)</option>
                        <option value="60">60 Months (5 Years)</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Monthly Income -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Monthly Net Income <span class="text-danger">*</span>
                </label>
                <div class="relative">
                    <select 
                        name="income" 
                        required
                        class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all appearance-none">
                        <option value="" disabled selected>Select your income range</option>
                        <option value="2,000-5,000">{{ Auth::user()->currency }}2,000 - {{ Auth::user()->currency }}5,000</option>
                        <option value="6,000-10,000">{{ Auth::user()->currency }}6,000 - {{ Auth::user()->currency }}10,000</option>
                        <option value="11,000-20,000">{{ Auth::user()->currency }}11,000 - {{ Auth::user()->currency }}20,000</option>
                        <option value="21,000-50,000">{{ Auth::user()->currency }}21,000 - {{ Auth::user()->currency }}50,000</option>
                        <option value="100,000 and above">{{ Auth::user()->currency }}100,000 and above</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i data-lucide="chevron-down" class="w-5 h-5 text-slate-400"></i>
                    </div>
                </div>
            </div>

            <!-- Purpose -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">
                    Loan Purpose / Narration <span class="text-danger">*</span>
                </label>
                <textarea 
                    name="purpose" 
                    required
                    rows="5"
                    placeholder="Please describe the purpose of this loan in detail..."
                    class="w-full px-4 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all resize-none"></textarea>
                <p class="mt-2 text-xs text-slate-400">Provide clear details about how you intend to use the loan funds</p>
            </div>

            <!-- Terms Agreement -->
            <div class="border border-champagne-200 rounded-xl p-6 bg-champagne-50">
                <label class="flex items-start space-x-3 cursor-pointer group">
                    <input 
                        type="checkbox" 
                        required
                        class="mt-1 w-5 h-5 rounded border-slate-300 bg-white text-primary-500 focus:ring-2 focus:ring-primary-200">
                    <span class="text-sm text-[#4A4A4A] group-hover:text-slate-900 transition-colors">
                        I agree to the <a href="#" class="text-champagne-600 hover:text-champagne-500 font-semibold">terms and conditions</a> and authorize {{ $settings->site_name }} to process my loan application. I understand that providing false information may result in application rejection.
                    </span>
                </label>
            </div>

            <!-- Estimated Monthly Payment Info -->
            <div x-show="loanAmount > 0" class="border border-primary-200 rounded-xl p-6 bg-primary-50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-[#7A7A7A] mb-1">Estimated Monthly Payment</p>
                        <p class="text-2xl font-display font-bold text-primary-600" x-text="'{{ Auth::user()->currency }}' + calculateMonthly()"></p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center">
                        <i data-lucide="calculator" class="w-6 h-6 text-primary-500"></i>
                    </div>
                </div>
                <p class="text-xs text-slate-400 mt-3">*This is an estimate. Actual amount may vary based on final approval terms.</p>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button 
                    type="button"
                    @click="showApplicationForm = false"
                    class="flex-1 px-6 py-3.5 border-2 border-slate-200 rounded-xl hover:bg-white transition-all text-sm font-semibold flex items-center justify-center space-x-2">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span>Cancel</span>
                </button>
                <button 
                    type="submit"
                    :disabled="isSubmitting"
                    class="flex-1 gradient-primary text-white px-6 py-3.5 rounded-xl hover:shadow-medium transition-all text-sm font-semibold flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!isSubmitting">Submit Application</span>
                    <span x-show="isSubmitting" class="flex items-center space-x-2">
                        <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Processing...</span>
                    </span>
                    <i data-lucide="send" x-show="!isSubmitting" class="w-4 h-4"></i>
                </button>
            </div>
        </form>

    </div>

</div>

@endsection
