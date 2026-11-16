<!doctype html>
<html lang="en">

<head>
    <title>@yield('title') - {{ $settings->site_name }}</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="apple-mobile-web-app-title" content="{{ $settings->site_name }}" />
    <meta name="application-name" content="{{ $settings->site_name }}" />
    <meta name="description" content="Swift and Secure Money Transfer to any bank account will become a breeze with {{ $settings->site_name }}." />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="theme-color" content="#c10e0e">
    <link rel="shortcut icon" href="{{ branding_url($settings->favicon) }}" />
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <!-- Fonts: Inter (Modern Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    
    @yield('head')
    
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50:  '#fdf2f2',
                    100: '#fce4e4',
                    200: '#f9c9c9',
                    300: '#f49c9c',
                    400: '#ec6262',
                    500: '#c10e0e',  // MAIN - Brand red
                    600: '#a30c0c',
                    700: '#870a0a',
                    800: '#6e0808',
                    900: '#580707',
                    },
                    surface: {
                        100: '#FFFFFF',
                        200: '#F9F9F9',
                        300: '#DFE5E5',
                        400: '#BFC9C9',
                    },
                },
                fontFamily: {
                    sans: ['Inter', 'system-ui', 'sans-serif'],
                },
                boxShadow: {
                    'elegant': '0 8px 32px rgba(0, 0, 0, 0.12), 0 2px 8px rgba(0, 0, 0, 0.08)',
                },
                animation: {
                    'fade-in': 'fadeIn .4s ease-out',
                },
                keyframes: {
                    fadeIn: { 
                        '0%': { opacity: '0' }, 
                        '100%': { opacity: '1' } 
                    },
                },
            }
        }
    }
    </script>
    
    <style>
    [x-cloak] { display: none !important; }





    
    
    :root {
        --color-bg-base: #FFFFFF;
        --color-bg-surface: #F9F9F9;
        --color-bg-muted: #DFE5E5;
        --color-primary: #c10e0e;
        --color-primary-dark: #a30c0c;
        --color-primary-light: #ec6262;
        --color-text-primary: #151515;
        --color-text-secondary: #616161;
        --color-text-muted: #7A7A7A;
        --color-text-inverse: #FFFFFF;
        --color-border: #E0E5E5;
        --color-border-dark: #C8D1D1;
        --color-border-muted: #A0ACAC;
        --color-status-success: #28A745;
        --color-status-warning: #EBBC46;
        --color-status-error: #D55672;
        --color-status-info:#c10e0e;
    }
    
    * {
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    body {
        font-family: 'Inter', system-ui, sans-serif;
        background: #F9F9F9;
        min-height: 100vh;
        letter-spacing: -0.011em;
    }
    
    /* Authentication Card */
    .auth-card {
        background: #FFFFFF;
        border: 1px solid var(--color-border);
        box-shadow: 0 8px 32px rgba(13, 27, 35, 0.06), 0 2px 8px rgba(13, 27, 35, 0.04);
    }
    
    /* Input Fields */
    .auth-input {
        background: #FFFFFF;
        border: 1px solid var(--color-border-dark);
        color: var(--color-text-primary);
        transition: border-color 0.2s ease;
    }
    
    .auth-input:focus {
        background: #FFFFFF;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 2px rgba(73, 120, 107, 0.12);
        outline: none;
    }
    
    .auth-input::placeholder {
        color: var(--color-text-muted);
    }
    
    /* Button */
    .auth-button {
        background: var(--color-primary);
        transition: background 0.2s ease;
    }
    
    .auth-button:hover {
        background: var(--color-primary-dark);
    }
    
    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #F9F9F9;
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--color-border-dark);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #A0ACAC;
    }
    
    /* Link Hover */
    .auth-link {
        color: var(--color-primary);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .auth-link:hover {
        color: var(--color-primary-light);
    }
    
    </style>
</head>

<body class="antialiased overflow-x-hidden" x-data="{ showPassword: false, loading: false }">
    
    <!-- Google Translate Widget -->
   
    
    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center p-4 relative z-10">
        @yield('content')
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
   
    
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    
    @if ($settings->tido)
        <script src="//code.tidio.co/{{ $settings->tido }}" async></script>
    @endif
    
    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
        
        // Reinitialize icons after Alpine updates
        document.addEventListener('alpine:initialized', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    <!-- Auto-refresh CSRF token to keep session alive -->
    <script>
        (function() {
            // Refresh CSRF token every 15 minutes to keep session alive
            const REFRESH_INTERVAL = 15 * 60 * 1000;

            function refreshCsrfToken() {
                fetch('{{ route("csrf.refresh") }}', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.token) {
                        // Update meta tag
                        let metaToken = document.querySelector('meta[name="csrf-token"]');
                        if (metaToken) metaToken.setAttribute('content', data.token);

                        // Update all hidden _token inputs in forms
                        document.querySelectorAll('input[name="_token"]').forEach(input => {
                            input.value = data.token;
                        });

                        // Update jQuery AJAX header if jQuery is loaded
                        if (typeof $ !== 'undefined') {
                            $.ajaxSetup({
                                headers: { 'X-CSRF-TOKEN': data.token }
                            });
                        }
                    }
                })
                .catch(() => { /* silently retry on next interval */ });
            }

            setInterval(refreshCsrfToken, REFRESH_INTERVAL);
        })();
    </script>

    @yield('scripts')
    <div>
    @include('layouts.lang')
    </div>
    @include('layouts.session-expired-handler')
</body>

</html>
