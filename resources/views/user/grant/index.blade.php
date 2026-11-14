@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="space-y-5">
    <!-- Header -->
    <div>
        <h1 class="text-xl font-bold text-[#0D1B23] mb-1">Grant Application</h1>
        <p class="text-sm text-[#7A7A7A]">Choose the type of grant you want to apply for</p>
    </div>

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Grant Types Selection -->
    <div class="grid md:grid-cols-2 gap-4 max-w-4xl">
        <!-- Individual Grant -->
        <a href="{{ route('grant.individual') }}" 
           class="bg-white border border-[#E0E5E5] rounded-xl p-6 hover:bg-primary-50 hover:border-[#C8D1D1] transition-colors shadow-sm group">
            <div class="flex items-start space-x-4">
                <div class="w-11 h-11 rounded-lg bg-primary-500 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-[#0D1B23] mb-1">Individual Grant</h3>
                    <p class="text-[#7A7A7A] text-sm mb-3">
                        Apply for personal funding for programs, research, or community projects
                    </p>
                    <span class="inline-flex items-center space-x-1 text-sm text-primary-500 font-medium group-hover:text-primary-600 transition-colors">
                        <span>Apply Now</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform"></i>
                    </span>
                </div>
            </div>
        </a>

        <!-- Company/Organization Grant -->
        <a href="{{ route('grant.company') }}" 
           class="bg-white border border-[#E0E5E5] rounded-xl p-6 hover:bg-primary-50 hover:border-[#C8D1D1] transition-colors shadow-sm group">
            <div class="flex items-start space-x-4">
                <div class="w-11 h-11 rounded-lg bg-primary-500 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="building-2" class="w-5 h-5 text-white"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-[#0D1B23] mb-1">Company/Organization Grant</h3>
                    <p class="text-[#7A7A7A] text-sm mb-3">
                        Apply for organizational funding for projects, programs, or operational support
                    </p>
                    <span class="inline-flex items-center space-x-1 text-sm text-primary-500 font-medium group-hover:text-primary-600 transition-colors">
                        <span>Apply Now</span>
                        <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform"></i>
                    </span>
                </div>
            </div>
        </a>
    </div>

    <!-- Info Section -->
    <div class="bg-white border border-[#E0E5E5] rounded-lg p-4 max-w-4xl shadow-sm">
        <div class="flex items-start space-x-3">
            <div class="w-8 h-8 rounded-lg bg-primary-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                <i data-lucide="info" class="w-4 h-4 text-primary-500"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-sm font-semibold text-[#0D1B23] mb-1.5">Before You Apply</h4>
                <ul class="text-sm text-[#7A7A7A] space-y-1">
                    <li>• Ensure all information provided is accurate and complete</li>
                    <li>• Applications are reviewed within 24-48 hours</li>
                    <li>• You will be notified of the decision via email and dashboard</li>
                    <li>• Approved grants are disbursed directly to your account</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- View My Applications -->
    <div>
        <a href="{{ route('grant.myApplications') }}" class="inline-flex items-center space-x-1.5 text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors">
            <i data-lucide="file-text" class="w-4 h-4"></i>
            <span>View My Applications</span>
        </a>
    </div>
</div>

@endsection
