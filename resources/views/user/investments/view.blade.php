@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="space-y-6">
    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Header with Back Button -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('investments.my-investments') }}" class="w-9 h-9 rounded-lg bg-white border border-[#E0E5E5] flex items-center justify-center hover:bg-[#DFE5E5] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 text-[#4A4A4A]"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">Investment Details</h1>
            <p class="text-sm text-[#7A7A7A]">Investment #{{ $investment->id }}</p>
        </div>
    </div>

    <!-- Status Card -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-5">
            <div class="flex items-center space-x-3 mb-4 md:mb-0">
                <div class="w-10 h-10 rounded-lg {{ $investment->active == 'yes' ? 'bg-[#49786B]' : 'bg-[#BFC9C9]' }} flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-[#0D1B23]">{{ $investment->plan_name }}</h2>
                    <p class="text-xs text-[#7A7A7A]">{{ $investment->inv_duration ?? $investment->plan_details->formatted_duration ?? 'N/A' }}</p>
                </div>
            </div>
            <div>
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

        <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5">
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Investment Amount</p>
                <p class="text-base font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</p>
            </div>
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">ROI Received</p>
                <p class="text-base font-semibold text-[#49786B]">{{ $settings->currency }}{{ number_format($investment->roi_received, 2) }}</p>
            </div>
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Started On</p>
                <p class="text-sm font-semibold text-[#0D1B23]">{{ $investment->activated_at ? $investment->activated_at->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Expires On</p>
                <p class="text-sm font-semibold text-[#0D1B23]">{{ $investment->expire_date ? $investment->expire_date->format('M d, Y') : 'N/A' }}</p>
            </div>
        </div>
    </div>

    <!-- Progress Card (for active investments) -->
    @if($investment->active == 'yes' && $investment->expire_date)
        @php
            $now = now();
            $total = $investment->activated_at->diffInDays($investment->expire_date);
            $elapsed = $investment->activated_at->diffInDays($now);
            $progress = $total > 0 ? min(100, ($elapsed / $total) * 100) : 0;
            $remaining = $investment->expire_date->diffInDays($now);
        @endphp
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-[#0D1B23]">Investment Progress</h3>
                <span class="text-xs text-[#7A7A7A]">{{ number_format($progress, 1) }}% Complete</span>
            </div>
            
            <div class="w-full bg-[#DFE5E5] rounded-full h-1.5 overflow-hidden mb-4">
                <div class="h-full bg-[#49786B] rounded-full transition-all duration-500" 
                     style="width: {{ $progress }}%"></div>
            </div>

            <div class="grid grid-cols-3 gap-4 text-center">
                <div>
                    <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Days Elapsed</p>
                    <p class="text-base font-semibold text-[#0D1B23]">{{ $elapsed }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Days Remaining</p>
                    <p class="text-base font-semibold text-[#49786B]">{{ $remaining }}</p>
                </div>
                <div>
                    <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Total Days</p>
                    <p class="text-base font-semibold text-[#0D1B23]">{{ $total }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Investment Details -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
        <h3 class="text-base font-semibold text-[#0D1B23] mb-4">Investment Information</h3>
        
        <div class="space-y-0">
            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Investment ID</span>
                <span class="text-sm font-medium text-[#0D1B23]">#{{ $investment->id }}</span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Plan Name</span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $investment->plan_name }}</span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Investment Amount</span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">ROI Received</span>
                <span class="text-sm font-medium text-[#49786B]">{{ $settings->currency }}{{ number_format($investment->roi_received, 2) }}</span>
            </div>

            @if($investment->plan_details)
            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Plan ROI</span>
                <span class="text-sm font-medium text-[#0D1B23]">
                    @if($investment->plan_details->roi_type == 'percentage')
                        {{ $investment->plan_details->roi_value }}%
                    @else
                        {{ $settings->currency }}{{ number_format($investment->plan_details->roi_value, 2) }}
                    @endif
                </span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Capital Return</span>
                <span class="text-sm font-medium {{ $investment->plan_details->return_capital ? 'text-[#28A745]' : 'text-[#3e4e83]' }}">
                    {{ $investment->plan_details->return_capital ? 'Yes' : 'No' }}
                </span>
            </div>
            @endif

            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Capital Returned</span>
                <span class="text-sm font-medium {{ $investment->capital_returned ? 'text-[#28A745]' : 'text-[#7A7A7A]' }}">
                    {{ $investment->capital_returned ? 'Yes' : 'No' }}
                </span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Status</span>
                <span class="text-sm font-medium {{ $investment->active == 'yes' ? 'text-[#28A745]' : 'text-[#7A7A7A]' }}">
                    {{ ucfirst($investment->active) }}
                </span>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Created At</span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $investment->created_at->format('M d, Y h:i A') }}</span>
            </div>

            @if($investment->activated_at)
            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Activated At</span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $investment->activated_at->format('M d, Y h:i A') }}</span>
            </div>
            @endif

            @if($investment->expire_date)
            <div class="flex items-center justify-between py-3 border-b border-[#E0E5E5]">
                <span class="text-sm text-[#4A4A4A]">Maturity Date</span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $investment->expire_date->format('M d, Y h:i A') }}</span>
            </div>
            @endif

            @if($investment->last_growth)
            <div class="flex items-center justify-between py-3">
                <span class="text-sm text-[#4A4A4A]">Last Updated</span>
                <span class="text-sm font-medium text-[#0D1B23]">{{ $investment->last_growth->format('M d, Y h:i A') }}</span>
            </div>
            @endif
        </div>
    </div>

    <!-- Expected Return Calculation -->
    @if($investment->active == 'yes' && $investment->plan_details && !$investment->capital_returned)
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                <i data-lucide="calculator" class="w-4 h-4 text-[#49786B]"></i>
            </div>
            <h3 class="text-base font-semibold text-[#0D1B23]">Expected at Maturity</h3>
        </div>

        @php
            $expectedRoi = 0;
            if ($investment->plan_details->roi_type == 'percentage') {
                $expectedRoi = ($investment->amount * $investment->plan_details->roi_value) / 100;
            } else {
                $expectedRoi = $investment->plan_details->roi_value;
            }
            $totalReturn = $investment->plan_details->return_capital ? ($investment->amount + $expectedRoi) : $expectedRoi;
        @endphp

        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-sm text-[#4A4A4A]">Expected ROI</span>
                <span class="text-sm font-semibold text-[#49786B]">{{ $settings->currency }}{{ number_format($expectedRoi, 2) }}</span>
            </div>
            @if($investment->plan_details->return_capital)
            <div class="flex items-center justify-between">
                <span class="text-sm text-[#4A4A4A]">Capital Return</span>
                <span class="text-sm font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($investment->amount, 2) }}</span>
            </div>
            @endif
            <div class="flex items-center justify-between pt-3 border-t border-[#E0E5E5]">
                <span class="text-sm font-medium text-[#0D1B23]">Total Expected</span>
                <span class="text-lg font-semibold text-[#49786B]">{{ $settings->currency }}{{ number_format($totalReturn, 2) }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Back Button -->
    <div class="flex justify-center">
        <a href="{{ route('investments.my-investments') }}" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-white border border-[#E0E5E5] hover:bg-[#DFE5E5] rounded-lg text-sm font-medium text-[#4A4A4A] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to My Investments</span>
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection
