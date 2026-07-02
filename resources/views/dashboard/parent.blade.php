@extends('layouts.dashboard')
@section('title', 'Parent Dashboard')
@section('content')
@if(request('payment') === 'success')
<div style="background:#F0FFF4;border:1px solid #7ED321;color:#2d6a0f;padding:16px;border-radius:12px;margin-bottom:24px;font-weight:600;">🎉 Payment successful! Your enrolment is now active.</div>
@endif

<div class="dash-header">
    <div><h1 class="dash-title">Welcome back! 👋</h1><p class="dash-sub">Track your children's learning and continue where they left off.</p></div>
    <a href="{{ route('courses') }}" class="btn-primary" style="min-height:42px;">➕ Enroll in a Program</a>
</div>

<div class="kpi-grid">
    <div class="kpi" style="--kpi-accent:#2AA7FF;--kpi-bg:#E8F4FF;"><div class="kpi-icon">👨‍👩‍👧</div><div class="kpi-value">{{ $students->count() }}</div><div class="kpi-label">Children</div></div>
    <div class="kpi" style="--kpi-accent:#7ED321;--kpi-bg:#EFFAEA;"><div class="kpi-icon">📚</div><div class="kpi-value">{{ $activeCourses }}</div><div class="kpi-label">Active Programs</div></div>
</div>

{{-- Upcoming classes with join links --}}
@if(isset($upcomingSessions) && $upcomingSessions->count())
<div class="panel" style="border:1.5px solid #B8E0FF;">
    <div class="panel-head" style="background:linear-gradient(90deg,#F0F8FF,#F8FCFF);">
        <div class="panel-title">🎥 Upcoming Classes</div>
    </div>
    <div class="panel-body padded">
        @foreach($upcomingSessions as $s)
            <div class="flex items-center justify-between" style="padding:12px;border-radius:10px;background:var(--surface);margin-bottom:8px;flex-wrap:wrap;gap:10px;">
                <div>
                    <div style="font-weight:700;color:var(--navy);">{{ $s->course->icon ?? '' }} {{ $s->course->title ?? 'Class' }}</div>
                    <div class="dash-sub" style="font-size:13px;">{{ $s->student->name ?? '' }} · {{ $s->scheduled_at?->format('D, M j · g:i a') }}{{ $s->tutor ? ' · '.$s->tutor->name : '' }}</div>
                </div>
                @if($s->meeting_link)
                    <a href="{{ $s->meeting_link }}" target="_blank" class="btn-pink" style="min-height:40px;">{{ $s->meeting_platform=='ZOOM'?'🎥 Join Zoom':'🎥 Join Meet' }}</a>
                @else
                    <span class="status-pill st-PENDING">Link coming soon</span>
                @endif
            </div>
        @endforeach
    </div>
</div>
@endif

@forelse($students as $student)
<div class="panel">
    <div class="panel-head">
        <div class="panel-title">{{ $student->name }} <span class="dash-sub">{{ $student->age ? "· Age {$student->age}" : '' }}</span></div>
        <button type="button" onclick="var r=document.getElementById('child-edit-{{ $student->id }}');r.style.display=(r.style.display==='block'?'none':'block')" class="status-pill st-CONTACTED" style="border:none;cursor:pointer;">✏️ Details & Emergency Contact</button>
    </div>
    {{-- Child details / emergency contact form --}}
    <div id="child-edit-{{ $student->id }}" style="display:none;padding:18px 22px;background:#FAFAFE;border-bottom:1px solid var(--border);">
        <form method="POST" action="{{ route('students.update', $student) }}">@csrf @method('PUT')
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Name</label><input type="text" name="name" value="{{ $student->name }}" class="input" required></div>
                <div><label class="label">Age</label><input type="number" name="age" value="{{ $student->age }}" class="input"></div>
            </div>
            <div class="mb-3"><label class="label">Grade / Year Level</label><input type="text" name="grade_level" value="{{ $student->grade_level }}" class="input" placeholder="e.g. Year 4"></div>
            <div style="padding:14px;background:#FFF4F4;border-radius:10px;border-left:4px solid #FF3E9E;margin-bottom:12px;">
                <div class="label" style="color:#c0392b;">🚨 Emergency Contact</div>
                <div class="grid md:grid-3 gap-3">
                    <div><label class="label">Name</label><input type="text" name="emergency_contact_name" value="{{ $student->emergency_contact_name }}" class="input"></div>
                    <div><label class="label">Phone</label><input type="text" name="emergency_contact_phone" value="{{ $student->emergency_contact_phone }}" class="input"></div>
                    <div><label class="label">Relationship</label><input type="text" name="emergency_contact_relationship" value="{{ $student->emergency_contact_relationship }}" class="input" placeholder="e.g. Aunt"></div>
                </div>
            </div>
            <div class="mb-3"><label class="label">Medical Notes / Allergies (optional)</label><textarea name="medical_notes" class="input" rows="2">{{ $student->medical_notes }}</textarea></div>
            <button class="btn-primary" style="min-height:42px;">Save Details</button>
        </form>
    </div>
    <div class="panel-body padded">
        @if($student->enrollments->count())
            <div class="grid md:grid-2 gap-4">
                @foreach($student->enrollments as $enrollment)
                    <div class="card" style="margin:0;">
                        <div class="flex items-center gap-3 mb-3">
                            <span style="font-size:28px;">{{ $enrollment->course->icon ?? '📚' }}</span>
                            <h3 style="font-size:16px;color:var(--navy);">{{ $enrollment->course->title ?? '—' }}</h3>
                        </div>
                        <div class="prog-wrap mb-4"><span class="mini-prog" style="flex:1;"><span style="width:{{ $enrollment->progress }}%;"></span></span><span class="prog-num">{{ round($enrollment->progress) }}%</span></div>
                        <a href="{{ route('learn', $enrollment->id) }}" class="btn-pink w-full" style="min-height:44px;">
                            {{ $enrollment->progress > 0 ? '▶ Continue Learning' : '▶ Start Learning' }}
                        </a>
                        @if($enrollment->course && $enrollment->course->meeting_link)
                            <a href="{{ $enrollment->course->meeting_link }}" target="_blank" class="btn-secondary w-full" style="min-height:42px;margin-top:8px;">
                                🎥 Join Live Class{{ $enrollment->course->meeting_schedule ? ' · '.$enrollment->course->meeting_schedule : '' }}
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center" style="padding:24px;color:var(--muted);">No active programs for {{ $student->name }}. <a href="{{ route('courses') }}" style="color:var(--purple);font-weight:600;">Browse programs →</a></div>
        @endif
    </div>
</div>
@empty
<div class="panel">
    <div class="panel-body padded text-center" style="padding:48px;">
        <div style="font-size:48px;margin-bottom:16px;">🌟</div>
        <h3 style="color:var(--navy);margin-bottom:8px;">No children enrolled yet</h3>
        <p style="color:var(--muted);margin-bottom:24px;">Browse our programs and enroll your child — no discovery call needed!</p>
        <a href="{{ route('courses') }}" class="btn-pink">Browse Programs</a>
    </div>
</div>
@endforelse
@endsection
