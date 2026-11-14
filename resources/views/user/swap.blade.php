@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="swapApp()" x-init="init()" class="space-y-6">

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#0D1B23] mb-1">Crypto Swap</h1>
            <p class="text-[#7A7A7A] text-sm">Swap between your main balance and crypto wallets instantly.</p>
        </div>
        <a href="{{ route('swaphistory') }}" class="px-4 py-2 bg-[#DFE5E5] hover:bg-[#C8D1D1]/60 border border-[#C8D1D1] rounded-lg text-sm text-[#4A4A4A] hover:text-[#0D1B23] transition-colors">
            <i data-lucide="history" class="w-4 h-4 inline-block mr-1"></i> History
        </a>
    </div>

    <!-- Balance Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <!-- Main Balance -->
        <div class="bg-white rounded-xl p-4 border border-[#E0E5E5]">
            <div class="flex items-center space-x-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-primary-600/30 flex items-center justify-center">
                    <i data-lucide="wallet" class="w-4 h-4 text-primary-500"></i>
                </div>
                <span class="text-xs font-medium text-[#7A7A7A] uppercase">USD</span>
            </div>
            <p class="text-lg font-bold text-[#0D1B23]">{{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}</p>
        </div>

        <!-- BTC Balance -->
        <div class="bg-white rounded-xl p-4 border border-[#E0E5E5]">
            <div class="flex items-center space-x-2 mb-2">
                <img src="https://assets.coingecko.com/coins/images/1/standard/bitcoin.png?1696501400" class="w-8 h-8 rounded-full" alt="BTC">
                <span class="text-xs font-medium text-[#7A7A7A] uppercase">BTC</span>
            </div>
            <p class="text-lg font-bold text-[#0D1B23]" x-text="balances.btc + ' BTC'">{{ number_format($cryptoAccount->btc ?? 0, 8) }} BTC</p>
            <p class="text-xs text-[#7A7A7A]" x-text="'≈ $' + usdValues.btc">...</p>
        </div>

        <!-- ETH Balance -->
        <div class="bg-white rounded-xl p-4 border border-[#E0E5E5]">
            <div class="flex items-center space-x-2 mb-2">
                <img src="https://assets.coingecko.com/coins/images/279/standard/ethereum.png?1696501628" class="w-8 h-8 rounded-full" alt="ETH">
                <span class="text-xs font-medium text-[#7A7A7A] uppercase">ETH</span>
            </div>
            <p class="text-lg font-bold text-[#0D1B23]" x-text="balances.eth + ' ETH'">{{ number_format($cryptoAccount->eth ?? 0, 8) }} ETH</p>
            <p class="text-xs text-[#7A7A7A]" x-text="'≈ $' + usdValues.eth">...</p>
        </div>

        <!-- USDT Balance -->
        <div class="bg-white rounded-xl p-4 border border-[#E0E5E5]">
            <div class="flex items-center space-x-2 mb-2">
                <img src="https://assets.coingecko.com/coins/images/325/standard/Tether.png?1696501661" class="w-8 h-8 rounded-full" alt="USDT">
                <span class="text-xs font-medium text-[#7A7A7A] uppercase">USDT</span>
            </div>
            <p class="text-lg font-bold text-[#0D1B23]" x-text="balances.usdt + ' USDT'">{{ number_format($cryptoAccount->usdt ?? 0, 8) }} USDT</p>
            <p class="text-xs text-[#7A7A7A]" x-text="'≈ $' + usdValues.usdt">...</p>
        </div>
    </div>

    <!-- Swap Card -->
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-xl border border-[#E0E5E5] overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-[#0D1B23] mb-5">Swap Assets</h2>

                <!-- From -->
                <div class="mb-1">
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">From</label>
                    <div class="flex space-x-3">
                        <select x-model="source" @change="onSourceChange()" class="w-1/3 bg-[#F9F9F9]/60 border border-[#C8D1D1] rounded-lg px-3 py-3 text-[#0D1B23] text-sm focus:outline-none focus:border-primary-500 transition-colors">
                            <option value="usd">USD</option>
                            <option value="btc">BTC</option>
                            <option value="eth">ETH</option>
                            <option value="usdt">USDT</option>
                        </select>
                        <div class="flex-1 relative">
                            <input type="number" x-model="amount" @input="calculateRate()" step="any" min="0"
                                   class="w-full bg-[#F9F9F9]/60 border border-[#C8D1D1] rounded-lg px-3 py-3 text-[#0D1B23] text-sm focus:outline-none focus:border-primary-500 transition-colors"
                                   placeholder="0.00">
                            <button @click="setMaxAmount()" class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-primary-500 hover:text-primary-400 font-semibold uppercase">Max</button>
                        </div>
                    </div>
                    <p class="text-xs text-[#7A7A7A] mt-1">
                        Available: <span x-text="getAvailable(source)"></span>
                    </p>
                </div>

                <!-- Swap Direction Button -->
                <div class="flex justify-center my-3">
                    <button @click="swapDirection()" class="w-10 h-10 rounded-full bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] flex items-center justify-center transition-colors">
                        <i data-lucide="arrow-up-down" class="w-4 h-4 text-champagne-400"></i>
                    </button>
                </div>

                <!-- To -->
                <div class="mb-4">
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">To</label>
                    <div class="flex space-x-3">
                        <select x-model="destination" @change="onDestChange()" class="w-1/3 bg-[#F9F9F9]/60 border border-[#C8D1D1] rounded-lg px-3 py-3 text-[#0D1B23] text-sm focus:outline-none focus:border-primary-500 transition-colors">
                            <option value="usd">USD</option>
                            <option value="btc">BTC</option>
                            <option value="eth">ETH</option>
                            <option value="usdt">USDT</option>
                        </select>
                        <input type="text" :value="receiveDisplay" readonly
                               class="flex-1 bg-[#F9F9F9] border border-[#C8D1D1]/30 rounded-lg px-3 py-3 text-[#4A4A4A] text-sm cursor-not-allowed"
                               placeholder="0.00">
                    </div>
                </div>

                <!-- Rate Display -->
                <div x-show="rateDisplay" x-transition class="bg-[#F9F9F9] rounded-lg p-3 border border-[#E0E5E5] mb-5">
                    <div class="flex justify-between text-xs text-[#7A7A7A]">
                        <span>Exchange Rate</span>
                        <span x-text="rateDisplay" class="text-[#4A4A4A] font-medium"></span>
                    </div>
                </div>

                <!-- Error -->
                <div x-show="errorMsg" x-transition class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-lg p-3 mb-4">
                    <p class="text-sm text-[#D55672]" x-text="errorMsg"></p>
                </div>

                <!-- Success -->
                <div x-show="successMsg" x-transition class="bg-green-500/10 border border-green-500/30 rounded-lg p-3 mb-4">
                    <p class="text-sm text-[#28A745]" x-text="successMsg"></p>
                </div>

                <!-- Submit -->
                <button @click="executeSwap()" :disabled="loading || !canSwap()"
                        class="w-full py-3.5 rounded-lg font-semibold text-sm transition-all duration-200"
                        :class="canSwap() && !loading ? 'bg-primary-600 hover:bg-primary-500 text-white' : 'bg-[#DFE5E5] text-[#7A7A7A] cursor-not-allowed'">
                    <span x-show="!loading">Swap Now</span>
                    <span x-show="loading" class="flex items-center justify-center space-x-2">
                        <svg class="animate-spin h-4 w-4 text-[#0D1B23]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span>Processing...</span>
                    </span>
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Swap History -->
    @if(count($recentSwaps) > 0)
    <div class="bg-white rounded-xl border border-[#E0E5E5] overflow-hidden">
        <div class="p-4 border-b border-[#E0E5E5] flex justify-between items-center">
            <h3 class="text-sm font-semibold text-[#0D1B23]">Recent Swaps</h3>
            <a href="{{ route('swaphistory') }}" class="text-xs text-primary-500 hover:text-primary-400">View All</a>
        </div>
        <div class="divide-y divide-slate-700/40">
            @foreach($recentSwaps as $swap)
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-full bg-[#DFE5E5] flex items-center justify-center">
                        <i data-lucide="arrow-right-left" class="w-4 h-4 text-champagne-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23]">{{ strtoupper($swap->source) }} → {{ strtoupper($swap->dest) }}</p>
                        <p class="text-xs text-[#7A7A7A]">{{ \Carbon\Carbon::parse($swap->created_at)->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-semibold text-[#0D1B23]">{{ number_format($swap->quantity, 8) }} {{ strtoupper($swap->dest) }}</p>
                    <p class="text-xs text-[#7A7A7A]">From {{ number_format($swap->amount, $swap->source === 'usd' ? 2 : 8) }} {{ strtoupper($swap->source) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
function swapApp() {
    return {
        source: 'usd',
        destination: 'btc',
        amount: '',
        receiveAmount: 0,
        receiveDisplay: '',
        rateDisplay: '',
        loading: false,
        errorMsg: '',
        successMsg: '',
        calcTimeout: null,
        prices: {},
        balances: {
            usd: '{{ number_format(Auth::user()->account_bal, 2, ".", "") }}',
            btc: '{{ number_format($cryptoAccount->btc ?? 0, 8, ".", "") }}',
            eth: '{{ number_format($cryptoAccount->eth ?? 0, 8, ".", "") }}',
            usdt: '{{ number_format($cryptoAccount->usdt ?? 0, 8, ".", "") }}',
        },
        usdValues: { btc: '0.00', eth: '0.00', usdt: '0.00' },

        init() {
            this.fetchPrices();
            setTimeout(() => lucide.createIcons(), 100);
        },

        async fetchPrices() {
            try {
                const resp = await fetch('{{ route("getliveprices") }}');
                const data = await resp.json();
                if (data.status === 200) {
                    this.prices = data.data;
                    // Calculate USD values
                    this.usdValues.btc = (parseFloat(this.balances.btc) * (this.prices.btc || 0)).toFixed(2);
                    this.usdValues.eth = (parseFloat(this.balances.eth) * (this.prices.eth || 0)).toFixed(2);
                    this.usdValues.usdt = (parseFloat(this.balances.usdt) * (this.prices.usdt || 0)).toFixed(2);
                }
            } catch (e) {
                console.error('Price fetch failed', e);
            }
        },

        getAvailable(coin) {
            if (coin === 'usd') return '{{ Auth::user()->currency }}' + parseFloat(this.balances.usd).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
            return parseFloat(this.balances[coin]).toFixed(8) + ' ' + coin.toUpperCase();
        },

        setMaxAmount() {
            this.amount = this.source === 'usd' ? parseFloat(this.balances.usd) : parseFloat(this.balances[this.source]);
            this.calculateRate();
        },

        swapDirection() {
            let tmp = this.source;
            this.source = this.destination;
            this.destination = tmp;
            this.calculateRate();
        },

        onSourceChange() {
            if (this.source === this.destination) {
                // pick a different destination
                const options = ['usd', 'btc', 'eth', 'usdt'];
                this.destination = options.find(o => o !== this.source) || 'usd';
            }
            this.calculateRate();
        },

        onDestChange() {
            if (this.source === this.destination) {
                const options = ['usd', 'btc', 'eth', 'usdt'];
                this.source = options.find(o => o !== this.destination) || 'usd';
            }
            this.calculateRate();
        },

        calculateRate() {
            this.errorMsg = '';
            this.successMsg = '';
            clearTimeout(this.calcTimeout);

            if (!this.amount || parseFloat(this.amount) <= 0) {
                this.receiveDisplay = '';
                this.rateDisplay = '';
                return;
            }

            this.calcTimeout = setTimeout(async () => {
                try {
                    const resp = await fetch(`{{ route('calculateswap') }}?source=${this.source}&destination=${this.destination}&amount=${this.amount}`);
                    const data = await resp.json();
                    if (data.status === 200) {
                        this.receiveAmount = data.data.receive_amount;
                        const decimals = this.destination === 'usd' ? 2 : 8;
                        this.receiveDisplay = parseFloat(data.data.receive_amount).toFixed(decimals) + ' ' + this.destination.toUpperCase();
                        this.rateDisplay = data.data.rate_display;
                    } else {
                        this.errorMsg = data.message || 'Failed to get rate';
                    }
                } catch (e) {
                    this.errorMsg = 'Network error. Please try again.';
                }
            }, 400);
        },

        canSwap() {
            return this.amount && parseFloat(this.amount) > 0 && this.receiveAmount > 0 && this.source !== this.destination;
        },

        async executeSwap() {
            if (!this.canSwap()) return;
            this.loading = true;
            this.errorMsg = '';
            this.successMsg = '';

            try {
                const resp = await fetch('{{ route("exchangenow") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf_token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        source: this.source,
                        destination: this.destination,
                        amount: parseFloat(this.amount),
                        quantity: this.receiveAmount
                    })
                });
                const data = await resp.json();
                if (data.status === 200) {
                    this.successMsg = data.success;
                    this.amount = '';
                    this.receiveDisplay = '';
                    this.rateDisplay = '';
                    this.receiveAmount = 0;
                    // Refresh balances
                    await this.refreshBalances();
                } else {
                    this.errorMsg = data.message || 'Swap failed. Please try again.';
                }
            } catch (e) {
                this.errorMsg = 'Network error. Please try again.';
            } finally {
                this.loading = false;
            }
        },

        async refreshBalances() {
            try {
                const resp = await fetch('{{ route("getallbalances") }}');
                const data = await resp.json();
                if (data.status === 200) {
                    this.balances = {
                        usd: parseFloat(data.data.usd).toFixed(2),
                        btc: parseFloat(data.data.btc).toFixed(8),
                        eth: parseFloat(data.data.eth).toFixed(8),
                        usdt: parseFloat(data.data.usdt).toFixed(8),
                    };
                    // Recalculate USD values
                    this.usdValues.btc = (parseFloat(this.balances.btc) * (this.prices.btc || 0)).toFixed(2);
                    this.usdValues.eth = (parseFloat(this.balances.eth) * (this.prices.eth || 0)).toFixed(2);
                    this.usdValues.usdt = (parseFloat(this.balances.usdt) * (this.prices.usdt || 0)).toFixed(2);
                }
            } catch (e) {
                console.error('Balance refresh failed', e);
            }
        }
    }
}
</script>
@endsection
