<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

        <title>@yield('title')</title>

        <!-- Tailwind CSS -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#f0f7f4',
                            100: '#d9ede3',
                            200: '#b3dbc7',
                            300: '#7fc29f',
                            400: '#4da576',
                            500: '#2d8659',
                            600: '#1f6b45',
                            700: '#195539',
                            800: '#15442e',
                            900: '#0d2a1c',
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
                }
            }
        }
        </script>

        <style>
        :root {
            --color-bg-base: #FFFFFF;
            --color-bg-surface: #F9F9F9;
            --color-primary: #2d8659;
            --color-primary-dark: #1f6b45;
            --color-text-primary: #0D1B23;
            --color-text-secondary: #4A4A4A;
            --color-text-muted: #7A7A7A;
            --color-border: #E0E5E5;
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

        .error-card {
            background: #FFFFFF;
            border: 1px solid var(--color-border);
            box-shadow: 0 8px 32px rgba(13, 27, 35, 0.06), 0 2px 8px rgba(13, 27, 35, 0.04);
        }

        .error-button {
            background: var(--color-primary);
            transition: background 0.2s ease;
        }

        .error-button:hover {
            background: var(--color-primary-dark);
        }
        </style>
    </head>
    <body class="antialiased overflow-x-hidden">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-8 items-center">

                    <!-- Left Side - Error Info -->
                    <div class="w-full">
                        <div class="error-card rounded-xl p-8 md:p-10 space-y-6">

                            <!-- Error Code -->
                            <div>
                                <span class="text-7xl md:text-8xl font-bold text-primary-500 tracking-tight">
                                    @yield('code', __('Oh no'))
                                </span>
                            </div>

                            <!-- Divider -->
                            <div class="w-16 h-1 rounded-full bg-primary-500"></div>

                            <!-- Message -->
                            <p class="text-lg text-[#4A4A4A] leading-relaxed">
                                @yield('message')
                            </p>

                            <!-- Action Button -->
                            <a href="{{ app('router')->has('home') ? route('home') : url('/') }}"
                               class="error-button inline-flex items-center justify-center space-x-2 px-6 py-3 rounded-xl font-semibold text-white text-sm group">
                                <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                                <span>{{ __('Go Home') }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Right Side - Illustration -->
                    <div class="hidden lg:flex items-center justify-center">
                        <div class="w-full max-w-md">
                            @yield('image')
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Initialize Lucide Icons -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        </script>
    </body>
</html>
