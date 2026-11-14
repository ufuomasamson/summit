@extends('layouts.bankapp')

@section('title', 'Processing Transfer')
@section('content')

<style>
.preview-mono {
    font-family: 'JetBrains Mono', 'Courier New', monospace;
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.01em;
}

@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.spin-slow {
    animation: spin-slow 2s linear infinite;
}

@keyframes blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}

.status-dot {
    animation: blink 1.5s ease-in-out infinite;
}
</style>

<div class="max-w-2xl mx-auto space-y-5" x-data="{ countdown: 20 }" x-init="
    let interval = setInterval(() => {
        countdown--;
        if (countdown <= 0) {
            clearInterval(interval);
            window.location = '{{ route('sucesstransfer') }}';
        }
    }, 1000);
">
    <!-- Processing Header -->
    <div class="text-center pt-4 mb-6">
        <div class="w-14 h-14 rounded-xl bg-primary-50 border border-primary-200 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="loader" class="w-7 h-7 text-primary-500 spin-slow"></i>
        </div>
        
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-primary-50 border border-primary-200 rounded-full text-xs font-semibold uppercase tracking-wider text-primary-500 mb-3">
            <span class="w-1.5 h-1.5 rounded-full bg-primary-500 status-dot"></span>
            Processing Transaction
        </span>
        
        <h1 class="text-2xl font-semibold text-[#0D1B23] mb-1">Transfer in Progress</h1>
        <p class="text-sm text-[#7A7A7A]">Please wait while we process your transaction</p>
        
        <div class="inline-flex items-center gap-2 mt-4 px-3 py-1.5 bg-white border border-[#E0E5E5] rounded-lg text-sm text-[#7A7A7A]">
            <i data-lucide="clock" class="w-4 h-4"></i>
            <span>Redirecting in <span class="text-[#0D1B23] font-semibold" x-text="countdown"></span> seconds</span>
        </div>
    </div>

    <!-- Receipt Card -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl overflow-hidden">
        <!-- 2px top accent -->
        <div class="h-0.5 bg-primary-400"></div>
        
        <!-- Receipt Header -->
        <div class="text-center p-5 border-b border-[#E0E5E5]">
            <h2 class="text-lg font-semibold text-[#0D1B23] mb-0.5">{{ $dp['bankname'] }}</h2>
            <p class="text-xs uppercase tracking-widest text-[#7A7A7A] font-semibold">Transaction Receipt</p>
        </div>

        <!-- Amount Section -->
        @php
            $isCryptoPreview = in_array($dp['payment_mode'], ['Bitcoin', 'Ethereum', 'USDT', 'Cryptocurrency']);
            $cryptoCoinPreview = $dp['crypto_currency'] ?? null;
            $cryptoSymbolMapPreview = ['btc' => 'BTC', 'eth' => 'ETH', 'usdt' => 'USDT'];
            $cryptoSymbolPreview = $cryptoSymbolMapPreview[$cryptoCoinPreview] ?? strtoupper($dp['payment_mode'] ?? '');
        @endphp
        <div class="text-center py-5 mx-5 my-4 bg-primary-50 border border-primary-200 rounded-lg">
            <p class="text-xs uppercase tracking-wider text-[#7A7A7A] font-semibold mb-1">Transfer Amount</p>
            <div class="text-3xl font-bold text-primary-500 preview-mono">
                @if($isCryptoPreview)
                    {{ rtrim(rtrim(number_format($dp['amount'], 8, '.', ','), '0'), '.') }} {{ $cryptoSymbolPreview }}
                @else
                    {{ Auth::user()->currency }}{{ number_format($dp['amount'], 2, '.', ',') }}
                @endif
            </div>
            <p class="text-sm text-[#7A7A7A] mt-1">
                To: <span class="text-[#0D1B23] font-medium">{{ $dp['accountname'] }}</span>
            </p>
        </div>

        <!-- Transaction Details -->
        <div class="px-5 pb-2">
            <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                    <i data-lucide="tag" class="w-4 h-4 text-[#7A7A7A]"></i>
                    Transaction Type
                </span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['payment_mode'] }}</span>
            </div>

            @if (in_array($dp['payment_mode'], ['Local Transfer', 'Wire Transfer', 'International Wire Transfer', 'Domestic Transfer']))
                @if(isset($dp['bankname']) && $dp['bankname'])
                    <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                        <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                            <i data-lucide="building" class="w-4 h-4 text-[#7A7A7A]"></i>
                            Bank Name
                        </span>
                        <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['bankname'] }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="hash" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Account Number
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23] preview-mono bg-[#F9F9F9] px-2 py-1 rounded border border-[#E0E5E5]">{{ $dp['accountnumber'] }}</span>
                </div>

                @if(isset($dp['Accounttype']) && $dp['Accounttype'])
                    <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                        <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                            <i data-lucide="file-text" class="w-4 h-4 text-[#7A7A7A]"></i>
                            Account Type
                        </span>
                        <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['Accounttype'] }}</span>
                    </div>
                @endif

                @if(isset($dp['iban']) && $dp['iban'])
                    <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                        <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#7A7A7A]"></i>
                            Routing Number
                        </span>
                        <span class="text-sm font-medium text-[#0D1B23] preview-mono bg-[#F9F9F9] px-2 py-1 rounded border border-[#E0E5E5]">{{ $dp['iban'] }}</span>
                    </div>
                @endif

                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="credit-card" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Transfer Fee
                    </span>
                    <span class="text-sm font-medium text-primary-500">{{ Auth::user()->currency }}0.00</span>
                </div>

            @elseif(in_array($dp['payment_mode'], ['Cryptocurrency', 'Bitcoin', 'Ethereum', 'USDT']))
                @if(isset($dp['bankname']) && $dp['bankname'])
                    <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                        <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                            <i data-lucide="coins" class="w-4 h-4 text-[#7A7A7A]"></i>
                            Cryptocurrency
                        </span>
                        <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['bankname'] }}</span>
                    </div>
                @endif

                <div class="flex flex-col py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5 mb-2">
                        <i data-lucide="wallet" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Wallet Address
                    </span>
                    <span class="text-xs font-medium text-[#0D1B23] preview-mono break-all bg-[#F9F9F9] px-2.5 py-1.5 rounded-lg border border-[#E0E5E5]">{{ $dp['accountnumber'] }}</span>
                </div>

            @elseif($dp['payment_mode'] == 'Western Union')
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="user" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Recipient Name
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['accountname'] }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="globe" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Country
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['country'] }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="map-pin" class="w-4 h-4 text-[#7A7A7A]"></i>
                        City
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['city'] }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="navigation" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Zip Code
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['zipcode'] }}</span>
                </div>

            @elseif(in_array($dp['payment_mode'], ['Paypal', 'Google Pay', 'Transfer Wise', 'Payoneer', 'Skrill']))
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="mail" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Email Address
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['accountnumber'] }}</span>
                </div>

            @else
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                        <i data-lucide="hash" class="w-4 h-4 text-[#7A7A7A]"></i>
                        Account Number
                    </span>
                    <span class="text-sm font-medium text-[#0D1B23]">{{ $dp['accountnumber'] }}</span>
                </div>
            @endif

            <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                    <i data-lucide="calendar" class="w-4 h-4 text-[#7A7A7A]"></i>
                    Transaction Date
                </span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ \Carbon\Carbon::parse($dp['date'])->format('M d, Y - h:i A') }}</span>
            </div>

            @if(!empty($dp['decription']))
            <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#7A7A7A] flex items-center gap-1.5">
                    <i data-lucide="message-square" class="w-4 h-4 text-[#7A7A7A]"></i>
                    Description
                </span>
                <span class="text-sm font-medium text-[#0D1B23] text-right max-w-[60%]">{{ $dp['decription'] }}</span>
            </div>
            @endif
        </div>

        <!-- Reference ID Section -->
        <div class="text-center px-5 pt-4 pb-5 mt-2 border-t-2 border-dashed border-[#E0E5E5]/50">
            <p class="text-xs uppercase tracking-widest text-[#7A7A7A] font-semibold mb-2">Transaction Reference</p>
            <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500/8 border border-primary-200 rounded-lg preview-mono text-sm font-bold text-primary-500">
                <i data-lucide="fingerprint" class="w-4 h-4"></i>
                <span>#{{ $dp['txn_id'] }}</span>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-2 gap-4">
        <a href="{{ route('dashboard') }}" class="px-4 py-3 rounded-lg border border-[#C8D1D1] hover:bg-[#DFE5E5] text-[#4A4A4A] text-sm font-semibold text-center flex items-center justify-center gap-2 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Send Again</span>
        </a>
        <a href="{{ route('dashboard') }}" class="px-4 py-3 rounded-lg bg-primary-500 hover:bg-primary-600 text-white text-sm font-semibold text-center flex items-center justify-center gap-2 transition-colors">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span>Go Home</span>
        </a>
    </div>

    <!-- Security Notice -->
    <div class="bg-[#28A745]/5 border border-[#28A745]/20 rounded-lg p-3 text-center">
        <p class="text-xs text-[#7A7A7A] flex items-center justify-center gap-1.5">
            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-primary-500"></i>
            Your transaction is secured with bank-grade encryption
        </p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection
