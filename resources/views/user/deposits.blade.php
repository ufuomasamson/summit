@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ selectedMethod: '' }" class="space-y-6 pb-24">

    <x-danger-alert />
    <x-success-alert />

    <!-- Page Header -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-600 flex items-center justify-center">
                <i data-lucide="wallet" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-[#0D1B23]">Make a Deposit</h1>
                <p class="text-[#7A7A7A] text-sm">Fund your account with your preferred payment method</p>
            </div>
        </div>
    </div>

    <!-- Deposit Form -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <form action="{{ route('newdeposit') }}" method="post" class="space-y-6">
            @csrf
            
            <!-- Payment Method Selection -->
            <div class="space-y-3">
                <label class="flex items-center space-x-2 text-[#0D1B23] font-semibold mb-4">
                    <i data-lucide="credit-card" class="w-5 h-5 text-[#7A7A7A]"></i>
                    <span>Select Payment Method</span>
                </label>
                
                @forelse ($dmethods as $method)
                    <label class="payment-method-card cursor-pointer block">
                        <input type="radio" 
                               name="payment_method" 
                               value="{{ $method->name }}" 
                               x-model="selectedMethod"
                               class="hidden payment-method-input"
                               required>
                        <div class="payment-method-content p-5 rounded-xl border-2 transition-all duration-200"
                             :class="selectedMethod === '{{ $method->name }}' ? 'border-primary-500 bg-primary-50' : 'border-[#E0E5E5] hover:border-[#C8D1D1] bg-[#F9F9F9]'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                                        <i data-lucide="banknote" class="w-5 h-5 text-primary-400"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-[#0D1B23]">{{ $method->name }}</h3>
                                        <p class="text-sm text-[#7A7A7A]">Secure and fast processing</p>
                                    </div>
                                </div>
                                <div class="check-circle w-6 h-6 rounded-full border-2 transition-all duration-200"
                                     :class="selectedMethod === '{{ $method->name }}' ? 'border-primary-500 bg-primary-500' : 'border-[#C8D1D1]'">
                                    <i data-lucide="check" class="w-4 h-4 text-white" 
                                       x-show="selectedMethod === '{{ $method->name }}'"></i>
                                </div>
                            </div>
                        </div>
                    </label>
                @empty
                    <div class="p-6 rounded-xl bg-[#D55672]/10 border border-[#D55672]/20">
                        <div class="flex items-center space-x-3">
                            <i data-lucide="alert-circle" class="w-6 h-6 text-[#D55672]"></i>
                            <p class="text-[#D55672]">No payment method enabled at the moment. Please check back later.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Amount Input -->
            <div class="space-y-3">
                <label class="flex items-center space-x-2 text-[#0D1B23] font-semibold">
                    <i data-lucide="dollar-sign" class="w-5 h-5 text-[#7A7A7A]"></i>
                    <span>Deposit Amount</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A] font-semibold">
                        {{ $settings->currency }}
                    </span>
                    <input type="text" 
                           name="amount" 
                           required
                           placeholder="0.00"
                           style="background: #FFFFFF;"
                           class="w-full pl-16 pr-6 py-4 rounded-xl border border-[#E0E5E5] focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-[#0D1B23] text-lg font-semibold transition-colors outline-none"
                           oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                </div>
                <p class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    <span>Enter the amount you wish to deposit in {{ $settings->currency }}</span>
                </p>
            </div>

            <!-- Security Notice -->
            <div class="p-4 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5]">
                <div class="flex items-start space-x-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-primary-400 mt-0.5 flex-shrink-0"></i>
                    <div class="text-sm text-[#4A4A4A]">
                        <p class="font-semibold text-[#0D1B23] mb-1">Secure Transaction</p>
                        <p>Your payment information is encrypted and secure. We never store sensitive payment details.</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="flex-1 px-8 py-4 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-semibold text-lg transition-colors flex items-center justify-center space-x-2 group">
                    <span>Continue to Payment</span>
                    <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Help Section -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <div class="flex items-start space-x-4">
            <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center flex-shrink-0">
                <i data-lucide="help-circle" class="w-5 h-5 text-[#7A7A7A]"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-[#0D1B23] mb-2">Need Help?</h3>
                <p class="text-[#7A7A7A] mb-4">If you encounter any issues during the deposit process, our support team is available 24/7 to assist you.</p>
                <a href="{{ route('support') }}" 
                   class="inline-flex items-center space-x-2 text-primary-400 hover:text-primary-300 font-semibold transition-colors group">
                    <span>Contact Support</span>
                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
    
    // Re-initialize icons when Alpine changes state
    document.addEventListener('alpine:initialized', () => {
        setTimeout(() => lucide.createIcons(), 100);
    });
</script>
@endpush

@endsection