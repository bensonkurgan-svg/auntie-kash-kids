@extends('layouts.app')
@section('title', 'Meet Auntie Kash')
@section('content')
<section class="hero-bg" style="padding:56px 0;">
    <div class="container">
        <div class="grid md:grid-2 gap-8 items-center">
            <div>
                <span class="welcome-pill">MEET</span>
                <h1 style="font-size:clamp(32px,5vw,52px);color:var(--navy);margin:12px 0;">Meet Auntie Kash</h1>
                <p style="color:#555;font-size:19px;line-height:1.8;">The heart, warmth, and inspiration behind Auntie Kash Kids — here to help every child learn, create, sing, speak, and shine. ✨</p>
            </div>
            <div class="text-center">
                <img src="{{ asset('images/mascot-hero.png') }}" alt="Auntie Kash" style="max-width:340px;width:100%;filter:drop-shadow(0 16px 30px rgba(80,60,160,0.22));">
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container-narrow" style="font-size:17px;line-height:1.9;color:#444;">
        <h2 style="font-size:28px;color:var(--navy);margin-bottom:16px;">A Warm Welcome 🌈</h2>
        <p style="margin-bottom:20px;">Auntie Kash is more than a name — she's a feeling. A warm, encouraging presence who believes that every child carries something special inside them, waiting to shine. With a big heart and an even bigger imagination, Auntie Kash created this academy to give children everywhere a place to grow in confidence, creativity, and joy.</p>

        <h2 style="font-size:24px;color:var(--navy);margin:28px 0 14px;">Her Mission</h2>
        <p style="margin-bottom:20px;">Auntie Kash believes learning should never feel like a chore. It should sparkle. It should feel like singing your favourite song, telling a story that makes everyone lean in, or standing a little taller because you just did something brave. Through live, interactive classes, she helps children find their voice, celebrate their culture, and discover just how capable they truly are.</p>

        <h2 style="font-size:24px;color:var(--navy);margin:28px 0 14px;">What She Stands For</h2>
        <div class="grid md:grid-2 gap-4" style="margin-bottom:20px;">
            <div class="card" style="margin:0;"><strong style="color:var(--pink);">💛 Confidence</strong><p style="font-size:15px;color:#666;margin-top:6px;">Every child deserves to believe in themselves.</p></div>
            <div class="card" style="margin:0;"><strong style="color:var(--purple);">🎨 Creativity</strong><p style="font-size:15px;color:#666;margin-top:6px;">Imagination is a superpower worth nurturing.</p></div>
            <div class="card" style="margin:0;"><strong style="color:var(--blue);">🌍 Culture</strong><p style="font-size:15px;color:#666;margin-top:6px;">Celebrating heritage and global citizenship.</p></div>
            <div class="card" style="margin:0;"><strong style="color:var(--green);">🗣️ Communication</strong><p style="font-size:15px;color:#666;margin-top:6px;">Helping every young voice be heard.</p></div>
        </div>

        <div class="text-center" style="margin-top:36px;padding:36px;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);border-radius:24px;">
            <h3 class="text-white" style="font-size:24px;margin-bottom:8px;">Come learn with Auntie Kash!</h3>
            <p style="color:rgba(255,255,255,0.9);margin-bottom:20px;">Where young minds learn, create, sing, speak & shine.</p>
            <a href="{{ route('discovery') }}" class="btn-white">✨ Book a Free Trial Class</a>
        </div>
    </div>
</section>
@endsection
