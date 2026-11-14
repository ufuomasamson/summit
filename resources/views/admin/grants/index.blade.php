<?php
if (Auth('admin')->User()->dashboard_style == 'light') {
    $bg = 'light';
    $text = 'dark';
    $gradient = 'primary';
} else {
    $bg = 'dark';
    $text = 'light';
    $gradient = 'dark';
}
?>
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
                            <h2 class="pb-2 text-white fw-bold">Grant Applications</h2>
                            <h5 class="mb-2 text-white op-7">Manage all grant applications</h5>
                        </div>
                        <div class="py-2 ml-md-auto py-md-0">
                            <a href="{{ route('admin.grants.pending') }}" class="mr-2 btn btn-warning">
                                Pending ({{ $stats['processing'] }})
                            </a>
                            <a href="{{ route('admin.grants.approved') }}" class="mr-2 btn btn-success">
                                Approved ({{ $stats['approved'] }})
                            </a>
                            <a href="{{ route('admin.grants.disbursed') }}" class="btn btn-info">
                                Disbursed ({{ $stats['disbursed'] }})
                            </a>
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

            <div class="page-inner mt--5">
                <!-- Statistics Cards -->
                <div class="row row-card-no-pd shadow-lg mt--2">
                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round full-height">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-file text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total Applications</p>
                                            <h4 class="card-title">{{ $stats['total'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round full-height">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-clock text-warning"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Processing</p>
                                            <h4 class="card-title">{{ $stats['processing'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round full-height">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-success text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Approved</p>
                                            <h4 class="card-title">{{ $stats['approved'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round full-height">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-coins text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total Requested</p>
                                            <h4 class="card-title">{{ $settings->currency }}{{ number_format($stats['total_requested'], 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-piggy-bank text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total Approved</p>
                                            <h4 class="card-title">{{ $settings->currency }}{{ number_format($stats['total_approved'], 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-diagram text-info"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total Disbursed</p>
                                            <h4 class="card-title">{{ $settings->currency }}{{ number_format($stats['total_disbursed'], 2) }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-error text-danger"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Rejected</p>
                                            <h4 class="card-title">{{ $stats['rejected'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="card card-stats card-round">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center icon-big">
                                            <i class="flaticon-check text-success"></i>
                                        </div>
                                    </div>
                                    <div class="col-8 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Disbursed</p>
                                            <h4 class="card-title">{{ $stats['disbursed'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications List -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">All Grant Applications</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>User</th>
                                                <th>Type</th>
                                                <th>Requested</th>
                                                <th>Approved</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($applications as $app)
                                                <tr>
                                                    <td>#{{ $app->id }}</td>
                                                    <td>
                                                        <strong>{{ $app->user->name }}</strong><br>
                                                        <small class="text-muted">{{ $app->user->email }}</small>
                                                    </td>
                                                    <td>
                                                        <span class="badge badge-{{ $app->application_type === 'individual' ? 'primary' : 'warning' }}">
                                                            {{ ucfirst($app->application_type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $settings->currency }}{{ number_format($app->requested_amount, 2) }}</td>
                                                    <td>
                                                        @if($app->approved_amount)
                                                            <strong class="text-success">{{ $settings->currency }}{{ number_format($app->approved_amount, 2) }}</strong>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($app->status === 'processing')
                                                            <span class="badge badge-warning">Processing</span>
                                                        @elseif($app->status === 'approved')
                                                            <span class="badge badge-success">Approved</span>
                                                        @elseif($app->status === 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @elseif($app->status === 'disbursed')
                                                            <span class="badge badge-info">Disbursed</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $app->created_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.grants.view', $app->id) }}" class="btn btn-sm btn-primary">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center py-4">
                                                        <i class="flaticon-folder text-muted" style="font-size: 48px;"></i>
                                                        <p class="text-muted mt-2">No grant applications found</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
