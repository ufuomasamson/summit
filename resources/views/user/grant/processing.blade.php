@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ progress: 0, step: 0 }" 
     x-init="
        let interval = setInterval(() => {
            if (progress < 100) {
                progress += 2;
                step = Math.floor(progress / 20);
            } else {
                clearInterval(interval);
            }
        }, 100);
     "
     class="max-w-2xl space-y-5">
    
    <!-- Header -->
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-lg bg-primary-500 flex items-center justify-center flex-shrink-0 animate-pulse">
            <i data-lucide="file-check" class="w-5 h-5 text-white"></i>
        </div>
        <div>
            <h1 class="text-xl font-bold text-[#0D1B23]">Processing Your Application</h1>
            <p class="text-sm text-[#7A7A7A]">Please wait while we review your application...</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-5 shadow-sm">
        <div class="mb-5">
            <div class="flex justify-between text-xs font-semibold text-[#7A7A7A] mb-1.5">
                <span>Progress</span>
                <span x-text="progress + '%'"></span>
            </div>
            <div class="h-2 bg-[#DFE5E5] rounded-full overflow-hidden">
                <div class="h-full bg-primary-500 rounded-full transition-all duration-300 ease-out" 
                     :style="'width: ' + progress + '%'"></div>
            </div>
        </div>

        <!-- Processing Steps -->
        <div class="space-y-2">
            <!-- Step 1 -->
            <div class="flex items-center space-x-3 p-3 rounded-lg transition-colors"
                 :class="step >= 0 ? 'bg-primary-50 border border-primary-200' : 'bg-[#F9F9F9] border border-[#E0E5E5]'">
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                     :class="step >= 1 ? 'bg-primary-500' : 'bg-[#BFC9C9]'">
                    <i :data-lucide="step >= 1 ? 'check' : 'loader'" 
                       :class="step >= 1 ? '' : 'animate-spin'"
                       class="w-3.5 h-3.5 text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-[#0D1B23]">Receiving Application</p>
                    <p class="text-xs text-[#7A7A7A]">Validating submission data</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-center space-x-3 p-3 rounded-lg transition-colors"
                 :class="step >= 1 ? 'bg-primary-50 border border-primary-200' : 'bg-[#F9F9F9] border border-[#E0E5E5]'">
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                     :class="step >= 2 ? 'bg-primary-500' : step >= 1 ? 'bg-primary-300' : 'bg-[#BFC9C9]'">
                    <i :data-lucide="step >= 2 ? 'check' : step >= 1 ? 'loader' : 'circle'" 
                       :class="step >= 1 && step < 2 ? 'animate-spin' : ''"
                       class="w-3.5 h-3.5 text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-[#0D1B23]">Verifying Information</p>
                    <p class="text-xs text-[#7A7A7A]">Checking account status and eligibility</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="flex items-center space-x-3 p-3 rounded-lg transition-colors"
                 :class="step >= 2 ? 'bg-primary-50 border border-primary-200' : 'bg-[#F9F9F9] border border-[#E0E5E5]'">
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                     :class="step >= 3 ? 'bg-primary-500' : step >= 2 ? 'bg-primary-300' : 'bg-[#BFC9C9]'">
                    <i :data-lucide="step >= 3 ? 'check' : step >= 2 ? 'loader' : 'circle'" 
                       :class="step >= 2 && step < 3 ? 'animate-spin' : ''"
                       class="w-3.5 h-3.5 text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-[#0D1B23]">Analyzing Request</p>
                    <p class="text-xs text-[#7A7A7A]">Evaluating grant eligibility</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="flex items-center space-x-3 p-3 rounded-lg transition-colors"
                 :class="step >= 3 ? 'bg-primary-50 border border-primary-200' : 'bg-[#F9F9F9] border border-[#E0E5E5]'">
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                     :class="step >= 4 ? 'bg-primary-500' : step >= 3 ? 'bg-primary-300' : 'bg-[#BFC9C9]'">
                    <i :data-lucide="step >= 4 ? 'check' : step >= 3 ? 'loader' : 'circle'" 
                       :class="step >= 3 && step < 4 ? 'animate-spin' : ''"
                       class="w-3.5 h-3.5 text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-[#0D1B23]">Processing Decision</p>
                    <p class="text-xs text-[#7A7A7A]">Finalizing application review</p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="flex items-center space-x-3 p-3 rounded-lg transition-colors"
                 :class="step >= 4 ? 'bg-primary-50 border border-primary-200' : 'bg-[#F9F9F9] border border-[#E0E5E5]'">
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 transition-colors"
                     :class="step >= 5 ? 'bg-primary-500' : step >= 4 ? 'bg-primary-300' : 'bg-[#BFC9C9]'">
                    <i :data-lucide="step >= 5 ? 'check' : step >= 4 ? 'loader' : 'circle'" 
                       :class="step >= 4 && step < 5 ? 'animate-spin' : ''"
                       class="w-3.5 h-3.5 text-white"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-[#0D1B23]">Generating Results</p>
                    <p class="text-xs text-[#7A7A7A]">Preparing your application status</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div>
        <a href="{{ route('grant.results', ['id' => $application->id]) }}" 
           x-show="progress >= 100"
           x-transition
           class="inline-flex items-center space-x-2 px-5 py-3 bg-primary-500 hover:bg-primary-600 rounded-lg font-semibold text-sm text-white transition-colors">
            <span>View Results</span>
            <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </a>
        
        <p x-show="progress < 100" class="text-[#7A7A7A] text-xs flex items-center">
            <i data-lucide="clock" class="w-3.5 h-3.5 mr-1"></i>
            This usually takes a few moments...
        </p>
    </div>

    <!-- Info Notice -->
    <div class="bg-white border border-[#E0E5E5] rounded-lg p-3.5 shadow-sm">
        <p class="text-xs text-[#7A7A7A] flex items-center">
            <i data-lucide="info" class="w-3.5 h-3.5 mr-1.5 text-primary-500 flex-shrink-0"></i>
            You can close this page and check your results later from "My Applications"
        </p>
    </div>
</div>

<script>
    // Reinitialize Lucide icons when Alpine updates
    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
        setInterval(() => {
            lucide.createIcons();
        }, 500);
    });
</script>

@endsection
