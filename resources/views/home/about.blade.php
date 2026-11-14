@extends('layouts.landing')

@section('content')
@php
    $nxAboutImg = fn (string $path) => asset('temp/wp-content/uploads/' . ltrim($path, '/'));
@endphp

@include('home.partials.page-hero', [
    'title' => 'About Us',
    'eyebrow' => 'Company',
    'lead' => 'We deliver excellent banking services with a focus on trust, clarity, and long-term financial success for every customer.',
])

<section class="nx-section">
    <div class="nx-container">
        <div class="nx-split nx-reveal">
            <div class="nx-split__copy">
                <span class="nx-eyebrow">What makes us unique</span>
                <h2 class="nx-title">Banking built around people</h2>
                <p>{{ $settings->site_name }} is a leading financial institution helping individuals and businesses make confident decisions, take action, and achieve lasting success.</p>
                <p>We provide strategic guidance and modern banking tools for the moments that matter most — from everyday spending to long-term growth.</p>
            </div>
            <div class="nx-split__media">
                <img src="{{ $nxAboutImg('2018/11/video_bg-1.jpg') }}" alt="Inside {{ $settings->site_name }}" width="800" height="600" loading="lazy">
            </div>
        </div>
    </div>
</section>

<section class="nx-section nx-section--soft">
    <div class="nx-container">
        <div class="nx-values nx-reveal">
            <div class="nx-value">
                <div class="nx-value__icon">01</div>
                <h3>Best advisors</h3>
                <p>Expert support ready to help with accounts, transfers, cards, and financial planning.</p>
            </div>
            <div class="nx-value">
                <div class="nx-value__icon">02</div>
                <h3>Trusted partner</h3>
                <p>Thousands of customers rely on us for secure, transparent, and dependable banking.</p>
            </div>
            <div class="nx-value">
                <div class="nx-value__icon">03</div>
                <h3>Deep experience</h3>
                <p>Decades of expertise across personal, business, and digital banking services.</p>
            </div>
        </div>
    </div>
</section>

<section class="nx-section">
    <div class="nx-container">
        <div class="nx-split nx-reveal">
            <div class="nx-split__copy">
                <span class="nx-eyebrow">Our beliefs</span>
                <h2 class="nx-title">What we believe</h2>
                <p>Since 1986, {{ $settings->site_name }} has served local families and businesses with trusted financial services. Community banking is about more than accounts and transactions.</p>
                <p>Every person in our community has a face and a name here. We are proud to serve you — and through you, our community.</p>
            </div>
            <div class="nx-split__copy">
                <span class="nx-eyebrow">Our strategy</span>
                <h2 class="nx-title">Why choose us</h2>
                <div class="nx-progress-list">
                    <div class="nx-progress-item">
                        <span><strong>Customer satisfaction</strong><strong>97%</strong></span>
                        <div class="nx-progress"><i style="--p:97%"></i></div>
                    </div>
                    <div class="nx-progress-item">
                        <span><strong>Successful negotiations</strong><strong>88%</strong></span>
                        <div class="nx-progress"><i style="--p:88%"></i></div>
                    </div>
                    <div class="nx-progress-item">
                        <span><strong>Business growth</strong><strong>85%</strong></span>
                        <div class="nx-progress"><i style="--p:85%"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="nx-section nx-section--soft">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Leadership</span>
            <h2 class="nx-title">Meet our team</h2>
            <p class="nx-sub">Experienced leaders committed to secure, modern, and customer-first banking.</p>
        </div>
        <div class="nx-team-grid">
            @foreach([
                ['name' => 'Thomas Bodine', 'role' => 'Executive Director', 'photo' => '2018/10/team-member_1-740x780.jpg'],
                ['name' => 'Mark Renold', 'role' => 'Marketing Manager', 'photo' => '2018/10/team-member_2-740x780.jpg'],
                ['name' => 'John Morisson', 'role' => 'Finance Manager', 'photo' => '2018/10/team-member_3-740x780.jpg'],
                ['name' => 'Angela Kwong', 'role' => 'Customer Relations Manager', 'photo' => '2018/12/team-5.jpg'],
                ['name' => 'Jameson Wood', 'role' => 'HR Manager', 'photo' => '2018/10/team-member_5-740x780.jpg'],
            ] as $member)
            <article class="nx-team-card nx-reveal">
                <img src="{{ $nxAboutImg($member['photo']) }}" alt="{{ $member['name'] }}" width="400" height="500" loading="lazy">
                <div class="nx-team-card__body">
                    <h3>{{ $member['name'] }}</h3>
                    <p class="nx-team-card__role">{{ $member['role'] }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>

<section class="nx-marquee" aria-label="Trusted partners">
    <div class="nx-marquee__track">
        @foreach(['Visa','Mastercard','Apple Pay','Google Pay','Verve','NIBSS','Visa','Mastercard','Apple Pay','Google Pay','Verve','NIBSS'] as $logo)
            <div class="nx-marquee__item">{{ $logo }}</div>
        @endforeach
    </div>
</section>

@include('home.partials.page-cta')
@endsection
