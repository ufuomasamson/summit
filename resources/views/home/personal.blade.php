@extends('layouts.landing')

@section('content')

@include('home.partials.page-hero', [
    'title' => 'Personal Banking',
    'eyebrow' => 'Personal',
    'lead' => 'Checking, savings, cards, and mobile banking — everything you need to manage daily life in one place.',
])

<section class="nx-section">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Our products</span>
            <h2 class="nx-title">Built for everyday life</h2>
            <p class="nx-sub">Everybody has different financial needs. {{ $settings->site_name }} offers personal banking services designed to make managing your money simple.</p>
        </div>
        <div class="nx-feat-grid">
            @foreach([
                ['Mobile Banking', 'Bank from anywhere with your smartphone or tablet.', 'Mb'],
                ['Checking Accounts', 'Free, NOW, golden, and premium checking options to fit your lifestyle.', 'Ch'],
                ['Debit Cards', 'Fast-issue debit cards for quick, secure access to your money.', 'Dc'],
                ['Savings', 'Traditional savings, children\'s savings, and goal-based vault accounts.', 'Sv'],
                ['Retirement Accounts', 'Plan for the future with individual retirement accounts and guidance.', 'Ir'],
                ['Safe Deposit Box', 'Keep valuables and important documents secure with us.', 'Sd'],
            ] as $item)
            <article class="nx-feat nx-reveal">
                <div class="nx-feat__icon" aria-hidden="true">{{ $item[2] }}</div>
                <h3>{{ $item[0] }}</h3>
                <p>{{ $item[1] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

<section class="nx-section nx-section--dark">
    <div class="nx-container">
        <div class="nx-split nx-reveal">
            <div class="nx-split__copy">
                <span class="nx-eyebrow" style="color:#ff6b7d">Why {{ $settings->site_name }}</span>
                <h2 class="nx-title" style="color:#fff">Banking that fits your life</h2>
                <p class="nx-sub">Instant transfers, digital cards, savings goals, and mobile alerts — designed to keep you in control without the complexity.</p>
            </div>
            <div class="nx-values" style="background:rgba(255,255,255,0.06);padding:32px">
                <div class="nx-value">
                    <div class="nx-value__icon" style="background:rgba(255,255,255,0.1);color:#fff">$</div>
                    <h3 style="color:#fff">No hidden fees</h3>
                    <p style="color:rgba(255,255,255,0.65)">Clear pricing on accounts, transfers, and card services.</p>
                </div>
                <div class="nx-value">
                    <div class="nx-value__icon" style="background:rgba(255,255,255,0.1);color:#fff">24</div>
                    <h3 style="color:#fff">Always on</h3>
                    <p style="color:rgba(255,255,255,0.65)">Mobile and online banking available whenever you need it.</p>
                </div>
                <div class="nx-value">
                    <div class="nx-value__icon" style="background:rgba(255,255,255,0.1);color:#fff">✓</div>
                    <h3 style="color:#fff">Secure by design</h3>
                    <p style="color:rgba(255,255,255,0.65)">Encryption, biometrics, and verification on sensitive actions.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('home.partials.page-cta', [
    'ctaTitle' => 'Start with personal banking',
    'ctaText' => 'Open your account today and experience banking that feels effortless.',
])
@endsection
