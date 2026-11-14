 <!-- Top Up Modal -->
 <div id="topupModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">
                 <img alt="" src="{{ asset('storage/app/public/photos/' . $user->profile_photo_path) }}"
                     width="60" height="60" style='border-radius: 50%;'>
                 <h4 class="modal-title pl-1">Fund/Debit Account.</strong></h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body ">
                 <form method="post" action="{{ route('topup') }}" id="fundingForm">
                     @csrf
                     
                     <div class="alert alert-info">
                         <i class="fa fa-info-circle"></i> <strong>Note:</strong> For incoming credits (funds received from external banks), all sender information is required for proper record keeping and compliance.
                     </div>
                     
                     <div class="row">
                         <div class="col-md-6">
                             <div class="form-group">
                                 <h5>Amount <span class="text-danger">*</span></h5>
                                 <input class="form-control" placeholder="Enter amount" type="number" step="0.01" name="amount" required>
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="form-group">
                                 <h5>Transaction Type <span class="text-danger">*</span></h5>
                                 <select class="form-control" name="t_type" id="transactionType" required onchange="toggleSenderFields()">
                                     <option value="">Select type</option>
                                     <option value="Credit">Credit (Fund Account - Money In)</option>
                                     <option value="Debit">Debit (Deduct Funds - Money Out)</option>
                                     <option value="Failed">Failed Transaction</option>
                                     <option value="Pending">Pending Transaction</option>
                                     <option value="Error">Error Transaction</option>
                                 </select>
                             </div>
                         </div>
                     </div>
                     
                     <div class="row" id="historyDirectionRow" style="display: none;">
                         <div class="col-md-6">
                             <div class="form-group">
                                 <h5>Show As <span class="text-danger">*</span></h5>
                                 <select class="form-control" name="history_direction" id="historyDirection">
                                     <option value="Debit">Debit (Money Out)</option>
                                     <option value="Credit">Credit (Money In)</option>
                                 </select>
                                 <small class="form-text text-muted">How this appears in the user history. Balance is not changed.</small>
                             </div>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-md-6">
                             <div class="form-group">
                                 <h5>Account Target <span class="text-danger">*</span></h5>
                                 <select class="form-control" name="type" id="accountTarget" required onchange="toggleSenderFields()">
                                     <option value="balance">Account Balance ({{ $user->currency }}{{ number_format($user->account_bal, 2) }})</option>
                                     <option value="crypto_btc">Bitcoin Balance ({{ number_format($cryptoAccount->btc ?? 0, 8) }} BTC)</option>
                                     <option value="crypto_eth">Ethereum Balance ({{ number_format($cryptoAccount->eth ?? 0, 8) }} ETH)</option>
                                     <option value="crypto_usdt">USDT Balance ({{ number_format($cryptoAccount->usdt ?? 0, 2) }} USDT)</option>
                                 </select>
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="form-group">
                                 <h5>Transfer Method <span class="text-danger">*</span></h5>
                                 <select class="form-control" name="scope" required>
                                     <option value="">Select method</option>
                                     <option value="Wire Transfer">Wire Transfer</option>
                                     <option value="International transfer">International Transfer</option>
                                     <option value="Local transfer">Local Transfer</option>
                                     <option value="Domestic Transfer">Domestic Transfer</option>
                                     <option value="Check Deposit">Check Deposit</option>
                                     <option value="Cash Deposit">Cash Deposit</option>
                                     <option value="Crypto Deposit">Crypto Deposit</option>
                                     <option value="ACH Transfer">ACH Transfer</option>
                                 </select>
                             </div>
                         </div>
                     </div>
                     
                     <!-- Party Information Section - Bank Details (for fiat account targets) -->
                     <div id="senderInfoSection" style="display: none;">
                         <div class="card mb-3" style="border-left: 4px solid #2d8659;">
                             <div class="card-header" style="background: #2d8659; color: white;">
                                 <h5 class="mb-0"><i class="fa fa-university"></i> <span id="senderHeaderText">Party Information</span></h5>
                             </div>
                             <div class="card-body">
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div class="form-group">
                                             <label><strong><span id="nameLabel">Party Name</span></strong> <span class="text-danger">*</span></label>
                                             <input type="text" class="form-control" name="sender_name" id="senderNameInput" placeholder="Full name or company name">
                                             <small class="form-text text-muted" id="nameHelp">Person or entity involved in transaction</small>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="form-group">
                                             <label><strong><span id="bankLabel">Bank Name</span></strong> <span class="text-danger">*</span></label>
                                             <input type="text" class="form-control" name="sender_bank" id="senderBankInput" placeholder="Bank name">
                                             <small class="form-text text-muted" id="bankHelp">Bank institution</small>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="row">
                                     <div class="col-md-6">
                                         <div class="form-group">
                                             <label><strong><span id="accountLabel">Account Number</span></strong> <span class="text-danger">*</span></label>
                                             <input type="text" class="form-control" name="sender_account_number" id="senderAccountInput" placeholder="Account number">
                                             <small class="form-text text-muted" id="accountHelp">Bank account number</small>
                                         </div>
                                     </div>
                                     <div class="col-md-6">
                                         <div class="form-group">
                                             <label><strong>Bank Reference Number</strong> <span class="text-danger">*</span></label>
                                             <input type="text" class="form-control" name="reference_number" id="referenceInput" placeholder="e.g., TXN123456789">
                                             <small class="form-text text-muted">External transaction/confirmation number</small>
                                         </div>
                                     </div>
                                 </div>
                                 <div class="form-group">
                                     <label><strong><span id="addressLabel">Bank Address</span></strong> <span class="text-danger">*</span></label>
                                     <textarea class="form-control" name="sender_bank_address" id="senderAddressInput" rows="2" placeholder="Full bank address including city, state, country"></textarea>
                                     <small class="form-text text-muted" id="addressHelp">Complete address of bank</small>
                                 </div>
                             </div>
                         </div>
                     </div>
                     
                     <!-- Crypto Transaction Info Section (for crypto account targets) -->
                     <div id="cryptoInfoSection" style="display: none;">
                         <div class="card mb-3" style="border-left: 4px solid #f7931a;">
                             <div class="card-header" style="background: #f7931a; color: white;">
                                 <h5 class="mb-0"><i class="fa fa-link"></i> <span id="cryptoHeaderText">Blockchain Transaction Details</span></h5>
                             </div>
                             <div class="card-body">
                                 <div class="form-group">
                                     <label><strong>Wallet Address</strong> <span class="text-danger">*</span></label>
                                     <input type="text" class="form-control" name="crypto_wallet_address" id="cryptoWalletInput" placeholder="e.g., 0x742d35Cc6634C0532925a3b844Bc9e7595f2bD38">
                                     <small class="form-text text-muted" id="cryptoWalletHelp">The wallet address involved in this transaction</small>
                                 </div>
                                 <div class="form-group">
                                     <label><strong>Transaction Hash (TXN ID)</strong> <span class="text-danger">*</span></label>
                                     <input type="text" class="form-control" name="crypto_txn_hash" id="cryptoTxnInput" placeholder="e.g., 0xabc123...def456">
                                     <small class="form-text text-muted">Blockchain transaction hash for verification</small>
                                 </div>
                             </div>
                         </div>
                     </div>
                     
                     <div class="form-group">
                         <h5>Description</h5>
                         <textarea class="form-control" name="Description" rows="2" placeholder="Transaction description or notes"></textarea>
                     </div>
                     
                     <div class="row">
                         <div class="col-md-6">
                             <div class="form-group">
                                 <h5>Transaction Date</h5>
                                 <input class="form-control" name="date" type='datetime-local'>
                                 <small class="form-text text-muted">Leave empty for current date/time</small>
                             </div>
                         </div>
                         <div class="col-md-6">
                             <div class="form-group">
                                 <h5>Notify User</h5>
                                 <select class="form-control" name="notifymailuser">
                                     <option value='1'>Yes - Send email notification</option>
                                     <option value='0'>No - Silent transaction</option>
                                 </select>
                             </div>
                         </div>
                     </div>
                     
                     <div class="form-group">
                         <h5>Admin Notes (Internal Only)</h5>
                         <textarea class="form-control" name="admin_note" rows="2" placeholder="Internal notes (not visible to user)"></textarea>
                         <small class="form-text text-muted">For record keeping and audit purposes</small>
                     </div>

                     <div class="form-group">
                         <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <button type="submit" class="btn btn-primary btn-block btn-lg">
                             <i class="fa fa-check-circle"></i> <span id="submitButtonText">Process Transaction</span>
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

