@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<style>
.confirm-mono {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    letter-spacing: -0.01em;
    font-variant-numeric: tabular-nums;
}
@media print {
    .no-print { display: none !important; }
}
</style>

@php
    $isCrypto = in_array($confirmdata['payment_mode'], ['Bitcoin', 'Ethereum', 'USDT', 'Cryptocurrency']);
    $cryptoCoin = $confirmdata['crypto_currency'] ?? null;
    $cryptoSymbolMap = ['btc' => 'BTC', 'eth' => 'ETH', 'usdt' => 'USDT'];
    $cryptoSymbol = $cryptoSymbolMap[$cryptoCoin] ?? strtoupper($confirmdata['payment_mode'] ?? '');
    $displayCurrency = $isCrypto ? '' : $user->currency;
    $displaySuffix = $isCrypto ? ' ' . $cryptoSymbol : '';
    $cryptoDecimals = $isCrypto ? 8 : 2;
    
    // Get crypto balance if applicable
    $cryptoBalance = 0;
    if ($isCrypto && $cryptoCoin) {
        $cryptoAcct = \App\Models\CryptoAccount::where('user_id', $user->id)->first();
        $cryptoBalance = $cryptoAcct ? $cryptoAcct->getCoinBalance($cryptoCoin) : 0;
    }
