<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $text = 'dark';
    $bg = 'light';
} else {
    $text = 'light';
    $bg = 'dark';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content ">
            <div class="page-inner">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-check-circle"></i> Success!</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-times-circle"></i> Error!</strong> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-triangle"></i> Alert!</strong> {{ session('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-info-circle"></i> Info!</strong> {{ session('info') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-exclamation-circle"></i> Warning!</strong> {{ session('warning') }}
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
                <!-- Beginning of  Dashboard Stats  -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-3 card ">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 ">

                                        <img alt=""
                                            src="{{ profile_photo_url($user) }}"
                                            width="60" height="60" style='border-radius: 50%;'>
                                        <h1 class=" pl-2 d-inline text-primary">{{ $user->name }} {{ $user->middlename }}
                                            {{ $user->lastname }}</h1><span></span>
                                        <div class="d-inline">
                                            <div class="float-right btn-group">
                                                <a class="btn btn-primary btn-sm" href="{{ route('manageusers') }}">
                                                    <i class="fa fa-arrow-left"></i> back
                                                </a>
                                                &nbsp;
                                                <button type="button" class="btn btn-secondary btn-sm"
                                                    data-toggle="modal" data-target="#userActionsModal">
                                                    <i class="fa fa-bolt"></i> Actions
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3 mt-4 border rounded row ">
                                    <div class="col-12 d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0"><i class="fa fa-wallet"></i> Account Balances</h6>
                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="toggleBalanceEdit()">
                                            <i class="fa fa-edit" id="balanceEditIcon"></i> <span id="balanceEditText">Edit Balances</span>
                                        </button>
                                    </div>
                                    
                                    {{-- Display Mode --}}
                                    <div id="balanceDisplay" class="col-12">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <h5 class="text-bold">Account Balance</h5>
                                                <p>{{ $user->currency }}{{ number_format($user->account_bal, 2) }}</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Bitcoin (BTC)</h5>
                                                <p>{{ number_format($cryptoAccount->btc ?? 0, 8) }} BTC</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>Ethereum (ETH)</h5>
                                                <p>{{ number_format($cryptoAccount->eth ?? 0, 8) }} ETH</p>
                                            </div>
                                            <div class="col-md-3">
                                                <h5>USDT</h5>
                                                <p>{{ number_format($cryptoAccount->usdt ?? 0, 2) }} USDT</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Edit Mode --}}
                                    <div id="balanceEdit" class="col-12" style="display: none;">
                                        <form method="post" action="{{ route('updateUserBalances', $user->id) }}">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Account Balance ({{ $user->currency }})</label>
                                                        <input type="number" step="0.01" class="form-control" name="account_bal" value="{{ $user->account_bal }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Bitcoin (BTC)</label>
                                                        <input type="number" step="0.00000001" class="form-control" name="btc" value="{{ $cryptoAccount->btc ?? 0 }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Ethereum (ETH)</label>
                                                        <input type="number" step="0.00000001" class="form-control" name="eth" value="{{ $cryptoAccount->eth ?? 0 }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">USDT</label>
                                                        <input type="number" step="0.01" class="form-control" name="usdt" value="{{ $cryptoAccount->usdt ?? 0 }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm mr-2">
                                                    <i class="fa fa-save"></i> Save Balances
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm" onclick="toggleBalanceEdit()">
                                                    Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="p-3 mt-2 border rounded row ">
                                    <div class="col-md-3">
                                        <h5>Account Limit</h5>
                                        <p>{{ $user->currency }}{{ number_format($user->limit) }} </p>
                                    </div>

                                    <div class="col-md-3">
                                        <h5>Account Status</h5>
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
                                        <span class="badge badge-{{ $badge }}">{{ ucwords(str_replace('_', ' ', $user->account_status ?? 'active')) }}</span>
                                    </div>
                                    <div class="col-md-3">
                                        <h5>Transfer Limits</h5>
                                        @if($user->daily_limit_enabled || $user->weekly_limit_enabled || $user->monthly_limit_enabled)
                                            <a href="#" data-toggle="modal" data-target="#accountLimitsModal" class="btn btn-sm btn-info">View Limits</a>
                                        @else
                                            <p>No Limits Set</p>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($user->daily_limit_enabled || $user->weekly_limit_enabled || $user->monthly_limit_enabled)
                                <div class="p-3 mt-3 border rounded row" style="background: #f8f9fa;">
                                    @if($user->daily_limit_enabled)
                                        <div class="col-md-4">
                                            <h6><i class="fa fa-calendar"></i> Daily Limit</h6>
                                            <p class="mb-0"><strong>{{ $user->currency }}{{ number_format($user->daily_limit, 2) }}</strong></p>
                                        </div>
                                    @endif
                                    @if($user->weekly_limit_enabled)
                                        <div class="col-md-4">
                                            <h6><i class="fa fa-calendar-week"></i> Weekly Limit</h6>
                                            <p class="mb-0"><strong>{{ $user->currency }}{{ number_format($user->weekly_limit, 2) }}</strong></p>
                                        </div>
                                    @endif
                                    @if($user->monthly_limit_enabled)
                                        <div class="col-md-4">
                                            <h6><i class="fa fa-calendar-alt"></i> Monthly Limit</h6>
                                            <p class="mb-0"><strong>{{ $user->currency }}{{ number_format($user->monthly_limit, 2) }}</strong></p>
                                        </div>
                                    @endif
                                </div>
                                @endif

                                {{-- Transfer Codes Status Display --}}
                                <div class="p-3 mt-3 border rounded">
                                    <div class="row align-items-center">
                                        <div class="col-md-8">
                                            <h5 class="mb-0">
                                                <i class="fa fa-shield-alt"></i> Transfer Code Settings
                                            </h5>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <button class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-target="#transferCodesModal">
                                                <i class="fa fa-cog"></i> Manage Codes
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h6>{{ $settings->code1 ?? 'Code 1' }}</h6>
                                            @if($user->code1_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->code1_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->code1status == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $settings->code2 ?? 'Code 2' }}</h6>
                                            @if($user->code2_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->code2_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->code2status == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $settings->code3 ?? 'Code 3' }}</h6>
                                            @if($user->code3_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->code3_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->code3status == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <h6>OTP Verification</h6>
                                            @if($user->otp_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->otp_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->otp == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-3 mt-4 border rounded row "></div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <h6>{{ $settings->code1 ?? 'Code 1' }}</h6>
                                            @if($user->code1_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->code1_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->code1status == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $settings->code2 ?? 'Code 2' }}</h6>
                                            @if($user->code2_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->code2_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->code2status == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <h6>{{ $settings->code3 ?? 'Code 3' }}</h6>
                                            @if($user->code3_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->code3_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->code3status == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <h6>OTP Verification</h6>
                                            @if($user->otp_enabled === 1)
                                                <span class="badge badge-success" style="background-color: #2d8659;">
                                                    <i class="fa fa-check"></i> User: Enabled
                                                </span>
                                            @elseif($user->otp_enabled === 0)
                                                <span class="badge badge-secondary">
                                                    <i class="fa fa-times"></i> User: Disabled
                                                </span>
                                            @else
                                                <span class="badge badge-warning" style="background-color: #e0a84e;">
                                                    <i class="fa fa-globe"></i> Global ({{ $settings->otp == '1' ? 'On' : 'Off' }})
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Block/Ban Status Display --}}
                                @if(in_array($user->account_status ?? '', ['blocked', 'permanently_banned']))
                                <div class="p-3 mt-4 border rounded" style="border-color: #dc3545 !important; border-width: 2px !important;">
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <h5 class="text-danger mb-0">
                                            <i class="fa fa-exclamation-triangle"></i> Account Blocked
                                        </h5>
                                        <span class="badge {{ $user->account_status == 'permanently_banned' ? 'badge-danger' : 'badge-warning' }} badge-lg" style="font-size: 14px;">
                                            {{ $user->account_status == 'permanently_banned' ? 'PERMANENTLY BANNED' : 'TEMPORARILY BLOCKED' }}
                                        </span>
                                    </div>
                                    
                                    @if($user->block_reason)
                                    <div class="alert alert-danger">
                                        <strong><i class="fa fa-info-circle"></i> Block Reason:</strong><br>
                                        {{ $user->block_reason }}
                                    </div>
                                    @endif
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-2">
                                                <strong>Blocked On:</strong><br>
                                                @if($user->blocked_at)
                                                    {{ \Carbon\Carbon::parse($user->blocked_at)->format('M d, Y \a\t h:i A') }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                            <p class="mb-2">
                                                <strong>Blocked By:</strong><br>
                                                @if($user->blockedByAdmin)
                                                    {{ $user->blockedByAdmin->firstName }} {{ $user->blockedByAdmin->lastName }}
                                                @else
                                                    System
                                                @endif
                                            </p>
                                        </div>
                                        <div class="col-md-6">
                                            @if($user->unblock_at && $user->account_status == 'blocked')
                                            <p class="mb-2">
                                                <strong>Scheduled Auto-Unblock:</strong><br>
                                                <span class="text-warning">
                                                    <i class="fa fa-clock"></i> {{ \Carbon\Carbon::parse($user->unblock_at)->format('M d, Y \a\t h:i A') }}
                                                </span>
                                                <br>
                                                <small class="text-muted">
                                                    ({{ \Carbon\Carbon::parse($user->unblock_at)->diffForHumans() }})
                                                </small>
                                            </p>
                                            @elseif($user->account_status == 'permanently_banned')
                                            <div class="alert alert-danger mb-2">
                                                <i class="fa fa-lock"></i> <strong>Permanent Ban</strong><br>
                                                <small>User cannot access account until manually unblocked</small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#unblockUserModal">
                                            <i class="fa fa-check-circle"></i> Unblock Account Now
                                        </button>
                                        @if($user->blockHistory && $user->blockHistory->count() > 1)
                                        <button type="button" class="btn btn-info btn-sm ml-2" onclick="alert('Feature coming soon: View complete block history')">
                                            <i class="fa fa-history"></i> View Block History ({{ $user->blockHistory->count() }})
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <div class="p-3 mt-4 border rounded row ">


                                    {{-- <div class="col-md-3">
                                        <h5>User Account Status</h5>
                                        @if ($user->status == 'blocked')
                                            <span class="badge badge-danger">Blocked</span>
                                        @elseif($user->status == 'unhold')
                                        <span class="badge badge-warning">Unhold</span>
                                            <span class="badge badge-success">Active</span>
                                        @endif
                                    </div> --}}
                                    <div class="col-md-3">
                                        <h5>Loans</h5>
                                        {{-- <span class="text-bold"> <strong>2</strong> </span> --}}
                                        @if ($user->plan != null)
                                            <a class="btn btn-sm btn-primary d-inline"
                                                href="{{ route('user.plans', $user->id) }}">Veiw loans</a>
                                        @else
                                            <p>No Loan</p>
                                        @endif

                                    </div>
                                    <div class="col-md-3">
                                        <h5>KYC</h5>
                                        @if ($user->account_verify == 'Not Verified' || $user->account_verify == null)
                                            <span class="badge badge-danger">Not Verified Yet</span>
                                        @else
                                            <span class="badge badge-success">Verified</span>
                                        @endif
                                    </div>
                                    {{-- <div class="col-md-3">
                                        <h5>Trade Mode</h5>
                                        @if ($user->trade_mode == 'off' || $user->trade_mode == null)
                                            <span class="badge badge-danger">Off</span>
                                        @else
                                            <span class="badge badge-success">On</span>
                                        @endif
                                    </div> --}}
                                </div>
                                <div class="mt-3 row ">
                                    <div class="col-md-12">
                                        <h5>USER INFORMATION</h5>
                                    </div>
                                </div>
                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>Fullname</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->name }} {{ $user->middlename }} {{ $user->lastname }}</h5>
                                    </div>
                                </div>
                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>Email Address</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->email }}</h5>
                                    </div>
                                </div>
                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>Mobile Number</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->phone }}</h5>
                                    </div>
                                </div>


                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>Account Number</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->usernumber }}</h5>
                                    </div>
                                </div>
                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>4 Digit Transaction Pin</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->pin }}</h5>
                                    </div>
                                </div>

                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>{{ $settings->code1 }} Code</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->code1 }}</h5>
                                    </div>
                                </div>

                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>{{ $settings->code2 }} Code</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->code2 }}</h5>
                                    </div>
                                </div>

                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>{{ $settings->code3 }} Code</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->code3 }}</h5>
                                    </div>
                                </div>
                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>Date of birth</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->dob }}</h5>
                                    </div>
                                </div>
                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>Nationality</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ $user->country }}</h5>
                                    </div>
                                </div>
                                {{-- <div class="p-3 border row ">
                                <div class="col-md-4 border-right">
                                    <h5>Wallet Address</h5>
                                </div>
                                <div class="col-md-8">
                                   <h5>@if ($user->wallet_address)
                                    {{$user->wallet_address}}
                                   @else
                                   Not added yet!
                                   @endif</h5>
                                </div>
                            </div> --}}
                                <div class="p-3 border row ">
                                    <div class="col-md-4 border-right">
                                        <h5>Registered</h5>
                                    </div>
                                    <div class="col-md-8">
                                        <h5>{{ \Carbon\Carbon::parse($user->created_at)->toDayDateTimeString() }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

        {{-- ===================== TRANSACTION HISTORY SECTION ===================== --}}
        <div class="mb-5 row mt-4">
            <div class="col card p-3 shadow">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="font-weight-bold mb-0">
                        <i class="fa fa-exchange-alt mr-2" style="color: #2d8659;"></i>Transaction History
                        <span class="badge badge-primary ml-2">{{ $withdrawals->count() }}</span>
                    </h4>
                </div>

                @if($withdrawals->count() > 0)
                <div class="bs-example widget-shadow table-responsive" data-example-id="user-txn-table">
                    <table id="UserTxnTable" class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>TXN ID</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Type</th>
                                <th>Payment Method</th>
                                <th>Beneficiary</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($withdrawals as $txn)
                                <tr>
                                    <td>{{ $txn->id }}</td>
                                    <td><small class="text-muted">{{ $txn->txn_id }}</small></td>
                                    <td>{{ $user->currency }}{{ number_format($txn->amount, 2) }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($txn->Description, 30) }}</td>
                                    <td>
                                        @php $txnStatusLower = strtolower($txn->status ?? ''); @endphp
                                        @if(in_array($txnStatusLower, ['failed', 'error'], true))
                                            <span class="badge badge-danger">{{ $txn->status }}</span>
                                        @elseif($txnStatusLower === 'pending')
                                            <span class="badge badge-warning">{{ $txn->status }}</span>
                                        @elseif($txn->type === 'Credit')
                                            <span class="badge badge-success">Credit</span>
                                        @else
                                            <span class="badge badge-danger">Debit</span>
                                        @endif
                                    </td>
                                    <td>{{ $txn->payment_mode }}</td>
                                    <td>{{ $txn->accountname }}</td>
                                    <td>
                                        @if ($txn->status == 'Processed')
                                            <span class="badge badge-success">{{ $txn->status }}</span>
                                        @elseif(in_array($txn->status, ['On-hold', 'Pending', 'Under Review']))
                                            <span class="badge badge-warning">{{ $txn->status }}</span>
                                        @elseif(in_array($txn->status, ['Reversed', 'Refunded']))
                                            <span class="badge badge-info">{{ $txn->status }}</span>
                                        @elseif($txn->status == 'Scheduled')
                                            <span class="badge badge-primary">{{ $txn->status }}</span>
                                        @elseif($txn->status == 'Cancelled')
                                            <span class="badge badge-secondary">{{ $txn->status }}</span>
                                        @elseif(in_array($txn->status, ['Expired', 'Frozen', 'Failed', 'Error']))
                                            <span class="badge badge-danger">{{ $txn->status }}</span>
                                        @else
                                            <span class="badge badge-danger">{{ $txn->status }}</span>
                                        @endif
                                    </td>
                                    <td><small>{{ \Carbon\Carbon::parse($txn->created_at)->toDayDateTimeString() }}</small></td>
                                    <td class="text-nowrap">
                                        <button type="button" class="btn btn-success btn-sm mb-1" title="Quick Process"
                                                onclick="openQuickProcessModal({{ $txn->id }}, '{{ $txn->txn_id }}', '{{ $user->currency }}{{ number_format($txn->amount, 2) }}', '{{ $txn->accountname }}')">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <a href="{{ route('processwithdraw', $txn->id) }}" class="btn btn-info btn-sm mb-1" title="View Details">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-primary btn-sm mb-1" title="Edit Transaction"
                                                onclick="openUserTxnEditModal({{ json_encode($txn) }})">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        
                                        <button type="button" class="btn btn-danger btn-sm mb-1" title="Delete Transaction"
                                                onclick="confirmDeleteUserTxn({{ $txn->id }}, '{{ $txn->txn_id }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No transactions found for this user.</p>
                </div>
                @endif
            </div>
        </div>
            </div>
        </div>
        </div>

        {{-- ===================== EDIT TRANSACTION MODAL ===================== --}}
        <div class="modal fade" id="userTxnEditModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <form id="userTxnEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="redirect_to" value="{{ route('viewuser', $user->id) }}">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fa fa-edit mr-2"></i>Edit Transaction</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="utxn_edit_id" name="withdrawal_id">

                            {{-- Transaction Info --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Transaction ID</label>
                                    <input type="text" class="form-control" id="utxn_edit_txn_id" name="txn_id" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Account Holder</label>
                                    <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                                </div>
                            </div>

                            {{-- Amount, Type, Status --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Amount <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control" id="utxn_edit_amount" name="amount" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Type <span class="text-danger">*</span></label>
                                    <select class="form-control" id="utxn_edit_type" name="type" required onchange="utxnToggleSenderFields()">
                                        <option value="Credit">Credit</option>
                                        <option value="Debit">Debit</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="font-weight-bold">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="utxn_edit_status" name="status" required>
                                        <option value="Pending">Pending</option>
                                        <option value="Processed">Processed</option>
                                        <option value="On-hold">On-hold</option>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Failed">Failed</option>
                                        <option value="Error">Error</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Reversed">Reversed</option>
                                        <option value="Refunded">Refunded</option>
                                        <option value="Scheduled">Scheduled</option>
                                        <option value="Expired">Expired</option>
                                        <option value="Under Review">Under Review</option>
                                        <option value="Frozen">Frozen</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Payment Mode & Date --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-control" id="utxn_edit_payment_mode" name="payment_mode" required onchange="utxnTogglePaymentFields()">
                                        <option value="Wire Transfer">Wire Transfer</option>
                                        <option value="Internal Transfer">Internal Transfer</option>
                                        <option value="Domestic Transfer">Domestic Transfer</option>
                                        <option value="Local transfer">Local Transfer</option>
                                        <option value="Western Union">Western Union</option>
                                        <option value="Bitcoin">Bitcoin</option>
                                        <option value="Ethereum">Ethereum</option>
                                        <option value="USDT">USDT</option>
                                        <option value="Cryptocurrency">Cryptocurrency</option>
                                        <option value="PayPal">PayPal</option>
                                        <option value="Skrill">Skrill</option>
                                        <option value="Payoneer">Payoneer</option>
                                        <option value="TransferWise">TransferWise</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="font-weight-bold">Transaction Date</label>
                                    <input type="datetime-local" class="form-control" id="utxn_edit_date" name="date">
                                </div>
                            </div>

                            {{-- Description --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label class="font-weight-bold">Description</label>
                                    <textarea class="form-control" id="utxn_edit_description" name="Description" rows="2"></textarea>
                                </div>
                            </div>

                            {{-- Sender/Party Information --}}
                            <div id="utxnSenderFieldsSection" class="border-top pt-3 mb-3" style="display: none;">
                                <h6 class="font-weight-bold mb-3" id="utxnSenderHeaderText">Sender Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label id="utxnSenderNameLabel">Sender Name</label>
                                        <input type="text" class="form-control" id="utxn_edit_sender_name" name="sender_name">
                                    </div>
                                    <div class="col-md-6">
                                        <label id="utxnSenderBankLabel">Sender Bank</label>
                                        <input type="text" class="form-control" id="utxn_edit_sender_bank" name="sender_bank">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label id="utxnSenderAccountLabel">Sender Account Number</label>
                                        <input type="text" class="form-control" id="utxn_edit_sender_account_number" name="sender_account_number">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Reference Number</label>
                                        <input type="text" class="form-control" id="utxn_edit_reference_number" name="reference_number">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label id="utxnSenderAddressLabel">Sender Bank Address</label>
                                        <textarea class="form-control" id="utxn_edit_sender_bank_address" name="sender_bank_address" rows="2"></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Beneficiary Information --}}
                            <div class="border-top pt-3 mb-3">
                                <h6 class="font-weight-bold mb-3">Beneficiary Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold">Account Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="utxn_edit_accountname" name="accountname" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold" id="utxnAccountNumberLabel">Account Number <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="utxn_edit_accountnumber" name="accountnumber" required>
                                    </div>
                                </div>

                                <div id="utxnBankTransferFields">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label>Bank Name</label>
                                            <input type="text" class="form-control" id="utxn_edit_bankname" name="bankname">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Account Type</label>
                                            <select class="form-control" id="utxn_edit_accounttype" name="Accounttype">
                                                <option value="">Select Account Type</option>
                                                <option value="Checking">Checking</option>
                                                <option value="Savings">Savings</option>
                                                <option value="Business">Business</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label>SWIFT Code</label>
                                            <input type="text" class="form-control" id="utxn_edit_swiftcode" name="swiftcode">
                                        </div>
                                        <div class="col-md-4">
                                            <label>IBAN</label>
                                            <input type="text" class="form-control" id="utxn_edit_iban" name="iban">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Routing Number</label>
                                            <input type="text" class="form-control" id="utxn_edit_routingnumber" name="routingnumber">
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Country</label>
                                        <input type="text" class="form-control" id="utxn_edit_country" name="country">
                                    </div>
                                    <div class="col-md-6">
                                        <label>City</label>
                                        <input type="text" class="form-control" id="utxn_edit_city" name="city">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Zip Code</label>
                                        <input type="text" class="form-control" id="utxn_edit_zipcode" name="zipcode">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Bank Address</label>
                                        <input type="text" class="form-control" id="utxn_edit_bankaddress" name="bankaddress">
                                    </div>
                                </div>
                            </div>

                            {{-- Additional Details --}}
                            <div class="border-top pt-3 mb-3">
                                <h6 class="font-weight-bold mb-3">Additional Information</h6>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>Balance After Transaction</label>
                                        <input type="number" step="0.01" class="form-control" id="utxn_edit_bal" name="bal">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Amount to Deduct</label>
                                        <input type="number" step="0.01" class="form-control" id="utxn_edit_to_deduct" name="to_deduct">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label>Payment Details</label>
                                        <textarea class="form-control" id="utxn_edit_paydetails" name="paydetails" rows="2"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <label>Admin Note</label>
                                        <textarea class="form-control" id="utxn_edit_admin_note" name="admin_note" rows="2" placeholder="Internal notes (not visible to user)"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-save mr-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ===================== QUICK PROCESS MODAL ===================== --}}
        <div class="modal fade" id="userTxnProcessModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('pwithdrawal') }}">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ route('viewuser', $user->id) }}">
                        <input type="hidden" name="id" id="utxn_process_id">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title"><i class="fa fa-cog mr-2"></i>Quick Process Transaction</h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-light border mb-3">
                                <strong>Transaction:</strong> <span id="utxn_process_txn_id"></span><br>
                                <strong>Amount:</strong> <span id="utxn_process_amount"></span><br>
                                <strong>Beneficiary:</strong> <span id="utxn_process_beneficiary"></span>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Action <span class="text-danger">*</span></label>
                                <select class="form-control" name="action" id="utxn_process_action" required onchange="utxnToggleEmailFields()">
                                    <option value="">-- Select Action --</option>
                                    <option value="Paid">Paid / Processed</option>
                                    <option value="Reject">Reject</option>
                                    <option value="On-hold">On-hold</option>
                                    <option value="Under-review">Under Review</option>
                                    <option value="Frozen">Frozen</option>
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Failed">Failed</option>
                                    <option value="Error">Error</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Reversed">Reversed</option>
                                    <option value="Refunded">Refunded</option>
                                    <option value="Expired">Expired</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-bold">Date</label>
                                <input type="datetime-local" name="date" class="form-control" value=" ">
                            </div>

                            {{-- Email options for reject-type actions --}}
                            <div id="utxnEmailSection" style="display: none;">
                                <div class="form-group">
                                    <label class="font-weight-bold">Send Notification Email?</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="emailsend" id="utxn_email_yes" value="true">
                                            <label class="form-check-label" for="utxn_email_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="emailsend" id="utxn_email_no" value="false" checked>
                                            <label class="form-check-label" for="utxn_email_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="Email subject">
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-bold">Reason / Message</label>
                                    <textarea class="form-control" name="reason" rows="3" placeholder="Reason for this action..."></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check mr-1"></i> Process
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Delete form for user transactions --}}
        <form id="deleteUserTxnForm" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
            <input type="hidden" name="redirect_to" value="{{ route('viewuser', $user->id) }}">
        </form>

        {{-- ===================== TRANSACTION SCRIPTS ===================== --}}
        <script>
            // ---- Edit Modal ----
            function openUserTxnEditModal(txn) {
                document.getElementById('userTxnEditForm').action = "{{ url('admin/dashboard/withdrawals') }}/" + txn.id;
                document.getElementById('utxn_edit_id').value = txn.id;
                document.getElementById('utxn_edit_txn_id').value = txn.txn_id || '';
                document.getElementById('utxn_edit_amount').value = txn.amount || '';
                document.getElementById('utxn_edit_type').value = txn.type || 'Debit';
                document.getElementById('utxn_edit_status').value = txn.status || 'Pending';
                document.getElementById('utxn_edit_payment_mode').value = txn.payment_mode || '';
                document.getElementById('utxn_edit_description').value = txn.Description || '';

                if (txn.created_at) {
                    var d = new Date(txn.created_at);
                    document.getElementById('utxn_edit_date').value = d.toISOString().slice(0, 16);
                }

                // Sender
                document.getElementById('utxn_edit_sender_name').value = txn.sender_name || '';
                document.getElementById('utxn_edit_sender_bank').value = txn.sender_bank || '';
                document.getElementById('utxn_edit_sender_account_number').value = txn.sender_account_number || '';
                document.getElementById('utxn_edit_sender_bank_address').value = txn.sender_bank_address || '';
                document.getElementById('utxn_edit_reference_number').value = txn.reference_number || '';

                // Beneficiary
                document.getElementById('utxn_edit_accountname').value = txn.accountname || '';
                document.getElementById('utxn_edit_accountnumber').value = txn.accountnumber || '';
                document.getElementById('utxn_edit_bankname').value = txn.bankname || '';
                document.getElementById('utxn_edit_accounttype').value = txn.Accounttype || '';
                document.getElementById('utxn_edit_swiftcode').value = txn.swiftcode || '';
                document.getElementById('utxn_edit_iban').value = txn.iban || '';
                document.getElementById('utxn_edit_routingnumber').value = txn.routingnumber || '';

                // Location
                document.getElementById('utxn_edit_country').value = txn.country || '';
                document.getElementById('utxn_edit_city').value = txn.city || '';
                document.getElementById('utxn_edit_zipcode').value = txn.zipcode || '';
                document.getElementById('utxn_edit_bankaddress').value = txn.bankaddress || '';

                // Additional
                document.getElementById('utxn_edit_bal').value = txn.bal || '';
                document.getElementById('utxn_edit_to_deduct').value = txn.to_deduct || '';
                document.getElementById('utxn_edit_paydetails').value = txn.paydetails || '';
                document.getElementById('utxn_edit_admin_note').value = txn.admin_note || '';

                utxnToggleSenderFields();
                utxnTogglePaymentFields();

                $('#userTxnEditModal').modal('show');
            }

            function utxnToggleSenderFields() {
                var type = document.getElementById('utxn_edit_type').value;
                var section = document.getElementById('utxnSenderFieldsSection');
                var header = document.getElementById('utxnSenderHeaderText');
                var nameL = document.getElementById('utxnSenderNameLabel');
                var bankL = document.getElementById('utxnSenderBankLabel');
                var acctL = document.getElementById('utxnSenderAccountLabel');
                var addrL = document.getElementById('utxnSenderAddressLabel');

                section.style.display = 'block';
                if (type === 'Credit') {
                    header.textContent = 'Sender Information';
                    nameL.textContent = 'Sender Name';
                    bankL.textContent = 'Sender Bank';
                    acctL.textContent = 'Sender Account Number';
                    addrL.textContent = 'Sender Bank Address';
                } else {
                    header.textContent = 'Recipient Information';
                    nameL.textContent = 'Recipient Name';
                    bankL.textContent = 'Recipient Bank';
                    acctL.textContent = 'Recipient Account Number';
                    addrL.textContent = 'Recipient Bank Address';
                }
            }

            function utxnTogglePaymentFields() {
                var pm = document.getElementById('utxn_edit_payment_mode').value;
                var bankFields = document.getElementById('utxnBankTransferFields');
                var label = document.getElementById('utxnAccountNumberLabel');

                if (['Wire Transfer', 'Internal Transfer', 'Domestic Transfer', 'Local transfer'].includes(pm)) {
                    bankFields.style.display = 'block';
                    label.innerHTML = 'Account Number <span class="text-danger">*</span>';
                } else if (['Bitcoin', 'Ethereum', 'USDT', 'Cryptocurrency'].includes(pm)) {
                    bankFields.style.display = 'none';
                    label.innerHTML = 'Wallet Address <span class="text-danger">*</span>';
                } else if (['PayPal', 'Skrill', 'Payoneer', 'TransferWise'].includes(pm)) {
                    bankFields.style.display = 'none';
                    label.innerHTML = 'Email Address <span class="text-danger">*</span>';
                } else if (pm === 'Western Union') {
                    bankFields.style.display = 'none';
                    label.innerHTML = 'Recipient Name <span class="text-danger">*</span>';
                } else {
                    bankFields.style.display = 'block';
                    label.innerHTML = 'Account Number <span class="text-danger">*</span>';
                }
            }

            // ---- Quick Process Modal ----
            function openQuickProcessModal(id, txnId, amount, beneficiary) {
                document.getElementById('utxn_process_id').value = id;
                document.getElementById('utxn_process_txn_id').textContent = txnId;
                document.getElementById('utxn_process_amount').textContent = amount;
                document.getElementById('utxn_process_beneficiary').textContent = beneficiary;
                document.getElementById('utxn_process_action').value = '';
                document.getElementById('utxnEmailSection').style.display = 'none';
                $('#userTxnProcessModal').modal('show');
            }

            function utxnToggleEmailFields() {
                var action = document.getElementById('utxn_process_action').value;
                var emailSection = document.getElementById('utxnEmailSection');
                if (['Reject', 'Failed', 'Cancelled', 'Reversed', 'Refunded', 'Expired'].includes(action)) {
                    emailSection.style.display = 'block';
                } else {
                    emailSection.style.display = 'none';
                }
            }

            // ---- Delete ----
            function confirmDeleteUserTxn(id, txnId) {
                if (confirm('Are you sure you want to delete transaction ' + txnId + '?\n\nThis action cannot be undone!')) {
                    var form = document.getElementById('deleteUserTxnForm');
                    form.action = "{{ url('admin/dashboard/withdrawals') }}/" + id;
                    form.submit();
                }
            }

            // ---- DataTable init ----
            $(document).ready(function() {
                if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#UserTxnTable')) {
                    $('#UserTxnTable').DataTable({
                        "order": [[0, "desc"]],
                        "pageLength": 10,
                        "language": {
                            "emptyTable": "No transactions found"
                        }
                    });
                }
            });
        </script>

        {{-- User Actions Launcher Modal + scrollable action modals --}}
        <style>
            /* All modals on this page: centered, body scrolls, header/footer stay fixed */
            .modal.fade .modal-dialog.modal-dialog-scrollable {
                max-height: calc(100vh - 1rem);
                margin: .5rem auto;
            }
            .modal.fade .modal-dialog.modal-dialog-scrollable .modal-content {
                max-height: calc(100vh - 1rem);
            }
            .modal.fade .modal-dialog.modal-dialog-scrollable .modal-body {
                overflow-y: auto;
                -webkit-overflow-scrolling: touch;
            }
            #userActionsModal .modal-dialog {
                max-width: 720px;
            }
            #userActionsModal .modal-body {
                padding: 1.25rem;
            }
            .user-actions-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 12px;
            }
            .user-action-btn {
                display: flex;
                align-items: center;
                gap: 10px;
                width: 100%;
                text-align: left;
                padding: 12px 14px;
                border: 1px solid rgba(0,0,0,.08);
                border-radius: 10px;
                background: #fff;
                color: inherit;
                transition: background .2s, border-color .2s, transform .2s;
                white-space: normal;
                line-height: 1.35;
            }
            .user-action-btn:hover {
                background: #f8f9fa;
                border-color: rgba(45, 134, 89, .35);
                transform: translateY(-1px);
                text-decoration: none;
                color: inherit;
            }
            .user-action-btn i {
                width: 18px;
                text-align: center;
                flex-shrink: 0;
            }
            .user-action-btn.is-danger { color: #c0392b; border-color: rgba(192,57,43,.2); }
            .user-action-btn.is-success { color: #2d8659; border-color: rgba(45,134,89,.25); }
            .user-actions-section {
                margin-bottom: 1.25rem;
            }
            .user-actions-section h6 {
                font-size: .78rem;
                letter-spacing: .06em;
                text-transform: uppercase;
                color: #6c757d;
                margin-bottom: .65rem;
                font-weight: 700;
            }
            @media (max-width: 575px) {
                .modal.fade .modal-dialog.modal-dialog-scrollable {
                    margin: .5rem;
                    max-width: calc(100% - 1rem);
                }
                #userActionsModal .modal-dialog {
                    max-width: calc(100% - 1rem);
                }
                .user-actions-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="modal fade" id="userActionsModal" tabindex="-1" role="dialog" aria-labelledby="userActionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-1" id="userActionsModalLabel">
                                <i class="fa fa-bolt text-primary"></i> User Actions
                            </h5>
                            <small class="text-muted">{{ $user->name }} {{ $user->lastname }} · {{ $user->email }}</small>
                        </div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="user-actions-section">
                            <h6>Account &amp; Funds</h6>
                            <div class="user-actions-grid">
                                @if (!$user->email_verified_at)
                                    <a href="{{ url('admin/dashboard/email-verify') }}/{{ $user->id }}" class="user-action-btn">
                                        <i class="fa fa-envelope"></i> Verify Email
                                    </a>
                                @endif
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('topupModal')">
                                    <i class="fa fa-money"></i> Fund / Debit Account
                                </button>
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('bulkTransactionModal')">
                                    <i class="fa fa-list"></i> Generate Bulk Transactions
                                </button>
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('accountLimitsModal')">
                                    <i class="fa fa-sliders"></i> Manage Account Limits
                                </button>
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('transferCodesModal')">
                                    <i class="fa fa-key"></i> Manage Transfer Codes
                                </button>
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('accountStatusModal')">
                                    <i class="fa fa-user"></i> Change Account Status
                                </button>
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('clearacctModal')">
                                    <i class="fa fa-eraser"></i> Clear Account
                                </button>
                            </div>
                        </div>

                        <div class="user-actions-section">
                            <h6>Security &amp; Access</h6>
                            <div class="user-actions-grid">
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('resetpswdModal')">
                                    <i class="fa fa-lock"></i> Reset Password
                                </button>
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('TradingModal')">
                                    <i class="fa fa-image"></i> Change Profile Pics
                                </button>
                                <button type="button" class="user-action-btn is-success" onclick="openUserActionModal('switchuserModal')">
                                    <i class="fa fa-sign-in"></i> Login as {{ $user->name }}
                                </button>
                                <a href="{{ route('loginactivity', $user->id) }}" class="user-action-btn">
                                    <i class="fa fa-history"></i> Login Activity
                                </a>
                                @if ($user->allowtransfer == 1)
                                    <a href="{{ url('admin/dashboard/undormant') }}/{{ $user->id }}" class="user-action-btn">
                                        <i class="fa fa-bed"></i> Turn Off Dormant Account
                                    </a>
                                @else
                                    <a href="{{ url('admin/dashboard/dormant') }}/{{ $user->id }}" class="user-action-btn">
                                        <i class="fa fa-bed"></i> Turn On Dormant Account
                                    </a>
                                @endif
                            </div>
                        </div>

                        @if ($card)
                        <div class="user-actions-section">
                            <h6>Card</h6>
                            <div class="user-actions-grid">
                                @if ($card->status == 'active')
                                    <a href="{{ route('admin.cards.view', $card->id) }}" class="user-action-btn">
                                        <i class="fa fa-credit-card"></i> Manage Card
                                    </a>
                                @elseif ($card->status == 'pending')
                                    <a href="{{ route('admin.cards.pending') }}" class="user-action-btn">
                                        <i class="fa fa-credit-card"></i> Review Card Application
                                    </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="user-actions-section">
                            <h6>Communication &amp; Profile</h6>
                            <div class="user-actions-grid">
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('edituser')">
                                    <i class="fa fa-edit"></i> Edit User
                                </button>
                                <button type="button" class="user-action-btn" onclick="openUserActionModal('sendmailtooneuserModal')">
                                    <i class="fa fa-envelope"></i> Send Email
                                </button>
                            </div>
                        </div>

                        <div class="user-actions-section">
                            <h6>Account Control</h6>
                            <div class="user-actions-grid">
                                @if(in_array($user->account_status ?? '', ['blocked', 'permanently_banned']))
                                    <button type="button" class="user-action-btn is-success" onclick="openUserActionModal('unblockUserModal')">
                                        <i class="fa fa-check-circle"></i> Unblock Account
                                    </button>
                                @else
                                    <button type="button" class="user-action-btn is-danger" onclick="openUserActionModal('blockUserModal')">
                                        <i class="fa fa-ban"></i> Block / Ban Account
                                    </button>
                                @endif
                                @if ($user->status == null || $user->status == 'blocked')
                                    <a href="{{ url('admin/dashboard/uunblock') }}/{{ $user->id }}" class="user-action-btn is-success">
                                        <i class="fa fa-unlock"></i> Unblock User Status
                                    </a>
                                @else
                                    <a href="{{ url('admin/dashboard/uublock') }}/{{ $user->id }}" class="user-action-btn is-danger">
                                        <i class="fa fa-lock"></i> Block User Status
                                    </a>
                                @endif
                                <button type="button" class="user-action-btn is-danger" onclick="openUserActionModal('deleteModal')">
                                    <i class="fa fa-trash"></i> Delete {{ $user->name }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function openUserActionModal(targetModalId) {
                $('#userActionsModal').modal('hide');
                setTimeout(function () {
                    $('#' + targetModalId).modal('show');
                }, 250);
            }
        </script>

        {{-- Balance Edit Toggle --}}
        <script>
            function toggleBalanceEdit() {
                var display = document.getElementById('balanceDisplay');
                var edit = document.getElementById('balanceEdit');
                var text = document.getElementById('balanceEditText');
                if (edit.style.display === 'none') {
                    edit.style.display = 'block';
                    display.style.display = 'none';
                    text.textContent = 'Cancel';
                } else {
                    edit.style.display = 'none';
                    display.style.display = 'block';
                    text.textContent = 'Edit Balances';
                }
            }
        </script>

        @include('admin.Users.users_actions')
    @endsection