<script>
function isCryptoTarget() {
    const accountTarget = document.getElementById('accountTarget');
    return accountTarget && accountTarget.value.startsWith('crypto_');
}

function getCryptoLabel() {
    const accountTarget = document.getElementById('accountTarget');
    const val = accountTarget ? accountTarget.value : '';
    if (val === 'crypto_btc') return 'Bitcoin (BTC)';
    if (val === 'crypto_eth') return 'Ethereum (ETH)';
    if (val === 'crypto_usdt') return 'USDT';
    return 'Crypto';
}

function toggleSenderFields() {
    const transactionType = document.getElementById('transactionType').value;
    const senderSection = document.getElementById('senderInfoSection');
    const cryptoSection = document.getElementById('cryptoInfoSection');
    const submitButton = document.getElementById('submitButtonText');
    const senderInputs = senderSection.querySelectorAll('input, textarea');
    const cryptoInputs = cryptoSection.querySelectorAll('input');
    const isCrypto = isCryptoTarget();
    
    // Get all dynamic label elements
    const headerText = document.getElementById('senderHeaderText');
    const nameLabel = document.getElementById('nameLabel');
    const bankLabel = document.getElementById('bankLabel');
    const accountLabel = document.getElementById('accountLabel');
    const addressLabel = document.getElementById('addressLabel');
    const nameHelp = document.getElementById('nameHelp');
    const bankHelp = document.getElementById('bankHelp');
    const accountHelp = document.getElementById('accountHelp');
    const addressHelp = document.getElementById('addressHelp');
    const nameInput = document.getElementById('senderNameInput');
    const bankInput = document.getElementById('senderBankInput');
    const accountInput = document.getElementById('senderAccountInput');
    const addressInput = document.getElementById('senderAddressInput');
    
    // Crypto-specific elements
    const cryptoHeaderText = document.getElementById('cryptoHeaderText');
    const cryptoWalletInput = document.getElementById('cryptoWalletInput');
    const cryptoWalletHelp = document.getElementById('cryptoWalletHelp');
    
    if (transactionType === 'Credit') {
        document.getElementById('historyDirectionRow').style.display = 'none';
        if (isCrypto) {
            // Crypto credit: show wallet + txn hash only
            senderSection.style.display = 'none';
            cryptoSection.style.display = 'block';
            submitButton.textContent = 'Process ' + getCryptoLabel() + ' Credit';
            
            cryptoHeaderText.textContent = 'Incoming ' + getCryptoLabel() + ' Transaction Details';
            cryptoWalletHelp.textContent = 'Sender wallet address that sent the ' + getCryptoLabel();
            
            // Make crypto fields required, bank fields not required
            senderInputs.forEach(input => { input.required = false; });
            cryptoInputs.forEach(input => { input.required = true; });
        } else {
            // Fiat credit: show bank details
            senderSection.style.display = 'block';
            cryptoSection.style.display = 'none';
            submitButton.textContent = 'Process Credit (Fund Account)';
            
            headerText.textContent = 'Sender Information (Required for Credits)';
            nameLabel.textContent = 'Sender Name';
            bankLabel.textContent = 'Sender Bank Name';
            accountLabel.textContent = 'Sender Account Number';
            addressLabel.textContent = 'Sender Bank Address';
            nameHelp.textContent = 'Person or entity sending the funds';
            bankHelp.textContent = 'Originating bank institution';
            accountHelp.textContent = "Sender's bank account number";
            addressHelp.textContent = "Complete address of sender's bank";
            nameInput.placeholder = 'Full name or company name of sender';
            bankInput.placeholder = "Sender's bank name";
            accountInput.placeholder = "Sender's account number";
            addressInput.placeholder = "Sender's bank address (city, state, country)";
            
            senderInputs.forEach(input => {
                if (input.name !== 'admin_note') { input.required = true; }
            });
            cryptoInputs.forEach(input => { input.required = false; });
        }
    } else if (transactionType === 'Debit') {
        document.getElementById('historyDirectionRow').style.display = 'none';
        if (isCrypto) {
            // Crypto debit: show wallet + txn hash only
            senderSection.style.display = 'none';
            cryptoSection.style.display = 'block';
            submitButton.textContent = 'Process ' + getCryptoLabel() + ' Debit';
            
            cryptoHeaderText.textContent = 'Outgoing ' + getCryptoLabel() + ' Transaction Details';
            cryptoWalletHelp.textContent = 'Recipient wallet address receiving the ' + getCryptoLabel();
            
            senderInputs.forEach(input => { input.required = false; });
            cryptoInputs.forEach(input => { input.required = true; });
        } else {
            // Fiat debit: show bank details
            senderSection.style.display = 'block';
            cryptoSection.style.display = 'none';
            submitButton.textContent = 'Process Debit (Deduct Funds)';
            
            headerText.textContent = 'Recipient Information (Required for Debits)';
            nameLabel.textContent = 'Recipient Name';
            bankLabel.textContent = 'Recipient Bank Name';
            accountLabel.textContent = 'Recipient Account Number';
            addressLabel.textContent = 'Recipient Bank Address';
            nameHelp.textContent = 'Person or entity receiving the funds';
            bankHelp.textContent = 'Destination bank institution';
            accountHelp.textContent = "Recipient's bank account number";
            addressHelp.textContent = "Complete address of recipient's bank";
            nameInput.placeholder = 'Full name or company name of recipient';
            bankInput.placeholder = "Recipient's bank name";
            accountInput.placeholder = "Recipient's account number";
            addressInput.placeholder = "Recipient's bank address (city, state, country)";
            
            senderInputs.forEach(input => {
                if (input.name !== 'admin_note') { input.required = true; }
            });
            cryptoInputs.forEach(input => { input.required = false; });
        }
    } else if (['Failed', 'Pending', 'Error'].includes(transactionType)) {
        // History-only statuses — no balance change
        document.getElementById('historyDirectionRow').style.display = 'block';
        senderSection.style.display = isCrypto ? 'none' : 'block';
        cryptoSection.style.display = isCrypto ? 'block' : 'none';
        submitButton.textContent = 'Create ' + transactionType + ' Transaction';

        headerText.textContent = 'Party Information (Optional)';
        nameLabel.textContent = 'Party Name';
        bankLabel.textContent = 'Bank Name';
        accountLabel.textContent = 'Account Number';
        addressLabel.textContent = 'Bank Address';
        nameHelp.textContent = 'Optional — shown on the user receipt';
        bankHelp.textContent = 'Optional bank institution';
        accountHelp.textContent = 'Optional account number';
        addressHelp.textContent = 'Optional bank address';

        senderInputs.forEach(input => { input.required = false; });
        cryptoInputs.forEach(input => { input.required = false; });
    } else {
        // Hide both sections if no type selected
        document.getElementById('historyDirectionRow').style.display = 'none';
        senderSection.style.display = 'none';
        cryptoSection.style.display = 'none';
        submitButton.textContent = 'Process Transaction';
        
        senderInputs.forEach(input => { input.required = false; });
        cryptoInputs.forEach(input => { input.required = false; });
    }
}

