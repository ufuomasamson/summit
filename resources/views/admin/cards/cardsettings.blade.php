@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1">Virtual Card System Settings</h1>
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
                
                <div class="mb-5 row">
                    <div class="col-md-8">
                        <div class="card shadow p-4">
                            <div class="card-header">
                                <h4 class="card-title">Card Issuance Fees & Limits</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.cards.settings.update') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    
                                    <h5 class="mb-3">Issuance Fees</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Standard Card Fee</label>
                                                <input type="number" name="standard_fee" step="0.01" 
                                                    value="{{ $cardSettings->standard_fee }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Gold Card Fee</label>
                                                <input type="number" name="gold_fee" step="0.01" 
                                                    value="{{ $cardSettings->gold_fee }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Platinum Card Fee</label>
                                                <input type="number" name="platinum_fee" step="0.01" 
                                                    value="{{ $cardSettings->platinum_fee }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Black Card Fee</label>
                                                <input type="number" name="black_fee" step="0.01" 
                                                    value="{{ $cardSettings->black_fee }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="mb-3">Operating Fees</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Monthly Maintenance Fee</label>
                                                <input type="number" name="monthly_fee" step="0.01" 
                                                    value="{{ $cardSettings->monthly_fee }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Top-up Fee (%)</label>
                                                <input type="number" name="topup_fee_percentage" step="0.01" 
                                                    value="{{ $cardSettings->topup_fee_percentage }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="mb-3">Daily Limits</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Minimum Daily Limit</label>
                                                <input type="number" name="min_daily_limit" step="0.01" 
                                                    value="{{ $cardSettings->min_daily_limit }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Maximum Daily Limit</label>
                                                <input type="number" name="max_daily_limit" step="0.01" 
                                                    value="{{ $cardSettings->max_daily_limit }}" 
                                                    class="form-control" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h5 class="mb-3">System Control</h5>
                                    <div class="form-group">
                                        <label>Card System Status</label>
                                        <select name="cards_enabled" class="form-control" required>
                                            <option value="1" {{ $cardSettings->cards_enabled ? 'selected' : '' }}>Enabled</option>
                                            <option value="0" {{ !$cardSettings->cards_enabled ? 'selected' : '' }}>Disabled</option>
                                        </select>
                                        <small class="form-text text-muted">
                                            When disabled, users cannot apply for new cards
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label>Admin Notes</label>
                                        <textarea name="admin_notes" class="form-control" rows="3" 
                                            placeholder="Internal notes about card system settings...">{{ $cardSettings->admin_notes }}</textarea>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-save"></i> Save Settings
                                        </button>
                                        <a href="{{ route('admin.cards.index') }}" class="btn btn-secondary">
                                            <i class="fa fa-arrow-left"></i> Back to Cards
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Quick Stats -->
                        <div class="card shadow p-4 mb-3">
                            <div class="card-body">
                                <h5 class="card-title">System Status</h5>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Card System</span>
                                    <span class="badge badge-{{ $cardSettings->cards_enabled ? 'success' : 'danger' }}">
                                        {{ $cardSettings->cards_enabled ? 'ENABLED' : 'DISABLED' }}
                                    </span>
                                </div>
                                <form action="{{ route('admin.cards.toggle') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-{{ $cardSettings->cards_enabled ? 'danger' : 'success' }} btn-block"
                                        onclick="return confirm('Are you sure you want to {{ $cardSettings->cards_enabled ? 'disable' : 'enable' }} the card system?')">
                                        <i class="fa fa-power-off"></i> 
                                        {{ $cardSettings->cards_enabled ? 'Disable System' : 'Enable System' }}
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Fee Summary -->
                        <div class="card shadow p-4">
                            <div class="card-body">
                                <h5 class="card-title">Current Fees</h5>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Standard</td>
                                        <td class="text-right">{{ $cardSettings->currency }}{{ number_format($cardSettings->standard_fee, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Gold</td>
                                        <td class="text-right">{{ $cardSettings->currency }}{{ number_format($cardSettings->gold_fee, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Platinum</td>
                                        <td class="text-right">{{ $cardSettings->currency }}{{ number_format($cardSettings->platinum_fee, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Black</td>
                                        <td class="text-right">{{ $cardSettings->currency }}{{ number_format($cardSettings->black_fee, 2) }}</td>
                                    </tr>
                                    <tr class="border-top">
                                        <td>Monthly Fee</td>
                                        <td class="text-right">{{ $cardSettings->currency }}{{ number_format($cardSettings->monthly_fee, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Top-up Fee</td>
                                        <td class="text-right">{{ $cardSettings->topup_fee_percentage }}%</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


