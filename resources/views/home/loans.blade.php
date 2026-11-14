@extends('layouts.landing')

@section('content')

@include('home.partials.page-hero', [
    'title' => 'Loans',
    'eyebrow' => 'Lending',
    'lead' => 'Flexible financing options to help you reach personal and business goals — with clear terms and fast decisions.',
])

<section class="nx-section">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Loan products</span>
            <h2 class="nx-title">Financing that fits your goals</h2>
            <p class="nx-sub">At {{ $settings->site_name }}, we offer diverse lending options to support homes, farms, small businesses, and commercial growth.</p>
        </div>
        <div class="nx-feat-grid nx-loan-grid">
            @foreach([
                ['Personal Loans', 'Whether it\'s for your dream home, a new car, or major life expenses — we\'re here to help.', 'Pl'],
                ['Agricultural Loans', 'Support the expansion of your farm or agribusiness with tailored agricultural financing.', 'Ag'],
                ['SBA Program Loans', 'Small business loans for working capital, inventory, equipment, real estate, and more.', 'Sb'],
                ['Business Loans & Leases', 'Commercial financing products designed for the demands of modern businesses.', 'Bl'],
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

<section class="nx-section nx-section--soft">
    <div class="nx-container">
        <div class="nx-split nx-reveal">
            <div class="nx-split__copy">
                <span class="nx-eyebrow">How it works</span>
                <h2 class="nx-title">Apply with confidence</h2>
                <p>Our loan process is designed to be straightforward — apply online, receive a decision quickly, and get funds when approved.</p>
                <div class="nx-progress-list" style="margin-top:28px">
                    <div class="nx-progress-item">
                        <span><strong>Application review</strong><strong>Step 1</strong></span>
                        <div class="nx-progress"><i style="--p:100%"></i></div>
                    </div>
                    <div class="nx-progress-item">
                        <span><strong>Document verification</strong><strong>Step 2</strong></span>
                        <div class="nx-progress"><i style="--p:75%"></i></div>
                    </div>
                    <div class="nx-progress-item">
                        <span><strong>Approval & disbursement</strong><strong>Step 3</strong></span>
                        <div class="nx-progress"><i style="--p:50%"></i></div>
                    </div>
                </div>
            </div>
            <div class="nx-values">
                <div class="nx-value">
                    <div class="nx-value__icon">%</div>
                    <h3>Competitive rates</h3>
                    <p>Transparent interest rates and terms with no surprises.</p>
                </div>
                <div class="nx-value">
                    <div class="nx-value__icon">⚡</div>
                    <h3>Fast decisions</h3>
                    <p>Quick review so you can move forward without long waits.</p>
                </div>
                <div class="nx-value">
                    <div class="nx-value__icon">🤝</div>
                    <h3>Expert guidance</h3>
                    <p>Loan specialists to help you choose the right product.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('home.partials.page-cta', [
    'ctaTitle' => 'Need financing?',
    'ctaText' => 'Open an account or log in to explore loan options and submit your application.',
    'ctaButton' => 'Get Started',
    'ctaUrl' => route('login'),
])
@endsection
