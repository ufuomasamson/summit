<section class="nx-page-hero">
    <div class="nx-page-hero__bg" aria-hidden="true"></div>
    <div class="nx-container nx-reveal nx-reveal--instant">
        <span class="nx-eyebrow">{{ $eyebrow ?? $settings->site_name }}</span>
        <h1 class="nx-title">{{ $title }}</h1>
        @if(!empty($lead))
            <p class="nx-sub">{{ $lead }}</p>
        @endif
    </div>
</section>
