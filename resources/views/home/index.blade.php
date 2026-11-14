@php
    if ($settings->redirect_url != null or !empty($settings->redirect_url)) {
        header("Location: $settings->redirect_url", true, 301);
        exit();
    }
@endphp
@extends('layouts.landing')

@section('content')
@php
    $nxBase = rtrim(request()->getBasePath(), '/');
    $nxImg = $nxBase . '/assets/landing';
@endphp

{{-- 1. Hero --}}
<section class="nx-hero" data-nx-hero>
    <div class="nx-hero__bg" aria-hidden="true">
        <div class="nx-hero__waves"></div>
        <div class="nx-hero__lines"></div>
        <div class="nx-hero__particles" data-nx-particles></div>
    </div>

    <div class="nx-hero__grid">
        <div class="nx-hero__copy">
            <h1 class="nx-hero__brand">{{ $settings->site_name }}</h1>
            <span class="nx-hero__kicker">Banking Made</span>
            <div class="nx-hero__rotate" aria-live="polite">
                <div class="nx-hero__rotate-track">
                    <span>Easily.</span>
                    <span>Fast.</span>
                    <span>Smarter.</span>
                    <span>Secure.</span>
                    <span>Borderless.</span>
                    <span>Easily.</span>
                </div>
            </div>
            <p class="nx-hero__lead">
                Instant transfers, digital cards, investments and savings — designed to feel effortless from the first tap.
            </p>
            <div class="nx-hero__ctas">
                <a href="{{ route('register') }}" class="nx-btn nx-btn--primary nx-btn--magnetic">
                    <span>Open Account</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <a href="{{ route('login') }}" class="nx-btn nx-btn--ghost">Login</a>
            </div>
        </div>

        <div class="nx-phones" data-nx-phones>
            <div class="nx-phone nx-phone--1" data-phone="1">
                <div class="nx-phone__notch"></div>
                <div class="nx-phone__screen">
                    <div class="nx-phone__label">Transactions</div>
                    <div class="nx-phone__balance" data-nx-balance>$12,450</div>
                    <div class="nx-tx"><span>Salary · Direct deposit</span><strong class="up">+$3,200</strong></div>
                    <div class="nx-tx"><span>Transfer · Maya</span><strong class="down">-$125.00</strong></div>
                    <div class="nx-tx"><span>Card · Netflix</span><strong class="down">-$15.99</strong></div>
                    <div class="nx-tx"><span>Interest · Save</span><strong class="up">+$18.40</strong></div>
                    <div class="nx-notif">Transfer successful · $125.00 sent</div>
                    <div class="nx-confetti" data-nx-confetti aria-hidden="true"></div>
                </div>
            </div>

            <div class="nx-phone nx-phone--2" data-phone="2">
                <div class="nx-phone__notch"></div>
                <div class="nx-phone__screen">
                    <div class="nx-phone__label">Debit Card</div>
                    <div class="nx-phone__balance">Ruby Card</div>
                    <div class="nx-phone__card-face">
                        <span>{{ $settings->site_name }}</span>
                        <strong>•••• 8842</strong>
                    </div>
                    <div class="nx-tx" style="margin-top:16px"><span>Available</span><strong>$1,852.00</strong></div>
                    <div class="nx-tx"><span>Status</span><strong class="up">Active</strong></div>
                </div>
            </div>

            <div class="nx-phone nx-phone--3" data-phone="3">
                <div class="nx-phone__notch"></div>
                <div class="nx-phone__screen">
                    <div class="nx-phone__label">Analytics</div>
                    <div class="nx-phone__balance">Spending</div>
                    <div class="nx-chart-bars" aria-hidden="true">
                        <i></i><i></i><i></i><i></i><i></i>
                    </div>
                    <div class="nx-tx"><span>This month</span><strong>-12%</strong></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Trusted By --}}
<section class="nx-marquee" aria-label="Trusted partners">
    <div class="nx-marquee__track">
        @foreach(['Visa','Mastercard','Apple Pay','Google Pay','Verve','NIBSS','Visa','Mastercard','Apple Pay','Google Pay','Verve','NIBSS'] as $logo)
            <div class="nx-marquee__item">{{ $logo }}</div>
        @endforeach
    </div>
</section>

