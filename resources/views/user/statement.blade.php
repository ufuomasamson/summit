@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="space-y-6">

    <!-- Page Header -->
    <div>
        <h1 class="text-2xl font-semibold text-[#0D1B23] mb-1">Account Statement</h1>
        <p class="text-sm text-[#7A7A7A]">Generate and download an official statement of your account transactions</p>
    </div>

    <!-- Error Message -->
    @if(session('error'))
    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl px-5 py-4">
        <div class="flex items-center gap-3">
            <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0"></i>
            <p class="text-sm text-[#D55672]">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Statement Form Card -->
    <div class="bg-white border border-[#E0E5E5] rounded-2xl overflow-hidden shadow-sm">
        
        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-[#E0E5E5]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-primary-500/10 flex items-center justify-center">
                    <i data-lucide="file-text" class="w-5 h-5 text-primary-500"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-[#0D1B23]">Generate Statement</h2>
                    <p class="text-xs text-[#7A7A7A]">Select a date range to generate your account statement</p>
                </div>
            </div>
        </div>

        <!-- Form Body -->
        <form action="{{ route('statement.generate') }}" method="POST" class="p-6">
            @csrf
            
            <!-- Account Info Preview -->
            <div class="bg-[#F9F9F9] rounded-xl p-5 mb-6 border border-[#E0E5E5]">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-[#7A7A7A] mb-1">Account Holder</p>
                        <p class="text-sm font-medium text-[#0D1B23]">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[#7A7A7A] mb-1">Account Number</p>
                        <p class="text-sm font-medium text-[#0D1B23]">{{ Auth::user()->usernumber }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-[#7A7A7A] mb-1">Account Type</p>
                        <p class="text-sm font-medium text-[#0D1B23]">{{ Auth::user()->accounttype ?? 'Savings Account' }}</p>
                    </div>
                </div>
            </div>

            <!-- Date Range -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                <div>
                    <label for="from_date" class="block text-sm font-medium text-[#4A4A4A] mb-2">From Date</label>
                    <input 
                        type="date" 
                        id="from_date" 
                        name="from_date" 
                        value="{{ old('from_date', now()->subMonth()->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        required
                        class="w-full px-4 py-3 bg-white border border-[#E0E5E5] rounded-xl text-sm text-[#0D1B23] focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20 transition-colors"
                    >
                    @error('from_date')
                        <p class="text-xs text-[#D55672] mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="to_date" class="block text-sm font-medium text-[#4A4A4A] mb-2">To Date</label>
                    <input 
                        type="date" 
                        id="to_date" 
                        name="to_date" 
                        value="{{ old('to_date', now()->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}"
                        required
                        class="w-full px-4 py-3 bg-white border border-[#E0E5E5] rounded-xl text-sm text-[#0D1B23] focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20 transition-colors"
                    >
                    @error('to_date')
                        <p class="text-xs text-[#D55672] mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Quick Select Buttons -->
            <div class="mb-6" x-data>
                <p class="text-xs text-[#7A7A7A] mb-3">Quick select</p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" 
                        @click="$refs.from.value = '{{ now()->subDays(7)->format('Y-m-d') }}'; $refs.to.value = '{{ now()->format('Y-m-d') }}'"
                        x-ref="q7"
                        onclick="document.getElementById('from_date').value='{{ now()->subDays(7)->format('Y-m-d') }}'; document.getElementById('to_date').value='{{ now()->format('Y-m-d') }}';"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#E0E5E5] bg-white text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        Last 7 Days
                    </button>
                    <button type="button" 
                        onclick="document.getElementById('from_date').value='{{ now()->subMonth()->format('Y-m-d') }}'; document.getElementById('to_date').value='{{ now()->format('Y-m-d') }}';"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#E0E5E5] bg-white text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        Last 30 Days
                    </button>
                    <button type="button" 
                        onclick="document.getElementById('from_date').value='{{ now()->subMonths(3)->format('Y-m-d') }}'; document.getElementById('to_date').value='{{ now()->format('Y-m-d') }}';"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#E0E5E5] bg-white text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        Last 3 Months
                    </button>
                    <button type="button" 
                        onclick="document.getElementById('from_date').value='{{ now()->subMonths(6)->format('Y-m-d') }}'; document.getElementById('to_date').value='{{ now()->format('Y-m-d') }}';"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#E0E5E5] bg-white text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        Last 6 Months
                    </button>
                    <button type="button" 
                        onclick="document.getElementById('from_date').value='{{ now()->startOfYear()->format('Y-m-d') }}'; document.getElementById('to_date').value='{{ now()->format('Y-m-d') }}';"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#E0E5E5] bg-white text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        Year to Date
                    </button>
                    <button type="button" 
                        onclick="document.getElementById('from_date').value='{{ now()->subYear()->format('Y-m-d') }}'; document.getElementById('to_date').value='{{ now()->format('Y-m-d') }}';"
                        class="px-3 py-1.5 text-xs font-medium rounded-lg border border-[#E0E5E5] bg-white text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        Last 12 Months
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-primary-500 hover:bg-primary-600 text-white font-semibold rounded-xl transition-colors flex items-center justify-center gap-2">
                <i data-lucide="download" class="w-4 h-4"></i>
                Download Statement (PDF)
            </button>
        </form>
    </div>

    <!-- Info Card -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5">
        <div class="flex items-start gap-3">
            <i data-lucide="info" class="w-5 h-5 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
            <div class="text-sm text-[#7A7A7A] space-y-1">
                <p>Your statement includes all <strong class="text-[#7A7A7A]">processed</strong> credits, debits, and deposits within the selected dates.</p>
                <p>Pending or rejected transactions are not included. Maximum period is 12 months per statement.</p>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => lucide.createIcons(), 100);
    });
</script>

@endsection
