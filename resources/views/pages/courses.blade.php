@extends('layouts.app')
@section('title', 'Programs')
@section('content')
<section class="hero-bg" style="padding:60px 0;">
    <div class="container text-center">
        <span class="badge badge-purple mb-4" style="display:inline-block;">Our Programs</span>
        <h1 style="font-size:clamp(32px,5vw,48px);color:var(--navy);margin-bottom:12px;">Learning Clubs for Every Child</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Live, interactive online classes designed to help children aged 5–16 learn, create, and shine.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid md:grid-2 lg:grid-3">
            @foreach($courses as $course)
                <a href="{{ route('courses.show', $course->slug) }}" class="card" data-reveal style="border-top:4px solid var(--pink);">
                    <div class="flex items-center justify-between mb-4">
                        <div style="font-size:44px;">{{ $course->icon }}</div>
                        <span class="badge badge-green">${{ number_format($course->price, 0) }}</span>
                    </div>
                    <h3 style="font-size:20px;color:var(--navy);margin-bottom:8px;">{{ $course->title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.6;margin-bottom:16px;">{{ Str::limit($course->description, 110) }}</p>
                    <div class="flex items-center gap-2" style="font-size:13px;color:var(--muted);">
                        <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px;">{{ Str::substr($course->tutorProfile->user->name ?? 'AK', 0, 2) }}</span>
                        {{ $course->tutorProfile->user->name ?? 'Auntie Kash Tutor' }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endsection