{{-- People band --}}
<section class="nx-people" aria-label="Customers using the app">
    <div class="nx-people__frame nx-reveal">
        <img src="{{ $nxImg }}/people-band.png" alt="People banking with {{ $settings->site_name }}" width="1600" height="900" loading="lazy">
        <div class="nx-people__caption">
            <strong>Real people. Real money moments.</strong>
            <span>Transfers, cards, and savings — built for everyday life in dollars.</span>
        </div>
    </div>
</section>

{{-- Why Customers Love Us — horizontal story --}}
<section class="nx-story" id="why">
    <div class="nx-story__head nx-reveal">
        <span class="nx-eyebrow">Why customers love us</span>
        <h2 class="nx-title" style="color:#fff">Banking that moves with you</h2>
    </div>
    <div class="nx-story__scroller" data-nx-hscroll>
        <article class="nx-story__panel">
            <div class="nx-story__content">
                <div>
                    <h3 class="nx-title" style="color:#fff;font-size:clamp(1.8rem,4vw,2.8rem)">Instant Transfers</h3>
                    <p class="nx-sub" style="color:rgba(255,255,255,0.7)">Money flies between accounts in under a second — locally and across borders.</p>
                </div>
                <div class="nx-story__visual">
                    <div class="nx-money-fly">
                        <div class="dot dot--a">You</div>
                        <div class="packet"></div>
                        <div class="dot dot--b">Them</div>
                    </div>
                </div>
            </div>
        </article>
        <article class="nx-story__panel">
            <div class="nx-story__content">
                <div>
                    <h3 class="nx-title" style="color:#fff;font-size:clamp(1.8rem,4vw,2.8rem)">Virtual Cards</h3>
                    <p class="nx-sub" style="color:rgba(255,255,255,0.7)">Glass-smooth digital cards with freeze, fund, and spend controls built in.</p>
                </div>
                <div class="nx-story__visual">
                    <div class="nx-card-spin">
                        <div style="font-size:11px;opacity:.6;margin-bottom:40px">VIRTUAL · DEBIT</div>
                        <div style="letter-spacing:.15em;font-size:13px">••••  4419</div>
                        <div style="margin-top:20px;font-size:12px;display:flex;justify-content:space-between"><span>RUBY</span><span>09/28</span></div>
                    </div>
                </div>
            </div>
        </article>
        <article class="nx-story__panel">
            <div class="nx-story__content">
                <div>
                    <h3 class="nx-title" style="color:#fff;font-size:clamp(1.8rem,4vw,2.8rem)">Investments</h3>
                    <p class="nx-sub" style="color:rgba(255,255,255,0.7)">Watch growth compound as charts climb and coins stack toward your goals.</p>
                </div>
                <div class="nx-story__visual">
                    <div class="nx-coin-stack" aria-hidden="true">
                        <span></span><span></span><span></span><span></span><span></span>
                    </div>
                </div>
            </div>
        </article>
        <article class="nx-story__panel">
            <div class="nx-story__content">
                <div>
                    <h3 class="nx-title" style="color:#fff;font-size:clamp(1.8rem,4vw,2.8rem)">Loans</h3>
                    <p class="nx-sub" style="color:rgba(255,255,255,0.7)">Apply in minutes. Watch approval assemble itself — no paperwork theater.</p>
                </div>
                <div class="nx-story__visual">
                    <div class="nx-loan-form">
                        <div class="bar"><i></i></div>
                        <div class="bar"><i style="animation-delay:.2s"></i></div>
                        <div class="bar"><i style="animation-delay:.4s"></i></div>
                        <div class="nx-badge-ok">✓ Approved</div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>

