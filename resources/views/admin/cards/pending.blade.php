@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1">Pending Card Applications</h1>
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
                    <div class="col-12">
                        <div class="card shadow p-4">
                            <div class="table-responsive" data-example-id="hoverable-table">
                                <table id="ShipTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Client Name</th>
                                            <th>Client Email</th>
                                            <th>Card Type</th>
                                            <th>Card Level</th>
                                            <th>Issuance Fee</th>
                                            <th>Date Applied</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($cards as $card)
                                            <tr>
                                                <th scope="row">{{ $card->id }}</th>
                                                <td>{{ $card->user->name }}</td>
                                                <td>{{ $card->user->email }}</td>
                                                <td>{{ $card->card_type }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $card->card_level === 'Standard' ? 'secondary' : ($card->card_level === 'Gold' ? 'warning' : ($card->card_level === 'Platinum' ? 'info' : 'dark')) }}">
                                                        {{ $card->card_level }}
                                                    </span>
                                                </td>
                                                <td>{{ $settings->currency }}{{ number_format($card->amount, 2) }}</td>
                                                <td>{{ $card->created_at->toDayDateTimeString() }}</td>
                                                <td>
                                                    <a href="{{ route('admin.cards.view', $card->id) }}" 
                                                        class="btn btn-info btn-sm m-1" title="View details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('admin.cards.approve', $card->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm m-1" 
                                                            onclick="return confirm('Approve this card application? A valid card number will be generated.')"
                                                            title="Approve">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <button type="button" class="btn btn-danger btn-sm m-1" 
                                                        data-toggle="modal" data-target="#rejectModal{{ $card->id }}"
                                                        title="Reject">
                                                        Reject
                                                    </button>

                                                    <!-- Reject Modal -->
                                                    <div class="modal fade" id="rejectModal{{ $card->id }}" tabindex="-1" role="dialog">
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
                                                                            The issuance fee will be refunded to the user's account.
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
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">
                                                    <div class="py-4">
                                                        <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted">No pending applications</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {{ $cards->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


