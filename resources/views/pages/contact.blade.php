@extends('layouts.app')
@section('title', 'Contact')
@section('content')
<section class="hero-bg" style="padding:60px 0;">
    <div class="container text-center">
        <h1 style="font-size:clamp(32px,5vw,48px);color:var(--navy);margin-bottom:12px;">Get in Touch</h1>
        <p style="color:#555;font-size:18px;">We'd love to hear from you!</p>
    </div>
</section>
<section class="section">
    <div class="container" style="max-width:560px;">
        <div class="card" style="padding:40px;">
            <form method="POST" action="{{ route('discovery') }}" onsubmit="alert('Thank you! For program enquiries, please use our Discovery form. Redirecting...'); window.location='{{ route('discovery') }}'; return false;">
                <div class="mb-4"><label class="label">Your Name</label><input type="text" class="input" required></div>
                <div class="mb-4"><label class="label">Email</label><input type="email" class="input" required></div>
                <div class="mb-4"><label class="label">Message</label><textarea class="input" rows="5" required style="resize:vertical;"></textarea></div>
                <button type="submit" class="btn-primary w-full">Send Message</button>
            </form>
            <div class="text-center mt-8" style="padding-top:24px;border-top:1px solid var(--border);">
                <p style="color:var(--muted);font-size:14px;">Or email us directly at</p>
                <a href="mailto:hello@auntiekashkids.com" style="color:var(--purple);font-weight:700;">hello@auntiekashkids.com</a>
            </div>
        </div>
    </div>
</section>
@endsection
