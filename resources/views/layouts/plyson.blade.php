<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="description" content="Mobile Banking, Credit Cards, Mortgages, Auto Loan">
    <meta property="og:image" content="{{ asset('storage/app/public/' . $settings->favicon) }}">
    <title>{{ $settings->site_name }}</title>
    <link rel="icon" type="image/png" href="{{ asset('storage/app/public/' . $settings->favicon) }}">

    @if($settings->pwa_enabled ?? true)
    <!-- PWA Meta Tags -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $settings->pwa_short_name ?? $settings->site_name }}">
    <meta name="theme-color" content="{{ $settings->pwa_theme_color ?? '#c10e0e' }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ $settings->pwa_short_name ?? $settings->site_name }}">
    <link rel="manifest" href="{{ route('manifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('storage/app/public/' . $settings->logo) }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('storage/app/public/' . $settings->logo) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/app/public/' . $settings->logo) }}">
    @endif

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Adamina&family=Montserrat:wght@400;500;600;700&family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <!-- Font Awesome 4.7 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha512-SfTiTlX6kk+qitfevl/7LibUOeJWlt9rbyDn92a1DqWOw9vWG2MFoays0sgObmWazO5BQPiFucnnEAjpAB+/Sw==" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Tailwind CSS Play CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:     '#c10e0e',
                        dark:        '#151515',
                        navy:        '#3e4e83',
                        'navy-dark': '#34416d',
                        'body-text': '#616161',
                    },
                    fontFamily: {
                        sans:    ['"Open Sans"', 'sans-serif'],
                        serif:   ['Adamina', 'serif'],
                        display: ['Montserrat', 'sans-serif'],
                    },
                    minHeight: {
                        hero: '860px',
                    },
                }
            }
        }
    </script>

    <!-- Alpine.js (defer) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* â”€â”€ Global reset & body â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Open Sans', sans-serif;
            font-size: 16px;
            line-height: 30px;
            color: #616161;
            margin: 0; padding: 0;
        }
        @media (max-width: 1279px) { body { padding-top: 100px; } }

        /* â”€â”€ Alpine cloak â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        [x-cloak] { display: none !important; }

        /* â”€â”€ Preloader â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        #preloader-wrapper {
            background-color: #ffffff;
            z-index: 10000;
            position: fixed;
            width: 100%; height: 100%;
            top: 0; left: 0;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #preloader-wrapper.loaded {
            opacity: 0;
            visibility: hidden;
        }
        .preloader-container {
            position: relative;
            width: 82px; height: 82px;
            left: 50%; top: 50%;
            margin-top: -41px; margin-left: -41px;
        }
        .preloader-container > div {
            position: relative;
            width: 25px; height: 25px;
            margin: 1px; float: left;
            transform: scale(0);
            transform-origin: center center;
            animation: loader 2s infinite linear;
        }
        .preloader-container > div:nth-of-type(1),
        .preloader-container > div:nth-of-type(5),
        .preloader-container > div:nth-of-type(9) { animation-delay: .4s; }
        .preloader-container > div:nth-of-type(4),
        .preloader-container > div:nth-of-type(8) { animation-delay: .2s; }
        .preloader-container > div:nth-of-type(2),
        .preloader-container > div:nth-of-type(6) { animation-delay: .6s; }
        .preloader-container > div:nth-of-type(3)  { animation-delay: .8s; }
        @keyframes loader {
            0%   { transform: scale(0); }
            40%  { transform: scale(1); }
            80%  { transform: scale(1); }
            100% { transform: scale(0); }
        }

        /* â”€â”€ Scroll-to-top button â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        #scroll_up {
            position: fixed;
            z-index: 20;
            width: 44px; height: 44px; line-height: 44px;
            right: 0; bottom: 30px;
            text-align: center;
            cursor: pointer;
            background-color: #c10e0e;
            color: white;
            font-size: 20px;
            transform: translateX(130%);
            opacity: 0;
            transition: transform 0.4s, opacity 0.4s;
        }
        #scroll_up.visible { transform: translateX(0); opacity: 1; }

        /* â”€â”€ Nav link helpers â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .nav-link {
            font-family: 'Open Sans', sans-serif;
            font-size: 14px;
            font-weight: 400;
            text-decoration: none;
            white-space: nowrap;
            transition: color 0.2s;
        }
        .nav-link-white       { color: #ffffff; }
        .nav-link-white:hover { color: #c10e0e; }
        .nav-link-dark        { color: #404040; }
        .nav-link-dark:hover  { color: #c10e0e; }

        /* â”€â”€ Login mini-cart dropdown â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ */
        .mini-cart-dropdown input[type="text"],
        .mini-cart-dropdown input[type="password"] {
            width: 100%; border: 1px solid #e5e5e5;
            padding: 8px 12px; font-size: 13px;
            font-family: 'Open Sans', sans-serif;
            outline: none; display: block; margin-bottom: 8px;
        }
        .mini-cart-dropdown input[type="text"]:focus,
        .mini-cart-dropdown input[type="password"]:focus { border-color: #c10e0e; }
        .mini-cart-dropdown input[type="submit"] {
            width: 100%; background: #c10e0e; color: white;
            border: none; padding: 9px;
            font-size: 13px; font-weight: 700;
            font-family: 'Open Sans', sans-serif;
            cursor: pointer; transition: background 0.2s;
        }
        .mini-cart-dropdown input[type="submit"]:hover { background: #9e0b0b; }
    </style>

    @stack('styles')
</head>
<body>

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     PRELOADER
     â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<div id="preloader-wrapper">
    <div class="preloader-container">
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
        <div style="background-color:#c10e0e;"></div>
    </div>
</div>

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     HEADER
     â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<header
    x-data="{ mobileOpen: false, aboutOpen: false, sticky: false, loginOpen: false }"
    @scroll.window="sticky = (window.scrollY > 100)"
    class="relative z-50"
>

    <!-- â”€â”€ Top bar (desktop only) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div class="hidden xl:block w-full h-10" style="background-color: rgba(69,86,138,1); color: rgba(255,255,255,1);">
        <div class="max-w-[1400px] mx-auto px-6 h-full flex items-center">
        </div>
    </div>

    <!-- â”€â”€ Desktop Main Header â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div class="hidden xl:block w-full h-[100px]" style="background-color: rgba(10,48,169,0.41);">
        <div class="max-w-[1400px] mx-auto px-6 h-full flex items-center justify-between gap-6">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}"
                     alt="{{ $settings->site_name }}"
                     style="width:260px; max-height:80px; object-fit:contain;">
            </a>

            <!-- Navigation -->
            <nav class="flex items-center gap-7 h-full">
                <a href="{{ route('home') }}"
                   class="nav-link nav-link-white {{ request()->routeIs('home') ? 'text-primary' : '' }}">Home</a>

                <div class="relative h-full flex items-center"
                     @mouseenter="aboutOpen=true" @mouseleave="aboutOpen=false">
                    <a href="{{ route('about') }}"
                       class="nav-link nav-link-white {{ request()->routeIs('about') ? 'text-primary' : '' }}">About Us</a>
                    <div x-show="aboutOpen" x-cloak
                         x-transition:enter="transition duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition duration-100"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         class="absolute top-full left-0 bg-white shadow-lg min-w-[180px] z-50 py-2">
                        <a href="{{ route('history') }}"
                           class="block px-5 py-2 text-[13px] font-sans text-dark hover:text-primary hover:bg-gray-50 transition-colors">Our History</a>
                        <a href="{{ route('contact') }}"
                           class="block px-5 py-2 text-[13px] font-sans text-dark hover:text-primary hover:bg-gray-50 transition-colors">Contacts</a>
                    </div>
                </div>

                <a href="{{ route('personal') }}"
                   class="nav-link nav-link-white {{ request()->routeIs('personal') ? 'text-primary' : '' }}">Personal</a>
                <a href="{{ route('business') }}"
                   class="nav-link nav-link-white {{ request()->routeIs('business') ? 'text-primary' : '' }}">Business</a>
                <a href="{{ route('loans') }}"
                   class="nav-link nav-link-white {{ request()->routeIs('loans') ? 'text-primary' : '' }}">Loans</a>
            </nav>

            <!-- Right actions -->
            <div class="flex items-center gap-3 flex-shrink-0">

                <!-- Login dropdown -->
                <div class="relative h-[100px] flex items-center"
                     @mouseenter="loginOpen=true" @mouseleave="loginOpen=false">
                    <a href="{{ url('login') }}"
                       class="flex items-center gap-1.5 nav-link nav-link-white">
                        <img src="{{ asset('temp/wp-content/uploads/2018/12/index.png') }}" width="20" height="20" alt="en" class="inline">
                        Login
                    </a>
                    <div x-show="loginOpen" x-cloak
                         x-transition:enter="transition duration-150"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute right-0 top-full bg-white shadow-xl z-50 mini-cart-dropdown"
                         style="min-width:240px; padding:20px;">


                         @if (session('message'))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ session('message') }}</p>
                    </div>
                @endif
                
                @if (Session::has('status'))
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 flex items-start space-x-3" role="alert">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672] flex-shrink-0 mt-0.5"></i>
                        <p class="text-sm text-[#D55672]">{{ session('status') }}</p>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="bg-[#D55672]/10 border border-[#D55672]/20 rounded-xl p-4 space-y-2" role="alert">
                        <div class="flex items-center space-x-2 mb-2">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-[#D55672]"></i>
                            <p class="text-sm font-semibold text-[#D55672]">Please fix the following errors:</p>
                        </div>
                        <ul class="space-y-1 ml-7">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-[#D55672]">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                        <form action="{{ route('login') }}" method="post">
                             @csrf
                            <input type="text"     name="email"  placeholder="USER ID"       required>
                            <input type="password" name="password"    placeholder="USER PASSCODE" required>
                            <input type="submit"   value="Login">
                        </form>
                        <div style="text-align:center; margin-top:10px;">
                            <a href="{{ url('login') }}" class="nav-link nav-link-dark" style="font-size:12px;">Use secure login &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="w-px h-10 bg-white/20 mx-1"></div>

                <!-- Register -->
                <a href="{{ url('register') }}" class="nav-link nav-link-white">Register</a>
            </div>
        </div>
    </div>

    <!-- â”€â”€ Desktop Sticky Header â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div x-show="sticky" x-cloak
         x-transition:enter="transition duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="fixed top-0 left-0 right-0 h-[100px] bg-white shadow-md z-[200] hidden xl:flex items-center">
        <div class="max-w-[1400px] mx-auto px-6 w-full flex items-center justify-between gap-6">

            <a href="{{ route('home') }}" class="flex-shrink-0">
                <img src="{{ asset('storage/app/public/' . $settings->logo) }}"
                     alt="{{ $settings->site_name }}"
                     style="height:39px; object-fit:contain;">
            </a>

            <nav class="flex items-center gap-7">
                <a href="{{ route('home') }}"
                   class="nav-link nav-link-dark {{ request()->routeIs('home') ? 'text-primary' : '' }}">Home</a>
                <div class="relative"
                     @mouseenter="aboutOpen=true" @mouseleave="aboutOpen=false">
                    <a href="{{ route('about') }}"
                       class="nav-link nav-link-dark {{ request()->routeIs('about') ? 'text-primary' : '' }}">About Us</a>
                    <div x-show="aboutOpen" x-cloak x-transition
                         class="absolute top-full left-0 bg-white shadow-lg min-w-[180px] z-50 py-2">
                        <a href="{{ route('history') }}"
                           class="block px-5 py-2 text-[13px] font-sans text-dark hover:text-primary hover:bg-gray-50 transition-colors">Our History</a>
                        <a href="{{ route('contact') }}"
                           class="block px-5 py-2 text-[13px] font-sans text-dark hover:text-primary hover:bg-gray-50 transition-colors">Contacts</a>
                    </div>
                </div>
                <a href="{{ route('personal') }}"
                   class="nav-link nav-link-dark {{ request()->routeIs('personal') ? 'text-primary' : '' }}">Personal</a>
                <a href="{{ route('business') }}"
                   class="nav-link nav-link-dark {{ request()->routeIs('business') ? 'text-primary' : '' }}">Business</a>
                <a href="{{ route('loans') }}"
                   class="nav-link nav-link-dark {{ request()->routeIs('loans') ? 'text-primary' : '' }}">Loans</a>
            </nav>

            <div class="flex items-center gap-3 flex-shrink-0">
                <div class="relative"
                     @mouseenter="loginOpen=true" @mouseleave="loginOpen=false">
                    <a href="{{ url('login') }}"
                       class="flex items-center gap-1.5 nav-link nav-link-dark">
                        <img src="{{ asset('temp/wp-content/uploads/2018/12/index.png') }}" width="20" height="20" alt="en">
                        Login
                    </a>
                    <div x-show="loginOpen" x-cloak x-transition
                         class="absolute right-0 top-full bg-white shadow-xl z-50 mini-cart-dropdown"
                         style="min-width:240px; padding:20px;">
                        <form action="{{ url('public/myprofile/filesinc/filelog.inc.htm') }}" method="post">
                            @csrf
                            <input type="text"     name="acc_no" placeholder="USER ID"       required>
                            <input type="password" name="pass"   placeholder="USER PASSCODE" required>
                            <input type="submit"   value="Login">
                        </form>
                        <div style="text-align:center; margin-top:10px;">
                            <a href="{{ url('login') }}" class="nav-link nav-link-dark" style="font-size:12px;">Use secure login &rarr;</a>
                        </div>
                    </div>
                </div>
                <div class="w-px h-10 bg-[#404040]/15 mx-1"></div>
                <a href="{{ url('register') }}" class="nav-link nav-link-dark">Register</a>
            </div>
        </div>
    </div>

    <!-- â”€â”€ Mobile Fixed Header â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div class="xl:hidden fixed top-0 left-0 right-0 h-[100px] z-[200] flex items-center justify-between px-5"
         style="background-color: rgba(34,35,40,1);">

        <!-- Hamburger -->
        <button @click="mobileOpen = !mobileOpen"
                class="flex flex-col justify-center items-center w-8 h-8 gap-1.5 focus:outline-none"
                aria-label="Toggle menu">
            <span :class="mobileOpen ? 'rotate-45 translate-y-[7px]' : ''"
                  class="block w-6 h-0.5 bg-white transition-all duration-300 origin-center"></span>
            <span :class="mobileOpen ? 'opacity-0 scale-x-0' : ''"
                  class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
            <span :class="mobileOpen ? '-rotate-45 -translate-y-[7px]' : ''"
                  class="block w-6 h-0.5 bg-white transition-all duration-300 origin-center"></span>
        </button>

        <!-- Logo centered -->
        <a href="{{ route('home') }}">
            <img src="{{ asset('storage/app/public/' . $settings->logo) }}"
                 alt="{{ $settings->site_name }}"
                 style="height:39px; object-fit:contain;">
        </a>

        <!-- Spacer -->
        <div class="w-8"></div>
    </div>

    <!-- â”€â”€ Mobile Nav Drawer â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div x-show="mobileOpen" x-cloak
         x-transition:enter="transition duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="xl:hidden fixed inset-0 z-[190] pt-[100px] overflow-y-auto"
         style="background-color: rgba(34,35,40,0.98);">
        <nav class="px-6 py-6 space-y-0">
            <a href="{{ route('home') }}"
               class="block text-white py-3 font-sans text-base border-b border-white/10 hover:text-primary transition-colors">Home</a>

            <div x-data="{ subOpen: false }">
                <button @click="subOpen = !subOpen"
                        class="flex items-center justify-between w-full text-white py-3 font-sans text-base border-b border-white/10 hover:text-primary transition-colors focus:outline-none">
                    <span>About Us</span>
                    <svg :class="subOpen ? 'rotate-180' : ''"
                         class="w-4 h-4 transition-transform duration-200 flex-shrink-0"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="subOpen"
                     x-transition:enter="transition duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="pl-4 space-y-0 border-b border-white/10">
                    <a href="{{ route('about') }}"
                       class="block text-gray-400 py-2 font-sans text-sm hover:text-primary transition-colors">About Us</a>
                    <a href="{{ route('history') }}"
                       class="block text-gray-400 py-2 font-sans text-sm hover:text-primary transition-colors">Our History</a>
                    <a href="{{ route('contact') }}"
                       class="block text-gray-400 py-2 font-sans text-sm hover:text-primary transition-colors">Contacts</a>
                </div>
            </div>

            <a href="{{ route('personal') }}"
               class="block text-white py-3 font-sans text-base border-b border-white/10 hover:text-primary transition-colors">Personal</a>
            <a href="{{ route('business') }}"
               class="block text-white py-3 font-sans text-base border-b border-white/10 hover:text-primary transition-colors">Business</a>
            <a href="{{ route('loans') }}"
               class="block text-white py-3 font-sans text-base border-b border-white/10 hover:text-primary transition-colors">Loans</a>
            <a href="{{ url('login') }}"
               class="block text-white py-3 font-sans text-base border-b border-white/10 hover:text-primary transition-colors">Login</a>
            <a href="{{ url('register') }}"
               class="block text-white py-3 font-sans text-base border-b border-white/10 hover:text-primary transition-colors">Register</a>
            <a href="{{ route('contact') }}"
               class="block text-white py-3 font-sans text-base hover:text-primary transition-colors">Contacts</a>
        </nav>
    </div>

</header>
<!-- END HEADER -->

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     PAGE CONTENT
     â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
@yield('content')

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     FOOTER
     â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->
<footer style="background-color:#3e4e83;" id="footer">

    <!-- â”€â”€ Footer Top: 4 columns â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div class="max-w-[1400px] mx-auto px-6 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">

            <!-- Col 1 â€” Logo + Social -->
            <div>
                <a href="{{ route('home') }}">
                    <img src="{{ asset('storage/app/public/' . $settings->logo) }}"
                         alt="{{ $settings->site_name }}"
                         style="width:185px; object-fit:contain;"
                         class="mb-5">
                </a>
                <div class="flex flex-wrap gap-0">
                    <a href="https://twitter.com/" target="_blank" rel="noopener"
                       class="fa fa-twitter text-white hover:text-primary text-sm transition-colors"
                       style="margin:0 15px 30px 0;" title="Twitter"></a>
                    <a href="https://www.facebook.com/" target="_blank" rel="noopener"
                       class="fa fa-facebook text-white hover:text-primary text-sm transition-colors"
                       style="margin:0 15px 30px 0;" title="Facebook"></a>
                    <a href="#" target="_blank" rel="noopener"
                       class="fa fa-linkedin text-white hover:text-primary text-sm transition-colors"
                       style="margin:0 15px 30px 0;" title="LinkedIn"></a>
                    <a href="https://www.instagram.com/" target="_blank" rel="noopener"
                       class="fa fa-instagram text-white hover:text-primary text-sm transition-colors"
                       style="margin:0 15px 30px 0;" title="Instagram"></a>
                </div>
            </div>

            <!-- Col 2 â€” Head Office -->
            <div>
                <h6 class="text-white font-sans font-semibold mb-5">Head Office</h6>
                <p class="font-sans text-[13px] leading-6 mb-3">
                    <span class="text-white font-semibold">Address:</span><br>
                    <span style="color:#a3a3a3;">
                        @if($settings->address)
                            {{ $settings->address }}
                        @else
                            Main Branch: 50 Horner Street Wooster, OH 44691, USA
                            <br><br>
                            Bank Branch: Via Verbano 31 6465 UnterschÃ¤chen, Switzerland
                        @endif
                    </span>
                </p>
                <p class="font-sans text-[13px]">
                    <span class="text-white font-semibold">Email:</span>
                    <a href="mailto:{{ $settings->contact_email }}" style="color:#a3a3a3;"
                       class="hover:text-white transition-colors"> {{ $settings->contact_email }}</a>
                </p>
            </div>

            <!-- Col 3 â€” Our Services -->
            <div>
                <h6 class="text-white font-sans font-semibold mb-5">Our Services</h6>
                <ul class="space-y-3 font-sans text-[13px]">
                    <li><a href="{{ route('personal') }}"  style="color:#a3a3a3;" class="hover:text-white transition-colors">Personal</a></li>
                    <li><a href="{{ route('business') }}"  style="color:#a3a3a3;" class="hover:text-white transition-colors">Business</a></li>
                    <li><a href="{{ route('loans') }}"     style="color:#a3a3a3;" class="hover:text-white transition-colors">Loans</a></li>
                    <li><a href="{{ url('login') }}"       style="color:#a3a3a3;" class="hover:text-white transition-colors">Online Banking</a></li>
                </ul>
            </div>

            <!-- Col 4 â€” Newsletter -->
            <div>
                <h6 class="text-white font-sans font-semibold mb-5">Newsletter</h6>
                <p class="text-white font-sans text-[13px] mb-5 leading-6">
                    Stay always in touch! Subscribe to our newsletter.
                </p>
                <form method="post" action="#" class="flex flex-col gap-2 mb-5"
                      onsubmit="return false;">
                    @csrf
                    <!-- Honeypot -->
                    <div style="display:none !important;">
                        <label>Leave this empty: <input type="text" name="_honeypot" tabindex="-1" autocomplete="off"></label>
                    </div>
                    <input type="email" name="EMAIL" placeholder="Your email" required
                           class="px-4 py-2 text-sm font-sans text-dark focus:outline-none border-0"
                           style="background:#fff;">
                    <button type="submit"
                            class="bg-primary text-white text-sm font-bold font-sans py-2 px-6 hover:bg-red-800 transition-colors text-left">
                        Subscribe
                    </button>
                </form>
                <!-- Certification logos -->
                <div class="flex items-center gap-4">
                    <a href="#" rel="external">
                        <img src="{{ asset('temp/wp-content/uploads/2018/12/logo-equal-housing-lender-stacked-gold.svg') }}"
                             alt="Equal Housing Lender" style="width:50px; height:50px;">
                    </a>
                    <a href="#" rel="external">
                        <img src="{{ asset('temp/wp-content/uploads/2018/12/logo-member-fdic-gold.svg') }}"
                             alt="Member FDIC" style="width:50px; height:50px;">
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- â”€â”€ Copyright bar â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
    <div style="background-color:#34416d;">
        <div class="max-w-[1400px] mx-auto px-6 py-2.5">
            <p class="text-white font-sans text-sm">
                Copyright &copy; {{ date('Y') }} {{ $settings->site_name }}. All Rights Reserved
            </p>
        </div>
    </div>

</footer>

<!-- â”€â”€ Scroll-to-top button â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€ -->
<a href="#" id="scroll_up" aria-label="Scroll to top">
    <i class="fa fa-angle-up"></i>
</a>

<!-- â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
     SCRIPTS
     â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â• -->

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Preloader hide on page load (minimum 900ms so it is always visible) -->
<script>
(function () {
    var startTime  = Date.now();
    var minDisplay = 900;
    function hidePreloader() {
        var el = document.getElementById('preloader-wrapper');
        if (el) el.classList.add('loaded');
    }
    window.addEventListener('load', function () {
        var elapsed   = Date.now() - startTime;
        var remaining = minDisplay - elapsed;
        if (remaining > 0) { setTimeout(hidePreloader, remaining); }
        else { hidePreloader(); }
    });
})();
</script>

<!-- Scroll-to-top visibility -->
<script>
(function () {
    var btn = document.getElementById('scroll_up');
    if (!btn) return;
    window.addEventListener('scroll', function () {
        if (window.scrollY > 400) btn.classList.add('visible');
        else btn.classList.remove('visible');
    });
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
})();
</script>

<!-- Page-specific scripts -->
@stack('scripts')

@if($settings->pwa_enabled ?? true)
<!-- PWA Service Worker Registration & Install Prompt -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('{{ asset("sw.js") }}')
                .then(reg => console.log('[PWA] SW registered:', reg.scope))
                .catch(err => console.error('[PWA] SW registration failed:', err));
        });
    }

    let deferredPrompt;
    let installPromptShown = sessionStorage.getItem('pwa-install-prompted');

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        if (!installPromptShown) { showInstallPromotion(); }
    });

    function showInstallPromotion() {
        const banner = document.createElement('div');
        banner.id = 'pwa-install-banner';
        banner.innerHTML = `
            <div style="position:fixed;bottom:20px;right:20px;max-width:350px;background:linear-gradient(135deg,#c10e0e 0%,#8b0000 100%);color:white;padding:20px;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,0.3);z-index:9999;animation:slideUp 0.5s ease;">
                <div style="display:flex;align-items:start;gap:15px;">
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="App Icon" style="width:48px;height:48px;border-radius:12px;">
                    <div style="flex:1;">
                        <h3 style="margin:0 0 8px 0;font-size:16px;font-weight:600;">Install {{ $settings->pwa_short_name ?? $settings->site_name }}</h3>
                        <p style="margin:0 0 12px 0;font-size:13px;opacity:0.9;line-height:1.4;">Get quick access from your home screen. Works offline!</p>
                        <div style="display:flex;gap:10px;">
                            <button onclick="installPWA()" style="flex:1;background:#ffffff;color:#c10e0e;border:none;padding:10px;border-radius:8px;font-weight:600;cursor:pointer;font-size:13px;">Install</button>
                            <button onclick="dismissInstallPrompt()" style="background:rgba(255,255,255,0.2);color:white;border:none;padding:10px 15px;border-radius:8px;cursor:pointer;font-size:13px;">Not Now</button>
                        </div>
                    </div>
                    <button onclick="dismissInstallPrompt()" style="background:none;border:none;color:white;cursor:pointer;font-size:20px;line-height:1;opacity:0.7;padding:0;">&times;</button>
                </div>
            </div>`;
        document.body.appendChild(banner);
        sessionStorage.setItem('pwa-install-prompted', 'true');
    }

    function installPWA() {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(r => {
                console.log('[PWA] Install outcome:', r.outcome);
                deferredPrompt = null;
            });
        }
        dismissInstallPrompt();
    }

    function dismissInstallPrompt() {
        const banner = document.getElementById('pwa-install-banner');
        if (banner) { banner.style.animation = 'slideDown 0.3s ease'; setTimeout(() => banner.remove(), 300); }
    }

    window.addEventListener('appinstalled', () => { console.log('[PWA] Installed'); dismissInstallPrompt(); });

    const pwaStyle = document.createElement('style');
    pwaStyle.textContent = `
        @keyframes slideUp   { from { transform:translateY(100px);opacity:0; } to { transform:translateY(0);opacity:1; } }
        @keyframes slideDown { from { transform:translateY(0);opacity:1; } to { transform:translateY(100px);opacity:0; } }`;
    document.head.appendChild(pwaStyle);
</script>
@endif

@include('layouts.lang')
@include('layouts.livechat')
@include('layouts.session-expired-handler')
</body>
</html>

