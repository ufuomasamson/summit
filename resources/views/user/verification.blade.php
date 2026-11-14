@extends('layouts.bankapp')
@section('title', $title)
@section('content')
    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                <i data-lucide="shield-check" class="w-5 h-5 text-primary-500"></i>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">Account Verification</h1>
                <p class="text-sm text-[#7A7A7A]">Complete your profile to unlock full banking access</p>
            </div>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="max-w-5xl mx-auto" x-data="{ 
        currentStep: 1, 
        totalSteps: 5,
        nextStep() {
            if (this.currentStep < this.totalSteps) {
                this.currentStep++;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        },
        prevStep() {
            if (this.currentStep > 1) {
                this.currentStep--;
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    }">
        <div class="bg-white border border-[#E0E5E5] rounded-lg p-5 md:p-8 mb-6">
            <!-- Introduction Section -->
            <div class="text-center mb-8 pb-6 border-b border-[#E0E5E5]">
                <div class="w-14 h-14 rounded-lg bg-[#DFE5E5] flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="user-check" class="w-7 h-7 text-primary-500"></i>
                </div>
                <h2 class="text-xl font-semibold text-[#0D1B23] mb-2">Complete Your Profile</h2>
                <p class="text-sm text-[#7A7A7A] max-w-2xl mx-auto">
                    To comply with financial regulations and ensure account security, please provide your information to proceed. 
                    All data is encrypted and handled according to banking privacy standards.
                </p>
            </div>

            <!-- Progress Stepper -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-semibold transition-colors"
                            :class="currentStep >= 1 ? 'bg-primary-500 text-white' : 'bg-[#DFE5E5] text-[#A0ACAC]'">
                            <span x-show="currentStep > 1">&#10003;</span>
                            <span x-show="currentStep <= 1">1</span>
                        </div>
                        <span class="text-xs mt-2 font-medium hidden md:block" :class="currentStep >= 1 ? 'text-primary-500' : 'text-[#A0ACAC]'">Personal</span>
                    </div>
                    <div class="h-0.5 flex-1 mx-2 rounded transition-colors" 
                        :class="currentStep >= 2 ? 'bg-primary-500' : 'bg-[#DFE5E5]'"></div>
                    
                    <!-- Step 2 -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-semibold transition-colors"
                            :class="currentStep >= 2 ? 'bg-primary-500 text-white' : 'bg-[#DFE5E5] text-[#A0ACAC]'">
                            <span x-show="currentStep > 2">&#10003;</span>
                            <span x-show="currentStep <= 2">2</span>
                        </div>
                        <span class="text-xs mt-2 font-medium hidden md:block" :class="currentStep >= 2 ? 'text-primary-500' : 'text-[#A0ACAC]'">Employment</span>
                    </div>
                    <div class="h-0.5 flex-1 mx-2 rounded transition-colors" 
                        :class="currentStep >= 3 ? 'bg-primary-500' : 'bg-[#DFE5E5]'"></div>
                    
                    <!-- Step 3 -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-semibold transition-colors"
                            :class="currentStep >= 3 ? 'bg-primary-500 text-white' : 'bg-[#DFE5E5] text-[#A0ACAC]'">
                            <span x-show="currentStep > 3">&#10003;</span>
                            <span x-show="currentStep <= 3">3</span>
                        </div>
                        <span class="text-xs mt-2 font-medium hidden md:block" :class="currentStep >= 3 ? 'text-primary-500' : 'text-[#A0ACAC]'">Address</span>
                    </div>
                    <div class="h-0.5 flex-1 mx-2 rounded transition-colors" 
                        :class="currentStep >= 4 ? 'bg-primary-500' : 'bg-[#DFE5E5]'"></div>
                    
                    <!-- Step 4 -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-semibold transition-colors"
                            :class="currentStep >= 4 ? 'bg-primary-500 text-white' : 'bg-[#DFE5E5] text-[#A0ACAC]'">
                            <span x-show="currentStep > 4">&#10003;</span>
                            <span x-show="currentStep <= 4">4</span>
                        </div>
                        <span class="text-xs mt-2 font-medium hidden md:block" :class="currentStep >= 4 ? 'text-primary-500' : 'text-[#A0ACAC]'">Next of Kin</span>
                    </div>
                    <div class="h-0.5 flex-1 mx-2 rounded transition-colors" 
                        :class="currentStep >= 5 ? 'bg-primary-500' : 'bg-[#DFE5E5]'"></div>
                    
                    <!-- Step 5 -->
                    <div class="flex flex-col items-center flex-1">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-sm font-semibold transition-colors"
                            :class="currentStep >= 5 ? 'bg-primary-500 text-white' : 'bg-[#DFE5E5] text-[#A0ACAC]'">
                            5
                        </div>
                        <span class="text-xs mt-2 font-medium hidden md:block" :class="currentStep >= 5 ? 'text-primary-500' : 'text-[#A0ACAC]'">Documents</span>
                    </div>
                </div>
            </div>

            <!-- Verification Form -->
            <form action="{{ route('kycsubmit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- Step 1: Personal Details Section -->
                <div x-show="currentStep === 1" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="space-y-6">
                    
                <div class="bg-white border border-[#E0E5E5] rounded-lg p-5">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="user" class="w-4 h-4 text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#0D1B23]">Personal Details</h3>
                            <p class="text-sm text-[#7A7A7A]">Basic information for identity verification</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Full Name <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="user" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="name"
                                    value='{{ Auth::user()->name }} {{ Auth::user()->firstname }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }}'
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('name'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('name') }}</p>
                            @endif
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Email Address <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="mail" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="email" name="email" value='{{ Auth::user()->email }}'
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('email'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Phone Number <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="phone" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="phone" value='{{ Auth::user()->phone }}'
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('phone'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('phone') }}</p>
                            @endif
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Title <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-10">
                                    <i data-lucide="badge" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <select name="title" 
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] focus:border-primary-500 focus:outline-none transition-colors appearance-none"
                                    required>
                                    <option value="">Select Title</option>
                                    <option value="Mr">Mr.</option>
                                    <option value="Mrs">Mrs.</option>
                                    <option value="Mr&Mrs">Mr & Mrs.</option>
                                    <option value="Ms">Ms.</option>
                                    <option value="Miss">Miss.</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-[#7A7A7A]"></i>
                                </div>
                            </div>
                            @if ($errors->has('title'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('title') }}</p>
                            @endif
                        </div>

                        <!-- Gender -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Gender <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-10">
                                    <i data-lucide="users" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <select name="gender" id="gender"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] focus:border-primary-500 focus:outline-none transition-colors appearance-none"
                                    required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-[#7A7A7A]"></i>
                                </div>
                            </div>
                            @if ($errors->has('gender'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('gender') }}</p>
                            @endif
                        </div>

                        <!-- Zipcode -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Zipcode <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="zipcode"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Date of Birth <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-10">
                                    <i data-lucide="calendar" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="date" name="dob"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('dob'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('dob') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                </div>

                <!-- Step 2: Employment Information Section -->
                <div x-show="currentStep === 2"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="space-y-6">

                <div class="bg-white border border-[#E0E5E5] rounded-lg p-5">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="briefcase" class="w-4 h-4 text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#0D1B23]">Employment Information</h3>
                            <p class="text-sm text-[#7A7A7A]">For loan facilities and account verification</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- SSN -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                State Security Number (SSN/NI/SIN) <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="shield" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="statenumber"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('statenumber'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('statenumber') }}</p>
                            @endif
                        </div>

                        <!-- Account Type -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Account Type <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-10">
                                    <i data-lucide="landmark" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <select name="accounttype" value="{{ Auth::user()->accounttype }}"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] focus:border-primary-500 focus:outline-none transition-colors appearance-none"
                                    required>
                                    <option value="">Select Account Type</option>
                                    <option value="Checking Account">Checking Account</option>
                                    <option value="Savings Account">Savings Account</option>
                                    <option value="Fixed Deposit Account">Fixed Deposit Account</option>
                                    <option value="Current Account">Current Account</option>
                                    <option value="Crypto Currency Account">Crypto Currency Account</option>
                                    <option value="Business Account">Business Account</option>
                                    <option value="Non Resident Account">Non Resident Account</option>
                                    <option value="Cooperate Business Account">Corporate Business Account</option>
                                    <option value="Investment Account">Investment Account</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-[#7A7A7A]"></i>
                                </div>
                            </div>
                            @if ($errors->has('accounttype'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('accounttype') }}</p>
                            @endif
                        </div>

                        <!-- Employment Type -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Type of Employment <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-10">
                                    <i data-lucide="building-2" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <select name="employer" id="occupation"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] focus:border-primary-500 focus:outline-none transition-colors appearance-none"
                                    required>
                                    <option value="">Select Employment Type</option>
                                    <option value="Self Employed">Self Employed</option>
                                    <option value="Public/Government Office">Public/Government Office</option>
                                    <option value="Private/Partnership Office">Private/Partnership Office</option>
                                    <option value="Business/Sales">Business/Sales</option>
                                    <option value="Trading/Market">Trading/Market</option>
                                    <option value="Military/Paramilitary">Military/Paramilitary</option>
                                    <option value="Politician/Celebrity">Politician/Celebrity</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-[#7A7A7A]"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Annual Income -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Annual Income Range <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 z-10">
                                    <i data-lucide="dollar-sign" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <select name="income"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] focus:border-primary-500 focus:outline-none transition-colors appearance-none"
                                    required>
                                    <option value="">Select Income Range</option>
                                    <option value="$100.00 - $500.00">$100 - $500</option>
                                    <option value="$700.00 - $1,000.00">$700 - $1,000</option>
                                    <option value="$1,000.00 - $2,000.00">$1,000 - $2,000</option>
                                    <option value="$2,000.00 - $5,000.00">$2,000 - $5,000</option>
                                    <option value="$5,000.00 - $10,000.00">$5,000 - $10,000</option>
                                    <option value="$15,000.00 - $20,000.00">$15,000 - $20,000</option>
                                    <option value="$25,000.00 - $30,000.00">$25,000 - $30,000</option>
                                    <option value="$30,000.00 - $70,000.00">$30,000 - $70,000</option>
                                    <option value="$80,000.00 - $140,000.00">$80,000 - $140,000</option>
                                    <option value="$150,000.00 - $300,000.00">$150,000 - $300,000</option>
                                    <option value="$300,000.00 - $1,000,000.00">$300,000 - $1,000,000+</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-[#7A7A7A]"></i>
                                </div>
                            </div>
                            @if ($errors->has('income'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('income') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                </div>

                <!-- Step 3: Address Section -->
                <div x-show="currentStep === 3"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="space-y-6">

                <div class="bg-white border border-[#E0E5E5] rounded-lg p-5">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="map-pin" class="w-4 h-4 text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#0D1B23]">Residential Address</h3>
                            <p class="text-sm text-[#7A7A7A]">Your location information for identification</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Address -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Address Line <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-4">
                                    <i data-lucide="home" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="address"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('address'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('address') }}</p>
                            @endif
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                City <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="building" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="city"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('city'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('city') }}</p>
                            @endif
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                State/Province <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="map" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="state"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('state'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('state') }}</p>
                            @endif
                        </div>

                        <!-- Country -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Nationality <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="flag" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="country" value='{{ Auth::user()->country }}'
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                            @if ($errors->has('country'))
                                <p class="text-[#D55672] text-xs mt-1">{{ $errors->first('country') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                </div>

                <!-- Step 4: Next of Kin Section -->
                <div x-show="currentStep === 4"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="space-y-6">

                <div class="bg-white border border-[#E0E5E5] rounded-lg p-5">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="users-2" class="w-4 h-4 text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#0D1B23]">Next of Kin</h3>
                            <p class="text-sm text-[#7A7A7A]">Registered beneficiary information</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Beneficiary Name -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Beneficiary Legal Name <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="user-check" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="kinname"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                        </div>

                        <!-- Next of Kin Address -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Next of Kin Address <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="kinaddress"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                        </div>

                        <!-- Relationship -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Relationship <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="heart" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="relationship"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                        </div>

                        <!-- Age -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-2">
                                Age <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2">
                                    <i data-lucide="calendar-days" class="w-4 h-4 text-[#A0ACAC]"></i>
                                </div>
                                <input type="text" name="age"
                                    class="w-full pl-12 pr-4 py-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg text-[#0D1B23] placeholder-[#A0ACAC] focus:border-primary-500 focus:outline-none transition-colors"
                                    required>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Step 5: Document Upload Section -->
                <div x-show="currentStep === 5"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     class="space-y-6">

                <div class="bg-white border border-[#E0E5E5] rounded-lg p-5">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="file-text" class="w-4 h-4 text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-[#0D1B23]">Document Upload</h3>
                            <p class="text-sm text-[#7A7A7A]">Identity verification documents required</p>
                        </div>
                    </div>

                    <!-- Document Type Selection -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-[#4A4A4A] mb-4">
                            Select Document Type <span class="text-[#D55672]">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="document_type" value="Int'l Passport" class="peer sr-only" checked>
                                <div class="px-5 py-4 rounded-lg border border-[#E0E5E5] bg-[#F9F9F9] peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:border-[#C8D1D1] transition-colors">
                                    <div class="flex flex-col items-center text-center space-y-2">
                                        <i data-lucide="book-open" class="w-6 h-6 text-[#7A7A7A]"></i>
                                        <span class="text-sm font-medium text-[#0D1B23]">Int'l Passport</span>
                                    </div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="document_type" value="National ID" class="peer sr-only">
                                <div class="px-5 py-4 rounded-lg border border-[#E0E5E5] bg-[#F9F9F9] peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:border-[#C8D1D1] transition-colors">
                                    <div class="flex flex-col items-center text-center space-y-2">
                                        <i data-lucide="id-card" class="w-6 h-6 text-[#7A7A7A]"></i>
                                        <span class="text-sm font-medium text-[#0D1B23]">National ID</span>
                                    </div>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="document_type" value="Drivers License" class="peer sr-only">
                                <div class="px-5 py-4 rounded-lg border border-[#E0E5E5] bg-[#F9F9F9] peer-checked:border-primary-500 peer-checked:bg-primary-50 hover:border-[#C8D1D1] transition-colors">
                                    <div class="flex flex-col items-center text-center space-y-2">
                                        <i data-lucide="car" class="w-6 h-6 text-[#7A7A7A]"></i>
                                        <span class="text-sm font-medium text-[#0D1B23]">Driver's License</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Guidelines -->
                    <div class="mb-6 p-4 rounded-lg bg-[#F9F9F9] border border-[#3e4e83]/30">
                        <h4 class="text-sm font-medium text-[#3e4e83] mb-3 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2"></i>
                            Document Guidelines
                        </h4>
                        <ul class="space-y-2 text-[#7A7A7A] text-sm">
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span>Document must be valid and not expired</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span>Image should be clear and all text must be readable</span>
                            </li>
                            <li class="flex items-start">
                                <i data-lucide="check-circle" class="w-4 h-4 text-primary-500 mr-2 flex-shrink-0 mt-0.5"></i>
                                <span>Avoid glare or shadows on the document</span>
                            </li>
                        </ul>
                    </div>

                    <!-- File Uploads -->
                    <div class="space-y-6">
                        <!-- Front Side -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-3">
                                Document Front Side <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="flex flex-col md:flex-row items-center gap-5">
                                <div class="flex-1 w-full">
                                    <label class="block cursor-pointer">
                                        <div id="frontimg-dropzone" class="border border-dashed border-[#C8D1D1] rounded-lg p-6 hover:border-primary-300 hover:bg-[#F9F9F9] transition-colors">
                                            <div id="frontimg-placeholder" class="flex flex-col items-center">
                                                <i data-lucide="upload" class="w-8 h-8 text-[#A0ACAC] mb-2"></i>
                                                <p class="text-sm text-[#4A4A4A] mb-0.5 font-medium">Click to upload</p>
                                                <p class="text-xs text-[#A0ACAC]">PNG, JPG or PDF (max. 5MB)</p>
                                            </div>
                                            <div id="frontimg-preview" class="hidden flex flex-col items-center">
                                                <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mb-2"></i>
                                                <p id="frontimg-filename" class="text-sm text-[#4A4A4A] mb-0.5 font-medium truncate max-w-full"></p>
                                                <p class="text-xs text-green-500">File selected - Click to change</p>
                                            </div>
                                        </div>
                                        <input type="file" name="frontimg" class="hidden" accept="image/*,.pdf" required onchange="handleFileSelect(this, 'frontimg')">
                                    </label>
                                    @if ($errors->has('frontimg'))
                                        <p class="text-[#D55672] text-xs mt-2">{{ $errors->first('frontimg') }}</p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <div id="frontimg-thumb" class="w-20 h-20 rounded-lg bg-[#DFE5E5] border border-[#C8D1D1] flex items-center justify-center overflow-hidden">
                                        <i data-lucide="credit-card" class="w-8 h-8 text-[#A0ACAC]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Side -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-3">
                                Document Back Side <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="flex flex-col md:flex-row items-center gap-5">
                                <div class="flex-1 w-full">
                                    <label class="block cursor-pointer">
                                        <div id="backimg-dropzone" class="border border-dashed border-[#C8D1D1] rounded-lg p-6 hover:border-primary-300 hover:bg-[#F9F9F9] transition-colors">
                                            <div id="backimg-placeholder" class="flex flex-col items-center">
                                                <i data-lucide="upload" class="w-8 h-8 text-[#A0ACAC] mb-2"></i>
                                                <p class="text-sm text-[#4A4A4A] mb-0.5 font-medium">Click to upload</p>
                                                <p class="text-xs text-[#A0ACAC]">PNG, JPG or PDF (max. 5MB)</p>
                                            </div>
                                            <div id="backimg-preview" class="hidden flex flex-col items-center">
                                                <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mb-2"></i>
                                                <p id="backimg-filename" class="text-sm text-[#4A4A4A] mb-0.5 font-medium truncate max-w-full"></p>
                                                <p class="text-xs text-green-500">File selected - Click to change</p>
                                            </div>
                                        </div>
                                        <input type="file" name="backimg" class="hidden" accept="image/*,.pdf" required onchange="handleFileSelect(this, 'backimg')">
                                    </label>
                                    @if ($errors->has('backimg'))
                                        <p class="text-[#D55672] text-xs mt-2">{{ $errors->first('backimg') }}</p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <div id="backimg-thumb" class="w-20 h-20 rounded-lg bg-[#DFE5E5] border border-[#C8D1D1] flex items-center justify-center overflow-hidden">
                                        <i data-lucide="credit-card" class="w-8 h-8 text-[#A0ACAC]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Passport Photo -->
                        <div>
                            <label class="block text-sm font-medium text-[#4A4A4A] mb-3">
                                Passport Photograph <span class="text-[#D55672]">*</span>
                            </label>
                            <div class="flex flex-col md:flex-row items-center gap-5">
                                <div class="flex-1 w-full">
                                    <label class="block cursor-pointer">
                                        <div id="photo-dropzone" class="border border-dashed border-[#C8D1D1] rounded-lg p-6 hover:border-primary-300 hover:bg-[#F9F9F9] transition-colors">
                                            <div id="photo-placeholder" class="flex flex-col items-center">
                                                <i data-lucide="upload" class="w-8 h-8 text-[#A0ACAC] mb-2"></i>
                                                <p class="text-sm text-[#4A4A4A] mb-0.5 font-medium">Click to upload</p>
                                                <p class="text-xs text-[#A0ACAC]">PNG or JPG (max. 5MB)</p>
                                            </div>
                                            <div id="photo-preview" class="hidden flex flex-col items-center">
                                                <i data-lucide="check-circle" class="w-8 h-8 text-green-500 mb-2"></i>
                                                <p id="photo-filename" class="text-sm text-[#4A4A4A] mb-0.5 font-medium truncate max-w-full"></p>
                                                <p class="text-xs text-green-500">File selected - Click to change</p>
                                            </div>
                                        </div>
                                        <input type="file" name="photo" class="hidden" accept="image/*" required onchange="handleFileSelect(this, 'photo')">
                                    </label>
                                    @if ($errors->has('photo'))
                                        <p class="text-[#D55672] text-xs mt-2">{{ $errors->first('photo') }}</p>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
                                    <div id="photo-thumb" class="w-20 h-20 rounded-lg bg-[#DFE5E5] border border-[#C8D1D1] flex items-center justify-center overflow-hidden">
                                        <i data-lucide="user" class="w-8 h-8 text-[#A0ACAC]"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex items-center justify-between pt-6 border-t border-[#E0E5E5] mt-8">
                    <!-- Previous Button -->
                    <button type="button" @click="prevStep()" x-show="currentStep > 1"
                        class="px-5 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] text-[#0D1B23] font-medium text-sm transition-colors flex items-center space-x-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        <span>Previous</span>
                    </button>
                    <div x-show="currentStep === 1"></div>

                    <!-- Next Button -->
                    <button type="button" @click="nextStep()" x-show="currentStep < 5"
                        class="ml-auto px-5 py-2.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-medium text-sm text-white flex items-center space-x-2">
                        <span>Next Step</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>

                    <!-- Submit Button (Only on Last Step) -->
                    <div x-show="currentStep === 5" class="ml-auto">
                        @if (Auth::user()->account_verify == 'Under review')
                            <button type="submit" disabled
                                class="px-6 py-2.5 rounded-lg bg-gray-600 cursor-not-allowed opacity-50 font-medium text-sm text-white flex items-center space-x-2">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                <span>Under Review</span>
                            </button>
                        @else
                            <button type="submit"
                                class="px-6 py-2.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-medium text-sm text-white flex items-center space-x-2">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                <span>Submit Application</span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Progress Text -->
                <div class="text-center mt-4">
                    <p class="text-sm text-[#7A7A7A]">
                        Step <span x-text="currentStep" class="text-primary-500 font-medium"></span> of <span class="text-primary-500 font-medium">5</span>
                    </p>
                </div>

            </form>
        </div>
    </div>

    <!-- Lucide Icons Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        
        // Reinitialize icons when Alpine updates
        document.addEventListener('alpine:initialized', () => {
            setInterval(() => {
                lucide.createIcons();
            }, 500);
        });

        function handleFileSelect(input, name) {
            var placeholder = document.getElementById(name + '-placeholder');
            var preview = document.getElementById(name + '-preview');
            var filenameEl = document.getElementById(name + '-filename');
            var thumbEl = document.getElementById(name + '-thumb');
            var dropzone = document.getElementById(name + '-dropzone');

            if (input.files && input.files[0]) {
                var file = input.files[0];
                filenameEl.textContent = file.name;
                placeholder.classList.add('hidden');
                preview.classList.remove('hidden');
                dropzone.style.borderColor = '#22c55e';
                dropzone.style.backgroundColor = '#f0fdf4';

                // Show image thumbnail preview
                if (file.type.startsWith('image/')) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        thumbEl.innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover rounded-lg" alt="Preview">';
                    };
                    reader.readAsDataURL(file);
                } else {
                    // PDF or other file type
                    thumbEl.innerHTML = '<div class="flex flex-col items-center"><svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg><span class="text-xs text-green-600 mt-1">PDF</span></div>';
                }

                lucide.createIcons();
            }
        }
    </script>
@endsection

