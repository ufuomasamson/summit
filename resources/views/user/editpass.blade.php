@extends('layouts.bankapp')
@section('title', $title)
@section('content')

<div x-data="{ 
    showCurrent: false, 
    showNew: false, 
    showConfirm: false,
    currentPassword: '',
    newPassword: '',
    confirmPassword: '',
    passwordStrength: 0,
    
    checkPasswordStrength() {
        let strength = 0;
        if (this.newPassword.length >= 8) strength++;
        if (/[a-z]/.test(this.newPassword)) strength++;
        if (/[A-Z]/.test(this.newPassword)) strength++;
        if (/[0-9]/.test(this.newPassword)) strength++;
        if (/[^a-zA-Z0-9]/.test(this.newPassword)) strength++;
        this.passwordStrength = strength;
    }
}" class="space-y-6 pb-24">

    <!-- Page Header -->
    <div class="animate-fade-in">
        <a href="{{ route('profile') }}" class="inline-flex items-center space-x-2 text-gray-400 hover:text-forest-400 transition-colors mb-4 group">
            <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-sm font-semibold">Back to Profile</span>
        </a>
        <h1 class="text-2xl md:text-3xl font-display font-bold mb-2">
            Security & Password
        </h1>
        <p class="text-gray-400">Update your account password to keep your account secure.</p>
    </div>

    <!-- Alert Messages -->
    <x-danger-alert />
    <x-success-alert />

    <!-- Password Change Form -->
    <div class="gradient-card rounded-3xl p-6 md:p-8 animate-slide-up relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-champagne-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-forest-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <!-- Card Header -->
            <div class="flex items-start space-x-4 mb-6">
                <div class="w-12 h-12 rounded-xl bg-champagne-500/20 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="key" class="w-6 h-6 text-champagne-400"></i>
                </div>
                <div>
                    <h2 class="text-xl font-display font-bold text-white mb-1">Change Your Password</h2>
                    <p class="text-sm text-gray-400">Choose a strong password to protect your account</p>
                </div>
            </div>

            <form action="{{ route('updateuserpass') }}" method="post" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- Security Notice -->
                <div class="bg-forest-500/5 border border-forest-500/20 rounded-xl p-4">
                    <div class="flex items-start space-x-3">
                        <i data-lucide="info" class="w-5 h-5 text-forest-400 flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-xs text-gray-300 mb-2">Password Requirements:</p>
                            <ul class="text-xs text-gray-400 space-y-1">
                                <li class="flex items-center space-x-2">
                                    <span :class="newPassword.length >= 8 ? 'text-green-400' : 'text-gray-500'">•</span>
                                    <span>At least 8 characters</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span :class="/[A-Z]/.test(newPassword) ? 'text-green-400' : 'text-gray-500'">•</span>
                                    <span>One uppercase letter</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span :class="/[a-z]/.test(newPassword) ? 'text-green-400' : 'text-gray-500'">•</span>
                                    <span>One lowercase letter</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span :class="/[0-9]/.test(newPassword) ? 'text-green-400' : 'text-gray-500'">•</span>
                                    <span>One number</span>
                                </li>
                                <li class="flex items-center space-x-2">
                                    <span :class="/[^a-zA-Z0-9]/.test(newPassword) ? 'text-green-400' : 'text-gray-500'">•</span>
                                    <span>One special character</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Current Password -->
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-gray-400">
                        Current Password
                    </label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" 
                               name="current_password" 
                               x-model="currentPassword"
                               required
                               autocomplete="current-password"
                               class="w-full px-4 py-3 pr-12 rounded-lg bg-slate-900/50 border-2 border-slate-700 focus:border-forest-500 focus:ring-0 text-white transition-all"
                               placeholder="Enter your current password">
                        <button type="button" 
                                @click="showCurrent = !showCurrent; setTimeout(() => lucide.createIcons(), 50)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-forest-400 transition-colors">
                            <i :data-lucide="showCurrent ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-gray-400">
                        New Password
                    </label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" 
                               name="password" 
                               x-model="newPassword"
                               @input="checkPasswordStrength()"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-3 pr-12 rounded-lg bg-slate-900/50 border-2 border-slate-700 focus:border-champagne-500 focus:ring-0 text-white transition-all"
                               placeholder="Enter new password">
                        <button type="button" 
                                @click="showNew = !showNew; setTimeout(() => lucide.createIcons(), 50)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-champagne-400 transition-colors">
                            <i :data-lucide="showNew ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                    
                    <!-- Password Strength Indicator -->
                    <div x-show="newPassword.length > 0" class="mt-2">
                        <div class="flex space-x-1 mb-1">
                            <div class="flex-1 h-1 rounded-full transition-all"
                                 :class="passwordStrength >= 1 ? (passwordStrength >= 4 ? 'bg-green-500' : passwordStrength >= 3 ? 'bg-yellow-500' : 'bg-red-500') : 'bg-slate-700'"></div>
                            <div class="flex-1 h-1 rounded-full transition-all"
                                 :class="passwordStrength >= 2 ? (passwordStrength >= 4 ? 'bg-green-500' : passwordStrength >= 3 ? 'bg-yellow-500' : 'bg-red-500') : 'bg-slate-700'"></div>
                            <div class="flex-1 h-1 rounded-full transition-all"
                                 :class="passwordStrength >= 3 ? (passwordStrength >= 4 ? 'bg-green-500' : 'bg-yellow-500') : 'bg-slate-700'"></div>
                            <div class="flex-1 h-1 rounded-full transition-all"
                                 :class="passwordStrength >= 4 ? 'bg-green-500' : 'bg-slate-700'"></div>
                            <div class="flex-1 h-1 rounded-full transition-all"
                                 :class="passwordStrength >= 5 ? 'bg-green-500' : 'bg-slate-700'"></div>
                        </div>
                        <p class="text-xs" 
                           :class="passwordStrength >= 4 ? 'text-green-400' : passwordStrength >= 3 ? 'text-yellow-400' : 'text-red-400'">
                            <span x-show="passwordStrength <= 2">Weak password</span>
                            <span x-show="passwordStrength === 3">Good password</span>
                            <span x-show="passwordStrength >= 4">Strong password</span>
                        </p>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-xs uppercase tracking-wider font-semibold mb-2 text-gray-400">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" 
                               name="password_confirmation" 
                               x-model="confirmPassword"
                               required
                               autocomplete="new-password"
                               class="w-full px-4 py-3 pr-12 rounded-lg bg-slate-900/50 border-2 border-slate-700 focus:border-champagne-500 focus:ring-0 text-white transition-all"
                               placeholder="Confirm new password">
                        <button type="button" 
                                @click="showConfirm = !showConfirm; setTimeout(() => lucide.createIcons(), 50)"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-champagne-400 transition-colors">
                            <i :data-lucide="showConfirm ? 'eye-off' : 'eye'" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <p x-show="confirmPassword && newPassword !== confirmPassword" 
                       class="text-xs text-red-400 mt-2 flex items-center space-x-1">
                        <i data-lucide="alert-circle" class="w-3 h-3"></i>
                        <span>Passwords do not match</span>
                    </p>
                    <p x-show="confirmPassword && newPassword === confirmPassword" 
                       class="text-xs text-green-400 mt-2 flex items-center space-x-1">
                        <i data-lucide="check-circle" class="w-3 h-3"></i>
                        <span>Passwords match</span>
                    </p>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        :disabled="!currentPassword || !newPassword || !confirmPassword || newPassword !== confirmPassword"
                        class="w-full px-6 py-4 rounded-xl gradient-primary hover:shadow-glow-green transition-all duration-200 font-bold text-lg hover-lift disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2">
                    <i data-lucide="check" class="w-5 h-5"></i>
                    <span>Update Password</span>
                </button>
                
                <p class="text-xs text-center text-gray-400">
                    <i data-lucide="lock" class="w-3 h-3 inline mr-1"></i>
                    Your password is encrypted with industry-standard security
                </p>
            </form>
        </div>
    </div>

    <!-- Security Tips -->
    <div class="glass-effect rounded-2xl p-6">
        <h3 class="text-lg font-display font-bold mb-4 flex items-center">
            <span class="w-10 h-10 rounded-xl bg-forest-500/20 flex items-center justify-center mr-3">
                <i data-lucide="shield-check" class="w-5 h-5 text-forest-400"></i>
            </span>
            Security Best Practices
        </h3>
        
        <div class="space-y-3">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-champagne-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="check" class="w-4 h-4 text-champagne-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white mb-1">Use a Unique Password</p>
                    <p class="text-xs text-gray-400">Don't reuse passwords from other websites or services.</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-champagne-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="check" class="w-4 h-4 text-champagne-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white mb-1">Enable Two-Factor Authentication</p>
                    <p class="text-xs text-gray-400">Add an extra layer of security with transaction PIN verification.</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-champagne-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="check" class="w-4 h-4 text-champagne-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white mb-1">Change Regularly</p>
                    <p class="text-xs text-gray-400">Update your password every 3-6 months for maximum security.</p>
                </div>
            </div>
            
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-champagne-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <i data-lucide="check" class="w-4 h-4 text-champagne-400"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white mb-1">Never Share</p>
                    <p class="text-xs text-gray-400">We'll never ask for your password via email or phone.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Lucide icons after Alpine is ready
    document.addEventListener('alpine:initialized', () => {
        lucide.createIcons();
        
        // Watch for changes and reinitialize icons
        document.addEventListener('click', function(e) {
            setTimeout(() => lucide.createIcons(), 100);
        });
    });
</script>

@endsection
