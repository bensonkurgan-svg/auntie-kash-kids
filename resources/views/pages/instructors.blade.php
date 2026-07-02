@extends('layouts.app')
@section('title', 'Instructors')
@section('content')
<section class="hero-bg" style="padding:60px 0;">
    <div class="container text-center">
        <span class="welcome-pill">OUR TEAM</span>
        <h1 style="font-size:clamp(32px,5vw,48px);color:var(--navy);margin:12px 0;">Meet Our Amazing Instructors</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Passionate, vetted educators who bring learning to life for every child.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid md:grid-2 lg:grid-3">
            @forelse($tutors as $t)
                <div class="card text-center" data-reveal style="border-radius:24px;border-top:4px solid var(--purple);">
                    @if($t->photo_url)
                        <img src="{{ $t->photo_url }}" alt="{{ $t->user->name ?? 'Instructor' }}" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 16px;border:3px solid var(--purple);">
                    @else
                        <div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:28px;margin:0 auto 16px;">{{ \Illuminate\Support\Str::substr($t->user->name ?? 'AK', 0, 2) }}</div>
                    @endif
                    <h3 style="font-size:20px;color:var(--navy);margin-bottom:4px;">{{ $t->user->name ?? 'Instructor' }}</h3>
                    @if(count($t->specialties ?? []) > 0)
                        <p style="font-size:13px;color:var(--purple);font-weight:700;margin-bottom:12px;">{{ implode(' · ', array_slice($t->specialties, 0, 3)) }}</p>
                    @endif
                    @if($t->rating > 0)
                        <div style="font-size:14px;margin-bottom:12px;"><span style="color:var(--yellow);">★</span> <strong>{{ $t->rating }}</strong> <span style="color:#999;font-size:12px;">({{ $t->review_count }} reviews)</span></div>
                    @endif
                    @if($t->bio)<p style="font-size:14px;color:#666;line-height:1.6;">{{ \Illuminate\Support\Str::limit($t->bio, 140) }}</p>@endif
                    @if($t->work_email || $t->phone)
                        <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--border);font-size:13px;color:var(--muted);">
                            @if($t->work_email)<div>✉️ {{ $t->work_email }}</div>@endif
                            @if($t->phone)<div style="margin-top:4px;">📞 {{ $t->phone }}</div>@endif
                        </div>
                    @endif
                </div>
            @empty
                <p class="text-center text-muted" style="grid-column:1/-1;">Our instructor profiles are coming soon!</p>
            @endforelse
        </div>
    </div>
</section>
<section class="section" style="background:linear-gradient(135deg,#7B2FF7,#FF3E9E);">
    <div class="container-narrow text-center">
        <h2 class="text-white mb-4" style="font-size:clamp(26px,4vw,40px);">Ready to Learn with Us?</h2>
        <a href="{{ route('discovery') }}" class="btn-white">✨ Book a Free Trial Class</a>
    </div>
</section>
@endsection
