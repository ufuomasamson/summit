@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<style>
    .field-input {
        background: #FFFFFF;
        border: 1px solid #E0E5E5;
        color: #0D1B23;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .field-input:focus, .field-input:focus-within {
        border-color: #49786B;
        box-shadow: 0 0 0 2px rgba(73, 120, 107, 0.12);
    }
    .field-input::placeholder { color: #7A7A7A; }

    @keyframes spin-slow {
        to { transform: rotate(360deg); }
    }
    .spin-slow { animation: spin-slow 1.2s linear infinite; }
</style>

<div class="pb-20 md:pb-8" x-data="{
    amount: '',
    accountname: '',
    accountnumber: '',
    bankname: '',
    accounttype: 'Online Banking',
    routing_number: '',
    swift_code: '',
    pin: '',
    description: '',
    showPreview: false,
    showOptional: false,
    isProcessing: false,
    processingProgress: 0,
    processingStage: 'Initiating transfer...',
    balance: {{ Auth::user()->account_bal }},
    currency: '{{ Auth::user()->currency }}',

    setQuickAmount(value) {
        this.amount = value === 'all' ? this.balance.toFixed(2) : value;
    },

    get remainingBalance() {
        return this.balance - (parseFloat(this.amount) || 0);
    },

    get isValidAmount() {
        const amt = parseFloat(this.amount);
        return amt > 0 && amt <= this.balance;
    },

    openPreview() {
        if (!this.isValidAmount) { swal('Invalid Amount', 'Please enter a valid transfer amount', 'error'); return; }
        if (!this.accountname || !this.accountnumber || !this.bankname || !this.pin) {
            swal('Missing Information', 'Please fill all required fields', 'warning'); return;
        }
        this.showPreview = true;
        setTimeout(() => lucide.createIcons(), 100);
    },

    submitTransfer() {
        this.showPreview = false;
        this.isProcessing = true;
        this.processingProgress = 0;
        const stages = [
            { progress: 20, text: 'Initiating transfer...' },
            { progress: 40, text: 'Verifying account details...' },
            { progress: 60, text: 'Processing transaction...' },
            { progress: 80, text: 'Confirming with bank...' },
            { progress: 100, text: 'Finalizing transfer...' }
        ];
        let currentStage = 0;
        const interval = setInterval(() => {
            if (currentStage < stages.length) {
                this.processingProgress = stages[currentStage].progress;
                this.processingStage = stages[currentStage].text;
                currentStage++;
                lucide.createIcons();
            } else {
                clearInterval(interval);
                setTimeout(() => { this.$refs.transferForm.submit(); }, 500);
            }
        }, 1000);
    }
}" x-init="setTimeout(() => lucide.createIcons(), 100)">

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-lg bg-white border border-[#E0E5E5] flex items-center justify-center hover:bg-[#DFE5E5] transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 text-[#7A7A7A]"></i>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">Local Transfer</h1>
                <p class="text-sm text-[#7A7A7A]">Send money to any local bank account</p>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    <x-danger-alert />
    <x-success-alert />

    {{-- Balance Summary --}}
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-[#7A7A7A] mb-1 font-medium">Available Balance</p>
                <h2 class="text-2xl md:text-3xl font-bold text-[#0D1B23]">
                    {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}
                </h2>
                <p class="text-xs text-[#7A7A7A] mt-1">Account: {{ Auth::user()->accountnumber }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-50 border border-primary-200 flex items-center justify-center">
                <i data-lucide="wallet" class="w-5 h-5 text-primary-500"></i>
            </div>
        </div>
    </div>

    {{-- Transfer Form --}}
    <form action="{{ route('localtransfer') }}" method="POST" x-ref="transferForm" @submit.prevent="openPreview()">
        @csrf
        <input type="hidden" name="balance_type" value="fiat">

        <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-xl p-5 md:p-6 space-y-6">

            {{-- Section: Amount --}}
            <div>
                <label class="block text-sm text-[#7A7A7A] mb-1.5">Transfer Amount <span class="text-[#D55672]">*</span></label>

                <div class="field-input flex items-center rounded-lg mb-3">
                    <span class="pl-4 pr-2 text-[#7A7A7A] font-medium text-lg">{{ Auth::user()->currency }}</span>
                    <input
                        type="number"
                        name="amount"
                        x-model="amount"
                        step="0.01"
                        min="1"
                        max="{{ Auth::user()->account_bal }}"
                        required
                        placeholder="0.00"
                        class="flex-1 bg-transparent border-none outline-none text-[#0D1B23] text-xl font-semibold py-3.5 px-2 placeholder-[#7A7A7A]">
                </div>

                <div class="grid grid-cols-4 gap-2 mb-2">
                    <button type="button" @click="setQuickAmount(100)" class="px-3 py-2 rounded-lg text-xs font-medium bg-[#DFE5E5] border border-[#C8D1D1] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        {{ Auth::user()->currency }}100
                    </button>
                    <button type="button" @click="setQuickAmount(500)" class="px-3 py-2 rounded-lg text-xs font-medium bg-[#DFE5E5] border border-[#C8D1D1] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        {{ Auth::user()->currency }}500
                    </button>
                    <button type="button" @click="setQuickAmount(1000)" class="px-3 py-2 rounded-lg text-xs font-medium bg-[#DFE5E5] border border-[#C8D1D1] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        {{ Auth::user()->currency }}1,000
                    </button>
                    <button type="button" @click="setQuickAmount('all')" class="px-3 py-2 rounded-lg text-xs font-medium bg-[#DFE5E5] border border-[#C8D1D1] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                        All
                    </button>
                </div>

                <p class="text-xs text-[#7A7A7A] px-1">
                    Limits: {{ Auth::user()->currency }}{{ number_format(Auth::user()->min_transfer_amount ?? 1, 2) }} - {{ Auth::user()->currency }}{{ number_format(Auth::user()->max_transfer_amount ?? Auth::user()->account_bal, 2) }}
                </p>
            </div>

            <hr class="border-[#E0E5E5]">

            {{-- Section: Beneficiary --}}
            <div>
                <h3 class="text-sm font-semibold text-[#4A4A4A] uppercase tracking-wider mb-4">Beneficiary Information</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-[#7A7A7A] mb-1.5">Account Holder Name <span class="text-[#D55672]">*</span></label>
                        <input
                            type="text"
                            name="accountname"
                            x-model="accountname"
                            required
                            placeholder="Full name as registered"
                            class="field-input w-full px-4 py-3 rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-sm text-[#7A7A7A] mb-1.5">Account Number <span class="text-[#D55672]">*</span></label>
                        <input
                            type="text"
                            name="accountnumber"
                            x-model="accountnumber"
                            required
                            placeholder="Beneficiary account number"
                            class="field-input w-full px-4 py-3 rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-sm text-[#7A7A7A] mb-1.5">Bank Name <span class="text-[#D55672]">*</span></label>
                        <input
                            type="text"
                            name="bankname"
                            x-model="bankname"
                            required
                            placeholder="Recipient's bank name"
                            class="field-input w-full px-4 py-3 rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-sm text-[#7A7A7A] mb-1.5">Account Type <span class="text-[#D55672]">*</span></label>
                        <select
                            name="Accounttype"
                            x-model="accounttype"
                            required
                            class="field-input w-full px-4 py-3 rounded-lg text-sm cursor-pointer">
                            <option value="Online Banking" style="background: #FFFFFF; color: #0D1B23;">Online Banking</option>
                            <option value="Checking" style="background: #FFFFFF; color: #0D1B23;">Checking Account</option>
                            <option value="Savings Account" style="background: #FFFFFF; color: #0D1B23;">Savings Account</option>
                            <option value="Current Account" style="background: #FFFFFF; color: #0D1B23;">Current Account</option>
                            <option value="Joint Account" style="background: #FFFFFF; color: #0D1B23;">Joint Account</option>
                            <option value="Business Account" style="background: #FFFFFF; color: #0D1B23;">Business Account</option>
                            <option value="Corporate Account" style="background: #FFFFFF; color: #0D1B23;">Corporate Account</option>
                            <option value="Money Market Account" style="background: #FFFFFF; color: #0D1B23;">Money Market Account</option>
                            <option value="Investment Account" style="background: #FFFFFF; color: #0D1B23;">Investment Account</option>
                            <option value="Fixed Deposit Account" style="background: #FFFFFF; color: #0D1B23;">Fixed Deposit Account</option>
                            <option value="Trust Account" style="background: #FFFFFF; color: #0D1B23;">Trust Account</option>
                            <option value="Student Account" style="background: #FFFFFF; color: #0D1B23;">Student Account</option>
                            <option value="Senior Citizen Account" style="background: #FFFFFF; color: #0D1B23;">Senior Citizen Account</option>
                            <option value="Salary Account" style="background: #FFFFFF; color: #0D1B23;">Salary Account</option>
                            <option value="NRI Account" style="background: #FFFFFF; color: #0D1B23;">NRI Account (Non-Resident)</option>
                        </select>
                    </div>

                    {{-- Optional Fields Toggle --}}
                    <button
                        type="button"
                        @click="showOptional = !showOptional"
                        class="text-sm text-primary-400 hover:text-primary-300 font-medium flex items-center space-x-2 transition-colors">
                        <span x-text="showOptional ? 'Hide' : 'Show'">Show</span>
                        <span>optional fields (Routing & SWIFT)</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': showOptional }"></i>
                    </button>

                    {{-- Optional Fields --}}
                    <div x-show="showOptional" x-collapse class="space-y-4 pt-2">
                        <div>
                            <label class="block text-sm text-[#7A7A7A] mb-1.5">Routing Number <span class="text-[#7A7A7A]">(Optional)</span></label>
                            <input
                                type="text"
                                name="routing_number"
                                x-model="routing_number"
                                maxlength="9"
                                placeholder="9-digit routing number"
                                class="field-input w-full px-4 py-3 rounded-lg text-sm">
                        </div>

                        <div>
                            <label class="block text-sm text-[#7A7A7A] mb-1.5">SWIFT Code <span class="text-[#7A7A7A]">(Optional)</span></label>
                            <input
                                type="text"
                                name="swift_code"
                                x-model="swift_code"
                                maxlength="11"
                                placeholder="8-11 character identifier"
                                class="field-input w-full px-4 py-3 rounded-lg text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-[#E0E5E5]">

            {{-- Section: Security --}}
            <div>
                <h3 class="text-sm font-semibold text-[#4A4A4A] uppercase tracking-wider mb-4">Security & Details</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-[#7A7A7A] mb-1.5">Transaction PIN <span class="text-[#D55672]">*</span></label>
                        <input
                            type="password"
                            name="pin"
                            x-model="pin"
                            pattern="[0-9]+"
                            minlength="4"
                            maxlength="10"
                            required
                            placeholder="Enter your PIN"
                            class="field-input w-full px-4 py-3 rounded-lg text-sm">
                    </div>

                    <div>
                        <label class="block text-sm text-[#7A7A7A] mb-1.5">Description / Note <span class="text-[#7A7A7A]">(Optional)</span></label>
                        <textarea
                            name="Description"
                            x-model="description"
                            rows="3"
                            placeholder="Purpose of transfer..."
                            class="field-input w-full px-4 py-3 rounded-lg text-sm resize-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route('dashboard') }}" class="flex-1 px-5 py-3.5 text-center rounded-lg border border-[#C8D1D1] hover:bg-[#DFE5E5] transition-colors text-sm font-medium text-[#7A7A7A]">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="flex-1 px-5 py-3.5 rounded-lg font-medium text-white text-sm bg-primary-500 hover:bg-primary-600 transition-colors flex items-center justify-center space-x-2">
                    <span>Review Transfer</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </form>

    {{-- Preview Confirmation Modal --}}
    <div
        x-show="showPreview"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;">

        <div @click="showPreview = false" class="absolute inset-0 bg-black/30"></div>

        <div
            x-show="showPreview"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white border border-[#E0E5E5] rounded-xl p-6 w-full max-w-md z-10">

            <div class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-semibold text-[#0D1B23]">Confirm Transfer</h3>
                <button @click="showPreview = false" class="w-8 h-8 rounded-lg hover:bg-[#DFE5E5] flex items-center justify-center transition-colors">
                    <i data-lucide="x" class="w-4 h-4 text-[#7A7A7A]"></i>
                </button>
            </div>

            <div class="space-y-0 mb-5">
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Amount</span>
                    <span class="text-[#0D1B23] font-semibold text-lg" x-text="currency + parseFloat(amount || 0).toFixed(2)"></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Recipient</span>
                    <span class="text-[#0D1B23] font-medium text-sm" x-text="accountname"></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Account Number</span>
                    <span class="text-[#4A4A4A] font-mono text-sm" x-text="accountnumber"></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Bank</span>
                    <span class="text-[#0D1B23] text-sm" x-text="bankname"></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Account Type</span>
                    <span class="text-[#0D1B23] text-sm" x-text="accounttype"></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Fee</span>
                    <span class="text-[#28A745] text-sm font-medium">Free</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-[#7A7A7A] text-sm">Remaining Balance</span>
                    <span class="text-[#0D1B23] font-semibold" x-text="currency + remainingBalance.toFixed(2)"></span>
                </div>
            </div>

            <div class="bg-[#3e4e83]/10 border border-[#3e4e83]/20 rounded-lg p-3 mb-5">
                <div class="flex items-start space-x-2.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-[#3e4e83] flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#7A7A7A]">This transfer is irreversible. Please verify all details before confirming.</p>
                </div>
            </div>

            <div class="flex space-x-3">
                <button
                    @click="showPreview = false"
                    class="flex-1 px-4 py-3 border border-[#C8D1D1] rounded-lg hover:bg-[#DFE5E5] transition-colors text-sm font-medium text-[#7A7A7A]">
                    Cancel
                </button>
                <button
                    @click="submitTransfer()"
                    class="flex-1 px-4 py-3 rounded-lg font-medium text-white text-sm bg-primary-500 hover:bg-primary-600 transition-colors">
                    Confirm Transfer
                </button>
            </div>
        </div>
    </div>

    {{-- Processing Modal --}}
    <div
        x-show="isProcessing"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;">

        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative bg-white border border-[#E0E5E5] rounded-xl p-8 w-full max-w-sm z-10 text-center">
            <div class="w-14 h-14 mx-auto mb-5 rounded-xl bg-primary-50 border border-primary-200 flex items-center justify-center">
                <i data-lucide="loader" class="w-7 h-7 text-primary-500 spin-slow"></i>
            </div>

            <h3 class="text-lg font-semibold text-[#0D1B23] mb-1">Processing Transfer</h3>
            <p class="text-sm text-[#7A7A7A] mb-5" x-text="processingStage"></p>

            <div class="w-full bg-[#DFE5E5] rounded-full h-1.5 mb-2">
                <div
                    class="bg-primary-500 h-1.5 rounded-full transition-all duration-700 ease-out"
                    :style="'width: ' + processingProgress + '%'">
                </div>
            </div>
            <p class="text-xs text-[#7A7A7A] font-medium" x-text="processingProgress + '%'"></p>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
});
</script>

@endsection
  
 
