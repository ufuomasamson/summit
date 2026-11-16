<!doctype html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="csrf_token" content="{{ csrf_token() }}" id="csrf_token" data-turbolinks-permanent>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="{{ $settings->pwa_theme_color ?? '#0a1929' }}">
    <title>{{ $settings->site_name }} | @yield('title')</title>
    <meta name="description" content="{{ $settings->pwa_description ?? $settings->site_name }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
    <link rel="icon" type="image/png" href="{{ branding_url($settings->favicon) }}" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ branding_url($settings->logo) }}">
    
    @if($settings->pwa_enabled ?? true)
    <!-- PWA Meta Tags -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ $settings->pwa_short_name ?? $settings->site_name }}">
    <meta name="apple-mobile-web-app-title" content="{{ $settings->pwa_short_name ?? $settings->site_name }}">
    
    <!-- PWA Manifest -->
    <link rel="manifest" href="{{ route('manifest') }}">
    
    <!-- Apple Touch Icons -->
    <link rel="apple-touch-icon" href="{{ branding_url($settings->logo) }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ branding_url($settings->logo) }}">
    <link rel="apple-touch-icon" sizes="167x167" href="{{ branding_url($settings->logo) }}">
    @endif
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
     
    
    
    <!-- Modern Icons - Lucide -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Sophisticated Banking Fonts - Playfair Display (Luxury serif) & Inter (Clean sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                /* Ally Bank Primary Green */
                primary: {
                      50:  '#fdf2f2',
                    100: '#fce4e4',
                    200: '#f8bcbc',
                    300: '#f08080',
                    400: '#e03030',
                    500: '#c10e0e',  // MAIN - Plyson Red
                    600: '#a30c0c',
                    700: '#8a0a0a',
                    800: '#6b0808',
                    900: '#400505',
                },

                /* Ally Bank Yellow - Accent / Warning */
                champagne: {
                    50:  '#f0f3f9',
                    100: '#dde3f0',
                    200: '#bbc7e2',
                    300: '#8fa3c0',
                    400: '#5a6f9c',
                    500: '#3e4e83',  // MAIN - Plyson Navy
                    600: '#34416d',
                    700: '#2a3357',
                    800: '#1e2440',
                    900: '#131829',
                },

                /* Ally Bank Surfaces - Light Theme */
                slate: {
                    50:  '#FFFFFF',
                    100: '#F9F9F9',
                    200: '#DFE5E5',
                    300: '#BFC9C9',
                    400: '#A0ACAC',
                    500: '#7A8A8A',
                    600: '#5A6868',
                    700: '#3A4848',
                    800: '#1E2C2C',
                    850: '#152020',
                    900: '#0D1B23',
                    950: '#081318',
                },

                /* Light Surfaces */
                cream: {
                    50: '#FFFFFF',
                    100: '#F9F9F9',
                    DEFAULT: '#DFE5E5',
                },

                /* Ally Bank Status Colors */
                success: {
                    light: '#d4f4dd',
                    DEFAULT: '#28A745',
                    dark: '#1e7e34',
                },
                alert: {
                    light: '#fff3cd',
                    DEFAULT: '#3e4e83',
                    dark: '#34416d',
                },
                danger: {
                    light: '#fce4ec',
                    DEFAULT: '#D55672',
                    dark: '#b84460',
                }
            },

            fontFamily: {
                sans: ['Inter', 'system-ui', 'sans-serif'],
                display: ['Playfair Display', 'Georgia', 'serif'],
            },

            boxShadow: {
                'soft': '0 1px 3px rgba(0,0,0,0.08)',
                'medium': '0 4px 12px rgba(0,0,0,0.1)',
                'strong': '0 8px 24px rgba(0,0,0,0.15)',
                'inner-soft': 'inset 0 1px 2px rgba(0,0,0,0.06)',
            },

            animation: {
                'fade-in': 'fadeIn .4s ease-out',
                'slide-up': 'slideUp .5s cubic-bezier(0.16, 1, 0.3, 1)',
                'scale-in': 'scaleIn .3s cubic-bezier(0.16, 1, 0.3, 1)',
                'shimmer': 'shimmer 2s infinite',
            },

            keyframes: {
                fadeIn: { 
                    '0%': { opacity: '0' }, 
                    '100%': { opacity: '1' } 
                },
                slideUp: {
                    '0%': { transform: 'translateY(24px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.92)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                }
            },

            backgroundImage: {
            }
        }
    }
}
</script>
<style>
:root {
    /* Ally Bank Primary Palette */
    --color-primary: #c10e0e;
    --color-primary-dark: #a30c0c;
    --color-primary-light: #ec6262;
    
    /* Ally Bank Accent */
    --champagne: #3e4e83;
    --champagne-dark: #34416d;
    --champagne-light: #8fa3c0;
    
    /* Ally Bank Backgrounds */
    --color-bg-base: #FFFFFF;
    --color-bg-surface: #F9F9F9;
    --color-bg-muted: #DFE5E5;
    --bg-light: #FFFFFF;
    --card-light: #FFFFFF;
    
    /* Ally Bank Status */
    --success: #28A745;
    --warning: #3e4e83;
    --danger: #D55672;
    
    /* Ally Bank Text */
    --color-text-primary: #151515;
    --color-text-secondary: #616161;
    --text-light: #151515;
    --text-muted: #7A7A7A;
    
    /* Ally Bank Borders */
    --color-border: #E0E5E5;
    --border-light: #E0E5E5;
    --border-dark: #C8D1D1;
    --divider: #E0E5E5;
}

