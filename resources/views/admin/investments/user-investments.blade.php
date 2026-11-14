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
                    <h1 class="title1">User Investments</h1>
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
                    <a href="{{ route('admin.investments.index') }}" class="btn btn-secondary">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.investments.add-manual') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Add Manual Investment
                    </a>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card p-3 shadow">
                            <div class="bs-example widget-shadow table-responsive">
                                <table id="ShipTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Plan</th>
                                            <th>Amount</th>
                                            <th>ROI Received</th>
                                            <th>Status</th>
                                            <th>Capital Returned</th>
                                            <th>Started</th>
                                            <th>Expires</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($investments as $investment)
                                            <tr>
                                                <td>{{ $investment->id }}</td>
                                                <td>
                                                    <strong>{{ $investment->user->name ?? 'N/A' }}</strong><br>
                                                    <small class="text-muted">{{ $investment->user->email ?? '' }}</small>
                                                </td>
                                                <td>{{ $investment->plan_name }}</td>
                                                <td>${{ number_format($investment->amount, 2) }}</td>
                                                <td>
                                                    <span class="badge badge-success">
                                                        ${{ number_format($investment->roi_received, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($investment->active == 'yes')
                                                        <span class="badge badge-success">Active</span>
                                                    @elseif($investment->active == 'Pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($investment->active == 'expired')
                                                        <span class="badge badge-secondary">Expired</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ $investment->active }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($investment->capital_returned)
                                                        <span class="badge badge-success">Yes</span>
                                                    @else
                                                        <span class="badge badge-warning">No</span>
                                                    @endif
                                                </td>
                                                <td>{{ $investment->activated_at ? $investment->activated_at->format('M d, Y') : 'N/A' }}</td>
                                                <td>{{ $investment->expire_date ? $investment->expire_date->format('M d, Y') : 'N/A' }}</td>
                                                <td>
                                                    @if($investment->active == 'yes')
                                                        <form action="{{ route('admin.investments.process-return', $investment->id) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm m-1"
                                                                    onclick="return confirm('Process this investment return?')">
                                                                <i class="fa fa-check"></i> Process Return
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.investments.cancel', $investment->id) }}" 
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm m-1"
                                                                    onclick="return confirm('Cancel this investment?')">
                                                                <i class="fa fa-times"></i> Cancel
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-muted">No actions</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <p class="my-4">No investments yet</p>
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
@endsection
