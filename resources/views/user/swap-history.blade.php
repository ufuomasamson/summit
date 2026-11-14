@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="space-y-6">

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0D1B23] mb-1">Swap History</h1>
            <p class="text-[#7A7A7A] text-sm">View all your crypto swap transactions.</p>
        </div>
        <a href="{{ route('assetbalance') }}" class="px-4 py-2 bg-primary-600 hover:bg-primary-500 rounded-lg text-sm text-white transition-colors">
            <i data-lucide="arrow-right-left" class="w-4 h-4 inline-block mr-1"></i> Swap
        </a>
    </div>

    <!-- History Table -->
    <div class="bg-white rounded-xl border border-[#E0E5E5] overflow-hidden">
        @if(count($transactions) > 0)
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-[#E0E5E5]">
                        <th class="text-left px-4 py-3 text-xs uppercase tracking-wider font-semibold text-[#7A7A7A]">From</th>
                        <th class="text-left px-4 py-3 text-xs uppercase tracking-wider font-semibold text-[#7A7A7A]">To</th>
                        <th class="text-right px-4 py-3 text-xs uppercase tracking-wider font-semibold text-[#7A7A7A]">Amount Sent</th>
                        <th class="text-right px-4 py-3 text-xs uppercase tracking-wider font-semibold text-[#7A7A7A]">Received</th>
                        <th class="text-center px-4 py-3 text-xs uppercase tracking-wider font-semibold text-[#7A7A7A]">Status</th>
                        <th class="text-right px-4 py-3 text-xs uppercase tracking-wider font-semibold text-[#7A7A7A]">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E0E5E5]">
                    @foreach($transactions as $swap)
                    <tr class="hover:bg-[#DFE5E5]/20 transition-colors">
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-[#DFE5E5] text-xs font-semibold text-[#0D1B23]">
                                {{ strtoupper($swap->source) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-1 rounded-md bg-[#DFE5E5] text-xs font-semibold text-[#0D1B23]">
                                {{ strtoupper($swap->dest) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-[#4A4A4A] font-medium">
                            {{ $swap->source === 'usd' ? number_format($swap->amount, 2) : number_format($swap->amount, 8) }} {{ strtoupper($swap->source) }}
                        </td>
                        <td class="px-4 py-3 text-right text-[#0D1B23] font-semibold">
                            {{ $swap->dest === 'usd' ? number_format($swap->quantity, 2) : number_format($swap->quantity, 8) }} {{ strtoupper($swap->dest) }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ ($swap->status ?? 'completed') === 'completed' ? 'bg-[#28A745]/10 text-[#28A745]' : 'bg-[#3e4e83]/10 text-[#3e4e83]' }}">
                                {{ ucfirst($swap->status ?? 'completed') }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right text-[#7A7A7A] text-xs">
                            {{ \Carbon\Carbon::parse($swap->created_at)->format('M d, Y H:i') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-[#E0E5E5]">
            {{ $transactions->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#DFE5E5] flex items-center justify-center">
                <i data-lucide="arrow-right-left" class="w-8 h-8 text-[#7A7A7A]"></i>
            </div>
            <h3 class="text-lg text-[#4A4A4A] font-semibold mb-1">No Swap History</h3>
            <p class="text-sm text-[#7A7A7A] mb-4">You haven't made any crypto swaps yet.</p>
            <a href="{{ route('assetbalance') }}" class="inline-block px-5 py-2.5 bg-primary-600 hover:bg-primary-500 rounded-lg text-sm text-white transition-colors">
                Start Swapping
            </a>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => { setTimeout(() => lucide.createIcons(), 100); });
</script>
@endsection

