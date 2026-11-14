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
                    <h1 class="title1">Create Investment Plan</h1>
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
                    <a href="{{ route('admin.investments.plans') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to Plans
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Plan Details</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.investments.store-plan') }}">
                                    @csrf

                                    <div class="form-group">
                                        <label>Plan Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" 
                                               value="{{ old('name') }}" required
                                               placeholder="e.g., Gold Plan, Premium Package">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Minimum Investment ($) <span class="text-danger">*</span></label>
                                                <input type="number" name="min_price" class="form-control" 
                                                       value="{{ old('min_price') }}" required min="0" step="0.01"
                                                       placeholder="100">
                                                @error('min_price')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Maximum Investment ($) <span class="text-danger">*</span></label>
                                                <input type="number" name="max_price" class="form-control" 
                                                       value="{{ old('max_price') }}" required min="0" step="0.01"
                                                       placeholder="10000">
                                                @error('max_price')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ROI Type <span class="text-danger">*</span></label>
                                                <select name="roi_type" class="form-control" required id="roiType">
                                                    <option value="percentage" {{ old('roi_type') == 'percentage' ? 'selected' : '' }}>
                                                        Percentage (%)
                                                    </option>
                                                    <option value="fixed" {{ old('roi_type') == 'fixed' ? 'selected' : '' }}>
                                                        Fixed Amount ($)
                                                    </option>
                                                </select>
                                                @error('roi_type')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ROI Value <span class="text-danger">*</span></label>
                                                <input type="number" name="roi_value" class="form-control" 
                                                       value="{{ old('roi_value') }}" required min="0" step="0.01"
                                                       placeholder="10" id="roiValue">
                                                <small class="text-muted" id="roiHelp">Enter percentage value (e.g., 10 for 10%)</small>
                                                @error('roi_value')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Duration <span class="text-danger">*</span></label>
                                                <input type="number" name="duration" class="form-control" 
                                                       value="{{ old('duration', 30) }}" required min="1"
                                                       placeholder="30">
                                                @error('duration')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Duration Type <span class="text-danger">*</span></label>
                                                <select name="duration_type" class="form-control" required>
                                                    <option value="days" {{ old('duration_type') == 'days' ? 'selected' : '' }}>Days</option>
                                                    <option value="weeks" {{ old('duration_type') == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                                    <option value="months" {{ old('duration_type') == 'months' ? 'selected' : '' }}>Months</option>
                                                </select>
                                                @error('duration_type')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Return Capital After Duration? <span class="text-danger">*</span></label>
                                                <select name="return_capital" class="form-control" required>
                                                    <option value="1" {{ old('return_capital') == '1' ? 'selected' : '' }}>
                                                        Yes - Return capital + ROI
                                                    </option>
                                                    <option value="0" {{ old('return_capital') == '0' ? 'selected' : '' }}>
                                                        No - Only ROI
                                                    </option>
                                                </select>
                                                @error('return_capital')
                                                    <small class="text-danger">{{ $message }}</small>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="d-block">Plan Status</label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_active" 
                                                           name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_active">
                                                        Active (visible to users)
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fa fa-save"></i> Create Investment Plan
                                        </button>
                                        <a href="{{ route('admin.investments.plans') }}" class="btn btn-secondary btn-lg">
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
                                <h4 class="card-title">Help & Tips</h4>
                            </div>
                            <div class="card-body">
                                <h5>ROI Type:</h5>
                                <ul class="small">
                                    <li><strong>Percentage:</strong> ROI calculated as % of investment amount</li>
                                    <li><strong>Fixed:</strong> Fixed dollar amount regardless of investment</li>
                                </ul>

                                <h5 class="mt-3">Return Capital:</h5>
                                <ul class="small">
                                    <li><strong>Yes:</strong> Users get back their investment + ROI</li>
                                    <li><strong>No:</strong> Users only receive ROI (capital consumed)</li>
                                </ul>

                                <h5 class="mt-3">Example Plans:</h5>
                                <div class="alert alert-info small">
                                    <strong>Basic Plan:</strong><br>
                                    $100-$1,000<br>
                                    10% ROI<br>
                                    30 Days<br>
                                    Return Capital: Yes
                                </div>
                                <div class="alert alert-success small">
                                    <strong>Premium Plan:</strong><br>
                                    $5,000-$50,000<br>
                                    $500 Fixed ROI<br>
                                    90 Days<br>
                                    Return Capital: No
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('roiType').addEventListener('change', function() {
                const helpText = document.getElementById('roiHelp');
                if (this.value === 'percentage') {
                    helpText.textContent = 'Enter percentage value (e.g., 10 for 10%)';
                } else {
                    helpText.textContent = 'Enter fixed dollar amount (e.g., 500 for $500)';
                }
            });
        </script>
@endsection
