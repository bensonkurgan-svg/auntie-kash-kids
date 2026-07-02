<footer class="footer">
    <div class="container">
        <div class="grid md:grid-4" style="margin-bottom:40px;">
            <div style="grid-column:span 2;">
                <img src="{{ asset('images/round-logo.png') }}" alt="Auntie Kash Kids" style="width:88px;height:88px;border-radius:50%;margin-bottom:16px;box-shadow:0 8px 20px rgba(0,0,0,0.25);">
                <p style="color:rgba(255,255,255,0.6);font-size:14px;max-width:320px;line-height:1.7;">
                    A global children's learning platform celebrating African heritage while delivering world-class enrichment education for children aged 5–16.
                </p>
            </div>
            <div>
                <h3 style="font-weight:700;margin-bottom:16px;color:rgba(255,255,255,0.9);">Quick Links</h3>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;font-size:14px;">
                    <li><a href="{{ route('courses') }}">Programs</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('meet.auntie.kash') }}">Meet Auntie Kash</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('anthem') }}">Our Anthem</a></li>
                    <li><a href="{{ route('blog') }}">Blog</a></li>
                    <li><a href="{{ route('shop') }}">Shop</a></li>
                    <li><a href="{{ route('parent.resources') }}">Parent Resources</a></li>
                    <li><a href="{{ route('reading.library') }}">Reading Library</a></li>
                </ul>
            </div>
            <div>
                <h3 style="font-weight:700;margin-bottom:16px;color:rgba(255,255,255,0.9);">Get Involved</h3>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;font-size:14px;">
                    <li><a href="{{ route('become.instructor') }}">Become an Instructor</a></li>
                    <li><a href="{{ route('careers') }}">Careers</a></li>
                    <li><a href="{{ route('waitlist') }}">Waitlist Corner</a></li>
                    <li><a href="{{ route('press') }}">Media & Press</a></li>
                </ul>
            </div>
            <div>
                <h3 style="font-weight:700;margin-bottom:16px;color:rgba(255,255,255,0.9);">Legal</h3>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;font-size:14px;">
                    <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                    <li><a href="{{ route('terms') }}">Terms & Conditions</a></li>
                    <li><a href="{{ route('cookies') }}">Cookie Policy</a></li>
                    <li><a href="{{ route('data-export') }}">Data Export</a></li>
                    <li><a href="{{ route('data-delete') }}">Delete My Data</a></li>
                </ul>
            </div>
        </div>
        <div style="border-top:1px solid rgba(255,255,255,0.1);padding-top:32px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <p style="color:rgba(255,255,255,0.5);font-size:14px;">© 2026 Auntie Kash Kids Academy. All rights reserved.</p>
            <a href="mailto:hello@auntiekashkids.com" style="color:rgba(255,255,255,0.5);font-size:14px;">hello@auntiekashkids.com</a>
        </div>
    </div>
</footer>