/* Ally Bank Light Theme Background */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: var(--color-bg-surface);
    min-height: 100vh;
    color: var(--color-text-primary);
    letter-spacing: -0.011em;
}

/* Clean sans-serif for headings */
.font-display {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    letter-spacing: -0.01em;
}

/* Primary Action Button */
.gradient-primary {
    background: var(--color-primary);
    transition: background 0.2s ease;
}

.gradient-primary:hover {
    background: var(--color-primary-dark);
}

/* Card */
.gradient-card {
    background: #FFFFFF;
    border: 1px solid var(--color-border);
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

/* Glass Effect */
.glass-effect {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid var(--color-border);
}

/* Subtle border */
.border-elegant {
    border-color: var(--color-border);
}

/* Premium Status Badge */
.status-badge {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    padding: 0.35rem 0.75rem;
    border-radius: 0.5rem;
    text-transform: uppercase;
}

/* Smooth Transitions - only on interactive elements */
a, button, input, select, textarea {
    transition-property: color, background-color, border-color, opacity;
    transition-duration: 150ms;
    transition-timing-function: ease;
}

/* Balance Number Styling */
.balance-display {
    font-variant-numeric: tabular-nums;
    letter-spacing: -0.03em;
}

/* Scrollbar */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.12);
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.2);
}



</style>


</head>


