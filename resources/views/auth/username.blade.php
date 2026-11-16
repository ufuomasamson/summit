@extends('layouts.guest2')

@section('title', 'Create Username')

@section('content')

<div class="w-full max-w-6xl mx-auto animate-fade-in">
    <div class="grid lg:grid-cols-2 gap-8 items-center">
        
        <!-- Left Side - Username Info -->
        <div class="hidden lg:block space-y-8 animate-slide-in">
            <!-- Logo -->
            <div class="mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-12 w-auto mb-6">
            </div>
            
            <!-- Welcome Content -->
            <div class="space-y-6">
                <h1 class="font-serif text-5xl font-bold text-white leading-tight">
                    One More
                    <span class="bg-gradient-to-r from-forest-400 to-champagne-400 bg-clip-text text-transparent">
                        Step
                    </span>
                </h1>
                <p class="text-lg text-gray-300 leading-relaxed">
                    Choose a unique username that will be used to identify your {{ $settings->site_name }} account.
                </p>
            </div>
            
            <!-- Username Tips -->
            <div class="space-y-4">
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Username Guidelines</h3>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-forest-500/20 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-4 h-4 text-forest-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Be Unique</p>
                        <p class="text-xs text-gray-400">Choose a username that's not already taken</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-champagne-500/20 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-4 h-4 text-champagne-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Keep it Simple</p>
                        <p class="text-xs text-gray-400">Easy to remember and type</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-forest-500/20 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-4 h-4 text-forest-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">No Spaces</p>
                        <p class="text-xs text-gray-400">Use letters, numbers, and underscores only</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-champagne-500/20 flex items-center justify-center mt-0.5">
                        <i data-lucide="check" class="w-4 h-4 text-champagne-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white mb-1">Professional</p>
                        <p class="text-xs text-gray-400">Choose something appropriate for banking</p>
                    </div>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="pt-8">
                <div class="bg-forest-500/10 border border-forest-500/20 rounded-2xl p-6">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="info" class="w-6 h-6 text-forest-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-sm font-semibold text-white mb-2">Important</p>
                            <p class="text-xs text-gray-400 leading-relaxed">
                                Your username will be visible to other users and cannot be changed later. Choose carefully!
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Side - Username Form -->
        <div class="w-full">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ branding_url($settings->logo) }}" 
                     alt="{{ $settings->site_name }}" 
                     class="h-10 w-auto mx-auto">
            </div>
            
            <!-- Auth Card -->
            <div class="auth-card rounded-3xl p-8 md:p-10 space-y-8">
                
                <!-- Header -->
                <div class="text-center space-y-3">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-forest-500/20 mb-4">
                        <i data-lucide="user-circle" class="w-8 h-8 text-forest-400"></i>
                    </div>
                    <h2 class="font-serif text-3xl font-bold text-white">Choose Your Username</h2>
                    <p class="text-gray-400">Select a unique username for your account.</p>
                </div>
                
                <!-- Success Alert -->
                @if(Session::has('status'))
                    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="check-circle" class="w-5 h-5 text-green-400 flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-green-300">{{ Session::get('status') }}</p>
                    </div>
                @endif
                
                <!-- Username Form -->
                <form method="POST" action="{{ route('addusername') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Username Field -->
                    <div class="space-y-2">
                        <label for="username" class="block text-sm font-semibold text-gray-300">
                            Username <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i data-lucide="at-sign" class="w-5 h-5 text-gray-500"></i>
                            </div>
                            <input 
                                id="username"
                                type="text" 
                                name="username" 
                                required 
                                autofocus
                                placeholder="Enter unique username"
                                pattern="[a-zA-Z0-9_]+"
                                class="auth-input w-full pl-12 pr-4 py-3.5 rounded-xl text-sm font-medium focus:ring-0 transition-all"
                            />
                        </div>
                        @if ($errors->has('username'))
                            <p class="text-xs text-red-400 mt-1">{{ $errors->first('username') }}</p>
                        @endif
                        <p class="text-xs text-gray-500">Only letters, numbers, and underscores are allowed</p>
                    </div>
                    
                    <!-- Examples -->
                    <div class="bg-gray-800/30 border border-gray-700/50 rounded-xl p-4">
                        <p class="text-xs font-semibold text-gray-400 mb-2 uppercase tracking-wider">Example Usernames:</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-forest-500/10 text-forest-400 text-xs rounded-lg border border-forest-500/20">john_doe</span>
                            <span class="px-3 py-1 bg-champagne-500/10 text-champagne-400 text-xs rounded-lg border border-champagne-500/20">jane_smith_2024</span>
                            <span class="px-3 py-1 bg-forest-500/10 text-forest-400 text-xs rounded-lg border border-forest-500/20">user_123</span>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        class="auth-button w-full py-3.5 rounded-xl font-semibold text-white text-sm flex items-center justify-center space-x-2 group"
                    >
                        <span>Complete Setup</span>
                        <i data-lucide="check" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    </button>
                </form>
                
                <!-- Footer Info -->
                <div class="pt-6 border-t border-gray-700/50">
                    <p class="text-xs text-gray-500 text-center leading-relaxed">
                        By completing this step, you agree to follow our community guidelines and terms of service.
                    </p>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    // Prevent spaces in username input
    document.addEventListener('DOMContentLoaded', function() {
        const usernameInput = document.getElementById('username');
        if (usernameInput) {
            usernameInput.addEventListener('keypress', function(e) {
                // Prevent space character
                if (e.which === 32) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Remove any non-alphanumeric characters except underscore
            usernameInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
            });
        }
    });
</script>
@endsection