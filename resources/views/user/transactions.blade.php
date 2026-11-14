@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ 
    filterOpen: false, 
    selectedFilter: 'all',
    searchQuery: '',
    viewMode: 'list'
}" class="space-y-5">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold text-[#0D1B23] mb-1">Transaction History</h1>
            <p class="text-sm text-[#7A7A7A]">Track and manage all your account activities</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex items-center gap-3">
            <!-- Search -->
            <div class="relative hidden md:block">
                <input 
                    type="text" 
                    x-model="searchQuery"
                    placeholder="Search transactions..." 
                    class="pl-10 pr-4 py-2.5 bg-white border border-[#E0E5E5] rounded-lg text-sm text-[#0D1B23] placeholder-gray-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500/20 transition-colors w-64"
                >
                <i data-lucide="search" class="w-4 h-4 text-[#7A7A7A] absolute left-3 top-1/2 -translate-y-1/2"></i>
            </div>
            
            <!-- Filter Toggle -->
            <button 
                @click="filterOpen = !filterOpen"
                class="px-4 py-2.5 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors flex items-center gap-2"
            >
                <i data-lucide="filter" class="w-4 h-4 text-[#7A7A7A]"></i>
                <span class="text-sm font-medium text-[#4A4A4A] hidden sm:inline">Filter</span>
            </button>
            
            <!-- View Mode Toggle -->
            <button 
                @click="viewMode = viewMode === 'list' ? 'grid' : 'list'"
                class="px-4 py-2.5 rounded-lg border border-[#E0E5E5] bg-white hover:bg-[#DFE5E5] transition-colors hidden lg:block"
            >
                <i :data-lucide="viewMode === 'list' ? 'grid-3x3' : 'list'" class="w-4 h-4 text-[#7A7A7A]"></i>
            </button>
        </div>
    </div>

    <!-- Filter Panel -->
    <div 
        x-show="filterOpen" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="bg-white border border-[#E0E5E5] rounded-xl p-4"
    >
        <div class="flex flex-wrap gap-2">
            <button 
                @click="selectedFilter = 'all'"
                :class="selectedFilter === 'all' ? 'bg-primary-500 text-white border-primary-500' : 'bg-white text-[#4A4A4A] border-[#C8D1D1] hover:bg-[#DFE5E5]'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors border"
            >
                All Transactions
            </button>
            <button 
                @click="selectedFilter = 'credit'"
                :class="selectedFilter === 'credit' ? 'bg-emerald-500/15 text-emerald-300 border-emerald-500/40' : 'bg-white text-[#4A4A4A] border-[#C8D1D1] hover:bg-[#DFE5E5]'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors border"
            >
                <i data-lucide="arrow-down-left" class="w-3.5 h-3.5 inline mr-1"></i>
                Credits
            </button>
            <button 
                @click="selectedFilter = 'debit'"
                :class="selectedFilter === 'debit' ? 'bg-red-500/15 text-[#D55672] border-red-500/40' : 'bg-white text-[#4A4A4A] border-[#C8D1D1] hover:bg-[#DFE5E5]'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors border"
            >
                <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 inline mr-1"></i>
                Debits
            </button>
            <button 
                @click="selectedFilter = 'deposit'"
                :class="selectedFilter === 'deposit' ? 'bg-blue-500/15 text-blue-300 border-blue-500/40' : 'bg-white text-[#4A4A4A] border-[#C8D1D1] hover:bg-[#DFE5E5]'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors border"
            >
                <i data-lucide="plus-circle" class="w-3.5 h-3.5 inline mr-1"></i>
                Deposits
            </button>
            <button 
                @click="selectedFilter = 'pending'"
                :class="selectedFilter === 'pending' ? 'bg-amber-500/15 text-amber-300 border-amber-500/40' : 'bg-white text-[#4A4A4A] border-[#C8D1D1] hover:bg-[#DFE5E5]'"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-colors border"
            >
                <i data-lucide="clock" class="w-3.5 h-3.5 inline mr-1"></i>
                Pending
            </button>
        </div>
    </div>

    <!-- Transactions List -->
    <div class="space-y-2" x-show="viewMode === 'list'">
        @php
            $allTransactions = collect();
            foreach ($withdrawals as $withdrawal) {
                $allTransactions->push([
                    'type' => 'withdrawal',
                    'data' => $withdrawal,
                    'timestamp' => $withdrawal->created_at ?? now()
                ]);
            }
            foreach ($deposits as $deposit) {
                $allTransactions->push([
                    'type' => 'deposit',
                    'data' => $deposit,
                    'timestamp' => $deposit->created_at ?? now()
                ]);
            }
            $allTransactions = $allTransactions->sortByDesc('timestamp');
        @endphp

        @forelse($allTransactions as $transaction)
            @php
                $item = $transaction['data'];
                $isDeposit = $transaction['type'] === 'deposit';
                $status = $item->status ?? '';
                $statusLower = strtolower($status);
                $isStatusTxn = !$isDeposit && in_array($statusLower, ['failed', 'pending', 'error'], true);
                $isCredit = !$isDeposit && !$isStatusTxn && $item->type === 'Credit';
                $isDebit = !$isDeposit && !$isStatusTxn && $item->type === 'Debit';
                $isPending = in_array($statusLower, ['pending', 'on-hold', 'scheduled', 'under review']);
                $isProcessed = in_array($statusLower, ['processed', 'approved']);
                $isRejected = in_array($statusLower, ['rejected', 'failed', 'error', 'expired', 'frozen']);
                $isCancelled = $statusLower === 'cancelled';
                $isReversed = in_array($statusLower, ['reversed', 'refunded']);
            @endphp
            
            <a href="{{ $isDeposit ? route('depositdetails', $item->id) : route('transactiondetails', $item->id) }}" 
               class="bg-white border border-[#E0E5E5] rounded-lg p-4 md:p-5 hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors block group">
                <div class="flex items-center gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
                        @if($isStatusTxn && $isPending)
                            bg-amber-500/10 border border-amber-500/20
                        @elseif($isStatusTxn || $isRejected)
                            bg-[#D55672]/10 border border-red-500/20
                        @elseif($isDeposit || $isCredit)
                            bg-emerald-500/10 border border-emerald-500/20
                        @else
                            bg-[#D55672]/10 border border-red-500/20
                        @endif
                    ">
                        @if($isStatusTxn && $isPending)
                            <i data-lucide="clock" class="w-4 h-4 text-[#3e4e83]"></i>
                        @elseif($isStatusTxn || $isRejected)
                            <i data-lucide="x-circle" class="w-4 h-4 text-[#D55672]"></i>
                        @elseif($isDeposit)
                            <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-400"></i>
                        @elseif($isCredit)
                            <i data-lucide="arrow-down-left" class="w-4 h-4 text-emerald-400"></i>
                        @else
                            <i data-lucide="arrow-up-right" class="w-4 h-4 text-[#D55672]"></i>
                        @endif
                    </div>
                    
                    <!-- Transaction Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <p class="font-medium text-sm
                                @if($isStatusTxn && $isPending) text-[#3e4e83]
                                @elseif($isStatusTxn) text-[#D55672]
                                @else text-[#0D1B23]
                                @endif">
                                @if($isDeposit)
                                    Deposit
                                @elseif($isStatusTxn)
                                    {{ ucfirst($status) }}
                                @else
                                    {{ $item->payment_mode ?? $item->type }}
                                @endif
                            </p>

                            @if($isStatusTxn)
                                <span class="text-xs text-[#7A7A7A]">{{ $item->payment_mode }}</span>
                            @endif
                            
                            {{-- Show party bank information for both Credits and Debits with tracking --}}
                            @if(!empty($item->sender_bank))
                                <span class="text-xs text-[#7A7A7A]">{{ $isCredit ? 'from' : 'to' }}</span>
                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-primary-50 border border-primary-200">
                                    <i data-lucide="landmark" class="w-3 h-3 text-primary-500"></i>
                                    <p class="text-xs font-medium text-primary-500">{{ $item->sender_bank }}</p>
                                </div>
                                @if(!empty($item->reference_number))
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-[#DFE5E5] text-[#7A7A7A] border border-[#C8D1D1]">
                                        {{ $item->reference_number }}
                                    </span>
                                @endif
                            @elseif(!$isDeposit && !$isStatusTxn && $item->accountname)
                                <span class="text-xs text-[#7A7A7A]">→</span>
                                <p class="text-sm text-[#7A7A7A] truncate">{{ $item->accountname }}</p>
                            @endif
                            
                            <!-- Status Badge (skip when title already is Failed/Pending/Error) -->
                            @unless($isStatusTxn)
                            @if($isPending)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-amber-500/10 text-[#3e4e83] border border-amber-500/20">
                                    <i data-lucide="clock" class="w-3 h-3 mr-1"></i>
                                    {{ $status }}
                                </span>
                            @elseif($isProcessed)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                    <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                    {{ $status }}
                                </span>
                            @elseif($isRejected)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-[#D55672]/10 text-[#D55672] border border-red-500/20">
                                    <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                    {{ $status }}
                                </span>
                            @elseif($isReversed)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20">
                                    <i data-lucide="rotate-ccw" class="w-3 h-3 mr-1"></i>
                                    {{ $status }}
                                </span>
                            @elseif($isCancelled)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-gray-500/10 text-[#7A7A7A] border border-gray-500/20">
                                    <i data-lucide="slash" class="w-3 h-3 mr-1"></i>
                                    {{ $status }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase tracking-wider bg-gray-500/10 text-[#7A7A7A] border border-gray-500/20">
                                    {{ $status }}
                                </span>
                            @endif
                            @endunless
                        </div>
                        
                        <div class="flex items-center gap-2 text-xs text-[#7A7A7A]">
                            <i data-lucide="calendar" class="w-3 h-3"></i>
                            <span>{{ $item->created_at ? $item->created_at->format('M d, Y') : 'N/A' }}</span>
                            <span class="text-gray-700">·</span>
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            <span>{{ $item->created_at ? $item->created_at->format('g:i A') : 'N/A' }}</span>
                            
                            {{-- Show party name for verified transactions (sender for Credits, recipient for Debits) --}}
                            @if(!empty($item->sender_name))
                                <span class="text-gray-700 hidden sm:inline">·</span>
                                <div class="hidden sm:flex items-center gap-1.5">
                                    <i data-lucide="user-check" class="w-3 h-3 text-primary-500"></i>
                                    <span class="text-xs text-primary-500">{{ $item->sender_name }}</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Mobile: Show sender details on separate line --}}
                        @if($isCredit && !empty($item->sender_name))
                            <div class="flex sm:hidden items-center gap-1.5 mt-1 text-xs text-primary-500">
                                <i data-lucide="user-check" class="w-3 h-3"></i>
                                <span>From: {{ $item->sender_name }}</span>
                                @if(!empty($item->sender_bank))
                                    <span class="text-[#7A7A7A]">·</span>
                                    <span>{{ $item->sender_bank }}</span>
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <!-- Amount -->
                    <div class="flex-shrink-0 text-right">
                        <p class="font-semibold text-lg
                            @if($isStatusTxn && $isPending)
                                text-[#3e4e83]
                            @elseif($isStatusTxn || $isRejected)
                                text-[#D55672]
                            @elseif($isDeposit || $isCredit)
                                text-emerald-400
                            @else
                                text-[#D55672]
                            @endif
                        ">
                            @if($isStatusTxn)
                                {{ Auth::user()->currency }}{{ number_format($item->amount, 2, '.', ',') }}
                            @elseif($isDeposit || $isCredit)
                                +
                                {{ Auth::user()->currency }}{{ number_format($item->amount, 2, '.', ',') }}
                            @else
                                -
                                {{ Auth::user()->currency }}{{ number_format($item->amount, 2, '.', ',') }}
                            @endif
                        </p>
                        <p class="text-xs mt-0.5
                            @if($isStatusTxn && $isPending) text-[#3e4e83] font-semibold
                            @elseif($isStatusTxn) text-[#D55672] font-semibold
                            @else text-[#7A7A7A]
                            @endif">
                            @if($isStatusTxn)
                                {{ ucfirst($status) }}
                            @elseif($isProcessed)
                                Completed
                            @elseif($isPending)
                                Processing
                            @else
                                {{ $status }}
                            @endif
                        </p>
                    </div>
                    
                    <!-- Arrow -->
                    <div class="flex-shrink-0 hidden md:block">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-[#7A7A7A] group-hover:text-[#7A7A7A] transition-colors"></i>
                    </div>
                </div>
            </a>
        @empty
            <!-- Empty State -->
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-primary-50 border border-primary-200 flex items-center justify-center">
                    <i data-lucide="receipt" class="w-8 h-8 text-primary-500"></i>
                </div>
                <h3 class="text-lg font-semibold text-[#0D1B23] mb-2">No Transactions Yet</h3>
                <p class="text-sm text-[#7A7A7A] max-w-md mx-auto mb-6">Your transaction history will appear here once you make your first deposit or withdrawal.</p>
                <a href="{{ route('accounthistory') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-500 hover:bg-primary-600 text-white rounded-lg font-medium text-sm transition-colors">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Get Started</span>
                </a>
            </div>
        @endforelse
    </div>

    <!-- View All Link -->
    @if($allTransactions->count() > 0)
    <div class="flex justify-center pt-4">
        <a href="{{ route('accounthistory') }}" class="inline-flex items-center gap-2 px-5 py-2.5 border border-[#E0E5E5] bg-white rounded-lg hover:bg-[#DFE5E5] hover:border-[#C8D1D1] transition-colors text-sm font-medium text-[#4A4A4A]">
            <span>View Complete History</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

@endsection

