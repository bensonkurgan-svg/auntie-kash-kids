@extends('layouts.dashboard')
@section('title', 'Parent Dashboard')
@section('content')
@if(request('payment') === 'success')
<div style="background:#F0FFF4;border:1px solid #7ED321;color:#2d6a0f;padding:16px;border-radius:12px;margin-bottom:24px;font-weight:600;">🎉 Payment successful! Your enrollment is now active.</div>
@endif
<div class="mb-8">
    <h1 style="font-size:30px;color:var(--navy);">Parent Dashboard</h1>
    <p style="color:var(--muted);margin-top:4px;">Track your children's learning progress.</p>
</div>
<div class="grid md:grid-2" style="gap:16px;margin-bottom:32px;">
    <div class="stat-card"><div style="font-family:var(--font-fredoka);font-weight:700;font-size:28px;color:var(--navy);">{{ $students->count() }}</div><div style="color:var(--muted);font-size:14px;">Children</div></div>
    <div class="stat-card"><div style="font-family:var(--font-fredoka);font-weight:700;font-size:28px;color:var(--navy);">{{ $activeCourses }}</div><div style="color:var(--muted);font-size:14px;">Active Courses</div></div>
</div>
@if($students->count() > 0)
    @foreach($students as $student)
        <section class="mb-8">
            <h2 style="font-size:20px;color:var(--navy);margin-bottom:16px;">{{ $student->name }} <span style="color:var(--muted);font-size:14px;font-weight:400;">{{ $student->age ? "(Age {$student->age})" : '' }}</span></h2>
            @if($student->enrollments->count() > 0)
                <div class="grid md:grid-2">
                    @foreach($student->enrollments as $enrollment)
                        <div class="card">
                            <div class="flex items-center gap-3 mb-3"><span style="font-size:28px;">{{ $enrollment->course->icon ?? '📚' }}</span><h3 style="font-size:16px;color:var(--navy);">{{ $enrollment->course->title ?? '—' }}</h3></div>
                            <div class="flex items-center gap-2"><div class="progress-bar" style="flex:1;"><div class="progress-bar-fill" style="width:{{ $enrollment->progress }}%;"></div></div><span style="font-size:13px;font-weight:700;color:var(--purple);">{{ round($enrollment->progress) }}%</span></div>
                        </div>
                    @endforeach
                </div>
            @else<div class="card text-center" style="color:var(--muted);">No active courses. <a href="{{ route('courses') }}" style="color:var(--purple);">Browse programs →</a></div>@endif
        </section>
    @endforeach
@else
    <div class="card text-center" style="padding:48px;">
        <div style="font-size:48px;margin-bottom:16px;">🌟</div>
        <h3 style="color:var(--navy);margin-bottom:8px;">No children enrolled yet</h3>
        <p style="color:var(--muted);margin-bottom:24px;">Book a free discovery call to find the perfect program.</p>
        <a href="{{ route('discovery') }}" class="btn-pink">Book Discovery Call</a>
    </div>
@endif
@endsection
