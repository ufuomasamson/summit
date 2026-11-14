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
                    <h1 class="title1">Edit Investment Plan</h1>
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
                                <form method="POST" action="{{ route('admin.investments.update-plan', $plan->id) }}">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-group">
                                        <label>Plan Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" 
                                               value="{{ old('name', $plan->name) }}" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Minimum Investment ($)</label>
                                                <input type="number" name="min_price" class="form-control" 
                                                       value="{{ old('min_price', $plan->min_price) }}" required step="0.01">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Maximum Investment ($)</label>
                                                <input type="number" name="max_price" class="form-control" 
                                                       value="{{ old('max_price', $plan->max_price) }}" required step="0.01">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ROI Type</label>
                                                <select name="roi_type" class="form-control" required>
                                                    <option value="percentage" {{ $plan->roi_type == 'percentage' ? 'selected' : '' }}>
                                                        Percentage (%)
                                                    </option>
                                                    <option value="fixed" {{ $plan->roi_type == 'fixed' ? 'selected' : '' }}>
                                                        Fixed Amount ($)
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>ROI Value</label>
                                                <input type="number" name="roi_value" class="form-control" 
                                                       value="{{ old('roi_value', $plan->roi_value) }}" required step="0.01">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Duration</label>
                                                <input type="number" name="duration" class="form-control" 
                                                       value="{{ old('duration', $plan->duration) }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Duration Type</label>
                                                <select name="duration_type" class="form-control" required>
                                                    <option value="days" {{ $plan->duration_type == 'days' ? 'selected' : '' }}>Days</option>
                                                    <option value="weeks" {{ $plan->duration_type == 'weeks' ? 'selected' : '' }}>Weeks</option>
                                                    <option value="months" {{ $plan->duration_type == 'months' ? 'selected' : '' }}>Months</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Return Capital After Duration?</label>
                                                <select name="return_capital" class="form-control" required>
                                                    <option value="1" {{ $plan->return_capital ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ !$plan->return_capital ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="d-block">Plan Status</label>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_active" 
                                                           name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_active">Active</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fa fa-save"></i> Update Plan
                                        </button>
                                        <a href="{{ route('admin.investments.plans') }}" class="btn btn-secondary btn-lg">
                                            Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
