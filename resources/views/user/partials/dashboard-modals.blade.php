<!-- Modern Deposit Modal -->
<div x-show="depositModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="depositModal = false"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="depositModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         class="bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-[#E0E5E5] overflow-hidden max-h-[90vh] overflow-y-auto">
        
        <div class="sticky top-0 bg-[#F9F9F9] p-6 border-b border-[#E0E5E5] flex items-center justify-between z-10">
            <div>
                <h3 class="text-xl font-display font-bold text-[#0D1B23]">Add Balance</h3>
                <p class="text-sm text-[#7A7A7A]">Cryptocurrency Deposit</p>
            </div>
            <button @click="depositModal = false" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors text-[#4A4A4A]">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form method="post" action="{{ route('savedeposit') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Select Cryptocurrency</label>
                <select name="method" id="selectOption" required
                        class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                    <option value="">Choose crypto...</option>
                    @foreach ($settings->cryptoaddress as $item)
                        <option value="{{ $item->id }}" data-description="{{ $item->address }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Wallet Address</label>
                <div class="relative">
                    <input type="text" id="inputField" readonly
                           class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] pr-12">
                    <button type="button" onclick="copyToClipboard()" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 hover:bg-[#DFE5E5] rounded-lg transition-colors text-[#4A4A4A]"><i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all"
                           placeholder="0.00">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Upload Proof of Payment</label>
                <input type="file" name="screenshot" required accept="image/*"
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#49786B]/20 file:text-[#49786B] hover:file:bg-[#49786B]/30 transition-all">
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Submit Deposit
            </button>
        </form>
    </div>
</div>

<!-- Modern Transfer Modal -->
<div x-show="transferModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="transferModal = false"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="transferModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         class="bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-[#E0E5E5] overflow-hidden max-h-[90vh] overflow-y-auto">
        
        <div class="sticky top-0 bg-[#F9F9F9] p-6 border-b border-[#E0E5E5] flex items-center justify-between z-10">
            <h3 class="text-xl font-display font-bold text-[#0D1B23]" x-text="'Transfer via ' + transferType.charAt(0).toUpperCase() + transferType.slice(1)"></h3>
            <button @click="transferModal = false" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors text-[#4A4A4A]">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Wire Transfer Form -->
        <form x-show="transferType === 'wire'" method="post" action="{{ route('internationaltransfer') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="method" value="Wire Transfer">
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Bank Name</label>
                <input type="text" name="bankname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Number</label>
                <input type="text" name="accno" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Routing Number</label>
                <input type="text" name="routeno" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Swift Code</label>
                <input type="text" name="swift" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Type</label>
                <select name="type" required
                        class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                    <option value="">Select type...</option>
                    <option value="Savings">Savings</option>
                    <option value="Current">Current</option>
                    <option value="Checking">Checking</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Description</label>
                <textarea name="narration" rows="3" required
                          class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">PIN</label>
                <input type="password" name="pin" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send Money
            </button>
        </form>

        <!-- Local Transfer Form -->
        <form x-show="transferType === 'local'" method="post" action="{{ route('localtransfer') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="method" value="local Transfer">
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Bank Name</label>
                <input type="text" name="bankname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Number</label>
                <input type="text" name="accno" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Type</label>
                <select name="type" required
                        class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                    <option value="">Select type...</option>
                    <option value="Savings">Savings</option>
                    <option value="Current">Current</option>
                    <option value="Checking">Checking</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Description</label>
                <textarea name="narration" rows="3" required
                          class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">PIN</label>
                <input type="password" name="pin" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send Money
            </button>
        </form>

        <!-- PayPal Transfer Form -->
        <form x-show="transferType === 'paypal'" method="post" action="{{ route('paypal') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">PayPal Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send to PayPal
            </button>
        </form>

        <!-- Similar forms for other transfer types... -->
        <form x-show="transferType === 'skrill'" method="post" action="{{ route('skrill') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Skrill Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send to Skrill
            </button>
        </form>

        <!-- Google Pay Form -->
        <form x-show="transferType === 'googlepay'" method="post" action="{{ route('googlepay') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Google Pay Email/Phone</label>
                <input type="text" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send via Google Pay
            </button>
        </form>

        <!-- Western Union Form -->
        <form x-show="transferType === 'western'" method="post" action="{{ route('westernunion') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Recipient Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Country</label>
                <input type="text" name="country" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send via Western Union
            </button>
        </form>

        <!-- TransferWise Form -->
        <form x-show="transferType === 'wise'" method="post" action="{{ route('transferwise') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Email Address</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send via TransferWise
            </button>
        </form>

        <!-- Payoneer Form -->
        <form x-show="transferType === 'payoneer'" method="post" action="{{ route('payoneer') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Email Address</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send via Payoneer
            </button>
        </form>
    </div>
</div>

<script>
    function copyToClipboard() {
        const input = document.getElementById('inputField');
        input.select();
        document.execCommand('copy');
        alert('Wallet address copied!');
    }
    
    document.getElementById('selectOption')?.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const selectedDescription = selectedOption.dataset.description;
        document.getElementById('inputField').value = selectedDescription || '';
    });
    
    // Reinitialize Lucide icons when modals open
    document.addEventListener('alpine:initialized', () => {
        setInterval(() => {
            lucide.createIcons();
        }, 1000);
    });
