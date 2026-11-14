@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="space-y-6">
    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Header -->
    <div>
        <h1 class="text-2xl md:text-3xl font-semibold text-[#0D1B23] mb-1">Investment Plans</h1>
        <p class="text-sm text-[#7A7A7A]">Choose a plan and start earning returns on your investment</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-3 gap-3 md:gap-4">
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
            <div class="flex items-center space-x-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="trending-up" class="w-4 h-4 text-[#28A745]"></i>
                </div>
            </div>
            <p class="text-xs text-[#7A7A7A] mb-0.5">Active</p>
            <h3 class="text-lg font-semibold text-[#0D1B23]">{{ $myInvestments }}</h3>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
            <div class="flex items-center space-x-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-[#3e4e83]"></i>
                </div>
            </div>
            <p class="text-xs text-[#7A7A7A] mb-0.5">Invested</p>
            <h3 class="text-lg font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($totalInvested, 2) }}</h3>
        </div>

        <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
            <div class="flex items-center space-x-2 mb-3">
                <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-4 h-4 text-[#28A745]"></i>
                </div>
            </div>
            <p class="text-xs text-[#7A7A7A] mb-0.5">Returns</p>
            <h3 class="text-lg font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($totalReturns, 2) }}</h3>
        </div>
    </div>

    <!-- Quick Link to My Investments -->
    @if($myInvestments > 0)
    <a href="{{ route('investments.my-investments') }}" class="block bg-white border border-[#E0E5E5] rounded-xl p-4 hover:border-[#49786B]/40 hover:bg-[#F9F9F9] transition-colors group">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-9 h-9 rounded-lg bg-[#49786B]/15 flex items-center justify-center">
                    <i data-lucide="briefcase" class="w-4 h-4 text-[#49786B]"></i>
                </div>
                <div>
                    <h3 class="font-medium text-[#0D1B23] text-sm">My Investments</h3>
                    <p class="text-xs text-[#7A7A7A]">View your {{ $myInvestments }} active investment(s)</p>
                </div>
            </div>
            <i data-lucide="chevron-right" class="w-4 h-4 text-[#7A7A7A] group-hover:text-[#0D1B23] transition-colors"></i>
        </div>
    </a>
    @endif

    <!-- Investment Plans -->
    <div>
        <h3 class="text-base font-semibold text-[#0D1B23] mb-4">Available Plans</h3>

        <div class="space-y-4">
            @forelse($plans as $plan)
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6 hover:border-[#C8D1D1] transition-colors">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <!-- Plan Info -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 rounded-lg bg-[#49786B] flex items-center justify-center">
                                <i data-lucide="bar-chart-3" class="w-5 h-5 text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-[#0D1B23]">{{ $plan->name }}</h4>
                                <p class="text-xs text-[#7A7A7A]">Investment Plan</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5 mb-4">
                            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Min Amount</p>
                                <p class="text-sm font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($plan->min_price, 2) }}</p>
                            </div>
                            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Max Amount</p>
                                <p class="text-sm font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($plan->max_price, 2) }}</p>
                            </div>
                            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">ROI</p>
                                <p class="text-sm font-semibold text-[#49786B]">
                                    @if($plan->roi_type == 'percentage')
                                        {{ $plan->roi_value }}%
                                    @else
                                        {{ $settings->currency }}{{ number_format($plan->roi_value, 2) }}
                                    @endif
                                </p>
                            </div>
                            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Duration</p>
                                <p class="text-sm font-semibold text-[#0D1B23]">{{ $plan->duration }} {{ ucfirst($plan->duration_type) }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4 text-xs">
                            @if($plan->return_capital)
                                <span class="flex items-center space-x-1.5 text-[#28A745]">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                    <span>Capital Returned</span>
                                </span>
                            @else
                                <span class="flex items-center space-x-1.5 text-[#3e4e83]">
                                    <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i>
                                    <span>Capital Not Returned</span>
                                </span>
                            @endif
                            <span class="flex items-center space-x-1.5 text-[#7A7A7A]">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                                <span>Secure Investment</span>
                            </span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="flex-shrink-0 md:ml-6">
                        <a href="{{ route('investments.invest', $plan->id) }}" 
                           class="block px-6 py-2.5 bg-[#49786B] hover:bg-[#3B645A] rounded-lg font-medium text-sm text-white transition-colors text-center">
                            Invest Now
                            <i data-lucide="arrow-right" class="w-4 h-4 inline ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-12 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-full bg-[#DFE5E5] flex items-center justify-center">
                    <i data-lucide="package" class="w-7 h-7 text-[#7A7A7A]"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#0D1B23] mb-1">No Plans Available</h3>
                <p class="text-sm text-[#4A4A4A]">Investment plans are currently being updated. Please check back soon.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Information Section -->
    <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-lucide="info" class="w-4 h-4 text-[#49786B]"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-[#0D1B23] mb-2">How Investments Work</h4>
                <ul class="space-y-2 text-sm text-[#4A4A4A]">
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#28A745] mt-0.5 flex-shrink-0"></i>
                        <span>Choose a plan that fits your investment goals</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#28A745] mt-0.5 flex-shrink-0"></i>
                        <span>Investment amount will be deducted from your account balance</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#28A745] mt-0.5 flex-shrink-0"></i>
                        <span>Returns are automatically credited at the end of duration</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#28A745] mt-0.5 flex-shrink-0"></i>
                        <span>Track all your investments from the "My Investments" page</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection
