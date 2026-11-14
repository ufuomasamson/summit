@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">

                {{-- ============================================================ --}}
                {{-- SUPPORT / LINKS BAR --}}
                {{-- ============================================================ --}}
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #0F9D58 0%, #0B8046 100%);">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-3 text-center text-md-left mb-3 mb-md-0">
                                        <h4 class="text-white mb-0 font-weight-bold">
                                            <i class="fas fa-life-ring mr-2"></i>Need Help?
                                        </h4>
                                        <p class="text-white-50 mb-0 small">Get support & updates</p>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="row text-center text-md-left">
                                            <div class="col-md-4 mb-2 mb-md-0">
                                                <a href="https://api.whatsapp.com/send?phone=18289208882&text=Hello" target="_blank"
                                                   class="btn btn-light btn-block shadow-sm hover-lift">
                                                    <i class="fab fa-whatsapp text-success fa-lg mr-2"></i>
                                                    <span class="font-weight-bold">Join WhatsApp</span>
                                                </a>
                                            </div>
                                            <div class="col-md-4 mb-2 mb-md-0">
                                                <a href="https://t.me/mydigitalmarkethub" target="_blank"
                                                   class="btn btn-light btn-block shadow-sm hover-lift">
                                                    <i class="fab fa-telegram text-info fa-lg mr-2"></i>
                                                    <span class="font-weight-bold">Join Telegram</span>
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="https://mydigitalmarkethub.com/" target="_blank"
                                                   class="btn btn-light btn-block shadow-sm hover-lift">
                                                    <i class="fas fa-globe text-primary fa-lg mr-2"></i>
                                                    <span class="font-weight-bold">Visit Website</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- PAGE HEADER --}}
                {{-- ============================================================ --}}
                <div class="mb-4">
                    <div class="card shadow-sm border-0" style="background: linear-gradient(135deg, #1a3a2a 0%, #2d8659 100%); color: #fff;">
                        <div class="card-body text-center py-5">
                            <h1 class="mb-2" style="font-weight: 700; font-size: 2.2rem; letter-spacing: .5px;">
                                <i class="fas fa-university mr-2"></i> FinCore — Platform Guide
                            </h1>
                            <p class="mb-0" style="font-size: 1.1rem; opacity: .9;">
                                A complete, easy-to-follow guide to every feature in the FinCore online banking platform.<br>
                                Choose a tab below to explore the <strong>Customer</strong> or <strong>Administrator</strong> experience.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ============================================================ --}}
                {{-- TABS --}}
                {{-- ============================================================ --}}
                <ul class="nav nav-pills nav-justified mb-4" id="guideTabs" role="tablist" style="font-size:1.05rem;">
                    <li class="nav-item">
                        <a class="nav-link active font-weight-bold" id="customer-tab" data-toggle="tab" href="#customerGuide" role="tab">
                            <i class="fas fa-user mr-1"></i> User Dashboard Guide
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link font-weight-bold" id="admin-tab" data-toggle="tab" href="#adminGuide" role="tab">
                            <i class="fas fa-user-shield mr-1"></i> Admin Dashboard Guide
                        </a>
                    </li>
                </ul>

                <div class="tab-content" id="guideTabContent">

                    {{-- ======================================================== --}}
                    {{--                 TAB 1 — CUSTOMER GUIDE                   --}}
                    {{-- ======================================================== --}}
                    <div class="tab-pane fade show active" id="customerGuide" role="tabpanel">

                        {{-- 1. Getting Started --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-success text-white">
                                <h4 class="mb-0"><i class="fas fa-rocket mr-2"></i>Getting Started</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-user-plus mr-1 text-success"></i> Creating Your Account</h5>
                                        <ol>
                                            <li>Visit the Summit Bank registration page and fill in your personal details (name, email, phone number, password).</li>
                                            <li>You will receive a <strong>6-digit verification code</strong> at your email address — enter it on the verification screen to activate your account.</li>
                                            <li>Once verified, log in with your email and password.</li>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-id-card mr-1 text-success"></i> KYC Verification (Know Your Customer)</h5>
                                        <p>Before you can use most banking features, you must complete KYC verification:</p>
                                        <ol>
                                            <li>Go to <strong>Profile → Verify Identity</strong>.</li>
                                            <li>Upload a clear photo of the <em>front</em> and <em>back</em> of a valid government-issued ID (passport, driver's licence, or national ID).</li>
                                            <li>Upload a recent photo of yourself (selfie).</li>
                                            <li>Fill in your personal details — date of birth, address, city, state, country, ZIP code, employer, and next-of-kin information.</li>
                                            <li>Submit the form. Your application will be reviewed by an administrator. You'll receive a notification once approved or if additional information is needed.</li>
                                        </ol>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-key mr-1 text-success"></i> Setting Up Your Transaction PIN</h5>
                                        <p>A 4-digit PIN protects every financial action on your account:</p>
                                        <ol>
                                            <li>After first login, you will be prompted to create a PIN.</li>
                                            <li>Choose a PIN you can remember but others cannot guess.</li>
                                            <li>You'll need this PIN to confirm transfers, card actions, and other transactions.</li>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-share-alt mr-1 text-success"></i> Referral Programme</h5>
                                        <p>Earn rewards by inviting others:</p>
                                        <ol>
                                            <li>Find your unique referral link on the Dashboard or Profile page.</li>
                                            <li>Share the link with friends and family.</li>
                                            <li>When someone registers through your link <em>and</em> makes their first deposit, you earn a commission — up to 5 levels deep!</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Dashboard Overview --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0"><i class="fas fa-tachometer-alt mr-2"></i>Your Dashboard</h4>
                            </div>
                            <div class="card-body">
                                <p>After logging in, your <strong>Dashboard</strong> gives you a snapshot of your entire account at a glance:</p>
                                <div class="row text-center">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded" style="background:#f0f7f3;">
                                            <i class="fas fa-wallet fa-2x text-success mb-2"></i>
                                            <h6 class="font-weight-bold">Account Balance</h6>
                                            <small>Your current available funds</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded" style="background:#f0f3f7;">
                                            <i class="fas fa-arrow-down fa-2x text-primary mb-2"></i>
                                            <h6 class="font-weight-bold">Total Deposited</h6>
                                            <small>Sum of all deposits</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded" style="background:#fdf5e6;">
                                            <i class="fas fa-arrow-up fa-2x text-warning mb-2"></i>
                                            <h6 class="font-weight-bold">Total Withdrawn</h6>
                                            <small>Sum of all transfers out</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded" style="background:#f7f0f0;">
                                            <i class="fas fa-history fa-2x text-danger mb-2"></i>
                                            <h6 class="font-weight-bold">Recent Activity</h6>
                                            <small>Latest transactions</small>
                                        </div>
                                    </div>
                                </div>
                                <p class="mb-0">You will also see quick-action buttons for <strong>Deposit</strong>, <strong>Transfer</strong>, and <strong>Apply for a Loan</strong>, along with a list of your active virtual cards and investment plans.</p>
                            </div>
                        </div>

                        {{-- 3. Making Deposits --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#2d8659; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-piggy-bank mr-2"></i>Making Deposits</h4>
                            </div>
                            <div class="card-body">
                                <p>Funding your account is simple. FinCore supports multiple payment methods:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Option A — Card Payment (Stripe)</h5>
                                        <ol>
                                            <li>Navigate to <strong>Deposits</strong> from the sidebar.</li>
                                            <li>Select <strong>Pay with Card</strong> and enter the amount you wish to deposit.</li>
                                            <li>Enter your debit or credit card details on the secure Stripe payment form.</li>
                                            <li>Once payment is confirmed, your account is credited <strong>instantly</strong>.</li>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Option B — Manual / Bank Transfer Deposit</h5>
                                        <ol>
                                            <li>Navigate to <strong>Deposits</strong> and choose <strong>Manual Deposit</strong>.</li>
                                            <li>Select the payment method (e.g., bank transfer, crypto) and enter the amount.</li>
                                            <li>Follow the on-screen instructions to make the payment.</li>
                                            <li>Upload a <strong>proof of payment</strong> (screenshot or receipt image).</li>
                                            <li>Submit the form. An administrator will review and approve your deposit, usually within a few hours.</li>
                                        </ol>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Tip:</strong> You may receive a deposit bonus (a percentage on top of your deposit) if the administrator has enabled the bonus programme.
                                </div>
                            </div>
                        </div>

                        {{-- 4. Money Transfers --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#1a3a2a; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-exchange-alt mr-2"></i>Money Transfers</h4>
                            </div>
                            <div class="card-body">
                                <p>Summit Bank offers <strong>10 different ways</strong> to send money. Choose the one that matches your recipient:</p>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width:25%;">Transfer Type</th>
                                                <th style="width:35%;">Best For</th>
                                                <th style="width:40%;">What You Need</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><i class="fas fa-building text-success mr-1"></i> <strong>Internal Transfer</strong></td>
                                                <td>Sending to another FinCore customer</td>
                                                <td>Recipient's FinCore account number (verified instantly)</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-home text-primary mr-1"></i> <strong>Local / Domestic</strong></td>
                                                <td>Sending to a bank within your country</td>
                                                <td>Recipient's bank name, account number, account name</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-globe text-info mr-1"></i> <strong>International / Wire</strong></td>
                                                <td>Sending to a bank in another country</td>
                                                <td>SWIFT/BIC code, IBAN, routing number, bank address</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fab fa-paypal text-primary mr-1"></i> <strong>PayPal</strong></td>
                                                <td>PayPal users</td>
                                                <td>Recipient's PayPal email address</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fab fa-bitcoin text-warning mr-1"></i> <strong>Cryptocurrency</strong></td>
                                                <td>Bitcoin, Ethereum, or USDT wallets</td>
                                                <td>Recipient's wallet address & chosen coin</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-money-check-alt mr-1" style="color:#8b3dff;"></i> <strong>Skrill</strong></td>
                                                <td>Skrill account holders</td>
                                                <td>Recipient's Skrill email address</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fab fa-google text-danger mr-1"></i> <strong>Google Pay</strong></td>
                                                <td>Google Pay users</td>
                                                <td>Recipient's Google Pay email</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-money-bill-wave text-success mr-1"></i> <strong>Payoneer</strong></td>
                                                <td>Payoneer account holders</td>
                                                <td>Recipient's Payoneer email address</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-paper-plane text-warning mr-1"></i> <strong>Western Union</strong></td>
                                                <td>Cash pickup anywhere in the world</td>
                                                <td>Recipient's full name and country</td>
                                            </tr>
                                            <tr>
                                                <td><i class="fas fa-random text-info mr-1"></i> <strong>Wise (TransferWise)</strong></td>
                                                <td>Wise account holders</td>
                                                <td>Recipient's Wise email address</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <h5 class="font-weight-bold mt-4"><i class="fas fa-list-ol mr-1 text-success"></i> How Every Transfer Works (Step by Step)</h5>
                                <ol>
                                    <li><strong>Choose the transfer type</strong> from the sidebar (e.g., Local Transfer, International Transfer, Internal Transfer, etc.).</li>
                                    <li><strong>Fill in the form</strong> with the recipient's details and the amount you wish to send.</li>
                                    <li><strong>Preview the transfer</strong> — review all details carefully on the confirmation screen.</li>
                                    <li><strong>Enter verification codes</strong> — if your account requires them, you will be asked for up to three security codes and/or a one-time password (OTP) sent to your email.</li>
                                    <li><strong>Confirm</strong> — once submitted, the transfer is sent for processing. Internal transfers are instant; other types may take 1–3 business days.</li>
                                </ol>
                                <div class="alert alert-warning mt-3 mb-0">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    <strong>Important:</strong> Always double-check recipient details before confirming. Transfers to incorrect accounts or wallet addresses cannot be reversed.
                                </div>
                            </div>
                        </div>

                        {{-- 5. Virtual Cards --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#e0a84e; color:#1a3a2a;">
                                <h4 class="mb-0"><i class="fas fa-credit-card mr-2"></i>Virtual Cards</h4>
                            </div>
                            <div class="card-body">
                                <p>Virtual debit cards let you shop online, pay subscriptions, and make purchases without exposing your main balance.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Applying for a Card</h5>
                                        <ol>
                                            <li>Go to <strong>Virtual Cards → Apply for Card</strong>.</li>
                                            <li>Choose your card brand — <em>Visa, Mastercard, American Express,</em> or <em>Discover</em>.</li>
                                            <li>Select a card level — <em>Standard, Gold, Platinum,</em> or <em>Black</em>. Higher tiers have higher spending limits but a higher issuance fee.</li>
                                            <li>The issuance fee is deducted from your account balance.</li>
                                            <li>Wait for an administrator to approve your application. You'll be notified once your card is ready.</li>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Managing Your Card</h5>
                                        <ul>
                                            <li><i class="fas fa-plus-circle text-success mr-1"></i> <strong>Fund Card</strong> — move money from your main balance to your card.</li>
                                            <li><i class="fas fa-minus-circle text-danger mr-1"></i> <strong>Withdraw from Card</strong> — move money back from your card to your main balance.</li>
                                            <li><i class="fas fa-snowflake text-info mr-1"></i> <strong>Freeze / Unfreeze</strong> — temporarily disable your card if you suspect unauthorised use.</li>
                                            <li><i class="fas fa-eye text-warning mr-1"></i> <strong>Reveal Card Details</strong> — view your full card number, CVV, and expiry date for online purchases.</li>
                                            <li><i class="fas fa-receipt text-primary mr-1"></i> <strong>Transactions</strong> — view a history of all purchases and payments made with the card.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 6. Investments --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#2d5f8a; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Investments</h4>
                            </div>
                            <div class="card-body">
                                <p>Grow your money by investing in one of the available plans.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">How to Invest</h5>
                                        <ol>
                                            <li>Navigate to <strong>Investments</strong> from the sidebar.</li>
                                            <li>Browse the available investment plans. Each plan shows:
                                                <ul>
                                                    <li><strong>Minimum &amp; Maximum</strong> investment amounts</li>
                                                    <li><strong>ROI (Return on Investment)</strong> — either a fixed amount or a percentage</li>
                                                    <li><strong>Duration</strong> — how long your money is locked (days, weeks, or months)</li>
                                                    <li><strong>Capital Return</strong> — whether your original investment is returned at the end</li>
                                                </ul>
                                            </li>
                                            <li>Choose a plan and enter the amount you wish to invest (must be within the plan's min/max range).</li>
                                            <li>Confirm. The amount is deducted from your account balance immediately.</li>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Tracking Your Investments</h5>
                                        <ul>
                                            <li>View all your active investments under <strong>My Investments</strong>.</li>
                                            <li>See your <strong>total invested</strong> and <strong>total returns</strong> at the top of the page.</li>
                                            <li>When an investment matures, returns are automatically credited to your account balance.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 7. Loans --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#5a3d8a; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-hand-holding-usd mr-2"></i>Loans</h4>
                            </div>
                            <div class="card-body">
                                <h5 class="font-weight-bold">Applying for a Loan</h5>
                                <ol>
                                    <li>Go to <strong>Loans</strong> from the sidebar menu.</li>
                                    <li>Click <strong>Apply for a Loan</strong>.</li>
                                    <li>Fill in the application:
                                        <ul>
                                            <li><strong>Loan Amount</strong> — how much you need</li>
                                            <li><strong>Income Range</strong> — your current income bracket</li>
                                            <li><strong>Purpose</strong> — what the loan will be used for</li>
                                            <li><strong>Facility Type</strong> — type of loan facility</li>
                                            <li><strong>Duration</strong> — repayment period in months</li>
                                        </ul>
                                    </li>
                                    <li>Submit your application. An administrator will review it.</li>
                                    <li>If <strong>approved</strong>, the loan amount is credited directly to your FinCore account balance.</li>
                                    <li>If <strong>rejected</strong>, you'll receive an email notification explaining why.</li>
                                </ol>
                                <p>You can view all your loan applications and their statuses under <strong>My Loans</strong>. You can also export your loan history as a CSV or Excel file.</p>
                            </div>
                        </div>

                        {{-- 8. Grant Applications --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#2d8659; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-donate mr-2"></i>Grant Applications</h4>
                            </div>
                            <div class="card-body">
                                <p>FinCore allows you to apply for financial grants. There are two types:</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-user text-success mr-1"></i> Individual Grant</h5>
                                        <ol>
                                            <li>Navigate to <strong>Grant Applications → Apply as Individual</strong>.</li>
                                            <li>Select a funding type: <em>Programme, Research, Capacity Building,</em> or <em>Other</em>.</li>
                                            <li>Enter the requested amount and provide supporting details.</li>
                                            <li>Submit your application.</li>
                                        </ol>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-building text-primary mr-1"></i> Company / Organisation Grant</h5>
                                        <ol>
                                            <li>Navigate to <strong>Grant Applications → Apply as Company</strong>.</li>
                                            <li>Provide organisation details: legal name, Tax ID / EIN, organisation type (non-profit, for-profit, government, educational), incorporation date.</li>
                                            <li>Fill in the mailing address, mission statement, service areas, and organisational history.</li>
                                            <li>Enter the requested amount and submit.</li>
                                        </ol>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="font-weight-bold">Application Lifecycle</h5>
                                <div class="d-flex flex-wrap align-items-center justify-content-center text-center my-3">
                                    <span class="badge badge-secondary p-2 m-1" style="font-size:.95rem;">Submitted</span>
                                    <i class="fas fa-arrow-right mx-1 text-muted"></i>
                                    <span class="badge badge-info p-2 m-1" style="font-size:.95rem;">Processing</span>
                                    <i class="fas fa-arrow-right mx-1 text-muted"></i>
                                    <span class="badge badge-success p-2 m-1" style="font-size:.95rem;">Approved</span>
                                    <i class="fas fa-arrow-right mx-1 text-muted"></i>
                                    <span class="badge badge-primary p-2 m-1" style="font-size:.95rem;">Disbursed (Funds Credited)</span>
                                </div>
                                <p class="mb-0">You can edit pending applications, view results, and track the status of all your grant requests from the <strong>My Applications</strong> page.</p>
                            </div>
                        </div>

                        {{-- 9. Notifications & Security --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h4 class="mb-0"><i class="fas fa-bell mr-2"></i>Notifications &amp; Security</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-bell text-warning mr-1"></i> Staying Informed</h5>
                                        <ul>
                                            <li>A <strong>bell icon</strong> in the top navigation shows your unread notifications in real time.</li>
                                            <li>Click the bell to see a quick preview, or visit the <strong>Notifications</strong> page for the full list.</li>
                                            <li>Notifications cover: transfers, deposits, card activity, password changes, and login alerts.</li>
                                            <li>You can mark individual notifications as read or clear them all at once.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-shield-alt text-success mr-1"></i> Account Security</h5>
                                        <ul>
                                            <li><strong>Transaction PIN</strong> — required for every financial action.</li>
                                            <li><strong>Verification Codes &amp; OTP</strong> — extra security layers on transfers (sent to your email).</li>
                                            <li><strong>Auto-Logout</strong> — your session expires after <strong>10 minutes</strong> of inactivity, with a 1-minute warning countdown.</li>
                                            <li><strong>Profile Settings</strong> — change your password, update your email, upload a profile photo, and manage your transaction PIN.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- / Customer Guide Tab --}}

                    {{-- ======================================================== --}}
                    {{--                   TAB 2 — ADMIN GUIDE                    --}}
                    {{-- ======================================================== --}}
                    <div class="tab-pane fade" id="adminGuide" role="tabpanel">

                        {{-- 1. Admin Dashboard --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h4 class="mb-0"><i class="fas fa-tachometer-alt mr-2"></i>Admin Dashboard</h4>
                            </div>
                            <div class="card-body">
                                <p>The admin dashboard gives you a bird's-eye view of the entire platform:</p>
                                <div class="row text-center">
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded bg-light">
                                            <i class="fas fa-dollar-sign fa-2x text-success mb-2"></i>
                                            <h6 class="font-weight-bold">Total Deposits</h6>
                                            <small>Overall &amp; pending</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded bg-light">
                                            <i class="fas fa-arrow-circle-up fa-2x text-primary mb-2"></i>
                                            <h6 class="font-weight-bold">Total Withdrawals</h6>
                                            <small>Overall &amp; pending</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded bg-light">
                                            <i class="fas fa-users fa-2x text-info mb-2"></i>
                                            <h6 class="font-weight-bold">User Counts</h6>
                                            <small>Active, blocked, unverified</small>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-6 mb-3">
                                        <div class="p-3 rounded bg-light">
                                            <i class="fas fa-chart-bar fa-2x text-warning mb-2"></i>
                                            <h6 class="font-weight-bold">Financial Charts</h6>
                                            <small>Visual summaries</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 2. Managing Users --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h4 class="mb-0"><i class="fas fa-users-cog mr-2"></i>Managing Users</h4>
                            </div>
                            <div class="card-body">
                                <p>The <strong>Manage Users</strong> section is your central control panel for all customer accounts.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Everyday Actions</h5>
                                        <ul>
                                            <li><i class="fas fa-search text-primary mr-1"></i> <strong>Search &amp; View</strong> — find any user by name, email, or account number and open their full profile.</li>
                                            <li><i class="fas fa-edit text-info mr-1"></i> <strong>Edit Profile &amp; Balances</strong> — update personal info and adjust account balances directly.</li>
                                            <li><i class="fas fa-plus-circle text-success mr-1"></i> <strong>Top Up Accounts</strong> — add funds to (or deduct from) a user's balance.</li>
                                            <li><i class="fas fa-key text-warning mr-1"></i> <strong>Reset Passwords</strong> — set a new password for any user who is locked out.</li>
                                            <li><i class="fas fa-envelope text-danger mr-1"></i> <strong>Send Emails</strong> — email an individual user or send a bulk email to all users at once.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Advanced Actions</h5>
                                        <ul>
                                            <li><i class="fas fa-ban text-danger mr-1"></i> <strong>Block / Unblock</strong> — disable a user's access with a reason and optional auto-unblock date.</li>
                                            <li><i class="fas fa-sign-in-alt text-success mr-1"></i> <strong>Switch to User</strong> — log into a user's account to see exactly what they see (for support purposes).</li>
                                            <li><i class="fas fa-code text-info mr-1"></i> <strong>Transfer Codes</strong> — enable or disable extra security codes (Code 1, Code 2, Code 3, OTP) per user.</li>
                                            <li><i class="fas fa-moon text-secondary mr-1"></i> <strong>Mark Dormant</strong> — flag inactive accounts as dormant or reactivate them.</li>
                                            <li><i class="fas fa-file-import text-primary mr-1"></i> <strong>Import Users</strong> — bulk-import users from an Excel spreadsheet.</li>
                                            <li><i class="fas fa-list text-warning mr-1"></i> <strong>Bulk Transactions</strong> — generate multiple transaction records for a user at once.</li>
                                            <li><i class="fas fa-user-plus text-success mr-1"></i> <strong>Create New User</strong> — manually create a user account (auto-generates account number, codes, and PIN).</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Deposits --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#2d8659; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-download mr-2"></i>Deposit Management</h4>
                            </div>
                            <div class="card-body">
                                <p>Review and process all customer deposits from the <strong>Users Deposits</strong> page.</p>
                                <ul>
                                    <li><i class="fas fa-list text-primary mr-1"></i> <strong>View All Deposits</strong> — see every deposit across the platform with amount, method, status, and date.</li>
                                    <li><i class="fas fa-image text-info mr-1"></i> <strong>View Proof</strong> — for manual deposits, click to view the payment proof image uploaded by the customer.</li>
                                    <li><i class="fas fa-check-circle text-success mr-1"></i> <strong>Approve / Process</strong> — approve pending deposits to credit the customer's account.</li>
                                    <li><i class="fas fa-edit text-warning mr-1"></i> <strong>Edit Amount</strong> — adjust the deposit amount before or after processing if needed.</li>
                                    <li><i class="fas fa-trash text-danger mr-1"></i> <strong>Delete</strong> — remove a deposit record from the system.</li>
                                </ul>
                            </div>
                        </div>

                        {{-- 4. Transfers / Withdrawals --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#1a3a2a; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-exchange-alt mr-2"></i>Transfer / Withdrawal Management</h4>
                            </div>
                            <div class="card-body">
                                <p>Manage all outgoing transactions from the <strong>Transfer Transactions</strong> page.</p>
                                <ul>
                                    <li><i class="fas fa-list text-primary mr-1"></i> <strong>View All Transfers</strong> — every transfer/withdrawal across all users.</li>
                                    <li><i class="fas fa-check text-success mr-1"></i> <strong>Process (Approve / Reject)</strong> — approve legitimate transfers or reject suspicious ones.</li>
                                    <li><i class="fas fa-edit text-warning mr-1"></i> <strong>Update Details</strong> — modify transfer details if corrections are needed.</li>
                                    <li><i class="fas fa-trash text-danger mr-1"></i> <strong>Delete</strong> — remove a transfer record.</li>
                                </ul>
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Transfers have three possible statuses: <strong>Pending</strong> (awaiting review), <strong>Processed</strong> (completed), and <strong>Rejected</strong> (declined).
                                </div>
                            </div>
                        </div>

                        {{-- 5. Virtual Card Management --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#e0a84e; color:#1a3a2a;">
                                <h4 class="mb-0"><i class="fas fa-credit-card mr-2"></i>Virtual Card Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Applications &amp; Approvals</h5>
                                        <ul>
                                            <li><strong>Pending Applications</strong> — review new card requests from customers.</li>
                                            <li><strong>Approve</strong> — the system auto-generates a unique card number, CVV, and expiry date upon approval.</li>
                                            <li><strong>Reject</strong> — decline an application if criteria are not met.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Card Operations</h5>
                                        <ul>
                                            <li><strong>All Cards</strong> — view and filter by status (Active, Blocked, Pending), level, or search by customer name.</li>
                                            <li><strong>Block / Unblock</strong> — disable or re-enable an active card.</li>
                                            <li><strong>Top Up / Deduct</strong> — add or remove funds from a specific card.</li>
                                            <li><strong>Delete</strong> — permanently remove a card from the system.</li>
                                        </ul>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="font-weight-bold">Card Settings</h5>
                                <ul class="mb-0">
                                    <li>Enable or disable the entire virtual card system.</li>
                                    <li>Set issuance fees for each card level (Standard, Gold, Platinum, Black).</li>
                                </ul>
                            </div>
                        </div>

                        {{-- 6. Grant Management --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#2d8659; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-donate mr-2"></i>Grant Application Management</h4>
                            </div>
                            <div class="card-body">
                                <p>The grant management dashboard provides a full overview of all applications.</p>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Reviewing Applications</h5>
                                        <ul>
                                            <li>View the <strong>statistics dashboard</strong> — total, processing, approved, rejected, and disbursed applications with associated amounts.</li>
                                            <li><strong>Filter</strong> by status to focus on pending or processed applications.</li>
                                            <li>Click into any application to see full details (applicant info, requested amount, supporting documents).</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Taking Action</h5>
                                        <ul>
                                            <li><i class="fas fa-check text-success mr-1"></i> <strong>Approve</strong> — set a custom approved amount (may differ from requested amount).</li>
                                            <li><i class="fas fa-times text-danger mr-1"></i> <strong>Reject</strong> — include a reason that the applicant can view.</li>
                                            <li><i class="fas fa-money-bill text-primary mr-1"></i> <strong>Disburse</strong> — credit the approved amount directly to the applicant's FinCore account.</li>
                                            <li><i class="fas fa-sticky-note text-warning mr-1"></i> <strong>Admin Notes</strong> — add internal notes visible only to other administrators.</li>
                                            <li><i class="fas fa-trash text-danger mr-1"></i> <strong>Delete</strong> — remove an application from the system.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 7. Loan Management --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#5a3d8a; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-hand-holding-usd mr-2"></i>Loan Management</h4>
                            </div>
                            <div class="card-body">
                                <ul>
                                    <li><strong>Statistics Dashboard</strong> — view total loan applications, pending, approved, rejected, and total amounts.</li>
                                    <li><strong>Filter &amp; Search</strong> — find applications by status or customer name.</li>
                                    <li><strong>View Details</strong> — see the applicant's profile, requested amount, income range, purpose, and repayment duration.</li>
                                    <li><i class="fas fa-check-circle text-success mr-1"></i> <strong>Approve</strong> — the loan amount is automatically credited to the customer's account balance.</li>
                                    <li><i class="fas fa-times-circle text-danger mr-1"></i> <strong>Reject</strong> — the customer receives an email notification explaining the decision.</li>
                                </ul>
                            </div>
                        </div>

                        {{-- 8. Investment Management --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#2d5f8a; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-chart-line mr-2"></i>Investment Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Managing Plans</h5>
                                        <ul>
                                            <li><strong>Create Plans</strong> — set a name, minimum/maximum investment, ROI type (percentage or fixed), ROI value, duration, and whether to return the original capital.</li>
                                            <li><strong>Edit / Delete Plans</strong> — update terms or remove plans that are no longer available.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold">Managing User Investments</h5>
                                        <ul>
                                            <li><strong>View All Investments</strong> — see every customer's active and completed investments.</li>
                                            <li><strong>Add Manually</strong> — create an investment on behalf of a customer.</li>
                                            <li><strong>Process Returns</strong> — manually trigger returns for a mature investment.</li>
                                            <li><strong>Cancel</strong> — cancel an active investment and return funds if appropriate.</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="alert alert-info mt-3 mb-0">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Investment returns can also be processed <strong>automatically</strong> using the server's cron job scheduler. See your server documentation for setup instructions.
                                </div>
                            </div>
                        </div>

                        {{-- 9. KYC Verification --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-info text-white">
                                <h4 class="mb-0"><i class="fas fa-id-badge mr-2"></i>KYC Verification</h4>
                            </div>
                            <div class="card-body">
                                <p>All new customers must complete KYC (Know Your Customer) verification before accessing banking features.</p>
                                <ul>
                                    <li><strong>New User Applications</strong> — view the queue of pending KYC submissions.</li>
                                    <li><strong>Review Documents</strong> — open each application to see the uploaded ID photos (front &amp; back), selfie, and personal information.</li>
                                    <li><i class="fas fa-check text-success mr-1"></i> <strong>Accept</strong> — marks the customer as verified, and they receive an email confirmation. The customer can now use all platform features.</li>
                                    <li><i class="fas fa-times text-danger mr-1"></i> <strong>Reject</strong> — deletes the uploaded documents and notifies the customer, allowing them to resubmit with corrected information.</li>
                                </ul>
                            </div>
                        </div>

                        {{-- 10. Settings & System --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h4 class="mb-0"><i class="fas fa-cogs mr-2"></i>Settings &amp; System (Super Admin)</h4>
                            </div>
                            <div class="card-body">
                                <p>These settings are available only to <strong>Super Administrators</strong>.</p>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 rounded bg-light h-100">
                                            <h6 class="font-weight-bold"><i class="fas fa-sliders-h text-primary mr-1"></i> App Settings</h6>
                                            <ul class="mb-0 small">
                                                <li>Site name &amp; address</li>
                                                <li>Default currency</li>
                                                <li>Email configuration</li>
                                                <li>Transfer code requirements</li>
                                                <li>General preferences</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 rounded bg-light h-100">
                                            <h6 class="font-weight-bold"><i class="fas fa-credit-card text-success mr-1"></i> Payment Settings</h6>
                                            <ul class="mb-0 small">
                                                <li>Add / edit / remove payment methods</li>
                                                <li>Stripe API keys</li>
                                                <li>CoinPayments configuration</li>
                                                <li>Transfer fees &amp; limits</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 rounded bg-light h-100">
                                            <h6 class="font-weight-bold"><i class="fas fa-tools text-warning mr-1"></i> Other Tools</h6>
                                            <ul class="mb-0 small">
                                                <li>SMS provider settings &amp; testing</li>
                                                <li>IP whitelist / blacklist management</li>
                                                <li>System cache clearing</li>
                                                <li>Admin account management</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 11. CRM & Content --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header" style="background:#3d6b5e; color:#fff;">
                                <h4 class="mb-0"><i class="fas fa-briefcase mr-2"></i>CRM &amp; Content Management</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-tasks text-primary mr-1"></i> CRM Tools</h5>
                                        <ul>
                                            <li><strong>Calendar</strong> — schedule and view events.</li>
                                            <li><strong>Tasks</strong> — create, assign, and track tasks across team members. Mark tasks as done when completed.</li>
                                            <li><strong>Customers</strong> — track customer engagement and status.</li>
                                            <li><strong>Leads</strong> — new registrations appear as leads that can be assigned to agents for follow-up.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="font-weight-bold"><i class="fas fa-pen-fancy text-info mr-1"></i> Content Management</h5>
                                        <ul>
                                            <li><strong>FAQs</strong> — create, edit, and delete frequently asked questions displayed on the public site.</li>
                                            <li><strong>Testimonials</strong> — manage customer testimonials shown on the website.</li>
                                            <li><strong>Pages &amp; Content</strong> — edit website pages and content sections.</li>
                                            <li><strong>Image Management</strong> — upload and manage images used across the site.</li>
                                            <li><strong>Privacy Policy &amp; Terms</strong> — update legal documents.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 12. Admin Types --}}
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-dark text-white">
                                <h4 class="mb-0"><i class="fas fa-user-shield mr-2"></i>Admin Roles &amp; Access</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Role</th>
                                                <th>Access Level</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Super Admin</strong></td>
                                                <td>Full access to all features including system settings, payment configuration, admin management, and all operational tools.</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Admin</strong></td>
                                                <td>Access to user management, transaction processing (deposits, withdrawals, loans, grants), KYC review, and CRM tools. Cannot modify system settings.</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Retention Agent</strong></td>
                                                <td>Focused on customer engagement — can view user details, send emails, and manage leads/tasks.</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Conversion Agent</strong></td>
                                                <td>Focused on converting new sign-ups — can view leads, contact users, and track conversion progress.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>{{-- / Admin Guide Tab --}}

                </div>{{-- / tab-content --}}

                {{-- ============================================================ --}}
                {{-- FOOTER HELP CARD --}}
                {{-- ============================================================ --}}
                <div class="card shadow-sm border-0 mt-4 mb-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                    <div class="card-body text-center py-4">
                        <h4 class="font-weight-bold mb-2"><i class="fas fa-life-ring text-success mr-2"></i>Need Help?</h4>
                        <p class="mb-1"><strong>Customers:</strong> Please contact your account manager or visit the <strong>Customer Support</strong> page from your dashboard.</p>
                        <p class="mb-0"><strong>Administrators:</strong> For technical assistance or system issues, reach out to the FinCore support team.</p>
                    </div>
                </div>

            </div>{{-- / page-inner --}}
        </div>{{-- / content --}}
    </div>{{-- / main-panel --}}
@endsection
