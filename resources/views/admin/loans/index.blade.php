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
                <h3 class="mb-2 fw-bold text-{{$text}}">{{ $title }}</h3>
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
                        <span class="text-{{$text}}">Loan Management</span>
                    </li>
                </ul>
            </div>

            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-3">
                    <div class="card card-stats card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="text-center icon-big icon-primary bubble-shadow-small">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Loans</p>
                                        <h4 class="card-title">{{ number_format($total_loans) }}</h4>
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
                                    <div class="text-center icon-big icon-warning bubble-shadow-small">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Pending</p>
                                        <h4 class="card-title">{{ number_format($pending_loans) }}</h4>
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
                                    <div class="text-center icon-big icon-success bubble-shadow-small">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Approved</p>
                                        <h4 class="card-title">{{ number_format($approved_loans) }}</h4>
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
                                    <div class="text-center icon-big icon-danger bubble-shadow-small">
                                        <i class="fas fa-times-circle"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Rejected</p>
                                        <h4 class="card-title">{{ number_format($rejected_loans) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amount Statistics -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="text-center icon-big icon-success bubble-shadow-small">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Total Approved Amount</p>
                                        <h4 class="card-title">{{ $settings->currency }}{{ number_format($total_amount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-round">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-icon">
                                    <div class="text-center icon-big icon-warning bubble-shadow-small">
                                        <i class="fas fa-hourglass-half"></i>
                                    </div>
                                </div>
                                <div class="col col-stats ml-3 ml-sm-0">
                                    <div class="numbers">
                                        <p class="card-category">Pending Amount</p>
                                        <h4 class="card-title">{{ $settings->currency }}{{ number_format($pending_amount, 2) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="card card-round">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h4 class="card-title">Loan Applications</h4>
                        <form method="GET" action="{{ route('admin.loans.index') }}" class="ml-auto d-flex">
                            <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Processed" {{ request('status') == 'Processed' ? 'selected' : '' }}>Approved</option>
                                <option value="yes" {{ request('status') == 'yes' ? 'selected' : '' }}>Active</option>
                                <option value="No" {{ request('status') == 'No' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <input type="text" name="search" class="form-control form-control-sm mr-2" placeholder="Search..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-search"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Loan Type</th>
                                    <th>Amount</th>
                                    <th>Duration</th>
                                    <th>Monthly Income</th>
                                    <th>Status</th>
                                    <th>Date Applied</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $loan)
                                <tr>
                                    <td>#{{ $loan->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($loan->user->profile_photo_path)
                                                <img src="{{ profile_photo_url($loan->user) }}" 
                                                     alt="{{ $loan->user->name }}" 
                                                     class="avatar avatar-sm mr-2">
                                            @else
                                                <div class="avatar avatar-sm bg-primary text-white mr-2">
                                                    {{ substr($loan->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <strong>{{ $loan->user->name }}</strong><br>
                                                <small class="text-muted">{{ $loan->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $loan->plan_name ?? 'N/A' }}</td>
                                    <td><strong>{{ $loan->user->currency }}{{ number_format($loan->amount, 2) }}</strong></td>
                                    <td>
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
                                    </td>
                                    <td>{{ $loan->income_range ?? 'N/A' }}</td>
                                    <td>
                                        @if($loan->active == 'Pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($loan->active == 'Processed')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($loan->active == 'yes')
                                            <span class="badge badge-info">Active</span>
                                        @elseif($loan->active == 'No')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $loan->active }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $loan->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $loan->created_at->format('h:i A') }}</small></td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.loans.show', $loan->id) }}" 
                                               class="btn btn-sm btn-info" 
                                               data-toggle="tooltip" 
                                               title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if($loan->active == 'Pending')
                                                <button type="button" 
                                                        class="btn btn-sm btn-success" 
                                                        data-toggle="modal" 
                                                        data-target="#approveModal{{ $loan->id }}"
                                                        title="Approve">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        data-toggle="modal" 
                                                        data-target="#rejectModal{{ $loan->id }}"
                                                        title="Reject">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $loan->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Approve Loan Application</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.loans.approve', $loan->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <p>Are you sure you want to approve this loan application?</p>
                                                    <div class="alert alert-info">
                                                        <strong>Loan Details:</strong><br>
                                                        User: {{ $loan->user->name }}<br>
                                                        Amount: {{ $loan->user->currency }}{{ number_format($loan->amount, 2) }}<br>
                                                        Type: {{ $loan->plan_name }}
                                                    </div>
                                                    <p class="text-success"><i class="fa fa-check-circle"></i> The loan amount will be credited to the user's account.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-success">Approve Loan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $loan->id }}" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Reject Loan Application</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <form method="POST" action="{{ route('admin.loans.reject', $loan->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label>Reason for Rejection <span class="text-danger">*</span></label>
                                                        <textarea name="reason" class="form-control" rows="4" required placeholder="Enter reason for rejection..."></textarea>
                                                    </div>
                                                    <div class="alert alert-warning">
                                                        <strong>Loan Details:</strong><br>
                                                        User: {{ $loan->user->name }}<br>
                                                        Amount: {{ $loan->user->currency }}{{ number_format($loan->amount, 2) }}<br>
                                                        Type: {{ $loan->plan_name }}
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger">Reject Loan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="py-5">
                                            <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No loan applications found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($loans->hasPages())
                        <div class="mt-3">
                            {{ $loans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

@endsection
