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
    'title' => 'Privacy Policy',
    'eyebrow' => 'Legal',
    'lead' => 'Learn how ' . $settings->site_name . ' collects, uses, and protects your personal information.',
])

<section class="nx-section nx-section--tight">
    <div class="nx-container">
        <div class="nx-legal-wrap nx-legal-wrap--visible">
            <div class="nx-legal">
                @if($policyHtml)
                    {!! $policyHtml !!}
                @else
                    <h2>Overview</h2>
                    <p>{{ $settings->site_name }} is committed to protecting your privacy. This policy explains what personal data we collect, how we use it, and the choices you have.</p>

                    <h2>Information we collect</h2>
                    <ul>
                        <li>Identity and contact details (name, address, email, phone number)</li>
                        <li>Account and transaction information</li>
                        <li>Device, browser, and usage data when you use our website or app</li>
                        <li>Verification data required for security and regulatory compliance</li>
                    </ul>

                    <h2>How we use your information</h2>
                    <ul>
                        <li>To provide and manage your accounts and services</li>
                        <li>To process transactions and prevent fraud</li>
                        <li>To communicate important updates about your account</li>
                        <li>To improve our products, security, and customer experience</li>
                    </ul>

                    <h2>How we protect your data</h2>
                    <p>We use encryption, access controls, monitoring, and other safeguards designed to protect personal information in transit and at rest, in line with applicable data protection laws.</p>

                    <h2>Sharing of information</h2>
                    <p>We do not sell your personal data. We may share information with trusted service providers, payment networks, regulators, or law enforcement when required by law or to protect our customers and platform.</p>

                    <h2>Your rights</h2>
                    <p>Depending on your location, you may have rights to access, correct, delete, or restrict processing of your personal data. Contact us to submit a privacy request.</p>

                    <h2>Contact us</h2>
                    <p>For privacy questions, email <a href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a> or use our <a href="{{ route('contact') }}">contact form</a>.</p>
                @endif
            </div>
        </div>
    </div>
</section>

@include('home.partials.page-cta', [
    'ctaTitle' => 'Your privacy matters',
    'ctaText' => 'Reach out if you have questions about how we handle your data.',
    'ctaButton' => 'Contact us',
    'ctaUrl' => route('contact'),
])
@endsection
