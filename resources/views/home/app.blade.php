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

<section class="page-banner" style="background-image:url('{{ asset('wp-content/uploads/2018/11/slide-33.jpg') }}');">
    <div class="page-banner-content max-w-[1400px] mx-auto px-6 py-16 w-full">
        <div class="font-display text-white/70 text-sm uppercase tracking-widest mb-2">{{ $settings->site_name }}</div>
        <h1 class="font-serif text-white text-5xl font-normal">Personal Banking</h1>
    </div>
</section>

<section class="max-w-[1400px] mx-auto px-6 py-20">
    <div class="text-center mb-14">
        <div class="font-display font-semibold text-primary text-sm uppercase tracking-wider mb-3">Our Personal Banking Products</div>
        <h2 class="font-serif text-dark text-4xl font-normal leading-tight mb-5">What We Offer for You</h2>
        <p class="font-sans text-body-text text-base max-w-2xl mx-auto">Everybody has different financial needs. To make managing your finances easier, {{ $settings->site_name }} provides a range of personal banking services, including checking and savings accounts as well as mobile banking.</p>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-mobile text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Mobile Banking</h4>
            <p class="font-sans text-body-text text-sm leading-7">{{ $settings->site_name }} mobile banking allows you to bank from anywhere with just a smartphone or tablet.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-bar-chart text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Checking Accounts</h4>
            <p class="font-sans text-body-text text-sm leading-7">Which of the following options—free checking, NOW checking, golden checking, or bazing checking—is best for you?</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-credit-card text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Debit Cards</h4>
            <p class="font-sans text-body-text text-sm leading-7">Our fast issue debit cards make it easy and quick to access your money.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-line-chart text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Savings</h4>
            <p class="font-sans text-body-text text-sm leading-7">Choose from our traditional savings, children''s savings or christmas club account.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-lightbulb-o text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Individual Retirement Account</h4>
            <p class="font-sans text-body-text text-sm leading-7">Making financial plans doesn''t have to be difficult. We make everything easier for you.</p>
        </div>
        <div class="inner-offer-card">
            <div class="inner-offer-icon"><i class="fa fa-lock text-primary text-2xl"></i></div>
            <h4 class="font-sans font-bold text-dark text-lg mb-3">Safe Deposit Box</h4>
            <p class="font-sans text-body-text text-sm leading-7">Keep your valuables and important documents with {{ $settings->site_name }} safe deposit box.</p>
        </div>
    </div>
</section>

<section class="w-full py-24 px-6" style="background-color:#3e4e83;">
    <div class="max-w-[1100px] mx-auto text-center">
        <div class="font-display font-semibold text-primary text-sm uppercase tracking-wider mb-4">Choose Your Bank</div>
        <h2 class="font-serif text-white text-4xl font-normal leading-tight mb-2">You''ve Got All Reasons to</h2>
        <h2 class="font-serif text-white text-4xl font-normal leading-tight mb-8">Move Your Business to {{ $settings->site_name }} Right Now</h2>
        <p class="font-sans max-w-2xl mx-auto mb-10" style="color:#bebebe;">The most important concerns and opportunities for our clients are the focus of our banking services; strategy, marketing, organization, operations, technology, transformation, and digital.</p>
        <a href="{{ url('login') }}" class="inline-block font-display font-semibold text-white text-sm uppercase tracking-widest px-10 py-4 border-2 border-white hover:bg-white hover:text-primary transition-all">OPEN ACCOUNT</a>
    </div>
</section>

@endsection
