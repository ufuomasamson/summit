@extends('layouts.landing')

@section('content')

@include('home.partials.page-hero', [
    'title' => 'Contact',
    'eyebrow' => 'Get in touch',
    'lead' => 'We would love to hear from you. Reach out directly or send us a message below.',
])

<section class="nx-section nx-section--tight">
    <div class="nx-container">
        <div class="nx-contact-grid nx-reveal">
            <div class="nx-contact-card">
                <div class="nx-contact-card__icon">☎</div>
                <h3>Phone</h3>
                <p>{{ $settings->whatsapp ?? '+1 (212) 456-8000' }}</p>
            </div>
            <div class="nx-contact-card">
                <div class="nx-contact-card__icon">📍</div>
                <h3>Address</h3>
                <p>{{ $settings->address ?? 'New York, NY, USA' }}</p>
            </div>
            <div class="nx-contact-card">
                <div class="nx-contact-card__icon">✉</div>
                <h3>Email</h3>
                <p>{{ $settings->contact_email }}</p>
            </div>
        </div>

        <div class="nx-split nx-reveal">
            <div>
                <span class="nx-eyebrow">Send a message</span>
                <h2 class="nx-title" style="font-size:clamp(1.6rem,3vw,2.2rem)">Contact {{ $settings->site_name }}</h2>

                @if(session('success'))
                    <div class="nx-alert nx-alert--ok">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="nx-alert nx-alert--err">{{ session('error') }}</div>
                @endif

                <form action="{{ route('homesendcontact') }}" method="POST" class="nx-form-grid" style="margin-top:28px">
                    @csrf
                    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
                    <div class="nx-form-grid nx-form-grid--2">
                        <div class="nx-field">
                            <input type="text" name="name" placeholder="Your name *" required>
                        </div>
                        <div class="nx-field">
                            <input type="email" name="email" placeholder="Your email *" required>
                        </div>
                    </div>
                    <div class="nx-field">
                        <input type="text" name="subject" placeholder="Subject">
                    </div>
                    <div class="nx-field">
                        <textarea name="message" placeholder="Your message *" required></textarea>
                    </div>
                    <button type="submit" class="nx-btn nx-btn--primary">
                        <span>Send message</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </form>
            </div>
            <div>
                <span class="nx-eyebrow">Our location</span>
                <h2 class="nx-title" style="font-size:clamp(1.6rem,3vw,2.2rem);margin-bottom:20px">Find us</h2>
                <div class="nx-map">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d387193.30596073366!2d-74.25987368715491!3d40.69714941680757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNew+York%2C+NY%2C+USA!5e0!3m2!1sen!2s!4v1539791893415"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="Map location">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

@include('home.partials.page-cta', [
    'ctaTitle' => 'Prefer to get started online?',
    'ctaText' => 'Open your account in minutes and manage everything from your dashboard.',
])
@endsection
