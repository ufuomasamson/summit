@extends('layouts.bankapp')

@section('title', 'Virtual Cards')

@section('content')
<div class="space-y-6" x-data="{ showFundModal: false, showWithdrawModal: false, selectedCard: null }">
    
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-semibold text-[#0D1B23]">Virtual Cards</h1>
            <p class="text-sm text-[#7A7A7A] mt-1">Manage your virtual debit cards</p>
        </div>
        <a href="{{ route('cards.apply') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-lg bg-[#49786B] hover:bg-[#3B645A] transition-colors text-sm font-medium text-white">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Apply for Card</span>
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
        <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#7A7A7A]">Total Balance</p>
                    <h3 class="text-xl font-semibold text-[#0D1B23] mt-1">{{ Auth::user()->currency }}{{ number_format($totalBalance, 2) }}</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-[#49786B]"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#7A7A7A]">Total Transactions</p>
                    <h3 class="text-xl font-semibold text-[#0D1B23] mt-1">{{ $totalTransactions }}</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="activity" class="w-4 h-4 text-[#49786B]"></i>
                </div>
            </div>
        </div>

        <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-[#7A7A7A]">Pending Applications</p>
                    <h3 class="text-xl font-semibold text-[#0D1B23] mt-1">{{ $pendingCount }}</h3>
                </div>
                <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4 text-[#3e4e83]"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-[#28A745]/10 border border-[#28A745]/20 text-[#28A745] p-4 rounded-lg">
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-[#D55672]/10 border border-[#D55672]/20 text-[#D55672] p-4 rounded-lg">
        <p class="text-sm font-medium">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Cards Grid -->
    @if($cards->isEmpty())
    <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-12 text-center">
        <div class="w-16 h-16 rounded-full bg-[#DFE5E5] flex items-center justify-center mx-auto mb-4">
            <i data-lucide="credit-card" class="w-8 h-8 text-[#7A7A7A]"></i>
        </div>
        <h3 class="text-lg font-semibold text-[#0D1B23] mb-1">No Cards Yet</h3>
        <p class="text-sm text-[#7A7A7A] mb-5">Apply for your first virtual card to get started</p>
        <a href="{{ route('cards.apply') }}" class="inline-flex items-center gap-2 bg-[#49786B] hover:bg-[#3B645A] text-white text-sm font-medium px-5 py-2.5 rounded-lg transition-colors">
            <i data-lucide="plus" class="w-4 h-4"></i>
            <span>Apply Now</span>
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($cards as $card)
        <div class="group" x-data="{ flipped: false }">
            <div class="relative h-56 perspective-1000">
                <div class="relative w-full h-full transition-transform duration-700 transform-style-preserve-3d" :class="flipped ? 'rotate-y-180' : ''">
                    
                    <!-- Card Front -->
                    <div class="absolute w-full h-full backface-hidden">
                        <div class="h-full rounded-xl p-5 text-white relative overflow-hidden
                            {{ $card->card_level === 'Standard' ? 'bg-slate-700' : '' }}
                            {{ $card->card_level === 'Gold' ? 'bg-amber-800' : '' }}
                            {{ $card->card_level === 'Platinum' ? 'bg-blue-800' : '' }}
                            {{ $card->card_level === 'Black' ? 'bg-slate-900 border border-slate-600' : '' }}">

                            <!-- Card Header -->
                            <div class="relative flex justify-between items-start">
                                <div>
                                    <!-- Card Type Logo -->
                                    @if($card->card_type === 'Visa')
                                    <svg class="h-5 w-9 mb-1.5" viewBox="0 0 1000 324" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M433.334 318.516H348.568L403.315 5.01855H488.081L433.334 318.516Z" fill="white"/>
                                        <path d="M727.98 15.9043C712.039 9.56622 686.227 2.51855 653.355 2.51855C574.054 2.51855 519.055 45.5277 518.802 106.826C518.306 152.343 558.126 177.912 587.98 193.316C618.34 209.233 629.315 219.335 629.315 233.014C629.061 254.008 603.493 263.591 579.702 263.591C547.839 263.591 530.7 258.287 505.123 245.892L493.902 240.335L482.166 313.221C501.069 323.07 536.096 331.667 572.643 332.163C657.14 332.163 711.128 289.897 711.895 224.144C712.394 188.508 689.871 160.445 643.015 136.91C615.2 121.744 598.306 111.638 598.554 97.2356C598.554 84.5987 613.224 70.9167 648.747 70.9167C677.823 70.4206 699.053 77.7188 715.707 85.5144L726.681 90.8186L738.417 19.9214L727.98 15.9043Z" fill="white"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M841.897 5.01855H773.691C755.782 5.01855 742.397 10.0624 734.862 30.0238L630.236 318.516H715.464C715.464 318.516 729.143 280.806 732.429 272.002C742.901 272.002 822.706 272.002 835.844 272.002C838.373 283.215 845.93 318.516 845.93 318.516H922.667L841.897 5.01855ZM762.22 208.49C769.018 189.752 796.85 116.686 796.85 116.686C796.602 117.174 803.16 99.19 806.699 88.0899L812.02 114.188C812.02 114.188 829.775 192.026 833.061 208.49H762.22Z" fill="white"/>
                                        <path d="M251.994 5.01855L171.471 219.335L162.137 174.313C146.216 129.054 102.175 80.0894 52.8662 56.8035L126.462 318.02H212.215L336.832 5.01855H251.994Z" fill="white"/>
                                        <path d="M127.963 5.01855H0.66211L0.167969 10.3101C98.1497 31.7971 163.301 76.5646 190.132 129.298L162.633 13.5747C157.329 -0.60098 144.316 5.51465 127.963 5.01855Z" fill="white"/>
                                    </svg>
                                    @elseif($card->card_type === 'Mastercard')
                                    <svg class="h-5 w-9 mb-1.5" viewBox="0 0 131.39 86.9" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M48.37 15.14h34.66v56.61H48.37z" fill="#ff5f00"/>
                                        <path d="M52.37 43.45a35.94 35.94 0 0113.75-28.3 36 36 0 100 56.61 35.94 35.94 0 01-13.75-28.31z" fill="#eb001b"/>
                                        <path d="M120.39 65.54V64.5h.48v-.24h-1.19v.24h.47v1.04zm2.31 0v-1.29h-.36l-.42.91-.42-.91h-.36v1.29h.26V64.9l.39.89h.27l.39-.89v.89z" fill="#f79e1b"/>
                                        <path d="M123.94 43.45a36 36 0 01-58 28.3 36 36 0 000-56.61 36 36 0 0158 28.3z" fill="#f79e1b"/>
                                    </svg>
                                    @elseif($card->card_type === 'American Express')
                                    <svg class="h-5 w-9 mb-1.5" viewBox="0 0 128 80" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="128" height="80" rx="12" fill="white" fill-opacity="0.9"/>
                                        <path d="M20 50h88v-20H20v20zm5-15h10v10H25v-10zm15 0h10v10H40v-10zm15 0h10v10H55v-10zm15 0h10v10H70v-10zm15 0h10v10H85v-10z" fill="#2E77BC"/>
                                        <text x="50%" y="25%" dominant-baseline="middle" text-anchor="middle"
                                              font-family="Arial, sans-serif" font-size="16" font-weight="bold" fill="#2E77BC">
                                          AMERICAN
                                        </text>
                                        <text x="50%" y="60%" dominant-baseline="middle" text-anchor="middle"
                                              font-family="Arial, sans-serif" font-size="18" font-weight="bold" fill="#2E77BC">
                                          EXPRESS
                                        </text>
                                    </svg>
                                    @elseif($card->card_type === 'Discover')
                                    <svg class="h-5 w-9 mb-1.5" viewBox="0 0 200 60" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="200" height="60" fill="#fff" rx="6" fill-opacity="0.9"/>
                                        <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
                                              font-family="Arial, sans-serif" font-weight="bold"
                                              font-size="28" fill="#000">
                                          DISC
                                        </text>
                                        <defs>
                                          <radialGradient id="discoverGrad{{ $card->id }}" cx="50%" cy="50%" r="70%">
                                            <stop offset="0%" stop-color="#ffb347"/>
                                            <stop offset="60%" stop-color="#ff7a00"/>
                                            <stop offset="100%" stop-color="#d85b00"/>
                                          </radialGradient>
                                        </defs>
                                        <circle cx="118" cy="30" r="22" fill="url(#discoverGrad{{ $card->id }})" />
                                        <text x="138" y="50%" dominant-baseline="middle" text-anchor="start"
                                              font-family="Arial, sans-serif" font-weight="bold"
                                              font-size="28" fill="#000">
                                          VER
                                        </text>
                                    </svg>
                                    @endif
                                    <p class="text-[10px] opacity-50">{{ $card->card_level }}</p>
                                </div>
                                <div class="flex gap-2">
                                    @if($card->status === 'active')
                                    <span class="bg-[#28A745]/15 text-[#28A745] border border-[#28A745]/20 px-2 py-0.5 rounded text-[10px] font-medium">Active</span>
                                    @elseif($card->status === 'pending')
                                    <span class="bg-[#3e4e83]/15 text-[#3e4e83] border border-[#3e4e83]/20 px-2 py-0.5 rounded text-[10px] font-medium">Pending</span>
                                    @elseif($card->status === 'blocked')
                                    <span class="bg-[#D55672]/15 text-[#D55672] border border-[#D55672]/20 px-2 py-0.5 rounded text-[10px] font-medium">Blocked</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Number -->
                            <div class="relative mt-6">
                                @if($card->status === 'active')
                                <p class="text-lg font-mono tracking-wider">{{ $card->masked_card_number }}</p>
                                @else
                                <p class="text-sm text-white/40">Card details pending approval</p>
                                @endif
                            </div>

                            <!-- Card Footer -->
                            <div class="relative mt-auto pt-3 flex justify-between items-end">
                                <div>
                                    <p class="text-[10px] opacity-50">Cardholder</p>
                                    <p class="font-medium text-xs">{{ strtoupper($card->cardholder_name) }}</p>
                                </div>
                                @if($card->status === 'active')
                                <div class="text-right">
                                    <p class="text-[10px] opacity-50">Expires</p>
                                    <p class="font-medium text-xs">{{ $card->expiry }}</p>
                                </div>
                                @endif
                            </div>

                            <!-- Balance Badge -->
                            @if($card->status === 'active')
                            <div class="absolute top-5 right-5 bg-white/10 px-2.5 py-1 rounded-md">
                                <p class="text-[10px] opacity-60">Balance</p>
                                <p class="font-semibold text-xs">${{ number_format($card->balance, 2) }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Card Back -->
                    <div class="absolute w-full h-full backface-hidden rotate-y-180">
                        <div class="h-full rounded-xl bg-slate-800 border border-slate-700/50 text-white relative overflow-hidden">
                            <div class="bg-slate-900 h-10 mt-5"></div>
                            <div class="p-5">
                                <div class="bg-white text-gray-800 px-3 py-1.5 rounded text-right mb-3">
                                    @if($card->status === 'active')
                                    <span class="font-mono text-sm">{{ $card->cvv }}</span>
                                    @else
                                    <span class="text-xs">---</span>
                                    @endif
                                </div>
                                <p class="text-[10px] text-gray-500 mb-3">For security, keep this CVV private</p>
                                <div class="text-[10px] text-gray-500 space-y-0.5">
                                    <p>Card Level: {{ $card->card_level }}</p>
                                    <p>Issued: {{ $card->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Actions -->
            <div class="mt-3 grid grid-cols-2 gap-2">
                @if($card->status === 'active')
                <button @click="selectedCard = {{ $card->id }}; showFundModal = true" class="bg-[#49786B] hover:bg-[#3B645A] text-white px-3 py-2 rounded-lg text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="arrow-up-circle" class="w-3.5 h-3.5"></i>
                    Fund
                </button>
                <button @click="selectedCard = {{ $card->id }}; showWithdrawModal = true" class="bg-[#0D1B23] hover:bg-[#0D1B23]/80 text-white px-3 py-2 rounded-lg text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="arrow-down-circle" class="w-3.5 h-3.5"></i>
                    Withdraw
                </button>
                <a href="{{ route('cards.view', $card->id) }}" class="bg-[#DFE5E5] hover:bg-[#BFC9C9] text-[#0D1B23] px-3 py-2 rounded-lg text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                    View Details
                </a>
                <button @click="flipped = !flipped" class="bg-[#DFE5E5] hover:bg-[#BFC9C9] text-[#0D1B23] px-3 py-2 rounded-lg text-xs font-medium transition-colors flex items-center justify-center gap-1.5">
                    <i data-lucide="flip-horizontal" class="w-3.5 h-3.5"></i>
                    Flip Card
                </button>
                @elseif($card->status === 'pending')
                <div class="col-span-2 bg-[#3e4e83]/10 border border-[#3e4e83]/20 text-[#3e4e83] px-3 py-2 rounded-lg text-xs text-center">
                    <i data-lucide="clock" class="w-3.5 h-3.5 inline mr-1"></i>
                    Awaiting approval
                </div>
                @elseif($card->status === 'blocked')
                <div class="col-span-2 bg-[#D55672]/10 border border-[#D55672]/20 text-[#D55672] px-3 py-2 rounded-lg text-xs text-center">
                    <i data-lucide="shield-off" class="w-3.5 h-3.5 inline mr-1"></i>
                    Card blocked
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Fund Card Modal -->
    <div x-show="showFundModal" x-cloak class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" @click.self="showFundModal = false">
        <div class="bg-white border border-[#E0E5E5] shadow-lg rounded-xl max-w-md w-full p-5" @click.away="showFundModal = false">
            <h3 class="text-lg font-semibold text-[#0D1B23] mb-4">Fund Card</h3>
            <form :action="`{{ url('/dashboard/cards') }}/${selectedCard}/fund`" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-[#4A4A4A] mb-1.5">Amount</label>
                    <input type="number" name="amount" step="0.01" min="1" required class="w-full px-4 py-2.5 bg-white border border-[#C8D1D1] rounded-lg focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 text-[#0D1B23] text-sm transition-colors" placeholder="0.00">
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="showFundModal = false" class="flex-1 bg-[#DFE5E5] hover:bg-[#BFC9C9] text-[#4A4A4A] py-2.5 rounded-lg text-sm font-medium transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#49786B] hover:bg-[#3B645A] text-white py-2.5 rounded-lg text-sm font-medium transition-colors">Fund Card</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdraw Modal -->
    <div x-show="showWithdrawModal" x-cloak class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4" @click.self="showWithdrawModal = false">
        <div class="bg-white border border-[#E0E5E5] shadow-lg rounded-xl max-w-md w-full p-5" @click.away="showWithdrawModal = false">
            <h3 class="text-lg font-semibold text-[#0D1B23] mb-4">Withdraw from Card</h3>
            <form :action="`{{ url('/dashboard/cards') }}/${selectedCard}/withdraw`" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm text-[#4A4A4A] mb-1.5">Amount</label>
                    <input type="number" name="amount" step="0.01" min="1" required class="w-full px-4 py-2.5 bg-white border border-[#C8D1D1] rounded-lg focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 text-[#0D1B23] text-sm transition-colors" placeholder="0.00">
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="showWithdrawModal = false" class="flex-1 bg-[#DFE5E5] hover:bg-[#BFC9C9] text-[#4A4A4A] py-2.5 rounded-lg text-sm font-medium transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 bg-[#0D1B23] hover:bg-[#0D1B23]/80 text-white py-2.5 rounded-lg text-sm font-medium transition-colors">Withdraw</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .perspective-1000 { perspective: 1000px; }
    .transform-style-preserve-3d { transform-style: preserve-3d; }
    .backface-hidden { backface-visibility: hidden; }
    .rotate-y-180 { transform: rotateY(180deg); }
</style>

<script>
    lucide.createIcons();
</script>
@endsection