// Initialize on modal open
$('#topupModal').on('shown.bs.modal', function () {
    toggleSenderFields();
});
</script>
 <!-- /deposit for a plan Modal -->

 <!-- Bulk Transaction Generator Modal -->
 <div id="bulkTransactionModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header bg-{{ $bg }}">
                 <h4 class="modal-title text-{{ $text }}">Generate Bulk Transactions for {{ $user->name }}</h4>
                 <button type="button" class="close text-{{ $text }}" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body bg-{{ $bg }}">
                 <form method="post" action="{{ route('bulkTransactions') }}">
                     @csrf
                     <input type="hidden" name="user_id" value="{{ $user->id }}">

                     <div class="alert alert-info">
                         <i class="fa fa-info-circle"></i> Generate multiple test transactions with randomized data. No notifications will be sent to the user.
                     </div>

                     <div class="row">
                         <div class="col-md-6">
                             <div class="form-group">
                                 <label class="text-{{ $text }}">Transaction Type <span class="text-danger">*</span></label>
                                 <select class="form-control bg-{{ $bg }} text-{{ $text }}" name="transaction_type" required>
                                     <option value="">Select Type</option>
                                     <option value="Credit">Credit (Incoming Money)</option>
                                     <option value="Debit">Debit (Outgoing Money)</option>
                                 </select>
                             </div>
                         </div>

                         <div class="col-md-6">
                             <div class="form-group">
                                 <label class="text-{{ $text }}">Payment Mode <span class="text-danger">*</span></label>
                                 <select class="form-control bg-{{ $bg }} text-{{ $text }}" name="payment_mode" required>
                                     <option value="">Select Mode</option>
                                     <option value="Wire Transfer">Wire Transfer</option>
                                     <option value="Domestic Transfer">Domestic Transfer</option>
                                     <option value="Local transfer">Local Transfer</option>
                                     <option value="Internal Transfer">Internal Transfer</option>
                                     <option value="Paypal">PayPal</option>
                                     <option value="Cryptocurrency">Cryptocurrency</option>
                                     <option value="Bitcoin">Bitcoin</option>
                                     <option value="Western Union">Western Union</option>
                                 </select>
                             </div>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-md-6">
                             <div class="form-group">
                                 <label class="text-{{ $text }}">Minimum Amount <span class="text-danger">*</span></label>
                                 <input type="number" step="0.01" min="1" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                        name="min_amount" placeholder="e.g., 100.00" required>
                             </div>
                         </div>

                         <div class="col-md-6">
                             <div class="form-group">
                                 <label class="text-{{ $text }}">Maximum Amount <span class="text-danger">*</span></label>
                                 <input type="number" step="0.01" min="1" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                        name="max_amount" placeholder="e.g., 5000.00" required>
                             </div>
                         </div>
                     </div>

                     <div class="row">
                         <div class="col-md-6">
                             <div class="form-group">
                                 <label class="text-{{ $text }}">From Date <span class="text-danger">*</span></label>
                                 <input type="date" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                        name="from_date" required>
                             </div>
                         </div>

                         <div class="col-md-6">
                             <div class="form-group">
                                 <label class="text-{{ $text }}">To Date <span class="text-danger">*</span></label>
                                 <input type="date" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                        name="to_date" required>
                             </div>
                         </div>
                     </div>

                     <div class="form-group">
                         <label class="text-{{ $text }}">Number of Transactions <span class="text-danger">*</span></label>
                         <input type="number" min="1" max="500" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                name="count" placeholder="e.g., 50" required>
                         <small class="form-text text-muted">Maximum: 500 transactions per batch</small>
                     </div>

                     <div class="alert alert-warning">
                         <i class="fa fa-exclamation-triangle"></i> <strong>Important:</strong>
                         <ul class="mb-0 mt-2">
                             <li>Transactions bypass daily/weekly/monthly limits</li>
                             <li>For debits, if balance becomes insufficient, remaining transactions will be skipped</li>
                             <li>Random account names, numbers, and banks will be generated</li>
                             <li>Current balance: <strong>{{ $settings->currency }}{{ number_format($user->account_bal, 2) }}</strong></li>
                         </ul>
                     </div>

                     <div class="form-group">
                         <button type="submit" class="btn btn-primary">
                             <i class="fa fa-cogs"></i> Generate Transactions
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- /Bulk Transaction Generator Modal -->

 <!-- Transfer Codes Management Modal -->
 <div id="transferCodesModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header bg-{{ $bg }}">
                 <h4 class="modal-title text-{{ $text }}">
                     <i class="fa fa-shield-alt"></i> Manage Transfer Codes for {{ $user->name }}
                 </h4>
                 <button type="button" class="close text-{{ $text }}" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body bg-{{ $bg }}">
                 <form method="post" action="{{ route('updateUserTransferCodes', $user->id) }}">
                     @csrf

                     <div class="alert alert-info">
                         <i class="fa fa-info-circle"></i> <strong>Per-User Transfer Code Settings</strong>
                         <p class="mb-0 mt-2">Configure which transfer verification codes are required for this specific user. 
                         When set to "Global Default", the system-wide settings will be used.</p>
                     </div>

                     <!-- Code Values Section -->
                     <div class="card mb-3">
                         <div class="card-header bg-dark text-white">
                             <h6 class="mb-0"><i class="fa fa-key"></i> Code Values</h6>
                         </div>
                         <div class="card-body">
                             <div class="row">
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label class="text-{{ $text }}">{{ $settings->code1 ?? 'Code 1' }} Value</label>
                                         <input type="text" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                                name="code1_value" value="{{ $user->code1 }}" 
                                                placeholder="Enter code 1 value">
                                     </div>
                                 </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label class="text-{{ $text }}">{{ $settings->code2 ?? 'Code 2' }} Value</label>
                                         <input type="text" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                                name="code2_value" value="{{ $user->code2 }}" 
                                                placeholder="Enter code 2 value">
                                     </div>
                                 </div>
                                 <div class="col-md-4">
                                     <div class="form-group">
                                         <label class="text-{{ $text }}">{{ $settings->code3 ?? 'Code 3' }} Value</label>
                                         <input type="text" class="form-control bg-{{ $bg }} text-{{ $text }}" 
                                                name="code3_value" value="{{ $user->code3 }}" 
                                                placeholder="Enter code 3 value">
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <!-- Code Enable/Disable Section -->
                     <div class="card mb-3">
                         <div class="card-header bg-dark text-white">
                             <h6 class="mb-0"><i class="fa fa-toggle-on"></i> Enable/Disable Codes</h6>
                         </div>
                         <div class="card-body">
                             <div class="row">
                                 <!-- Code 1 Status -->
                                 <div class="col-md-6 mb-3">
                                     <h6 class="text-{{ $text }}">
                                         {{ $settings->code1 ?? 'Code 1' }}
                                         <small class="text-muted">(Global: {{ $settings->code1status == '1' ? 'On' : 'Off' }})</small>
                                     </h6>
                                     <div class="selectgroup">
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code1_enabled" value="1" class="selectgroup-input"
                                                 {{ $user->code1_enabled === 1 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Enabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code1_enabled" value="0" class="selectgroup-input"
                                                 {{ $user->code1_enabled === 0 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Disabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code1_enabled" value="" class="selectgroup-input"
                                                 {{ $user->code1_enabled === null ? 'checked' : '' }}>
                                             <span class="selectgroup-button">Global Default</span>
                                         </label>
                                     </div>
                                 </div>

                                 <!-- Code 2 Status -->
                                 <div class="col-md-6 mb-3">
                                     <h6 class="text-{{ $text }}">
                                         {{ $settings->code2 ?? 'Code 2' }}
                                         <small class="text-muted">(Global: {{ $settings->code2status == '1' ? 'On' : 'Off' }})</small>
                                     </h6>
                                     <div class="selectgroup">
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code2_enabled" value="1" class="selectgroup-input"
                                                 {{ $user->code2_enabled === 1 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Enabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code2_enabled" value="0" class="selectgroup-input"
                                                 {{ $user->code2_enabled === 0 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Disabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code2_enabled" value="" class="selectgroup-input"
                                                 {{ $user->code2_enabled === null ? 'checked' : '' }}>
                                             <span class="selectgroup-button">Global Default</span>
                                         </label>
                                     </div>
                                 </div>

                                 <!-- Code 3 Status -->
                                 <div class="col-md-6 mb-3">
                                     <h6 class="text-{{ $text }}">
                                         {{ $settings->code3 ?? 'Code 3' }}
                                         <small class="text-muted">(Global: {{ $settings->code3status == '1' ? 'On' : 'Off' }})</small>
                                     </h6>
                                     <div class="selectgroup">
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code3_enabled" value="1" class="selectgroup-input"
                                                 {{ $user->code3_enabled === 1 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Enabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code3_enabled" value="0" class="selectgroup-input"
                                                 {{ $user->code3_enabled === 0 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Disabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="code3_enabled" value="" class="selectgroup-input"
                                                 {{ $user->code3_enabled === null ? 'checked' : '' }}>
                                             <span class="selectgroup-button">Global Default</span>
                                         </label>
                                     </div>
                                 </div>

                                 <!-- OTP Status -->
                                 <div class="col-md-6 mb-3">
                                     <h6 class="text-{{ $text }}">
                                         OTP Verification
                                         <small class="text-muted">(Global: {{ $settings->otp == '1' ? 'On' : 'Off' }})</small>
                                     </h6>
                                     <div class="selectgroup">
                                         <label class="selectgroup-item">
                                             <input type="radio" name="otp_enabled" value="1" class="selectgroup-input"
                                                 {{ $user->otp_enabled === 1 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Enabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="otp_enabled" value="0" class="selectgroup-input"
                                                 {{ $user->otp_enabled === 0 ? 'checked' : '' }}>
                                             <span class="selectgroup-button">User: Disabled</span>
                                         </label>
                                         <label class="selectgroup-item">
                                             <input type="radio" name="otp_enabled" value="" class="selectgroup-input"
                                                 {{ $user->otp_enabled === null ? 'checked' : '' }}>
                                             <span class="selectgroup-button">Global Default</span>
                                         </label>
                                     </div>
                                 </div>
                             </div>

                             <!-- Quick Actions -->
                             <div class="row mt-3">
                                 <div class="col-md-12">
                                     <button type="button" class="btn btn-sm btn-secondary" 
                                             onclick="copyGlobalSettings()">
                                         <i class="fa fa-copy"></i> Copy Global Settings to User
                                     </button>
                                     <button type="button" class="btn btn-sm btn-warning" 
                                             onclick="resetToGlobal()">
                                         <i class="fa fa-undo"></i> Reset All to Global Default
                                     </button>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="form-group">
                         <button type="submit" class="btn btn-primary btn-block">
                             <i class="fa fa-save"></i> Save Transfer Code Settings
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- /Transfer Codes Management Modal -->

 <script>
 function copyGlobalSettings() {
     // Copy global settings to user settings (enable all that are globally enabled)
     @if($settings->code1status == '1')
         document.querySelector('input[name="code1_enabled"][value="1"]').checked = true;
     @else
         document.querySelector('input[name="code1_enabled"][value="0"]').checked = true;
     @endif

     @if($settings->code2status == '1')
         document.querySelector('input[name="code2_enabled"][value="1"]').checked = true;
     @else
         document.querySelector('input[name="code2_enabled"][value="0"]').checked = true;
     @endif

     @if($settings->code3status == '1')
         document.querySelector('input[name="code3_enabled"][value="1"]').checked = true;
     @else
         document.querySelector('input[name="code3_enabled"][value="0"]').checked = true;
     @endif

     @if($settings->otp == '1')
         document.querySelector('input[name="otp_enabled"][value="1"]').checked = true;
     @else
         document.querySelector('input[name="otp_enabled"][value="0"]').checked = true;
     @endif

     alert('Global settings copied! Click "Save" to apply changes.');
 }

 function resetToGlobal() {
     // Set all to "Global Default" (empty value)
     document.querySelectorAll('input[name="code1_enabled"][value=""]')[0].checked = true;
     document.querySelectorAll('input[name="code2_enabled"][value=""]')[0].checked = true;
     document.querySelectorAll('input[name="code3_enabled"][value=""]')[0].checked = true;
     document.querySelectorAll('input[name="otp_enabled"][value=""]')[0].checked = true;

     alert('All codes reset to Global Default! Click "Save" to apply changes.');
 }
 </script>

 <!--user action mode-->
 <div id="userAction" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header bg-{{ $bg }}">
                 <h4 class="modal-title text-{{ $text }}">Action amount for{{ $user->name }} account.</strong>
                 </h4>
                 <button type="button" class="close text-{{ $text }}" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body bg-{{ $bg }}">
                 <form method="post" action="{{ route('action') }}">
                     @csrf
                     <div class="form-group">
                         <h5 class="text-{{ $text }}">On or Off Action</h5>
                         <select class="form-control bg-{{ $bg }} text-{{ $text }}" name="type"
                             required>
                             <option value="" selected disabled>Select Column</option>
                             <option value="Yes">On upgrade action</option>
                             <option value="No">Off upgrade action</option>
                         </select>
                     </div>
                     <div class="form-group">
                         <input class="form-control bg-{{ $bg }} text-{{ $text }}"
                             placeholder="Enter actoin amount" type="text" name="amount">
                     </div>

                     <div class="form-group">
                         <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <input type="submit" class="btn btn-{{ $text }}" value="Submit">
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!--user action modal end-->
 <!--signal action model-->


 <div id="userActionsignal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header bg-{{ $bg }}">
                 <h4 class="modal-title text-{{ $text }}">Signal action for {{ $user->name }}
                     account.</strong></h4>
                 <button type="button" class="close text-{{ $text }}" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body bg-{{ $bg }}">
                 <form method="post" action="{{ route('signalaction') }}">
                     @csrf
                     <div class="form-group">
                         <h5 class="text-{{ $text }}">On or Off signal action</h5>
                         <select class="form-control bg-{{ $bg }} text-{{ $text }}"
                             name="signalstatus" required>
                             <option value="" selected disabled>Select Column</option>
                             <option value="Yes">On signal</option>
                             <option value="No">Off signal</option>
                         </select>
                     </div>
                     <div class="form-group">
                         <input class="form-control bg-{{ $bg }} text-{{ $text }}"
                             placeholder="Enter actoin amount" type="text" name="signalamount">
                     </div>
                     <div class="form-group">
                         <input class="form-control bg-{{ $bg }} text-{{ $text }}"
                             placeholder="Enter signal name" type="text" name="signalname">
                     </div>

                     <div class="form-group">
                         <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <input type="submit" class="btn btn-{{ $text }}" value="Submit">
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!--user action modal end-->

 <!-- send a single user email Modal-->
 <div id="sendmailtooneuserModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">
                 <h4 class="modal-title ">Send Email</h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body ">
                 <p class="">This message will be sent to {{ $user->name }}</p>
                 <form style="padding:3px;" role="form" method="post"
                     action="{{ route('sendmailtooneuser') }}">
                     @csrf
                     <div class=" form-group">
                         <input type="text" name="subject" class="form-control  " placeholder="Subject" required>
                     </div>
                     <div class=" form-group">
                         <textarea placeholder="Type your message here" class="form-control  " name="message" row="8"
                             placeholder="Type your message here" required></textarea>
                     </div>
                     <div class=" form-group">
                         <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <input type="submit" class="btn " value="Send">
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- card -->

<!-- card Modal -->
<div id="card" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
         <h4 class="modal-title pl-1">{{$user->name}} {{$user->middlename}} {{$user->lastname}} Card Details</strong></h4>
         <div class="modal-header ">
         <button type="button" class="close " data-dismiss="modal">&times;</button>
</div>
             <div class="modal-body ">
                 <form method="post" action="{{ route('adminCardupdate') }}">
                     @csrf







			<div class="form-group">
                         <h4 class="">Card Name</h4>
                         <input class="form-control"  value="{{$card->name}}" type="text" name="name"  required>
                     </div>	
                     <div class="form-group">
                         <h4 class="">Card Number</h4>
                         <input class="form-control"  type="text" name="card_number" value="{{$card->card_number}}" required>
                     </div>
                     

			<div class="form-group">
                         <h4 class="">CVV</h4>
                         <input class="form-control"  value="{{$card->cvv}}" type="text" name="cvv" required>
                     </div>

			<div class="form-group">
                         <h4 class="">Expiry</h4>
                         <input class="form-control"  value="{{$card->expiry}}" type="text" name="expiry"  required>
                     </div>

			 <div class="form-group">
                         <h5 class="">Status</h5>
                         <select class="form-control" name="status" required>
                             <option value="{{$card->status}}">{{$card->status}}</option>
                             <option value="Not Active">Not Active</option>
                             <option value="Active">Active</option>
                             <option value="Blocked">Blocked</option>
                            
                         </select>
                      
                     </div>
			
			<div class="form-group">
                         <h4 class="">Amount</h4>
                         <input class="form-control" value="{{$card->amount}}"  type="text" name="amount"  required>
                     </div>

                     
                     <div class="form-group">
                         <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <input type="submit" class="btn btn-primary" value="Save Card">
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>


 <!-- card ends -->
 <!-- /Trading History Modal -->

 <div id="TradingModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">
                 <h4 class="modal-title "> <img alt=""
                         src="{{ $settings->site_address }}/storage/app/public/photos/{{ $user->profile_photo_path }}"
                         width="60" height="60" style='border-radius: 50%;'>
                     <h1 class="d-inline text-primary"> {{ $user->name }} {{ $user->l_name }}
                 </h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body ">
                 <form role="form" method="post" action="{{ route('profileimage') }}"
                     enctype="multipart/form-data">
                     @csrf
                     <div class="form-group">
                         <h5 class=" ">Change {{ $user->name }} profile image</h5>

                     </div>
                     <div class="form-group">
                         <h5 class=" ">Profile image</h5>
                         <input type="file" name="photo" class="form-control  ">
                     </div>

                     <div class="form-group">
                         <input type="submit" class="btn btn-primary" value="Change Profile Image">
                         <input type="hidden" name="user_id" value="{{ $user->id }}">
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
 <!-- /send a single user email Modal -->

 <!-- Edit user Modal -->
 <div id="edituser" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">
                 <img alt="" src="{{ asset('storage/app/public/photos/' . $user->profile_photo_path) }}"
                     width="60" height="60" style='border-radius: 50%;'>
                 <h4 class="modal-title pl-1">Edit {{ $user->name }} details.</strong></h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body ">
                 <form role="form" method="post" action="{{ route('edituser') }}">
                     <div class="form-group">
                         <h5 class=" ">Username</h5>
                         <input class="form-control  " id="input1" value="{{ $user->username }}" type="text"
                             name="username" required>
                         {{-- <small>Note: same username should be use in the referral link.</small> --}}
                     </div>
                     <div class="form-group">
                         <h5 class=" ">First Name</h5>
                         <input class="form-control  " value="{{ $user->name }}" type="text" name="name"
                             required>
                     </div>
                     <div class="form-group">
                         <h5 class=" ">Middle Name</h5>
                         <input class="form-control  " value="{{ $user->middlename }}" type="text"
                             name="middlename" required>
                     </div>

                     <div class="form-group">
                         <h5 class=" ">Last Name</h5>
                         <input class="form-control  " value="{{ $user->lastname }}" type="text" name="lastname"
                             required>
                     </div>
                     <div class="form-group">
                         <h5 class=" ">Email</h5>
                         <input class="form-control  " value="{{ $user->email }}" type="text" name="email"
                             required>
                     </div>
                     <div class="form-group">
                         <h5 class=" ">Phone Number</h5>
                         <input class="form-control  " value="{{ $user->phone }}" type="text" name="phone"
                             required>
                     </div>

                     <div class="form-group">
                         <h5 class=" ">Date Of birth</h5>
                         <input class="form-control  " value="{{ $user->dob }}" type="date" name="dob"
                             required>
                     </div>

                     <div class="form-group">
                         <h5 class=" "> Address </h5>
                         <input class="form-control  " value="{{ $user->address }}" type="text" name="address"
                             required>
                     </div>
                     <div class="form-group col-md-12">
                         <h6 class="text-{{ $text }}">Nationality</h6>
                         <select type="text" class="form-control bg-{{ $bg }} text-{{ $text }}"
                             name="country" value='{{ $user->country }}' required>
                             <option value='{{ $user->country }}'>{{ $user->country }}</option>
                             @include('auth.countries')

                         </select>
                     </div>
                     <div class="form-group">
                         <h5 class=" ">Account Number</h5>
                         <input class="form-control  " value="{{ $user->usernumber }}" type="text"
                             name="usernumber" required>
                     </div>
                     <div class="form-group">
                         <h5 class=" ">{{ $settings->code1 }}</h5>
                         <input class="form-control  " value="{{ $user->code1 }}" type="text" name="code1"
                             required>
                     </div>

                     <div class="form-group">
                         <h5 class=" ">{{ $settings->code2 }}</h5>
                         <input class="form-control  " value="{{ $user->code2 }}" type="text" name="code2"
                             required>
                     </div>
                     <div class="form-group">
                         <h5 class=" ">{{ $settings->code3 }}</h5>
                         <input class="form-control" value="{{ $user->code3 }}" type="text" name="code3"
                             required>
                     </div>
                     <div class="form-group col-md-12">
                         <h6 class="text-{{ $text }}">Account Type</h6>
                         <select type="text" class="form-control  text-{{ $text }}" name="accounttype"
                             value='{{ $user->accounttype }}' required>
                             <option value="{{ $user->accounttype }}">{{ $user->accounttype }}</option>
                             <option value="Checking Account">Checking Account</option>
                             <option value="Savings Account">Saving Account</option>
                             <option value="Fixed Deposit Account">Fixed Deposit Account</option>
                             <option value="Current Account">Current Account</option>
                             <option value="Crypto Currency Account">Crypto Currency Account</option>
                             <option value="Business Account">Business Account</option>
                             <option value="Non Resident Account">Non Resident Account</option>
                             <option value="Cooperate Business Account">Cooperate Business Account</option>
                             <option value="Investment Account">Investment Account</option>
                         </select>
                     </div>


                     <div class="form-group col-md-12">
                         <h6 class="text-{{ $text }}">Change user currency</h6>
                         <select type="text" class="form-control bg-{{ $bg }} text-{{ $text }}"
                             name="currency" value='{{ $user->currency }}' required>


                             <option value="USD" selected="selected">United States Dollars</option>
                             <option value="EUR">Euro</option>
                             <option value="GBP">United Kingdom Pounds</option>
                             <option value="DZD">Algeria Dinars</option>

                             <option value="ARP">Argentina Pesos</option>
                             <option value="AUD">Australia Dollars</option>
                             <option value="ATS">Austria Schillings</option>
                             <option value="AZN">Azerbaijani Manat</option>
                             <option value="BSD">Bahamas Dollars</option>
                             <option value="BBD">Barbados Dollars</option>
                             <option value="BDT">Bangladesh Taka</option>
                             <option value="BEF">Belgium Francs</option>
                             <option value="BMD">Bermuda Dollars</option>
                             <option value="BRR">Brazil Real</option>
                             <option value="BGL">Bulgaria Lev</option>
                             <option value="CAD">Canada Dollars</option>
                             <option value="KHR"> Cambodian riel</option>
                             <option value="CLP">Chile Pesos</option>
                             <option value="CNY">China Yuan Renmimbi</option>
                             <option value="CYP">Cyprus Pounds</option>
                             <option value="CSK">Czech Republic Koruna</option>
                             <option value="DKK">Denmark Kroner</option>
                             <option value="NLG">Dutch Guilders</option>
                             <option value="XCD">Eastern Caribbean Dollars</option>
                             <option value="EGP">Egypt Pounds</option>
                             <option value="FJD">Fiji Dollars</option>
                             <option value="FIM">Finland Markka</option>
                             <option value="FRF">France Francs</option>
                             <option value="DEM">Germany Deutsche Marks</option>
                             <option value="XAU">Gold Ounces</option>
                             <option value="GRD">Greece Drachmas</option>
                             <option value="HKD">Hong Kong Dollars</option>
                             <option value="HUF">Hungary Forint</option>
                             <option value="ISK">Iceland Krona</option>
                             <option value="INR">India Rupees</option>
                             <option value="IDR">Indonesia Rupiah</option>
                             <option value="IEP">Ireland Punt</option>
                             <option value="ILS">Israel New Shekels</option>
                             <option value="ITL">Italy Lira</option>
                             <option value="JMD">Jamaica Dollars</option>
                             <option value="JPY">Japan Yen</option>
                             <option value="JOD">Jordan Dinar</option>
                             <option value="KRW">Korea (South) Won</option>
                             <option value="KWD">Kuwait Dinar</option>
                             <option value="LBP">Lebanon Pounds</option>
                             <option value="LUF">Luxembourg Francs</option>
                             <option value="MYR">Malaysia Ringgit</option>
                             <option value="MXN">Mexico Pesos</option>
                             <option value="NLG">Netherlands Guilders</option>
                             <option value="NPR">Nepalese Rupee</option>

                             <option value="NZD">New Zealand Dollars</option>
                             <option value="NGN">Nigerian Naira</option>
                             <option value="NOK">Norway Kroner</option>
                             <option value="PKR">Pakistan Rupees</option>
                             <option value="XPD">Palladium Ounces</option>
                             <option value="PHP">Philippines Pesos</option>
                             <option value="XPT">Platinum Ounces</option>
                             <option value="PLZ">Poland Zloty</option>
                             <option value="PTE">Portugal Escudo</option>
                             <option value="ROL">Romania Leu</option>
                             <option value="RUR">Russia Rubles</option>
                             <option value="SAR">Saudi Arabia Riyal</option>
                             <option value="XAG">Silver Ounces</option>
                             <option value="SGD">Singapore Dollars</option>
                             <option value="LKR">Sri Lankan rupee</option>
                             <option value="SKK">Slovakia Koruna</option>
                             <option value="ZAR">South Africa Rand</option>
                             <option value="KRW">South Korea Won</option>
                             <option value="ESP">Spain Pesetas</option>
                             <option value="XDR">Special Drawing Right (IMF)</option>
                             <option value="SDG">Sudan Dinar</option>
                             <option value="SEK">Sweden Krona</option>
                             <option value="CHF">Switzerland Francs</option>
                             <option value="SYP">Syrian Pound</option>

                             <option value="TWD">Taiwan Dollars</option>
                             <option value="THB">Thailand Baht</option>
                             <option value="TTD">Trinidad and Tobago Dollars</option>
                             <option value="TRL">Turkey Lira</option>
                             <option value="TND">Tunisian dinar</option>
                             <option value="VEB">Venezuela Bolivar</option>
                             <option value="ZMK">Zambia Kwacha</option>
                             <option value="EUR">Euro</option>
                             <option value="XCD">Eastern Caribbean Dollars</option>
                             <option value="XDR">Special Drawing Right (IMF)</option>
                             <option value="XAG">Silver Ounces</option>
                             <option value="XAU">Gold Ounces</option>
                             <option value="XPD">Palladium Ounces</option>
                             <option value="XPT">Platinum Ounces</option>

                         </select>
                     </div>

                     <div class="form-group">
                         <h6 class="text-{{ $text }}">Account Limit ({{ $settings->currency }}) </h6>
                         <input type="number" class="form-control  text-{{ $text }}" name="limit"
                             value='{{ $user->limit }}' required>
                     </div>
                     <div class="form-group">
                         <h6 class="text-{{ $text }}">4 Digit Transaction pin</h6>
                         <input type="text" class="form-control  text-{{ $text }}" name="pin"
                             value='{{ $user->pin }}' required>
                     </div>

                     {{-- <div class="form-group">
                         <h5 class=" ">Country</h5>
                         <input class="form-control" value="{{ $user->country }}" type="text" name="country">
                     </div> --}}
                     {{-- <div class="form-group">
                         <h5 class=" ">Referral link</h5>
                         <input class="form-control  " value="{{ $user->ref_link }}" type="text" name="ref_link"
                             required>
                     </div> --}}
                     <div class="form-group">
                         <input type="hidden" name="_token" value="{{ csrf_token() }}">
                         <input type="hidden" name="user_id" value="{{ $user->id }}">
                         <input type="submit" class="btn  btn-primary" value="Update">
                     </div>
                 </form>
             </div>
             <script>
                 $('#input1').on('keypress', function(e) {
                     return e.which !== 32;
                 });
             </script>
         </div>
     </div>
 </div>
 <!-- /Edit user Modal -->

 <!-- Reset user password Modal -->
 <div id="resetpswdModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">
                 <h4 class="modal-title ">Reset Password</strong></h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body ">
                 <p class="">Are you sure you want to reset password for {{ $user->name }} to <span
                         class="text-primary font-weight-bolder">user01236</span></p>
                 <a class="btn " href="{{ url('admin/dashboard/resetpswd') }}/{{ $user->id }}">Reset Now</a>
             </div>
         </div>
     </div>
 </div>
 <!-- /Reset user password Modal -->

 <!-- Switch useraccount Modal -->
 <div id="switchuserModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">
                 <h4 class="modal-title ">You are about to login as {{ $user->name }}.</strong></h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body ">
                 <a class="btn btn-success"
                     href="{{ url('admin/dashboard/switchuser') }}/{{ $user->id }}">Proceed</a>
             </div>
         </div>
     </div>
 </div>
 <!-- /Switch user account Modal -->

 <!-- Clear account Modal -->
 <div id="clearacctModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">
                 <h4 class="modal-title ">Clear Account</strong></h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body ">
                 <p class="">You are clearing account for {{ $user->name }} to {{ $settings->currency }}0.00
                 </p>
                 <a class="btn " href="{{ url('admin/dashboard/clearacct') }}/{{ $user->id }}">Proceed</a>
             </div>
         </div>
     </div>
 </div>
 <!-- /Clear account Modal -->

 <!-- Delete user Modal -->
 <div id="deleteModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <!-- Modal content-->
         <div class="modal-content">
             <div class="modal-header ">

                 <h4 class="modal-title ">Delete User</strong></h4>
                 <button type="button" class="close " data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body  p-3">
                 <p class="">Are you sure you want to delete {{ $user->name }} Account? Everything associated
                     with this account will be loss.</p>
                 <a class="btn btn-danger" href="{{ url('admin/dashboard/delsystemuser') }}/{{ $user->id }}">Yes
                     i'm sure</a>
             </div>
         </div>
     </div>
 </div>
 <!-- /Delete user Modal -->

 <!-- Account Limits Modal -->
 <div id="accountLimitsModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <img alt="" src="{{ asset('storage/app/public/photos/' . $user->profile_photo_path) }}"
                     width="60" height="60" style='border-radius: 50%;'>
                 <h4 class="modal-title pl-1">Manage Transfer Limits for {{ $user->name }}</h4>
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body">
                 <form method="post" action="{{ route('admin.user.limits.update') }}">
                     @csrf
                     <input type="hidden" name="user_id" value="{{ $user->id }}">
                     
                     <div class="alert alert-info">
                         <i class="fa fa-info-circle"></i> <strong>Note:</strong> When a limit is enabled and reached, transfers will be blocked until the period resets.
                     </div>
                     
                     <!-- Daily Limit -->
                     <div class="card mb-3">
                         <div class="card-body">
                             <div class="custom-control custom-switch mb-2">
                                 <input type="checkbox" class="custom-control-input" id="dailyLimitSwitch" name="daily_limit_enabled"
                                     {{ $user->daily_limit_enabled ? 'checked' : '' }}>
                                 <label class="custom-control-label" for="dailyLimitSwitch">
                                     <strong>Enable Daily Limit</strong>
                                 </label>
                             </div>
                             <div class="form-group">
                                 <label>Daily Transfer Limit ({{ $user->currency }})</label>
                                 <input type="number" step="0.01" class="form-control" name="daily_limit" 
                                     value="{{ $user->daily_limit ?? 0 }}" placeholder="Enter daily limit amount">
                                 <small class="form-text text-muted">Maximum amount user can transfer per day</small>
                             </div>
                         </div>
                     </div>
                     
                     <!-- Weekly Limit -->
                     <div class="card mb-3">
                         <div class="card-body">
                             <div class="custom-control custom-switch mb-2">
                                 <input type="checkbox" class="custom-control-input" id="weeklyLimitSwitch" name="weekly_limit_enabled"
                                     {{ $user->weekly_limit_enabled ? 'checked' : '' }}>
                                 <label class="custom-control-label" for="weeklyLimitSwitch">
                                     <strong>Enable Weekly Limit</strong>
                                 </label>
                             </div>
                             <div class="form-group">
                                 <label>Weekly Transfer Limit ({{ $user->currency }})</label>
                                 <input type="number" step="0.01" class="form-control" name="weekly_limit" 
                                     value="{{ $user->weekly_limit ?? 0 }}" placeholder="Enter weekly limit amount">
                                 <small class="form-text text-muted">Maximum amount user can transfer per week</small>
                             </div>
                         </div>
                     </div>
                     
                     <!-- Monthly Limit -->
                     <div class="card mb-3">
                         <div class="card-body">
                             <div class="custom-control custom-switch mb-2">
                                 <input type="checkbox" class="custom-control-input" id="monthlyLimitSwitch" name="monthly_limit_enabled"
                                     {{ $user->monthly_limit_enabled ? 'checked' : '' }}>
                                 <label class="custom-control-label" for="monthlyLimitSwitch">
                                     <strong>Enable Monthly Limit</strong>
                                 </label>
                             </div>
                             <div class="form-group">
                                 <label>Monthly Transfer Limit ({{ $user->currency }})</label>
                                 <input type="number" step="0.01" class="form-control" name="monthly_limit" 
                                     value="{{ $user->monthly_limit ?? 0 }}" placeholder="Enter monthly limit amount">
                                 <small class="form-text text-muted">Maximum amount user can transfer per month</small>
                             </div>
                         </div>
                     </div>
                     
                     <div class="form-group">
                         <button type="submit" class="btn btn-primary">
                             <i class="fa fa-save"></i> Save Limits
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- Account Status Modal -->
 <div id="accountStatusModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header">
                 <img alt="" src="{{ asset('storage/app/public/photos/' . $user->profile_photo_path) }}"
                     width="60" height="60" style='border-radius: 50%;'>
                 <h4 class="modal-title pl-1">Change Account Status for {{ $user->name }}</h4>
                 <button type="button" class="close" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body">
                 <form method="post" action="{{ route('admin.user.status.update') }}">
                     @csrf
                     <input type="hidden" name="user_id" value="{{ $user->id }}">
                     
                     <div class="alert alert-warning">
                         <i class="fa fa-exclamation-triangle"></i> <strong>Warning:</strong> Changing account status will affect the user's ability to make transfers.
                     </div>
                     
                     <div class="form-group">
                         <label><strong>Current Status:</strong></label>
                         <p>
                             @php
                                 $statusBadges = [
                                     'active' => 'success',
                                     'dormant' => 'warning',
                                     'daily_limit_exceeded' => 'danger',
                                     'weekly_limit_exceeded' => 'danger',
                                     'monthly_limit_exceeded' => 'danger',
                                     'transaction_declined' => 'danger',
                                 ];
                                 $badge = $statusBadges[$user->account_status ?? 'active'] ?? 'secondary';
                             @endphp
                             <span class="badge badge-{{ $badge }} badge-lg">
                                 {{ ucwords(str_replace('_', ' ', $user->account_status ?? 'active')) }}
                             </span>
                         </p>
                     </div>
                     
                     <div class="form-group">
                         <label><strong>Select New Status:</strong></label>
                         <select class="form-control" name="account_status" required>
                             <option value="">-- Select Status --</option>
                             <option value="active" {{ ($user->account_status ?? 'active') == 'active' ? 'selected' : '' }}>
                                Active - All transfers allowed
                             </option>
                             <option value="dormant" {{ ($user->account_status ?? '') == 'dormant' ? 'selected' : '' }}>
                                 Dormant - Account inactive, no transfers
                             </option>
                             <option value="daily_limit_exceeded" {{ ($user->account_status ?? '') == 'daily_limit_exceeded' ? 'selected' : '' }}>
                                 Daily Limit Exceeded - No more transfers today
                             </option>
                             <option value="weekly_limit_exceeded" {{ ($user->account_status ?? '') == 'weekly_limit_exceeded' ? 'selected' : '' }}>
                                 Weekly Limit Exceeded - No more transfers this week
                             </option>
                             <option value="monthly_limit_exceeded" {{ ($user->account_status ?? '') == 'monthly_limit_exceeded' ? 'selected' : '' }}>
                                 Monthly Limit Exceeded - No more transfers this month
                             </option>
                             <option value="transaction_declined" {{ ($user->account_status ?? '') == 'transaction_declined' ? 'selected' : '' }}>
                                 Transaction Declined - All transfers blocked
                             </option>
                         </select>
                     </div>
                     
                     <div class="form-group">
                         <label><strong>Notify User:</strong></label>
                         <select class="form-control" name="notifyuser">
                             <option value="0">No</option>
                             <option value="1" selected>Yes - Send email notification</option>
                         </select>
                     </div>
                     
                     <div class="card bg-light p-3 mb-3">
                         <h6><i class="fa fa-info-circle"></i> Status Descriptions:</h6>
                         <ul class="mb-0" style="font-size: 13px;">
                             <li><strong>Active:</strong> User can make all types of transfers</li>
                             <li><strong>Dormant:</strong> Account is inactive due to inactivity</li>
                             <li><strong>Daily/Weekly/Monthly Limit Exceeded:</strong> Transfer limit reached for the period</li>
                             <li><strong>Transaction Declined:</strong> All transactions are blocked</li>
                         </ul>
                     </div>
                     
                     <div class="form-group">
                         <button type="submit" class="btn btn-danger">
                             <i class="fa fa-exchange-alt"></i> Change Status
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- Block/Ban User Modal -->
 <div id="blockUserModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header bg-danger text-white">
                 <h4 class="modal-title"><i class="fa fa-ban"></i> Block/Ban User Account</h4>
                 <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body">
                 <div class="alert alert-warning">
                     <i class="fa fa-exclamation-triangle"></i> <strong>Warning:</strong> 
                     Blocking or banning this account will prevent the user from making any transactions. 
                     Permanent bans will also prevent login access.
                 </div>
                 
                 <form method="post" action="{{ route('blockUser', $user->id) }}" id="blockUserForm">
                     @csrf
                     
                     <div class="form-group">
                         <label><strong>Block Type:</strong></label>
                         <div>
                             <div class="custom-control custom-radio custom-control-inline">
                                 <input type="radio" id="blockTypeTemp" name="block_type" class="custom-control-input" value="blocked" checked>
                                 <label class="custom-control-label" for="blockTypeTemp">
                                     <span class="badge badge-warning">Temporary Block</span>
                                 </label>
                             </div>
                             <div class="custom-control custom-radio custom-control-inline">
                                 <input type="radio" id="blockTypePerm" name="block_type" class="custom-control-input" value="permanently_banned">
                                 <label class="custom-control-label" for="blockTypePerm">
                                     <span class="badge badge-danger">Permanent Ban</span>
                                 </label>
                             </div>
                         </div>
                         <small class="form-text text-muted">Temporary blocks can be scheduled to auto-unblock. Permanent bans require manual unblock.</small>
                     </div>
                     
                     <div class="form-group">
                         <label><strong>Reason for Block/Ban:</strong> <span class="text-danger">*</span></label>
                         <textarea class="form-control" name="block_reason" rows="4" required 
                                   minlength="10" maxlength="1000"
                                   placeholder="Enter detailed reason (e.g., 'Suspicious activity detected', 'Multiple failed login attempts', 'Violation of terms', etc.)"></textarea>
                         <small class="form-text text-muted">10-1000 characters. This message will be shown to the user.</small>
                     </div>
                     
                     <div class="form-group" id="unblockDaysGroup">
                         <label><strong>Auto-Unblock After (Days):</strong></label>
                         <input type="number" class="form-control" name="unblock_days" min="1" max="365" 
                                placeholder="e.g., 7 for one week" value="7">
                         <small class="form-text text-muted">Account will be automatically unblocked after this many days (1-365).</small>
                     </div>
                     
                     <div class="form-group">
                         <div class="custom-control custom-checkbox">
                             <input type="hidden" name="notify_user" value="0">
                             <input type="checkbox" class="custom-control-input" id="notifyUserBlock" name="notify_user" value="1" checked>
                             <label class="custom-control-label" for="notifyUserBlock">
                                 Send email notification to user
                             </label>
                         </div>
                     </div>
                     
                     <div class="form-group">
                         <div class="custom-control custom-checkbox">
                             <input type="hidden" name="notify_admins" value="0">
                             <input type="checkbox" class="custom-control-input" id="notifyAdminsBlock" name="notify_admins" value="1">
                             <label class="custom-control-label" for="notifyAdminsBlock">
                                 Notify other administrators about this action
                             </label>
                         </div>
                     </div>
                     
                     <div class="form-group">
                         <button type="submit" class="btn btn-danger" id="blockSubmitBtn">
                             <i class="fa fa-ban"></i> <span id="blockBtnText">Block Account</span>
                         </button>
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <!-- Unblock User Modal -->
 <div id="unblockUserModal" class="modal fade" role="dialog">
     <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content">
             <div class="modal-header bg-success text-white">
                 <h4 class="modal-title"><i class="fa fa-check-circle"></i> Unblock User Account</h4>
                 <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
             </div>
             <div class="modal-body">
                 @if(in_array($user->account_status ?? '', ['blocked', 'permanently_banned']))
                 <div class="card bg-light mb-3">
                     <div class="card-body">
                         <h6><i class="fa fa-info-circle"></i> Current Block Information:</h6>
                         <p class="mb-1"><strong>Status:</strong> 
                             <span class="badge {{ $user->account_status == 'permanently_banned' ? 'badge-danger' : 'badge-warning' }}">
                                 {{ $user->account_status == 'permanently_banned' ? 'Permanently Banned' : 'Blocked' }}
                             </span>
                         </p>
                         @if($user->block_reason)
                         <p class="mb-1"><strong>Reason:</strong> {{ $user->block_reason }}</p>
                         @endif
                         @if($user->blocked_at)
                         <p class="mb-1"><strong>Blocked On:</strong> {{ \Carbon\Carbon::parse($user->blocked_at)->format('M d, Y \a\t h:i A') }}</p>
                         @endif
                         @if($user->blockedByAdmin)
                         <p class="mb-1"><strong>Blocked By:</strong> {{ $user->blockedByAdmin->firstName }} {{ $user->blockedByAdmin->lastName }}</p>
                         @endif
                         @if($user->unblock_at)
                         <p class="mb-0"><strong>Scheduled Unblock:</strong> {{ \Carbon\Carbon::parse($user->unblock_at)->format('M d, Y \a\t h:i A') }}</p>
                         @endif
                     </div>
                 </div>
                 @endif
                 
                 <form method="post" action="{{ route('unblockUser', $user->id) }}">
                     @csrf
                     
                     <div class="form-group">
                         <label><strong>Unblock Note (Optional):</strong></label>
                         <textarea class="form-control" name="unblock_note" rows="3" maxlength="500"
                                   placeholder="Optional note about why account is being unblocked (e.g., 'Issue resolved', 'User verified identity', etc.)"></textarea>
                         <small class="form-text text-muted">This note will be logged in the account history.</small>
                     </div>
                     
                     <div class="form-group">
                         <div class="custom-control custom-checkbox">
                             <input type="hidden" name="notify_user" value="0">
                             <input type="checkbox" class="custom-control-input" id="notifyUserUnblock" name="notify_user" value="1" checked>
                             <label class="custom-control-label" for="notifyUserUnblock">
                                 Send email notification to user
                             </label>
                         </div>
                     </div>
                     
                     <div class="form-group">
                         <button type="submit" class="btn btn-success">
                             <i class="fa fa-check-circle"></i> Unblock Account
                         </button>
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>

 <script>
 // Toggle unblock days field based on block type
 document.querySelectorAll('input[name="block_type"]').forEach(function(radio) {
     radio.addEventListener('change', function() {
         const unblockDaysGroup = document.getElementById('unblockDaysGroup');
         const blockBtnText = document.getElementById('blockBtnText');
         
         if (this.value === 'permanently_banned') {
             unblockDaysGroup.style.display = 'none';
             unblockDaysGroup.querySelector('input').removeAttribute('required');
             blockBtnText.textContent = 'Ban Account Permanently';
         } else {
             unblockDaysGroup.style.display = 'block';
             blockBtnText.textContent = 'Block Account';
         }
     });
 });
 </script>