<body class="bg-[#F9F9F9] text-[#151515]" x-data="{ mobileMenuOpen: false, sidebarOpen: false }">

    <!-- App Header -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-[#E0E5E5] lg:left-80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 md:h-16">
                <!-- Left: Menu Button -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors">
                    <i data-lucide="menu" class="w-5 h-5 text-[#616161]"></i>
                </button>
                
                <!-- Logo (centered) -->
                <div class="absolute left-1/2 -translate-x-1/2 flex items-center">
                    <img src="{{ branding_url($settings->logo) }}" alt="{{ $settings->site_name }}" class="h-7 md:h-9 w-auto object-contain">
                </div>
                
                <!-- Language Selector -->
                
                
                <!-- Right: Profile -->
                <div class="flex items-center space-x-4 lg:ml-auto">
                    <!-- Notification Bell -->
                    <div x-data="{ 
                        notificationOpen: false,
                        notifications: [],
                        unreadCount: 0,
                        loading: false,
                        
                        async fetchNotifications() {
                            if (this.notifications.length === 0) {
                                this.loading = true;
                                try {
                                    const response = await fetch('{{ route('notifications.recent') }}');
                                    const data = await response.json();
                                    this.notifications = data.notifications;
                                    this.unreadCount = data.unread_count;
                                } catch (error) {
                                    console.error('Error fetching notifications:', error);
                                } finally {
                                    this.loading = false;
                                }
                            }
                        },
                        
                        async markAsRead(id) {
                            try {
                                const response = await fetch(`/dashboard/notifications/${id}/read`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf_token]').content
                                    }
                                });
                                
                                if (response.ok) {
                                    const notification = this.notifications.find(n => n.id === id);
                                    if (notification && !notification.read_at) {
                                        notification.read_at = new Date();
                                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                                    }
                                }
                            } catch (error) {
                                console.error('Error marking notification as read:', error);
                            }
                        },
                        
                        async markAllAsRead() {
                            try {
                                const response = await fetch('{{ route('notifications.readall') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf_token]').content
                                    }
                                });
                                
                                if (response.ok) {
                                    this.notifications.forEach(n => n.read_at = new Date());
                                    this.unreadCount = 0;
                                }
                            } catch (error) {
                                console.error('Error marking all as read:', error);
                            }
                        }
                    }" 
                    class="relative" 
                    @click.away="notificationOpen = false">
                        <button @click="notificationOpen = !notificationOpen; fetchNotifications()" 
                                class="relative p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors">
                            <i data-lucide="bell" class="w-5 h-5 text-[#616161]"></i>
                            <span x-show="unreadCount > 0" 
                                  x-text="unreadCount > 9 ? '9+' : unreadCount"
                                  class="absolute -top-1 -right-1 min-w-[18px] h-[18px] flex items-center justify-center bg-danger rounded-full text-[10px] font-bold px-1"></span>
                        </button>

                        <!-- Notification Dropdown -->
                        <div x-show="notificationOpen"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="fixed left-1/2 -translate-x-1/2 top-16 w-[calc(100vw-1.5rem)] sm:absolute sm:left-auto sm:translate-x-0 sm:top-auto sm:right-0 sm:mt-2 sm:w-96 rounded-xl bg-white border border-[#E0E5E5] shadow-strong overflow-hidden z-50 max-h-[32rem]"
                             style="display: none;">
                            
                            <!-- Header -->
                            <div class="p-4 border-b border-[#E0E5E5] flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-base text-[#151515]">Notifications</h3>
                                    <p class="text-xs text-[#7A7A7A]" x-show="unreadCount > 0">
                                        <span x-text="unreadCount"></span> unread
                                    </p>
                                </div>
                                <button @click="markAllAsRead()" 
                                        x-show="unreadCount > 0"
                                        class="text-xs font-medium text-primary-500 hover:text-primary-600 transition-colors">
                                    Mark all read
                                </button>
                            </div>

                            <!-- Notifications List -->
                            <div class="max-h-96 overflow-y-auto">
                                <!-- Loading State -->
                                <div x-show="loading" class="p-8 text-center">
                                    <div class="inline-block w-8 h-8 border-4 border-primary-500/30 border-t-primary-500 rounded-full animate-spin"></div>
                                </div>

                                <!-- Empty State -->
                                <div x-show="!loading && notifications.length === 0" class="p-8 text-center">
                                    <div class="w-16 h-16 rounded-full bg-[#DFE5E5] flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="bell-off" class="w-8 h-8 text-[#7A7A7A]"></i>
                                    </div>
                                    <p class="text-[#7A7A7A] text-sm">No notifications yet</p>
                                </div>

                                <!-- Notification Items -->
                                <template x-for="notification in notifications" :key="notification.id">
                                    <a href="{{ route('notifications') }}" 
                                       @click="markAsRead(notification.id)"
                                       class="block p-4 border-b border-[#E0E5E5] hover:bg-[#F9F9F9] transition-colors"
                                       :class="{ 'bg-primary-50': !notification.read_at }">
                                        <div class="flex items-start space-x-3">
                                            <!-- Icon -->
                                            <div class="w-10 h-10 rounded-lg flex-shrink-0 flex items-center justify-center"
                                                 :class="{
                                                     'bg-primary-500/15': notification.type === 'transfer' || notification.type === 'deposit',
                                                     'bg-amber-500/15': notification.type === 'password_change' || notification.type === 'login',
                                                     'bg-blue-500/15': notification.type === 'card',
                                                     'bg-orange-500/15': notification.type === 'withdrawal',
                                                     'bg-gray-500/15': !['transfer', 'deposit', 'password_change', 'login', 'card', 'withdrawal'].includes(notification.type)
                                                 }">
                                                <i :data-lucide="notification.icon || 'bell'" class="w-5 h-5 text-[#151515]"></i>
                                            </div>
                                            
                                            <!-- Content -->
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start justify-between gap-2">
                                                    <h4 class="text-sm font-medium text-[#151515]" 
                                                        x-text="notification.title"></h4>
                                                    <span x-show="!notification.read_at" 
                                                          class="w-2 h-2 bg-primary-500 rounded-full flex-shrink-0 mt-1.5"></span>
                                                </div>
                                                <p class="text-xs text-[#616161] mt-1 line-clamp-2" 
                                                   x-text="notification.message"></p>
                                                <p class="text-[10px] text-[#7A7A7A] mt-2" 
                                                   x-text="new Date(notification.created_at).toLocaleString()"></p>
                                            </div>
                                        </div>
                                    </a>
                                </template>
                            </div>

                            <!-- Footer -->
                            <div class="p-3 border-t border-[#E0E5E5] bg-white">
                                <a href="{{ route('notifications') }}" 
                                   class="block text-center text-sm font-medium text-primary-500 hover:text-primary-600 transition-colors">
                                    View All Notifications
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen" class="flex items-center space-x-2 lg:space-x-3 px-2 lg:px-3 py-2 rounded-lg hover:bg-[#DFE5E5] transition-colors group">
                            @if(Auth::user()->profile_photo_path)
                                <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}" 
                                     alt="{{ Auth::user()->name }}" 
                                     class="w-8 h-8 lg:w-9 lg:h-9 rounded-full object-cover border border-[#E0E5E5]">
                            @else
                                <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-full bg-primary-500 flex items-center justify-center font-semibold text-xs lg:text-sm text-white">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="hidden xl:block text-left">
                                <p class="text-sm font-medium text-[#151515]">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-[#7A7A7A]">{{ Auth::user()->accounttype }}</p>
                            </div>
                            <i data-lucide="chevron-down" class="w-3 h-3 lg:w-4 lg:h-4 text-[#7A7A7A]" :class="{ 'rotate-180': profileOpen }"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="profileOpen"
                             @click.away="profileOpen = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-64 lg:w-72 rounded-xl bg-white border border-[#E0E5E5] shadow-strong overflow-hidden z-50"
                             style="display: none;">
                            
                            <!-- User Info Header -->
                            <div class="p-3 lg:p-4 border-b border-[#E0E5E5]">
                                <div class="flex items-center space-x-2 lg:space-x-3">
                                    @if(Auth::user()->profile_photo_path)
                                        <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}" 
                                             alt="{{ Auth::user()->name }}" 
                                             class="w-10 h-10 lg:w-11 lg:h-11 rounded-full object-cover border border-[#E0E5E5]">
                                    @else
                                        <div class="w-10 h-10 lg:w-11 lg:h-11 rounded-full bg-primary-500 flex items-center justify-center font-semibold text-base lg:text-lg text-white">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-[#151515] truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[10px] lg:text-xs text-[#7A7A7A] truncate">{{ Auth::user()->email }}</p>
                                        <span class="inline-flex items-center px-1.5 lg:px-2 py-0.5 rounded-full bg-[#DFE5E5] text-[9px] lg:text-[10px] font-medium text-[#616161] mt-1">
                                            {{ Auth::user()->accounttype }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="p-1.5 lg:p-2">
                                <a href="{{ route('profile') }}" class="flex items-center space-x-2 lg:space-x-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-lg hover:bg-[#F9F9F9] transition-colors group">
                                    <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                                        <i data-lucide="user-cog" class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-[#616161]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-xs lg:text-sm text-[#151515]">Account Settings</p>
                                        <p class="text-[10px] lg:text-xs text-[#7A7A7A]">Manage your profile</p>
                                    </div>
                                </a>

                                <a href="{{ route('profile') }}" class="flex items-center space-x-2 lg:space-x-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-lg hover:bg-[#F9F9F9] transition-colors group">
                                    <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-[#616161]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-xs lg:text-sm text-[#151515]">Security & PIN</p>
                                        <p class="text-[10px] lg:text-xs text-[#7A7A7A]">Update your PIN</p>
                                    </div>
                                </a>

                                <a href="{{ route('accounthistory') }}" class="flex items-center space-x-2 lg:space-x-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-lg hover:bg-[#F9F9F9] transition-colors group">
                                    <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                                        <i data-lucide="receipt" class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-[#616161]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-xs lg:text-sm text-[#151515]">Transaction History</p>
                                        <p class="text-[10px] lg:text-xs text-[#7A7A7A]">View all activities</p>
                                    </div>
                                </a>

                                <a href="{{ route('support') }}" class="flex items-center space-x-2 lg:space-x-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-lg hover:bg-[#F9F9F9] transition-colors group">
                                    <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-lg bg-[#DFE5E5] flex items-center justify-center">
                                        <i data-lucide="headphones" class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-[#616161]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-xs lg:text-sm text-[#151515]">Support Center</p>
                                        <p class="text-[10px] lg:text-xs text-[#7A7A7A]">Get help</p>
                                    </div>
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="p-1.5 lg:p-2 border-t border-[#E0E5E5]">
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                   class="flex items-center space-x-2 lg:space-x-3 px-3 lg:px-4 py-2.5 lg:py-3 rounded-lg hover:bg-red-50 transition-colors group">
                                    <div class="w-8 h-8 lg:w-9 lg:h-9 rounded-lg bg-red-50 flex items-center justify-center">
                                        <i data-lucide="log-out" class="w-3.5 h-3.5 lg:w-4 lg:h-4 text-[#D55672]"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-xs lg:text-sm text-[#D55672]">Log Out</p>
                                        <p class="text-[10px] lg:text-xs text-[#7A7A7A]">Sign out of your account</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="pt-10 md:pt-14 pb-24 md:pb-8 min-h-screen lg:ml-80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @yield('content')
        </div>
    </main>

    <!-- Mobile Navigation Bar -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40">
        <!-- Frosted glass background -->
        <div class="bg-white/95 backdrop-blur-xl border-t border-[#E0E5E5]">
            <div class="relative px-2 pt-1.5 pb-safe">
                <div class="flex items-center justify-around">
                    <!-- Home -->
                    <a href="{{ route('dashboard') }}" class="relative flex flex-col items-center py-1.5 px-3 group min-w-[56px]">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary-100' : 'group-active:bg-[#DFE5E5]' }}">
                                <i data-lucide="home" class="w-[22px] h-[22px] transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}"></i>
                            </div>
                            @if(request()->routeIs('dashboard'))
                            <div class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-500"></div>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors {{ request()->routeIs('dashboard') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}">Home</span>
                    </a>

                    <!-- Activity -->
                    <a href="{{ route('accounthistory') }}" class="relative flex flex-col items-center py-1.5 px-3 group min-w-[56px]">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('accounthistory') ? 'bg-primary-100' : 'group-active:bg-[#DFE5E5]' }}">
                                <i data-lucide="arrow-left-right" class="w-[22px] h-[22px] transition-colors duration-200 {{ request()->routeIs('accounthistory') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}"></i>
                            </div>
                            @if(request()->routeIs('accounthistory'))
                            <div class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-500"></div>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors {{ request()->routeIs('accounthistory') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}">Activity</span>
                    </a>

                    <!-- Center: Send/Transfer -->
                    <a href="{{ route('localtransfer') }}" class="relative flex flex-col items-center py-1 group min-w-[60px]">
                        <div class="w-14 h-14 -mt-5 rounded-2xl bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center shadow-lg shadow-primary-500/20 border-[3px] border-white group-active:scale-95 transition-transform duration-150">
                            <i data-lucide="send" class="w-6 h-6 text-white"></i>
                        </div>
                        <span class="text-[10px] font-semibold mt-0.5 text-primary-500">Transfer</span>
                    </a>

                    <!-- Cards -->
                    <a href="{{ route('cards.index') }}" class="relative flex flex-col items-center py-1.5 px-3 group min-w-[56px]">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('cards.*') ? 'bg-primary-100' : 'group-active:bg-[#DFE5E5]' }}">
                                <i data-lucide="credit-card" class="w-[22px] h-[22px] transition-colors duration-200 {{ request()->routeIs('cards.*') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}"></i>
                            </div>
                            @if(request()->routeIs('cards.*'))
                            <div class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-500"></div>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors {{ request()->routeIs('cards.*') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}">Cards</span>
                    </a>

                    <!-- Account -->
                    <a href="{{ route('profile') }}" class="relative flex flex-col items-center py-1.5 px-3 group min-w-[56px]">
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('profile') ? 'bg-primary-100' : 'group-active:bg-[#DFE5E5]' }}">
                                @if(Auth::user()->profile_photo_path)
                                    <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}" 
                                         alt="{{ Auth::user()->name }}" 
                                         class="w-7 h-7 rounded-full object-cover border {{ request()->routeIs('profile') ? 'border-primary-500' : 'border-[#E0E5E5]' }}">
                                @else
                                    <i data-lucide="user" class="w-[22px] h-[22px] transition-colors duration-200 {{ request()->routeIs('profile') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}"></i>
                                @endif
                            </div>
                            @if(request()->routeIs('profile'))
                            <div class="absolute -bottom-0.5 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-primary-500"></div>
                            @endif
                        </div>
                        <span class="text-[10px] font-medium mt-0.5 transition-colors {{ request()->routeIs('profile') ? 'text-primary-500' : 'text-[#7A7A7A] group-hover:text-[#616161]' }}">Account</span>
                    </a>
                </div>
                
                <!-- Safe area spacer -->
                <div class="h-1"></div>
            </div>
        </div>
    </nav>



    <!-- Modern Sidebar Panel -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 lg:hidden"
         style="display: none;">
    </div>

    <aside x-show="sidebarOpen"
           x-transition:enter="transition ease-out duration-300 transform"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in duration-200 transform"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
           class="fixed left-0 top-0 bottom-0 w-80 bg-white border-r border-[#E0E5E5] z-50 overflow-y-auto lg:!block lg:!translate-x-0"
           style="display: none;">
        
        <!-- Close Button -->
        <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 p-2 rounded-lg hover:bg-[#DFE5E5] transition-colors">
            <i data-lucide="x" class="w-5 h-5 text-[#7A7A7A]"></i>
        </button>
        
        <!-- Profile Section -->
        <div class="p-6 border-b border-[#E0E5E5]">
            <div class="flex items-start space-x-4">
                @if(Auth::user()->profile_photo_path)
                    <img src="{{ asset('storage/app/public/photos/' . Auth::user()->profile_photo_path) }}" 
                         alt="{{ Auth::user()->name }}" 
                         class="w-14 h-14 rounded-full object-cover border border-[#E0E5E5] flex-shrink-0">
                @else
                    <div class="w-14 h-14 rounded-full bg-primary-500 flex items-center justify-center font-semibold text-xl text-white flex-shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-base mb-1 truncate text-[#151515]">
                        {{ Auth::user()->name }} {{ Auth::user()->middlename }} {{ Auth::user()->lastname }}
                    </h3>
                    <p class="text-sm text-[#616161] mb-2">{{ Auth::user()->email }}</p>
                    <div class="inline-flex items-center px-2.5 py-1 rounded bg-[#DFE5E5] border border-[#C8D1D1]">
                        <i data-lucide="hash" class="w-3 h-3 mr-1 text-[#7A7A7A]"></i>
                        <span class="text-xs font-medium text-[#616161]">{{ Auth::user()->usernumber }}</span>
                    </div>
                    <p class="text-xs text-[#7A7A7A] mt-2">{{ Auth::user()->accounttype }}</p>
                </div>
            </div>
        </div>

        <!-- Balance Display -->
        <div class="p-6 border-b border-[#E0E5E5]">
            <p class="text-xs uppercase tracking-wider text-[#7A7A7A] mb-2 font-medium">Available Balance</p>
            <h2 class="font-bold text-2xl mb-4 text-[#151515]">
                {{ $settings->currency }}{{ number_format(Auth::user()->account_bal, 2, '.', ',') }}
            </h2>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('deposits') }}" class="flex items-center justify-center space-x-2 px-4 py-2.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 text-white"></i>
                    <span class="text-sm font-medium text-white">Deposit</span>
                </a>
                <a href="{{ route('loan') }}" class="flex items-center justify-center space-x-2 px-4 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] transition-colors">
                    <i data-lucide="piggy-bank" class="w-4 h-4 text-[#616161]"></i>
                    <span class="text-sm font-medium text-[#616161]">Loan</span>
                </a>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4">
            <p class="text-xs uppercase tracking-wider text-[#7A7A7A] px-3 mb-3 font-semibold">Menu</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="layout-dashboard" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('deposits') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="arrow-down-to-line" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Deposits</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('localtransfer') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="building-2" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Local Transfer</span>
                    </a>
                </li>


                <li>
                    <a href="{{ route('internationaltransfer') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="globe" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">International Transfer</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('internaltransfer') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="repeat" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Internal Transfer</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('accounthistory') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="receipt" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Transactions</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('statement') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="file-text" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Account Statement</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cards.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="credit-card" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Virtual Cards</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('investments.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="trending-up" class="w-5 h-5 text-[#616161]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Investments</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('grant.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="hand-coins" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Grant Applications</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('assetbalance') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="arrow-right-left" class="w-5 h-5 text-champagne-500"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Crypto Swap</span>
                    </a>
                </li>
            </ul>

            <p class="text-xs uppercase tracking-wider text-[#7A7A7A] px-3 mb-3 mt-6 font-semibold">Settings</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('support') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="headphones" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Customer Support</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-[#F9F9F9] transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-[#DFE5E5] flex items-center justify-center group-hover:bg-[#C8D1D1] transition-colors">
                            <i data-lucide="user-cog" class="w-5 h-5 text-[#151515]"></i>
                        </div>
                        <span class="font-medium text-[#616161] group-hover:text-[#151515] transition-colors">Profile Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="flex items-center space-x-3 px-4 py-3 rounded-xl hover:bg-red-50 transition-colors group">
                        <div class="w-10 h-10 rounded-lg bg-red-50 flex items-center justify-center group-hover:bg-red-100 transition-colors">
                            <i data-lucide="log-out" class="w-5 h-5 text-[#D55672]"></i>
                        </div>
                        <span class="font-medium text-[#D55672] group-hover:text-[#D55672] transition-colors">Log Out</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Success Modal -->
    @if (Session::has('success'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="scale-95 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 class="bg-white rounded-xl shadow-strong max-w-md w-full border border-[#E0E5E5] overflow-hidden">
                <div class="p-8">
                    <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="check-circle" class="w-7 h-7 text-[#28A745]"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-center mb-2 text-[#151515]">Success</h3>
                    <p class="text-[#616161] text-center text-sm mb-6">{{ Session::get('success') }}</p>
                    <button @click="show = false" class="w-full px-6 py-2.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-medium text-white">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Error Modal -->
    @if (Session::has('message'))
        <div x-data="{ show: true }" 
             x-show="show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60">
            <div x-show="show"
                 x-transition:enter="transition ease-out duration-200 transform"
                 x-transition:enter-start="scale-95 opacity-0"
                 x-transition:enter-end="scale-100 opacity-100"
                 class="bg-white rounded-xl shadow-strong max-w-md w-full border border-[#E0E5E5] overflow-hidden">
                <div class="p-8">
                    <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="alert-circle" class="w-7 h-7 text-[#D55672]"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-center mb-2 text-[#151515]">Error</h3>
                    <p class="text-[#616161] text-center text-sm mb-6">{{ Session::get('message') }}</p>
                    <button @click="show = false" class="w-full px-6 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] transition-colors font-medium text-[#151515]">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
        
        // Reinitialize icons after Alpine updates
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });
    </script>

    <!-- Auto-Logout Security Feature -->
    <script>
        (function() {
            // Configuration
            const INACTIVITY_TIMEOUT = 10 * 60 * 1000; // 10 minutes in milliseconds
            const WARNING_TIME = 1 * 60 * 1000; // Show warning 1 minute before logout
            const PIN_ROUTE = "{{ route('pin') }}";
            
            let inactivityTimer;
            let warningTimer;
            let warningModal = null;
            let countdownInterval = null;
            
            // Events that indicate user activity
            const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
            
            // Reset the inactivity timer
            function resetTimer() {
                // Clear existing timers
                clearTimeout(inactivityTimer);
                clearTimeout(warningTimer);
                
                // Hide warning modal if it's showing
                if (warningModal) {
                    warningModal.remove();
                    warningModal = null;
                }
                
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                    countdownInterval = null;
                }
                
                // Set warning timer (1 minute before logout)
                warningTimer = setTimeout(showWarningModal, INACTIVITY_TIMEOUT - WARNING_TIME);
                
                // Set logout timer
                inactivityTimer = setTimeout(logout, INACTIVITY_TIMEOUT);
            }
            
            // Show warning modal
            function showWarningModal() {
                let remainingSeconds = Math.floor(WARNING_TIME / 1000);
                
                warningModal = document.createElement('div');
                warningModal.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60';
                warningModal.innerHTML = `
                    <div class="bg-white rounded-xl shadow-strong max-w-md w-full border border-[#E0E5E5] overflow-hidden">
                        <div class="p-8">
                            <div class="w-14 h-14 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-4">
                                <i data-lucide="clock" class="w-7 h-7 text-[#3e4e83]"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-center mb-2 text-[#151515]">Session Timeout Warning</h3>
                            <p class="text-[#616161] text-center text-sm mb-4">
                                For your security, you will be logged out due to inactivity.
                            </p>
                            <div class="bg-[#F9F9F9] rounded-lg p-4 mb-6 text-center border border-[#E0E5E5]">
                                <p class="text-sm text-[#7A7A7A] mb-2">Time remaining:</p>
                                <p id="countdown-timer" class="text-2xl font-bold text-[#3e4e83]">${remainingSeconds}s</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <button id="logout-now-btn" class="px-6 py-2.5 rounded-lg bg-[#DFE5E5] hover:bg-[#C8D1D1] border border-[#C8D1D1] transition-colors font-medium text-[#151515]">
                                    Logout Now
                                </button>
                                <button id="stay-logged-in-btn" class="px-6 py-2.5 rounded-lg bg-primary-500 hover:bg-primary-600 transition-colors font-medium text-white">
                                    Stay Logged In
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(warningModal);
                lucide.createIcons();
                
                // Update countdown
                const countdownElement = document.getElementById('countdown-timer');
                countdownInterval = setInterval(() => {
                    remainingSeconds--;
                    if (countdownElement) {
                        countdownElement.textContent = `${remainingSeconds}s`;
                    }
                    if (remainingSeconds <= 0) {
                        clearInterval(countdownInterval);
                    }
                }, 1000);
                
                // Button handlers
                document.getElementById('stay-logged-in-btn').addEventListener('click', function() {
                    resetTimer();
                });
                
                document.getElementById('logout-now-btn').addEventListener('click', function() {
                    logout();
                });
            }
            
            // Logout function - redirect to PIN page
            function logout() {
                // Store current URL for return after PIN verification
                sessionStorage.setItem('return_url', window.location.href);
                
                // Clear timers
                clearTimeout(inactivityTimer);
                clearTimeout(warningTimer);
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }
                
                // Redirect to PIN page
                window.location.href = PIN_ROUTE;
            }
            
            // Attach event listeners for user activity
            activityEvents.forEach(function(event) {
                document.addEventListener(event, resetTimer, true);
            });
            
            // Initialize timer on page load
            resetTimer();
            
            // Prevent timer on PIN page itself
            if (window.location.pathname.includes('/pin')) {
                activityEvents.forEach(function(event) {
                    document.removeEventListener(event, resetTimer, true);
                });
                clearTimeout(inactivityTimer);
                clearTimeout(warningTimer);
            }
        })();
    </script>

    @if($settings->pwa_enabled ?? true)
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('{{ asset("sw.js") }}')
                    .then(registration => {
                        console.log('[PWA] Service Worker registered:', registration.scope);
                    })
                    .catch(error => {
                        console.error('[PWA] Service Worker registration failed:', error);
                    });
            });
        }
    </script>
    @endif
<div>
    @include('layouts.lang')
    @include('layouts.session-expired-handler')
</div>
</body>

@include('layouts.livechat')
</html>


