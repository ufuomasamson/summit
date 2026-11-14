@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="space-y-6">
    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-[#0D1B23] mb-1">My Investments</h1>
        <p class="text-sm text-[#7A7A7A]">Track and manage your investment portfolio</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
            <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mb-3">
                <i data-lucide="briefcase" class="w-4 h-4 text-[#49786B]"></i>
            </div>
            <p class="text-xs text-[#7A7A7A] mb-0.5">Active</p>
            <h3 class="text-xl font-semibold text-[#0D1B23]">{{ $stats['active'] }}</h3>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
            <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mb-3">
                <i data-lucide="dollar-sign" class="w-4 h-4 text-[#28A745]"></i>
            </div>
            <p class="text-xs text-[#7A7A7A] mb-0.5">Total Invested</p>
            <h3 class="text-lg font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($stats['total_invested'], 2) }}</h3>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
            <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mb-3">
                <i data-lucide="trending-up" class="w-4 h-4 text-[#28A745]"></i>
            </div>
            <p class="text-xs text-[#7A7A7A] mb-0.5">Total Returns</p>
            <h3 class="text-lg font-semibold text-[#49786B]">{{ $settings->currency }}{{ number_format($stats['total_returns'], 2) }}</h3>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
            <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center mb-3">
                <i data-lucide="clock" class="w-4 h-4 text-[#3e4e83]"></i>
            </div>
            <p class="text-xs text-[#7A7A7A] mb-0.5">Pending</p>
            <h3 class="text-xl font-semibold text-[#0D1B23]">{{ $stats['pending'] }}</h3>
        </div>
    </div>

    <!-- Quick Action -->
    <a href="{{ route('investments.index') }}" class="block bg-white border border-[#E0E5E5] rounded-xl p-4 hover:border-[#49786B]/40 hover:bg-[#F9F9F9] transition-colors group">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-[#49786B] flex items-center justify-center">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-[#0D1B23]">New Investment</h3>
                    <p class="text-xs text-[#7A7A7A]">Browse available investment plans</p>
                </div>
            </div>
            <i data-lucide="chevron-right" class="w-4 h-4 text-[#7A7A7A] group-hover:text-[#0D1B23] transition-colors"></i>
        </div>
    </a>

    <!-- Investments List -->
    <div>
        <h3 class="text-base font-semibold text-[#0D1B23] mb-4">All Investments</h3>
        
        <div class="space-y-3">
            @forelse($investments as $investment)
            <a href="{{ route('investments.view', $investment->id) }}" 
               class="block bg-white border border-[#E0E5E5] rounded-xl p-5 hover:border-[#C8D1D1] transition-colors group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg {{ $investment->active == 'yes' ? 'bg-[#49786B]' : 'bg-[#BFC9C9]' }} flex items-center justify-center">
                            <i data-lucide="trending-up" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-[#0D1B23]">{{ $investment->plan_name }}</h4>
                            <p class="text-xs text-[#7A7A7A]">ID: #{{ $investment->id }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        @if($investment->active == 'yes')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#28A745]/15 text-[#28A745] border border-[#28A745]/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#28A745] mr-1.5"></span>
                                Active
                            </span>
                        @elseif($investment->active == 'Pending')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#3e4e83]/15 text-[#3e4e83] border border-[#3e4e83]/20">
                                Pending
                            </span>
                        @elseif($investment->active == 'expired')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#BFC9C9]/15 text-[#7A7A7A] border border-[#C8D1D1]/30">
                                Expired
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-[#D55672]/15 text-[#D55672] border border-[#D55672]/20">
                                {{ $investment->active }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 mb-4">
                    <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                        <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Invested</p>
                        <p class="text-sm font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</p>
                    </div>
                    <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                        <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">ROI Received</p>
                        <p class="text-sm font-semibold text-[#49786B]">{{ $settings->currency }}{{ number_format($investment->roi_received, 2) }}</p>
                    </div>
                    <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                        <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Started</p>
                        <p class="text-sm font-semibold text-[#0D1B23]">{{ $investment->activated_at ? $investment->activated_at->format('M d, Y') : 'N/A' }}</p>
                    </div>
                    <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                        <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Expires</p>
                        <p class="text-sm font-semibold text-[#0D1B23]">{{ $investment->expire_date ? $investment->expire_date->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>

                @if($investment->active == 'yes' && $investment->expire_date)
                    @php
                        $now = now();
                        $total = $investment->activated_at->diffInDays($investment->expire_date);
                        $elapsed = $investment->activated_at->diffInDays($now);
                        $progress = $total > 0 ? min(100, ($elapsed / $total) * 100) : 0;
                        $remaining = $investment->expire_date->diffInDays($now);
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-[#7A7A7A]">Progress</span>
                            <span class="text-[#7A7A7A]">{{ $remaining }} days remaining</span>
                        </div>
                        <div class="w-full bg-[#DFE5E5] rounded-full h-1.5 overflow-hidden">
                            <div class="h-full bg-[#49786B] rounded-full transition-all duration-500" 
                                 style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                @endif

                <div class="flex items-center justify-end mt-3 text-xs text-[#7A7A7A] group-hover:text-[#0D1B23] transition-colors">
                    <span>View Details</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5 ml-1"></i>
                </div>
            </a>
            @empty
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-12 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="inbox" class="w-7 h-7 text-[#7A7A7A]"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#0D1B23] mb-1">No Investments Yet</h3>
                <p class="text-sm text-[#4A4A4A] mb-5">Start growing your wealth today with our investment plans</p>
                <a href="{{ route('investments.index') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-[#49786B] hover:bg-[#3B645A] rounded-lg font-medium text-sm text-white transition-colors">
                    <i data-lucide="trending-up" class="w-4 h-4"></i>
                    <span>Browse Plans</span>
                </a>
            </div>
            @endforelse
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection
