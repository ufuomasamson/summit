@extends('layouts.landing')

@section('content')
@php
    $nxBase = rtrim(request()->getBasePath(), '/');
    $nxImg = $nxBase . '/assets/landing';
@endphp

@include('home.partials.page-hero', [
    'title' => 'Our History',
    'eyebrow' => 'Corporate history',
    'lead' => 'A trusted partner since 1986 — growing with our community while staying rooted in service and integrity.',
])

<section class="nx-section nx-section--tight">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Our journey</span>
            <h2 class="nx-title">Built on trust and innovation</h2>
            <p class="nx-sub">{{ $settings->site_name }} was established to serve the local community with trusted financial services. Over the decades, we have expanded our reach while keeping our commitment to customers at the center of everything we do.</p>
        </div>
    </div>
</section>

<section class="nx-section nx-section--soft">
    <div class="nx-container">
        <div class="nx-timeline nx-reveal">
            <article class="nx-timeline__item">
                <div class="nx-timeline__dot"></div>
                <div>
                    <span class="nx-timeline__year">1986</span>
                    <h3>Founded</h3>
                    <p>{{ $settings->site_name }} opens its first branch, offering checking, savings, and personal loan products to the local community.</p>
                </div>
            </article>
            <article class="nx-timeline__item">
                <div class="nx-timeline__dot"></div>
                <div>
                    <span class="nx-timeline__year">1996</span>
                    <h3>Regional expansion</h3>
                    <p>After a decade of steady growth, we expand to five additional branch locations, bringing convenient banking to a wider regional customer base.</p>
                </div>
            </article>
            <article class="nx-timeline__item">
                <div class="nx-timeline__dot"></div>
                <div>
                    <span class="nx-timeline__year">2006</span>
                    <h3>Digital banking launch</h3>
                    <p>Our first online banking platform lets customers manage accounts, transfer funds, and pay bills securely from any connected device.</p>
                </div>
            </article>
            <article class="nx-timeline__item">
                <div class="nx-timeline__dot"></div>
                <div>
                    <span class="nx-timeline__year">2016</span>
                    <h3>Award-winning service</h3>
                    <p>Industry recognition for customer satisfaction and community investment cements our reputation as a trusted financial institution.</p>
                </div>
            </article>
            <article class="nx-timeline__item">
                <div class="nx-timeline__dot"></div>
                <div>
                    <span class="nx-timeline__year">2019</span>
                    <h3>Mobile banking app</h3>
                    <p>We launch our mobile app with biometric login, instant transfers, and real-time notifications — redefining everyday banking convenience.</p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="nx-section">
    <div class="nx-container">
        <div class="nx-split nx-reveal">
            <div class="nx-split__media">
                <img src="{{ $nxImg }}/people-band.png" alt="{{ $settings->site_name }} history" width="800" height="600" loading="lazy">
            </div>
            <div class="nx-split__copy">
                <span class="nx-eyebrow">Today</span>
                <h2 class="nx-title">A legacy built on trust</h2>
                <p>From our first branch to a full digital banking platform, our mission has stayed the same: help people and businesses manage money with confidence.</p>
                <p>We continue investing in security, technology, and customer experience — so banking feels simple, fast, and secure.</p>
            </div>
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

@include('home.partials.page-cta', [
    'ctaTitle' => 'Join our family',
    'ctaText' => 'A legacy built on trust, community, and innovation — start your journey with us today.',
])
@endsection
