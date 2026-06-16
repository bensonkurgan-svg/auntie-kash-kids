@extends('layouts.app')
@section('title', 'Home')
@section('content')

{{-- Hero --}}
<section class="hero-bg section">
    <div class="container">
        <div class="grid lg:grid-2 items-center gap-6">
            <div data-reveal>
                <span class="badge badge-purple mb-4" style="display:inline-block;">✨ Live Online Classes for Ages 5–16</span>
                <h1 style="font-size:clamp(32px,5vw,54px);color:var(--navy);margin-bottom:20px;">
                    {{ $content['hero']['headline'] }}
                </h1>
                <p style="font-size:18px;color:#555;margin-bottom:32px;max-width:520px;line-height:1.7;">
                    {{ $content['hero']['subheading'] }}
                </p>
                <div class="flex gap-4" style="flex-wrap:wrap;">
                    <a href="{{ route('discovery') }}" class="btn-pink">{{ $content['hero']['primaryCTA'] }}</a>
                    <a href="{{ route('courses') }}" class="btn-secondary">{{ $content['hero']['secondaryCTA'] }}</a>
                </div>
            </div>
            <div style="position:relative;max-width:420px;margin:0 auto;">
                <img src="{{ asset('images/mascot-hero.png') }}" alt="Auntie Kash Kids mascot" class="float" style="width:100%;">
            </div>
        </div>
    </div>
</section>

{{-- Stats strip --}}
<section style="background:linear-gradient(90deg,#7B2FF7,#FF3E9E);padding:40px 0;">
    <div class="container">
        <div class="grid md:grid-4 text-center" style="gap:24px;">
            <div><div data-counter="500" data-suffix="+" class="font-fredoka font-bold text-white" style="font-size:40px;">0</div><div class="text-white" style="opacity:0.8;font-weight:600;font-size:14px;">Happy Students</div></div>
            <div><div class="font-fredoka font-bold text-white" style="font-size:40px;">4.9★</div><div class="text-white" style="opacity:0.8;font-weight:600;font-size:14px;">Average Rating</div></div>
            <div><div data-counter="8" class="font-fredoka font-bold text-white" style="font-size:40px;">0</div><div class="text-white" style="opacity:0.8;font-weight:600;font-size:14px;">Expert Tutors</div></div>
            <div><div data-counter="12" class="font-fredoka font-bold text-white" style="font-size:40px;">0</div><div class="text-white" style="opacity:0.8;font-weight:600;font-size:14px;">Programs</div></div>
        </div>
    </div>
</section>

{{-- Programs --}}
<section class="section">
    <div class="container">
        <div class="text-center mb-8">
            <span class="badge badge-pink mb-4" style="display:inline-block;">Our Programs</span>
            <h2 style="font-size:clamp(28px,4vw,44px);color:var(--navy);">Explore Our Learning Clubs</h2>
        </div>
        <div class="grid md:grid-2 lg:grid-4">
            @foreach($featuredCourses as $course)
                <a href="{{ route('courses.show', $course->slug) }}" class="card" data-reveal style="border-top:4px solid var(--pink);">
                    <div style="font-size:40px;margin-bottom:12px;">{{ $course->icon }}</div>
                    <h3 style="font-size:18px;color:var(--navy);margin-bottom:8px;">{{ $course->title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.6;margin-bottom:12px;">{{ Str::limit($course->description, 90) }}</p>
                    <span style="color:var(--purple);font-weight:700;font-size:14px;">Learn more →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- About preview --}}
<section class="section" style="background:white;">
    <div class="container">
        <div class="grid lg:grid-2 items-center gap-6">
            <div style="max-width:380px;margin:0 auto;">
                <img src="{{ asset('images/mascot-1.png') }}" alt="mascot" class="float" style="width:100%;">
            </div>
            <div data-reveal>
                <span class="badge badge-purple mb-4" style="display:inline-block;">About Us</span>
                <h2 style="font-size:clamp(28px,4vw,40px);color:var(--navy);margin-bottom:20px;">About Auntie Kash Kids</h2>
                <p style="font-size:17px;color:#444;margin-bottom:24px;line-height:1.7;">{{ $content['aboutPreview']['blurb'] }}</p>
                <ul style="list-style:none;display:flex;flex-direction:column;gap:12px;margin-bottom:32px;">
                    @foreach(['Live, interactive online classes','African heritage & cultural celebration','Expert, vetted tutors','Ages 5–16, all skill levels'] as $item)
                        <li class="flex items-center gap-3" style="font-weight:600;color:var(--navy);font-size:15px;">
                            <span style="width:24px;height:24px;border-radius:50%;background:var(--green);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">✓</span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('about') }}" class="btn-purple">Learn More About Us</a>
            </div>
        </div>
    </div>
</section>

{{-- Testimonials --}}
<section class="section" style="background:#F9F0FF;">
    <div class="container">
        <h2 class="text-center mb-8" style="font-size:clamp(28px,4vw,44px);color:var(--navy);">What Parents Are Saying</h2>
        <div class="grid md:grid-3">
            @foreach([
                ['My daughter has transformed into a confident reader! The classes are engaging, fun, and rooted in our culture.','Adaeze W.'],
                ['Emeka now volunteers to speak in class at school. The confidence boost has been incredible!','Chidi O.'],
                ['The cultural element really resonates with our family. Our kids are learning and celebrating their heritage.','Fatima B.'],
            ] as [$quote, $name])
                <div class="card" data-reveal style="border-radius:24px;">
                    <div style="color:var(--yellow);font-size:20px;margin-bottom:16px;">★★★★★</div>
                    <blockquote style="font-style:italic;color:#444;font-size:15px;line-height:1.7;margin-bottom:20px;">"{{ $quote }}"</blockquote>
                    <footer style="font-weight:700;color:var(--purple);font-size:14px;">— {{ $name }}</footer>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="section" style="background:linear-gradient(135deg,#7B2FF7,#FF3E9E);">
    <div class="container-narrow text-center">
        <h2 class="text-white mb-4" style="font-size:clamp(28px,4vw,48px);">Ready to Start Learning?</h2>
        <p class="text-white mb-8" style="opacity:0.85;font-size:18px;">Book a free discovery call today. No commitment required.</p>
        <a href="{{ route('discovery') }}" class="btn-white">📅 Book Your Free Discovery Call</a>
    </div>
</section>

@endsection
