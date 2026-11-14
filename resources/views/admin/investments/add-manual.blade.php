<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $text = 'dark';
} else {
    $text = 'light';
}
?>
@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1">Add Manual Investment</h1>
                </div>
                
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

                <div class="mb-3">
                    <a href="{{ route('admin.investments.user-investments') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Investments
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Investment Details</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.investments.store-manual') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label>Select User <span class="text-danger">*</span></label>
                                        <select name="user_id" class="form-control" required id="userSelect">
                                            <option value="">-- Select User --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email }}) - Balance: ${{ number_format($user->account_bal, 2) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Select Plan <span class="text-danger">*</span></label>
                                        <select name="plan_id" class="form-control" required id="planSelect">
                                            <option value="">-- Select Plan --</option>
                                            @foreach($plans as $plan)
                                                <option value="{{ $plan->id }}" 
                                                        data-min="{{ $plan->min_price }}"
                                                        data-max="{{ $plan->max_price }}"
                                                        data-roi-type="{{ $plan->roi_type }}"
                                                        data-roi-value="{{ $plan->roi_value }}"
                                                        data-duration="{{ $plan->duration }}"
                                                        data-duration-type="{{ $plan->duration_type }}">
                                                    {{ $plan->name }} 
                                                    (${{ number_format($plan->min_price) }} - ${{ number_format($plan->max_price) }}) 
                                                    - ROI: {{ $plan->roi_type == 'percentage' ? $plan->roi_value . '%' : '$' . $plan->roi_value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('plan_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-muted" id="planInfo"></small>
                                    </div>

                                    <div class="form-group">
                                        <label>Investment Amount ($) <span class="text-danger">*</span></label>
                                        <input type="number" name="amount" class="form-control" 
                                               value="{{ old('amount') }}" required min="0" step="0.01"
                                               placeholder="Enter investment amount" id="investAmount">
                                        @error('amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-muted" id="amountHelp"></small>
                                    </div>

                                    <div class="form-group">
                                        <label>ROI Amount to Credit ($) <span class="text-danger">*</span></label>
                                        <input type="number" name="roi_amount" class="form-control" 
                                               value="{{ old('roi_amount') }}" required min="0" step="0.01"
                                               placeholder="ROI amount" id="roiAmount">
                                        @error('roi_amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-muted">The ROI amount to immediately credit to user's account balance</small>
                                    </div>

                                    <div class="form-group">
                                        <label>Add to User Balance? <span class="text-danger">*</span></label>
                                        <select name="add_to_balance" class="form-control" required>
                                            <option value="yes">Yes - Add ROI to account balance immediately</option>
                                            <option value="no">No - Just record the investment</option>
                                        </select>
                                        @error('add_to_balance')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                        <small class="text-muted">Choose "Yes" to immediately credit the ROI amount to user's account</small>
                                    </div>

                                    <div class="alert alert-info">
                                        <strong><i class="fa fa-info-circle"></i> Note:</strong>
                                        <ul class="mb-0">
                                            <li>This will create an investment record for the selected user</li>
                                            <li>If "Add to Balance" is Yes, the ROI amount will be added to their account immediately</li>
                                            <li>The investment will be marked as active</li>
                                            <li>User will receive email notification</li>
                                        </ul>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fa fa-save"></i> Add Investment
                                        </button>
                                        <a href="{{ route('admin.investments.user-investments') }}" class="btn btn-secondary btn-lg">
                                            Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Quick Tips</h4>
                            </div>
                            <div class="card-body">
                                <h5>When to use this?</h5>
                                <ul class="small">
                                    <li>Manual bonus investments for VIP clients</li>
                                    <li>Promotional investment credits</li>
                                    <li>Correction of investment records</li>
                                    <li>Special arrangements with users</li>
                                </ul>

                                <h5 class="mt-3">Best Practices:</h5>
                                <ul class="small">
                                    <li>Verify user details before adding</li>
                                    <li>Document the reason for manual investment</li>
                                    <li>Communicate with user after adding</li>
                                    <li>Keep records for accounting</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('planSelect').addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const min = selected.dataset.min;
                const max = selected.dataset.max;
                const roiType = selected.dataset.roiType;
                const roiValue = selected.dataset.roiValue;
                const duration = selected.dataset.duration;
                const durationType = selected.dataset.durationType;
                
                if (this.value) {
                    document.getElementById('planInfo').textContent = 
                        `Duration: ${duration} ${durationType}, ROI: ${roiType === 'percentage' ? roiValue + '%' : '$' + roiValue}`;
                    document.getElementById('amountHelp').textContent = 
                        `Must be between $${parseFloat(min).toFixed(2)} and $${parseFloat(max).toFixed(2)}`;
                } else {
                    document.getElementById('planInfo').textContent = '';
                    document.getElementById('amountHelp').textContent = '';
                }
            });

            document.getElementById('investAmount').addEventListener('input', function() {
                const amount = parseFloat(this.value) || 0;
                const planSelect = document.getElementById('planSelect');
                const selected = planSelect.options[planSelect.selectedIndex];
                
                if (planSelect.value && amount > 0) {
                    const roiType = selected.dataset.roiType;
                    const roiValue = parseFloat(selected.dataset.roiValue) || 0;
                    
                    let calculatedRoi = 0;
                    if (roiType === 'percentage') {
                        calculatedRoi = (amount * roiValue) / 100;
                    } else {
                        calculatedRoi = roiValue;
                    }
                    
                    document.getElementById('roiAmount').value = calculatedRoi.toFixed(2);
                }
            });
        </script>
@endsection
