@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ showProof: false }" class="space-y-6 pb-24">

    <x-danger-alert />
    <x-success-alert />
    <x-error-alert />

    <!-- Page Header -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-primary-600 flex items-center justify-center">
                <i data-lucide="credit-card" class="w-6 h-6 text-white"></i>
            </div>
            <div>
                <h1 class="text-2xl font-semibold text-[#0D1B23]">
                    @if($title == "Complete Payment")
                        Complete Your Payment
                    @else
                        Make Deposit
                    @endif
                </h1>
                <p class="text-[#7A7A7A] text-sm">Secure payment processing for your account</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        @if($title != "Complete Payment")
            @php
                if($payment_mode->name == "Bitcoin"){
                    $coin = 'BTC';
                }elseif ($payment_mode->name == "Litecoin") {
                    $coin = 'LTC';
                }else {
                    $coin = 'ETH';
                }
            @endphp

            <!-- Payment Amount Card -->
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-6 mb-6">
                <div class="text-center mb-6">
                    <p class="text-[#7A7A7A] text-sm mb-2">Payment Amount</p>
                    <h2 class="text-4xl font-semibold text-[#0D1B23] mb-4">
                        {{$settings->currency}}{{ number_format($amount) }}
                    </h2>
                    <div class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-[#DFE5E5] border border-[#C8D1D1]">
                        <i data-lucide="shield-check" class="w-4 h-4 text-primary-500"></i>
                        <span class="text-sm text-[#4A4A4A]">Secure Payment</span>
                    </div>
                </div>

                <!-- Payment Method Display -->
                <div class="bg-[#F9F9F9] rounded-xl p-6 border border-[#E0E5E5]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                                <i data-lucide="banknote" class="w-5 h-5 text-primary-500"></i>
                            </div>
                            <div>
                                <p class="text-sm text-[#7A7A7A]">Payment Method</p>
                                <h3 class="text-xl font-bold text-[#0D1B23]">{{ $payment_mode->name }}</h3>
                            </div>
                        </div>
                    </div>

                    @if ($payment_mode->methodtype != 'currency' and !empty($payment_mode->wallet_address))
                        <div class="flex justify-center py-4">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{urlencode($payment_mode->wallet_address)}}" alt="QR Code" class="max-w-xs rounded-xl border border-[#E0E5E5]">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-6 mb-6">
                @if($settings->deposit_option != "manual")
                    @if ($payment_mode->name == "Bitcoin" or $payment_mode->name == "Litecoin" or $payment_mode->name == "Ethereum")
                        <a href="{{ url('dashboard/cpay') }}/{{$amount}}/{{$coin}}/{{ Auth::user()->id }}/new" 
                           class="w-full px-6 py-4 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-semibold transition-colors flex items-center justify-center space-x-2 group">
                            <span>Pay Via Coinpayment</span>
                            <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @endif
                @endif

                @if ($payment_mode->methodtype != 'currency')
                    <!-- Crypto Wallet Address -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-2">
                            <i data-lucide="wallet" class="w-5 h-5 text-[#7A7A7A]"></i>
                            <h3 class="text-lg font-semibold text-[#0D1B23]">{{$payment_mode->name}} Address</h3>
                        </div>
                        
                        <div class="relative">
                            <input class="w-full px-4 py-4 pr-12 rounded-xl bg-[#F9F9F9] border-2 border-[#E0E5E5] text-[#0D1B23] font-mono text-sm transition-all outline-none" 
                                   readonly 
                                   type="text" 
                                   id="myInput" 
                                   value="{{$payment_mode->wallet_address}}">
                            <button onclick="myFunction()" 
                                    type="button"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 p-2 rounded-lg bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] transition-colors group">
                                <i data-lucide="copy" class="w-5 h-5 text-[#4A4A4A]"></i>
                            </button>
                        </div>

                        <div class="flex items-center space-x-2 text-sm text-[#7A7A7A]">
                            <i data-lucide="info" class="w-4 h-4"></i>
                            <span><strong class="text-[#4A4A4A]">Network Type:</strong> {{$payment_mode->network}}</span>
                        </div>
                    </div>
                @else
                    <!-- Bank Transfer or Payment Gateway -->
                    <div class="space-y-4">
                        @if ($payment_mode->defaultpay == 'yes')
                            @if ($payment_mode->name == "Paystack")
                                <?php $payamount = $amount * 100  ?>
                                <div id="paystack">
                                    <form method="POST" action="{{ route('pay.paystack') }}" accept-charset="UTF-8" class="form-horizontal" role="form">
                                        <input type="hidden" name="email" value="{{auth::user()->email}}">
                                        <input type="hidden" name="amount" value="{{$payamount}}">
                                        <input type="hidden" name="currency" value="{{$settings->s_currency}}">
                                        <input type="hidden" name="metadata" value="{{ json_encode($array = ['key_name' => 'value',]) }}"> 
                                        <input type="hidden" name="reference" value="{{ Paystack::genTranxRef() }}"> 
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        
                                        <button class="w-full px-6 py-4 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-semibold transition-colors flex items-center justify-center space-x-2" 
                                                type="submit">
                                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                                            <span>Pay with Paystack</span>
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if ($payment_mode->name == "Stripe")
                                <form id="payment-form" class="sr-payment-form space-y-4">
                                    @csrf
                                    <div class="sr-combo-inputs-row">
                                        <div class="sr-input sr-card-element p-4 rounded-xl bg-[#F9F9F9] border-2 border-[#E0E5E5]" id="card-element"></div>
                                    </div>

                                    <button id="stripesubmit"
                                            class="w-full px-6 py-4 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-semibold transition-colors flex items-center justify-center space-x-2">
                                        <div class="spinner d-none" id="spinner"></div>
                                        <span id="buttontext">Pay Now</span>
                                    </button>
                                </form>
                        
                                <div class="hidden row" id="stripesuccess">
                                    <div class="col-lg-12 text-center p-4 bg-primary-100 rounded-xl border border-primary-300">
                                        <i data-lucide="check-circle" class="w-8 h-8 text-primary-500 mx-auto mb-2"></i>
                                        <span class="text-primary-400">Payment Completed, redirecting.....</span>
                                    </div>
                                </div>
                                
                                <form id="selectform" method="POST" action="javascript:void(0)">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{$amount}}">
                                </form>
                            @endif

                            @if ($payment_mode->name == "Paypal")
                                <div class="p-4 bg-[#F9F9F9] rounded-xl border border-[#E0E5E5]">
                                    @include('includes.paypal')
                                </div>
                            @endif

                            @if ($payment_mode->name == "Bank Transfer")
                                @if (!empty($payment_mode->bankname))
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-[#7A7A7A]">Bank Name</label>
                                    <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                        <p class="text-[#0D1B23]">{{$payment_mode->bankname}}</p>
                                    </div>
                                </div>
                                @endif

                                @if (!empty($payment_mode->account_name))
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-[#7A7A7A]">Account Name</label>
                                    <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                        <p class="text-[#0D1B23]">{{$payment_mode->account_name}}</p>
                                    </div>
                                </div>
                                @endif

                                @if (!empty($payment_mode->account_number))
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-[#7A7A7A]">Account Number</label>
                                    <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                        <p class="text-[#0D1B23] font-mono">{{$payment_mode->account_number}}</p>
                                    </div>
                                </div>
                                @endif

                                @if (!empty($payment_mode->swift_code))
                                <div class="space-y-2">
                                    <label class="text-sm font-semibold text-[#7A7A7A]">Swift Code</label>
                                    <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                        <p class="text-[#0D1B23] font-mono">{{$payment_mode->swift_code}}</p>
                                    </div>
                                </div>
                                @endif
                            @endif
                        @else
                            <!-- Manual Bank Transfer Details -->
                            @if (!empty($payment_mode->bankname))
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-[#7A7A7A]">Bank Name</label>
                                <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                    <p class="text-[#0D1B23]">{{$payment_mode->bankname}}</p>
                                </div>
                            </div>
                            @endif

                            @if (!empty($payment_mode->account_name))
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-[#7A7A7A]">Account Name</label>
                                <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                    <p class="text-[#0D1B23]">{{$payment_mode->account_name}}</p>
                                </div>
                            </div>
                            @endif

                            @if (!empty($payment_mode->account_number))
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-[#7A7A7A]">Account Number</label>
                                <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                    <p class="text-[#0D1B23] font-mono">{{$payment_mode->account_number}}</p>
                                </div>
                            </div>
                            @endif

                            @if (!empty($payment_mode->swift_code))
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-[#7A7A7A]">Swift Code</label>
                                <div class="bg-[#F9F9F9] rounded-xl px-4 py-3 border border-[#E0E5E5]">
                                    <p class="text-[#0D1B23] font-mono">{{$payment_mode->swift_code}}</p>
                                </div>
                            </div>
                            @endif
                        @endif
                    </div>
                @endif
            </div>

            <!-- Payment Proof Upload -->
            @if ($settings->deposit_option == "auto" and $payment_mode->defaultpay != 'yes')
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
                    <form method="post" action="{{route('savedeposit')}}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="space-y-3">
                            <label class="flex items-center space-x-2 text-[#0D1B23] font-semibold">
                                <i data-lucide="upload" class="w-5 h-5 text-[#7A7A7A]"></i>
                                <span>Upload Payment Proof</span>
                            </label>
                            <div class="relative">
                                <input class="w-full px-4 py-4 rounded-xl bg-[#F9F9F9] border-2 border-dashed border-[#E0E5E5] focus:border-primary-500 text-[#0D1B23] transition-colors outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#DFE5E5] file:text-[#4A4A4A] hover:file:bg-[#C8D1D1]" 
                                       name="proof" 
                                       type="file" 
                                       multiple />
                            </div>
                            <p class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                                <i data-lucide="info" class="w-4 h-4"></i>
                                <span>Please upload a screenshot or photo of your payment confirmation</span>
                            </p>
                        </div>
                        
                        <input type="hidden" name="amount" value="{{$amount}}">
                        <input type="hidden" name="paymethd_method" value="{{$payment_mode->name}}">

                        <button type="submit" 
                                class="w-full px-6 py-4 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-semibold transition-colors flex items-center justify-center space-x-2 group">
                            <span>Submit Payment</span>
                            <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            @endif

            @if($settings->deposit_option == "manual" and $payment_mode->name != "Paystack" and $payment_mode->name != "Stripe" and $payment_mode->name != "Paypal" and $title !="Complete Payment")
                <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
                    <form method="post" action="{{route('savedeposit')}}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="space-y-3">
                            <label class="flex items-center space-x-2 text-[#0D1B23] font-semibold">
                                <i data-lucide="upload" class="w-5 h-5 text-[#7A7A7A]"></i>
                                <span>Upload Payment Proof</span>
                            </label>
                            <div class="relative">
                                <input class="w-full px-4 py-4 rounded-xl bg-[#F9F9F9] border-2 border-dashed border-[#E0E5E5] focus:border-primary-500 text-[#0D1B23] transition-colors outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#DFE5E5] file:text-[#4A4A4A] hover:file:bg-[#C8D1D1]" 
                                       type="file" 
                                       name="proof" 
                                       required 
                                       multiple />
                            </div>
                            <p class="text-sm text-[#7A7A7A] flex items-center space-x-2">
                                <i data-lucide="info" class="w-4 h-4"></i>
                                <span>Please upload a screenshot or photo of your payment confirmation</span>
                            </p>
                        </div>

                        <input type="hidden" name="amount" value="{{$amount}}">
                        <input type="hidden" name="paymethd_method" value="{{$payment_mode->name}}">

                        <button type="submit" 
                                class="w-full px-6 py-4 rounded-xl bg-primary-600 hover:bg-primary-500 text-white font-semibold transition-colors flex items-center justify-center space-x-2 group">
                            <span>Submit Payment</span>
                            <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </form>
                </div>
            @endif
        @endif

        {{-- Automatic Cryptopayment QR Code --}}
        @if($title=="Complete Payment")
            <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
                <div class="text-center space-y-6">
                    <div class="flex justify-center">
                        <div class="w-12 h-12 rounded-xl bg-primary-600 flex items-center justify-center">
                            <i data-lucide="qr-code" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-[#0D1B23] mb-2">Scan QR Code to Pay</h3>
                        <p class="text-[#7A7A7A] mb-6">Send <strong class="text-[#0D1B23]">{{$amount}} {{$coin}}</strong> to complete your payment</p>
                    </div>

                    <div class="flex justify-center mb-6">
                        <div class="p-4 bg-white rounded-xl">
                            <img width="220" height="220" alt="Payment QR code" src="{{$p_qrcode}}" class="rounded-lg">
                        </div>
                    </div>

                    <div class="bg-[#F9F9F9] rounded-xl p-4 border border-[#E0E5E5]">
                        <p class="text-sm font-semibold text-[#0D1B23] mb-2">Payment Address</p>
                        <p class="text-sm text-[#4A4A4A] font-mono break-all">{{$p_address}}</p>
                    </div>

                    <div class="p-4 bg-[#F9F9F9] rounded-xl border border-[#E0E5E5]">
                        <div class="flex items-start space-x-3">
                            <i data-lucide="info" class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0"></i>
                            <p class="text-sm text-[#4A4A4A] text-left">You can exit this page after scanning and completing payment. The system will automatically track your payment and update your account accordingly.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
    // Copy function
    function myFunction() {
        var copyText = document.getElementById("myInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        swal("Copied", copyText.value, "success");
    }

    // Initialize Lucide icons
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>

<script type="text/javascript">
    var stripe = Stripe("{{$settings->s_p_k}}");
    var elements = stripe.elements();
    var style = {
        base: {
            color: "#0D1B23",
            fontFamily: '"DM Sans", sans-serif',
            fontSize: "16px",
            "::placeholder": {
                color: "#7A7A7A"
            }
        }
    };
    const paybtn = document.querySelector('#stripesubmit');
    if (paybtn) {
        console.log(paybtn);
        paybtn.disabled = true;

        var card = elements.create("card", { style: style });
        card.mount("#card-element");

        function checkcardforerrors() {
            card.on('change', function(event) {
                if (event.error) {
                    swal("Error", event.error.message, "error");
                    paybtn.disabled = true;
                } else {
                    paybtn.disabled = false;
                }
            });
        }
        checkcardforerrors();
        
        var form = document.getElementById('payment-form');

        form.addEventListener('submit', function(ev) {
            paybtn.disabled = true;
            ev.preventDefault();
            checkcardforerrors();
            document.getElementById('spinner').classList.remove('d-none');
            document.getElementById('buttontext').classList.add('d-none');
            
            var clientSecret = "{{$intent}}";
            stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: "{{Auth::user()->name}}"
                    }
                }
            }).then(function(result) {
                if (result.error) {
                    swal("Error", 'There was an error processing your payment, Please try deposit again from deposit page', "error");
                    console.log(result.error.message);
                } else {
                    if (result.paymentIntent.status === 'succeeded') {
                        $.ajax({
                            url: "{{url('/dashboard/submit-stripe-payment')}}",
                            type: 'POST',
                            data:$('#selectform').serialize(),
                            success: function (data) {
                                swal("Success", data.success, "success");
                                setTimeout(function(){window.location.replace("{{route('accounthistory')}}"); }, 3000);
                            },
                            error: function (error) {
                                alert('Error Submiting Payment Data');
                                console.log(error);
                            },
                        });
                    }
                }
            });
        });
    }
</script>
@endpush

@endsection
