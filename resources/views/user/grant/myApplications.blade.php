@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="space-y-5">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-[#0D1B23] mb-1">My Grant Applications</h1>
        <p class="text-sm text-[#7A7A7A]">View and manage all your grant applications</p>
    </div>

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="bg-white border border-[#E0E5E5] rounded-lg p-3.5 shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="file-text" class="w-4 h-4 text-primary-500"></i>
                </div>
                <div>
                    <p class="text-xs text-[#7A7A7A]">Total</p>
                    <p class="text-base font-bold text-[#0D1B23]">{{ $applications->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-[#E0E5E5] rounded-lg p-3.5 shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock" class="w-4 h-4 text-[#3e4e83]"></i>
                </div>
                <div>
                    <p class="text-xs text-[#7A7A7A]">Processing</p>
                    <p class="text-base font-bold text-[#0D1B23]">{{ $applications->where('status', 'processing')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-[#E0E5E5] rounded-lg p-3.5 shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="check-circle" class="w-4 h-4 text-[#28A745]"></i>
                </div>
                <div>
                    <p class="text-xs text-[#7A7A7A]">Approved</p>
                    <p class="text-base font-bold text-[#0D1B23]">{{ $applications->whereIn('status', ['approved', 'disbursed'])->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white border border-[#E0E5E5] rounded-lg p-3.5 shadow-sm">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="x-circle" class="w-4 h-4 text-[#D55672]"></i>
                </div>
                <div>
                    <p class="text-xs text-[#7A7A7A]">Rejected</p>
                    <p class="text-base font-bold text-[#0D1B23]">{{ $applications->where('status', 'rejected')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications List -->
    @if($applications->count() > 0)
        <div class="space-y-3">
            @foreach($applications as $app)
                <a href="{{ route('grant.view', $app->id) }}" 
                   class="bg-white border border-[#E0E5E5] rounded-xl p-4 hover:bg-primary-50 hover:border-[#C8D1D1] transition-colors block shadow-sm">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1.5">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $app->getStatusBadgeClass() }}">
                                    {{ $app->getStatusDisplayName() }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $app->application_type === 'individual' ? 'bg-primary-100 text-primary-600' : 'bg-amber-50 text-[#3e4e83]' }}">
                                    {{ ucfirst($app->application_type) }}
                                </span>
                            </div>
                            <h3 class="text-sm font-bold text-[#0D1B23] mb-0.5">
                                Application #{{ $app->id }}
                            </h3>
                            <p class="text-xs text-[#7A7A7A]">
                                Submitted {{ $app->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="text-right flex-shrink-0 ml-4">
                            <p class="text-xs text-[#7A7A7A] mb-0.5">Requested</p>
                            <p class="text-base font-bold text-[#0D1B23]">
                                {{ $settings->currency }}{{ number_format($app->requested_amount, 2) }}
                            </p>
                            @if($app->approved_amount)
                                <p class="text-xs text-[#28A745] mt-0.5">
                                    Approved: {{ $settings->currency }}{{ number_format($app->approved_amount, 2) }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-3 border-t border-[#E0E5E5]">
                        <div class="flex items-center space-x-3 text-xs text-[#7A7A7A]">
                            <span class="flex items-center">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 mr-1"></i>
                                {{ $app->created_at->format('M d, Y') }}
                            </span>
                            @if($app->disbursal_date)
                                <span class="flex items-center text-[#28A745]">
                                    <i data-lucide="banknote" class="w-3.5 h-3.5 mr-1"></i>
                                    Disbursed {{ $app->disbursal_date->format('M d, Y') }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-primary-500 font-medium flex items-center space-x-1">
                            <span>View Details</span>
                            <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </span>
                    </div>
                </a>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-8 text-center shadow-sm">
            <div class="w-11 h-11 mx-auto mb-3 rounded-full bg-[#DFE5E5] flex items-center justify-center">
                <i data-lucide="inbox" class="w-5 h-5 text-[#7A7A7A]"></i>
            </div>
            <h3 class="text-sm font-bold text-[#0D1B23] mb-1">No Applications Yet</h3>
            <p class="text-sm text-[#7A7A7A] mb-4">You haven't submitted any grant applications</p>
            <a href="{{ route('grant.index') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2 bg-primary-500 hover:bg-primary-600 rounded-lg text-sm font-medium text-white transition-colors">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Apply for Grant</span>
            </a>
        </div>
    @endif

    <!-- Action Button -->
    <div>
        <a href="{{ route('grant.index') }}" 
           class="inline-flex items-center space-x-1.5 text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            <span>Submit New Application</span>
        </a>
    </div>
</div>

@endsection

