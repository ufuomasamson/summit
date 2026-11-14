@extends('layouts.app')
@section('content')
    @include('admin.topmenu')
    @include('admin.sidebar')
    <div class="main-panel">
        <div class="content">
            <div class="page-inner">
                <div class="mt-2 mb-4">
                    <h1 class="title1">Manage Virtual Cards</h1>
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

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card card-stats card-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fa fa-credit-card"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total Cards</p>
                                            <h4 class="card-title">{{ $stats['total'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats card-warning">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fa fa-clock"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Pending</p>
                                            <h4 class="card-title">{{ $stats['pending'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats card-success">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fa fa-check-circle"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Active</p>
                                            <h4 class="card-title">{{ $stats['active'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card card-stats card-danger">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">
                                        <div class="icon-big text-center">
                                            <i class="fa fa-ban"></i>
                                        </div>
                                    </div>
                                    <div class="col-7 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Blocked</p>
                                            <h4 class="card-title">{{ $stats['blocked'] }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5 row">
                    <div class="col-12">
                        <div class="card shadow p-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">All Virtual Cards</h4>
                                    <div>
                                        <a href="{{ route('admin.cards.pending') }}" class="btn btn-warning btn-sm m-1">
                                            <i class="fa fa-clock"></i> Pending ({{ $stats['pending'] }})
                                        </a>
                                        <a href="{{ route('admin.cards.settings') }}" class="btn btn-primary btn-sm m-1">
                                            <i class="fa fa-cog"></i> Settings
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Filters -->
                            <form method="GET" class="mb-3 mt-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <select name="status" class="form-control">
                                            <option value="">All Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <select name="level" class="form-control">
                                            <option value="">All Levels</option>
                                            <option value="Standard">Standard</option>
                                            <option value="Gold">Gold</option>
                                            <option value="Platinum">Platinum</option>
                                            <option value="Black">Black</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" name="search" class="form-control" placeholder="Search user or card..." value="{{ request('search') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive" data-example-id="hoverable-table">
                                <table id="ShipTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Client Name</th>
                                            <th>Client Email</th>
                                            <th>Card Details</th>
                                            <th>Level</th>
                                            <th>Balance</th>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cards as $card)
                                            <tr>
                                                <th scope="row">{{ $card->id }}</th>
                                                <td>{{ $card->user->name }}</td>
                                                <td>{{ $card->user->email }}</td>
                                                <td>
                                                    <div>{{ $card->card_type }}</div>
                                                    @if($card->last_four)
                                                    <small class="text-muted">**** {{ $card->last_four }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $card->card_level === 'Standard' ? 'secondary' : ($card->card_level === 'Gold' ? 'warning' : ($card->card_level === 'Platinum' ? 'info' : 'dark')) }}">
                                                        {{ $card->card_level }}
                                                    </span>
                                                </td>
                                                <td>{{ $settings->currency }}{{ number_format($card->balance, 2) }}</td>
                                                <td>
                                                    @if ($card->status == 'active')
                                                        <span class="badge badge-success">{{ ucfirst($card->status) }}</span>
                                                    @elseif ($card->status == 'pending')
                                                        <span class="badge badge-warning">{{ ucfirst($card->status) }}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{ ucfirst($card->status) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $card->created_at->toDayDateTimeString() }}</td>
                                                <td>
                                                    <a href="{{ route('admin.cards.view', $card->id) }}" 
                                                        class="btn btn-info btn-sm m-1" title="View card details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    
                                                    @if($card->status === 'pending')
                                                    <form action="{{ route('admin.cards.approve', $card->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm m-1" 
                                                            onclick="return confirm('Approve this card application?')"
                                                            title="Approve card">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    @if($card->status === 'active')
                                                    <form action="{{ route('admin.cards.block', $card->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-warning btn-sm m-1" 
                                                            onclick="return confirm('Block this card?')"
                                                            title="Block card">
                                                            <i class="fa fa-ban"></i>
                                                        </button>
                                                    </form>
                                                    @endif

                                                    @if($card->status === 'blocked')
                                                    <form action="{{ route('admin.cards.unblock', $card->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm m-1" 
                                                            onclick="return confirm('Unblock this card?')"
                                                            title="Unblock card">
                                                            <i class="fa fa-check-circle"></i>
                                                        </button>
                                                    </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
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


