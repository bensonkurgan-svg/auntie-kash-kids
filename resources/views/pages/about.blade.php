@extends('layouts.app')
@section('title', 'About Us')
@section('content')
<section class="section text-center" style="background:linear-gradient(135deg,#F9F0FF,#FFF0FB,#F0FFF4);">
    <div class="container-narrow">
        <div style="width:140px;height:140px;margin:0 auto 24px;"><img src="{{ asset('images/mascot-hero.png') }}" class="float" alt="mascot"></div>
        <span class="badge badge-purple mb-4" style="display:inline-block;">About Us</span>
        <h1 style="font-size:clamp(30px,5vw,52px);color:var(--navy);margin-bottom:16px;">{{ $about['headline'] }}</h1>
        <p style="font-size:clamp(18px,2.5vw,26px);color:var(--purple);font-weight:700;font-family:var(--font-fredoka);margin-bottom:24px;">{{ $about['tagline'] }}</p>
        <p style="color:#444;font-size:17px;max-width:680px;margin:0 auto;line-height:1.8;">{{ $about['intro'] }}</p>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="grid lg:grid-2 items-center gap-6">
            <div data-reveal>
                <span class="badge badge-pink mb-4" style="display:inline-block;">Our Mission</span>
                <h2 style="font-size:clamp(26px,3.5vw,40px);color:var(--navy);margin-bottom:20px;">Every Child Is Gifted.<br>Every Child Can Shine.</h2>
                <p style="color:#444;font-size:16px;line-height:1.8;margin-bottom:24px;">{{ $about['mission'] }}</p>
                <a href="{{ route('discovery') }}" class="btn-pink">Book a Free Discovery Call</a>
            </div>
            <div style="max-width:380px;margin:0 auto;"><img src="{{ asset('images/mascot-1.png') }}" class="float" alt="mascot"></div>
        </div>
    </div>
</section>

<section class="section" style="background:#F9F0FF;">
    <div class="container">
        <div class="text-center mb-8">
            <span class="badge badge-blue mb-4" style="display:inline-block;">Our Academy</span>
            <h2 style="font-size:clamp(26px,3.5vw,40px);color:var(--navy);">What We Teach</h2>
        </div>
        <div class="grid md:grid-3 lg:grid-4" style="gap:16px;">
            @foreach($about['programFocus'] as $prog)
                <div class="card flex items-center gap-3" data-reveal style="padding:16px;">
                    <span style="font-size:24px;flex-shrink:0;">{{ $prog['icon'] }}</span>
                    <span style="font-weight:600;font-size:14px;color:var(--navy);line-height:1.3;">{{ $prog['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="section" style="background:linear-gradient(135deg,#1D1B4E,#3B1F8C);">
    <div class="container">
        <div class="grid lg:grid-2 items-center gap-6">
            <div style="max-width:360px;margin:0 auto;position:relative;">
                <div style="aspect-ratio:1;border-radius:24px;overflow:hidden;border:3px solid rgba(255,62,158,0.4);"><img src="{{ asset('images/mascot-hero.png') }}" alt="{{ $about['founderName'] }}" style="width:100%;height:100%;object-fit:cover;object-position:top;"></div>
            </div>
            <div data-reveal>
                <span class="badge badge-pink mb-4" style="display:inline-block;">Meet the Founder</span>
                <h2 class="text-white" style="font-size:clamp(26px,3.5vw,40px);margin-bottom:8px;">{{ $about['founderName'] }}</h2>
                <p style="color:var(--pink);font-weight:600;font-size:15px;margin-bottom:24px;">{{ $about['founderTitle'] }}</p>
                <p style="color:rgba(255,255,255,0.8);font-size:15px;line-height:1.8;margin-bottom:24px;">{{ $about['founderBio'] }}</p>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($about['qualifications'] as $q)
                        <div class="flex items-center gap-3">
                            <span style="width:20px;height:20px;border-radius:50%;background:var(--pink);color:white;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">✓</span>
                            <span style="color:rgba(255,255,255,0.8);font-size:14px;">{{ $q }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" style="background:linear-gradient(135deg,#FFF0FB,#F0E8FF);">
    <div class="container-narrow text-center">
        <span class="badge badge-purple mb-4" style="display:inline-block;">Our Promise</span>
        <h2 style="font-size:clamp(26px,3.5vw,40px);color:var(--navy);margin-bottom:40px;">At Auntie Kash Kids, We Promise To:</h2>
        <div class="grid md:grid-2 lg:grid-3">
            @foreach($about['promises'] as $promise)
                <div class="card flex items-center gap-4" data-reveal style="border-radius:24px;text-align:left;">
                    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#FF3E9E,#7B2FF7);display:flex;align-items:center;justify-content:center;flex-shrink:0;">🌟</div>
                    <span style="font-weight:600;color:var(--navy);font-size:15px;">{{ $promise }}</span>
                </div>
            @endforeach
        </div>
    </div>
</section>

@if($tutors->count() > 0)
<section class="section">
    <div class="container">
        <h2 class="text-center mb-8" style="font-size:clamp(26px,3.5vw,40px);color:var(--navy);">Meet Our Tutors</h2>
        <div class="grid md:grid-2 lg:grid-3">
            @foreach($tutors as $t)
                <div class="card text-center" data-reveal style="border-radius:24px;">
                    <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:22px;margin:0 auto 16px;">{{ Str::substr($t->user->name, 0, 2) }}</div>
                    <h3 style="font-size:18px;color:var(--navy);margin-bottom:4px;">{{ $t->user->name }}</h3>
                    @if(count($t->specialties ?? []) > 0)<p style="font-size:13px;color:var(--purple);margin-bottom:12px;">{{ implode(' · ', array_slice($t->specialties, 0, 2)) }}</p>@endif
                    @if($t->rating > 0)<div style="font-size:14px;margin-bottom:12px;"><span style="color:var(--yellow);">★</span> <strong>{{ $t->rating }}</strong> <span style="color:#999;font-size:12px;">({{ $t->review_count }})</span></div>@endif
                    @if($t->bio)<p style="font-size:14px;color:#666;line-height:1.6;">{{ Str::limit($t->bio, 120) }}</p>@endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section" style="background:linear-gradient(135deg,#7B2FF7,#FF3E9E);">
    <div class="container-narrow text-center">
        <div style="font-size:40px;margin-bottom:16px;">🌈</div>
        <h2 class="text-white mb-4" style="font-size:clamp(26px,4vw,44px);">Ready to Join the Family?</h2>
        <p class="text-white mb-8" style="opacity:0.85;font-size:18px;">Book a free discovery call. Let us match the perfect program for your child.</p>
        <a href="{{ route('discovery') }}" class="btn-white">📅 Book Free Discovery Call</a>
    </div>
</section>
@endsection
