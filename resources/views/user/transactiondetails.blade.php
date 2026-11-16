@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<!-- Transaction Receipt -->
<div class="max-w-4xl mx-auto space-y-5 print:max-w-full">
    
    <!-- Header Actions Bar (Hide on Print) -->
    <div class="print:hidden flex items-center justify-between">
        <a href="{{ route('accounthistory') }}" 
           class="inline-flex items-center gap-2 text-[#7A7A7A] hover:text-[#0D1B23] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span class="text-sm font-medium">Back to Transactions</span>
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" 
                    class="px-4 py-2 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors text-sm font-medium flex items-center gap-2 text-[#4A4A4A]">
                <i data-lucide="printer" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Print</span>
            </button>
            <button onclick="downloadPDF()" 
                    class="px-4 py-2 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors text-sm font-medium flex items-center gap-2 text-[#4A4A4A]">
                <i data-lucide="download" class="w-4 h-4"></i>
                <span class="hidden sm:inline">Download</span>
            </button>
        </div>
    </div>

    <!-- Main Receipt Card -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl overflow-hidden print:shadow-none print:border print:border-gray-300">
        
        <!-- Bank Header -->
        <div class="bg-primary-600 p-6 print:bg-white print:border-b-2 print:border-forest-600">
            <div class="flex items-start justify-between">
                <div>
                    @if($settings->logo ?? false)
                        <img src="{{ branding_url($settings->logo) }}" 
                             alt="{{ $settings->site_name ?? 'Bank' }}" 
                             class="h-10 w-auto mb-1 print:h-10"
                             style="filter: brightness(0) invert(1);">
                    @else
                        <h1 class="text-xl font-semibold text-[#0D1B23] print:text-forest-800">
                            {{ $settings->site_name ?? 'Bank' }}
                        </h1>
                    @endif
                    <p class="text-[#0D1B23]/80 text-sm mt-1 print:text-[#0D1B23]">Transaction Receipt</p>
                </div>
                <div class="text-right">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider
                        @if(strtolower($transaction->status) === 'processed' || strtolower($transaction->status) === 'approved')
                            bg-white/20 text-[#0D1B23] border border-white/30 print:bg-emerald-50 print:text-emerald-700 print:border-emerald-300
                        @elseif(in_array(strtolower($transaction->status), ['pending', 'on-hold', 'scheduled', 'under review']))
                            bg-white/20 text-[#0D1B23] border border-white/30 print:bg-amber-50 print:text-amber-700 print:border-amber-300
                        @elseif(in_array(strtolower($transaction->status), ['rejected', 'failed', 'error', 'expired', 'frozen']))
                            bg-white/20 text-[#0D1B23] border border-white/30 print:bg-red-50 print:text-red-700 print:border-red-300
                        @elseif(in_array(strtolower($transaction->status), ['reversed', 'refunded']))
                            bg-white/20 text-[#0D1B23] border border-white/30 print:bg-blue-50 print:text-blue-700 print:border-blue-300
                        @elseif(strtolower($transaction->status) === 'cancelled')
                            bg-white/20 text-[#0D1B23] border border-white/30 print:bg-gray-50 print:text-gray-700 print:border-gray-300
                        @else
                            bg-white/20 text-[#0D1B23] border border-white/30 print:bg-gray-50 print:text-gray-700 print:border-gray-300
                        @endif
                    ">
                        {{ $transaction->status }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction Summary Section -->
        <div class="p-6 border-b border-[#E0E5E5] print:border-gray-300">
            @php
                $receiptStatus = strtolower($transaction->status ?? '');
                $isStatusReceipt = in_array($receiptStatus, ['failed', 'pending', 'error'], true);
            @endphp
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold mb-1.5">Transaction Amount</p>
                    <h2 class="text-2xl font-bold
                        @if($isStatusReceipt && $receiptStatus === 'pending')
                            text-[#3e4e83] print:text-amber-700
                        @elseif($isStatusReceipt)
                            text-[#D55672] print:text-red-600
                        @elseif($transaction->type === 'Credit')
                            text-emerald-400 print:text-emerald-600
                        @else
                            text-[#D55672] print:text-red-600
                        @endif
                    ">
                        @unless($isStatusReceipt)
                            @if($transaction->type === 'Credit')
                                <span class="text-xl">+</span>
                            @else
                                <span class="text-xl">-</span>
                            @endif
                        @endunless
                        @if($transaction->crypto_currency)
                            {{ number_format($transaction->amount, 8, '.', ',') }} {{ strtoupper($transaction->crypto_currency) }}
                        @else
                            {{ Auth::user()->currency }}{{ number_format($transaction->amount, 2, '.', ',') }}
                        @endif
                    </h2>
                </div>
                <div class="text-right">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center
                        @if($isStatusReceipt && $receiptStatus === 'pending')
                            bg-amber-500/10 border border-amber-500/20
                        @elseif($isStatusReceipt)
                            bg-[#D55672]/10 border border-red-500/20 print:bg-red-50 print:border-red-200
                        @elseif($transaction->type === 'Credit')
                            bg-emerald-500/10 border border-emerald-500/20 print:bg-emerald-50 print:border-emerald-200
                        @else
                            bg-[#D55672]/10 border border-red-500/20 print:bg-red-50 print:border-red-200
                        @endif
                    ">
                        @if($isStatusReceipt && $receiptStatus === 'pending')
                            <i data-lucide="clock" class="w-6 h-6 text-[#3e4e83]"></i>
                        @elseif($isStatusReceipt)
                            <i data-lucide="x-circle" class="w-6 h-6 text-[#D55672]"></i>
                        @elseif($transaction->type === 'Credit')
                            <i data-lucide="arrow-down-left" class="w-6 h-6 text-emerald-400"></i>
                        @else
                            <i data-lucide="arrow-up-right" class="w-6 h-6 text-[#D55672]"></i>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm
                @if($isStatusReceipt && $receiptStatus === 'pending')
                    bg-amber-500/10 text-[#3e4e83] border border-amber-500/15
                @elseif($isStatusReceipt)
                    bg-[#D55672]/10 text-[#D55672] border border-red-500/15 print:bg-red-50 print:text-red-700
                @elseif($transaction->type === 'Credit')
                    bg-emerald-500/10 text-emerald-400 border border-emerald-500/15 print:bg-emerald-50 print:text-emerald-700
                @else
                    bg-[#D55672]/10 text-[#D55672] border border-red-500/15 print:bg-red-50 print:text-red-700
                @endif
            ">
                <i data-lucide="info" class="w-3.5 h-3.5"></i>
                <span class="text-sm font-medium">
                    @if($isStatusReceipt)
                        Transaction {{ ucfirst($transaction->status) }}
                    @elseif($transaction->type === 'Credit')
                        Funds received to your account
                    @else
                        Funds sent from your account
                    @endif
                </span>
            </div>
        </div>

        <!-- Transaction Details -->
        <div class="p-6">
            <h3 class="text-sm font-semibold text-[#4A4A4A] uppercase tracking-wider print:text-gray-900 mb-5 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-primary-50 print:bg-forest-50 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-primary-500 print:text-forest-600"></i>
                </div>
                Transaction Details
            </h3>

            <div class="space-y-5">
                <!-- Row 1: Transaction ID & Date -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Transaction ID</label>
                        <div class="flex items-center gap-2 bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                            <p class="text-sm font-mono text-[#0D1B23] print:text-gray-900 flex-1">{{ $transaction->txn_id }}</p>
                            <button onclick="copyToClipboard('{{ $transaction->txn_id }}')" 
                                    class="p-1.5 hover:bg-[#DFE5E5] rounded transition-colors print:hidden">
                                <i data-lucide="copy" class="w-4 h-4 text-[#7A7A7A]"></i>
                            </button>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Transaction Date</label>
                        <div class="flex items-center gap-2 bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                            <i data-lucide="calendar" class="w-4 h-4 text-[#7A7A7A] print:text-[#7A7A7A]"></i>
                            <p class="text-sm text-[#0D1B23] print:text-gray-900">
                                {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('F d, Y - g:i A') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Payment Method & Type -->
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Payment Method</label>
                        <div class="flex items-center gap-2 bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                            @if($transaction->payment_mode === 'Wire Transfer')
                                <i data-lucide="landmark" class="w-4 h-4 text-primary-500 print:text-forest-600"></i>
                            @elseif(in_array($transaction->payment_mode, ['Cryptocurrency', 'Bitcoin', 'Ethereum']))
                                <i data-lucide="bitcoin" class="w-4 h-4 text-[#3e4e83] print:text-amber-600"></i>
                            @elseif($transaction->payment_mode === 'Western Union')
                                <i data-lucide="globe" class="w-4 h-4 text-primary-500 print:text-forest-600"></i>
                            @else
                                <i data-lucide="credit-card" class="w-4 h-4 text-[#7A7A7A] print:text-[#7A7A7A]"></i>
                            @endif
                            <p class="text-sm font-medium text-[#0D1B23] print:text-gray-900">{{ $transaction->payment_mode }}</p>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Transaction Type</label>
                        <div class="flex items-center gap-2 bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded
                                @if($transaction->type === 'Credit')
                                    bg-emerald-500/10 text-emerald-400 print:bg-emerald-50 print:text-emerald-700
                                @else
                                    bg-[#D55672]/10 text-[#D55672] print:bg-red-50 print:text-red-700
                                @endif
                            ">
                                <span class="text-xs font-semibold uppercase">{{ $transaction->type }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recipient/Sender Information -->
                <div class="pt-5 border-t border-[#E0E5E5] print:border-gray-300">
                    <h4 class="text-sm font-semibold text-[#0D1B23] print:text-gray-900 mb-4 flex items-center gap-2">
                        <div class="w-6 h-6 rounded bg-[#DFE5E5] print:bg-gray-100 flex items-center justify-center">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-[#7A7A7A] print:text-[#7A7A7A]"></i>
                        </div>
                        @if($transaction->sender_name)
                            @if($transaction->type === 'Credit')
                                Originating Bank Information
                            @else
                                Destination Bank Information
                            @endif
                        @elseif($transaction->crypto_wallet_address)
                            Blockchain Transaction Information
                        @else
                            @if($transaction->type === 'Credit')
                                Sender Information
                            @else
                                Recipient Information
                            @endif
                        @endif
                    </h4>

                    {{-- Party Bank Information (For fiat transactions only) --}}
                    @if(!$transaction->crypto_currency && $transaction->sender_name)
                        <div class="bg-[#F9F9F9] print:bg-gray-50 border border-[#E0E5E5] print:border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded bg-[#DFE5E5] print:bg-gray-100 flex items-center justify-center">
                                    <i data-lucide="landmark" class="w-3.5 h-3.5 text-[#7A7A7A] print:text-[#7A7A7A]"></i>
                                </div>
                                <h5 class="text-sm font-semibold text-[#0D1B23] print:text-gray-900">
                                    @if($transaction->type === 'Credit')
                                        Sender Details
                                    @else
                                        Recipient Details
                                    @endif
                                </h5>
                                @if($transaction->reference_number)
                                    <span class="ml-auto text-xs font-mono px-2 py-1 rounded bg-white text-[#7A7A7A] border border-[#E0E5E5] print:bg-gray-100 print:text-gray-700">
                                        Ref: {{ $transaction->reference_number }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">
                                        @if($transaction->type === 'Credit')
                                            Sender Name
                                        @else
                                            Recipient Name
                                        @endif
                                    </label>
                                    <div class="bg-white print:bg-white rounded-lg p-3 border border-[#E0E5E5] print:border-gray-200">
                                        <p class="text-sm font-medium text-[#0D1B23] print:text-gray-900">{{ $transaction->sender_name }}</p>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">
                                        @if($transaction->type === 'Credit')
                                            Sender Bank
                                        @else
                                            Recipient Bank
                                        @endif
                                    </label>
                                    <div class="bg-white print:bg-white rounded-lg p-3 border border-[#E0E5E5] print:border-gray-200">
                                        <p class="text-sm font-medium text-[#0D1B23] print:text-gray-900">{{ $transaction->sender_bank }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4 mt-4">
                                <div class="space-y-1.5">
                                    <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">
                                        @if($transaction->type === 'Credit')
                                            Sender Account Number
                                        @else
                                            Recipient Account Number
                                        @endif
                                    </label>
                                    <div class="bg-white print:bg-white rounded-lg p-3 border border-[#E0E5E5] print:border-gray-200">
                                        <p class="text-sm font-mono text-[#0D1B23] print:text-gray-900">{{ $transaction->sender_account_number }}</p>
                                    </div>
                                </div>
                                @if($transaction->processed_at)
                                <div class="space-y-1.5">
                                    <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Processed On</label>
                                    <div class="bg-white print:bg-white rounded-lg p-3 border border-[#E0E5E5] print:border-gray-200 flex items-center gap-2">
                                        <i data-lucide="clock" class="w-4 h-4 text-[#7A7A7A] print:text-[#7A7A7A]"></i>
                                        <p class="text-sm text-[#0D1B23] print:text-gray-900">
                                            {{ \Carbon\Carbon::parse($transaction->processed_at)->format('M d, Y - g:i A') }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            @if($transaction->sender_bank_address)
                            <div class="space-y-1.5 mt-4">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">
                                    @if($transaction->type === 'Credit')
                                        Sender Bank Address
                                    @else
                                        Recipient Bank Address
                                    @endif
                                </label>
                                <div class="bg-white print:bg-white rounded-lg p-3 border border-[#E0E5E5] print:border-gray-200">
                                    <p class="text-sm text-[#4A4A4A] print:text-gray-700 leading-relaxed">{{ $transaction->sender_bank_address }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-[#E0E5E5] print:border-gray-200">
                                <div class="flex items-start gap-2 text-xs text-[#7A7A7A] print:text-[#7A7A7A]">
                                    <i data-lucide="shield-check" class="w-4 h-4 flex-shrink-0 mt-0.5 text-primary-500"></i>
                                    <p class="leading-relaxed">
                                        @if($transaction->type === 'Credit')
                                            Verified transaction from external banking institution. All sender information has been validated and recorded for audit purposes.
                                        @else
                                            Verified transaction to external banking institution. All recipient information has been validated and recorded for audit purposes.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Crypto Blockchain Transaction Details (wallet address + txn hash) --}}
                    @if($transaction->crypto_wallet_address || $transaction->crypto_txn_hash)
                        <div class="bg-[#F9F9F9] print:bg-gray-50 border border-[#E0E5E5] print:border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded bg-amber-500/10 print:bg-amber-50 flex items-center justify-center">
                                    <i data-lucide="link" class="w-3.5 h-3.5 text-[#3e4e83] print:text-amber-600"></i>
                                </div>
                                <h5 class="text-sm font-semibold text-[#0D1B23] print:text-gray-900">
                                    Blockchain Transaction Details
                                    @if($transaction->crypto_currency)
                                        <span class="text-xs font-normal text-[#3e4e83] print:text-amber-600 ml-1">
                                            ({{ strtoupper($transaction->crypto_currency) }})
                                        </span>
                                    @endif
                                </h5>
                            </div>

                            @if($transaction->crypto_wallet_address)
                            <div class="space-y-1.5 mb-4">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">
                                    @if($transaction->type === 'Credit')
                                        Sender Wallet Address
                                    @else
                                        Recipient Wallet Address
                                    @endif
                                </label>
                                <div class="bg-white print:bg-white rounded-lg p-3 border border-[#E0E5E5] print:border-gray-200">
                                    <p class="text-xs font-mono text-[#0D1B23] print:text-gray-900 break-all leading-relaxed">{{ $transaction->crypto_wallet_address }}</p>
                                    <button onclick="copyToClipboard('{{ $transaction->crypto_wallet_address }}')" 
                                            class="mt-2 text-xs text-[#7A7A7A] hover:text-[#0D1B23] print:hidden flex items-center gap-1.5 font-medium transition-colors">
                                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                        Copy Wallet Address
                                    </button>
                                </div>
                            </div>
                            @endif

                            @if($transaction->crypto_txn_hash)
                            <div class="space-y-1.5">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Transaction Hash (TXN ID)</label>
                                <div class="bg-white print:bg-white rounded-lg p-3 border border-[#E0E5E5] print:border-gray-200">
                                    <p class="text-xs font-mono text-[#0D1B23] print:text-gray-900 break-all leading-relaxed">{{ $transaction->crypto_txn_hash }}</p>
                                    <button onclick="copyToClipboard('{{ $transaction->crypto_txn_hash }}')" 
                                            class="mt-2 text-xs text-[#7A7A7A] hover:text-[#0D1B23] print:hidden flex items-center gap-1.5 font-medium transition-colors">
                                        <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                        Copy Transaction Hash
                                    </button>
                                </div>
                            </div>
                            @endif

                            <div class="mt-3 pt-3 border-t border-[#E0E5E5] print:border-gray-200">
                                <div class="flex items-start gap-2 text-xs text-[#7A7A7A] print:text-[#7A7A7A]">
                                    <i data-lucide="shield-check" class="w-4 h-4 flex-shrink-0 mt-0.5 text-[#3e4e83]"></i>
                                    <p class="leading-relaxed">
                                        Verified blockchain transaction. All on-chain details have been recorded for audit and compliance purposes.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Payment mode specific details (only for non-crypto transactions) --}}
                    @if(!$transaction->crypto_currency)
                    @if(in_array($transaction->payment_mode, ['Wire Transfer', 'Internal Transfer', 'Domestic Transfer', 'Local transfer']))
                        <!-- Bank Transfer Details -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Account Name</label>
                                <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                    <p class="text-sm font-medium text-[#0D1B23] print:text-gray-900">{{ $transaction->accountname }}</p>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Account Number</label>
                                <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                    <p class="text-sm font-mono text-[#0D1B23] print:text-gray-900">{{ $transaction->accountnumber }}</p>
                                </div>
                            </div>
                        </div>
                        @if($transaction->bankname)
                        <div class="space-y-1.5 mt-4">
                            <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Bank Name</label>
                            <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                <p class="text-sm font-medium text-[#0D1B23] print:text-gray-900">{{ $transaction->bankname }}</p>
                            </div>
                        </div>
                        @endif

                    @elseif($transaction->payment_mode === 'Western Union')
                        <!-- Western Union Details -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Recipient Name</label>
                                <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                    <p class="text-sm font-medium text-[#0D1B23] print:text-gray-900">{{ $transaction->accountname }}</p>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Country</label>
                                <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                    <p class="text-sm text-[#0D1B23] print:text-gray-900">{{ $transaction->country }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div class="space-y-1.5">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">City</label>
                                <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                    <p class="text-sm text-[#0D1B23] print:text-gray-900">{{ $transaction->city }}</p>
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Zip Code</label>
                                <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                    <p class="text-sm text-[#0D1B23] print:text-gray-900">{{ $transaction->zipcode }}</p>
                                </div>
                            </div>
                        </div>

                    @elseif(in_array($transaction->payment_mode, ['Cryptocurrency', 'Bitcoin', 'Ethereum', 'USDT']))
                        <!-- Cryptocurrency Details -->
                        <div class="space-y-1.5">
                            <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">Wallet Address</label>
                            <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                <p class="text-xs font-mono text-[#0D1B23] print:text-gray-900 break-all leading-relaxed">{{ $transaction->accountnumber }}</p>
                                <button onclick="copyToClipboard('{{ $transaction->accountnumber }}')" 
                                        class="mt-2 text-xs text-[#7A7A7A] hover:text-[#0D1B23] print:hidden flex items-center gap-1.5 font-medium transition-colors">
                                    <i data-lucide="copy" class="w-3.5 h-3.5"></i>
                                    Copy Wallet Address
                                </button>
                            </div>
                        </div>

                    @else
                        <!-- Email-based Payment Details -->
                        <div class="space-y-1.5">
                            <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold">
                                @if($transaction->type === 'Credit')
                                    Sender Email
                                @else
                                    Recipient Email
                                @endif
                            </label>
                            <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                                <p class="text-sm text-[#0D1B23] print:text-gray-900">{{ $transaction->accountnumber }}</p>
                            </div>
                        </div>
                    @endif
                    @endif {{-- end !crypto_currency --}}
                </div>

                <!-- Description -->
                @if($transaction->Description)
                <div class="pt-5 border-t border-[#E0E5E5] print:border-gray-300">
                    <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold block mb-2">Transaction Description</label>
                    <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                        <p class="text-sm text-[#4A4A4A] print:text-gray-700 leading-relaxed">{{ $transaction->Description }}</p>
                    </div>
                </div>
                @endif

                <!-- Transaction Processing Info (if processed by admin) -->
                {{-- @if($transaction->processed_at && $transaction->processed_by)
                <div class="pt-5 border-t border-[#E0E5E5] print:border-gray-300">
                    <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold block mb-2 flex items-center gap-2">
                        <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                        Transaction Processing Details
                    </label>
                    <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-4 border border-[#E0E5E5] print:border-gray-300">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-[#7A7A7A] print:text-[#7A7A7A] mb-1">Processed By</p>
                                <p class="text-sm font-medium text-[#0D1B23] print:text-gray-900">
                                    @if($transaction->processedByAdmin)
                                        {{ $transaction->processedByAdmin->firstName }} {{ $transaction->processedByAdmin->lastName }}
                                    @else
                                        Bank Administrator
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-[#7A7A7A] print:text-[#7A7A7A] mb-1">Processing Time</p>
                                <p class="text-sm text-[#0D1B23] print:text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaction->processed_at)->format('M d, Y - g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif --}}

                <!-- Account Balance (if available) -->
                @if($transaction->bal)
                <div class="pt-5 border-t border-[#E0E5E5] print:border-gray-300">
                    <label class="text-xs uppercase tracking-wider text-[#7A7A7A] print:text-[#7A7A7A] font-semibold block mb-2">Balance After Transaction</label>
                    <div class="bg-[#F9F9F9] print:bg-gray-50 rounded-lg p-3 border border-[#E0E5E5] print:border-gray-300">
                        <p class="text-base font-bold text-[#0D1B23] print:text-gray-900">
                            {{ Auth::user()->currency }}{{ number_format($transaction->bal, 2, '.', ',') }}
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="bg-[#F9F9F9]/30 print:bg-gray-50 p-5 border-t border-[#E0E5E5] print:border-gray-300">
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 rounded-lg bg-primary-50 print:bg-forest-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5 text-primary-500 print:text-forest-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-[#0D1B23] print:text-gray-900 mb-0.5">Important Notice</h4>
                    <p class="text-xs text-[#7A7A7A] print:text-[#7A7A7A] leading-relaxed">
                        This is an official transaction receipt from {{ $settings->site_name ?? 'your bank' }}. 
                        Please retain this receipt for your records. If you have any questions about this transaction, 
                        please contact us at {{ $settings->contact_email ?? 'support@bank.com' }} or through live chat.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Hide on Print) -->
    <div class="print:hidden flex flex-wrap gap-3 justify-center">
        <button onclick="window.print()" 
                class="px-5 py-2.5 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors font-medium text-sm flex items-center gap-2 text-[#4A4A4A]">
            <i data-lucide="printer" class="w-4 h-4"></i>
            <span>Print Receipt</span>
        </button>
        <button onclick="downloadPDF()" 
                class="px-5 py-2.5 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors font-medium text-sm flex items-center gap-2 text-[#4A4A4A]">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span>Download PDF</span>
        </button>
        <a href="{{ route('accounthistory') }}" 
           class="px-5 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-500 transition-colors font-medium text-sm flex items-center gap-2 text-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Transactions</span>
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i data-lucide="check" class="w-4 h-4 text-primary-500"></i>';
            lucide.createIcons();
            setTimeout(() => {
                button.innerHTML = originalHTML;
                lucide.createIcons();
            }, 2000);
        });
    }

    function downloadPDF() {
        window.print();
    }
</script>

<style>
@media print {
    body {
        background: white !important;
    }
    
    .bg-white\/60,
    .bg-[#F9F9F9]\/40,
    .bg-[#F9F9F9]\/30 {
        background: white !important;
        box-shadow: none !important;
    }
    
    .text-[#0D1B23] {
        color: #1a202c !important;
    }
    
    .text-[#4A4A4A],
    .text-[#7A7A7A],
    .text-[#7A7A7A] {
        color: #4a5568 !important;
    }
    
    .border-[#E0E5E5],
    .border-[#E0E5E5]\/40,
    .border-[#E0E5E5]\/50 {
        border-color: #e2e8f0 !important;
    }
    
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>

@endsection

