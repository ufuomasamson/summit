@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ 
    depositModal: false, 
    transferModal: false,
    transferType: '',
    showBalance: true 
}" class="space-y-6">

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Welcome Section -->
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1">
            Welcome back, {{ Auth::user()->name }}
        </h1>
        <p class="text-slate-500 text-sm">Here's an overview of your account activity.</p>
    </div>

    <!-- Balance Card -->
    <div class="rounded-2xl overflow-hidden shadow-xl bg-gradient-to-br from-primary-600 via-primary-800 to-primary-900">
        <!-- Top Section: Balance + BTC Badge -->
        <div class="px-6 pt-6 pb-5 md:px-8 md:pt-8 md:pb-6 relative">
            <!-- Decorative circles -->
            <div class="absolute top-0 right-0 w-40 h-40 rounded-full opacity-[0.06] bg-white transform translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 rounded-full opacity-[0.04] bg-white transform -translate-x-12 translate-y-12"></div>
            
            <div class="flex items-start justify-between relative z-10">
                <div class="flex-1">
                    <p class="text-xs uppercase tracking-widest font-semibold text-white/70 mb-3">Available Balance</p>
                    <div class="flex items-center space-x-3 mb-1">
                        <h2 x-show="showBalance" class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-none">
                            {{ Auth::user()->currency }} {{ number_format(Auth::user()->account_bal, 2, '.', ',') }}
                        </h2>
                        <h2 x-show="!showBalance" x-cloak class="text-2xl sm:text-4xl md:text-5xl font-extrabold text-white tracking-tight leading-none">
                            ******
                        </h2>
                        <button @click="showBalance = !showBalance" class="p-1.5 rounded-full hover:bg-white/10 transition-colors">
                            <i :data-lucide="showBalance ? 'eye' : 'eye-off'" class="w-5 h-5 text-white/60"></i>
                        </button>
                    </div>
                </div>

                <!-- Fund Button -->
                <div class="flex-shrink-0 ml-4">
                    <button @click="depositModal = true" class="flex items-center justify-center space-x-1.5 px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 border border-white/10 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                        <span class="text-sm font-semibold text-white">Fund</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom Stats Row -->
        <div class="grid grid-cols-3 divide-x divide-white/15 bg-black/20 border-t border-white/15">
            <!-- BTC Balance -->
            <div class="px-4 py-4 md:px-6">
                <div class="flex items-center space-x-1.5 mb-1">
                    <img src="https://assets.coingecko.com/coins/images/1/standard/bitcoin.png?1696501400" class="w-3.5 h-3.5" alt="BTC">
                    <p class="text-[10px] uppercase tracking-widest font-semibold text-white/60">BTC</p>
                </div>
                <p class="text-base md:text-lg font-bold text-white">{{ number_format($cryptoAccount->btc ?? 0, 6) }}</p>
                <p class="text-[10px] text-white/50">≈ {{ Auth::user()->currency }}{{ number_format(($cryptoAccount->btc ?? 0) * ($cryptoPrices['btc'] ?? 0), 2) }}</p>
            </div>
            <!-- ETH Balance -->
            <div class="px-4 py-4 md:px-6">
                <div class="flex items-center space-x-1.5 mb-1">
                    <img src="https://assets.coingecko.com/coins/images/279/standard/ethereum.png?1696501628" class="w-3.5 h-3.5" alt="ETH">
                    <p class="text-[10px] uppercase tracking-widest font-semibold text-white/60">ETH</p>
                </div>
                <p class="text-base md:text-lg font-bold text-white">{{ number_format($cryptoAccount->eth ?? 0, 6) }}</p>
                <p class="text-[10px] text-white/50">≈ {{ Auth::user()->currency }}{{ number_format(($cryptoAccount->eth ?? 0) * ($cryptoPrices['eth'] ?? 0), 2) }}</p>
            </div>
            <!-- USDT Balance -->
            <div class="px-4 py-4 md:px-6">
                <div class="flex items-center space-x-1.5 mb-1">
                    <img src="https://assets.coingecko.com/coins/images/325/standard/Tether.png?1696501661" class="w-3.5 h-3.5" alt="USDT">
                    <p class="text-[10px] uppercase tracking-widest font-semibold text-white/60">USDT</p>
                </div>
                <p class="text-base md:text-lg font-bold text-white">{{ number_format($cryptoAccount->usdt ?? 0, 2) }}</p>
                <p class="text-[10px] text-white/50">≈ {{ Auth::user()->currency }}{{ number_format(($cryptoAccount->usdt ?? 0) * ($cryptoPrices['usdt'] ?? 1), 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Crypto Balances -->
    {{-- <div class="grid grid-cols-3 gap-2">
        <div class="bg-white rounded-xl p-3 border border-slate-200 hover:border-slate-300 shadow-sm transition-colors">
            <div class="flex items-center space-x-1.5 mb-1.5">
                <img src="https://assets.coingecko.com/coins/images/1/standard/bitcoin.png?1696501400" class="w-4 h-4" alt="BTC">
                <p class="text-xs font-medium text-slate-500">BTC</p>
            </div>
            <p class="text-sm font-bold text-slate-900">{{ number_format($cryptoAccount->btc ?? 0, 6) }}</p>
            <p class="text-[10px] text-slate-500">≈ {{ Auth::user()->currency }}{{ number_format(($cryptoAccount->btc ?? 0) * ($cryptoPrices['btc'] ?? 0), 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-3 border border-slate-200 hover:border-slate-300 shadow-sm transition-colors">
            <div class="flex items-center space-x-1.5 mb-1.5">
                <img src="https://assets.coingecko.com/coins/images/279/standard/ethereum.png?1696501628" class="w-4 h-4" alt="ETH">
                <p class="text-xs font-medium text-slate-500">ETH</p>
            </div>
            <p class="text-sm font-bold text-slate-900">{{ number_format($cryptoAccount->eth ?? 0, 6) }}</p>
            <p class="text-[10px] text-slate-500">≈ {{ Auth::user()->currency }}{{ number_format(($cryptoAccount->eth ?? 0) * ($cryptoPrices['eth'] ?? 0), 2) }}</p>
        </div>
        <div class="bg-white rounded-xl p-3 border border-slate-200 hover:border-slate-300 shadow-sm transition-colors">
            <div class="flex items-center space-x-1.5 mb-1.5">
                <img src="https://assets.coingecko.com/coins/images/325/standard/Tether.png?1696501661" class="w-4 h-4" alt="USDT">
                <p class="text-xs font-medium text-slate-500">USDT</p>
            </div>
            <p class="text-sm font-bold text-slate-900">{{ number_format($cryptoAccount->usdt ?? 0, 2) }}</p>
            <p class="text-[10px] text-slate-500">≈ {{ Auth::user()->currency }}{{ number_format(($cryptoAccount->usdt ?? 0) * ($cryptoPrices['usdt'] ?? 1), 2) }}</p>
        </div>
    </div> --}}

    <!-- Quick Actions -->
    <div>
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Send Money</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <button @click="transferType = 'wire'; transferModal = true" 
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/jh.png') }}" alt="Wire Transfer" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">Wire Transfer</p>
                <p class="text-[11px] text-slate-500 leading-tight">International bank transfer</p>
            </button>
            
            <button @click="transferType = 'local'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/4.jpg') }}" alt="Local Transfer" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">Local Transfer</p>
                <p class="text-[11px] text-slate-500 leading-tight">Domestic bank transfer</p>
            </button>
            
            <button @click="transferType = 'paypal'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/pa.png') }}" alt="PayPal" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">PayPal</p>
                <p class="text-[11px] text-slate-500 leading-tight">Send via PayPal</p>
            </button>
            
            <button @click="transferType = 'skrill'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/s.png') }}" alt="Skrill" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">Skrill</p>
                <p class="text-[11px] text-slate-500 leading-tight">Send via Skrill</p>
            </button>
        </div>
    </div>

    <!-- Cryptocurrency Transfer Options -->
    @if($settings->enable_crypto_transfer == 'yes')
    <div>
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Crypto Transfer</h3>
        <div class="grid grid-cols-3 gap-3">
            <button @click="transferType = 'bitcoin'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-3 sm:p-4 hover:bg-amber-500/5 hover:border-amber-500/20 shadow-sm transition-all duration-200 text-left min-w-0 overflow-hidden">
                <div class="flex flex-col sm:flex-row items-center sm:items-center sm:space-x-3 mb-2.5">
                    <img src="https://assets.coingecko.com/coins/images/1/standard/bitcoin.png?1696501400" alt="Bitcoin" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full ring-2 ring-amber-500/20 flex-shrink-0 mb-1.5 sm:mb-0">
                    <div class="min-w-0 text-center sm:text-left">
                        <p class="text-xs sm:text-sm font-semibold text-slate-900 truncate">Bitcoin</p>
                        <p class="text-[10px] text-slate-500 truncate">BTC Network</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] text-slate-500">Send BTC</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-slate-500 group-hover:text-amber-500 transition-colors"></i>
                </div>
            </button>
            
            <button @click="transferType = 'ethereum'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-3 sm:p-4 hover:bg-indigo-500/5 hover:border-indigo-500/20 shadow-sm transition-all duration-200 text-left min-w-0 overflow-hidden">
                <div class="flex flex-col sm:flex-row items-center sm:items-center sm:space-x-3 mb-2.5">
                    <img src="https://assets.coingecko.com/coins/images/279/standard/ethereum.png?1696501628" alt="Ethereum" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full ring-2 ring-indigo-500/20 flex-shrink-0 mb-1.5 sm:mb-0">
                    <div class="min-w-0 text-center sm:text-left">
                        <p class="text-xs sm:text-sm font-semibold text-slate-900 truncate">Ethereum</p>
                        <p class="text-[10px] text-slate-500 truncate">ETH Network</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] text-slate-500">Send ETH</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-slate-500 group-hover:text-indigo-500 transition-colors"></i>
                </div>
            </button>
            
            <button @click="transferType = 'usdt'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-3 sm:p-4 hover:bg-emerald-500/5 hover:border-emerald-500/20 shadow-sm transition-all duration-200 text-left min-w-0 overflow-hidden">
                <div class="flex flex-col sm:flex-row items-center sm:items-center sm:space-x-3 mb-2.5">
                    <img src="https://assets.coingecko.com/coins/images/325/standard/Tether.png?1696501661" alt="USDT" class="w-8 h-8 sm:w-9 sm:h-9 rounded-full ring-2 ring-emerald-500/20 flex-shrink-0 mb-1.5 sm:mb-0">
                    <div class="min-w-0 text-center sm:text-left">
                        <p class="text-xs sm:text-sm font-semibold text-slate-900 truncate">Tether</p>
                        <p class="text-[10px] text-slate-500 truncate">USDT (TRC-20)</p>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] sm:text-[11px] text-slate-500">Send USDT</span>
                    <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 text-slate-500 group-hover:text-emerald-500 transition-colors"></i>
                </div>
            </button>
        </div>
    </div>
    @endif

    <!-- More Transfer Options -->
    <div class="hidden md:block">
        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">More Options</h3>
        <div class="grid grid-cols-4 gap-3">
            <button @click="transferType = 'googlepay'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/g.jfif') }}" alt="Google Pay" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">Google Pay</p>
                <p class="text-[11px] text-slate-500 leading-tight">Mobile payment</p>
            </button>
            
            <button @click="transferType = 'western'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/yu.png') }}" alt="Western Union" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">Western Union</p>
                <p class="text-[11px] text-slate-500 leading-tight">Cash pickup</p>
            </button>
            
            <button @click="transferType = 'wise'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/22.png') }}" alt="TransferWise" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">Wise</p>
                <p class="text-[11px] text-slate-500 leading-tight">Low-fee transfer</p>
            </button>
            
            <button @click="transferType = 'payoneer'; transferModal = true"
                    class="group bg-white border border-slate-200 rounded-xl p-4 hover:bg-primary-50 hover:border-primary-400 shadow-sm transition-all duration-200 text-left">
                <div class="w-11 h-11 rounded-xl overflow-hidden mb-3 ring-2 ring-slate-200 group-hover:ring-primary-400 transition-all">
                    <img src="{{ asset('dash2/app/assets/img/sample/brand/34.png') }}" alt="Payoneer" class="w-full h-full object-cover">
                </div>
                <p class="text-sm font-semibold text-slate-900 mb-0.5">Payoneer</p>
                <p class="text-[11px] text-slate-500 leading-tight">Business payments</p>
            </button>
        </div>
    </div>

    <!-- Virtual Cards Section -->
    @if($cards && $cards->count() > 0)
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Your Cards</h3>
            <a href="{{ route('cards.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium flex items-center space-x-1 transition-colors">
                <span>View all</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        
        <div class="space-y-3">
            @foreach($cards as $card)
            <!-- Professional Card Item -->
            <a href="{{ route('cards.view', $card->id) }}" 
               class="bg-white border border-slate-200 rounded-xl p-4 hover:bg-slate-100 hover:border-slate-300 shadow-sm transition-colors block group">
                <div class="flex items-center gap-4">
                    <!-- Card Visual -->
                    <div class="flex-shrink-0">
                        <div class="w-20 h-14 rounded-lg overflow-hidden relative"
                             style="background: linear-gradient(135deg, 
                                 @if($card->card_level === 'Black') #1a1a1a 0%, #000000 100%
                                 @elseif($card->card_level === 'Platinum') #4A5568 0%, #2D3748 100%
                                 @elseif($card->card_level === 'Gold') #D4AF37 0%, #B8860B 100%
                                 @else #6B7280 0%, #4B5563 100%
                                 @endif);">
                            <!-- Card Pattern -->
                            <div class="absolute inset-0 opacity-5">
                                <div class="absolute top-0 right-0 w-16 h-16 bg-white rounded-full transform translate-x-6 -translate-y-6"></div>
                                <div class="absolute bottom-0 left-0 w-12 h-12 bg-white rounded-full transform -translate-x-4 translate-y-4"></div>
                            </div>
                            
                            <!-- EMV Chip -->
                            <div class="absolute top-2 left-2 w-3.5 h-3 rounded-sm bg-amber-300 opacity-80"></div>
                            
                            <!-- Card Network Logo -->
                            <div class="absolute top-2 right-2">
                                @if($card->card_type === 'Visa')
                                <svg class="h-3 w-5 opacity-90" viewBox="0 0 1000 324" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M433.334 318.516H348.568L403.315 5.01855H488.081L433.334 318.516Z" fill="white"/>
                                    <path d="M727.98 15.9043C712.039 9.56622 686.227 2.51855 653.355 2.51855C574.054 2.51855 519.055 45.5277 518.802 106.826C518.306 152.343 558.126 177.912 587.98 193.316C618.34 209.233 629.315 219.335 629.315 233.014C629.061 254.008 603.493 263.591 579.702 263.591C547.839 263.591 530.7 258.287 505.123 245.892L493.902 240.335L482.166 313.221C501.069 323.07 536.096 331.667 572.643 332.163C657.14 332.163 711.128 289.897 711.895 224.144C712.394 188.508 689.871 160.445 643.015 136.91C615.2 121.744 598.306 111.638 598.554 97.2356C598.554 84.5987 613.224 70.9167 648.747 70.9167C677.823 70.4206 699.053 77.7188 715.707 85.5144L726.681 90.8186L738.417 19.9214L727.98 15.9043Z" fill="white"/>
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M841.897 5.01855H773.691C755.782 5.01855 742.397 10.0624 734.862 30.0238L630.236 318.516H715.464C715.464 318.516 729.143 280.806 732.429 272.002C742.901 272.002 822.706 272.002 835.844 272.002C838.373 283.215 845.93 318.516 845.93 318.516H922.667L841.897 5.01855ZM762.22 208.49C769.018 189.752 796.85 116.686 796.85 116.686C796.602 117.174 803.16 99.19 806.699 88.0899L812.02 114.188C812.02 114.188 829.775 192.026 833.061 208.49H762.22Z" fill="white"/>
                                    <path d="M251.994 5.01855L171.471 219.335L162.137 174.313C146.216 129.054 102.175 80.0894 52.8662 56.8035L126.462 318.02H212.215L336.832 5.01855H251.994Z" fill="white"/>
                                </svg>
                                @elseif($card->card_type === 'Mastercard')
                                <svg class="h-3 w-5 opacity-90" viewBox="0 0 131.39 86.9" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="44" cy="43.45" r="36" fill="#eb001b"/>
                                    <circle cx="87.39" cy="43.45" r="36" fill="#f79e1b"/>
                                    <path d="M65.7 15.14a35.94 35.94 0 000 56.61 35.94 35.94 0 000-56.61z" fill="#ff5f00"/>
                                </svg>
                                @elseif($card->card_type === 'American Express')
                                <div class="text-[6px] font-bold text-white opacity-90 leading-none">AMEX</div>
                                @elseif($card->card_type === 'Discover')
                                <div class="text-[6px] font-bold text-white opacity-90 leading-none">DISC</div>
                                @else
                                <div class="w-4 h-2.5 rounded bg-white/20"></div>
                                @endif
                            </div>
                            
                            <!-- Last 4 Digits -->
                            @if($card->status === 'active' && $card->last_four)
                            <p class="absolute bottom-1.5 left-2 text-white text-[9px] font-mono font-bold tracking-wider opacity-90">
                            *** {{ $card->last_four }}
                            </p>
                            @endif
                        </div>
                    </div>

                    <!-- Card Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <p class="font-semibold text-slate-900 text-sm truncate">{{ $card->card_type }} {{ $card->card_level }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold flex-shrink-0
                                @if($card->status === 'active') bg-emerald-500/20 text-emerald-600
                                @elseif($card->status === 'pending') bg-amber-500/20 text-amber-600
                                @elseif($card->status === 'blocked') bg-red-500/20 text-red-400
                                @else bg-gray-500/20 text-slate-600
                                @endif">
                                {{ ucfirst($card->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-slate-500 truncate">{{ $card->cardholder_name }}</p>
                    </div>

                    <!-- Balance & Action -->
                    <div class="flex items-center gap-3 flex-shrink-0">
                        @if($card->status === 'active')
                        <div class="text-right">
                            <p class="text-xs text-slate-500 mb-0.5">Balance</p>
                            <p class="text-lg font-bold text-slate-900">{{ $settings->currency }}{{ number_format($card->balance, 2) }}</p>
                        </div>
                        @elseif($card->status === 'pending')
                        <div class="text-right">
                            <div class="flex items-center text-amber-600 text-sm">
                                <i data-lucide="clock" class="w-4 h-4 mr-1"></i>
                                <span class="font-medium">Pending</span>
                            </div>
                        </div>
                        @endif
                        <i data-lucide="chevron-right" class="w-5 h-5 text-slate-500 group-hover:text-primary-500 transition-colors"></i>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white border border-slate-200 rounded-xl p-8 text-center shadow-sm">
        <div class="w-11 h-11 mx-auto mb-3 rounded-full bg-slate-200 flex items-center justify-center">
            <i data-lucide="credit-card" class="w-5 h-5 text-slate-500"></i>
        </div>
        <h3 class="text-sm font-semibold text-slate-900 mb-1">No Cards Yet</h3>
        <p class="text-slate-500 text-sm mb-4 max-w-md mx-auto">Apply for a virtual card to make secure online payments.</p>
        <a href="{{ route('cards.apply') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-primary-500 hover:bg-primary-600 rounded-lg text-sm font-medium text-white transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Apply for Card</span>
        </a>
    </div>
    @endif

    <!-- Recent Transactions -->
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider">Recent Transactions</h3>
            <a href="{{ route('accounthistory') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium flex items-center space-x-1 transition-colors">
                <span>View All</span>
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        
        <div class="space-y-3">
            @forelse ($withdrawals as $withdrawal)
                <a href="{{ route('transactiondetails', $withdrawal->id) }}" 
                   class="bg-white border border-slate-200 rounded-xl p-4 hover:bg-slate-100 hover:border-slate-300 shadow-sm transition-colors block">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <div class="w-10 h-10 rounded-lg 
                                @if(strtolower($withdrawal->type ?? 'debit') == 'credit')
                                    @if(strtolower($withdrawal->status) == 'processed' || strtolower($withdrawal->status) == 'approved') bg-success/20
                                    @elseif(strtolower($withdrawal->status) == 'pending') bg-warning/20
                                    @else bg-danger/20
                                    @endif
                                @else
                                    {{-- For Debits with recipient tracking, show primary-green when processed --}}
                                    @if(!empty($withdrawal->sender_bank) && (strtolower($withdrawal->status) == 'processed' || strtolower($withdrawal->status) == 'approved'))
                                        bg-primary-500/20
                                    @elseif(strtolower($withdrawal->status) == 'processed' || strtolower($withdrawal->status) == 'approved')
                                        bg-danger/20
                                    @elseif(strtolower($withdrawal->status) == 'pending')
                                        bg-warning/20
                                    @else
                                        bg-danger/20
                                    @endif
                                @endif 
                                flex items-center justify-center flex-shrink-0">
                                <i data-lucide="{{ strtolower($withdrawal->type ?? 'debit') == 'credit' ? 'arrow-down-left' : 'arrow-up-right' }}" class="w-5 h-5 
                                    @if(strtolower($withdrawal->type ?? 'debit') == 'credit')
                                        @if(strtolower($withdrawal->status) == 'processed' || strtolower($withdrawal->status) == 'approved') text-success
                                        @elseif(strtolower($withdrawal->status) == 'pending') text-warning
                                        @else text-danger
                                        @endif
                                    @else
                                        {{-- For Debits with recipient tracking, show primary-green when processed --}}
                                        @if(!empty($withdrawal->sender_bank) && (strtolower($withdrawal->status) == 'processed' || strtolower($withdrawal->status) == 'approved'))
                                            text-primary-500
                                        @elseif(strtolower($withdrawal->status) == 'processed' || strtolower($withdrawal->status) == 'approved')
                                            text-danger
                                        @elseif(strtolower($withdrawal->status) == 'pending')
                                            text-warning
                                        @else
                                            text-danger
                                        @endif
                                    @endif"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                    @php
                                        $wStatus = strtolower($withdrawal->status ?? '');
                                        $isStatusLabel = in_array($wStatus, ['failed', 'pending', 'error'], true);
                                    @endphp
                                    <span class="text-xs px-2 py-0.5 rounded-full mr-1 font-semibold
                                        @if($isStatusLabel)
                                            @if($wStatus === 'pending') bg-warning/20 text-warning
                                            @else bg-danger/20 text-danger
                                            @endif
                                        @elseif(strtolower($withdrawal->type ?? 'debit') == 'credit') bg-success/20 text-success
                                        @else bg-danger/20 text-danger
                                        @endif">
                                        @if($isStatusLabel)
                                            {{ ucfirst($withdrawal->status) }}
                                        @else
                                            {{ ucfirst($withdrawal->type ?? 'Debit') }}
                                        @endif
                                    </span>
                                    <p class="font-semibold truncate">
                                        {{ $withdrawal->payment_mode ?? 'Transfer' }}
                                    </p>
                                    
                                    {{-- Show party bank for both Credits and Debits with tracking --}}
                                    @if(!empty($withdrawal->crypto_wallet_address))
                                        {{-- Crypto transaction: show wallet address --}}
                                        <span class="text-xs text-slate-500">{{ strtolower($withdrawal->type ?? 'debit') == 'credit' ? 'from' : 'to' }}</span>
                                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-500/10 border border-amber-500/20">
                                            <i data-lucide="link" class="w-3 h-3 text-amber-400"></i>
                                            <p class="text-xs font-mono text-amber-600 truncate" style="max-width: 120px;" title="{{ $withdrawal->crypto_wallet_address }}">{{ $withdrawal->crypto_wallet_address }}</p>
                                        </div>
                                        @if(!empty($withdrawal->crypto_txn_hash))
                                            <span class="hidden sm:inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-mono bg-amber-500/10 text-amber-400 border border-amber-500/15 truncate" style="max-width: 100px;" title="{{ $withdrawal->crypto_txn_hash }}">
                                                {{ $withdrawal->crypto_txn_hash }}
                                            </span>
                                        @endif
                                    @elseif(!empty($withdrawal->sender_bank))
                                        <span class="text-xs text-slate-500">{{ strtolower($withdrawal->type ?? 'debit') == 'credit' ? 'from' : 'to' }}</span>
                                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-primary-500/10 border border-primary-500/20">
                                            <i data-lucide="landmark" class="w-3 h-3 text-primary-500"></i>
                                            <p class="text-xs font-medium text-primary-500">{{ $withdrawal->sender_bank }}</p>
                                        </div>
                                        @if(!empty($withdrawal->reference_number))
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-mono bg-amber-500/10 text-amber-400 border border-amber-500/15">
                                                {{ $withdrawal->reference_number }}
                                            </span>
                                        @endif
                                    @elseif($withdrawal->accountname)
                                        <span class="text-xs text-slate-500">?</span>
                                        <p class="text-sm text-slate-500 truncate">{{ $withdrawal->accountname }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                                    <span class="flex items-center">
                                        <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>
                                        {{ $withdrawal->created_at ? $withdrawal->created_at->format('M d, Y') : 'N/A' }}
                                    </span>
                                    @if($withdrawal->txn_id)
                                        <span class="flex items-center truncate">
                                            <i data-lucide="hash" class="w-3 h-3 mr-1 flex-shrink-0"></i>
                                            <span class="truncate">{{ $withdrawal->txn_id }}</span>
                                        </span>
                                    @endif
                                    {{-- Show party name for verified transactions (sender for Credits, recipient for Debits) --}}
                                    @if(!empty($withdrawal->sender_name))
                                        <span class="hidden sm:flex items-center gap-1">
                                            <i data-lucide="user-check" class="w-3 h-3 text-primary-500"></i>
                                            <span class="text-primary-500">{{ $withdrawal->sender_name }}</span>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            @php
                                $wStatusAmt = strtolower($withdrawal->status ?? '');
                                $isFailLike = in_array($wStatusAmt, ['failed', 'error', 'rejected'], true);
                                $isPendLike = in_array($wStatusAmt, ['pending', 'on-hold', 'scheduled', 'under review'], true);
                            @endphp
                            <p class="font-bold text-base mb-1 whitespace-nowrap
                                @if($isFailLike) text-danger
                                @elseif($isPendLike) text-warning
                                @elseif(strtolower($withdrawal->type ?? 'debit') == 'credit') text-success
                                @else text-slate-900
                                @endif">
                                @if($isFailLike || $isPendLike)
                                    {{-- status transactions: no +/- success styling --}}
                                    @if($withdrawal->crypto_currency){{ number_format($withdrawal->amount, 8, '.', ',') }} {{ strtoupper($withdrawal->crypto_currency) }}@else{{ Auth::user()->currency }}{{ number_format($withdrawal->amount, 2, '.', ',') }}@endif
                                @else
                                    {{ strtolower($withdrawal->type ?? 'debit') == 'credit' ? '+' : '-' }}@if($withdrawal->crypto_currency){{ number_format($withdrawal->amount, 8, '.', ',') }} {{ strtoupper($withdrawal->crypto_currency) }}@else{{ Auth::user()->currency }}{{ number_format($withdrawal->amount, 2, '.', ',') }}@endif
                                @endif
                            </p>
                            @unless($isFailLike || $isPendLike)
                            <span class="text-xs px-2 py-1 rounded-full
                                @if(strtolower($withdrawal->status) == 'processed' || strtolower($withdrawal->status) == 'approved') bg-success/20 text-success
                                @elseif(in_array(strtolower($withdrawal->status), ['pending', 'on-hold', 'scheduled', 'under review'])) bg-warning/20 text-warning
                                @elseif(in_array(strtolower($withdrawal->status), ['reversed', 'refunded'])) bg-blue-500/20 text-blue-400
                                @elseif(strtolower($withdrawal->status) == 'cancelled') bg-gray-500/20 text-slate-500
                                @elseif(in_array(strtolower($withdrawal->status), ['failed', 'error', 'rejected', 'expired', 'frozen'])) bg-red-500/20 text-red-400
                                @else bg-danger/20 text-danger
                                @endif">
                                {{ ucfirst($withdrawal->status) }}
                            </span>
                            @endunless
                        </div>
                    </div>
                </a>
            @empty
                <div class="bg-white border border-slate-200 rounded-xl p-8 text-center shadow-sm">
                    <i data-lucide="inbox" class="w-10 h-10 mx-auto mb-3 text-slate-500"></i>
                    <p class="text-slate-500 text-sm">No transactions yet</p>
                </div>
            @endforelse

            @foreach ($deposits as $deposit)
                <a href="{{ route('depositdetails', $deposit->id) }}" 
                   class="bg-white border border-slate-200 rounded-xl p-4 hover:bg-slate-100 hover:border-slate-300 shadow-sm transition-colors block">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 rounded-lg bg-primary-500/20 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="arrow-down-left" class="w-5 h-5 text-primary-500"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Deposit</p>
                                <p class="text-xs text-slate-500">{{ $deposit->created_at ? $deposit->created_at->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-base text-success mb-1">
                                +{{ Auth::user()->currency }}{{ number_format($deposit->amount, 2, '.', ',') }}
                            </p>
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($deposit->status == 'approved') bg-success/20 text-success
                                @elseif($deposit->status == 'pending') bg-warning/20 text-warning
                                @else bg-danger/20 text-danger
                                @endif">
                                {{ ucfirst($deposit->status) }}
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Professional Banking Deposit Modal -->
<div x-show="depositModal" 
     x-data="{
         amount: '',
         selectedMethod: '',
         walletAddress: '',
         proofUploaded: false,
         formStep: 1,
         formatAmount(value) {
             if (!value) return '';
             return parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
         }
     }"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="depositModal = false"
     class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="depositModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         class="bg-white rounded-xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="sticky top-0 bg-white border-b border-slate-200 z-10">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center">
                        <span class="w-9 h-9 rounded-lg bg-primary-100 flex items-center justify-center mr-3">
                            <i data-lucide="arrow-down-to-line" class="w-4.5 h-4.5 text-primary-500"></i>
                        </span>
                        Fund Account
                    </h3>
                    <p class="text-sm text-slate-500 ml-12">Secure cryptocurrency deposit</p>
                </div>
                <button @click="depositModal = false" class="p-2 rounded-lg hover:bg-slate-200 transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-500"></i>
                </button>
            </div>
            <!-- Progress Steps -->
            <div class="flex items-center px-6 pb-4 space-x-2">
                <div :class="formStep >= 1 ? 'bg-primary-500' : 'bg-slate-200'" class="flex-1 h-1 rounded-full transition-all"></div>
                <div :class="formStep >= 2 ? 'bg-primary-500' : 'bg-slate-200'" class="flex-1 h-1 rounded-full transition-all"></div>
                <div :class="formStep >= 3 ? 'bg-primary-500' : 'bg-slate-200'" class="flex-1 h-1 rounded-full transition-all"></div>
            </div>
        </div>
        
        <form method="post" action="{{ route('savedeposit') }}" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            
            <!-- Step 1: Payment Method Selection -->
            <div x-show="formStep === 1" x-transition class="space-y-4">
                <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4 mb-4">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="info" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                        <div class="text-sm text-slate-600">
                            <p class="font-semibold text-amber-600 mb-1">Deposit Instructions</p>
                            <p class="text-xs">Select your preferred cryptocurrency and follow the wallet address to complete your deposit.</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-3 text-slate-900">Select Cryptocurrency Network</label>
                    <select name="payment_method" id="selectOption" required
                            x-model="selectedMethod"
                            @change="walletAddress = $event.target.selectedOptions[0].dataset.description; formStep = 2"
                            class="w-full px-4 py-3.5 rounded-xl bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 transition-all text-slate-900 appearance-none cursor-pointer hover:border-primary-500/50">
                        <option value="" disabled>Choose your network...</option>
                        @foreach ($dmethods as $method)
                            <option value="{{ $method->name }}" data-description="{{ $method->wallet_address }}">
                                {{ $method->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Step 2: Wallet & Amount -->
            <div x-show="formStep === 2" x-transition class="space-y-5">
                <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-primary-500">Deposit Wallet Address</label>
                    <div class="relative">
                        <input type="text" id="inputField" readonly :value="walletAddress"
                               class="w-full px-4 py-3 pr-24 rounded-lg bg-white border border-primary-400 text-slate-900 text-sm font-mono">
                        <button type="button" onclick="copyToClipboard()" 
                                class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-amber-500/15 hover:bg-amber-500/20 rounded-lg transition-all group flex items-center space-x-1">
                            <i data-lucide="copy" class="w-3.5 h-3.5 text-amber-400"></i>
                            <span class="text-xs font-semibold text-amber-600">Copy</span>
                        </button>
                    </div>
                    <p class="text-xs text-slate-500 mt-2 flex items-center">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1 text-primary-500"></i>
                        Send only <span x-text="selectedMethod" class="font-semibold mx-1"></span> to this address
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-slate-900">Deposit Amount</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                        <input type="number" name="amount" required
                               x-model="amount"
                               @input="formStep = amount > 0 ? 2 : 2"
                               step="0.01" min="1"
                               class="w-full px-4 py-4 pl-14 pr-24 rounded-xl bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 transition-all text-slate-900 text-lg font-bold"
                               placeholder="0.00">
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">
                            <span x-show="amount" x-text="'� ' + formatAmount(amount)"></span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs">
                        <span class="text-slate-500">Minimum: {{ Auth::user()->currency }}10.00</span>
                        <button type="button" @click="amount = {{ Auth::user()->account_bal }}"
                                class="text-amber-400 hover:text-amber-600 font-semibold transition-colors">Use max balance</button>
                    </div>
                    <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                        Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                    </p>
                </div>

                <button type="button" @click="formStep = 3" :disabled="!amount || amount < 1 || parseFloat(amount) > maxBal"
                        class="w-full px-6 py-3.5 rounded-xl bg-primary-50 hover:bg-primary-100 border-2 border-primary-400 transition-all font-semibold text-primary-600 disabled:opacity-50 disabled:cursor-not-allowed">
                    Continue to Upload Proof
                </button>
            </div>

            <!-- Step 3: Proof Upload & Submit -->
            <div x-show="formStep === 3" x-transition class="space-y-5">
                <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-semibold text-slate-600">Transaction Summary</span>
                        <button type="button" @click="formStep = 2" class="text-xs text-amber-400 hover:text-amber-600">Edit</button>
                    </div>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Network:</span>
                            <span class="font-semibold text-slate-900" x-text="selectedMethod"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Amount:</span>
                            <span class="font-bold text-lg text-primary-500">{{ Auth::user()->currency }}<span x-text="formatAmount(amount)"></span></span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-3 text-slate-900">Upload Payment Confirmation</label>
                    <div class="relative border-2 border-dashed border-slate-200 hover:border-primary-500/50 rounded-xl transition-all">
                        <input type="file" name="proof" required accept="image/*" id="proofUpload"
                               @change="proofUploaded = $event.target.files.length > 0"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-slate-200 flex items-center justify-center">
                                <i :data-lucide="proofUploaded ? 'check-circle' : 'upload'" 
                                   :class="proofUploaded ? 'text-primary-500' : 'text-slate-500'" 
                                   class="w-8 h-8"></i>
                            </div>
                            <p class="text-sm font-semibold text-slate-600 mb-1" x-text="proofUploaded ? 'Proof uploaded successfully' : 'Click to upload proof'"></p>
                            <p class="text-xs text-slate-500">PNG, JPG up to 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-xs text-slate-600">Please ensure your transaction screenshot clearly shows the amount, date, and transaction ID for faster processing.</p>
                    </div>
                </div>

                <button type="submit" :disabled="!proofUploaded"
                        class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                    <i data-lucide="send" class="w-5 h-5"></i>
                    <span>Submit Deposit Request</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modern Transfer Modal -->
<div x-show="transferModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="transferModal = false"
     class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="transferModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         class="bg-white rounded-xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden max-h-[90vh] overflow-y-auto">
        
        <div class="sticky top-0 bg-white border-b border-slate-200 z-10">
            <div class="p-5 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 flex items-center">
                        <span class="w-9 h-9 rounded-lg bg-primary-100 flex items-center justify-center mr-3">
                            <i data-lucide="arrow-right-left" class="w-4.5 h-4.5 text-primary-500"></i>
                        </span>
                        <span x-text="'Send Money via ' + transferType.charAt(0).toUpperCase() + transferType.slice(1)"></span>
                    </h3>
                    <p class="text-sm text-slate-500 ml-12">Secure international transfer</p>
                </div>
                <button @click="transferModal = false" class="p-2 rounded-lg hover:bg-slate-200 transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-slate-500"></i>
                </button>
            </div>
        </div>
        
        <!-- Wire Transfer Form with Banking UX -->
        <form x-show="transferType === 'wire'" 
              x-data="{
                  amount: '',
                  pin: '',
                  showPin: false,
                  accountValid: false,
                  maxBal: {{ Auth::user()->account_bal }},
                  formatIBAN(value) {
                      return value.replace(/(.{4})/g, '$1 ').trim();
                  }
              }"
              method="post" action="{{ route('internationaltransfer') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Wire Transfer">
            
            <!-- Beneficiary Details Section -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="user" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Beneficiary Information
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Account Holder Name</label>
                        <input type="text" name="accountname" required
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="John Smith">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Account Number</label>
                            <input type="text" name="accountnumber" required
                                   @input="accountValid = $event.target.value.length >= 8"
                                   class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all font-mono"
                                   placeholder="123456789">
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">IBAN</label>
                            <input type="text" name="iban" required
                                   class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all font-mono text-sm"
                                   placeholder="GB29NWBK60161331926819">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Details Section -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="building-2" class="w-4 h-4 mr-2 text-amber-400"></i>
                    Bank Details
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Bank Name</label>
                        <input type="text" name="bankname" required
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="Bank of America">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">SWIFT / BIC Code</label>
                            <input type="text" name="swift" required
                                   class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all font-mono uppercase"
                                   placeholder="BOFAUS3N">
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Country</label>
                            <input type="text" name="country" required
                                   class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                                   placeholder="United States">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Bank Address</label>
                        <input type="text" name="address" required
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="100 N Tryon St, Charlotte, NC 28202">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Account Type</label>
                        <select name="Accounttype" required
                                class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all appearance-none cursor-pointer">
                            <option value="">Select account type...</option>
                            <option value="Checking">Checking Account</option>
                            <option value="Savings Account">Savings Account</option>
                            <option value="Current">Current Account</option>
                            <option value="Online Banking">Online Banking</option>
                            <option value="Joint Account">Joint Account</option>
                            <option value="Corporate">Corporate Account</option>
                            <option value="Offshore">Offshore Account</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Transfer Details Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="banknote" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                            <input type="number" name="amount" required
                                   x-model="amount"
                                   step="0.01" min="1"
                                   class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                                   placeholder="0.00">
                        </div>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                            <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Processing time: 1-3 business days</span>
                        </div>
                        <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                            Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Transfer Purpose <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <textarea name="description" rows="2"
                                  class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all resize-none"
                                  placeholder="Invoice payment, personal transfer, etc."></textarea>
                    </div>
                </div>
            </div>

            <!-- Security PIN Section -->
            <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Secure Transaction</h4>
                        <p class="text-xs text-slate-500">Enter your PIN to authorize this transfer</p>
                    </div>
                </div>
                <div class="relative">
                    <input :type="showPin ? 'text' : 'password'" name="pin" required
                           x-model="pin"
                           maxlength="6"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-white border-2 border-amber-400 focus:border-amber-500 focus:ring-0 text-slate-900 transition-all font-mono text-center text-lg tracking-widest"
                           placeholder="� � � � � �">
                    <button type="button" @click="showPin = !showPin"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-400 transition-colors">
                        <i :data-lucide="showPin ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Submit Button with Security Indicator -->
            <button type="submit" :disabled="!amount || !pin || pin.length < 4 || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="lock" class="w-5 h-5"></i>
                <span>Authorize & Send Money</span>
            </button>
            
            <p class="text-xs text-center text-slate-500">
                <i data-lucide="shield" class="w-3 h-3 inline mr-1"></i>
                Your transaction is secured with bank-grade encryption
            </p>
        </form>

        <!-- Local Transfer Form -->
        <form x-show="transferType === 'local'" 
              x-data="{ amount: '', pin: '', showPin: false, maxBal: {{ Auth::user()->account_bal }} }"
              method="post" action="{{ route('localtransfer') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="local Transfer">
            
            <!-- Beneficiary Details -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="user-check" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Beneficiary Information
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Bank Name</label>
                        <input type="text" name="bankname" required
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="Select your bank">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Account Holder Name</label>
                        <input type="text" name="accountname" required
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="Full name as per bank records">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Account Number</label>
                            <input type="text" name="accountnumber" required
                                   class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all font-mono"
                                   placeholder="123456789">
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Account Type</label>
                            <select name="Accounttype" required
                                    class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all appearance-none cursor-pointer">
                                <option value="">Select...</option>
                                <option value="Checking">Checking</option>
                                <option value="Savings Account">Savings</option>
                                <option value="Current">Current</option>
                                <option value="Online Banking">Online</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transfer Amount -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="banknote" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Details
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Transfer</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                            <input type="number" name="amount" required
                                   x-model="amount"
                                   step="0.01" min="1"
                                   class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                                   placeholder="0.00">
                        </div>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                            <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Instant transfer</span>
                        </div>
                        <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                            Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Purpose of Transfer <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <textarea name="description" rows="2"
                                  class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all resize-none"
                                  placeholder="Payment description or reference"></textarea>
                    </div>
                </div>
            </div>

            <!-- Security PIN -->
            <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Secure Transaction</h4>
                        <p class="text-xs text-slate-500">Enter your PIN to authorize this transfer</p>
                    </div>
                </div>
                <div class="relative">
                    <input :type="showPin ? 'text' : 'password'" name="pin" required
                           x-model="pin"
                           maxlength="6"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-white border-2 border-amber-400 focus:border-amber-500 focus:ring-0 text-slate-900 transition-all font-mono text-center text-lg tracking-widest"
                           placeholder="� � � � � �">
                    <button type="button" @click="showPin = !showPin"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-400 transition-colors">
                        <i :data-lucide="showPin ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !pin || pin.length < 4 || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send Money</span>
            </button>
            
            <p class="text-xs text-center text-slate-500">
                <i data-lucide="zap" class="w-3 h-3 inline mr-1"></i>
                Instant domestic transfer
            </p>
        </form>

        <!-- PayPal Transfer Form -->
        <form x-show="transferType === 'paypal'" 
              x-data="{ amount: '', email: '', maxBal: {{ Auth::user()->account_bal }} }"
              method="post" action="{{ route('paypal') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Paypal">
            
            <!-- Recipient Details -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="mail" class="w-4 h-4 mr-2 text-blue-400"></i>
                    PayPal Recipient
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">PayPal Email Address</label>
                    <input type="email" name="email" required
                           x-model="email"
                           class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                           placeholder="recipient@example.com">
                    <p class="text-xs text-slate-500 mt-2">Enter the recipient's PayPal email address</p>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="dollar-sign" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                        <input type="number" name="amount" required
                               x-model="amount"
                               step="0.01" min="1"
                               class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                               placeholder="0.00">
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs">
                        <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                        <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Fee: {{ Auth::user()->currency }}0.00</span>
                    </div>
                    <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                        Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                    </p>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-blue-500/5 border border-blue-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-slate-600">Funds will be sent directly to the recipient's PayPal account. Processing typically takes a few minutes.</p>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !email || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send to PayPal</span>
            </button>
        </form>

        <!-- Skrill Form -->
        <form x-show="transferType === 'skrill'" 
              x-data="{ amount: '', email: '', fullname: '', maxBal: {{ Auth::user()->account_bal }} }"
              method="post" action="{{ route('skrill') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Skrill">
            
            <!-- Recipient Details -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="user-check" class="w-4 h-4 mr-2 text-purple-400"></i>
                    Skrill Recipient
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Full Name</label>
                        <input type="text" name="fullname" required
                               x-model="fullname"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="Recipient's full name">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Skrill Email Address</label>
                        <input type="email" name="email" required
                               x-model="email"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="recipient@example.com">
                        <p class="text-xs text-slate-500 mt-2">Email linked to Skrill account</p>
                    </div>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="banknote" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                        <input type="number" name="amount" required
                               x-model="amount"
                               step="0.01" min="1"
                               class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                               placeholder="0.00">
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs">
                        <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                        <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Processing: Instant</span>
                    </div>
                    <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                        Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                    </p>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-purple-500/5 border border-purple-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="info" class="w-5 h-5 text-purple-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-slate-600">Transfer will be processed instantly to the recipient's Skrill account.</p>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !email || !fullname || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send to Skrill</span>
            </button>
        </form>

        <!-- Google Pay Form -->
        <form x-show="transferType === 'googlepay'" 
              x-data="{ amount: '', contact: '', maxBal: {{ Auth::user()->account_bal }} }"
              method="post" action="{{ route('googlepay') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Google Pay">
            
            <!-- Recipient Details -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="smartphone" class="w-4 h-4 mr-2 text-green-400"></i>
                    Google Pay Recipient
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Email or Phone Number</label>
                    <input type="text" name="email" required
                           x-model="contact"
                           class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                           placeholder="email@example.com or +1234567890">
                    <p class="text-xs text-slate-500 mt-2">Enter recipient's Google Pay email or mobile number</p>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="credit-card" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Payment Amount
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                        <input type="number" name="amount" required
                               x-model="amount"
                               step="0.01" min="1"
                               class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                               placeholder="0.00">
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs">
                        <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                        <span class="text-green-400 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">? Instant transfer</span>
                    </div>
                    <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                        Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                    </p>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-green-500/5 border border-green-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="zap" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-slate-600">Lightning-fast transfer directly to Google Pay. No fees, instant delivery.</p>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !contact || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send via Google Pay</span>
            </button>
        </form>

        <!-- Western Union Form -->
        <form x-show="transferType === 'western'" 
              x-data="{ amount: '', name: '', country: '', maxBal: {{ Auth::user()->account_bal }} }"
              method="post" action="{{ route('westernunion') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Western Union">
            
            <!-- Recipient Details -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="user" class="w-4 h-4 mr-2 text-yellow-400"></i>
                    Recipient Information
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Full Name</label>
                        <input type="text" name="name" required
                               x-model="name"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="First and last name">
                        <p class="text-xs text-slate-500 mt-1">Name as it appears on recipient's ID</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Country</label>
                            <input type="text" name="country" required
                                   x-model="country"
                                   class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                                   placeholder="Destination country">
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">City</label>
                            <input type="text" name="city" required
                                   class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                                   placeholder="Pickup city">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Postal / Zip Code</label>
                        <input type="text" name="zipcode" required
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="Postal code">
                    </div>
                </div>
            </div>

            <!-- Transfer Amount -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="banknote" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Details
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                            <input type="number" name="amount" required
                                   x-model="amount"
                                   step="0.01" min="1"
                                   class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                                   placeholder="0.00">
                        </div>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                            <span class="text-yellow-400 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Cash pickup available</span>
                        </div>
                        <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                            Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Transfer Purpose (Optional)</label>
                        <textarea name="description" rows="2"
                                  class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all resize-none"
                                  placeholder="Family support, business payment, etc."></textarea>
                    </div>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-yellow-500/5 border border-yellow-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="clock" class="w-5 h-5 text-yellow-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-xs font-semibold text-slate-900 mb-1">Ready for pickup in minutes</p>
                        <p class="text-xs text-slate-500">Recipient can collect cash at any Western Union location with valid ID.</p>
                    </div>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !name || !country || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send via Western Union</span>
            </button>
        </form>

        <!-- TransferWise/Wise Form -->
        <form x-show="transferType === 'wise'" 
              x-data="{ amount: '', email: '', maxBal: {{ Auth::user()->account_bal }} }"
              method="post" action="{{ route('transferwise') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Transfer Wise">
            
            <!-- Recipient Details -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="globe" class="w-4 h-4 mr-2 text-teal-400"></i>
                    Wise Recipient
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Recipient Email</label>
                    <input type="email" name="email" required
                           x-model="email"
                           class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                           placeholder="recipient@example.com">
                    <p class="text-xs text-slate-500 mt-2">Email address linked to Wise account</p>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="trending-up" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                        <input type="number" name="amount" required
                               x-model="amount"
                               step="0.01" min="1"
                               class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                               placeholder="0.00">
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs">
                        <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                        <span class="text-teal-400 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Low-cost transfer</span>
                    </div>
                    <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                        Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                    </p>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-teal-500/5 border border-teal-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-teal-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-xs font-semibold text-slate-900 mb-1">International transfers made easy</p>
                        <p class="text-xs text-slate-500">Fast, transparent pricing with real exchange rates. Usually arrives within 1-2 business days.</p>
                    </div>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !email || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send via Wise</span>
            </button>
        </form>

        <!-- Payoneer Form -->
        <form x-show="transferType === 'payoneer'" 
              x-data="{ amount: '', email: '', maxBal: {{ Auth::user()->account_bal }} }"
              method="post" action="{{ route('payoneer') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Payoneer">
            
            <!-- Recipient Details -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="briefcase" class="w-4 h-4 mr-2 text-orange-400"></i>
                    Payoneer Recipient
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Payoneer Email</label>
                    <input type="email" name="email" required
                           x-model="email"
                           class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                           placeholder="recipient@example.com">
                    <p class="text-xs text-slate-500 mt-2">Email address associated with Payoneer account</p>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="wallet" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Payment Amount
                </h4>
                
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                        <input type="number" name="amount" required
                               x-model="amount"
                               step="0.01" min="1"
                               class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                               placeholder="0.00">
                    </div>
                    <div class="flex items-center justify-between mt-2 text-xs">
                        <span class="text-slate-500">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                        <span class="text-orange-400 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Business payment</span>
                    </div>
                    <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                        <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                        Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                    </p>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-orange-500/5 border border-orange-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="award" class="w-5 h-5 text-orange-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-xs font-semibold text-slate-900 mb-1">Professional payment platform</p>
                        <p class="text-xs text-slate-500">Secure transfer to Payoneer account. Ideal for freelancer and business payments.</p>
                    </div>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !email || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send via Payoneer</span>
            </button>
        </form>

        <!-- Bitcoin Transfer Form -->
        <form x-show="transferType === 'bitcoin'" 
              x-data="{ amount: '', walletAddress: '', pin: '', showPin: false, maxBal: {{ $cryptoAccount->btc ?? 0 }} }"
              method="post" action="{{ route('cryptocurrency') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Bitcoin">
            
            <!-- Recipient Wallet -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-primary-500" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M23.638 14.904c-1.602 6.43-8.113 10.34-14.542 8.736C2.67 22.05-1.244 15.525.362 9.105 1.962 2.67 8.475-1.243 14.9.358c6.43 1.605 10.342 8.115 8.738 14.548v-.002zm-6.35-4.613c.24-1.59-.974-2.45-2.64-3.03l.54-2.153-1.315-.33-.525 2.107c-.345-.087-.705-.167-1.064-.25l.526-2.127-1.32-.33-.54 2.165c-.285-.067-.565-.132-.84-.2l-1.815-.45-.35 1.407s.975.225.955.236c.535.136.63.486.615.766l-1.477 5.92c-.075.166-.24.406-.614.314.015.02-.96-.24-.96-.24l-.66 1.51 1.71.426.93.242-.54 2.19 1.32.327.54-2.17c.36.1.705.19 1.05.273l-.51 2.154 1.32.33.545-2.19c2.24.427 3.93.257 4.64-1.774.57-1.637-.03-2.58-1.217-3.196.854-.193 1.5-.76 1.68-1.93h.01zm-3.01 4.22c-.404 1.64-3.157.75-4.05.53l.72-2.9c.896.23 3.757.67 3.33 2.37zm.41-4.24c-.37 1.49-2.662.735-3.405.55l.654-2.64c.744.18 3.137.524 2.75 2.084v.006z"/>
                    </svg>
                    Bitcoin Recipient
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Recipient's Bitcoin Wallet Address</label>
                        <input type="text" name="wallet" required
                               x-model="walletAddress"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all font-mono text-sm"
                               placeholder="Enter wallet address">
                        <p class="text-xs text-slate-500 mt-2 flex items-center">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 text-primary-500"></i>
                            Ensure the address is correct. Bitcoin transactions cannot be reversed.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Recipient Name <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <input type="text" name="accountname"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="John Doe">
                    </div>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="coins" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send (BTC)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg font-bold text-primary-500">BTC</span>
                            <input type="number" name="amount" required
                                   x-model="amount"
                                   step="0.00000001" min="0.00000001"
                                   max="{{ $cryptoAccount->btc ?? 0 }}"
                                   class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                                   placeholder="0.00000000">
                        </div>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <span class="text-slate-500">Available: {{ number_format($cryptoAccount->btc ?? 0, 8) }} BTC</span>
                            <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Network: BTC</span>
                        </div>
                        <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                            Insufficient BTC balance. You only have {{ number_format($cryptoAccount->btc ?? 0, 8) }} BTC available.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Transfer Note <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <textarea name="description" rows="2"
                                  class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all resize-none"
                                  placeholder="Payment reference or note"></textarea>
                    </div>
                </div>
            </div>

            <!-- Security Warning -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="shield-alert" class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-xs font-semibold text-slate-900 mb-1">Important Security Notice</p>
                        <p class="text-xs text-slate-500">Double-check the wallet address. Cryptocurrency transfers are irreversible and cannot be refunded once confirmed.</p>
                    </div>
                </div>
            </div>

            <!-- Security PIN -->
            <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Secure Transaction</h4>
                        <p class="text-xs text-slate-500">Enter your PIN to authorize this transfer</p>
                    </div>
                </div>
                <div class="relative">
                    <input :type="showPin ? 'text' : 'password'" name="pin" required
                           x-model="pin"
                           maxlength="6"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-white border-2 border-amber-400 focus:border-amber-500 focus:ring-0 text-slate-900 transition-all font-mono text-center text-lg tracking-widest"
                           placeholder="� � � � � �">
                    <button type="button" @click="showPin = !showPin"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-400 transition-colors">
                        <i :data-lucide="showPin ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !walletAddress || !pin || pin.length < 4 || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send Bitcoin</span>
            </button>
        </form>

        <!-- Ethereum Transfer Form -->
        <form x-show="transferType === 'ethereum'" 
              x-data="{ amount: '', walletAddress: '', pin: '', showPin: false, maxBal: {{ $cryptoAccount->eth ?? 0 }} }"
              method="post" action="{{ route('cryptocurrency') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="Ethereum">
            
            <!-- Recipient Wallet -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-primary-500" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M15.927 23.959l-9.823-5.797 9.817 13.839 9.828-13.839-9.828 5.797zM16.073 0l-9.819 16.297 9.819 5.807 9.823-5.801z"/>
                    </svg>
                    Ethereum Recipient
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Recipient's Ethereum Wallet Address</label>
                        <input type="text" name="wallet" required
                               x-model="walletAddress"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all font-mono text-sm"
                               placeholder="Enter wallet address">
                        <p class="text-xs text-slate-500 mt-2 flex items-center">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 text-primary-500"></i>
                            Verify the address carefully. ETH transactions are irreversible.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Recipient Name <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <input type="text" name="accountname"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="Jane Smith">
                    </div>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="coins" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send (ETH)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg font-bold text-primary-500">ETH</span>
                            <input type="number" name="amount" required
                                   x-model="amount"
                                   step="0.00000001" min="0.00000001"
                                   max="{{ $cryptoAccount->eth ?? 0 }}"
                                   class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                                   placeholder="0.00000000">
                        </div>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <span class="text-slate-500">Available: {{ number_format($cryptoAccount->eth ?? 0, 8) }} ETH</span>
                            <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Network: ERC-20</span>
                        </div>
                        <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                            Insufficient ETH balance. You only have {{ number_format($cryptoAccount->eth ?? 0, 8) }} ETH available.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Transfer Note <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <textarea name="description" rows="2"
                                  class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all resize-none"
                                  placeholder="Payment reference or note"></textarea>
                    </div>
                </div>
            </div>

            <!-- Gas Fee Notice -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="zap" class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-xs font-semibold text-slate-900 mb-1">Network Gas Fees Apply</p>
                        <p class="text-xs text-slate-500">Ethereum network fees will be calculated and displayed before final confirmation. Transfers are irreversible.</p>
                    </div>
                </div>
            </div>

            <!-- Security PIN -->
            <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Secure Transaction</h4>
                        <p class="text-xs text-slate-500">Enter your PIN to authorize this transfer</p>
                    </div>
                </div>
                <div class="relative">
                    <input :type="showPin ? 'text' : 'password'" name="pin" required
                           x-model="pin"
                           maxlength="6"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-white border-2 border-amber-400 focus:border-amber-500 focus:ring-0 text-slate-900 transition-all font-mono text-center text-lg tracking-widest"
                           placeholder="� � � � � �">
                    <button type="button" @click="showPin = !showPin"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-400 transition-colors">
                        <i :data-lucide="showPin ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !walletAddress || !pin || pin.length < 4 || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send Ethereum</span>
            </button>
        </form>

        <!-- USDT Transfer Form -->
        <form x-show="transferType === 'usdt'" 
              x-data="{ amount: '', walletAddress: '', network: 'TRC20', pin: '', showPin: false, maxBal: {{ $cryptoAccount->usdt ?? 0 }} }"
              method="post" action="{{ route('cryptocurrency') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="method" value="USDT">
            
            <!-- Network Selection -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="network" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Select Network
                </h4>
                
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="network = 'TRC20'"
                            :class="network === 'TRC20' ? 'bg-primary-500/20 border-primary-500/40' : 'bg-white border-slate-200'"
                            class="p-3 rounded-lg border-2 transition-all text-left">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900">TRC20</span>
                            <i data-lucide="check-circle" x-show="network === 'TRC20'" class="w-4 h-4 text-primary-500"></i>
                        </div>
                        <p class="text-xs text-slate-500">Tron Network</p>
                        <p class="text-xs text-primary-500 mt-1">Lower fees</p>
                    </button>
                    
                    <button type="button" @click="network = 'ERC20'"
                            :class="network === 'ERC20' ? 'bg-amber-500/10 border-amber-500/30' : 'bg-white border-slate-200'"
                            class="p-3 rounded-lg border-2 transition-all text-left">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-bold text-slate-900">ERC20</span>
                            <i data-lucide="check-circle" x-show="network === 'ERC20'" class="w-4 h-4 text-amber-400"></i>
                        </div>
                        <p class="text-xs text-slate-500">Ethereum Network</p>
                        <p class="text-xs text-slate-500 mt-1">Standard</p>
                    </button>
                </div>
                <input type="hidden" name="Accounttype" :value="network">
            </div>

            <!-- Recipient Wallet -->
            <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <svg class="w-4 h-4 mr-2 text-primary-500" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M16 32C7.163 32 0 24.837 0 16S7.163 0 16 0s16 7.163 16 16-7.163 16-16 16zm1.922-18.207v-2.366h5.414V7.819H8.595v3.608h5.414v2.365c-4.4.202-7.709 1.074-7.709 2.118 0 1.044 3.309 1.915 7.709 2.118v7.582h3.913v-7.584c4.393-.202 7.694-1.073 7.694-2.116 0-1.043-3.301-1.914-7.694-2.117zm0 3.59v-.002c-.11.008-.677.042-1.942.042-1.01 0-1.721-.03-1.971-.042v.003c-3.888-.171-6.79-.848-6.79-1.658 0-.809 2.902-1.486 6.79-1.66v2.644c.254.018.982.061 1.988.061 1.207 0 1.812-.05 1.925-.06v-2.643c3.88.173 6.775.85 6.775 1.658 0 .81-2.895 1.485-6.775 1.657z"/>
                    </svg>
                    USDT Recipient
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Recipient's <span x-text="network"></span> Wallet Address</label>
                        <input type="text" name="wallet" required
                               x-model="walletAddress"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all font-mono text-sm"
                               :placeholder="network === 'TRC20' ? 'Enter TRC20 wallet address' : 'Enter ERC20 wallet address'">
                        <p class="text-xs text-slate-500 mt-2 flex items-center">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 mr-1 text-primary-500"></i>
                            Ensure the address matches the selected <span x-text="network" class="font-semibold mx-1"></span> network
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Recipient Name <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <input type="text" name="accountname"
                               class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all"
                               placeholder="Alex Johnson">
                    </div>
                </div>
            </div>

            <!-- Amount Section -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-slate-900 mb-4 flex items-center">
                    <i data-lucide="coins" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Amount to Send (USDT)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-lg font-bold text-primary-500">USDT</span>
                            <input type="number" name="amount" required
                                   x-model="amount"
                                   step="0.01" min="0.01"
                                   max="{{ $cryptoAccount->usdt ?? 0 }}"
                                   class="w-full px-4 py-4 pl-[4.5rem] rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all text-xl font-bold"
                                   placeholder="0.00">
                        </div>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <span class="text-slate-500">Available: {{ number_format($cryptoAccount->usdt ?? 0, 2) }} USDT</span>
                            <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= maxBal">Network: <span x-text="network"></span></span>
                        </div>
                        <p x-show="amount && parseFloat(amount) > maxBal" x-transition class="text-xs text-red-400 mt-1 flex items-center">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                            Insufficient USDT balance. You only have {{ number_format($cryptoAccount->usdt ?? 0, 2) }} USDT available.
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-slate-500">Transfer Note <span class="text-slate-500 font-normal">(Optional)</span></label>
                        <textarea name="description" rows="2"
                                  class="w-full px-4 py-3 rounded-lg bg-white border-2 border-slate-200 focus:border-primary-500 focus:ring-0 text-slate-900 transition-all resize-none"
                                  placeholder="Payment reference or note"></textarea>
                    </div>
                </div>
            </div>

            <!-- Network Fee Notice -->
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="info" class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-xs font-semibold text-slate-900 mb-1">Stablecoin Transfer</p>
                        <p class="text-xs text-slate-500">USDT is pegged 1:1 to USD. <span x-show="network === 'TRC20'">TRC20 offers lower transaction fees.</span><span x-show="network === 'ERC20'">ERC20 is widely supported.</span></p>
                    </div>
                </div>
            </div>

            <!-- Security PIN -->
            <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 mb-1">Secure Transaction</h4>
                        <p class="text-xs text-slate-500">Enter your PIN to authorize this transfer</p>
                    </div>
                </div>
                <div class="relative">
                    <input :type="showPin ? 'text' : 'password'" name="pin" required
                           x-model="pin"
                           maxlength="6"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-white border-2 border-amber-400 focus:border-amber-500 focus:ring-0 text-slate-900 transition-all font-mono text-center text-lg tracking-widest"
                           placeholder="� � � � � �">
                    <button type="button" @click="showPin = !showPin"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-amber-400 transition-colors">
                        <i :data-lucide="showPin ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <button type="submit" :disabled="!amount || !walletAddress || !pin || pin.length < 4 || parseFloat(amount) > maxBal"
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-5 h-5"></i>
                <span>Send USDT</span>
            </button>
        </form>
    </div>
</div>

</div>
<!-- End of Alpine.js x-data scope -->

<script>
    function copyToClipboard() {
        const input = document.getElementById('inputField');
        input.select();
        document.execCommand('copy');
        alert('Wallet address copied!');
    }
    
    document.getElementById('selectOption')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const selectedDescription = selectedOption.dataset.description;
        document.getElementById('inputField').value = selectedDescription || '';
    });
    
    // Reinitialize Lucide icons when modals open
    document.addEventListener('alpine:initialized', () => {
        setInterval(() => {
            lucide.createIcons();
        }, 1000);
    });
</script>

@endsection

