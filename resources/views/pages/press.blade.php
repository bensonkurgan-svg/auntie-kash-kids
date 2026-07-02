@extends('layouts.app')
@section('title', 'Media & Press')
@section('content')
<section class="hero-bg" style="padding:56px 0;">
    <div class="container text-center">
        <span class="welcome-pill">MEDIA & PRESS</span>
        <h1 style="font-size:clamp(30px,4vw,48px);color:var(--navy);margin:12px 0;">In the Spotlight</h1>
        <p style="color:#555;font-size:18px;max-width:620px;margin:0 auto;">Interviews, features, and news from the Auntie Kash Kids journey.</p>
    </div>
</section>

<section class="section">
    <div class="container-narrow">
        <div class="card text-center" style="padding:48px;">
            <div style="font-size:52px;margin-bottom:16px;">📰</div>
            <h2 style="font-size:26px;color:var(--navy);margin-bottom:12px;">Press & Media Coverage Coming Soon</h2>
            <p style="color:#666;line-height:1.8;max-width:520px;margin:0 auto 24px;">As our community grows, this is where we'll share interviews, podcast appearances, articles, speaking engagements, and brand announcements. Watch this space!</p>
            <div style="text-align:left;max-width:480px;margin:0 auto;">
                <h4 style="color:var(--navy);margin-bottom:12px;">For media enquiries, we'd love to hear from you:</h4>
                <ul style="list-style:none;line-height:2.2;color:#555;">
                    <li>🎙️ Interviews & podcasts</li>
                    <li>📰 Press coverage & articles</li>
                    <li>🎤 Speaking engagements</li>
                    <li>📢 News & brand updates</li>
                </ul>
            </div>
            <a href="{{ route('contact') }}" class="btn-pink" style="margin-top:24px;">Media Enquiries</a>
        </div>
    </div>
</section>
@endsection
