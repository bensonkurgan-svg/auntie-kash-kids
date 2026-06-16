@extends('layouts.app')
@section('title', $course->title)
@section('content')
<section class="hero-bg" style="padding:50px 0;">
    <div class="container">
        <a href="{{ route('courses') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Programs</a>
        <div class="flex items-center gap-4 mt-4">
            <div style="font-size:56px;">{{ $course->icon }}</div>
            <div>
                <h1 style="font-size:clamp(28px,4vw,42px);color:var(--navy);">{{ $course->title }}</h1>
                <p style="color:var(--muted);">with {{ $course->tutorProfile->user->name ?? 'Auntie Kash Tutor' }}</p>
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid lg:grid-3 gap-6">
            <div style="grid-column:span 2;">
                <h2 style="font-size:24px;color:var(--navy);margin-bottom:16px;">About This Program</h2>
                <p style="color:#444;font-size:16px;line-height:1.8;margin-bottom:32px;">{{ $course->description }}</p>

                <h2 style="font-size:24px;color:var(--navy);margin-bottom:16px;">Curriculum</h2>
                @foreach($course->modules as $module)
                    <div class="card mb-4">
                        <h3 style="font-size:17px;color:var(--navy);margin-bottom:12px;">{{ $module->title }}</h3>
                        <ul style="list-style:none;display:flex;flex-direction:column;gap:8px;">
                            @foreach($module->lessons as $lesson)
                                <li class="flex items-center gap-3" style="font-size:14px;color:#555;">
                                    <span style="color:var(--purple);">▸</span> {{ $lesson->title }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach

                <div class="card" style="margin-top:24px;background:var(--surface);">
                    <h3 style="font-size:17px;color:var(--navy);margin-bottom:8px;">Your Tutor</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <span style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;">{{ Str::substr($course->tutorProfile->user->name ?? 'AK', 0, 2) }}</span>
                        <div>
                            <div style="font-weight:700;color:var(--navy);">{{ $course->tutorProfile->user->name ?? 'Tutor' }}</div>
                            <div style="font-size:13px;color:var(--yellow);">★ {{ $course->tutorProfile->rating ?? '5.0' }} ({{ $course->tutorProfile->review_count ?? 0 }} reviews)</div>
                        </div>
                    </div>
                    <p style="font-size:14px;color:#666;line-height:1.6;">{{ $course->tutorProfile->bio ?? '' }}</p>
                </div>
            </div>

            <div>
                <div class="card" style="position:sticky;top:90px;">
                    <div style="font-size:36px;font-weight:800;color:var(--navy);font-family:var(--font-fredoka);">${{ number_format($course->price, 2) }}</div>
                    <p style="color:var(--muted);font-size:14px;margin-bottom:20px;">per month</p>
                    @auth
                        @if(Auth::user()->isParent())
                            <a href="{{ route('discovery') }}" class="btn-pink w-full mb-2">Enroll Now</a>
                        @else
                            <a href="{{ route('discovery') }}" class="btn-pink w-full mb-2">Book Discovery Call</a>
                        @endif
                    @else
                        <a href="{{ route('signup') }}" class="btn-pink w-full mb-2">Sign Up to Enroll</a>
                    @endauth
                    <a href="{{ route('discovery') }}" class="btn-secondary w-full">Free Discovery Call</a>
                    <ul style="list-style:none;margin-top:24px;display:flex;flex-direction:column;gap:12px;font-size:14px;color:#555;">
                        <li>📚 {{ $course->modules->count() }} modules</li>
                        <li>🎓 {{ $course->lesson_count }} lessons</li>
                        <li>👥 {{ $enrollmentCount }} students enrolled</li>
                        <li>🌍 Live online classes</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
