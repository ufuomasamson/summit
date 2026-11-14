@extends('layouts.plyson')

@section('content')

<style>
.page-banner { background-size:cover; background-position:center; position:relative; min-height:300px; display:flex; align-items:center; }
.page-banner::before { content:''; position:absolute; inset:0; background:rgba(0,0,0,0.45); }
.page-banner-content { position:relative; z-index:1; }
.inner-offer-card { position:relative; overflow:hidden; border:1px solid #ebebeb; padding:32px 28px; background:#fff; transition:box-shadow 0.3s,border-color 0.3s; cursor:default; }
.inner-offer-card:hover { border-color:#c10e0e; box-shadow:0 6px 28px rgba(193,14,14,0.08); }
.inner-offer-icon { width:60px; height:60px; border-radius:50%; background:#fff2f2; display:inline-flex; align-items:center; justify-content:center; margin-bottom:16px; transition:background 0.3s; }
.inner-offer-card:hover .inner-offer-icon { background:#c10e0e; }
.inner-offer-card:hover .inner-offer-icon i { color:#fff !important; }
</style>

<section class="page-banner" style="background-image:url('{{ asset('temp/wp-content/uploads/2018/11/slide-33.jpg') }}');">
    <div class="page-banner-content max-w-[1400px] mx-auto px-6 py-16 w-full">
        <div class="font-display text-white/70 text-sm uppercase tracking-widest mb-2">{{ $settings->site_name }}</div>
        <h1 class="font-serif text-white text-5xl font-normal">Services</h1>
    </div>
</section>

<section class="max-w-[1400px] mx-auto px-6 py-20">
    <div class="text-center mb-14">
        <div class="font-display font-semibold text-primary text-sm uppercase tracking-wider mb-3">Our Personal Banking Products</div>
        <h2 class="font-serif text-dark text-4xl font-normal leading-tight mb-5">What We Offer for You</h2>
        <p class="font-sans text-body-text text-base max-w-2xl mx-auto">No two people''s financial needs are the same. At {{ $settings->site_name }}, we offer a variety of personal banking services, from Mobile Banking to Checking and Savings accounts, to help make managing your money easier.</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-mobile text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Mobile Banking</h4>
            <p class="font-sans text-body-text text-sm leading-7">Bank where you are with {{ $settings->site_name }}''s mobile banking with just your smartphone or tablet.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-bar-chart text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Checking Accounts</h4>
            <p class="font-sans text-body-text text-sm leading-7">Bazing checking, free checking, NOW checking and golden checking — which one is right for you?</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-credit-card text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Debit Cards</h4>
            <p class="font-sans text-body-text text-sm leading-7">Our instant issue debits cards makes accessing your cash quick and convenient.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-line-chart text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Savings</h4>
            <p class="font-sans text-body-text text-sm leading-7">Choose from our traditional savings, children''s savings or christmas club account.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-lightbulb-o text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Individual Retirement Account</h4>
            <p class="font-sans text-body-text text-sm leading-7">Planning your financial future does not have to be a headache. We simplify everything for you.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-lock text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Safe Deposit Box</h4>
            <p class="font-sans text-body-text text-sm leading-7">Keep your valuables and important documents with {{ $settings->site_name }} safe deposit box.</p>
        </div>
    </div>
</section>

<section style="background-color:#f9f9f9;" class="w-full py-20 px-6">
    <div class="max-w-[1400px] mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
            <div>
                <div class="font-display font-semibold text-primary text-sm uppercase tracking-wider mb-3">Our Strategy</div>
                <h2 class="font-serif text-dark text-4xl font-normal leading-tight mb-6">Industry Expertise</h2>
                <p class="font-sans text-body-text text-base leading-8">Explore the industries most relevant to you. We work with change-oriented executives to help them make better decisions, convert those decisions to actions.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-0">
                <ul class="space-y-3">
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Advanced Manufacturing</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Aerospace Services</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Forest Products &amp; Packaging</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Energy &amp; Natural Resources</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Finance and Regulation</li>
                </ul>
                <ul class="space-y-3 mt-3 sm:mt-0">
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Machinery &amp; Equipment</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Metals &amp; Mining</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Private Equity</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Social &amp; Public Sector</li>
                    <li class="flex items-center gap-3 font-sans text-body-text text-sm"><i class="fa fa-check text-primary text-xs"></i>Utilities &amp; Renewables</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="w-full py-8 px-6 border-y border-gray-200">
    <div class="max-w-[1400px] mx-auto">
        <div class="swiper clients-swiper">
            <div class="swiper-wrapper items-center">
                <div class="swiper-slide flex justify-center"><img src="{{ asset('temp/wp-content/uploads/2018/11/client_5_solana_240x136.png') }}" alt="Solana" class="max-w-[160px] h-auto grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100"></div>
                <div class="swiper-slide flex justify-center"><img src="{{ asset('temp/wp-content/uploads/2018/11/client_4_deters_240x136.png') }}" alt="Deters" class="max-w-[160px] h-auto grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100"></div>
                <div class="swiper-slide flex justify-center"><img src="{{ asset('temp/wp-content/uploads/2018/11/client_3_okta_240x136.png') }}" alt="Okta" class="max-w-[160px] h-auto grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100"></div>
                <div class="swiper-slide flex justify-center"><img src="{{ asset('temp/wp-content/uploads/2018/11/client_2_visla_240x136.png') }}" alt="Visla" class="max-w-[160px] h-auto grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100"></div>
                <div class="swiper-slide flex justify-center"><img src="{{ asset('temp/wp-content/uploads/2018/11/client_6_cube_240x136.png') }}" alt="Cube" class="max-w-[160px] h-auto grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100"></div>
                <div class="swiper-slide flex justify-center"><img src="{{ asset('temp/wp-content/uploads/2018/11/client_7_vetrov_240x136.png') }}" alt="Vetrov" class="max-w-[160px] h-auto grayscale hover:grayscale-0 transition-all opacity-60 hover:opacity-100"></div>
            </div>
        </div>
    </div>
</section>

<section class="w-full py-24 px-6" style="background-color:#3e4e83;">
    <div class="max-w-[1100px] mx-auto text-center">
        <div class="font-display font-semibold text-primary text-sm uppercase tracking-wider mb-4">Choose Your Plan</div>
        <h2 class="font-serif text-white text-4xl font-normal leading-tight mb-2">You''ve Got All Reasons to</h2>
        <h2 class="font-serif text-white text-4xl font-normal leading-tight mb-8">Move Your Business to {{ $settings->site_name }} Right Now</h2>
        <p class="font-sans max-w-2xl mx-auto mb-10" style="color:#bebebe;">The most important concerns and opportunities for our clients are the focus of our banking services; strategy, marketing, organization, operations, technology, transformation, and digital.</p>
        <a href="{{ url('login') }}" class="inline-block font-display font-semibold text-white text-sm uppercase tracking-widest px-10 py-4 border-2 border-white hover:bg-white hover:text-primary transition-all">OPEN ACCOUNT</a>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    new Swiper('.clients-swiper', {
        slidesPerView: 2, spaceBetween: 20, loop: true,
        autoplay: { delay: 3000, disableOnInteraction: false },
        breakpoints: { 480: { slidesPerView: 3 }, 800: { slidesPerView: 4 }, 1025: { slidesPerView: 6 } }
    });
});
</script>
@endpush
