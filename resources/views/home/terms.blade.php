@php
    if ($settings->redirect_url != null or !empty($settings->redirect_url)) {
        header("Location: $settings->redirect_url", true, 301);
        exit();
    }
    $policyHtml = ($terms && !empty($terms->description)) ? $terms->description : null;
@endphp
@extends('layouts.landing')

@section('content')

@include('home.partials.page-hero', [
    'title' => 'Terms of Service',
    'eyebrow' => 'Legal',
    'lead' => 'Please read these terms carefully before using ' . $settings->site_name . ' products and services.',
])

<section class="nx-section nx-section--tight">
    <div class="nx-container">
        <div class="nx-legal-wrap nx-legal-wrap--visible">
            <div class="nx-legal">
                @if($policyHtml)
                    {!! $policyHtml !!}
                @else
                    <h2>Agreement to terms</h2>
                    <p>By accessing or using {{ $settings->site_name }} websites, mobile applications, and banking services, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>

                    <h2>Eligibility</h2>
                    <p>You must be at least 18 years old and legally able to enter into a binding agreement to open or maintain an account with {{ $settings->site_name }}.</p>

                    <h2>Account responsibilities</h2>
                    <ul>
                        <li>You are responsible for keeping your login credentials confidential.</li>
                        <li>You must provide accurate and complete information when registering or applying for services.</li>
                        <li>You agree to notify us promptly of any unauthorized access or suspicious activity on your account.</li>
                    </ul>

                    <h2>Use of services</h2>
                    <p>Our services may include accounts, transfers, cards, loans, savings, and investments. You agree not to use our platform for unlawful activity, fraud, money laundering, or any purpose that violates these terms.</p>

                    <h2>Fees and transactions</h2>
                    <p>Applicable fees, limits, and processing times are disclosed during account opening and within your dashboard. We may decline or reverse transactions that appear fraudulent or violate policy.</p>

                    <h2>Limitation of liability</h2>
                    <p>To the fullest extent permitted by law, {{ $settings->site_name }} is not liable for indirect, incidental, or consequential damages arising from your use of our services, except where prohibited by applicable regulation.</p>

                    <h2>Changes to these terms</h2>
                    <p>We may update these terms from time to time. Continued use of our services after changes are posted constitutes acceptance of the revised terms.</p>

                    <h2>Contact</h2>
                    <p>Questions about these terms? Email <a href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a> or visit our <a href="{{ route('contact') }}">contact page</a>.</p>
                @endif
            </div>
        </div>
    </div>
</section>

@include('home.partials.page-cta', [
    'ctaTitle' => 'Questions about our terms?',
    'ctaText' => 'Our team is here to help — reach out anytime.',
    'ctaButton' => 'Contact us',
    'ctaUrl' => route('contact'),
])
@endsection
