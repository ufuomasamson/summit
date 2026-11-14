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
                            50:  '#fdf2f2',
                            100: '#fce4e4',
                            200: '#f9c9c9',
                            300: '#f49c9c',
                            400: '#ec6262',
                            500: '#c10e0e',
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
                }
            }
        }
        </script>

        <style>
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
            border: 1px solid #E0E5E5;
            box-shadow: 0 8px 32px rgba(13, 27, 35, 0.06), 0 2px 8px rgba(13, 27, 35, 0.04);
        }

        .error-card a { color: #c10e0e; }
        .error-card a:hover { color: #a30c0c; }
        </style>
    </head>
    <body class="antialiased overflow-x-hidden">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md mx-auto">
                <div class="error-card rounded-xl p-8 md:p-10 text-center space-y-4">
                    <p class="text-lg text-[#4A4A4A] leading-relaxed">
                        @yield('message')
                    </p>
                    <a href="{{ url('/') }}" class="inline-block text-sm font-medium transition-colors" style="color:#c10e0e;">
                        Return to homepage
                    </a>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            });
        </script>
    </body>
</html>
