@extends('layouts.bankapp')

@section('title', 'Card Transactions')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('cards.view', $card->id) }}" class="w-9 h-9 rounded-lg bg-[#F9F9F9] border border-[#E0E5E5] flex items-center justify-center hover:bg-[#DFE5E5] transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4 text-[#4A4A4A]"></i>
        </a>
        <div>
            <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">Transaction History</h1>
            <p class="text-sm text-[#7A7A7A] mt-0.5">{{ $card->card_type }} •••• {{ $card->last_four }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4 sm:p-5">
        <form method="GET" action="{{ route('cards.transactions', $card->id) }}" class="space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <div>
                    <label class="block text-xs text-[#7A7A7A] uppercase tracking-wide mb-1.5">Transaction Type</label>
                    <select name="type" class="w-full px-3 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-sm text-[#0D1B23] transition-colors">
                        <option value="">All Types</option>
                        <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>Purchase</option>
                        <option value="topup" {{ request('type') == 'topup' ? 'selected' : '' }}>Top Up</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>Withdrawal</option>
                        <option value="refund" {{ request('type') == 'refund' ? 'selected' : '' }}>Refund</option>
                        <option value="fee" {{ request('type') == 'fee' ? 'selected' : '' }}>Fee</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-[#7A7A7A] uppercase tracking-wide mb-1.5">Status</label>
                    <select name="status" class="w-full px-3 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-sm text-[#0D1B23] transition-colors">
                        <option value="">All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs text-[#7A7A7A] uppercase tracking-wide mb-1.5">From Date</label>
                    <input type="date" name="from" value="{{ request('from') }}" 
                        class="w-full px-3 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-sm text-[#0D1B23] transition-colors">
                </div>

                <div>
                    <label class="block text-xs text-[#7A7A7A] uppercase tracking-wide mb-1.5">To Date</label>
                    <input type="date" name="to" value="{{ request('to') }}" 
                        class="w-full px-3 py-2.5 bg-white border border-[#C8D1D1] focus:border-[#49786B] focus:ring-1 focus:ring-[#49786B]/20 rounded-lg text-sm text-[#0D1B23] transition-colors">
                </div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-[#49786B] hover:bg-[#3B645A] transition-colors text-sm font-medium text-white">
                    <i data-lucide="filter" class="w-3.5 h-3.5 inline mr-1.5"></i>
                    Apply Filters
                </button>
                <a href="{{ route('cards.transactions', $card->id) }}" class="px-5 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#BFC9C9] transition-colors text-sm font-medium text-[#4A4A4A]">
                    <i data-lucide="x" class="w-3.5 h-3.5 inline mr-1.5"></i>
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Transactions List -->
    <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4 sm:p-5">
        @if($transactions->count() > 0)
        <div class="space-y-3">
            @foreach($transactions as $transaction)
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-4 hover:bg-[#DFE5E5]/50 transition-colors">
                <div class="flex items-start justify-between gap-4">
                    <!-- Transaction Icon & Details -->
                    <div class="flex items-start gap-3 flex-1 min-w-0">
                        <div class="flex-shrink-0 w-9 h-9 rounded-lg flex items-center justify-center
                            @if($transaction->type === 'purchase') bg-[#D55672]/15
                            @elseif($transaction->type === 'topup') bg-[#28A745]/15
                            @elseif($transaction->type === 'refund') bg-[#49786B]/15
                            @elseif($transaction->type === 'withdrawal') bg-[#3e4e83]/15
                            @else bg-[#DFE5E5]
                            @endif">
                            <i data-lucide="
                                @if($transaction->type === 'purchase') shopping-cart
                                @elseif($transaction->type === 'topup') arrow-down-circle
                                @elseif($transaction->type === 'refund') rotate-ccw
                                @elseif($transaction->type === 'withdrawal') arrow-up-circle
                                @else credit-card
                                @endif" 
                                class="w-4 h-4
                                @if($transaction->type === 'purchase') text-[#D55672]
                                @elseif($transaction->type === 'topup') text-[#28A745]
                                @elseif($transaction->type === 'refund') text-[#49786B]
                                @elseif($transaction->type === 'withdrawal') text-[#3e4e83]
                                @else text-[#7A7A7A]
                                @endif">
                            </i>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-0.5">
                                <h3 class="font-medium text-[#0D1B23] text-sm">
                                    @if($transaction->merchant_name)
                                        {{ $transaction->merchant_name }}
                                    @else
                                        {{ ucfirst($transaction->type) }}
                                    @endif
                                </h3>
                                <span class="px-1.5 py-0.5 rounded-md text-[10px] font-medium
                                    @if($transaction->status === 'completed') bg-[#28A745]/15 text-[#28A745] border border-[#28A745]/20
                                    @elseif($transaction->status === 'pending') bg-[#3e4e83]/15 text-[#3e4e83] border border-[#3e4e83]/20
                                    @else bg-[#D55672]/15 text-[#D55672] border border-[#D55672]/20
                                    @endif">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </div>
                            
                            @if($transaction->merchant_location)
                            <p class="text-xs text-[#7A7A7A] mb-0.5 truncate">
                                <i data-lucide="map-pin" class="w-3 h-3 inline mr-0.5"></i>
                                {{ $transaction->merchant_location }}
                            </p>
                            @endif
                            
                            @if($transaction->description)
                            <p class="text-xs text-[#7A7A7A] truncate">{{ $transaction->description }}</p>
                            @endif
                            
                            <div class="flex items-center gap-3 mt-1.5 text-[11px] text-[#7A7A7A]">
                                <span>{{ $transaction->created_at->format('M d, Y') }}</span>
                                <span>{{ $transaction->created_at->format('h:i A') }}</span>
                                @if($transaction->reference)
                                <span class="hidden sm:inline">Ref: {{ $transaction->reference }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="text-right flex-shrink-0">
                        <p class="font-semibold text-sm
                            @if(in_array($transaction->type, ['purchase', 'withdrawal', 'fee'])) text-[#D55672]
                            @else text-[#28A745]
                            @endif">
                            @if(in_array($transaction->type, ['purchase', 'withdrawal', 'fee']))
                                -
                            @else
                                +
                            @endif
                            {{ Auth::user()->currency }}{{ number_format($transaction->amount, 2) }}
                        </p>
                        @if($transaction->balance_after !== null)
                        <p class="text-[11px] text-[#7A7A7A] mt-0.5">
                            Balance: {{ Auth::user()->currency }}{{ number_format($transaction->balance_after, 2) }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            {{ $transactions->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <div class="w-14 h-14 rounded-full bg-[#DFE5E5] flex items-center justify-center mx-auto mb-3">
                <i data-lucide="inbox" class="w-7 h-7 text-[#7A7A7A]"></i>
            </div>
            <h3 class="text-sm font-semibold text-[#0D1B23] mb-1">No Transactions Found</h3>
            <p class="text-xs text-[#7A7A7A]">
                @if(request()->hasAny(['type', 'status', 'from', 'to']))
                    No transactions match your filters. Try adjusting your search criteria.
                @else
                    You haven't made any transactions with this card yet.
                @endif
            </p>
        </div>
        @endif
    </div>

    <!-- Card Summary -->
    <div class="bg-white border border-[#E0E5E5] shadow-sm rounded-xl p-4 sm:p-5">
        <h3 class="text-sm font-semibold text-[#0D1B23] mb-4">Card Summary</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3 text-center">
                <p class="text-[11px] text-[#7A7A7A] uppercase tracking-wide mb-1">Current Balance</p>
                <p class="text-base font-semibold text-[#28A745]">{{ Auth::user()->currency }}{{ number_format($card->balance, 2) }}</p>
            </div>
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3 text-center">
                <p class="text-[11px] text-[#7A7A7A] uppercase tracking-wide mb-1">Total Spent</p>
                <p class="text-base font-semibold text-[#D55672]">
                    {{ Auth::user()->currency }}{{ number_format($transactions->whereIn('type', ['purchase', 'withdrawal', 'fee'])->where('status', 'completed')->sum('amount'), 2) }}
                </p>
            </div>
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3 text-center">
                <p class="text-[11px] text-[#7A7A7A] uppercase tracking-wide mb-1">Total Topped Up</p>
                <p class="text-base font-semibold text-[#28A745]">
                    {{ Auth::user()->currency }}{{ number_format($transactions->where('type', 'topup')->where('status', 'completed')->sum('amount'), 2) }}
                </p>
            </div>
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-lg p-3 text-center">
                <p class="text-[11px] text-[#7A7A7A] uppercase tracking-wide mb-1">Transactions</p>
                <p class="text-base font-semibold text-[#0D1B23]">{{ $transactions->total() }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection

