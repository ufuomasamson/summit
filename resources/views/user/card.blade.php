@extends('layouts.bankapp')
@section('title', $title)
@section('content')
  

  <!-- App Header -->
<!-- * App Header -->

<!-- App Capsule -->
 <div id="appCapsule" class="full-height">


    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs capsuled" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#ngn" role="tab">
                        Credit Card
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#usd" role="tab" disabled>
                        Debit Card (disabled)
                    </a>
                </li>

            </ul>
        </div>
    </div><br>

   

        <div class="tab-content mt-1">
            <div class="tab-pane fade show active" id="ngn" role="tabpanel">

                <div class="card-block bg-white border mb-2">
                    <div class="card-main">
                        <div class="card-button dropdown">
                            <img src="{{ asset('storage/app/public/mastercard.png')}}" alt="img" class="image-block imaged w48 lazy animate">

                        </div>
                        
                         
                        <div class="balance">
                            <span class="label">BALANCE</span>
                            <h1 class="title">{{ Auth::user()->currency }}{{ number_format($card->amount,1, '.', ',')}} </h1>
                        </div>
                        <div class="in">
                            <div class="card-number">
                                <span class="label">Card Number</span>
                               {{ $card->card_number }}
                            </div>
                            <div class="bottom">
                                <div class="card-expiry">
                                    <span class="label">Expiry</span>
                                 {{ $card->expiry }}
                                </div>
                                <div class="card-ccv">
                                    <span class="label">CCV</span>
                                   {{ $card->cvv }}
                                </div>
                                
                               <div class="card-button dropdown mt-5">
                               
                            <h3 class="title text-dark"> {{ $card->status}} </h3>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="section mt-2">
                    <center>
                        <div class="card-body pb-1">
                             @if($card->card_status==0)
                            <a href="#" class="btn btn-outline-warning me-1 mb-1" data-bs-toggle="modal" data-bs-target="#CardActionSheet">Activate Card</a>
                            
                            
                            
                            
                              @endif 
                              
                                @if($card->card_status==1)
                            <a href="#" class="btn btn-outline-success me-1 mb-1" data-bs-toggle="modal" data-bs-target="#FundCard">Fund Card</a>
                            
                              <a href="#" class="btn btn-outline-success me-1 mb-1" data-bs-toggle="modal" data-bs-target="#Withdrawfromcard">Withdraw to Account</a>
                              @if($card->status=='Active')  
                               <a  href="freeze" class="btn btn-outline-success me-1 mb-1"  >Freeze Card</a>
                             @else
                            
                              <a  href="unfreeze" class="btn btn-outline-success me-1 mb-1" >Unfreeze Card</a>
                               @endif 
                            
                              @endif 
                              
                              
                            <a href="{{route('support')}}" type="button" class="btn btn-outline-info me-1 mb-1">Need Help?</a>

                        </div>
                    </center>
                </div><br>
                <!-- Wallet Card -->


                <div class="card">
                    <ul class="listview flush transparent image-listview text">
                        <li>
                            <a href="#" class="item">
                                <div class="in">
                                    <div>Name On Card
                                        <div class="text-muted">
                                           {{ $card->name }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="item">
                                <div class="in">
                                    <div>Card Number
                                        <div class="text-muted">
                                            {{ $card->card_number }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="item">
                                <div class="in">
                                    <div>Expiry Date
                                        <div class="text-muted">
                                          {{ $card->expiry }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>


                        <li>
                            <a href="#" class="item">
                                <div class="in">
                                    <div>CVC
                                        <div class="text-muted">
                                           {{ $card->cvv }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </li>


                    </ul>
                </div>



        </div>

                <!-- * carousel single -->
            </div>


            <div class="tab-pane fade" id="usd" role="tabpanel">

                <div class="card-block mb-2">
                    <div class="card-main">
                        <div class="card-button dropdown">
                            <img src="logo.png" alt="img" class="image-block imaged w48 lazy animate">

                        </div>
                       
                    </div>
                </div>

                <!-- * carousel single -->
            </div>

        </div>






   




    <!-- Bonus Action Sheet -->
    <!-- <div class="modal fade action-sheet" id="CardActionSheet" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">

                    <div class="card-body mb-3">



                       <form action="{{ route('createcard') }}" method="post">
                                        @csrf




                            <div class="form-group basic">
                                 <p>A prepaid card has its own balance and can have
                                        funds transferred to it.</p>
                                         <div class="input-wrapper mb-1">
                                    <label for="">Funding amount <small class="text-danger">(required)</small></label>
                                    <input type="text" class="form-control" inputmode="numeric"  style="margin-bottom: 5px" placeholder="Amount" name="amount" required>
                                  
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                                <div class="input-wrapper">
                                    <label for="">Transaction Pin <small class="text-danger">(required)</small></label>
                                    <input type="text" class="form-control" inputmode="numeric" required  autocomplete="off" style="margin-bottom: 5px" placeholder="Your 4 Digit Transaction Pin" name="pin">
                                  
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                </div>
                            </div>



                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block mt-10 btn-lg" name="card_generate" id="">Create Card
                                    </button>
                            </div>


                        </form>




                    </div>
                </div>
            </div>
        </div>
    </div> -->

    <div class="modal fade" id="CardActionSheet" tabindex="-1" role="dialog"  aria-labelledby="withdrawActionSheet" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id='Cardact'>
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="action-sheet-content">

<div class="alert alert-warning" role="alert">
  <!-- <h4 class="alert-heading">Activate Card</h4> -->
  <p>Card activation fee is {{$setting->card_fee}}{{Auth::user()->currency}}</p>
  <hr>
  <p class="mb-0">Activation fees are often charged by service providers to cover the initial costs associated with setting up and activating a Card service.</p>
</div>
                           
                               
                                <div class="form-group basic">
<a href="{{'deposits'}}" class='btn btn-primary btn-block btn-lg'>Activate Card Now</a>
                                  
                                </div>
                          
                          </div>
                </div>
            </div>
        </div>
    </div>

<div class="modal fade action-sheet" id="FundCard" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">

                    <div class="card-body mb-3">



                       <form action="{{ route('fundcard') }}" method="post">
                                        @csrf




                            <div class="form-group basic">
                                 <p>Fund Card.</p>
                                         <div class="input-wrapper mb-1">
                                    <label for="">Funding amount <small class="text-danger">(required)</small></label>
                                    <input type="text" class="form-control" inputmode="numeric"  style="margin-bottom: 5px" placeholder="Amount" name='amount'required>
                                  
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                    
                                     <p class="text-dark fw-bold fs-6 mb-0">Balance before transaction: <span id="balanceAfterFund">{{ $settings->currency }} {{ number_format(Auth::user()->account_bal,2,) }} {{ $settings->s_currency }}</span></p>
                                            <p class="text-dark fw-bold fs-6 mb-0">Fee: <span id="feeFund">{{ $settings->currency }} 0.00 {{ $settings->s_currency }}</span></p>
                                </div>
                                
                            </div>



                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block mt-10 btn-lg" name="card_generate" id="">Fund Card
                                    </button>
                            </div>


                        </form>




                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade action-sheet" id="Withdrawfromcard" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">

                    <div class="card-body mb-3">



                       <form action="{{ route('withdrawcard') }}" method="post">
                                        @csrf




                            <div class="form-group basic">
                                 <p>Withdraw from Card.</p>
                                         <div class="input-wrapper mb-1">
                                    <label for="">Withdrawal  amount <small class="text-danger">(required)</small></label>
                                    <input type="text" class="form-control" inputmode="numeric"  style="margin-bottom: 5px" placeholder="Amount" name='amount'required>
                                  
                                    <i class="clear-input">
                                        <ion-icon name="close-circle"></ion-icon>
                                    </i>
                                    
                                     <p class="text-dark fw-bold fs-6 mb-0">Balance before transaction: <span id="balanceAfterFund">{{ $settings->currency }} {{ number_format(Auth::user()->account_bal,2,) }} {{ $settings->s_currency }}</span></p>
                                            <p class="text-dark fw-bold fs-6 mb-0">Fee: <span id="feeFund">{{ $settings->currency }} 0.00 {{ $settings->s_currency }}</span></p>
                                </div>
                                
                            </div>



                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block mt-10 btn-lg" name="card_generate" id="">Withdraw from Card
                                    </button>
                            </div>


                        </form>




                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
$('#Cardact').click(function() {
        $('#CardActionSheet').modal('hide');
    });
    
</script>
    @endsection