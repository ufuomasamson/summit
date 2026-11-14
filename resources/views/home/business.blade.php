@extends('layouts.landing')

@section('content')

@include('home.partials.page-hero', [
    'title' => 'Business Banking',
    'eyebrow' => 'Business',
    'lead' => 'Tools and accounts designed to help your business move faster — from daily operations to long-term growth.',
])

<section class="nx-section">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Our products</span>
            <h2 class="nx-title">Banking for every business size</h2>
            <p class="nx-sub">We value your business, no matter how big or small. Let {{ $settings->site_name }} help you operate efficiently and grow with confidence.</p>
        </div>
        <div class="nx-feat-grid">
            @foreach([
                ['Commercial Checking', 'Straightforward business checking with flexible check writing and account controls.', 'Cc'],
                ['Remote Deposit Capture', 'Deposit checks without visiting a branch — scan and submit from your office.', 'Rd'],
                ['Business Loans', 'Working capital, equipment, and expansion financing tailored to your company.', 'Bl'],
                ['Payroll Services', 'Pay employees on time with integrated payroll and reporting tools.', 'Py'],
                ['Merchant Services', 'Accept card payments and manage settlements from one dashboard.', 'Ms'],
                ['Treasury Management', 'Cash flow visibility, transfers, and controls for growing teams.', 'Tm'],
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
                <span class="nx-eyebrow">For growing teams</span>
                <h2 class="nx-title">Move your business forward</h2>
                <p>From startup to scale-up, we provide the accounts, lending, and digital tools your team needs to stay focused on what matters.</p>
                <p style="margin-top:16px;color:var(--nx-muted);line-height:1.75">Dedicated support, clear reporting, and secure access controls help you manage finances with confidence.</p>
            </div>
            <div class="nx-values">
                <div class="nx-value">
                    <div class="nx-value__icon">01</div>
                    <h3>Faster operations</h3>
                    <p>Remote deposits, instant transfers, and digital statements save time every week.</p>
                </div>
                <div class="nx-value">
                    <div class="nx-value__icon">02</div>
                    <h3>Flexible lending</h3>
                    <p>Business loans and lines of credit when you need to invest or expand.</p>
                </div>
                <div class="nx-value">
                    <div class="nx-value__icon">03</div>
                    <h3>Dedicated support</h3>
                    <p>Business banking specialists who understand your industry and goals.</p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('home.partials.page-cta', [
    'ctaTitle' => 'Ready to move your business?',
    'ctaText' => 'Open a business account and get the tools to run and grow your company.',
    'ctaButton' => 'Open Business Account',
])
@endsection
