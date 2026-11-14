@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<style>
@media print {
    .no-print { display: none !important; }
    .print-block { display: block !important; }
    body { background: white !important; }
    .receipt-card, .detail-field { background: white !important; color: black !important; border-color: #ddd !important; }
}
</style>

<div class="max-w-3xl mx-auto px-4 py-6 space-y-5">

    {{-- Header --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('accounthistory') }}" class="no-print w-9 h-9 flex items-center justify-center rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors text-[#7A7A7A] hover:text-[#0D1B23]">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
        </a>
        <div class="flex-1 min-w-0">
            <h1 class="text-lg font-semibold text-[#0D1B23]">Deposit Receipt</h1>
            <p class="text-xs text-[#7A7A7A] font-mono truncate">{{ $transaction->txn_id }}</p>
        </div>
        <button onclick="navigator.clipboard.writeText('{{ $transaction->txn_id }}')" class="no-print w-9 h-9 flex items-center justify-center rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors text-[#7A7A7A] hover:text-[#0D1B23]" title="Copy Transaction ID">
            <i data-lucide="copy" class="w-4 h-4"></i>
        </button>
    </div>

    {{-- Amount Card --}}
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 space-y-3">
        <span class="inline-flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wider text-[#28A745]">
            <i data-lucide="arrow-down-left" class="w-3.5 h-3.5"></i>
            Deposit
        </span>

        <div class="text-3xl font-semibold text-[#0D1B23] font-mono">
            +{{ Auth::user()->currency }}{{ number_format($transaction->amount, 2, '.', ',') }}
        </div>

        <div class="flex items-center justify-between gap-3 pt-1">
            <span class="text-xs text-[#7A7A7A] font-mono">
                {{ $transaction->created_at ? \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y • g:i A') : 'N/A' }}
            </span>
            @php $statusLower = strtolower($transaction->status); @endphp
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold
                {{ $statusLower === 'pending' ? 'bg-[#3e4e83]/10 text-[#3e4e83] border border-[#3e4e83]/20' : 'bg-[#28A745]/10 text-[#28A745] border border-[#28A745]/20' }}">
                <i data-lucide="{{ $statusLower === 'pending' ? 'clock' : 'check-circle-2' }}" class="w-3.5 h-3.5"></i>
                {{ $transaction->status }}
            </span>
        </div>
    </div>

    {{-- Details --}}
    <div class="bg-white border border-[#E0E5E5] rounded-xl divide-y divide-[#E0E5E5]">
        {{-- Payment Method --}}
        <div class="p-4 flex items-center justify-between gap-3">
            <span class="text-xs uppercase tracking-wider text-[#7A7A7A] font-semibold flex items-center gap-1.5">
                <i data-lucide="credit-card" class="w-3 h-3"></i>
                Payment Method
            </span>
            <span class="inline-flex items-center gap-2 text-sm font-semibold text-primary-400 bg-primary-50 border border-primary-200 rounded-lg px-3 py-1.5">
                @if($transaction->payment_mode === 'Bank Transfer')
                    <i data-lucide="landmark" class="w-4 h-4"></i>
                @elseif($transaction->payment_mode === 'Cash')
                    <i data-lucide="banknote" class="w-4 h-4"></i>
                @elseif($transaction->payment_mode === 'Check')
                    <i data-lucide="file-check" class="w-4 h-4"></i>
                @else
                    <i data-lucide="wallet" class="w-4 h-4"></i>
                @endif
                {{ $transaction->payment_mode }}
            </span>
        </div>

        {{-- Type --}}
        <div class="p-4 flex items-center justify-between gap-3">
            <span class="text-xs uppercase tracking-wider text-[#7A7A7A] font-semibold flex items-center gap-1.5">
                <i data-lucide="repeat" class="w-3 h-3"></i>
                Type
            </span>
            <span class="text-sm font-semibold text-[#0D1B23]">Incoming Deposit</span>
        </div>
    </div>

    {{-- Actions --}}
    <div class="no-print flex flex-wrap gap-3 pt-1">
        <button onclick="window.print()" class="flex-1 min-w-[130px] flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors text-sm font-semibold text-[#4A4A4A]">
            <i data-lucide="printer" class="w-4 h-4"></i>
            Print
        </button>
        <button onclick="window.print()" class="flex-1 min-w-[130px] flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors text-sm font-semibold text-[#4A4A4A]">
            <i data-lucide="download" class="w-4 h-4"></i>
            Download
        </button>
        <a href="{{ route('accounthistory') }}" class="flex-1 min-w-[130px] flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors text-sm font-semibold text-white">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to History
        </a>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection

