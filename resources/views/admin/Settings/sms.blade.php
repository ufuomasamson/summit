@php
    if (Auth('admin')->User()->dashboard_style == 'light') {
        $bg = 'light';
        $text = 'dark';
        $gradient = 'primary';
    } else {
        $bg = 'dark';
        $text = 'light';
        $gradient = 'dark';
    }
@endphp

@extends('layouts.app')

@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    
    <div class="main-panel">
        <div class="content">
            <div class="panel-header bg-{{ $gradient }}-gradient">
                <div class="py-5 page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                        <div>
                            <h2 class="pb-2 text-white fw-bold">SMS Notification Settings</h2>
                            <h5 class="mb-2 text-white op-7">Configure SMS providers and notification events</h5>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-check-circle"></i> Success!</strong> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong><i class="fa fa-exclamation-triangle"></i> Validation Errors!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <div class="page-inner mt--5">
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-lg">
                            <div class="card-header">
                                <h4 class="card-title text-{{$text}}">SMS Configuration</h4>
                            </div>
                        <div class="card-body">
                            <ul class="nav nav-pills nav-{{$gradient}}" id="sms-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab">
                                        <i class="fas fa-cog"></i> General
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="providers-tab" data-toggle="pill" href="#providers" role="tab">
                                        <i class="fas fa-server"></i> Providers
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="events-tab" data-toggle="pill" href="#events" role="tab">
                                        <i class="fas fa-bell"></i> Events
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="statistics-tab" data-toggle="pill" href="#statistics" role="tab">
                                        <i class="fas fa-chart-bar"></i> Statistics
                                    </a>
                                </li>
                            </ul>

                            <form method="POST" action="{{ route('admin.sms.update') }}" class="mt-4">
                                @csrf
                                
                                <div class="tab-content" id="sms-tabs-content">
                                    <!-- General Settings Tab -->
                                    <div class="tab-pane fade show active" id="general" role="tabpanel">
                                        <h4 class="text-{{$text}} mb-3">General Settings</h4>
                                        
                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-{{$text}}">Enable SMS Notifications</label>
                                            <div class="col-md-9">
                                                <select name="sms_enabled" class="form-control" required>
                                                    <option value="yes" {{ $sms_settings->sms_enabled == 'yes' ? 'selected' : '' }}>Enabled</option>
                                                    <option value="no" {{ $sms_settings->sms_enabled == 'no' ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                                <small class="form-text text-muted">
                                                    When enabled, users will receive SMS notifications for selected events
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-{{$text}}">Primary SMS Provider</label>
                                            <div class="col-md-9">
                                                <select name="sms_primary_provider" id="sms-provider" class="form-control" required>
                                                    <option value="twilio" {{ $sms_settings->sms_primary_provider == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                                    <option value="msg91" {{ $sms_settings->sms_primary_provider == 'msg91' ? 'selected' : '' }}>MSG91</option>
                                                    <option value="plivo" {{ $sms_settings->sms_primary_provider == 'plivo' ? 'selected' : '' }}>Plivo</option>
                                                    <option value="vonage" {{ $sms_settings->sms_primary_provider == 'vonage' ? 'selected' : '' }}>Vonage (Nexmo)</option>
                                                    <option value="africastalking" {{ $sms_settings->sms_primary_provider == 'africastalking' ? 'selected' : '' }}>Africa's Talking</option>
                                                    <option value="termii" {{ $sms_settings->sms_primary_provider == 'termii' ? 'selected' : '' }}>Termii</option>
                                                </select>
                                                <small class="form-text text-muted">
                                                    Select your primary SMS gateway provider
                                                </small>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-{{$text}}">Fallback Provider 1</label>
                                            <div class="col-md-9">
                                                <select name="sms_fallback_provider_1" class="form-control">
                                                    <option value="">None</option>
                                                    <option value="twilio" {{ $sms_settings->sms_fallback_provider_1 == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                                    <option value="msg91" {{ $sms_settings->sms_fallback_provider_1 == 'msg91' ? 'selected' : '' }}>MSG91</option>
                                                    <option value="plivo" {{ $sms_settings->sms_fallback_provider_1 == 'plivo' ? 'selected' : '' }}>Plivo</option>
                                                    <option value="vonage" {{ $sms_settings->sms_fallback_provider_1 == 'vonage' ? 'selected' : '' }}>Vonage</option>
                                                    <option value="africastalking" {{ $sms_settings->sms_fallback_provider_1 == 'africastalking' ? 'selected' : '' }}>Africa's Talking</option>
                                                    <option value="termii" {{ $sms_settings->sms_fallback_provider_1 == 'termii' ? 'selected' : '' }}>Termii</option>
                                                </select>
                                                <small class="form-text text-muted">Used if primary provider fails</small>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-md-3 col-form-label text-{{$text}}">Fallback Provider 2</label>
                                            <div class="col-md-9">
                                                <select name="sms_fallback_provider_2" class="form-control">
                                                    <option value="">None</option>
                                                    <option value="twilio" {{ $sms_settings->sms_fallback_provider_2 == 'twilio' ? 'selected' : '' }}>Twilio</option>
                                                    <option value="msg91" {{ $sms_settings->sms_fallback_provider_2 == 'msg91' ? 'selected' : '' }}>MSG91</option>
                                                    <option value="plivo" {{ $sms_settings->sms_fallback_provider_2 == 'plivo' ? 'selected' : '' }}>Plivo</option>
                                                    <option value="vonage" {{ $sms_settings->sms_fallback_provider_2 == 'vonage' ? 'selected' : '' }}>Vonage</option>
                                                    <option value="africastalking" {{ $sms_settings->sms_fallback_provider_2 == 'africastalking' ? 'selected' : '' }}>Africa's Talking</option>
                                                    <option value="termii" {{ $sms_settings->sms_fallback_provider_2 == 'termii' ? 'selected' : '' }}>Termii</option>
                                                </select>
                                                <small class="form-text text-muted">Used if fallback 1 also fails</small>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-9 offset-md-3">
                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#testSmsModal">
                                                    <i class="fas fa-paper-plane"></i> Send Test SMS
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Provider Configuration Tab -->
                                    <div class="tab-pane fade" id="providers" role="tabpanel">
                                        <h4 class="text-{{$text}} mb-3">Provider Configuration</h4>
                                        
                                        <div class="alert alert-info">
                                            <strong>Current Provider:</strong> <span class="badge badge-primary">{{ ucfirst($sms_settings->sms_primary_provider) }}</span>
                                            <p class="mb-0 mt-2"><small>Configure credentials for all providers below. Only the selected provider (chosen in General tab) will be used.</small></p>
                                        </div>
                                        
                                        <!-- Twilio Provider -->
                                        <div class="provider-config mb-4" id="twilio-config">
                                            <div class="card">
                                                <div class="card-header bg-info text-white">
                                                    <h5 class="mb-0">
                                                        <i class="fas fa-comment-sms"></i> Twilio Configuration
                                                        @if($sms_settings->sms_primary_provider == 'twilio')
                                                            <span class="badge badge-success float-right">Active</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Account SID</label>
                                                        <input type="text" name="sms_twilio_sid" class="form-control" 
                                                            value="{{ $sms_settings->sms_twilio_sid ?? '' }}"
                                                            placeholder="Enter your Twilio Account SID">
                                                        <small class="form-text text-muted">
                                                            Found in your Twilio Console dashboard
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Auth Token</label>
                                                        <input type="text" name="sms_twilio_token" class="form-control" 
                                                            value="{{ $sms_settings->sms_twilio_token ?? '' }}"
                                                            placeholder="Enter your Twilio Auth Token">
                                                        <small class="form-text text-muted">
                                                            Your Twilio authentication token
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">From Number</label>
                                                        <input type="text" name="sms_twilio_from" class="form-control" 
                                                            value="{{ $sms_settings->sms_twilio_from ?? '' }}"
                                                            placeholder="+1234567890">
                                                        <small class="form-text text-muted">
                                                            Your Twilio phone number (with country code)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- MSG91 Provider -->
                                        <div class="provider-config mb-4" id="msg91-config">
                                            <div class="card">
                                                <div class="card-header bg-primary text-white">
                                                    <h5 class="mb-0">
                                                        <i class="fas fa-comment-sms"></i> MSG91 Configuration
                                                        @if($sms_settings->sms_primary_provider == 'msg91')
                                                            <span class="badge badge-light float-right">Active</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Auth Key</label>
                                                        <input type="text" name="sms_msg91_authkey" class="form-control" 
                                                            value="{{ $sms_settings->sms_msg91_authkey ?? '' }}"
                                                            placeholder="Enter your MSG91 Auth Key">
                                                        <small class="form-text text-muted">
                                                            Found in your MSG91 dashboard
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Sender ID</label>
                                                        <input type="text" name="sms_msg91_sender_id" class="form-control" 
                                                            value="{{ $sms_settings->sms_msg91_sender_id ?? '' }}"
                                                            placeholder="BANKAP">
                                                        <small class="form-text text-muted">
                                                            Your registered sender ID (6 characters, alphanumeric)
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Route</label>
                                                        <select name="sms_msg91_route" class="form-control">
                                                            <option value="4" {{ ($sms_settings->sms_msg91_route ?? '4') == '4' ? 'selected' : '' }}>Transactional (4)</option>
                                                            <option value="1" {{ ($sms_settings->sms_msg91_route ?? '4') == '1' ? 'selected' : '' }}>Promotional (1)</option>
                                                        </select>
                                                        <small class="form-text text-muted">
                                                            Route type for SMS delivery
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Plivo Provider -->
                                        <div class="provider-config mb-4" id="plivo-config">
                                            <div class="card">
                                                <div class="card-header bg-secondary text-white">
                                                    <h5 class="mb-0">
                                                        <i class="fas fa-comment-sms"></i> Plivo Configuration
                                                        @if($sms_settings->sms_primary_provider == 'plivo')
                                                            <span class="badge badge-light float-right">Active</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Auth ID</label>
                                                        <input type="text" name="sms_plivo_auth_id" class="form-control" 
                                                            value="{{ $sms_settings->sms_plivo_auth_id ?? '' }}"
                                                            placeholder="Enter your Plivo Auth ID">
                                                        <small class="form-text text-muted">
                                                            Found in your Plivo console
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Auth Token</label>
                                                        <input type="text" name="sms_plivo_auth_token" class="form-control" 
                                                            value="{{ $sms_settings->sms_plivo_auth_token ?? '' }}"
                                                            placeholder="Enter your Plivo Auth Token">
                                                        <small class="form-text text-muted">
                                                            Your Plivo authentication token
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">From Number</label>
                                                        <input type="text" name="sms_plivo_from" class="form-control" 
                                                            value="{{ $sms_settings->sms_plivo_from ?? '' }}"
                                                            placeholder="+1234567890">
                                                        <small class="form-text text-muted">
                                                            Your Plivo phone number (with country code)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Vonage Provider -->
                                        <div class="provider-config mb-4" id="vonage-config">
                                            <div class="card">
                                                <div class="card-header bg-success text-white">
                                                    <h5 class="mb-0">
                                                        <i class="fas fa-comment-sms"></i> Vonage (Nexmo) Configuration
                                                        @if($sms_settings->sms_primary_provider == 'vonage')
                                                            <span class="badge badge-light float-right">Active</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">API Key</label>
                                                        <input type="text" name="sms_vonage_key" class="form-control" 
                                                            value="{{ $sms_settings->sms_vonage_key ?? '' }}"
                                                            placeholder="Enter your Vonage API Key">
                                                        <small class="form-text text-muted">
                                                            Found in your Vonage API dashboard
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">API Secret</label>
                                                        <input type="text" name="sms_vonage_secret" class="form-control" 
                                                            value="{{ $sms_settings->sms_vonage_secret ?? '' }}"
                                                            placeholder="Enter your Vonage API Secret">
                                                        <small class="form-text text-muted">
                                                            Your Vonage API secret key
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">From Name/Number</label>
                                                        <input type="text" name="sms_vonage_from" class="form-control" 
                                                            value="{{ $sms_settings->sms_vonage_from ?? '' }}"
                                                            placeholder="BankApp">
                                                        <small class="form-text text-muted">
                                                            Sender ID or phone number (alphanumeric supported)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Africa's Talking Provider -->
                                        <div class="provider-config mb-4" id="africastalking-config">
                                            <div class="card">
                                                <div class="card-header bg-warning text-white">
                                                    <h5 class="mb-0">
                                                        <i class="fas fa-comment-sms"></i> Africa's Talking Configuration
                                                        @if($sms_settings->sms_primary_provider == 'africastalking')
                                                            <span class="badge badge-light float-right">Active</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Username</label>
                                                        <input type="text" name="sms_africastalking_username" class="form-control" 
                                                            value="{{ $sms_settings->sms_africastalking_username ?? '' }}"
                                                            placeholder="Enter your Africa's Talking username">
                                                        <small class="form-text text-muted">
                                                            Your Africa's Talking username (usually your app name)
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">API Key</label>
                                                        <input type="text" name="sms_africastalking_api_key" class="form-control" 
                                                            value="{{ $sms_settings->sms_africastalking_api_key ?? '' }}"
                                                            placeholder="Enter your Africa's Talking API Key">
                                                        <small class="form-text text-muted">
                                                            Found in your Africa's Talking dashboard
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Sender ID</label>
                                                        <input type="text" name="sms_africastalking_from" class="form-control" 
                                                            value="{{ $sms_settings->sms_africastalking_from ?? '' }}"
                                                            placeholder="BANKAPP">
                                                        <small class="form-text text-muted">
                                                            Your registered sender ID (alphanumeric, max 11 characters)
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Termii Provider -->
                                        <div class="provider-config mb-4" id="termii-config">
                                            <div class="card">
                                                <div class="card-header bg-danger text-white">
                                                    <h5 class="mb-0">
                                                        <i class="fas fa-comment-sms"></i> Termii Configuration
                                                        @if($sms_settings->sms_primary_provider == 'termii')
                                                            <span class="badge badge-light float-right">Active</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">API Key</label>
                                                        <input type="text" name="sms_termii_api_key" class="form-control" 
                                                            value="{{ $sms_settings->sms_termii_api_key ?? '' }}"
                                                            placeholder="Enter your Termii API Key">
                                                        <small class="form-text text-muted">
                                                            Found in your Termii dashboard
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Sender ID</label>
                                                        <input type="text" name="sms_termii_sender_id" class="form-control" 
                                                            value="{{ $sms_settings->sms_termii_sender_id ?? '' }}"
                                                            placeholder="BANKAPP">
                                                        <small class="form-text text-muted">
                                                            Your registered sender ID (3-11 characters)
                                                        </small>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <label class="text-{{$text}}">Channel</label>
                                                        <select name="sms_termii_channel" class="form-control">
                                                            <option value="generic" {{ ($sms_settings->sms_termii_channel ?? 'generic') == 'generic' ? 'selected' : '' }}>Generic</option>
                                                            <option value="dnd" {{ ($sms_settings->sms_termii_channel ?? 'generic') == 'dnd' ? 'selected' : '' }}>DND</option>
                                                            <option value="whatsapp" {{ ($sms_settings->sms_termii_channel ?? 'generic') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                                                        </select>
                                                        <small class="form-text text-muted">
                                                            SMS delivery channel
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Events Tab -->
                                    <div class="tab-pane fade" id="events" role="tabpanel">
                                        <h4 class="text-{{$text}} mb-3">SMS Event Notifications</h4>
                                        <p class="text-muted">Select which events should trigger SMS notifications to users</p>
                                        
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="text-{{$text}}">Transaction Events</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-4 p-3 border-bottom">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="sms_on_deposit" 
                                                                    id="sms_on_deposit" {{ $sms_settings->sms_on_deposit ? 'checked' : '' }}>
                                                                <span class="form-check-sign"></span>
                                                                <span class="text-{{$text}}">
                                                                    <strong>Deposit Notifications</strong>
                                                                    <br><small class="text-muted">Notify users when deposits are credited</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="mb-4 p-3 border-bottom">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="sms_on_withdrawal" 
                                                                    id="sms_on_withdrawal" {{ $sms_settings->sms_on_withdrawal ? 'checked' : '' }}>
                                                                <span class="form-check-sign"></span>
                                                                <span class="text-{{$text}}">
                                                                    <strong>Withdrawal Notifications</strong>
                                                                    <br><small class="text-muted">Notify users of withdrawal confirmations</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="mb-4 p-3">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="sms_on_transfer" 
                                                                    id="sms_on_transfer" {{ $sms_settings->sms_on_transfer ? 'checked' : '' }}>
                                                                <span class="form-check-sign"></span>
                                                                <span class="text-{{$text}}">
                                                                    <strong>Transfer Notifications</strong>
                                                                    <br><small class="text-muted">Notify users of money transfers</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="text-{{$text}}">Account Events</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="mb-4 p-3 border-bottom">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="sms_on_registration" 
                                                                    id="sms_on_registration" {{ $sms_settings->sms_on_registration ? 'checked' : '' }}>
                                                                <span class="form-check-sign"></span>
                                                                <span class="text-{{$text}}">
                                                                    <strong>Registration</strong>
                                                                    <br><small class="text-muted">Welcome SMS on new user registration</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="mb-4 p-3 border-bottom">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="sms_on_login" 
                                                                    id="sms_on_login" {{ $sms_settings->sms_on_login ? 'checked' : '' }}>
                                                                <span class="form-check-sign"></span>
                                                                <span class="text-{{$text}}">
                                                                    <strong>Login Alerts</strong>
                                                                    <br><small class="text-muted">Notify on new login attempts</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                        
                                                        <div class="mb-4 p-3">
                                                            <label class="form-check-label">
                                                                <input type="checkbox" name="sms_on_password_change" 
                                                                    id="sms_on_password_change" {{ $sms_settings->sms_on_password_change ? 'checked' : '' }}>
                                                                <span class="form-check-sign"></span>
                                                                <span class="text-{{$text}}">
                                                                    <strong>Password Changes</strong>
                                                                    <br><small class="text-muted">Alert on password changes</small>
                                                                </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="text-{{$text}}">Additional Settings</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="mb-4 p-3">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" name="sms_rate_limiting_enabled" 
                                                                            id="sms_rate_limiting_enabled" {{ $sms_settings->sms_rate_limiting_enabled ? 'checked' : '' }}>
                                                                        <span class="form-check-sign"></span>
                                                                        <span class="text-{{$text}}">
                                                                            <strong>Enable Rate Limiting</strong>
                                                                            <br><small class="text-muted">Limit SMS per user per time window</small>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="text-{{$text}}">SMS Limit Per User</label>
                                                                    <input type="number" name="sms_rate_limit_per_user" class="form-control" 
                                                                        value="{{ $sms_settings->sms_rate_limit_per_user ?? 10 }}" min="1">
                                                                    <small class="form-text text-muted">Maximum SMS per time window</small>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label class="text-{{$text}}">Rate Limit Window (minutes)</label>
                                                                    <input type="number" name="sms_rate_limit_window" class="form-control" 
                                                                        value="{{ $sms_settings->sms_rate_limit_window ?? 60 }}" min="1">
                                                                    <small class="form-text text-muted">Time window in minutes</small>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <div class="mb-4 p-3">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" name="sms_email_fallback_enabled" 
                                                                            id="sms_email_fallback_enabled" {{ $sms_settings->sms_email_fallback_enabled ? 'checked' : '' }}>
                                                                        <span class="form-check-sign"></span>
                                                                        <span class="text-{{$text}}">
                                                                            <strong>Email Fallback</strong>
                                                                            <br><small class="text-muted">Send email if SMS fails</small>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                                
                                                                <div class="mb-4 p-3">
                                                                    <label class="form-check-label">
                                                                        <input type="checkbox" name="sms_cost_tracking_enabled" 
                                                                            id="sms_cost_tracking_enabled" {{ $sms_settings->sms_cost_tracking_enabled ? 'checked' : '' }}>
                                                                        <span class="form-check-sign"></span>
                                                                        <span class="text-{{$text}}">
                                                                            <strong>Cost Tracking</strong>
                                                                            <br><small class="text-muted">Track SMS costs in logs</small>
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Statistics Tab -->
                                    <div class="tab-pane fade" id="statistics" role="tabpanel">
                                        <h4 class="text-{{$text}} mb-3">SMS Statistics</h4>
                                        
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <div class="card card-stats card-round">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-icon">
                                                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                                                    <i class="fas fa-sms"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col col-stats ml-3 ml-sm-0">
                                                                <div class="numbers">
                                                                    <p class="card-category">Total Sent</p>
                                                                    <h4 class="card-title">{{ number_format($total_sent) }}</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="card card-stats card-round">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-icon">
                                                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                                                    <i class="fas fa-check-circle"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col col-stats ml-3 ml-sm-0">
                                                                <div class="numbers">
                                                                    <p class="card-category">Success</p>
                                                                    <h4 class="card-title">{{ number_format($success_count) }}</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="card card-stats card-round">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-icon">
                                                                <div class="icon-big text-center icon-danger bubble-shadow-small">
                                                                    <i class="fas fa-times-circle"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col col-stats ml-3 ml-sm-0">
                                                                <div class="numbers">
                                                                    <p class="card-category">Failed</p>
                                                                    <h4 class="card-title">{{ number_format($failed_count) }}</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-3">
                                                <div class="card card-stats card-round">
                                                    <div class="card-body">
                                                        <div class="row align-items-center">
                                                            <div class="col-icon">
                                                                <div class="icon-big text-center icon-warning bubble-shadow-small">
                                                                    <i class="fas fa-clock"></i>
                                                                </div>
                                                            </div>
                                                            <div class="col col-stats ml-3 ml-sm-0">
                                                                <div class="numbers">
                                                                    <p class="card-category">Pending</p>
                                                                    <h4 class="card-title">{{ number_format($pending_count) }}</h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card">
                                            <div class="card-header">
                                                <h5 class="text-{{$text}}">Recent SMS Messages</h5>
                                            </div>
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Phone</th>
                                                                <th>Message</th>
                                                                <th>Provider</th>
                                                                <th>Event</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($recent_sms as $sms)
                                                                <tr>
                                                                    <td>{{ date('M d, Y H:i', strtotime($sms->created_at)) }}</td>
                                                                    <td>{{ $sms->phone_number }}</td>
                                                                    <td>{{ Str::limit($sms->message, 50) }}</td>
                                                                    <td>
                                                                        <span class="badge badge-info">{{ ucfirst($sms->provider) }}</span>
                                                                    </td>
                                                                    <td>{{ ucfirst($sms->event_type) }}</td>
                                                                    <td>
                                                                        @if($sms->status == 'success')
                                                                            <span class="badge badge-success">Success</span>
                                                                        @elseif($sms->status == 'failed')
                                                                            <span class="badge badge-danger">Failed</span>
                                                                        @else
                                                                            <span class="badge badge-warning">Pending</span>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="6" class="text-center text-muted">No SMS messages sent yet</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="submit" class="btn btn-{{$gradient}} btn-lg px-5">
                                        <i class="fas fa-save"></i> Save Settings
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Test SMS Modal -->
    <div class="modal fade" id="testSmsModal" tabindex="-1" role="dialog" aria-labelledby="testSmsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-{{$gradient}}-gradient">
                    <h5 class="modal-title text-white" id="testSmsModalLabel">Send Test SMS</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="test-sms-form">
                        <div class="form-group">
                            <label class="text-{{$text}}">Phone Number</label>
                            <input type="text" id="test_phone" class="form-control" 
                                placeholder="+1234567890" required>
                            <small class="form-text text-muted">
                                Enter phone number with country code
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label class="text-{{$text}}">Message</label>
                            <textarea id="test_message" class="form-control" rows="3" 
                                placeholder="Enter your test message" maxlength="160" required></textarea>
                            <small class="form-text text-muted">
                                <span id="char-count">0</span> / 160 characters
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" id="send-test-sms" class="btn btn-{{$gradient}}">
                        <i class="fas fa-paper-plane"></i> Send Test
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Character counter
            $('#test_message').on('input', function() {
                var length = $(this).val().length;
                $('#char-count').text(length);
            });

            // Send test SMS
            $('#send-test-sms').on('click', function() {
                var phone = $('#test_phone').val();
                var message = $('#test_message').val();
                
                if (!phone || !message) {
                    alert('Please fill in all fields');
                    return;
                }

                var btn = $(this);
                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Sending...');

                $.ajax({
                    url: '{{ route("admin.sms.test") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        test_phone: phone,
                        test_message: message
                    },
                    success: function(response) {
                        alert(response.message);
                        $('#testSmsModal').modal('hide');
                        $('#test_phone').val('');
                        $('#test_message').val('');
                        $('#char-count').text('0');
                        
                        // Reload statistics tab
                        if ($('#statistics-tab').hasClass('active')) {
                            location.reload();
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to send SMS';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        alert(message);
                    },
                    complete: function() {
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Send Test');
                    }
                });
            });
        });
    </script>
@endsection