{{-- Banking Experience --}}
<section class="nx-section nx-experience" id="experience">
    <div class="nx-container">
        <div class="nx-head nx-reveal">
            <span class="nx-eyebrow">The experience</span>
            <h2 class="nx-title">Banking, distilled.</h2>
        </div>
        <div class="nx-experience__sticky" data-nx-experience>
            <div class="nx-experience__copy">
                <div class="nx-exp-step is-active" data-exp-step="0">
                    <h3>See everything at a glance</h3>
                    <p>Balances, goals, and recent activity — composed like a product, not a spreadsheet.</p>
                </div>
                <div class="nx-exp-step" data-exp-step="1">
                    <h3>Move money in two taps</h3>
                    <p>Local, international, and internal transfers with clear confirmation states.</p>
                </div>
                <div class="nx-exp-step" data-exp-step="2">
                    <h3>Cards that feel physical</h3>
                    <p>Fund, freeze, and track every swipe without leaving the app.</p>
                </div>
                <div class="nx-exp-step" data-exp-step="3">
                    <h3>Grow on autopilot</h3>
                    <p>Investments and savings plans that credit returns when they mature.</p>
                </div>
            </div>
            <div class="nx-experience__stage">
                <div class="nx-exp-screen" data-exp-screen data-screen="0">
                    <div class="nx-phone__label">Dashboard</div>
                    <div class="nx-phone__balance">$48,200</div>
                    <div class="nx-tx"><span>Available</span><strong>$28,420</strong></div>
                    <div class="nx-tx"><span>Invested</span><strong>$14,310</strong></div>
                    <div class="nx-tx"><span>Savings</span><strong>$5,470</strong></div>
                    <div class="nx-chart-bars" style="height:80px;margin-top:24px"><i></i><i></i><i></i><i></i><i></i></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Smart Statistics --}}
<section class="nx-section nx-stats">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal" style="color:#fff">
            <span class="nx-eyebrow" style="color:rgba(255,255,255,0.85)">Proof in numbers</span>
            <h2 class="nx-title" style="color:#fff">Built for scale. Tuned for speed.</h2>
        </div>
        <div class="nx-stats__grid">
            <div class="nx-stat nx-reveal">
                <div class="nx-stat__num" data-nx-count data-target="12" data-prefix="$" data-suffix="B+">0</div>
                <div class="nx-stat__label">Transactions</div>
            </div>
            <div class="nx-stat nx-reveal">
                <div class="nx-stat__num" data-nx-count data-target="95" data-suffix="%">0</div>
                <div class="nx-stat__label">Customer Satisfaction</div>
            </div>
            <div class="nx-stat nx-reveal">
                <div class="nx-stat__num" data-nx-count data-target="5" data-suffix="M+">0</div>
                <div class="nx-stat__label">Users</div>
            </div>
            <div class="nx-stat nx-reveal">
                <div class="nx-stat__num" data-nx-count data-target="0.8" data-decimals="1" data-suffix=" sec">0</div>
                <div class="nx-stat__label">Average Transfer</div>
            </div>
        </div>
    </div>
</section>

{{-- Live Dashboard --}}
<section class="nx-section nx-dashboard" id="dashboard">
    <div class="nx-container">
        <div class="nx-head nx-reveal">
            <span class="nx-eyebrow">Live banking</span>
            <h2 class="nx-title">A dashboard that feels alive</h2>
        </div>
        <div class="nx-dash-grid">
            <div class="nx-dash-card nx-dash-card--wide nx-reveal">
                <h4>Recent Transactions</h4>
                <div class="nx-tx"><span>Transfer to Maya</span><strong class="down">-$250.00</strong></div>
                <div class="nx-tx"><span>Direct deposit</span><strong class="up">+$3,200.00</strong></div>
                <div class="nx-tx"><span>Card · Uber</span><strong class="down">-$34.20</strong></div>
                <div class="nx-tx"><span>ROI credit</span><strong class="up">+$82.50</strong></div>
            </div>
            <div class="nx-dash-card nx-reveal">
                <h4>Spend mix</h4>
                <div class="nx-pie" aria-hidden="true"></div>
                <p style="text-align:center;font-size:13px;color:var(--nx-muted)">Lifestyle 62%</p>
            </div>
            <div class="nx-dash-card nx-reveal">
                <h4>Balance</h4>
                <div style="font-family:var(--nx-display);font-size:2rem;font-weight:800;letter-spacing:-0.04em">$8,421.90</div>
                <p style="font-size:13px;color:#0a8f4a;margin-top:6px">↑ 4.2% this week</p>
            </div>
            <div class="nx-dash-card nx-reveal">
                <h4>Credit Score</h4>
                <div style="font-family:var(--nx-display);font-size:2.4rem;font-weight:800;color:var(--nx-red)">742</div>
                <div class="nx-progress"><i style="--p:74%"></i></div>
            </div>
            <div class="nx-dash-card nx-dash-card--wide nx-reveal">
                <h4>Savings Goals</h4>
                <div class="nx-tx"><span>Emergency fund</span><strong>68%</strong></div>
                <div class="nx-progress"><i style="--p:68%"></i></div>
                <div class="nx-tx" style="margin-top:14px"><span>Vacation</span><strong>42%</strong></div>
                <div class="nx-progress"><i style="--p:42%"></i></div>
            </div>
            <div class="nx-dash-card nx-reveal">
                <h4>Budgets</h4>
                <div class="nx-tx"><span>Food</span><strong>$180 / $250</strong></div>
                <div class="nx-progress"><i style="--p:72%"></i></div>
            </div>
            <div class="nx-dash-card nx-reveal">
                <h4>Investments</h4>
                <div style="font-family:var(--nx-display);font-size:1.6rem;font-weight:700">$31,040</div>
                <p style="font-size:13px;color:var(--nx-muted);margin-top:6px">3 active plans</p>
            </div>
        </div>
    </div>
