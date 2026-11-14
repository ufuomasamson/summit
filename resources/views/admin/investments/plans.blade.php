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
                    <h1 class="title1">Investment Plans</h1>
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
                    <a href="{{ route('admin.investments.create-plan') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Create New Plan
                    </a>
                    <a href="{{ route('admin.investments.index') }}" class="btn btn-secondary">
                        <i class="fa fa-dashboard"></i> Dashboard
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
                                            <th>Plan Name</th>
                                            <th>Min Amount</th>
                                            <th>Max Amount</th>
                                            <th>ROI</th>
                                            <th>Duration</th>
                                            <th>Return Capital</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($plans as $plan)
                                            <tr>
                                                <td>{{ $plan->id }}</td>
                                                <td><strong>{{ $plan->name }}</strong></td>
                                                <td>${{ number_format($plan->min_price, 2) }}</td>
                                                <td>${{ number_format($plan->max_price, 2) }}</td>
                                                <td>
                                                    @if($plan->roi_type == 'percentage')
                                                        <span class="badge badge-info">{{ $plan->roi_value }}%</span>
                                                    @else
                                                        <span class="badge badge-success">${{ number_format($plan->roi_value, 2) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $plan->duration }} {{ ucfirst($plan->duration_type) }}</td>
                                                <td>
                                                    @if($plan->return_capital)
                                                        <span class="badge badge-success">Yes</span>
                                                    @else
                                                        <span class="badge badge-warning">No</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($plan->is_active)
                                                        <span class="badge badge-success">Active</span>
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>{{ $plan->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <a href="{{ route('admin.investments.edit-plan', $plan->id) }}" 
                                                       class="btn btn-info btn-sm m-1">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.investments.delete-plan', $plan->id) }}" 
                                                          method="POST" 
                                                          style="display: inline;"
                                                          onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm m-1">
                                                            <i class="fa fa-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <p class="my-4">No investment plans created yet.</p>
                                                    <a href="{{ route('admin.investments.create-plan') }}" class="btn btn-primary">
                                                        <i class="fa fa-plus"></i> Create First Plan
                                                    </a>
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
