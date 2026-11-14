<section class="nx-cta">
    <div class="nx-cta__lines" aria-hidden="true"></div>
    <div class="nx-container nx-reveal">
        <h2>{{ $ctaTitle ?? 'Ready to Bank Smarter?' }}</h2>
        <p>{{ $ctaText ?? 'Open your account in minutes and start managing your money with confidence.' }}</p>
        <a href="{{ $ctaUrl ?? route('register') }}" class="nx-btn nx-btn--primary nx-btn--pulse nx-btn--magnetic">
            <span>{{ $ctaButton ?? 'Open Account' }}</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
</section>
