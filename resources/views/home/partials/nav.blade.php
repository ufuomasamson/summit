<header class="nx-nav" data-nx-nav>
    <div class="nx-nav__inner">
        <a href="{{ route('home') }}" class="nx-nav__brand" aria-label="{{ $settings->site_name }} home">
            <img src="{{ branding_url($settings->logo) }}"
                 alt="{{ $settings->site_name }}"
                 class="nx-nav__logo"
                 width="160"
                 height="48">
        </a>

        <nav class="nx-nav__links" aria-label="Primary">
            <a href="{{ route('home') }}#why">Why us</a>
            <a href="{{ route('home') }}#experience">Experience</a>
            <a href="{{ route('home') }}#cards">Cards</a>
            <a href="{{ route('home') }}#security">Security</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>

        <div class="nx-nav__actions">
            <div class="nx-nav__auth">
                <a href="{{ route('login') }}" class="nx-btn nx-btn--ghost nx-btn--sm">Login</a>
                <a href="{{ route('register') }}" class="nx-btn nx-btn--primary nx-btn--sm nx-btn--magnetic">
                    <span>Open Account</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
            <button type="button" class="nx-nav__toggle" data-nx-menu-toggle aria-expanded="false" aria-controls="nx-mobile-menu" aria-label="Open menu">
                <span></span><span></span>
            </button>
        </div>
    </div>

    <div id="nx-mobile-menu" class="nx-nav__drawer" data-nx-menu hidden>
        <a href="{{ route('home') }}#why">Why us</a>
        <a href="{{ route('home') }}#experience">Experience</a>
        <a href="{{ route('home') }}#cards">Cards</a>
        <a href="{{ route('home') }}#security">Security</a>
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('contact') }}">Contact</a>
    </div>
</header>