@endphp
<div class="max-w-3xl mx-auto space-y-5" x-data="{ 
    showModal: false,
    showBalance: false,
    copySuccess: false,
    isCrypto: {{ $isCrypto ? 'true' : 'false' }},
    copyText(text) {
        navigator.clipboard.writeText(text);
        this.copySuccess = true;
        setTimeout(() => this.copySuccess = false, 2000);
    },
    get currentBalance() {
        return {{ $isCrypto ? $cryptoBalance : $user->account_bal }};
    },
    get deductAmount() {
        return {{ $confirmdata['to_deduct'] ?? $confirmdata['amount'] }};
    },
    get newBalance() {
        return this.currentBalance - this.deductAmount;
    },
    get hasFee() {
        return {{ $confirmdata['to_deduct'] ?? $confirmdata['amount'] }} > {{ $confirmdata['amount'] }};
    },
    get processingFee() {
        return this.deductAmount - {{ $confirmdata['amount'] }};
    },
    get processingTime() {
        const mode = '{{ $confirmdata['payment_mode'] }}';
        if (mode === 'Internal Transfer') return 'Instant';
        if (['Bitcoin', 'Ethereum', 'USDT', 'Cryptocurrency'].includes(mode)) return 'Network Dependent';
        if (['Wire Transfer', 'Local transfer', 'Domestic Transfer'].includes(mode)) return '1-3 Business Days';
        return '24-48 Hours';
    },
    formatAmount(val) {
        if (this.isCrypto) {
            return parseFloat(val).toFixed(8).replace(/0+$/, '').replace(/\.$/, '') + ' {{ $cryptoSymbol }}';
        }
        return '{{ $user->currency }}' + val.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }
}">
    <!-- Header -->
    <div class="flex items-center justify-between mb-4 no-print">
        <a href="{{ route('cancelconfirm') }}" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors group">
            <i data-lucide="arrow-left" class="w-5 h-5 text-[#7A7A7A] group-hover:text-[#0D1B23]"></i>
        </a>
        <div class="text-center flex-1">
            <p class="text-xs uppercase tracking-wider text-[#7A7A7A] font-semibold">Review Transfer</p>
            <h1 class="text-xl font-semibold text-[#0D1B23]">Confirmation Required</h1>
        </div>
        <button onclick="location.reload();" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors group">
            <i data-lucide="refresh-cw" class="w-5 h-5 text-[#7A7A7A] group-hover:text-[#0D1B23]"></i>
        </button>
    </div>

    <!-- Processing Timeline -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-center justify-between mb-5">
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center z-10">
                    <i data-lucide="eye" class="w-3.5 h-3.5 text-white"></i>
                </div>
                <p class="text-xs font-medium mt-2 text-primary-500">Review</p>
            </div>
            <div class="flex-1 h-px bg-[#DFE5E5] -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full bg-[#DFE5E5] border border-[#C8D1D1] flex items-center justify-center z-10">
                    <i data-lucide="loader" class="w-3.5 h-3.5 text-[#7A7A7A]"></i>
                </div>
                <p class="text-xs font-medium mt-2 text-[#7A7A7A]">Processing</p>
            </div>
            <div class="flex-1 h-px bg-[#DFE5E5] -mt-4"></div>
            <div class="flex flex-col items-center flex-1">
                <div class="w-8 h-8 rounded-full bg-[#DFE5E5] border border-[#C8D1D1] flex items-center justify-center z-10">
                    <i data-lucide="check-circle" class="w-3.5 h-3.5 text-[#7A7A7A]"></i>
                </div>
                <p class="text-xs font-medium mt-2 text-[#7A7A7A]">Complete</p>
            </div>
        </div>
        <div class="flex items-center justify-center space-x-2 text-sm">
            <i data-lucide="clock" class="w-4 h-4 text-[#7A7A7A]"></i>
            <span class="text-[#7A7A7A]">Estimated Processing: <span class="font-medium text-[#0D1B23]" x-text="processingTime"></span></span>
        </div>
    </div>

    <!-- From / To Account Cards -->
    <div class="grid md:grid-cols-2 gap-4 relative">
        <!-- From Account -->
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-primary-500"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-[#7A7A7A] font-semibold">From</p>
                    <p class="text-sm font-semibold text-[#0D1B23]">Your Account</p>
                </div>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#7A7A7A]">Account Holder</span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $user->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#7A7A7A]">Account Number</span>
                    <span class="text-sm font-medium text-[#0D1B23] confirm-mono">{{ $user->account_number }}</span>
                </div>
                <hr class="border-[#E0E5E5]">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#7A7A7A]">Current Balance</span>
                    @if($isCrypto)
                        <span class="text-sm font-bold text-primary-500">{{ rtrim(rtrim(number_format($cryptoBalance, 8, '.', ','), '0'), '.') }} {{ $cryptoSymbol }}</span>
                    @else
                        <span class="text-sm font-bold text-primary-500">{{ $user->currency }}{{ number_format($user->account_bal, 2, '.', ',') }}</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- To Account -->
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
            <div class="flex items-center space-x-3 mb-4">
                @php
                    $icon = match($confirmdata['payment_mode']) {
                        'Wire Transfer', 'Local transfer', 'Domestic Transfer', 'Internal Transfer' => 'building-2',
                        'Bitcoin' => 'bitcoin',
                        'Ethereum' => 'hexagon',
                        'USDT', 'Cryptocurrency' => 'coins',
                        'Paypal' => 'mail',
                        'Western Union' => 'send',
                        default => 'send'
                    };
                @endphp
                <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="{{ $icon }}" class="w-4 h-4 text-[#4A4A4A]"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-[#7A7A7A] font-semibold">To</p>
                    <p class="text-sm font-semibold text-[#0D1B23]">{{ $confirmdata['accountname'] }}</p>
                </div>
            </div>
            <div class="space-y-2.5">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#7A7A7A]">Transfer Method</span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $confirmdata['payment_mode'] }}</span>
                </div>
                @if (in_array($confirmdata['payment_mode'], ['Local transfer', 'Wire Transfer', 'Domestic Transfer', 'Internal Transfer']))
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#7A7A7A]">Account</span>
                        <span class="text-sm font-medium text-[#0D1B23] confirm-mono">{{ $confirmdata['accountnumber'] }}</span>
                    </div>
                @elseif(in_array($confirmdata['payment_mode'], ['Bitcoin', 'Ethereum', 'USDT', 'Cryptocurrency']))
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#7A7A7A]">Wallet</span>
                        <span class="text-xs font-medium text-[#0D1B23] confirm-mono break-all">{{ substr($confirmdata['accountnumber'], 0, 12) }}...{{ substr($confirmdata['accountnumber'], -8) }}</span>
                    </div>
                @elseif(in_array($confirmdata['payment_mode'], ['Paypal', 'Skrill', 'Google Pay', 'Transfer Wise', 'Payoneer']))
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#7A7A7A]">Email</span>
                        <span class="text-sm font-medium text-[#0D1B23]">{{ $confirmdata['accountnumber'] }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Amount Display -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 text-center">
        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-primary-50 border border-primary-200 text-primary-500 text-xs font-medium uppercase tracking-wider mb-3">
            <i data-lucide="check-circle" class="w-3 h-3 mr-1.5"></i>
            Ready to Confirm
        </span>
        <div class="text-2xl md:text-3xl font-bold text-[#0D1B23] mb-1 confirm-mono">
            @if($isCrypto)
                {{ rtrim(rtrim(number_format($confirmdata['amount'], 8, '.', ','), '0'), '.') }} {{ $cryptoSymbol }}
            @else
                {{ $user->currency }}{{ number_format($confirmdata['amount'], 2, '.', ',') }}
            @endif
        </div>
        <p class="text-xs text-[#7A7A7A]">Transfer Amount</p>
    </div>

    <!-- Balance Impact Preview -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl overflow-hidden">
        <button @click="showBalance = !showBalance" class="w-full p-4 flex items-center justify-between hover:bg-[#DFE5E5]/30 transition-colors">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="trending-down" class="w-4 h-4 text-[#7A7A7A]"></i>
                </div>
                <div class="text-left">
                    <p class="text-sm font-semibold text-[#0D1B23]">Balance Impact</p>
                    <p class="text-xs text-[#7A7A7A]">See how this affects your account</p>
                </div>
            </div>
            <i data-lucide="chevron-down" class="w-4 h-4 text-[#7A7A7A] transition-transform" :class="{'rotate-180': showBalance}"></i>
        </button>
        
        <div x-show="showBalance" x-collapse class="px-4 pb-4 space-y-3">
            <div class="bg-[#F9F9F9] rounded-lg p-4 space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#7A7A7A]">Current Balance</span>
                    <span class="text-sm font-semibold text-[#0D1B23] confirm-mono" x-text="formatAmount(currentBalance)"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#7A7A7A]">Amount to Deduct</span>
                    <span class="text-sm font-semibold text-[#D55672] confirm-mono">-<span x-text="formatAmount(deductAmount)"></span></span>
                </div>
                <hr class="border-[#E0E5E5]">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-semibold text-[#0D1B23]">New Balance</span>
                    <span class="text-base font-bold text-primary-500 confirm-mono" x-text="formatAmount(newBalance)"></span>
                </div>
            </div>
            
            @if($isCrypto)
                @if($cryptoBalance < ($confirmdata['to_deduct'] ?? $confirmdata['amount']))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-lg p-3 flex items-start space-x-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#D55672] mb-0.5">Insufficient {{ $cryptoSymbol }} Balance</p>
                            <p class="text-xs text-[#7A7A7A]">Your {{ $cryptoSymbol }} wallet balance is lower than the transfer amount.</p>
                        </div>
                    </div>
                @endif
            @else
                @if($user->account_bal < ($confirmdata['to_deduct'] ?? $confirmdata['amount']))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-lg p-3 flex items-start space-x-3">
                        <i data-lucide="alert-triangle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-[#D55672] mb-0.5">Insufficient Funds</p>
                            <p class="text-xs text-[#7A7A7A]">Your account balance is lower than the transfer amount. Please deposit funds or reduce the transfer amount.</p>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Transfer Summary -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-center space-x-2 mb-4">
            <i data-lucide="receipt" class="w-4 h-4 text-[#7A7A7A]"></i>
            <h3 class="text-sm font-semibold text-[#4A4A4A] uppercase tracking-wider">Transfer Summary</h3>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-sm text-[#7A7A7A]">Transfer Amount</span>
                <span class="text-sm font-medium text-[#0D1B23] confirm-mono">
                    @if($isCrypto)
                        {{ rtrim(rtrim(number_format($confirmdata['amount'], 8, '.', ','), '0'), '.') }} {{ $cryptoSymbol }}
                    @else
                        {{ $user->currency }}{{ number_format($confirmdata['amount'], 2, '.', ',') }}
                    @endif
                </span>
            </div>
            
            @if(($confirmdata['to_deduct'] ?? $confirmdata['amount']) > $confirmdata['amount'])
                <div class="flex justify-between items-center">
                    <span class="text-sm text-[#7A7A7A]">Processing Fee</span>
                    <span class="text-sm font-medium text-[#0D1B23] confirm-mono">
                        @if($isCrypto)
                            {{ rtrim(rtrim(number_format(($confirmdata['to_deduct'] ?? $confirmdata['amount']) - $confirmdata['amount'], 8, '.', ','), '0'), '.') }} {{ $cryptoSymbol }}
                        @else
                            {{ $user->currency }}{{ number_format(($confirmdata['to_deduct'] ?? $confirmdata['amount']) - $confirmdata['amount'], 2, '.', ',') }}
                        @endif
                    </span>
                </div>
            @else
                <div class="flex items-center space-x-2 text-primary-500">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    <span class="text-sm font-medium">No Processing Fee</span>
                </div>
            @endif
            
            <hr class="border-[#E0E5E5]">
            <div class="flex justify-between items-center">
                <span class="text-sm font-semibold text-[#0D1B23]">Total Deduction</span>
                <span class="text-base font-bold text-[#0D1B23] confirm-mono">
                    @if($isCrypto)
                        {{ rtrim(rtrim(number_format($confirmdata['to_deduct'] ?? $confirmdata['amount'], 8, '.', ','), '0'), '.') }} {{ $cryptoSymbol }}
                    @else
                        {{ $user->currency }}{{ number_format($confirmdata['to_deduct'] ?? $confirmdata['amount'], 2, '.', ',') }}
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-center space-x-2 mb-4">
            <i data-lucide="file-text" class="w-4 h-4 text-[#7A7A7A]"></i>
            <h3 class="text-sm font-semibold text-[#4A4A4A] uppercase tracking-wider">Transaction Details</h3>
        </div>

        <div class="space-y-0">
            <div class="flex justify-between items-center py-2.5">
                <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Recipient</span>
                </span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $confirmdata['accountname'] }}</span>
            </div>

            <div class="flex justify-between items-center py-2.5 border-t border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                    <span>Method</span>
                </span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $confirmdata['payment_mode'] }}</span>
            </div>

        @if (in_array($confirmdata['payment_mode'], ['Local transfer', 'Wire Transfer', 'International Wire Transfer', 'Domestic Transfer', 'Internal Transfer']))
            <div class="flex justify-between items-center py-2.5 border-t border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                    <span>Account Number</span>
                </span>
                <span class="text-sm font-medium text-[#0D1B23] confirm-mono">{{ $confirmdata['accountnumber'] }}</span>
            </div>
            @if (isset($confirmdata['iban']) && $confirmdata['iban'])
                <div class="flex justify-between items-center py-2.5 border-t border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                        <i data-lucide="git-branch" class="w-4 h-4"></i>
                        <span>Routing Number</span>
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23] confirm-mono">{{ $confirmdata['iban'] }}</span>
                </div>
            @endif
        @elseif(in_array($confirmdata['payment_mode'], ['Cryptocurrency', 'Bitcoin', 'Ethereum', 'USDT']))
            <div class="flex flex-col py-2.5 border-t border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center space-x-2 mb-2">
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                    <span>Wallet Address</span>
                </span>
                <span class="text-xs font-medium text-[#0D1B23] confirm-mono break-all bg-[#F9F9F9] p-2 rounded-lg border border-[#E0E5E5]">{{ $confirmdata['accountnumber'] }}</span>
            </div>
        @elseif(in_array($confirmdata['payment_mode'], ['Paypal', 'Google Pay', 'Transfer Wise', 'Payoneer', 'Skrill']))
            <div class="flex justify-between items-center py-2.5 border-t border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                    <span>Email</span>
                </span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $confirmdata['accountnumber'] }}</span>
            </div>
        @elseif($confirmdata['payment_mode'] == 'Western Union')
            <div class="flex justify-between items-center py-2.5 border-t border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                    <span>Recipient Name</span>
                </span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $confirmdata['accountnumber'] }}</span>
            </div>
        @else
            <div class="flex justify-between items-center py-2.5 border-t border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                    <i data-lucide="hash" class="w-4 h-4"></i>
                    <span>Account Number</span>
                </span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $confirmdata['accountnumber'] }}</span>
            </div>
        @endif

        <div class="flex justify-between items-center py-2.5 border-t border-[#E0E5E5]">
            <span class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                <i data-lucide="receipt" class="w-4 h-4"></i>
                <span>Reference ID</span>
            </span>
            <div class="flex items-center space-x-2">
                <span class="text-sm font-semibold text-primary-500 confirm-mono">#{{ $confirmdata['txn_id'] }}</span>
                <button @click="copyText('{{ $confirmdata['txn_id'] }}')" class="p-1 rounded hover:bg-[#DFE5E5] transition-colors">
                    <i :data-lucide="copySuccess ? 'check' : 'copy'" class="w-4 h-4 text-[#7A7A7A]"></i>
                </button>
            </div>
        </div>
        </div>
    </div>

    <!-- Security Notice -->
    <div class="bg-white border border-[#E0E5E5] rounded-lg p-3 flex items-start space-x-3">
        <i data-lucide="shield-check" class="w-4 h-4 text-primary-500 flex-shrink-0 mt-0.5"></i>
        <div>
            <p class="text-sm font-medium text-[#4A4A4A] mb-0.5">Secure Transaction</p>
            <p class="text-xs text-[#7A7A7A] leading-relaxed">This transfer is protected with bank-grade encryption. Review all details carefully before proceeding.</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-2 gap-3 no-print">
        <button @click="showModal = true" class="px-4 py-3 rounded-lg bg-white border border-[#E0E5E5] hover:bg-[#DFE5E5] hover:border-[#C8D1D1] text-[#4A4A4A] font-medium text-sm flex items-center justify-center space-x-2 transition-colors">
            <i data-lucide="x-circle" class="w-4 h-4"></i>
            <span>Cancel</span>
        </button>
        
        <a href="{{ route('proceedtranscation') }}" class="px-4 py-3 rounded-lg bg-primary-600 hover:bg-primary-500 text-white font-medium text-sm flex items-center justify-center space-x-2 transition-colors">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            <span>Confirm Transfer</span>
        </a>
    </div>

    <!-- Warning Footer -->
    <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3 text-center">
        <div class="flex items-center justify-center space-x-2 text-xs text-[#7A7A7A]">
            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 text-[#7A7A7A]"></i>
            <span>Please verify all details. Once confirmed, this action <span class="font-medium text-[#0D1B23]">cannot be reversed</span>.</span>
        </div>
    </div>

    <!-- Cancel Modal -->
    <div x-show="showModal" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         @click="showModal = false"
         class="fixed inset-0 bg-black/30 z-50 flex items-center justify-center p-4"
         style="display: none;">
        <div x-show="showModal"
             x-transition:enter="transition ease-out duration-200 transform"
             x-transition:enter-start="scale-95 opacity-0"
             x-transition:enter-end="scale-100 opacity-100"
             @click.stop
             class="bg-white border border-[#E0E5E5] rounded-xl max-w-md w-full overflow-hidden">
            <div class="p-6">
                <div class="w-14 h-14 rounded-full bg-[#D55672]/10 border border-[#D55672]/20 flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-circle" class="w-7 h-7 text-[#D55672]"></i>
                </div>
                
                <h3 class="text-lg font-semibold text-center mb-2 text-[#0D1B23]">Cancel Transfer?</h3>
                <p class="text-sm text-[#7A7A7A] text-center mb-6">Are you sure you want to cancel this transaction? All entered information will be lost.</p>
                
                <div class="grid grid-cols-2 gap-3">
                    <button @click="showModal = false" class="px-4 py-2.5 rounded-lg border border-[#C8D1D1] hover:bg-[#DFE5E5] text-[#4A4A4A] transition-colors font-medium text-sm">
                        Go Back
                    </button>
                    <a href="{{ route('cancelconfirm') }}" class="px-4 py-2.5 rounded-lg bg-[#D55672] hover:bg-[#c4405e] text-white transition-colors font-medium text-sm text-center">
                        Yes, Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
    });
</script>

@endsection