</script>

@endsection
                                            <option value="Afganistan">Afghanistan</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Anguilla">Anguilla</option>
                                            <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Aruba">Aruba</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Bonaire">Bonaire</option>
                                            <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                            <option value="Brunei">Brunei</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Canary Islands">Canary Islands</option>
                                            <option value="Cape Verde">Cape Verde</option>
                                            <option value="Cayman Islands">Cayman Islands</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Channel Islands">Channel Islands</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Christmas Island">Christmas Island</option>
                                            <option value="Cocos Island">Cocos Island</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo">Congo</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Cote DIvoire">Cote D'Ivoire</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Curaco">Curacao</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="East Timor">East Timor</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Falkland Islands">Falkland Islands</option>
                                            <option value="Faroe Islands">Faroe Islands</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="French Guiana">French Guiana</option>
                                            <option value="French Polynesia">French Polynesia</option>
                                            <option value="French Southern Ter">French Southern Ter</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Gibraltar">Gibraltar</option>
                                            <option value="Great Britain">Great Britain</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Greenland">Greenland</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guadeloupe">Guadeloupe</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Hawaii">Hawaii</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hong Kong">Hong Kong</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Iran">Iran</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Isle of Man">Isle of Man</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Korea North">Korea North</option>
                                            <option value="Korea Sout">Korea South</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Laos">Laos</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Macau">Macau</option>
                                            <option value="Macedonia">Macedonia</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Martinique">Martinique</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mayotte">Mayotte</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Midway Islands">Midway Islands</option>
                                            <option value="Moldova">Moldova</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montserrat">Montserrat</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Nambia">Nambia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherland Antilles">Netherland Antilles</option>
                                            <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                            <option value="Nevis">Nevis</option>
                                            <option value="New Caledonia">New Caledonia</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Niue">Niue</option>
                                            <option value="Norfolk Island">Norfolk Island</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau Island">Palau Island</option>
                                            <option value="Palestine">Palestine</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Phillipines">Philippines</option>
                                            <option value="Pitcairn Island">Pitcairn Island</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Republic of Montenegro">Republic of Montenegro</option>
                                            <option value="Republic of Serbia">Republic of Serbia</option>
                                            <option value="Reunion">Reunion</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="St Barthelemy">St Barthelemy</option>
                                            <option value="St Eustatius">St Eustatius</option>
                                            <option value="St Helena">St Helena</option>
                                            <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                            <option value="St Lucia">St Lucia</option>
                                            <option value="St Maarten">St Maarten</option>
                                            <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
                                            <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines
                                            </option>
                                            <option value="Saipan">Saipan</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="Samoa American">Samoa American</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Serbia">Serbia</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Swaziland">Swaziland</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syria">Syria</option>
                                            <option value="Tahiti">Tahiti</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tokelau">Tokelau</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Erimates">United Arab Emirates</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="United States of America">United States of America</option>
                                            <option value="Uraguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Vatican City State">Vatican City State</option>
                                            <option value="Venezuela">Venezuela</option>
                                            <option value="Vietnam">Vietnam</option>
                                            <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                            <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                            <option value="Wake Island">Wake Island</option>
                                            <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zaire">Zaire</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>

                                        <!-- <input type="text" class="form-control" id="text11d" placeholder="Enter Bank Name" name="bname" required>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <i class="clear-input">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <ion-icon name="close-circle"></ion-icon>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </i> -->
                                    </div>
                                </div>

                                <div class="form-group basic">

                                    <label class="label" for="text11">Account Type</label>
                                    <div class="input-group mb-2">
                                        <select name="Accounttype" class="form-control">
                                            <option value="Online Banking">Online Banking</option>
                                            <option value="Joint Account">Joint Account</option>
                                            <option value="Savings Account">Savings Account</option>
                                            <option value="Checking">Checking Account</option>
                                            <option value="Current">Current Account</option>
                                            <option value="Corporate">Corporate Account</option>
                                            <option value="Offshore">Offshore Account</option>

                                        </select>
                                        <!-- <input type="text" class="form-control" id="text11"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      placeholder="Wallet_id" name="wallet_id" >
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <i class="clear-input">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <ion-icon name="close-circle"></ion-icon>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </i>-->
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <label class="label">Enter Amount</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"
                                            id="basic-addonb1">{{ Auth::user()->currency }}</span>
                                        <input type="text" class="form-control" placeholder="Enter an amount"
                                            name="amount" required>
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Description</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Description" name="description">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <label class="label">Pin</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"
                                            id="basic-addonb1"></span>
                                        <input type="text" class="form-control" placeholder="Enter an pin"
                                            name="pin" required>
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg"
                                        name="submit">Transfer</button>
                                </div>
                            </form>
                          </div>
                </div>
            </div>
        </div>
    </div>
        <!-- * Withdraw Action Sheet -->

 <div class="modal fade" id="localtransfer" tabindex="-1" role="dialog"  aria-labelledby="withdrawActionSheet" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transfer Money</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id='localtrans'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content">
                            <form method="post" action="{{ route('localtransfer') }}">
                                @csrf
                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Bank Name</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Enter Bank Name" name="bankname" required>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Account Name</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Enter Account Name" name="accountname" required>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>


                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Account Number</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Enter Account Number" name="accountnumber" required>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                               

                                        <input type="hidden" name="method" value="local Transfer">


                                
                                
                                
                                 
                                
                               
                     
                                <div class="form-group basic">

                                    <label class="label" for="text11">Account Type</label>
                                    <div class="input-group mb-2">
                                        <select name="Accounttype" class="form-control">
                                            <option value="Online Banking">Online Banking</option>
                                            <option value="Joint Account">Joint Account</option>
                                            <option value="Savings Account">Savings Account</option>
                                            <option value="Checking">Checking Account</option>
                                            <option value="Current">Current Account</option>
                                            <option value="Corporate">Corporate Account</option>
                                            <option value="Offshore">Offshore Account</option>

                                        </select>
                                        <!-- <input type="text" class="form-control" id="text11"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      placeholder="Wallet_id" name="wallet_id" >
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <i class="clear-input">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <ion-icon name="close-circle"></ion-icon>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </i>-->
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <label class="label">Enter Amount</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"
                                            id="basic-addonb1">{{ Auth::user()->currency }}</span>
                                        <input type="text" class="form-control" placeholder="Enter an amount"
                                            name="amount" required>
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Description</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Description" name="description">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                                <div class="form-group basic">
                                    <label class="label">Pin</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"
                                            id="basic-addonb1"></span>
                                        <input type="text" class="form-control" placeholder="Enter an pin"
                                            name="pin" required>
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg"
                                        name="submit">Transfer</button>
                                </div>
                            </form>
                          </div>
                </div>
            </div>
        </div>
    </div>
        <!-- * Withdraw Action Sheet -->

        <!-- i am adding this sheet-->
        <!-- Withdraw Action Sheet -->
        <div class="modal fade action-sheet" id="west" tabindex="-1" role="dialog"  aria-labelledby="west" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Via Western Union</h5>
                        
                        
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">
                            <form method="post" action="{{ route('westernunion') }}">
                                @csrf
                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Country</label>
                                        <!-- <label for="country">Country</label> -->
                                        <select type="text" name="country" id="country" class="form-control"
                                            required>
                                            <option value="Afganistan">Afghanistan</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Anguilla">Anguilla</option>
                                            <option value="Antigua &amp; Barbuda">Antigua &amp; Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Aruba">Aruba</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia">Bolivia</option>
                                            <option value="Bonaire">Bonaire</option>
                                            <option value="Bosnia &amp; Herzegovina">Bosnia &amp; Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                            <option value="Brunei">Brunei</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Canary Islands">Canary Islands</option>
                                            <option value="Cape Verde">Cape Verde</option>
                                            <option value="Cayman Islands">Cayman Islands</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Channel Islands">Channel Islands</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="Christmas Island">Christmas Island</option>
                                            <option value="Cocos Island">Cocos Island</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo">Congo</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Cote DIvoire">Cote D'Ivoire</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Curaco">Curacao</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czech Republic">Czech Republic</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="East Timor">East Timor</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Falkland Islands">Falkland Islands</option>
                                            <option value="Faroe Islands">Faroe Islands</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="French Guiana">French Guiana</option>
                                            <option value="French Polynesia">French Polynesia</option>
                                            <option value="French Southern Ter">French Southern Ter</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Gibraltar">Gibraltar</option>
                                            <option value="Great Britain">Great Britain</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Greenland">Greenland</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guadeloupe">Guadeloupe</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Hawaii">Hawaii</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hong Kong">Hong Kong</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Iran">Iran</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Isle of Man">Isle of Man</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Korea North">Korea North</option>
                                            <option value="Korea Sout">Korea South</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Laos">Laos</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Macau">Macau</option>
                                            <option value="Macedonia">Macedonia</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Martinique">Martinique</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mayotte">Mayotte</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Midway Islands">Midway Islands</option>
                                            <option value="Moldova">Moldova</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montserrat">Montserrat</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Nambia">Nambia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherland Antilles">Netherland Antilles</option>
                                            <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                            <option value="Nevis">Nevis</option>
                                            <option value="New Caledonia">New Caledonia</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Niue">Niue</option>
                                            <option value="Norfolk Island">Norfolk Island</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau Island">Palau Island</option>
                                            <option value="Palestine">Palestine</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Phillipines">Philippines</option>
                                            <option value="Pitcairn Island">Pitcairn Island</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Republic of Montenegro">Republic of Montenegro</option>
                                            <option value="Republic of Serbia">Republic of Serbia</option>
                                            <option value="Reunion">Reunion</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russia">Russia</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="St Barthelemy">St Barthelemy</option>
                                            <option value="St Eustatius">St Eustatius</option>
                                            <option value="St Helena">St Helena</option>
                                            <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                            <option value="St Lucia">St Lucia</option>
                                            <option value="St Maarten">St Maarten</option>
                                            <option value="St Pierre &amp; Miquelon">St Pierre &amp; Miquelon</option>
                                            <option value="St Vincent &amp; Grenadines">St Vincent &amp; Grenadines
                                            </option>
                                            <option value="Saipan">Saipan</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="Samoa American">Samoa American</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome &amp; Principe">Sao Tome &amp; Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Serbia">Serbia</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Swaziland">Swaziland</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syria">Syria</option>
                                            <option value="Tahiti">Tahiti</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Tanzania">Tanzania</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tokelau">Tokelau</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad &amp; Tobago">Trinidad &amp; Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Turks &amp; Caicos Is">Turks &amp; Caicos Is</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Erimates">United Arab Emirates</option>
                                            <option value="United Kingdom">United Kingdom</option>
                                            <option value="United States of America">United States of America</option>
                                            <option value="Uraguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Vatican City State">Vatican City State</option>
                                            <option value="Venezuela">Venezuela</option>
                                            <option value="Vietnam">Vietnam</option>
                                            <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                            <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                            <option value="Wake Island">Wake Island</option>
                                            <option value="Wallis &amp; Futana Is">Wallis &amp; Futana Is</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zaire">Zaire</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                        </select>

                                        <!-- <input type="text" class="form-control" id="text11d" placeholder="Enter Bank Name" name="bname" required>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  <i class="clear-input">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <ion-icon name="close-circle"></ion-icon>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  </i> -->
                                    </div>
                                </div>

                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">City</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="City Name" name="city" required>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>


                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Zipcode</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Enter Valid Zipcode" name="zipcode" required>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>

                                <div class="form-group basic">
                                    <div class="input-group mb-2">

                                        <input type="hidden" name="method" value="Western Union">

                                    </div>
                                </div>

                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Name</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Enter Account Name" name="name" required>
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>



                                <div class="form-group basic">
                                    <label class="label">Enter Amount</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"
                                            id="basic-addonb1">{{ Auth::user()->currency }}</span>
                                        <input type="text" class="form-control" placeholder="Enter an amount"
                                            name="amount" required>
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11d">Description</label>
                                        <input type="text" class="form-control" id="text11d"
                                            placeholder="Description" name="description">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>
                                <div class="form-group basic">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg"
                                        name="submit">Transfer</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Withdraw Action Sheet -->







        <!-- Send Action Sheet -->
        <div class="modal fade action-sheet" id="sendActionSheet" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transfer Crypto</h5>
                    </div>
                    <div class="modal-body">
                        <div class="action-sheet-content">
                            <form action="{{ route('crypto') }}" method="post">
                                @csrf

                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11">Select Crypto</label>
                                        <select name="paydetails" class="form-control">
                                            @foreach ($dmethods as $method)
                                                <option value="{{ $method->name }}">{{ $method->name }}</option>
                                            @endforeach

                                        </select>

                                    </div>
                                </div>

                                <div class="form-group basic">
                                    <div class="input-wrapper">
                                        <label class="label" for="text11">To</label>
                                        <input type="text" class="form-control" id="text11"
                                            placeholder="Wallet address" name="wallet">
                                        <i class="clear-input">
                                            <ion-icon name="close-circle"></ion-icon>
                                        </i>
                                    </div>
                                </div>


                                <div class="form-group basic">
                                    <div class="input-group mb-2">

                                        <input type="hidden" name="method" value="Cryptocurrency">

                                    </div>
                                </div>


                                <div class="form-group basic">
                                    <label class="label">Enter Amount</label>
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"
                                            id="basic-addon1">{{ Auth::user()->currency }}</span>
                                        <input type="number" class="form-control" placeholder="Enter an amount"
                                            name="amount">
                                    </div>
                                </div>

                                <div class="form-group basic">
                                    <button type="submit" class="btn btn-primary btn-block btn-lg"
                                        name="send">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- * Send Action Sheet -->



        <!-- Stats -->
        <div class="section">
            <div class="row mt-2 mb-1">
                <div class="col-12">
                    <div class="stat-box">
                        <div class="title" style="color:Green;">Total<br>Balance</div>
                        <div class="value text-success h6">
                            {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}</div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="stat-box">
                        <div class="title" style="color:Green;">Refundable <br>Balance</div>
                        <div class="value">
                            {{ Auth::user()->currency }}{{ number_format(Auth::user()->profit, 2, '.', ',') }}</div>
                    </div>
                </div>
            </div>

            <br>


            <!-- App Capsule -->
            <div class="section mt-4">
                <div class="section-heading">
                    <h2 class="title">Transactions</h2>
                    <a href="{{ route('accounthistory') }}" class="link">View All</a>
                </div>
            </div>

            @foreach ($withdrawals as $withdrawal)
                <div class="section mt-4 mb-1">
                    <div class="transactions">
                        <!-- item -->
                        <a href="{{ route('transactiondetails', $withdrawal->id) }}" class='item'>
                            <div class="detail">
                                <!-- <img src="assets/img/sample/brand/1.jpg" alt="img" class="image-block imaged w48"> -->
                                <div>
                                    @if ($withdrawal->type == 'Credit')
                                        <strong>Credit</strong>
                                    @else
                                        <strong>Debit</strong>
                                    @endif
                                    @if ($withdrawal->status == 'Pending')
                                        <p><span
                                                class="badge badge-pill badge-warning badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @elseif($withdrawal->status == 'On-hold' || $withdrawal->status == 'Scheduled' || $withdrawal->status == 'Under Review')
                                        <p><span
                                                class="badge badge-pill badge-warning badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @elseif($withdrawal->status == 'Rejected' || $withdrawal->status == 'Failed' || $withdrawal->status == 'Expired' || $withdrawal->status == 'Frozen')
                                        <p><span
                                                class="badge badge-pill badge-danger badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @elseif($withdrawal->status == 'Processed')
                                        <p><span
                                                class="badge badge-pill badge-success badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @elseif($withdrawal->status == 'Reversed' || $withdrawal->status == 'Refunded')
                                        <p><span
                                                class="badge badge-pill badge-info badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @elseif($withdrawal->status == 'Cancelled')
                                        <p><span
                                                class="badge badge-pill badge-secondary badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @else
                                        <p><span
                                                class="badge badge-pill badge-danger badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="right">
                                @if ($withdrawal->type == 'Credit')
                                    <div class="price"> <span
                                            style="color:green">{{ Auth::user()->currency }}{{ number_format($withdrawal->amount, 2, '.', ',') }}</span>
                                    </div>
                                @else
                                    <div class="price"> <span
                                            style="color: #880808">{{ Auth::user()->currency }}{{ number_format($withdrawal->amount, 2, '.', ',') }}</span>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <!-- * item -->
                    </div>
                </div>
            @endforeach
            @foreach ($deposits as $withdrawal)
                <div class="section mt-4 mb-3">

                    <div class="transactions">
                        <!-- item -->
                        <a href="{{ route('depositdetails', $withdrawal->id) }}" class='item'>
                            <div class="detail">
                                <!-- <img src="assets/img/sample/brand/1.jpg" alt="img" class="image-block imaged w48"> -->
                                <div>
                                    <strong>Deposit</strong>
                                    @if ($withdrawal->status == 'Pending')
                                        <p><span
                                                class="badge badge-pill badge-warning badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @else
                                        <p><span
                                                class="badge badge-pill badge-success badge-sm">{{ $withdrawal->status }}</span>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="right">
                                <div class="price"> <span
                                        style="color:green">{{ Auth::user()->currency }}{{ number_format($withdrawal->amount, 2, '.', ',') }}</span>
                                </div>
                            </div>
                        </a>
                        <!-- * item -->



                    </div>
                </div>
            @endforeach

