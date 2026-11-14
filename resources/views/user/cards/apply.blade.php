@extends('layouts.bankapp')

@section('title', 'Apply for Virtual Card')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-3">
        <a href="{{ route('cards.index') }}" class="w-9 h-9 rounded-lg bg-[#F9F9F9] border border-[#E0E5E5] flex items-center justify-center hover:bg-[#DFE5E5] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 text-[#4A4A4A]"></i>
        </a>
        <div>
            <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">Apply for Virtual Card</h1>
            <p class="text-sm text-[#7A7A7A]">Choose your card level and complete the application</p>
        </div>
    </div>

    @if(session('error'))
    <div class="bg-[#D55672]/10 border border-[#D55672]/20 text-[#D55672] p-4 rounded-lg">
        <p class="text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <form action="{{ route('cards.apply.submit') }}" method="POST" x-data="{ 
        selectedLevel: 'Standard',
        selectedType: 'Visa',
        fees: {
            Standard: {{ $settings->standard_fee }},
            Gold: {{ $settings->gold_fee }},
            Platinum: {{ $settings->platinum_fee }},
            Black: {{ $settings->black_fee }}
        }
    }">
        @csrf
        
        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Application Form -->
            <div class="lg:col-span-2 space-y-5">
                
                <!-- Card Level Selection -->
                <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-5">
                    <h2 class="text-sm font-semibold text-[#0D1B23] mb-4 flex items-center gap-2">
                        <i data-lucide="layers" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Select Card Level
                    </h2>
                    
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Standard -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="card_level" value="Standard" x-model="selectedLevel" class="peer sr-only" required>
                            <div class="border border-[#C8D1D1] peer-checked:border-[#49786B] peer-checked:bg-[#49786B]/5 rounded-lg p-3 sm:p-4 transition-colors hover:border-[#A0ACAC] bg-white min-h-[130px] sm:min-h-[150px] flex flex-col">
                                <div class="flex flex-col gap-0.5 mb-2">
                                    <span class="font-medium text-[#0D1B23] text-sm">Standard</span>
                                    <span class="text-xs font-medium text-[#7A7A7A]">{{ Auth::user()->currency }}{{ number_format($settings->standard_fee, 2) }}</span>
                                </div>
                                <p class="text-xs text-[#7A7A7A] mb-2">Perfect for everyday use</p>
                                <div class="mt-auto space-y-1">
                                    <p class="text-[10px] sm:text-xs text-[#7A7A7A] flex items-center gap-1">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>Basic features</span>
                                    </p>
                                    <p class="text-[10px] sm:text-xs text-[#7A7A7A] flex items-center gap-1">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>Standard limits</span>
                                    </p>
                                </div>
                            </div>
                        </label>

                        <!-- Gold -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="card_level" value="Gold" x-model="selectedLevel" class="peer sr-only">
                            <div class="border border-[#C8D1D1] peer-checked:border-[#3e4e83] peer-checked:bg-[#3e4e83]/5 rounded-lg p-3 sm:p-4 transition-colors hover:border-[#A0ACAC] bg-white min-h-[130px] sm:min-h-[150px] flex flex-col">
                                <div class="flex flex-col gap-0.5 mb-2">
                                    <span class="font-medium text-[#0D1B23] flex items-center gap-1.5 text-sm">
                                        <i data-lucide="star" class="w-3.5 h-3.5 text-[#3e4e83] flex-shrink-0"></i>
                                        <span>Gold</span>
                                    </span>
                                    <span class="text-xs font-medium text-[#7A7A7A]">{{ Auth::user()->currency }}{{ number_format($settings->gold_fee, 2) }}</span>
                                </div>
                                <p class="text-xs text-[#7A7A7A] mb-2">Enhanced benefits</p>
                                <div class="mt-auto space-y-1">
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-[#7A7A7A]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>Higher limits</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-[#7A7A7A]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>Priority support</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Platinum -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="card_level" value="Platinum" x-model="selectedLevel" class="peer sr-only">
                            <div class="border border-[#C8D1D1] peer-checked:border-[#49786B] peer-checked:bg-[#49786B]/5 rounded-lg p-3 sm:p-4 transition-colors hover:border-[#A0ACAC] bg-white min-h-[130px] sm:min-h-[150px] flex flex-col">
                                <div class="flex flex-col gap-0.5 mb-2">
                                    <span class="font-medium text-[#0D1B23] flex items-center gap-1.5 text-sm">
                                        <i data-lucide="gem" class="w-3.5 h-3.5 text-[#49786B] flex-shrink-0"></i>
                                        <span>Platinum</span>
                                    </span>
                                    <span class="text-xs font-medium text-[#7A7A7A]">{{ Auth::user()->currency }}{{ number_format($settings->platinum_fee, 2) }}</span>
                                </div>
                                <p class="text-xs text-[#7A7A7A] mb-2">Premium experience</p>
                                <div class="mt-auto space-y-1">
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-[#7A7A7A]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>Premium limits</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-[#7A7A7A]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>Exclusive perks</span>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <!-- Black -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="card_level" value="Black" x-model="selectedLevel" class="peer sr-only">
                            <div class="border border-[#C8D1D1] peer-checked:border-[#0D1B23] peer-checked:bg-[#0D1B23]/5 rounded-lg p-3 sm:p-4 transition-colors hover:border-[#A0ACAC] bg-white min-h-[130px] sm:min-h-[150px] flex flex-col">
                                <div class="flex flex-col gap-0.5 mb-2">
                                    <span class="font-medium text-[#0D1B23] flex items-center gap-1.5 text-sm">
                                        <i data-lucide="crown" class="w-3.5 h-3.5 text-[#0D1B23] flex-shrink-0"></i>
                                        <span>Black</span>
                                    </span>
                                    <span class="text-xs font-medium text-[#7A7A7A]">{{ Auth::user()->currency }}{{ number_format($settings->black_fee, 2) }}</span>
                                </div>
                                <p class="text-xs text-[#7A7A7A] mb-2">Ultimate luxury</p>
                                <div class="mt-auto space-y-1">
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-[#7A7A7A]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>Maximum limits</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-[#7A7A7A]">
                                        <i data-lucide="check" class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-[#49786B] flex-shrink-0"></i>
                                        <span>VIP treatment</span>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Card Type Selection -->
                <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-5">
                    <h2 class="text-sm font-semibold text-[#0D1B23] mb-4 flex items-center gap-2">
                        <i data-lucide="credit-card" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Card Network
                    </h2>
                    
                    <div class="grid grid-cols-4 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="card_type" value="Visa" x-model="selectedType" class="peer sr-only" required>
                            <div class="border border-[#C8D1D1] peer-checked:border-[#49786B] peer-checked:bg-[#49786B]/5 rounded-lg p-4 flex items-center justify-center transition-colors hover:border-[#A0ACAC] bg-white">
                                <svg class="h-8 w-12" viewBox="0 0 1000 324" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M433.334 318.516H348.568L403.315 5.01855H488.081L433.334 318.516Z" fill="#1434CB"/>
                                    <path d="M727.98 15.9043C712.039 9.56622 686.227 2.51855 653.355 2.51855C574.054 2.51855 519.055 45.5277 518.802 106.826C518.306 152.343 558.126 177.912 587.98 193.316C618.34 209.233 629.315 219.335 629.315 233.014C629.061 254.008 603.493 263.591 579.702 263.591C547.839 263.591 530.7 258.287 505.123 245.892L493.902 240.335L482.166 313.221C501.069 323.07 536.096 331.667 572.643 332.163C657.14 332.163 711.128 289.897 711.895 224.144C712.394 188.508 689.871 160.445 643.015 136.91C615.2 121.744 598.306 111.638 598.554 97.2356C598.554 84.5987 613.224 70.9167 648.747 70.9167C677.823 70.4206 699.053 77.7188 715.707 85.5144L726.681 90.8186L738.417 19.9214L727.98 15.9043Z" fill="#1434CB"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M841.897 5.01855H773.691C755.782 5.01855 742.397 10.0624 734.862 30.0238L630.236 318.516H715.464C715.464 318.516 729.143 280.806 732.429 272.002C742.901 272.002 822.706 272.002 835.844 272.002C838.373 283.215 845.93 318.516 845.93 318.516H922.667L841.897 5.01855ZM762.22 208.49C769.018 189.752 796.85 116.686 796.85 116.686C796.602 117.174 803.16 99.19 806.699 88.0899L812.02 114.188C812.02 114.188 829.775 192.026 833.061 208.49H762.22Z" fill="#1434CB"/>
                                    <path d="M251.994 5.01855L171.471 219.335L162.137 174.313C146.216 129.054 102.175 80.0894 52.8662 56.8035L126.462 318.02H212.215L336.832 5.01855H251.994Z" fill="#1434CB"/>
                                    <path d="M127.963 5.01855H0.66211L0.167969 10.3101C98.1497 31.7971 163.301 76.5646 190.132 129.298L162.633 13.5747C157.329 -0.60098 144.316 5.51465 127.963 5.01855Z" fill="#1434CB"/>
                                </svg>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="card_type" value="Mastercard" x-model="selectedType" class="peer sr-only">
                            <div class="border border-[#C8D1D1] peer-checked:border-[#D55672] peer-checked:bg-[#D55672]/5 rounded-lg p-4 flex items-center justify-center transition-colors hover:border-[#A0ACAC] bg-white">
                                <svg class="h-8 w-12" viewBox="0 0 131.39 86.9" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M48.37 15.14h34.66v56.61H48.37z" fill="#ff5f00"/>
                                    <path d="M52.37 43.45a35.94 35.94 0 0113.75-28.3 36 36 0 100 56.61 35.94 35.94 0 01-13.75-28.31z" fill="#eb001b"/>
                                    <path d="M120.39 65.54V64.5h.48v-.24h-1.19v.24h.47v1.04zm2.31 0v-1.29h-.36l-.42.91-.42-.91h-.36v1.29h.26V64.9l.39.89h.27l.39-.89v.89z" fill="#f79e1b"/>
                                    <path d="M123.94 43.45a36 36 0 01-58 28.3 36 36 0 000-56.61 36 36 0 0158 28.3z" fill="#f79e1b"/>
                                </svg>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="card_type" value="American Express" x-model="selectedType" class="peer sr-only">
                            <div class="border border-[#C8D1D1] peer-checked:border-[#49786B] peer-checked:bg-[#49786B]/5 rounded-lg p-4 flex items-center justify-center transition-colors hover:border-[#A0ACAC] bg-white">
                                <svg class="h-8 w-12" viewBox="0 0 128 80" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="128" height="80" rx="12" fill="#2E77BC"/>
                                    <path d="M20 50h88v-20H20v20zm5-15h10v10H25v-10zm15 0h10v10H40v-10zm15 0h10v10H55v-10zm15 0h10v10H70v-10zm15 0h10v10H85v-10z" fill="white"/>
                                    <text x="50%" y="25%" dominant-baseline="middle" text-anchor="middle"
                                          font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="white">
                                      AMERICAN
                                    </text>
                                    <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle"
                                          font-family="Arial, sans-serif" font-size="18" font-weight="bold" fill="white">
                                      EXPRESS
                                    </text>
                                </svg>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="card_type" value="Discover" x-model="selectedType" class="peer sr-only">
                            <div class="border border-[#C8D1D1] peer-checked:border-[#3e4e83] peer-checked:bg-[#3e4e83]/5 rounded-lg p-4 flex items-center justify-center transition-colors hover:border-[#A0ACAC] bg-white">
                                <svg class="h-8 w-12" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="200" height="60" fill="#fff" rx="6"/>
                                    <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
                                          font-family="Arial, sans-serif" font-weight="bold"
                                          font-size="28" fill="#000">
                                      DISC
                                    </text>
                                    <defs>
                                      <radialGradient id="discoverGrad" cx="50%" cy="50%" r="70%">
                                        <stop offset="0%" stop-color="#ffb347"/>
                                        <stop offset="60%" stop-color="#ff7a00"/>
                                        <stop offset="100%" stop-color="#d85b00"/>
                                      </radialGradient>
                                    </defs>
                                    <circle cx="118" cy="30" r="22" fill="url(#discoverGrad)" />
                                    <text x="138" y="50%" dominant-baseline="middle" text-anchor="start"
                                          font-family="Arial, sans-serif" font-weight="bold"
                                          font-size="28" fill="#000">
                                      VER
                                    </text>
                                </svg>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Cardholder Information -->
                <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-5">
                    <h2 class="text-sm font-semibold text-[#0D1B23] mb-4 flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Cardholder Information
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-[#4A4A4A] mb-1.5">Full Name (as on card)</label>
                            <input type="text" name="cardholder_name" value="{{ old('cardholder_name', Auth::user()->name . ' ' . Auth::user()->middlename.' ' . Auth::user()->lastname) }}" required class="w-full px-4 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-[#0D1B23] text-sm transition-colors" placeholder="JOHN DOE">
                            @error('cardholder_name')
                            <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm text-[#4A4A4A] mb-1.5">Billing Address</label>
                            <input type="text" name="billing_address" value="{{ old('billing_address') }}" required class="w-full px-4 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-[#0D1B23] text-sm transition-colors" placeholder="123 Main Street">
                            @error('billing_address')
                            <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-sm text-[#4A4A4A] mb-1.5">City</label>
                                <input type="text" name="billing_city" value="{{ old('billing_city') }}" required class="w-full px-4 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-[#0D1B23] text-sm transition-colors" placeholder="New York">
                                @error('billing_city')
                                <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm text-[#4A4A4A] mb-1.5">ZIP Code</label>
                                <input type="text" name="billing_zipcode" value="{{ old('billing_zipcode') }}" required class="w-full px-4 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-[#0D1B23] text-sm transition-colors" placeholder="10001">
                                @error('billing_zipcode')
                                <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-[#4A4A4A] mb-1.5">Country</label>
                            <input type="text" name="billing_country" value="{{ old('billing_country', 'United States') }}" required class="w-full px-4 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-[#0D1B23] text-sm transition-colors" placeholder="United States">
                            @error('billing_country')
                            <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-5 sticky top-8">
                    <h2 class="text-sm font-semibold text-[#0D1B23] mb-4">Application Summary</h2>
                    
                    <!-- Preview Card -->
                    <div class="mb-5 rounded-lg p-5 text-white relative overflow-hidden"
                        :class="{
                            'bg-slate-700': selectedLevel === 'Standard',
                            'bg-amber-800': selectedLevel === 'Gold',
                            'bg-blue-800': selectedLevel === 'Platinum',
                            'bg-slate-900 border border-slate-600': selectedLevel === 'Black'
                        }">
                        <div class="relative z-10">
                            <p class="text-xs opacity-70" x-text="selectedType"></p>
                            <p class="text-[10px] opacity-50" x-text="selectedLevel"></p>
                            <p class="text-sm font-mono tracking-wider mt-5">**** **** **** ****</p>
                            <div class="mt-3 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] opacity-50">Cardholder</p>
                                    <p class="font-medium text-xs">{{ strtoupper(Auth::user()->name) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] opacity-50">Expires</p>
                                    <p class="font-medium text-xs">--/--</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Breakdown -->
                    <div class="space-y-2.5 mb-5">
                        <div class="flex justify-between text-sm">
                            <span class="text-[#7A7A7A]">Card Level</span>
                            <span class="font-medium text-[#0D1B23]" x-text="selectedLevel"></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-[#7A7A7A]">Issuance Fee</span>
                            <span class="font-medium text-[#0D1B23]" x-text="'{{ Auth::user()->currency }}' + fees[selectedLevel].toFixed(2)"></span>
                        </div>
                        <div class="border-t border-[#E0E5E5] pt-2.5 flex justify-between">
                            <span class="text-sm font-medium text-[#0D1B23]">Total to Pay</span>
                            <span class="text-base font-semibold text-[#49786B]" x-text="'{{ Auth::user()->currency }}' + fees[selectedLevel].toFixed(2)"></span>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full px-6 py-3 rounded-lg bg-[#49786B] hover:bg-[#3B645A] transition-colors font-medium text-white text-sm">
                        Submit Application
                    </button>

                    <p class="text-xs text-[#7A7A7A] mt-3 text-center flex items-center justify-center gap-1">
                        <i data-lucide="shield" class="w-3 h-3"></i>
                        Fee will be deducted from your account balance
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection


