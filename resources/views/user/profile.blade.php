@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ 
    uploadModal: false, 
    pinModal: false,
    passwordModal: false,
    editMode: false,
    previewImage: null
}" class="space-y-6 pb-24">

    <x-danger-alert />
    <x-success-alert />

    <!-- Profile Header Section -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <!-- Profile Photo with Upload Overlay -->
            <div class="relative group">
                @if(Auth::user()->profile_photo_path)
                    <img src="{{ profile_photo_url(Auth::user()) }}" 
                         alt="{{ Auth::user()->name }}"
                         class="w-24 h-24 rounded-xl object-cover border-2 border-[#C8D1D1]">
                @else
                    <div class="w-24 h-24 rounded-xl bg-primary-600 flex items-center justify-center border-2 border-[#C8D1D1]">
                        <span class="text-3xl font-semibold text-[#0D1B23]">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->lastname ?? '', 0, 1)) }}
                        </span>
                    </div>
                @endif
                
                <!-- Upload Overlay -->
                <button @click.prevent="uploadModal = true" 
                        type="button"
                        class="absolute inset-0 bg-black/60 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center">
                    <i data-lucide="camera" class="w-6 h-6 text-[#0D1B23] mb-1"></i>
                    <span class="text-xs font-semibold text-[#0D1B23]">Change</span>
                </button>
            </div>

                <!-- User Info -->
                <div class="flex-1 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-3 mb-2">
                        <h1 class="text-2xl font-semibold text-[#0D1B23]">
                            {{ Auth::user()->name }} {{ Auth::user()->lastname }}
                        </h1>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#DFE5E5] text-[#4A4A4A] border border-[#C8D1D1]">
                            {{ Auth::user()->accounttype }}
                        </span>
                    </div>
                    <p class="text-[#7A7A7A] mb-4">Account #{{ Auth::user()->usernumber }}</p>
                    
                    <!-- Quick Stats -->
                    <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                        <div class="flex items-center space-x-2 text-sm">
                            <i data-lucide="mail" class="w-4 h-4 text-[#7A7A7A]"></i>
                            <span class="text-[#4A4A4A]">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm">
                            <i data-lucide="phone" class="w-4 h-4 text-[#7A7A7A]"></i>
                            <span class="text-[#4A4A4A]">{{ Auth::user()->phone }}</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#7A7A7A]"></i>
                            <span class="text-[#4A4A4A]">{{ Auth::user()->country }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-2">
                    <button @click.prevent="pinModal = true" 
                            type="button"
                            class="px-5 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#DFE5E5] border border-[#C8D1D1] transition-colors flex items-center space-x-2">
                        <i data-lucide="shield" class="w-4 h-4 text-primary-500"></i>
                        <span class="text-sm font-semibold text-[#4A4A4A]">Change PIN</span>
                    </button>
                    <button @click.prevent="passwordModal = true"
                            type="button"
                            class="px-5 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#DFE5E5] border border-[#C8D1D1] transition-colors flex items-center space-x-2">
                        <i data-lucide="key" class="w-4 h-4 text-[#7A7A7A]"></i>
                        <span class="text-sm font-semibold text-[#4A4A4A]">Password</span>
                    </button>
                </div>
            </div>
    </div>

    <!-- Personal Information -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-[#0D1B23] flex items-center">
                <span class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                    <i data-lucide="user" class="w-4 h-4 text-primary-500"></i>
                </span>
                Personal Information
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Full Name -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-primary-300 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-wider font-semibold text-[#7A7A7A] mb-1">Full Name</p>
                        <p class="text-base font-semibold text-[#0D1B23]">{{ Auth::user()->name }} {{ Auth::user()->lastname }}</p>
                    </div>
                    <i data-lucide="user-check" class="w-4 h-4 text-primary-500 mt-1"></i>
                </div>
            </div>

            <!-- Date of Birth -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-primary-300 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-wider font-semibold text-[#7A7A7A] mb-1">Date of Birth</p>
                        <p class="text-base font-semibold text-[#0D1B23]">{{ \Carbon\Carbon::parse(Auth::user()->dob)->format('M d, Y') }}</p>
                    </div>
                    <i data-lucide="calendar" class="w-4 h-4 text-[#7A7A7A] mt-1"></i>
                </div>
            </div>

            <!-- Email -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-primary-300 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-wider font-semibold text-[#7A7A7A] mb-1">Email Address</p>
                        <p class="text-base font-semibold text-[#0D1B23] break-all">{{ Auth::user()->email }}</p>
                    </div>
                    <i data-lucide="mail" class="w-4 h-4 text-primary-500 mt-1"></i>
                </div>
            </div>

            <!-- Phone -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-primary-300 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-wider font-semibold text-[#7A7A7A] mb-1">Phone Number</p>
                        <p class="text-base font-semibold text-[#0D1B23]">{{ Auth::user()->phone }}</p>
                    </div>
                    <i data-lucide="phone" class="w-4 h-4 text-[#7A7A7A] mt-1"></i>
                </div>
            </div>

            <!-- Country -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-primary-300 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-wider font-semibold text-[#7A7A7A] mb-1">Country</p>
                        <p class="text-base font-semibold text-[#0D1B23]">{{ Auth::user()->country }}</p>
                    </div>
                    <i data-lucide="globe" class="w-4 h-4 text-primary-500 mt-1"></i>
                </div>
            </div>

            <!-- City/Address -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-primary-300 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <p class="text-xs uppercase tracking-wider font-semibold text-[#7A7A7A] mb-1">City Address</p>
                        <p class="text-base font-semibold text-[#0D1B23]">{{ Auth::user()->address }}</p>
                    </div>
                    <i data-lucide="map-pin" class="w-4 h-4 text-[#7A7A7A] mt-1"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Account Settings -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <h2 class="text-lg font-semibold text-[#0D1B23] flex items-center mb-6">
            <span class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                <i data-lucide="settings" class="w-4 h-4 text-[#7A7A7A]"></i>
            </span>
            Account Settings
        </h2>

        <div class="space-y-3">
            <!-- Currency Preference -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-[#C8D1D1] transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="dollar-sign" class="w-4 h-4 text-[#7A7A7A]"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23]">Currency Preference</p>
                            <p class="text-xs text-[#7A7A7A]">Default transaction currency</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 rounded-lg bg-primary-100 border border-primary-300">
                        <span class="text-sm font-bold text-primary-400">{{ Auth::user()->currency }}</span>
                    </div>
                </div>
            </div>

            <!-- Account Type -->
            <div class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-[#C8D1D1] transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="briefcase" class="w-4 h-4 text-primary-500"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0D1B23]">Account Type</p>
                            <p class="text-xs text-[#7A7A7A]">Your banking tier</p>
                        </div>
                    </div>
                    <div class="px-4 py-2 rounded-lg bg-[#DFE5E5] border border-[#C8D1D1]">
                        <span class="text-sm font-semibold text-[#4A4A4A]">{{ Auth::user()->accounttype }}</span>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <button @click.prevent="passwordModal = true"
                    type="button" 
                    class="bg-white/30 rounded-xl p-4 border border-[#E0E5E5] hover:border-[#C8D1D1] transition-colors group flex items-center justify-between w-full">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-4 h-4 text-primary-500"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0D1B23]">Security & Password</p>
                        <p class="text-xs text-[#7A7A7A]">Manage your security settings</p>
                    </div>
                </div>
                <i data-lucide="chevron-right" class="w-5 h-5 text-[#7A7A7A] group-hover:text-primary-500 transition-colors"></i>
            </button>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white border border-[#E0E5E5] rounded-xl p-6">
        <h2 class="text-lg font-semibold text-[#0D1B23] flex items-center mb-6">
            <span class="w-9 h-9 rounded-lg bg-red-500/15 flex items-center justify-center mr-3">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-[#D55672]"></i>
            </span>
            Session Management
        </h2>

        <div class="bg-red-500/5 border border-red-500/20 rounded-xl p-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <p class="text-sm font-semibold text-[#0D1B23] mb-1">Logout from all devices</p>
                    <p class="text-xs text-[#7A7A7A]">End all active sessions and require re-authentication</p>
                </div>
                <button type="button"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                        class="px-6 py-2.5 rounded-lg bg-red-500/20 hover:bg-red-500/30 border border-red-500/40 transition-all font-semibold text-sm text-[#D55672] hover:text-red-200 flex items-center space-x-2">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Profile Photo Modal -->
<div x-show="uploadModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="uploadModal = false"
     class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="uploadModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         class="bg-[#F9F9F9] rounded-xl max-w-md w-full border border-[#E0E5E5] overflow-hidden">
        
        <!-- Modal Header -->
        <div class="border-b border-[#E0E5E5] p-5 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#0D1B23] flex items-center">
                    <span class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                        <i data-lucide="camera" class="w-4 h-4 text-[#7A7A7A]"></i>
                    </span>
                    Update Profile Photo
                </h3>
                <p class="text-sm text-[#7A7A7A] ml-12">Choose a new profile picture</p>
            </div>
            <button @click="uploadModal = false" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-[#7A7A7A]"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('updateprofilephoto') }}" method="post" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf

            <!-- Preview Area -->
            <div class="flex justify-center mb-4">
                <div class="relative">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ profile_photo_url(Auth::user()) }}" 
                             alt="Current"
                             class="w-24 h-24 rounded-xl object-cover border-2 border-[#C8D1D1]">
                    @else
                        <div class="w-24 h-24 rounded-xl bg-primary-600 flex items-center justify-center border-2 border-[#C8D1D1]">
                            <span class="text-3xl font-semibold text-[#0D1B23]">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- File Upload -->
            <div>
                <label class="block text-sm font-semibold mb-3 text-[#0D1B23]">New Profile Picture</label>
                <div class="relative border-2 border-dashed border-[#E0E5E5] hover:border-forest-500/50 rounded-xl transition-all">
                    <input type="file" name="photo" required accept="image/*" 
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-3 rounded-xl bg-[#DFE5E5] flex items-center justify-center">
                            <i data-lucide="upload" class="w-8 h-8 text-[#7A7A7A]"></i>
                        </div>
                        <p class="text-sm font-semibold text-[#4A4A4A] mb-1">Click to upload new photo</p>
                        <p class="text-xs text-[#7A7A7A]">PNG, JPG up to 5MB</p>
                    </div>
                </div>
            </div>

            <!-- Info Banner -->
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="info" class="w-5 h-5 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#4A4A4A]">Your profile photo will be displayed across your account and in transaction history.</p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full px-6 py-3.5 rounded-xl bg-primary-600 hover:bg-primary-500 transition-colors font-semibold text-white flex items-center justify-center space-x-2">
                <i data-lucide="check" class="w-5 h-5"></i>
                <span>Update Profile Photo</span>
            </button>
        </form>
    </div>
</div>

<!-- Change PIN Modal -->
<div x-show="pinModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="pinModal = false"
     class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="pinModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         x-data="{ showPin: false, showPassword: false, pin: '', password: '' }"
         class="bg-[#F9F9F9] rounded-xl max-w-md w-full border border-[#E0E5E5] overflow-hidden">
        
        <!-- Modal Header -->
        <div class="border-b border-[#E0E5E5] p-5 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#0D1B23] flex items-center">
                    <span class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                        <i data-lucide="shield" class="w-4 h-4 text-primary-500"></i>
                    </span>
                    Change Transaction PIN
                </h3>
                <p class="text-sm text-[#7A7A7A] ml-12">Update your security PIN</p>
            </div>
            <button @click="pinModal = false" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-[#7A7A7A]"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('changepin') }}" method="post" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Security Notice -->
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-[#3e4e83] flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#4A4A4A]">Your PIN is used to authorize transactions. Choose a secure 4-6 digit PIN that you can remember.</p>
                </div>
            </div>

            <!-- New PIN -->
            <div>
                <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">New Transaction PIN</label>
                <div class="relative">
                    <input :type="showPin ? 'text' : 'password'" 
                           name="pin" 
                           x-model="pin"
                           required
                           minlength="4"
                           maxlength="6"
                           inputmode="numeric"
                           pattern="[0-9]{4,6}"
                           @input="pin = pin.replace(/[^0-9]/g, '')"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[#F9F9F9] border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all font-mono text-center text-lg tracking-widest"
                           placeholder="••••••">
                    <button type="button" 
                            @click="showPin = !showPin; setTimeout(() => lucide.createIcons(), 50)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#7A7A7A] hover:text-primary-500 transition-colors">
                        <i :data-lucide="showPin ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="text-xs text-[#7A7A7A] mt-2">Enter a secure 4-6 digit PIN</p>
            </div>

            <!-- Password Confirmation -->
            <div>
                <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Confirm with Password</label>
                <div class="relative">
                    <input :type="showPassword ? 'text' : 'password'" 
                           name="password" 
                           x-model="password"
                           required
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[#F9F9F9] border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-colors"
                           placeholder="Enter your account password">
                    <button type="button" 
                            @click="showPassword = !showPassword; setTimeout(() => lucide.createIcons(), 50)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors">
                        <i :data-lucide="showPassword ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="text-xs text-[#7A7A7A] mt-2">Required for security verification</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="!pin || !password || pin.length < 4"
                    class="w-full px-6 py-3.5 rounded-xl bg-primary-600 hover:bg-primary-500 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="lock" class="w-5 h-5"></i>
                <span>Update Transaction PIN</span>
            </button>
            
            <p class="text-xs text-center text-[#7A7A7A]">
                <i data-lucide="shield-check" class="w-3 h-3 inline mr-1"></i>
                Your PIN is encrypted and never stored in plain text
            </p>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div x-show="passwordModal" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     @click="passwordModal = false"
     class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 flex items-center justify-center p-4"
     style="display: none;">
    <div x-show="passwordModal"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="scale-95 opacity-0"
         x-transition:enter-end="scale-100 opacity-100"
         @click.stop
         x-data="{ showCurrent: false, showNew: false, showConfirm: false, currentPassword: '', newPassword: '', confirmPassword: '' }"
         class="bg-[#F9F9F9] rounded-xl max-w-md w-full border border-[#E0E5E5] overflow-hidden">
        
        <!-- Modal Header -->
        <div class="border-b border-[#E0E5E5] p-5 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-[#0D1B23] flex items-center">
                    <span class="w-9 h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center mr-3">
                        <i data-lucide="key" class="w-4 h-4 text-[#7A7A7A]"></i>
                    </span>
                    Change Password
                </h3>
                <p class="text-sm text-[#7A7A7A] ml-12">Update your account password</p>
            </div>
            <button @click="passwordModal = false" class="p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors">
                <i data-lucide="x" class="w-5 h-5 text-[#7A7A7A]"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <form action="{{ route('updateuserpass') }}" method="post" class="p-6 space-y-5">
            @csrf
            @method('PUT')

            <!-- Security Notice -->
            <div class="bg-[#F9F9F9] border border-[#E0E5E5] rounded-xl p-4">
                <div class="flex items-start space-x-3">
                    <i data-lucide="info" class="w-5 h-5 text-[#7A7A7A] flex-shrink-0 mt-0.5"></i>
                    <p class="text-xs text-[#4A4A4A]">Choose a strong password with at least 8 characters, including uppercase, lowercase, numbers, and special characters.</p>
                </div>
            </div>

            <!-- Current Password -->
            <div>
                <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Current Password</label>
                <div class="relative">
                    <input :type="showCurrent ? 'text' : 'password'" 
                           name="current_password" 
                           x-model="currentPassword"
                           required
                           autocomplete="current-password"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[#F9F9F9] border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-all"
                           placeholder="Enter your current password">
                    <button type="button" 
                            @click="showCurrent = !showCurrent; setTimeout(() => lucide.createIcons(), 50)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#7A7A7A] hover:text-primary-500 transition-colors">
                        <i :data-lucide="showCurrent ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">New Password</label>
                <div class="relative">
                    <input :type="showNew ? 'text' : 'password'" 
                           name="password" 
                           x-model="newPassword"
                           required
                           autocomplete="new-password"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[#F9F9F9] border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-colors"
                           placeholder="Enter new password">
                    <button type="button" 
                            @click="showNew = !showNew; setTimeout(() => lucide.createIcons(), 50)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors">
                        <i :data-lucide="showNew ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-[#7A7A7A]">Confirm New Password</label>
                <div class="relative">
                    <input :type="showConfirm ? 'text' : 'password'" 
                           name="password_confirmation" 
                           x-model="confirmPassword"
                           required
                           autocomplete="new-password"
                           class="w-full px-4 py-3 pr-12 rounded-lg bg-[#F9F9F9] border-2 border-[#E0E5E5] focus:border-primary-500 focus:ring-0 text-[#0D1B23] transition-colors"
                           placeholder="Confirm new password">
                    <button type="button" 
                            @click="showConfirm = !showConfirm; setTimeout(() => lucide.createIcons(), 50)"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-[#7A7A7A] hover:text-[#4A4A4A] transition-colors">
                        <i :data-lucide="showConfirm ? 'eye-off' : 'eye'" class="w-4 h-4"></i>
                    </button>
                </div>
                <p class="text-xs text-[#7A7A7A] mt-2">Must match the new password</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" :disabled="!currentPassword || !newPassword || !confirmPassword"
                    class="w-full px-6 py-3.5 rounded-xl bg-primary-600 hover:bg-primary-500 transition-colors font-semibold text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                <i data-lucide="check" class="w-5 h-5"></i>
                <span>Change Password</span>
            </button>
            
            <p class="text-xs text-center text-[#7A7A7A]">
                <i data-lucide="lock" class="w-3 h-3 inline mr-1"></i>
                Your password is encrypted with industry-standard security
            </p>
        </form>
    </div>
</div>

</div>

<script>
    // Initialize Lucide icons after Alpine is ready
    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
        
        // Watch for modal changes and reinitialize icons
        document.addEventListener('click', function(e) {
            setTimeout(() => lucide.createIcons(), 100);
        });
    });
</script>

@endsection

