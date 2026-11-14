@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ amount: '', confirmModal: false }" class="space-y-6">
    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Header with Back Button -->
    <div class="flex items-center space-x-3">
        <a href="{{ route('investments.index') }}" class="w-9 h-9 rounded-lg bg-white border border-[#E0E5E5] flex items-center justify-center hover:bg-[#DFE5E5] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 text-[#4A4A4A]"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">{{ $plan->name }}</h1>
            <p class="text-sm text-[#7A7A7A]">Complete your investment</p>
        </div>
    </div>

    <!-- Current Balance Card -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-[#7A7A7A] mb-1">Available Balance</p>
                <h2 class="text-xl font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</h2>
            </div>
            <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                <i data-lucide="wallet" class="w-5 h-5 text-[#49786B]"></i>
            </div>
        </div>
    </div>

    <!-- Plan Details -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6">
        <div class="flex items-center space-x-3 mb-5">
            <div class="w-10 h-10 rounded-lg bg-[#49786B] flex items-center justify-center">
                <i data-lucide="trending-up" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-[#0D1B23]">{{ $plan->name }}</h3>
                <p class="text-xs text-[#7A7A7A]">Investment Details</p>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Minimum</p>
                <p class="text-sm font-semibold text-[#0D1B23]">{{ $settings->currency }}{{ number_format($plan->min_price, 2) }}</p>
            </div>
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3">
                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Maximum</p>
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
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3 col-span-2">
                <p class="text-[11px] text-[#7A7A7A] mb-1 uppercase tracking-wide">Capital Return</p>
                <p class="text-sm font-semibold {{ $plan->return_capital ? 'text-[#28A745]' : 'text-[#3e4e83]' }}">
                    {{ $plan->return_capital ? 'Capital + ROI Returned' : 'Only ROI Returned' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Investment Form -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6">
        <h3 class="text-base font-semibold text-[#0D1B23] mb-4">Enter Investment Amount</h3>
        
        <form method="POST" action="{{ route('investments.process') }}" id="investForm">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">

            <div class="mb-5">
                <label class="block text-sm text-[#4A4A4A] mb-2">Investment Amount ({{ $settings->currency }})</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A] text-base">{{ $settings->currency }}</span>
                    <input type="number" 
                           name="amount" 
                           x-model="amount"
                           class="w-full bg-white border border-[#C8D1D1] rounded-lg px-4 py-3 pl-10 text-base font-medium text-[#0D1B23] focus:outline-none focus:border-[#49786B]/50 focus:ring-1 focus:ring-[#49786B]/20 transition-colors"
                           placeholder="0.00"
                           min="{{ $plan->min_price }}"
                           max="{{ $plan->max_price }}"
                           step="0.01"
                           required>
                </div>
                <div class="flex items-center justify-between mt-2 text-xs text-[#7A7A7A]">
                    <span>Min: {{ $settings->currency }}{{ number_format($plan->min_price, 2) }}</span>
                    <span>Max: {{ $settings->currency }}{{ number_format($plan->max_price, 2) }}</span>
                </div>
            </div>

            <!-- Expected Return Calculation -->
            <div x-show="amount >= {{ $plan->min_price }} && amount <= {{ $plan->max_price }}" 
                 x-transition
                 class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-4 mb-5">
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-sm text-[#4A4A4A]">Your Investment</span>
                    <span class="text-sm font-semibold text-[#0D1B23]" x-text="'{{ $settings->currency }}' + parseFloat(amount || 0).toFixed(2)"></span>
                </div>
                <div class="flex items-center justify-between mb-2.5">
                    <span class="text-sm text-[#4A4A4A]">Expected ROI</span>
                    <span class="text-sm font-semibold text-[#49786B]">
                        @if($plan->roi_type == 'percentage')
                            <span x-text="'{{ $settings->currency }}' + (parseFloat(amount || 0) * {{ $plan->roi_value }} / 100).toFixed(2)"></span>
                        @else
                            <span>{{ $settings->currency }}{{ number_format($plan->roi_value, 2) }}</span>
                        @endif
                    </span>
                </div>
                @if($plan->return_capital)
                <div class="flex items-center justify-between pt-2.5 border-t border-[#C8D1D1]">
                    <span class="text-sm font-medium text-[#0D1B23]">Total Return</span>
                    <span class="text-base font-semibold text-[#49786B]">
                        @if($plan->roi_type == 'percentage')
                            <span x-text="'{{ $settings->currency }}' + (parseFloat(amount || 0) + (parseFloat(amount || 0) * {{ $plan->roi_value }} / 100)).toFixed(2)"></span>
                        @else
                            <span x-text="'{{ $settings->currency }}' + (parseFloat(amount || 0) + {{ $plan->roi_value }}).toFixed(2)"></span>
                        @endif
                    </span>
                </div>
                @endif
            </div>

            <!-- Terms Agreement -->
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-4 mb-5">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" required class="mt-0.5 w-4 h-4 rounded border-[#C8D1D1] text-[#49786B] focus:ring-[#49786B]/30 bg-white">
                    <span class="text-sm text-[#4A4A4A]">
                        I understand that my investment of <span class="text-[#0D1B23] font-medium" x-text="'{{ $settings->currency }}' + parseFloat(amount || 0).toFixed(2)"></span> 
                        will be locked for {{ $plan->duration }} {{ $plan->duration_type }} and returns will be automatically credited after the duration.
                    </span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    :disabled="!amount || amount < {{ $plan->min_price }} || amount > {{ $plan->max_price }} || amount > {{ Auth::user()->account_bal }}"
                    class="w-full px-6 py-3 bg-[#49786B] hover:bg-[#3B645A] rounded-lg font-medium text-white transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                <i data-lucide="lock" class="w-4 h-4 inline mr-1.5"></i>
                Confirm Investment
            </button>

            <p class="text-center text-xs text-[#7A7A7A] mt-3">
                <i data-lucide="shield-check" class="w-3 h-3 inline mr-1"></i>
                Your investment is secured with bank-grade encryption
            </p>
        </form>
    </div>

    <!-- Important Information -->
    <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 rounded-lg bg-[#DFE5E5] flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-[#3e4e83]"></i>
            </div>
            <div>
                <h4 class="text-sm font-semibold text-[#0D1B23] mb-2">Important Information</h4>
                <ul class="space-y-2 text-sm text-[#4A4A4A]">
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#28A745] mt-0.5 flex-shrink-0"></i>
                        <span>The investment amount will be immediately deducted from your balance</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#28A745] mt-0.5 flex-shrink-0"></i>
                        <span>Returns will be automatically calculated and credited at maturity</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <i data-lucide="check" class="w-3.5 h-3.5 text-[#28A745] mt-0.5 flex-shrink-0"></i>
                        <span>You can track your investment progress anytime from "My Investments"</span>
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
