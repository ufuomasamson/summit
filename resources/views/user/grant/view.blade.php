@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="max-w-4xl mx-auto space-y-4">
    <!-- Header -->
    <div>
        <a href="{{ route('grant.myApplications') }}" class="inline-flex items-center space-x-2 text-sm text-[#7A7A7A] hover:text-primary-500 mb-3 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to My Applications</span>
        </a>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-lg font-semibold text-[#0D1B23] mb-1">Application #{{ $application->id }}</h1>
                <p class="text-sm text-[#7A7A7A]">Submitted {{ $application->created_at->format('M d, Y \a\t h:i A') }}</p>
            </div>
            <span class="px-3 py-1.5 rounded-lg text-xs font-semibold border {{ $application->getStatusBadgeClass() }}">
                {{ $application->getStatusDisplayName() }}
            </span>
        </div>
    </div>

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Status Timeline -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-[#0D1B23] mb-3 flex items-center">
            <i data-lucide="activity" class="w-4 h-4 mr-2 text-primary-500"></i>
            Application Status
        </h3>
        <div class="flex items-center space-x-2">
            <div class="flex-1 h-1.5 rounded-full {{ in_array($application->status, ['processing', 'approved', 'rejected', 'disbursed']) ? 'bg-primary-500' : 'bg-[#DFE5E5]' }}"></div>
            <div class="flex-1 h-1.5 rounded-full {{ in_array($application->status, ['approved', 'disbursed']) ? 'bg-primary-500' : 'bg-[#DFE5E5]' }}"></div>
            <div class="flex-1 h-1.5 rounded-full {{ $application->status === 'disbursed' ? 'bg-primary-500' : 'bg-[#DFE5E5]' }}"></div>
        </div>
        <div class="flex justify-between text-xs text-[#7A7A7A] mt-2">
            <span>Submitted</span>
            <span>{{ $application->status === 'rejected' ? 'Rejected' : 'Approved' }}</span>
            <span>Disbursed</span>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-[#0D1B23] mb-3 flex items-center">
            <i data-lucide="circle-dollar-sign" class="w-4 h-4 mr-2 text-[#3e4e83]"></i>
            Financial Summary
        </h3>
        <div class="grid md:grid-cols-3 gap-3">
            <div class="bg-[#F9F9F9] rounded-lg p-3">
                <p class="text-xs text-[#7A7A7A] mb-1">Requested Amount</p>
                <p class="text-lg font-bold text-primary-500">
                    {{ $settings->currency }}{{ number_format($application->requested_amount, 2) }}
                </p>
            </div>
            @if($application->approved_amount)
                <div class="bg-[#F9F9F9] rounded-lg p-3">
                    <p class="text-xs text-[#7A7A7A] mb-1">Approved Amount</p>
                    <p class="text-lg font-bold text-[#28A745]">
                        {{ $settings->currency }}{{ number_format($application->approved_amount, 2) }}
                    </p>
                </div>
            @endif
            @if($application->disbursal_date)
                <div class="bg-[#F9F9F9] rounded-lg p-3">
                    <p class="text-xs text-[#7A7A7A] mb-1">Disbursed On</p>
                    <p class="text-sm font-bold text-primary-700">
                        {{ $application->disbursal_date->format('M d, Y') }}
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Application Details -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
        <h3 class="text-sm font-semibold text-[#0D1B23] mb-3 flex items-center">
            <i data-lucide="file-text" class="w-4 h-4 mr-2 text-primary-500"></i>
            Application Details
        </h3>
        <div class="space-y-4">
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <p class="text-xs text-[#7A7A7A] mb-1">Application Type</p>
                    <p class="text-sm font-medium text-[#0D1B23] capitalize">{{ $application->application_type }}</p>
                </div>
                <div>
                    <p class="text-xs text-[#7A7A7A] mb-1">Application ID</p>
                    <p class="text-sm font-medium text-[#0D1B23]">#{{ $application->id }}</p>
                </div>
            </div>

            @if($application->application_type === 'company')
                <div class="pt-3 border-t border-[#E0E5E5]">
                    <h4 class="text-sm font-medium text-[#0D1B23] mb-3">Company Information</h4>
                    <div class="grid md:grid-cols-2 gap-3">
                        @if($application->legal_name)
                            <div>
                                <p class="text-xs text-[#7A7A7A] mb-1">Legal Name</p>
                                <p class="text-sm font-medium text-[#0D1B23]">{{ $application->legal_name }}</p>
                            </div>
                        @endif
                        @if($application->ein)
                            <div>
                                <p class="text-xs text-[#7A7A7A] mb-1">Tax ID/EIN</p>
                                <p class="text-sm font-medium text-[#0D1B23]">{{ $application->ein }}</p>
                            </div>
                        @endif
                        @if($application->incorporation_date)
                            <div>
                                <p class="text-xs text-[#7A7A7A] mb-1">Incorporation Date</p>
                                <p class="text-sm font-medium text-[#0D1B23]">{{ $application->incorporation_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                        @if($application->mailing_address)
                            <div class="md:col-span-2">
                                <p class="text-xs text-[#7A7A7A] mb-1">Mailing Address</p>
                                <p class="text-sm font-medium text-[#0D1B23]">{{ $application->mailing_address }}</p>
                            </div>
                        @endif
                        @if($application->mission_statement)
                            <div class="md:col-span-2">
                                <p class="text-xs text-[#7A7A7A] mb-1">Mission Statement</p>
                                <p class="text-sm text-[#0D1B23]">{{ $application->mission_statement }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($application->application_type === 'individual')
                <div class="pt-3 border-t border-[#E0E5E5]">
                    <h4 class="text-sm font-medium text-[#0D1B23] mb-3">Funding Types</h4>
                    <div class="flex flex-wrap gap-2">
                        @if($application->program_funding)
                            <span class="px-2.5 py-1 rounded-lg bg-primary-50 text-primary-600 text-xs font-medium border border-primary-200">
                                Program Funding
                            </span>
                        @endif
                        @if($application->research_funding)
                            <span class="px-2.5 py-1 rounded-lg bg-primary-50 text-primary-600 text-xs font-medium border border-primary-200">
                                Research Funding
                            </span>
                        @endif
                        @if($application->equipment_funding)
                            <span class="px-2.5 py-1 rounded-lg bg-primary-50 text-primary-600 text-xs font-medium border border-primary-200">
                                Capacity Building
                            </span>
                        @endif
                        @if($application->community_outreach)
                            <span class="px-2.5 py-1 rounded-lg bg-amber-50 text-[#3e4e83] text-xs font-medium border border-amber-200">
                                Community Projects
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Notes History -->
    @if($application->notes)
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-semibold text-[#0D1B23] mb-3 flex items-center">
                <i data-lucide="message-square" class="w-4 h-4 mr-2 text-[#3e4e83]"></i>
                Notes & Updates
            </h3>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @foreach(array_filter(explode("\n\n", $application->notes)) as $note)
                    @if(trim($note))
                        <div class="bg-[#F9F9F9] rounded-lg p-3 border border-[#E0E5E5]">
                            <p class="text-sm text-[#4A4A4A] whitespace-pre-wrap">{{ $note }}</p>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="grid md:grid-cols-2 gap-3">
        @if($application->status === 'processing')
            <a href="{{ route('grant.edit', $application->id) }}" 
               class="px-4 py-3 rounded-lg bg-white border border-[#E0E5E5] hover:bg-[#F9F9F9] hover:border-[#C8D1D1] transition-colors text-sm font-medium text-[#4A4A4A] flex items-center justify-center space-x-2 shadow-sm">
                <i data-lucide="edit" class="w-4 h-4"></i>
                <span>Edit Application</span>
            </a>
        @endif
        <a href="{{ route('grant.myApplications') }}" 
           class="px-4 py-3 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors text-sm font-medium text-white flex items-center justify-center space-x-2">
            <i data-lucide="folder-open" class="w-4 h-4"></i>
            <span>View All Applications</span>
        </a>
    </div>
</div>

@endsection