</section>

{{-- Card Showcase --}}
<section class="nx-section nx-cards-show" id="cards">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Card showcase</span>
            <h2 class="nx-title">Cards with presence</h2>
            <p class="nx-sub">Black, Ruby, White, and Premium finishes.</p>
        </div>
        <div class="nx-card-stage" data-nx-card-stage>
            <div class="nx-debit nx-debit--black nx-reveal" data-nx-tilt>
                <div class="nx-debit__chip"></div>
                <div class="nx-debit__num">•••• •••• •••• 1024</div>
                <div class="nx-debit__meta"><span>BLACK</span><span>12/29</span></div>
            </div>
            <div class="nx-debit nx-debit--ruby nx-reveal" data-nx-tilt>
                <div class="nx-debit__chip"></div>
                <div class="nx-debit__num">•••• •••• •••• 8842</div>
                <div class="nx-debit__meta"><span>RUBY</span><span>09/28</span></div>
            </div>
            <div class="nx-debit nx-debit--white nx-reveal" data-nx-tilt>
                <div class="nx-debit__chip"></div>
                <div class="nx-debit__num">•••• •••• •••• 3310</div>
                <div class="nx-debit__meta"><span>WHITE</span><span>03/30</span></div>
            </div>
            <div class="nx-debit nx-debit--premium nx-reveal" data-nx-tilt>
                <div class="nx-debit__chip"></div>
                <div class="nx-debit__num">•••• •••• •••• 7701</div>
                <div class="nx-debit__meta"><span>PREMIUM</span><span>01/31</span></div>
            </div>
        </div>
    </div>
</section>

{{-- AI Assistant --}}
<section class="nx-section nx-ai" id="ai">
    <div class="nx-container">
        <div class="nx-ai__wrap">
            <div class="nx-reveal">
                <span class="nx-eyebrow">AI financial assistant</span>
                <h2 class="nx-title">Ask. Plan. Grow.</h2>
                <p class="nx-sub">A living conversation that projects savings, suggests next steps, and updates your path in real time.</p>
                <div class="nx-ai__people" aria-hidden="true">
                    <img src="{{ $nxImg }}/person-1.png" alt="" width="72" height="72" loading="lazy">
                    <img src="{{ $nxImg }}/person-2.png" alt="" width="72" height="72" loading="lazy">
                    <img src="{{ $nxImg }}/person-3.png" alt="" width="72" height="72" loading="lazy">
                </div>
            </div>
            <div class="nx-chat nx-reveal" aria-label="AI conversation demo">
                <div class="nx-chat__row nx-chat__row--user">
                    <div class="nx-chat__bubble nx-chat__bubble--user">Can I save $500 monthly?</div>
                    <img class="nx-chat__face" src="{{ $nxImg }}/person-1.png" alt="" width="40" height="40">
                </div>
                <div class="nx-chat__row nx-chat__row--ai">
                    <img class="nx-chat__face" src="{{ $nxImg }}/person-2.png" alt="" width="40" height="40">
                    <div class="nx-chat__bubble nx-chat__bubble--ai">Absolutely. At that pace, your projected savings are <strong>$6,000</strong> in 12 months.</div>
                </div>
                <div class="nx-chat__row nx-chat__row--ai">
                    <img class="nx-chat__face" src="{{ $nxImg }}/person-2.png" alt="" width="40" height="40">
                    <div class="nx-chat__bubble nx-chat__bubble--ai delay-2">Suggestion: auto-sweep $500 on payday into a high-yield vault.</div>
                </div>
                <div class="nx-chat__row nx-chat__row--ai">
                    <img class="nx-chat__face" src="{{ $nxImg }}/person-2.png" alt="" width="40" height="40">
                    <div class="nx-chat__bubble nx-chat__bubble--ai delay-3">Chart updated — you’re on track for your emergency fund by October.</div>
                </div>
                <div class="nx-chat__hint">
                    <span class="nx-chip">Emergency fund</span>
                    <span class="nx-chip">Auto-sweep</span>
                    <span class="nx-chip">$6,000 / yr</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Security --}}
<section class="nx-section nx-security" id="security">
    <div class="nx-container">
        <div class="nx-security__grid">
            <div class="nx-reveal">
                <span class="nx-eyebrow" style="color:#ff6b7d">Security</span>
                <h2 class="nx-title" style="color:#fff">Trust, engineered.</h2>
                <p class="nx-sub" style="color:rgba(255,255,255,0.65)">Encryption, biometrics, and layered verification to keep your money safe.</p>
                <ul class="nx-sec-list">
                    <li>
                        <strong style="color:#fff">Bank-grade encryption</strong>
                        <span>Data protected in transit and at rest with modern cipher suites.</span>
                    </li>
                    <li>
                        <strong style="color:#fff">Biometric unlock</strong>
                        <span>Fingerprint and Face ID style flows for sensitive actions.</span>
                    </li>
                    <li>
                        <strong style="color:#fff">Multi-step transfers</strong>
                        <span>Codes and rate limits keep high-value moves intentional.</span>
                    </li>
                </ul>
            </div>
            <div class="nx-shield nx-reveal" aria-hidden="true">
                <div class="nx-shield__ring"></div>
                <svg viewBox="0 0 200 240" fill="none">
                    <path d="M100 12 L180 48 V110 C180 170 140 210 100 228 C60 210 20 170 20 110 V48 Z"
                          fill="url(#nxShieldGrad)" stroke="rgba(255,255,255,0.25)" stroke-width="2"/>
                    <path d="M70 118 L92 140 L134 90" stroke="#fff" stroke-width="10" stroke-linecap="round" stroke-linejoin="round"
                          style="stroke-dasharray:120;stroke-dashoffset:120;animation:nxDraw 2.5s ease forwards infinite"/>
                    <defs>
                        <linearGradient id="nxShieldGrad" x1="20" y1="12" x2="180" y2="228">
                            <stop stop-color="#D6001C"/>
                            <stop offset="1" stop-color="#1A1A1A"/>
                        </linearGradient>
                    </defs>
                </svg>
            </div>
        </div>
    </div>
</section>
<style>@keyframes nxDraw{0%{stroke-dashoffset:120}40%,100%{stroke-dashoffset:0}}</style>

{{-- Features Grid --}}
<section class="nx-section nx-features" id="features">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Everything you need</span>
            <h2 class="nx-title">Features with motion</h2>
        </div>
        <div class="nx-feat-grid">
            @foreach([
                ['Transfer', 'Money moves instantly between people and banks.', 'Tf'],
                ['Savings', 'Vaults that fill toward goals on autopilot.', 'Sv'],
                ['Loans', 'Applications that assemble and approve cleanly.', 'Ln'],
                ['Cards', 'Virtual debit that flips, freezes, and funds.', 'Cd'],
                ['Rewards', 'Earn as you spend — coins that celebrate wins.', 'Rw'],
                ['Insurance', 'Coverage that forms a shield around what matters.', 'In'],
                ['Crypto', 'Exchange and hold with clear ledger trails.', 'Cr'],
                ['QR Payments', 'Scan, confirm, done — in a breath.', 'QR'],
            ] as $feat)
            <article class="nx-feat nx-reveal">
                <div class="nx-feat__icon" aria-hidden="true">{{ $feat[2] }}</div>
                <h3>{{ $feat[0] }}</h3>
                <p>{{ $feat[1] }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Customer Stories --}}
<section class="nx-section nx-stories" id="stories">
    <div class="nx-container">
        <div class="nx-head nx-head--center nx-reveal">
            <span class="nx-eyebrow">Customer stories</span>
            <h2 class="nx-title">What our customers say</h2>
        </div>
        <div class="nx-bubbles">
            @php
                $storyPhotos = [
                    $nxImg . '/person-1.png',
                    $nxImg . '/person-2.png',
                    $nxImg . '/person-3.png',
                ];
                $stories = isset($test) ? $test->take(3) : collect();
                if ($stories->isEmpty()) {
                    $stories = collect([
                        (object)['name' => 'Maya R.', 'position' => 'Founder', 'what_is_said' => 'Transfers finally feel instant. Opening an account took minutes, not days.'],
                        (object)['name' => 'Jordan L.', 'position' => 'Designer', 'what_is_said' => 'The cards and dashboard feel like a product I would actually show friends.'],
                        (object)['name' => 'Elena M.', 'position' => 'Trader', 'what_is_said' => 'Investments matured on schedule and the notifications kept me calm.'],
                    ]);
                }
            @endphp
            @foreach($stories as $i => $story)
            <article class="nx-bubble nx-reveal">
                <div class="nx-bubble__photo">
                    <img src="{{ $storyPhotos[$i % 3] }}" alt="{{ $story->name ?? 'Customer' }}" width="320" height="200" loading="lazy">
                </div>
                <div class="nx-bubble__top">
                    <img class="nx-avatar nx-avatar--img" src="{{ $storyPhotos[$i % 3] }}" alt="" width="44" height="44">
                    <div>
                        <strong>{{ $story->name ?? 'Customer' }}</strong>
                        <div style="font-size:12px;color:var(--nx-muted)">{{ $story->position ?? 'Member' }}</div>
                    </div>
                </div>
                <div class="nx-stars" aria-label="5 stars">★★★★★</div>
                <p>{{ $story->what_is_said ?? 'A smoother way to bank every day.' }}</p>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Mobile Banking --}}
<section class="nx-section nx-mobile-bank" id="mobile">
    <div class="nx-container">
        <div class="nx-mobile-bank__wrap" data-nx-mobile-bank>
            <div class="nx-reveal">
                <span class="nx-eyebrow">Mobile banking</span>
                <h2 class="nx-title">Your bank in your hand</h2>
                <div class="nx-mb-steps">
                    @foreach(['Dashboard','Transfer','Cards','Savings','Investments','Notifications'] as $i => $label)
                        <button type="button" class="nx-mb-step {{ $i === 0 ? 'is-active' : '' }}" data-mb-step="{{ $i }}">{{ $label }}</button>
                    @endforeach
                </div>
            </div>
            <div class="nx-phone-scroll" data-mb-phone>
                <div class="nx-phone-scroll__screen">
                    @foreach([
                        ['Dashboard', '$48,200', 'Overview of balances'],
                        ['Transfer', 'Send money', 'To any bank · Instant'],
                        ['Cards', 'Ruby Card', '•••• 8842 · Active'],
                        ['Savings', 'Vaults', '$5,470 locked'],
                        ['Investments', '3 plans', '$31,040 growing'],
                        ['Alerts', 'All caught up', '2 new this week'],
                    ] as $i => $frame)
                    <div class="nx-phone-frame {{ $i === 0 ? 'is-active' : '' }}" data-mb-frame="{{ $i }}">
                        <div class="nx-phone__label">{{ $frame[0] }}</div>
                        <div class="nx-phone__balance">{{ $frame[1] }}</div>
                        <p style="font-size:13px;color:var(--nx-muted)">{{ $frame[2] }}</p>
                        <div class="nx-chart-bars" style="height:90px;margin-top:28px"><i></i><i></i><i></i><i></i><i></i></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Final CTA --}}
<section class="nx-cta" id="cta">
    <div class="nx-cta__lines" aria-hidden="true"></div>
    <div class="nx-container nx-reveal">
        <div class="nx-cta__faces" aria-hidden="true">
            <img src="{{ $nxImg }}/person-1.png" alt="" width="56" height="56" loading="lazy">
            <img src="{{ $nxImg }}/person-2.png" alt="" width="56" height="56" loading="lazy">
            <img src="{{ $nxImg }}/person-3.png" alt="" width="56" height="56" loading="lazy">
        </div>
        <h2>Ready to Bank Smarter?</h2>
        <p>Open your account in minutes.</p>
        <a href="{{ route('register') }}" class="nx-btn nx-btn--primary nx-btn--pulse nx-btn--magnetic">
            <span>Open Account</span>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
    </div>
</section>

@endsection
