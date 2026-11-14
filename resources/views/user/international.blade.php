@extends('layouts.bankapp')
@section('title', 'International Transfer')
@section('content')

<style>
    /* Ally Bank Color Design System — Light Theme */
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
    bankaddress: '',
    accounttype: 'Online Banking',
    country: '',
    swiftcode: '',
    iban: '',
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
        if (!this.accountname || !this.accountnumber || !this.bankname || !this.bankaddress || !this.country || !this.pin) {
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
            { progress: 20, text: 'Initiating international transfer...' },
            { progress: 40, text: 'Verifying account details...' },
            { progress: 60, text: 'Processing with SWIFT network...' },
            { progress: 80, text: 'Confirming with international bank...' },
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
            <a href="{{ route('dashboard') }}" class="w-9 h-9 rounded-lg bg-[#DFE5E5] border border-[#C8D1D1] flex items-center justify-center hover:bg-[#C8D1D1] transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 text-[#7A7A7A]"></i>
            </a>
            <div>
                <h1 class="text-xl md:text-2xl font-semibold text-[#0D1B23]">International Transfer</h1>
                <p class="text-sm text-[#7A7A7A]">Send money globally via SWIFT network</p>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    <x-danger-alert />
    <x-success-alert />

    {{-- Balance Summary --}}
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 mb-6 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs uppercase tracking-wider text-[#7A7A7A] mb-1 font-medium">Available Balance</p>
                <h2 class="text-2xl md:text-3xl font-bold text-[#0D1B23]">
                    {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}
                </h2>
                <p class="text-xs text-[#7A7A7A] mt-1">Account: {{ Auth::user()->accountnumber }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-500/10 border border-primary-500/15 flex items-center justify-center">
                <i data-lucide="globe" class="w-5 h-5 text-primary-500"></i>
            </div>
        </div>
    </div>

    {{-- Transfer Form --}}
    <form action="{{ route('internationaltransfer') }}" method="POST" x-ref="transferForm" @submit.prevent="openPreview()">
        @csrf
        <input type="hidden" name="balance_type" value="fiat">

        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6 space-y-6 shadow-sm">

            {{-- Section: Amount --}}
            <div class="bg-primary-500/5 border border-primary-500/20 rounded-xl p-4">
                <h4 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                    <i data-lucide="banknote" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Transfer Amount
                </h4>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Amount to Send</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-xl font-bold text-primary-500">{{ Auth::user()->currency }}</span>
                            <input
                                type="number"
                                name="amount"
                                x-model="amount"
                                step="0.01"
                                min="1"
                                max="{{ Auth::user()->account_bal }}"
                                required
                                placeholder="0.00"
                                class="w-full px-4 py-4 pl-16 rounded-lg bg-white border-2 border-primary-400 focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-xl font-bold">
                        </div>
                        <div class="flex items-center justify-between mt-2 text-xs">
                            <span class="text-[#7A7A7A]">Available: {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}</span>
                            <span class="text-primary-500 font-semibold" x-show="amount > 0 && parseFloat(amount) <= balance">Processing time: 1-3 business days</span>
                        </div>
                        <p x-show="amount && parseFloat(amount) > balance" x-transition class="text-xs text-[#D55672] mt-1 flex items-center">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5 mr-1"></i>
                            Insufficient balance. Your available balance is {{ Auth::user()->currency }}{{ number_format(Auth::user()->account_bal, 2) }}.
                        </p>
                    </div>

                    <div class="grid grid-cols-4 gap-2">
                        <button type="button" @click="setQuickAmount(100)" class="px-3 py-2 rounded-lg text-xs font-medium bg-white border border-[#E0E5E5] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                            {{ Auth::user()->currency }}100
                        </button>
                        <button type="button" @click="setQuickAmount(500)" class="px-3 py-2 rounded-lg text-xs font-medium bg-white border border-[#E0E5E5] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                            {{ Auth::user()->currency }}500
                        </button>
                        <button type="button" @click="setQuickAmount(1000)" class="px-3 py-2 rounded-lg text-xs font-medium bg-white border border-[#E0E5E5] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                            {{ Auth::user()->currency }}1,000
                        </button>
                        <button type="button" @click="setQuickAmount('all')" class="px-3 py-2 rounded-lg text-xs font-medium bg-white border border-[#E0E5E5] text-[#4A4A4A] hover:bg-[#DFE5E5] hover:text-[#0D1B23] transition-colors">
                            All
                        </button>
                    </div>
                </div>

                <div class="flex items-start space-x-2 px-1 mt-3">
                    <i data-lucide="info" class="w-3.5 h-3.5 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#7A7A7A]">International transfers typically take 1-3 business days to complete.</p>
                </div>
            </div>

            {{-- Section: Beneficiary --}}
            <div class="bg-[#F9F9F9] rounded-xl p-4 border border-[#E0E5E5]">
                <h4 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                    <i data-lucide="user" class="w-4 h-4 mr-2 text-primary-500"></i>
                    Beneficiary Information
                </h4>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Account Holder Name</label>
                        <input
                            type="text"
                            name="accountname"
                            x-model="accountname"
                            required
                            placeholder="Full name as registered"
                            class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Account Number</label>
                            <input
                                type="text"
                                name="accountnumber"
                                x-model="accountnumber"
                                required
                                placeholder="Beneficiary account number"
                                class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Account Type</label>
                            <select
                                name="Accounttype"
                                x-model="accounttype"
                                required
                                class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm appearance-none cursor-pointer">
                                <option value="Online Banking">Online Banking</option>
                                <option value="Checking">Checking Account</option>
                                <option value="Savings Account">Savings Account</option>
                                <option value="Current Account">Current Account</option>
                                <option value="Joint Account">Joint Account</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Bank Details --}}
            <div class="bg-[#F9F9F9] rounded-xl p-4 border border-[#E0E5E5]">
                <h4 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                    <i data-lucide="building-2" class="w-4 h-4 mr-2 text-amber-400"></i>
                    Bank Details
                </h4>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Bank Name</label>
                        <input
                            type="text"
                            name="bankname"
                            x-model="bankname"
                            required
                            placeholder="Recipient's bank name"
                            class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Bank Address</label>
                        <input
                            type="text"
                            name="bankaddress"
                            x-model="bankaddress"
                            required
                            placeholder="Bank's physical address"
                            class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Country</label>
                            <select
                                name="country"
                                x-model="country"
                                required
                                class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm appearance-none cursor-pointer">
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="American Samoa">American Samoa</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Anguilla">Anguilla</option>
                        <option value="Antarctica">Antarctica</option>
                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
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
                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Bouvet Island">Bouvet Island</option>
                        <option value="Brazil">Brazil</option>
                        <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                        <option value="Brunei Darussalam">Brunei Darussalam</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Canada">Canada</option>
                        <option value="Cape Verde">Cape Verde</option>
                        <option value="Cayman Islands">Cayman Islands</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Chile">Chile</option>
                        <option value="China">China</option>
                        <option value="Christmas Island">Christmas Island</option>
                        <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo">Congo</option>
                        <option value="Cook Islands">Cook Islands</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Croatia (Hrvatska)">Croatia (Hrvatska)</option>
                        <option value="Cuba">Cuba</option>
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
                        <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                        <option value="Faroe Islands">Faroe Islands</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="France, Metropolitan">France, Metropolitan</option>
                        <option value="French Guiana">French Guiana</option>
                        <option value="French Polynesia">French Polynesia</option>
                        <option value="French Southern Territories">French Southern Territories</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Gibraltar">Gibraltar</option>
                        <option value="Guernsey">Guernsey</option>
                        <option value="Greece">Greece</option>
                        <option value="Greenland">Greenland</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Guadeloupe">Guadeloupe</option>
                        <option value="Guam">Guam</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Heard and Mc Donald Islands">Heard and Mc Donald Islands</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hong Kong">Hong Kong</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="India">India</option>
                        <option value="Isle of Man">Isle of Man</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Iran (Islamic Republic of)">Iran (Islamic Republic of)</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Ivory Coast">Ivory Coast</option>
                        <option value="Jersey">Jersey</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Korea, Democratic People&#039;s Republic of">Korea, Democratic People&#039;s Republic of</option>
                        <option value="Korea, Republic of">Korea, Republic of</option>
                        <option value="Kosovo">Kosovo</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Lao People&#039;s Democratic Republic">Lao People&#039;s Democratic Republic</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Macau">Macau</option>
                        <option value="Macedonia">Macedonia</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="Martinique">Martinique</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Mayotte">Mayotte</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                        <option value="Moldova, Republic of">Moldova, Republic of</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Montenegro">Montenegro</option>
                        <option value="Montserrat">Montserrat</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Namibia">Namibia</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="Netherlands Antilles">Netherlands Antilles</option>
                        <option value="New Caledonia">New Caledonia</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Niue">Niue</option>
                        <option value="Norfolk Island">Norfolk Island</option>
                        <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                        <option value="Norway">Norway</option>
                        <option value="Oman">Oman</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Palau">Palau</option>
                        <option value="Palestine">Palestine</option>
                        <option value="Panama">Panama</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Pitcairn">Pitcairn</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Puerto Rico">Puerto Rico</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Reunion">Reunion</option>
                        <option value="Romania">Romania</option>
                        <option value="Russian Federation">Russian Federation</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                        <option value="Saint Lucia">Saint Lucia</option>
                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                        <option value="Samoa">Samoa</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
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
                        <option value="South Georgia South Sandwich Islands">South Georgia South Sandwich Islands</option>
                        <option value="South Sudan">South Sudan</option>
                        <option value="Spain">Spain</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="St. Helena">St. Helena</option>
                        <option value="St. Pierre and Miquelon">St. Pierre and Miquelon</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Suriname">Suriname</option>
                        <option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
                        <option value="Swaziland">Swaziland</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                        <option value="Taiwan">Taiwan</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Togo">Togo</option>
                        <option value="Tokelau">Tokelau</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States">United States</option>
                        <option value="United States minor outlying islands">United States minor outlying islands</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Vatican City State">Vatican City State</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Virgin Islands (British)">Virgin Islands (British)</option>
                        <option value="Virgin Islands (U.S.)">Virgin Islands (U.S.)</option>
                        <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
                        <option value="Western Sahara">Western Sahara</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Zaire">Zaire</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                        </select>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">SWIFT / BIC Code</label>
                            <input
                                type="text"
                                name="swiftcode"
                                x-model="swiftcode"
                                maxlength="11"
                                placeholder="BOFAUS3N"
                                class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm font-mono uppercase">
                        </div>
                    </div>

                    {{-- Optional SWIFT/IBAN Toggle --}}
                    <button
                        type="button"
                        @click="showOptional = !showOptional"
                        class="text-sm text-primary-500 hover:text-primary-600 font-medium flex items-center space-x-2 transition-colors">
                        <span x-text="showOptional ? 'Hide' : 'Show'">Show</span>
                        <span>additional bank codes (IBAN)</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': showOptional }"></i>
                    </button>

                    {{-- Optional Fields --}}
                    <div x-show="showOptional" x-collapse class="space-y-4 pt-2">
                        <div>
                            <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">IBAN Number <span class="text-[#7A7A7A] font-normal">(Optional)</span></label>
                            <input
                                type="text"
                                name="iban"
                                x-model="iban"
                                maxlength="34"
                                placeholder="GB29NWBK60161331926819"
                                class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm font-mono">
                            <p class="text-xs text-[#7A7A7A] mt-1">Required for transfers to European banks</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Security & Details --}}
            <div class="bg-amber-500/5 border border-amber-500/15 rounded-xl p-4">
                <div class="flex items-start space-x-3 mb-3">
                    <i data-lucide="shield-check" class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-[#0D1B23] mb-1">Secure Transaction</h4>
                        <p class="text-xs text-[#7A7A7A]">Enter your PIN to authorize this transfer</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="relative">
                        <input
                            type="password"
                            name="pin"
                            x-model="pin"
                            pattern="[0-9]+"
                            minlength="4"
                            maxlength="10"
                            required
                            placeholder="&#x2022; &#x2022; &#x2022; &#x2022; &#x2022; &#x2022;"
                            class="w-full px-4 py-3 rounded-lg bg-white border-2 border-amber-400 focus:border-amber-500 focus:ring-0 text-[#0D1B23] transition-all font-mono text-center text-lg tracking-widest">
                    </div>

                    <div>
                        <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Transfer Purpose <span class="text-[#7A7A7A] font-normal">(Optional)</span></label>
                        <textarea
                            name="Description"
                            x-model="description"
                            rows="2"
                            placeholder="Invoice payment, personal transfer, etc."
                            class="w-full px-4 py-3 rounded-lg bg-white border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all text-sm resize-none"></textarea>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <a href="{{ route('dashboard') }}" class="flex-1 px-5 py-3.5 text-center rounded-lg border border-[#E0E5E5] hover:bg-[#F9F9F9] transition-colors text-sm font-medium text-[#7A7A7A]">
                    Cancel
                </a>
                <button
                    type="submit"
                    class="flex-1 px-5 py-3.5 rounded-lg font-semibold text-white text-sm bg-primary-500 hover:bg-primary-600 transition-colors flex items-center justify-center space-x-2">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>Review Transfer</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
            
            <p class="text-xs text-center text-[#7A7A7A]">
                <i data-lucide="shield" class="w-3 h-3 inline mr-1"></i>
                Your transaction is secured with bank-grade encryption
            </p>
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

        <div @click="showPreview = false" class="absolute inset-0 bg-black/70"></div>

        <div
            x-show="showPreview"
            x-transition:enter="transition ease-out duration-200 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-2"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150 transform"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white border border-[#E0E5E5] rounded-xl p-6 w-full max-w-md z-10 max-h-[90vh] overflow-y-auto shadow-strong">

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
                    <span class="text-[#7A7A7A] text-sm">Country</span>
                    <span class="text-[#0D1B23] text-sm" x-text="country"></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Account Type</span>
                    <span class="text-[#0D1B23] text-sm" x-text="accounttype"></span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-[#E0E5E5]">
                    <span class="text-[#7A7A7A] text-sm">Processing</span>
                    <span class="text-[#3e4e83] text-sm font-medium">1-3 business days</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-[#7A7A7A] text-sm">Remaining Balance</span>
                    <span class="text-[#0D1B23] font-semibold" x-text="currency + remainingBalance.toFixed(2)"></span>
                </div>
            </div>

            <div class="bg-[#3e4e83]/10 border border-[#3e4e83]/20 rounded-lg p-3 mb-5">
                <div class="flex items-start space-x-2.5">
                    <i data-lucide="alert-triangle" class="w-4 h-4 text-[#3e4e83] flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#4A4A4A]">This international wire transfer is irreversible and may take 1-3 business days to complete. Please verify all details before confirming.</p>
                </div>
            </div>

            <div class="flex space-x-3">
                <button
                    @click="showPreview = false"
                    class="flex-1 px-4 py-3 border border-[#E0E5E5] rounded-lg hover:bg-[#F9F9F9] transition-colors text-sm font-medium text-[#7A7A7A]">
                    Cancel
                </button>
                <button
                    @click="submitTransfer()"
                    class="flex-1 px-4 py-3 rounded-lg font-semibold text-white text-sm bg-primary-500 hover:bg-primary-600 transition-colors flex items-center justify-center space-x-2">
                    <i data-lucide="lock" class="w-4 h-4"></i>
                    <span>Confirm Transfer</span>
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

        <div class="absolute inset-0 bg-black/70"></div>

        <div class="relative bg-white border border-[#E0E5E5] rounded-xl p-8 w-full max-w-sm z-10 text-center shadow-strong">
            <div class="w-14 h-14 mx-auto mb-5 rounded-xl bg-primary-500/10 border border-primary-500/15 flex items-center justify-center">
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
