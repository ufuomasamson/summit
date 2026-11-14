@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1">Card Details - #{{ $card->id }}</h1>
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
                    <!-- Card Information -->
                    <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                        <div class="card shadow mb-3">
                            <div class="card-header">
                                <h4 class="card-title">Card Information</h4>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-5 col-sm-5">Card Type:</dt>
                                    <dd class="col-7 col-sm-7">{{ $card->card_type }}</dd>

                                    <dt class="col-5 col-sm-5">Card Level:</dt>
                                    <dd class="col-7 col-sm-7">
                                        <span class="badge badge-{{ $card->card_level === 'Standard' ? 'secondary' : ($card->card_level === 'Gold' ? 'warning' : ($card->card_level === 'Platinum' ? 'info' : 'dark')) }}">
                                            {{ $card->card_level }}
                                        </span>
                                    </dd>

                                    <dt class="col-5 col-sm-5">Status:</dt>
                                    <dd class="col-7 col-sm-7">
                                        @if ($card->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @elseif ($card->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($card->status == 'blocked')
                                            <span class="badge badge-danger">Blocked</span>
                                        @elseif ($card->status == 'rejected')
                                            <span class="badge badge-dark">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($card->status) }}</span>
                                        @endif
                                    </dd>

                                    @if($card->card_number)
                                    <dt class="col-5 col-sm-5">Card Number:</dt>
                                    <dd class="col-7 col-sm-7">**** **** **** {{ $card->last_four }}</dd>
                                    @endif

                                    @if($card->cvv)
                                    <dt class="col-5 col-sm-5">CVV:</dt>
                                    <dd class="col-7 col-sm-7">{{ $card->cvv }}</dd>
                                    @endif

                                    @if($card->expiry)
                                    <dt class="col-5 col-sm-5">Expiry:</dt>
                                    <dd class="col-7 col-sm-7">{{ $card->expiry }}</dd>
                                    @endif

                                    <dt class="col-5 col-sm-5">Balance:</dt>
                                    <dd class="col-7 col-sm-7 font-weight-bold">{{ $settings->currency }}{{ number_format($card->balance, 2) }}</dd>

                                    <dt class="col-5 col-sm-5">Daily Limit:</dt>
                                    <dd class="col-7 col-sm-7">{{ $settings->currency }}{{ number_format($card->daily_limit, 2) }}</dd>

                                    <dt class="col-5 col-sm-5">Created:</dt>
                                    <dd class="col-7 col-sm-7 text-sm">{{ $card->created_at->toDayDateTimeString() }}</dd>

                                    @if($card->activated_at)
                                    <dt class="col-5 col-sm-5">Activated:</dt>
                                    <dd class="col-7 col-sm-7 text-sm">{{ $card->activated_at->toDayDateTimeString() }}</dd>
                                    @endif

                                    @if($card->blocked_at)
                                    <dt class="col-5 col-sm-5">Blocked:</dt>
                                    <dd class="col-7 col-sm-7 text-sm">{{ $card->blocked_at->toDayDateTimeString() }}</dd>
                                    @endif

                                    @if($card->rejection_reason)
                                    <dt class="col-sm-12 mt-2">Rejection Reason:</dt>
                                    <dd class="col-sm-12">
                                        <div class="alert alert-danger">{{ $card->rejection_reason }}</div>
                                    </dd>
                                    @endif
                                </dl>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="card shadow mb-3">
                            <div class="card-header">
                                <h4 class="card-title">Cardholder Details</h4>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-5 col-sm-5">Name:</dt>
                                    <dd class="col-7 col-sm-7">{{ $card->user->name }}</dd>

                                    <dt class="col-5 col-sm-5">Email:</dt>
                                    <dd class="col-7 col-sm-7 text-sm">{{ $card->user->email }}</dd>

                                    <dt class="col-5 col-sm-5">Card Name:</dt>
                                    <dd class="col-7 col-sm-7">{{ $card->cardholder_name }}</dd>

                                    <dt class="col-sm-12 mt-2">Billing Address:</dt>
                                    <dd class="col-sm-12">
                                        {{ $card->billing_address }}<br>
                                        {{ $card->billing_city }}, {{ $card->billing_zipcode }}<br>
                                        {{ $card->billing_country }}
                                    </dd>
                                </dl>
                                <a href="{{ route('viewuser', $card->user_id) }}" class="btn btn-sm btn-primary btn-block">
                                    <i class="fa fa-user"></i> View User Profile
                                </a>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card shadow">
                            <div class="card-header">
                                <h4 class="card-title">Actions</h4>
                            </div>
                            <div class="card-body">
                                @if($card->status === 'pending')
                                <form action="{{ route('admin.cards.approve', $card->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block" 
                                        onclick="return confirm('Approve this card application?')">
                                        <i class="fa fa-check"></i> Approve Card
                                    </button>
                                </form>

                                <button type="button" class="btn btn-danger btn-block mb-2" 
                                    data-toggle="modal" data-target="#rejectModal">
                                    <i class="fa fa-times"></i> Reject Application
                                </button>
                                @endif

                                @if($card->status === 'active')
                                <button type="button" class="btn btn-primary btn-block mb-2" 
                                    data-toggle="modal" data-target="#topupModal">
                                    <i class="fa fa-plus"></i> Top Up Balance
                                </button>

                                <button type="button" class="btn btn-warning btn-block mb-2" 
                                    data-toggle="modal" data-target="#deductModal">
                                    <i class="fa fa-minus"></i> Deduct Balance
                                </button>

                                <form action="{{ route('admin.cards.block', $card->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-block" 
                                        onclick="return confirm('Block this card?')">
                                        <i class="fa fa-ban"></i> Block Card
                                    </button>
                                </form>
                                @endif

                                @if($card->status === 'blocked')
                                <form action="{{ route('admin.cards.unblock', $card->id) }}" method="POST" class="mb-2">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block" 
                                        onclick="return confirm('Unblock this card?')">
                                        <i class="fa fa-check-circle"></i> Unblock Card
                                    </button>
                                </form>
                                @endif

                                @if($card->balance == 0)
                                <form action="{{ route('admin.cards.delete', $card->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-dark btn-block" 
                                        onclick="return confirm('Permanently delete this card?')">
                                        <i class="fa fa-trash"></i> Delete Card
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Transactions -->
                    <div class="col-12 col-lg-8">
                        <div class="card shadow">
                            <div class="card-header">
                                <h4 class="card-title">Transaction History</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive" data-example-id="hoverable-table" style="overflow-x: auto;">
                                    <table class="table table-hover table-sm" style="min-width: 800px;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Description</th>
                                                <th>Balance Before</th>
                                                <th>Balance After</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($transactions as $txn)
                                                <tr>
                                                    <td>{{ $txn->id }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $txn->type === 'purchase' || $txn->type === 'fee' || $txn->type === 'deduction' ? 'danger' : 'success' }}">
                                                            {{ ucfirst($txn->type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $settings->currency }}{{ number_format($txn->amount, 2) }}</td>
                                                    <td>{{ $txn->description }}</td>
                                                    <td>{{ $settings->currency }}{{ number_format($txn->balance_before, 2) }}</td>
                                                    <td>{{ $settings->currency }}{{ number_format($txn->balance_after, 2) }}</td>
                                                    <td>
                                                        <span class="badge badge-{{ $txn->status === 'completed' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($txn->status) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $txn->created_at->format('M d, Y H:i') }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="text-center">
                                                        <div class="py-4">
                                                            <i class="fa fa-inbox fa-2x text-muted mb-2"></i>
                                                            <p class="text-muted">No transactions yet</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-3">
                                    {{ $transactions->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Card Application</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.cards.reject', $card->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Rejection Reason</label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required 
                                placeholder="Enter reason for rejection..."></textarea>
                        </div>
                        <p class="text-muted small">
                            <i class="fa fa-info-circle"></i> 
                            The issuance fee of {{ $settings->currency }}{{ number_format($card->amount, 2) }} will be refunded to the user's account.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Top Up Modal -->
    <div class="modal fade" id="topupModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Top Up Card Balance</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.cards.topup', $card->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" step="0.01" min="1" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Optional description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Top Up Card</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Deduct Modal -->
    <div class="modal fade" id="deductModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deduct from Card Balance</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.cards.deduct', $card->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fa fa-exclamation-triangle"></i> 
                            Current balance: {{ $settings->currency }}{{ number_format($card->balance, 2) }}
                        </div>
                        <div class="form-group">
                            <label>Amount to Deduct</label>
                            <input type="number" name="amount" step="0.01" min="1" max="{{ $card->balance }}" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Reason (Required)</label>
                            <textarea name="description" class="form-control" rows="3" required 
                                placeholder="Enter reason for deduction..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Deduct Amount</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


