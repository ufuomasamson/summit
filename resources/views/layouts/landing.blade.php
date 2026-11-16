<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="description" content="{{ $settings->site_name }} — Instant transfers, digital cards, investments and savings. Banking made easily, fast, and secure.">
    <meta property="og:title" content="{{ $settings->site_name }}">
    <meta property="og:description" content="Banking made easily. Fast. Secure. Borderless.">
    <meta property="og:image" content="{{ branding_url($settings->favicon) }}">
    <title>{{ $settings->site_name }} — Banking Made Easily</title>
    <link rel="icon" type="image/png" href="{{ branding_url($settings->favicon) }}">

    @if($settings->pwa_enabled ?? true)
    <meta name="theme-color" content="{{ $settings->pwa_theme_color ?? '#D6001C' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ $settings->pwa_short_name ?? $settings->site_name }}">
    <link rel="manifest" href="{{ route('manifest') }}">
    <link rel="apple-touch-icon" href="{{ branding_url($settings->logo) }}">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Syne:wght@500;600;700;800&display=swap" rel="stylesheet">
    @php
        // Same-origin relative base (works on HTTPS mobile; avoids mixed-content / localhost URLs)
        $nxAssetBase = rtrim(request()->getBasePath(), '/');
        $nxCssPath = base_path('css/landing.css');
        $nxJsPath = base_path('js/landing.js');
    @endphp
    <link rel="stylesheet" href="{{ $nxAssetBase }}/css/landing.css?v={{ file_exists($nxCssPath) ? filemtime($nxCssPath) : time() }}">
    @stack('styles')
</head>
<body class="nx-body">
    <a class="nx-skip" href="#main">Skip to content</a>

    @include('home.partials.nav')

    <main id="main">
        @yield('content')
    </main>

    @include('home.partials.footer')

    <script>
        window.NX = {
            siteName: @json($settings->site_name),
            registerUrl: @json(route('register')),
            loginUrl: @json(route('login')),
            homeUrl: @json(route('home')),
        };
    </script>
    <script src="{{ $nxAssetBase }}/js/landing.js?v={{ file_exists($nxJsPath) ? filemtime($nxJsPath) : time() }}" defer></script>
    @stack('scripts')

    @if(($settings->pwa_enabled ?? true) && file_exists(public_path('sw.js')))
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function () {
                navigator.serviceWorker.register('{{ asset('sw.js') }}').catch(function () {});
            });
        }
    </script>
    @endif

    @include('layouts.lang')
    @include('layouts.session-expired-handler')
</body>
</html>