<!-- Modern Deposit Modal -->
<div x-show="depositModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="depositModal = false"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="depositModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         class="bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-[#E0E5E5] overflow-hidden max-h-[90vh] overflow-y-auto">
        
        <div class="sticky top-0 bg-[#F9F9F9] p-6 border-b border-[#E0E5E5] flex items-center justify-between z-10">
            <div>
                <h3 class="text-xl font-display font-bold text-[#0D1B23]">Add Balance</h3>
                <p class="text-sm text-[#7A7A7A]">Cryptocurrency Deposit</p>
            </div>
            <button @click="depositModal = false" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors text-[#4A4A4A]">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <form method="post" action="{{ route('savedeposit') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Select Cryptocurrency</label>
                <select name="method" id="selectOption" required
                        class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                    <option value="">Choose crypto...</option>
                    @foreach ($settings->cryptoaddress as $item)
                        <option value="{{ $item->id }}" data-description="{{ $item->address }}">
                            {{ $item->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Wallet Address</label>
                <div class="relative">
                    <input type="text" id="inputField" readonly
                           class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] pr-12">
                    <button type="button" onclick="copyToClipboard()" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-2 hover:bg-[#DFE5E5] rounded-lg transition-colors text-[#4A4A4A]"><i data-lucide="copy" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all"
                           placeholder="0.00">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Upload Proof of Payment</label>
                <input type="file" name="screenshot" required accept="image/*"
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-[#49786B]/20 file:text-[#49786B] hover:file:bg-[#49786B]/30 transition-all">
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Submit Deposit
            </button>
        </form>
    </div>
</div>

<!-- Modern Transfer Modal -->
<div x-show="transferModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="transferModal = false"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="transferModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         class="bg-white rounded-2xl shadow-2xl max-w-lg w-full border border-[#E0E5E5] overflow-hidden max-h-[90vh] overflow-y-auto">
        
        <div class="sticky top-0 bg-[#F9F9F9] p-6 border-b border-[#E0E5E5] flex items-center justify-between z-10">
            <h3 class="text-xl font-display font-bold text-[#0D1B23]" x-text="'Transfer via ' + transferType.charAt(0).toUpperCase() + transferType.slice(1)"></h3>
            <button @click="transferModal = false" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors text-[#4A4A4A]">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        
        <!-- Wire Transfer Form -->
        <form x-show="transferType === 'wire'" method="post" action="{{ route('internationaltransfer') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="method" value="Wire Transfer">
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Bank Name</label>
                <input type="text" name="bankname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Number</label>
                <input type="text" name="accno" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Routing Number</label>
                <input type="text" name="routeno" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Swift Code</label>
                <input type="text" name="swift" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Type</label>
                <select name="type" required
                        class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                    <option value="">Select type...</option>
                    <option value="Savings">Savings</option>
                    <option value="Current">Current</option>
                    <option value="Checking">Checking</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Description</label>
                <textarea name="narration" rows="3" required
                          class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">PIN</label>
                <input type="password" name="pin" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send Money
            </button>
        </form>

        <!-- Local Transfer Form -->
        <form x-show="transferType === 'local'" method="post" action="{{ route('localtransfer') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="method" value="local Transfer">
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Bank Name</label>
                <input type="text" name="bankname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Number</label>
                <input type="text" name="accno" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Type</label>
                <select name="type" required
                        class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                    <option value="">Select type...</option>
                    <option value="Savings">Savings</option>
                    <option value="Current">Current</option>
                    <option value="Checking">Checking</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Description</label>
                <textarea name="narration" rows="3" required
                          class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">PIN</label>
                <input type="password" name="pin" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send Money
            </button>
        </form>

        <!-- PayPal Transfer Form -->
        <form x-show="transferType === 'paypal'" method="post" action="{{ route('paypal') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">PayPal Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send to PayPal
            </button>
        </form>

        <!-- Similar forms for other transfer types... -->
        <form x-show="transferType === 'skrill'" method="post" action="{{ route('skrill') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Skrill Email</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send to Skrill
            </button>
        </form>

        <!-- Google Pay Form -->
        <form x-show="transferType === 'googlepay'" method="post" action="{{ route('googlepay') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Google Pay Email/Phone</label>
                <input type="text" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send via Google Pay
            </button>
        </form>

        <!-- Western Union Form -->
        <form x-show="transferType === 'western'" method="post" action="{{ route('westernunion') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Recipient Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Country</label>
                <input type="text" name="country" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send via Western Union
            </button>
        </form>

        <!-- TransferWise Form -->
        <form x-show="transferType === 'wise'" method="post" action="{{ route('transferwise') }}" class="p-6 space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Email Address</label>
                <input type="email" name="email" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Account Name</label>
                <input type="text" name="accname" required
                       class="w-full px-4 py-3 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-2 text-[#0D1B23]">Amount</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A]">{{ Auth::user()->currency }}</span>
                    <input type="number" name="amount" required
                           class="w-full px-4 py-3 pl-12 rounded-xl bg-[#F9F9F9] border border-[#E0E5E5] text-[#0D1B23] focus:border-[#49786B] focus:ring-2 focus:ring-[#49786B]/20 transition-all">
                </div>
            </div>

            <button type="submit" 
                    class="w-full px-6 py-4 rounded-xl bg-[#49786B] hover:bg-[#3B645A] text-white transition-all duration-200 font-semibold text-lg">
                Send via TransferWise
            </button>
        </form>
    </div>
</div>
