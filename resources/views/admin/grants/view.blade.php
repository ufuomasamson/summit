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
                            <h2 class="pb-2 text-white fw-bold">Grant Application #{{ $application->id }}</h2>
                            <h5 class="mb-2 text-white op-7">{{ $application->user->name }}</h5>
                        </div>
                        <div class="py-2 ml-md-auto py-md-0">
                            @if($application->status === 'processing')
                                <span class="badge badge-warning badge-lg">Processing</span>
                            @elseif($application->status === 'approved')
                                <span class="badge badge-success badge-lg">Approved</span>
                            @elseif($application->status === 'rejected')
                                <span class="badge badge-danger badge-lg">Rejected</span>
                            @elseif($application->status === 'disbursed')
                                <span class="badge badge-info badge-lg">Disbursed</span>
                            @endif
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
                <div class="row">
                    <!-- User Information -->
                    <div class="col-md-4">
                        <div class="card card-profile">
                            <div class="card-header" style="background-image: url('/dash/img/blogpost.jpg')">
                                <div class="profile-picture">
                                    <div class="avatar avatar-xl">
                                        <img src="{{ asset('dash/img/profile.jpg') }}" alt="..." class="avatar-img rounded-circle">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="user-profile text-center">
                                    <div class="name">{{ $application->user->name }}</div>
                                    <div class="job">{{ $application->user->email }}</div>
                                    <div class="desc mt-3">
                                        <strong>Account Balance:</strong> {{ $settings->currency }}{{ number_format($application->user->account_bal, 2) }}
                                    </div>
                                    <div class="desc">
                                        <strong>Grant Limit:</strong> {{ $settings->currency }}{{ number_format($application->user->grant_limit, 2) }}
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row user-stats text-center">
                                    <div class="col">
                                        <div class="number">{{ $application->user->created_at->format('M d, Y') }}</div>
                                        <div class="title">Joined</div>
                                    </div>
                                    <div class="col">
                                        <div class="number">{{ \App\Models\GrantApplication::where('user_id', $application->user_id)->count() }}</div>
                                        <div class="title">Applications</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Application Details -->
                    <div class="col-md-8">
                        <!-- Financial Information -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Financial Information</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><strong>Requested Amount</strong></label>
                                            <h3 class="text-primary">{{ $settings->currency }}{{ number_format($application->requested_amount, 2) }}</h3>
                                        </div>
                                    </div>
                                    @if($application->approved_amount)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>Approved Amount</strong></label>
                                                <h3 class="text-success">{{ $settings->currency }}{{ number_format($application->approved_amount, 2) }}</h3>
                                            </div>
                                        </div>
                                    @endif
                                    @if($application->disbursal_date)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><strong>Disbursed On</strong></label>
                                                <h3 class="text-info">{{ $application->disbursal_date->format('M d, Y') }}</h3>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Application Details -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Application Details</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Application Type</strong></label>
                                            <p>
                                                <span class="badge badge-{{ $application->application_type === 'individual' ? 'primary' : 'warning' }}">
                                                    {{ ucfirst($application->application_type) }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><strong>Submission Date</strong></label>
                                            <p>{{ $application->created_at->format('M d, Y \a\t h:i A') }}</p>
                                        </div>
                                    </div>
                                </div>

                                @if($application->application_type === 'company')
                                    <hr>
                                    <h5 class="mb-3">Company Information</h5>
                                    <div class="row">
                                        @if($application->legal_name)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Legal Name</strong></label>
                                                    <p>{{ $application->legal_name }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($application->ein)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Tax ID/EIN</strong></label>
                                                    <p>{{ $application->ein }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($application->incorporation_date)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label><strong>Incorporation Date</strong></label>
                                                    <p>{{ $application->incorporation_date->format('M d, Y') }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($application->mailing_address)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>Mailing Address</strong></label>
                                                    <p>{{ $application->mailing_address }}</p>
                                                </div>
                                            </div>
                                        @endif
                                        @if($application->mission_statement)
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label><strong>Mission Statement</strong></label>
                                                    <p>{{ $application->mission_statement }}</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($application->application_type === 'individual')
                                    <hr>
                                    <h5 class="mb-3">Funding Types Selected</h5>
                                    <div class="row">
                                        <div class="col-md-12">
                                            @if($application->program_funding)
                                                <span class="badge badge-success mr-2 mb-2">Program Funding</span>
                                            @endif
                                            @if($application->research_funding)
                                                <span class="badge badge-info mr-2 mb-2">Research Funding</span>
                                            @endif
                                            @if($application->equipment_funding)
                                                <span class="badge badge-warning mr-2 mb-2">Capacity Building</span>
                                            @endif
                                            @if($application->community_outreach)
                                                <span class="badge badge-primary mr-2 mb-2">Community Projects</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Forms -->
                        @if($application->status === 'processing')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card card-success">
                                        <div class="card-header">
                                            <h4 class="card-title text-white">Approve Application</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('admin.grants.approve', $application->id) }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Approved Amount</label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">{{ $settings->currency }}</span>
                                                        </div>
                                                        <input type="number" name="approved_amount" class="form-control" 
                                                               step="0.01" min="1" 
                                                               value="{{ $application->requested_amount }}" required>
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        Requested: {{ $settings->currency }}{{ number_format($application->requested_amount, 2) }}
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-success btn-block">
                                                    <i class="fa fa-check"></i> Approve Application
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card card-danger">
                                        <div class="card-header">
                                            <h4 class="card-title text-white">Reject Application</h4>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('admin.grants.reject', $application->id) }}" method="POST">
                                                @csrf
                                                <div class="form-group">
                                                    <label>Rejection Reason</label>
                                                    <textarea name="rejection_reason" class="form-control" rows="3" 
                                                              placeholder="Provide reason for rejection..." required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-danger btn-block">
                                                    <i class="fa fa-times"></i> Reject Application
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($application->status === 'approved')
                            <div class="card card-info">
                                <div class="card-header">
                                    <h4 class="card-title text-white">Disburse Funds</h4>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <strong>Amount to Disburse:</strong> {{ $settings->currency }}{{ number_format($application->approved_amount, 2) }}
                                    </div>
                                    <form action="{{ route('admin.grants.disburse', $application->id) }}" method="POST">
                                        @csrf
                                        <p>This will add <strong>{{ $settings->currency }}{{ number_format($application->approved_amount, 2) }}</strong> to the user's account balance.</p>
                                        <button type="submit" class="btn btn-info btn-block" 
                                                onclick="return confirm('Are you sure you want to disburse funds to this user?')">
                                            <i class="fa fa-money-bill"></i> Disburse Funds to User Account
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <!-- Admin Notes -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Add Admin Note</h4>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.grants.add-note', $application->id) }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="note" class="form-control" rows="3" 
                                                  placeholder="Add internal note about this application..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Add Note
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Notes History -->
                        @if($application->notes)
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Notes & History</h4>
                                </div>
                                <div class="card-body">
                                    <div style="max-height: 400px; overflow-y: auto;">
                                        @foreach(array_filter(explode("\n\n", $application->notes)) as $note)
                                            @if(trim($note))
                                                <div class="alert alert-light mb-2">
                                                    <pre class="mb-0" style="white-space: pre-wrap;">{{ $note }}</pre>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Delete Application -->
                        @if($application->status !== 'disbursed')
                            <div class="card card-danger">
                                <div class="card-header">
                                    <h4 class="card-title text-white">Danger Zone</h4>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Permanently delete this application. This action cannot be undone.</p>
                                    <a href="{{ route('admin.grants.delete', $application->id) }}" 
                                       class="btn btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this application? This action cannot be undone!')">
                                        <i class="fa fa-trash"></i> Delete Application
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
