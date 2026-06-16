@extends('layouts.dashboard')
@section('title', 'Tutor Dashboard')
@section('content')
<div class="mb-8">
    <h1 style="font-size:30px;color:var(--navy);">Tutor Dashboard</h1>
    <p style="color:var(--muted);margin-top:4px;">Welcome back, {{ Auth::user()->name }}!</p>
</div>
<div class="grid md:grid-3" style="gap:16px;margin-bottom:32px;">
    <div class="stat-card"><div class="stat-icon" style="background:#2AA7FF22;">📚</div><div style="font-family:var(--font-fredoka);font-weight:700;font-size:28px;color:var(--navy);">{{ $profile->courses->count() ?? 0 }}</div><div style="color:var(--muted);font-size:14px;">My Courses</div></div>
    <div class="stat-card"><div class="stat-icon" style="background:#7ED32122;">👥</div><div style="font-family:var(--font-fredoka);font-weight:700;font-size:28px;color:var(--navy);">{{ $totalStudents }}</div><div style="color:var(--muted);font-size:14px;">Total Students</div></div>
    <div class="stat-card"><div class="stat-icon" style="background:#FFD93D33;">⭐</div><div style="font-family:var(--font-fredoka);font-weight:700;font-size:28px;color:var(--navy);">{{ $profile->rating ?? '—' }}★</div><div style="color:var(--muted);font-size:14px;">Average Rating</div></div>
</div>
@if($profile && $profile->courses->count() > 0)
<section style="margin-bottom:32px;">
    <h2 style="font-size:20px;color:var(--navy);margin-bottom:16px;">My Courses</h2>
    <div class="grid md:grid-2">
        @foreach($profile->courses as $course)
            <div class="card"><div class="flex items-center gap-3 mb-2"><span style="font-size:32px;">{{ $course->icon }}</span><h3 style="font-size:17px;color:var(--navy);">{{ $course->title }}</h3></div><p style="color:var(--muted);font-size:14px;">{{ $course->enrollments->count() }} students enrolled</p></div>
        @endforeach
    </div>
</section>
@endif
@if($profile && $profile->reviews->count() > 0)
<section>
    <h2 style="font-size:20px;color:var(--navy);margin-bottom:16px;">Recent Reviews</h2>
    @foreach($profile->reviews as $review)
        <div class="card mb-4"><div style="color:var(--yellow);margin-bottom:8px;">{{ str_repeat('★', $review->rating) }}</div><p style="color:#444;font-style:italic;margin-bottom:8px;">"{{ $review->comment }}"</p><p style="font-size:13px;color:var(--muted);">— {{ $review->user->name ?? 'Parent' }}</p></div>
    @endforeach
</section>
@endif
@endsection
