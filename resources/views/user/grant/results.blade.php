@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="max-w-2xl mx-auto space-y-4">
    @if($application->status === 'approved')
        <!-- Approved Result -->
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-10 h-10 rounded-lg bg-green-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="check-circle" class="w-5 h-5 text-[#28A745]"></i>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-[#0D1B23]">Congratulations!</h1>
                    <p class="text-sm text-[#7A7A7A]">Application #{{ $application->id }} has been approved</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-[#F9F9F9] rounded-lg mb-4">
                <div>
                    <p class="text-xs text-[#7A7A7A] mb-1">Approved Amount</p>
                    <p class="text-xl font-bold text-[#28A745]">
                        {{ $settings->currency }}{{ number_format($application->approved_amount, 2) }}
                    </p>
                </div>
                <div class="w-9 h-9 rounded-lg bg-green-50 flex items-center justify-center">
                    <i data-lucide="trophy" class="w-5 h-5 text-[#28A745]"></i>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-start space-x-3 p-3 bg-[#F9F9F9] rounded-lg">
                    <i data-lucide="check" class="w-4 h-4 text-[#28A745] mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23]">Application Approved</p>
                        <p class="text-xs text-[#7A7A7A]">Your request has been reviewed and approved</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-[#F9F9F9] rounded-lg">
                    <i data-lucide="clock" class="w-4 h-4 text-primary-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23]">Awaiting Disbursement</p>
                        <p class="text-xs text-[#7A7A7A]">Funds will be disbursed to your account by our team</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-[#F9F9F9] rounded-lg">
                    <i data-lucide="bell" class="w-4 h-4 text-[#3e4e83] mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23]">Notification Sent</p>
                        <p class="text-xs text-[#7A7A7A]">We've sent details to {{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-semibold text-[#0D1B23] mb-3 flex items-center">
                <i data-lucide="list-checks" class="w-4 h-4 mr-2 text-primary-500"></i>
                What's Next?
            </h3>
            <ul class="space-y-2 text-sm text-[#4A4A4A]">
                <li class="flex items-start space-x-2">
                    <span class="text-[#28A745] text-xs mt-0.5">✓</span>
                    <span>Our team will review the final details</span>
                </li>
                <li class="flex items-start space-x-2">
                    <span class="text-[#28A745] text-xs mt-0.5">✓</span>
                    <span>Approved funds will be disbursed to your account</span>
                </li>
                <li class="flex items-start space-x-2">
                    <span class="text-[#28A745] text-xs mt-0.5">✓</span>
                    <span>You'll receive an email notification once disbursed</span>
                </li>
                <li class="flex items-start space-x-2">
                    <span class="text-[#28A745] text-xs mt-0.5">✓</span>
                    <span>Track your application status in "My Applications"</span>
                </li>
            </ul>
        </div>

    @else
        <!-- Review/Rejected Result -->
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-[#3e4e83]"></i>
                </div>
                <div>
                    <h1 class="text-lg font-semibold text-[#0D1B23]">Application Status Update</h1>
                    <p class="text-sm text-[#7A7A7A]">Application #{{ $application->id }} requires further review</p>
                </div>
            </div>

            <div class="flex items-center justify-between p-4 bg-[#F9F9F9] rounded-lg mb-4">
                <div>
                    <p class="text-xs text-[#7A7A7A] mb-1">Status</p>
                    <p class="text-lg font-bold text-[#3e4e83]">Under Review</p>
                </div>
                <div class="w-9 h-9 rounded-lg bg-amber-50 flex items-center justify-center">
                    <i data-lucide="file-search" class="w-5 h-5 text-[#3e4e83]"></i>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-start space-x-3 p-3 bg-[#F9F9F9] rounded-lg">
                    <i data-lucide="info" class="w-4 h-4 text-primary-500 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23]">Application Received</p>
                        <p class="text-xs text-[#7A7A7A]">Your application has been submitted successfully</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-[#F9F9F9] rounded-lg">
                    <i data-lucide="clock" class="w-4 h-4 text-[#3e4e83] mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23]">Additional Review Required</p>
                        <p class="text-xs text-[#7A7A7A]">Our team will review your application and contact you shortly</p>
                    </div>
                </div>

                <div class="flex items-start space-x-3 p-3 bg-[#F9F9F9] rounded-lg">
                    <i data-lucide="mail" class="w-4 h-4 text-[#3e4e83] mt-0.5"></i>
                    <div>
                        <p class="text-sm font-medium text-[#0D1B23]">Check Your Email</p>
                        <p class="text-xs text-[#7A7A7A]">We've sent an update to {{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Information -->
        <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
            <h3 class="text-sm font-semibold text-[#0D1B23] mb-3 flex items-center">
                <i data-lucide="headphones" class="w-4 h-4 mr-2 text-primary-500"></i>
                Need Assistance?
            </h3>
            <p class="text-sm text-[#4A4A4A] mb-3">
                Our support team is here to help. Contact us if you have questions about your application.
            </p>
            <a href="{{ route('contact') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2 rounded-lg bg-[#DFE5E5] border border-[#C8D1D1] text-sm text-[#4A4A4A] hover:bg-[#BFC9C9] hover:border-[#A0ACAC] transition-colors">
                <i data-lucide="message-circle" class="w-4 h-4"></i>
                <span>Contact Support</span>
            </a>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="grid md:grid-cols-2 gap-3">
        <a href="{{ route('grant.myApplications') }}" 
           class="px-4 py-3 rounded-lg bg-white border border-[#E0E5E5] hover:bg-[#F9F9F9] hover:border-[#C8D1D1] transition-colors text-sm font-medium text-[#4A4A4A] flex items-center justify-center space-x-2 shadow-sm">
            <i data-lucide="folder-open" class="w-4 h-4"></i>
            <span>My Applications</span>
        </a>
        <a href="{{ route('dashboard') }}" 
           class="px-4 py-3 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors text-sm font-medium text-white flex items-center justify-center space-x-2">
            <i data-lucide="home" class="w-4 h-4"></i>
            <span>Go to Dashboard</span>
        </a>
    </div>
</div>

@endsection

