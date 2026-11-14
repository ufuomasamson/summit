@extends('layouts.bankapp')

@section('title', 'Card Details')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('cards.index') }}" class="w-9 h-9 rounded-lg bg-[#F9F9F9] border border-[#E0E5E5] flex items-center justify-center hover:bg-[#DFE5E5] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 text-[#4A4A4A]"></i>
        </a>
        <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">Card Details</h1>
    </div>

    @if(session('success'))
    <div class="bg-[#28A745]/10 border border-[#28A745]/20 text-[#28A745] p-4 rounded-lg">
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-5">
        <!-- Card Info -->
        <div class="lg:col-span-1 space-y-5">
            <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4">
                <div class="rounded-xl p-4 sm:p-5 text-white relative overflow-hidden aspect-[1.586/1]
                    {{ $card->card_level === 'Black' ? 'bg-slate-900 border border-slate-600' : '' }}
                    {{ $card->card_level === 'Platinum' ? 'bg-blue-800' : '' }}
                    {{ $card->card_level === 'Gold' ? 'bg-amber-800' : '' }}
                    {{ $card->card_level === 'Standard' ? 'bg-slate-700' : '' }}">
                    
                    <div class="relative h-full flex flex-col justify-between py-1">
                        <!-- Card Header -->
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs opacity-70">{{ $card->card_type }}</p>
                                <p class="text-[10px] opacity-50">{{ $card->card_level }}</p>
                            </div>
                            @if($card->card_type === 'Visa')
                            <svg class="h-6 w-auto opacity-90" viewBox="0 0 1000 324" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M433.334 318.516H348.568L403.315 5.01855H488.081L433.334 318.516Z" fill="white"/>
                                <path d="M727.98 15.9043C712.039 9.56622 686.227 2.51855 653.355 2.51855C574.054 2.51855 519.055 45.5277 518.802 106.826C518.306 152.343 558.126 177.912 587.98 193.316C618.34 209.233 629.315 219.335 629.315 233.014C629.061 254.008 603.493 263.591 579.702 263.591C547.839 263.591 530.7 258.287 505.123 245.892L493.902 240.335L482.166 313.221C501.069 323.07 536.096 331.667 572.643 332.163C657.14 332.163 711.128 289.897 711.895 224.144C712.394 188.508 689.871 160.445 643.015 136.91C615.2 121.744 598.306 111.638 598.554 97.2356C598.554 84.5987 613.224 70.9167 648.747 70.9167C677.823 70.4206 699.053 77.7188 715.707 85.5144L726.681 90.8186L738.417 19.9214L727.98 15.9043Z" fill="white"/>
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M841.897 5.01855H773.691C755.782 5.01855 742.397 10.0624 734.862 30.0238L630.236 318.516H715.464C715.464 318.516 729.143 280.806 732.429 272.002C742.901 272.002 822.706 272.002 835.844 272.002C838.373 283.215 845.93 318.516 845.93 318.516H922.667L841.897 5.01855ZM762.22 208.49C769.018 189.752 796.85 116.686 796.85 116.686C796.602 117.174 803.16 99.19 806.699 88.0899L812.02 114.188C812.02 114.188 829.775 192.026 833.061 208.49H762.22Z" fill="white"/>
                                <path d="M251.994 5.01855L171.471 219.335L162.137 174.313C146.216 129.054 102.175 80.0894 52.8662 56.8035L126.462 318.02H212.215L336.832 5.01855H251.994Z" fill="white"/>
                            </svg>
                            @elseif($card->card_type === 'Mastercard')
                            <svg class="h-6 w-auto opacity-90" viewBox="0 0 131.39 86.9" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="44" cy="43.45" r="36" fill="#eb001b"/>
                                <circle cx="87.39" cy="43.45" r="36" fill="#f79e1b"/>
                                <path d="M65.7 15.14a35.94 35.94 0 000 56.61 35.94 35.94 0 000-56.61z" fill="#ff5f00"/>
                            </svg>
                            @endif
                        </div>
                        
                        <!-- EMV Chip -->
                        <div class="w-10 h-8 rounded bg-amber-400/80"></div>
                        
                        <!-- Card Number -->
                        <div>
                            @if($card->card_number)
                            <p class="text-sm sm:text-lg lg:text-xl font-mono tracking-[0.1em] sm:tracking-[0.15em] font-semibold">
                                {{ chunk_split($card->card_number, 4, ' ') }}
                            </p>
                            @else
                            <p class="text-sm sm:text-lg lg:text-xl font-mono tracking-[0.1em] sm:tracking-[0.15em] font-semibold opacity-40">
                                **** **** **** ****
                            </p>
                            @endif
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="flex justify-between items-end gap-2 sm:gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-[9px] uppercase opacity-50 mb-0.5 tracking-wider">Cardholder</p>
                                <p class="font-medium text-xs sm:text-sm uppercase tracking-wide truncate">{{ $card->cardholder_name ?? 'CARD HOLDER' }}</p>
                            </div>
                            <div class="flex gap-3 sm:gap-5 flex-shrink-0">
                                <div>
                                    <p class="text-[9px] uppercase opacity-50 mb-0.5 tracking-wider">Expires</p>
                                    <p class="font-medium text-xs sm:text-sm">
                                        @if($card->expiry_month && $card->expiry_year)
                                        {{ str_pad($card->expiry_month, 2, '0', STR_PAD_LEFT) }}/{{ substr($card->expiry_year, -2) }}
                                        @else
                                        09/28
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[9px] uppercase opacity-50 mb-0.5 tracking-wider">CVV</p>
                                    <p class="font-medium text-xs sm:text-sm font-mono">{{ $card->cvv ?? '694' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4 sm:p-5">
                <h3 class="text-sm font-semibold text-[#0D1B23] mb-3">Card Information</h3>
                <dl class="space-y-2 text-xs sm:text-sm">
                    <div class="flex justify-between items-center py-2 border-b border-[#E0E5E5]">
                        <dt class="text-[#7A7A7A]">Status</dt>
                        <dd>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium
                                @if($card->status === 'active') bg-[#28A745]/15 text-[#28A745] border border-[#28A745]/20
                                @elseif($card->status === 'pending') bg-[#3e4e83]/15 text-[#3e4e83] border border-[#3e4e83]/20
                                @elseif($card->status === 'blocked') bg-[#D55672]/15 text-[#D55672] border border-[#D55672]/20
                                @else bg-[#DFE5E5] text-[#7A7A7A]
                                @endif">
                                {{ ucfirst($card->status) }}
                            </span>
                        </dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-[#E0E5E5]">
                        <dt class="text-[#7A7A7A]">Balance</dt>
                        <dd class="font-semibold text-[#28A745]">${{ number_format($card->balance, 2) }}</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-[#E0E5E5]">
                        <dt class="text-[#7A7A7A]">Cardholder</dt>
                        <dd class="font-medium text-[#0D1B23]">{{ $card->cardholder_name }}</dd>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-[#E0E5E5]">
                        <dt class="text-[#7A7A7A]">Daily Limit</dt>
                        <dd class="font-medium text-[#0D1B23]">${{ number_format($card->daily_limit, 2) }}</dd>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-[#E0E5E5] gap-1 sm:gap-0">
                        <dt class="text-[#7A7A7A]">Billing Address</dt>
                        <dd class="font-medium text-[#0D1B23] text-left sm:text-right break-words">{{ $card->billing_address }}</dd>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <dt class="text-[#7A7A7A]">Created</dt>
                        <dd class="font-medium text-[#0D1B23]">{{ $card->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Transactions -->
        <div class="lg:col-span-2">
            <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-[#0D1B23]">Recent Transactions</h3>
                    <a href="{{ route('cards.transactions', $card->id) }}" class="text-[#49786B] hover:text-[#5E8A7C] text-xs font-medium">View All →</a>
                </div>

                @if($recentTransactions->isEmpty())
                <div class="text-center py-12">
                    <div class="w-14 h-14 rounded-full bg-[#DFE5E5] flex items-center justify-center mx-auto mb-3">
                        <i data-lucide="inbox" class="w-7 h-7 text-[#7A7A7A]"></i>
                    </div>
                    <p class="text-sm text-[#7A7A7A]">No transactions yet</p>
                </div>
                @else
                <div class="space-y-2">
                    @foreach($recentTransactions as $txn)
                    <div class="flex items-start sm:items-center justify-between p-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg gap-3">
                        <div class="flex items-start sm:items-center gap-2.5 flex-1 min-w-0">
                            <div class="bg-{{ $txn->type_color }}-500/15 p-2 rounded-lg flex-shrink-0">
                                <i data-lucide="{{ $txn->type_icon }}" class="w-4 h-4 text-{{ $txn->type_color }}-400"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-[#0D1B23] text-sm">{{ ucfirst($txn->type) }}</p>
                                <p class="text-xs text-[#7A7A7A] truncate">{{ $txn->description }}</p>
                                <p class="text-[10px] text-[#7A7A7A] mt-0.5">{{ $txn->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-semibold text-sm {{ in_array($txn->type, ['topup', 'refund']) ? 'text-[#28A745]' : 'text-[#D55672]' }}">
                                {{ in_array($txn->type, ['topup', 'refund']) ? '+' : '-' }}${{ number_format($txn->amount, 2) }}
                            </p>
                            <span class="text-[10px] px-1.5 py-0.5 rounded-md font-medium border
                                {{ $txn->status === 'completed' ? 'bg-[#28A745]/15 text-[#28A745] border-[#28A745]/20' : 'bg-[#DFE5E5] text-[#7A7A7A] border-[#C8D1D1]' }}">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection


