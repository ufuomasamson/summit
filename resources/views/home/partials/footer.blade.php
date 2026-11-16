<footer class="nx-footer" id="footer">
    <div class="nx-footer__wave" aria-hidden="true">
        <canvas data-nx-footer-wave width="1200" height="120"></canvas>
    </div>

    <div class="nx-footer__inner">
        <div class="nx-footer__brand">
            <a href="{{ route('home') }}" class="nx-footer__logo-wrap" aria-label="{{ $settings->site_name }} home">
                <img src="{{ branding_url($settings->logo) }}"
                     alt="{{ $settings->site_name }}"
                     width="140"
                     height="48">
            </a>
            <p>Banking that feels premium — instant transfers, digital cards, investments, and savings in one place.</p>
            <div class="nx-footer__social">
                <a href="#" aria-label="Twitter" class="nx-social">𝕏</a>
                <a href="#" aria-label="Instagram" class="nx-social">◎</a>
                <a href="#" aria-label="LinkedIn" class="nx-social">in</a>
                <a href="#" aria-label="Facebook" class="nx-social">f</a>
            </div>
        </div>

        <div class="nx-footer__cols">
            <div>
                <h4>Product</h4>
                <ul>
                    <li><a href="{{ route('personal') }}">Personal</a></li>
                    <li><a href="{{ route('business') }}">Business</a></li>
                    <li><a href="{{ route('cards') }}">Cards</a></li>
                    <li><a href="{{ route('loans') }}">Loans</a></li>
                </ul>
            </div>
            <div>
                <h4>Company</h4>
                <ul>
                    <li><a href="{{ route('about') }}">About</a></li>
                    <li><a href="{{ route('history') }}">History</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('register') }}">Open Account</a></li>
                </ul>
            </div>
            <div>
                <h4>Legal</h4>
                <ul>
                    <li><a href="{{ route('terms') }}">Terms</a></li>
                    <li><a href="{{ route('privacy') }}">Privacy</a></li>
                    <li><a href="{{ route('login') }}">Secure Login</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="nx-footer__bottom">
        <p>&copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.</p>
        <p class="nx-footer__note">Educational banking experience. Always verify regulatory status in your region.</p>
    </div>
</footer>
