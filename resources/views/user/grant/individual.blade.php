@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="max-w-3xl space-y-5">
    <!-- Header -->
    <div>
        <a href="{{ route('grant.index') }}" class="inline-flex items-center space-x-1.5 text-sm text-[#7A7A7A] hover:text-primary-500 mb-3 transition-colors">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Back to Grant Selection</span>
        </a>
        <h1 class="text-xl font-bold text-[#0D1B23] mb-1">Individual Grant Application</h1>
        <p class="text-sm text-[#7A7A7A]">Complete the form below to apply for an individual grant</p>
    </div>

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Application Form -->
    <form method="POST" action="{{ route('grant.storeIndividual') }}" class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6 space-y-5 shadow-sm">
        @csrf

        <!-- Applicant Information -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <h3 class="text-sm font-bold text-[#0D1B23] mb-3 flex items-center">
                <i data-lucide="user-circle" class="w-4 h-4 mr-1.5 text-[#7A7A7A]"></i>
                Applicant Information
            </h3>
            <div class="grid md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-[#7A7A7A] mb-1.5">Full Name</label>
                    <input type="text" value="{{ Auth::user()->name }}" disabled 
                           class="w-full px-3 py-2.5 rounded-lg bg-[#DFE5E5] border border-[#E0E5E5] text-sm text-[#7A7A7A] cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-[#7A7A7A] mb-1.5">Email</label>
                    <input type="email" value="{{ Auth::user()->email }}" disabled 
                           class="w-full px-3 py-2.5 rounded-lg bg-[#DFE5E5] border border-[#E0E5E5] text-sm text-[#7A7A7A] cursor-not-allowed">
                </div>
            </div>
        </div>

        <!-- Funding Information -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <h3 class="text-sm font-bold text-[#0D1B23] mb-3 flex items-center">
                <i data-lucide="circle-dollar-sign" class="w-4 h-4 mr-1.5 text-[#7A7A7A]"></i>
                Funding Request
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-[#7A7A7A] mb-1.5">
                        Requested Amount <span class="text-[#D55672]">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[#7A7A7A] text-sm">{{ $settings->currency }}</span>
                        <input type="number" name="requested_amount" step="0.01" min="1" required
                               value="{{ old('requested_amount') }}"
                               class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-white border border-[#C8D1D1] text-sm text-[#0D1B23] focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                               placeholder="0.00">
                    </div>
                    @error('requested_amount')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#7A7A7A] mb-2">
                        Select Funding Type(s)
                    </label>
                    <div class="space-y-1">
                        <label class="flex items-center space-x-3 p-2.5 rounded-lg hover:bg-primary-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="program_funding" value="1" {{ old('program_funding') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-[#C8D1D1] text-primary-500 focus:ring-primary-200">
                            <span class="text-sm text-[#4A4A4A]">Program Funding</span>
                        </label>
                        <label class="flex items-center space-x-3 p-2.5 rounded-lg hover:bg-primary-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="research_funding" value="1" {{ old('research_funding') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-[#C8D1D1] text-primary-500 focus:ring-primary-200">
                            <span class="text-sm text-[#4A4A4A]">Research Funding</span>
                        </label>
                        <label class="flex items-center space-x-3 p-2.5 rounded-lg hover:bg-primary-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="capacity_funding" value="1" {{ old('capacity_funding') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-[#C8D1D1] text-primary-500 focus:ring-primary-200">
                            <span class="text-sm text-[#4A4A4A]">Capacity Building Funding</span>
                        </label>
                        <label class="flex items-center space-x-3 p-2.5 rounded-lg hover:bg-primary-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="other_funding" value="1" {{ old('other_funding') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded border-[#C8D1D1] text-primary-500 focus:ring-primary-200">
                            <span class="text-sm text-[#4A4A4A]">Other Community Projects</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-[#7A7A7A] mb-1.5">
                        Funding Details (Optional)
                    </label>
                    <textarea name="funding_details" rows="4"
                              class="w-full px-3 py-2.5 rounded-lg bg-white border border-[#C8D1D1] text-sm text-[#0D1B23] focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors resize-none" 
                              placeholder="Describe how you plan to use the funds...">{{ old('funding_details') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <label class="flex items-start space-x-3 cursor-pointer">
                <input type="checkbox" required
                       class="w-4 h-4 rounded border-[#C8D1D1] text-primary-500 focus:ring-primary-200 mt-0.5">
                <span class="text-xs text-[#7A7A7A]">
                    I certify that all information provided is accurate and complete. I understand that false information may result in disqualification.
                </span>
            </label>
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" 
                    class="w-full px-6 py-3.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-semibold text-sm text-white flex items-center justify-center space-x-2">
                <i data-lucide="send" class="w-4 h-4"></i>
                <span>Submit Application</span>
            </button>
        </div>
    </form>
</div>

@endsection
