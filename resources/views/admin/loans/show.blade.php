@extends('layouts.app')
@section('content')
@include('admin.topmenu')
@include('admin.sidebar')

<?php
$bg = Auth('admin')->User()->dashboard_style == 'light' ? 'light' : 'dark';
$text = $bg == 'light' ? 'dark' : 'light';
?>

<div class="main-panel bg-{{$bg}}">
    <div class="content bg-{{$bg}}">
        <div class="page-inner">
            <!-- Page Header -->
            <div class="mb-4 page-header">
                <h3 class="mb-2 fw-bold text-{{$text}}">Loan Application Details</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-home">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="flaticon-home text-{{$text}}"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow text-{{$text}}"></i>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.loans.index') }}" class="text-{{$text}}">Loans</a>
                    </li>
                    <li class="separator">
                        <i class="flaticon-right-arrow text-{{$text}}"></i>
                    </li>
                    <li class="nav-item">
                        <span class="text-{{$text}}">Application #{{ $loan->id }}</span>
                    </li>
                </ul>
            </div>

            <div class="row">
                <!-- Loan Details -->
                <div class="col-md-8">
                    <div class="card card-round">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="card-title">Loan Information</h4>
                                @if($loan->active == 'Pending')
                                    <span class="badge badge-warning badge-lg">Pending Review</span>
                                @elseif($loan->active == 'Processed')
                                    <span class="badge badge-success badge-lg">Approved</span>
                                @elseif($loan->active == 'yes')
                                    <span class="badge badge-info badge-lg">Active</span>
                                @elseif($loan->active == 'No')
                                    <span class="badge badge-danger badge-lg">Rejected</span>
                                @else
                                    <span class="badge badge-secondary badge-lg">{{ $loan->active }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">Application ID</label>
                                        <p class="font-weight-bold">#{{ $loan->id }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Loan Amount</label>
                                        <p class="font-weight-bold text-success" style="font-size: 1.5rem;">
                                            {{ $loan->user->currency }}{{ number_format($loan->amount, 2) }}
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Loan Type</label>
                                        <p class="font-weight-bold">{{ $loan->plan_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="text-muted small">Duration</label>
                                        <p class="font-weight-bold">
                                            @if($loan->duration)
                                                @if($loan->duration < 12)
                                                    {{ $loan->duration }} {{ $loan->duration == 1 ? 'Month' : 'Months' }}
                                                @elseif($loan->duration % 12 == 0)
                                                    {{ $loan->duration / 12 }} {{ $loan->duration == 12 ? 'Year' : 'Years' }}
                                                @else
                                                    {{ floor($loan->duration / 12) }} {{ floor($loan->duration / 12) == 1 ? 'Year' : 'Years' }} 
                                                    {{ $loan->duration % 12 }} {{ ($loan->duration % 12) == 1 ? 'Month' : 'Months' }}
                                                @endif
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Monthly Income Range</label>
                                        <p class="font-weight-bold">{{ $loan->income_range ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="text-muted small">Application Date</label>
                                        <p class="font-weight-bold">{{ $loan->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            @if($loan->loan_purpose)
                            <div class="mb-3">
                                <label class="text-muted small">Loan Purpose</label>
                                <div class="alert alert-light">
                                    {{ $loan->loan_purpose }}
                                </div>
                            </div>
                            @endif

                            <!-- Loan Calculation -->
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Loan Summary</h5>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <small class="text-muted">Principal Amount</small>
                                                <p class="font-weight-bold">{{ $loan->user->currency }}{{ number_format($loan->amount, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <small class="text-muted">Interest Rate (est.)</small>
                                                <p class="font-weight-bold">5% per annum</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-2">
                                                <small class="text-muted">Est. Monthly Payment</small>
                                                <p class="font-weight-bold text-primary">
                                                    @php
                                                        $rate = 0.05 / 12;
                                                        $months = $loan->duration ?? 12;
                                                        $monthly = $loan->amount * ($rate * pow(1 + $rate, $months)) / (pow(1 + $rate, $months) - 1);
                                                    @endphp
                                                    {{ $loan->user->currency }}{{ number_format($monthly, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($loan->active == 'No' && $loan->rejection_reason)
                            <div class="alert alert-danger mt-3">
                                <h6 class="alert-heading"><i class="fa fa-times-circle"></i> Rejection Reason</h6>
                                <p class="mb-0">{{ $loan->rejection_reason }}</p>
                            </div>
                            @endif
                        </div>
                        
                        @if($loan->active == 'Pending')
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-danger mr-2" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fa fa-times"></i> Reject Application
                                </button>
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#approveModal">
                                    <i class="fa fa-check"></i> Approve Application
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div class="col-md-4">
                    <div class="card card-round">
                        <div class="card-header">
                            <h4 class="card-title">Applicant Information</h4>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if($loan->user->profile_photo_path)
                                    <img src="{{ asset('storage/app/public/photos/' . $loan->user->profile_photo_path) }}" 
                                         alt="{{ $loan->user->name }}" 
                                         class="avatar avatar-xxl mb-3">
                                @else
                                    <div class="avatar avatar-xxl bg-primary text-white mb-3" style="font-size: 2rem;">
                                        {{ substr($loan->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <h4 class="font-weight-bold">{{ $loan->user->name }}</h4>
                                <p class="text-muted">{{ $loan->user->email }}</p>
                            </div>

                            <div class="separator-dashed mb-3"></div>

                            <div class="mb-3">
                                <label class="text-muted small">Account Number</label>
                                <p class="font-weight-bold">{{ $loan->user->acct_no ?? 'N/A' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Account Balance</label>
                                <p class="font-weight-bold text-success">{{ $loan->user->currency }}{{ number_format($loan->user->balance, 2) }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Phone</label>
                                <p class="font-weight-bold">{{ $loan->user->phone ?? 'N/A' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Address</label>
                                <p class="font-weight-bold">
                                    {{ $loan->user->address ?? 'Not provided' }}<br>
                                    @if($loan->user->city){{ $loan->user->city }}, @endif
                                    @if($loan->user->state){{ $loan->user->state }} @endif
                                    @if($loan->user->zip_code){{ $loan->user->zip_code }}@endif
                                </p>
                            </div>

                            <div class="separator-dashed mb-3"></div>

                            <div class="mb-3">
                                <label class="text-muted small">Member Since</label>
                                <p class="font-weight-bold">{{ $loan->user->created_at ? $loan->user->created_at->format('M d, Y') : 'N/A' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small">Account Status</label>
                                <p>
                                    @if($loan->user->email_verified_at)
                                        <span class="badge badge-success">Verified</span>
                                    @else
                                        <span class="badge badge-warning">Unverified</span>
                                    @endif
                                </p>
                            </div>

                            <a href="{{ route('manageusers') }}" class="btn btn-primary btn-block">
                                <i class="fa fa-user"></i> View User Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa fa-check-circle"></i> Approve Loan Application</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.loans.approve', $loan->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Approval Confirmation</h6>
                        <p>You are about to approve this loan application. Please review the details:</p>
                    </div>
                    
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Applicant</th>
                            <td>{{ $loan->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Loan Amount</th>
                            <td><strong class="text-success">{{ $loan->user->currency }}{{ number_format($loan->amount, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Loan Type</th>
                            <td>{{ $loan->plan_name }}</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>
                                @if($loan->duration)
                                    @if($loan->duration < 12)
                                        {{ $loan->duration }} {{ $loan->duration == 1 ? 'Month' : 'Months' }}
                                    @elseif($loan->duration % 12 == 0)
                                        {{ $loan->duration / 12 }} {{ $loan->duration == 12 ? 'Year' : 'Years' }}
                                    @else
                                        {{ floor($loan->duration / 12) }} {{ floor($loan->duration / 12) == 1 ? 'Year' : 'Years' }} 
                                        {{ $loan->duration % 12 }} Months
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Current Balance</th>
                            <td>{{ $loan->user->currency }}{{ number_format($loan->user->balance, 2) }}</td>
                        </tr>
                        <tr>
                            <th>New Balance</th>
                            <td><strong class="text-primary">{{ $loan->user->currency }}{{ number_format($loan->user->balance + $loan->amount, 2) }}</strong></td>
                        </tr>
                    </table>

                    <div class="alert alert-success">
                        <p class="mb-0"><i class="fa fa-info-circle"></i> The loan amount will be immediately credited to the user's account.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check"></i> Confirm Approval
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fa fa-times-circle"></i> Reject Loan Application</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('admin.loans.reject', $loan->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <h6 class="alert-heading">Rejection Notice</h6>
                        <p>Please provide a detailed reason for rejecting this loan application. The applicant will be notified.</p>
                    </div>

                    <div class="form-group">
                        <label for="reason">Reason for Rejection <span class="text-danger">*</span></label>
                        <textarea name="reason" id="reason" class="form-control" rows="5" required placeholder="E.g., Insufficient credit history, incomplete documentation, debt-to-income ratio too high..."></textarea>
                        <small class="form-text text-muted">Please be specific and professional. This will be sent to the applicant.</small>
                    </div>

                    <div class="card bg-light">
                        <div class="card-body">
                            <h6 class="card-title">Application Summary</h6>
                            <p class="mb-1"><strong>Applicant:</strong> {{ $loan->user->name }}</p>
                            <p class="mb-1"><strong>Amount:</strong> {{ $loan->user->currency }}{{ number_format($loan->amount, 2) }}</p>
                            <p class="mb-0"><strong>Type:</strong> {{ $loan->plan_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times"></i> Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
