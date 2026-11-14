@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div class="max-w-4xl mx-auto space-y-5">
    <!-- Header -->
    <div>
        <a href="{{ route('grant.index') }}" class="inline-flex items-center space-x-1.5 text-sm text-[#7A7A7A] hover:text-primary-500 mb-3 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Back to Grant Selection</span>
        </a>
        <h1 class="text-xl font-bold text-[#0D1B23] mb-1">Company/Organization Grant Application</h1>
        <p class="text-sm text-[#7A7A7A]">Complete the form below to apply for an organizational grant</p>
    </div>

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Application Form -->
    <form method="POST" action="{{ route('grant.storeCompany') }}" class="bg-white border border-[#E0E5E5] rounded-xl p-5 md:p-6 space-y-5 shadow-sm">
        @csrf

        <!-- Organization Information -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <h3 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                <i data-lucide="building-2" class="w-4 h-4 mr-2 text-[#7A7A7A]"></i>
                Organization Information
            </h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Legal Name <span class="text-[#D55672]">*</span>
                    </label>
                    <input type="text" name="legal_name" required value="{{ old('legal_name') }}"
                           class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                           placeholder="Enter legal organization name">
                    @error('legal_name')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Tax ID/EIN <span class="text-[#D55672]">*</span>
                    </label>
                    <input type="text" name="tax_id" required value="{{ old('tax_id') }}"
                           class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                           placeholder="XX-XXXXXXX">
                    @error('tax_id')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Organization Type <span class="text-[#D55672]">*</span>
                    </label>
                    <select name="organization_type" required
                            class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors">
                        <option value="">Select type</option>
                        <option value="nonprofit" {{ old('organization_type') == 'nonprofit' ? 'selected' : '' }}>Non-Profit</option>
                        <option value="for-profit" {{ old('organization_type') == 'for-profit' ? 'selected' : '' }}>For-Profit</option>
                        <option value="government" {{ old('organization_type') == 'government' ? 'selected' : '' }}>Government</option>
                        <option value="educational" {{ old('organization_type') == 'educational' ? 'selected' : '' }}>Educational Institution</option>
                    </select>
                    @error('organization_type')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Founding Year <span class="text-[#D55672]">*</span>
                    </label>
                    <input type="number" name="founding_year" required min="1800" max="{{ date('Y') }}" value="{{ old('founding_year') }}"
                           class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                           placeholder="{{ date('Y') }}">
                    @error('founding_year')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Incorporation Date <span class="text-[#D55672]">*</span>
                    </label>
                    <input type="date" name="incorporation_date" required value="{{ old('incorporation_date') }}"
                           class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors">
                    @error('incorporation_date')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <h3 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                <i data-lucide="phone" class="w-4 h-4 mr-2 text-[#7A7A7A]"></i>
                Contact Information
            </h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Phone Number <span class="text-[#D55672]">*</span>
                    </label>
                    <input type="tel" name="phone_number" required value="{{ old('phone_number') }}"
                           class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                           placeholder="+1 (555) 000-0000">
                    @error('phone_number')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Contact Person <span class="text-[#D55672]">*</span>
                    </label>
                    <input type="text" name="contact_person" required value="{{ old('contact_person') }}"
                           class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                           placeholder="Full name of contact person">
                    @error('contact_person')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Mailing Address <span class="text-[#D55672]">*</span>
                    </label>
                    <textarea name="mailing_address" rows="2" required
                              class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                              placeholder="Street address, City, State, ZIP">{{ old('mailing_address') }}</textarea>
                    @error('mailing_address')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Mission Statement -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <h3 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                <i data-lucide="target" class="w-4 h-4 mr-2 text-[#7A7A7A]"></i>
                Mission Statement
            </h3>
            <textarea name="mission_statement" rows="4" required
                      class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                      placeholder="Describe your organization's mission and goals...">{{ old('mission_statement') }}</textarea>
            @error('mission_statement')
                <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Project Information -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <h3 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                <i data-lucide="briefcase" class="w-4 h-4 mr-2 text-[#7A7A7A]"></i>
                Project Information
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Project Title <span class="text-[#D55672]">*</span>
                    </label>
                    <input type="text" name="project_title" required value="{{ old('project_title') }}"
                           class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                           placeholder="Enter project title">
                    @error('project_title')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Project Description <span class="text-[#D55672]">*</span>
                    </label>
                    <textarea name="project_description" rows="5" required
                              class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                              placeholder="Provide detailed description of the project...">{{ old('project_description') }}</textarea>
                    @error('project_description')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                        Expected Outcomes <span class="text-[#D55672]">*</span>
                    </label>
                    <textarea name="expected_outcomes" rows="4" required
                              class="w-full px-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                              placeholder="What do you expect to achieve with this grant?">{{ old('expected_outcomes') }}</textarea>
                    @error('expected_outcomes')
                        <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Funding Request -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <h3 class="text-sm font-bold text-[#0D1B23] mb-4 flex items-center">
                <i data-lucide="circle-dollar-sign" class="w-4 h-4 mr-2 text-[#7A7A7A]"></i>
                Funding Request
            </h3>
            <div>
                <label class="block text-sm font-medium text-[#4A4A4A] mb-1.5">
                    Requested Amount <span class="text-[#D55672]">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[#7A7A7A] text-sm">{{ $settings->currency }}</span>
                    <input type="number" name="requested_amount" step="0.01" min="1" required
                           value="{{ old('requested_amount') }}"
                           class="w-full pl-12 pr-4 py-3 rounded-lg bg-white border border-[#C8D1D1] text-[#0D1B23] text-sm focus:border-primary-500 focus:ring-1 focus:ring-primary-200 transition-colors" 
                           placeholder="0.00">
                </div>
                @error('requested_amount')
                    <p class="text-[#D55672] text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Terms and Conditions -->
        <div class="bg-[#F9F9F9] rounded-lg p-4 border border-[#E0E5E5]">
            <label class="flex items-start space-x-3 cursor-pointer">
                <input type="checkbox" required
                       class="w-4 h-4 rounded border-[#C8D1D1] text-primary-500 focus:ring-primary-200 mt-0.5">
                <span class="text-sm text-[#4A4A4A]">
                    I certify that I am authorized to submit this application on behalf of the organization and that all information provided is accurate and complete.
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
