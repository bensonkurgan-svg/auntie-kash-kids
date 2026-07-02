@extends('layouts.dashboard')
@section('title', $student->name)
@section('content')
<div class="dash-header">
    <div>
        <a href="{{ route('directory.students') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← All Students</a>
        <h1 class="dash-title" style="margin-top:6px;">{{ $student->name }}</h1>
        <p class="dash-sub">{{ $student->age ? "Age {$student->age}" : '' }}{{ $student->grade_level ? " · {$student->grade_level}" : '' }}</p>
    </div>
</div>

<div class="grid md:grid-2 gap-6">
    {{-- Parent & emergency contacts --}}
    <div class="panel">
        <div class="panel-head"><div class="panel-title">👨‍👩‍👧 Parent & Emergency Contacts</div></div>
        <div class="panel-body padded">
            <div style="margin-bottom:18px;">
                <div class="label">Parent / Guardian</div>
                <div style="font-weight:700;color:var(--navy);">{{ $student->parent->name ?? '—' }}</div>
                <div style="font-size:14px;color:#555;">{{ $student->parent->email ?? '' }}</div>
                @if($student->parent && $student->parent->phone)<div style="font-size:14px;color:#555;">📞 {{ $student->parent->phone }}</div>@endif
            </div>
            <div style="padding:14px;background:#FFF4F4;border-radius:12px;border-left:4px solid #FF3E9E;">
                <div class="label" style="color:#c0392b;">🚨 Emergency Contact</div>
                @if($student->emergency_contact_name)
                    <div style="font-weight:700;color:var(--navy);">{{ $student->emergency_contact_name }}{{ $student->emergency_contact_relationship ? " ({$student->emergency_contact_relationship})" : '' }}</div>
                    <div style="font-size:15px;color:#555;">📞 {{ $student->emergency_contact_phone ?: 'No phone on file' }}</div>
                @else
                    <div style="font-size:14px;color:var(--muted);">No emergency contact on file yet.</div>
                @endif
            </div>
            @if($student->medical_notes)
                <div style="margin-top:14px;padding:12px;background:var(--surface);border-radius:10px;">
                    <div class="label">Medical / Notes</div>
                    <div style="font-size:14px;color:#555;">{{ $student->medical_notes }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- Enrollment & progress --}}
    <div class="panel">
        <div class="panel-head"><div class="panel-title">📚 Programs & Progress</div></div>
        <div class="panel-body padded">
            <div style="margin-bottom:14px;font-size:14px;"><strong>Assigned Instructor:</strong> {{ $student->assignedTutor->user->name ?? 'Not assigned' }}</div>
            @forelse($student->enrollments as $e)
                <div style="margin-bottom:14px;">
                    <div class="flex items-center justify-between mb-1">
                        <span style="font-weight:700;color:var(--navy);font-size:14px;">{{ $e->course->icon ?? '' }} {{ $e->course->title ?? '—' }}</span>
                        <span class="status-pill st-{{ $e->status }}">{{ $e->status }}</span>
                    </div>
                    <div class="prog-wrap"><span class="mini-prog" style="flex:1;"><span style="width:{{ $e->progress }}%;"></span></span><span class="prog-num">{{ round($e->progress) }}%</span></div>
                    @if($e->lastLesson)<div class="dash-sub" style="font-size:12px;margin-top:2px;">Last lesson: {{ $e->lastLesson->title }}</div>@endif
                </div>
            @empty
                <p class="dash-sub">No program enrollments yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Attendance / session history --}}
<div class="panel">
    <div class="panel-head"><div class="panel-title">📅 Attendance & Session History</div></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Date</th><th>Program</th><th>Instructor</th><th>Status</th><th>Duration</th></tr></thead>
            <tbody>
            @forelse($sessions as $s)
                <tr>
                    <td data-label="Date">{{ $s->scheduled_at?->format('M j, Y g:i a') ?? $s->created_at->format('M j, Y') }}</td>
                    <td data-label="Program">{{ $s->course->title ?? '—' }}</td>
                    <td data-label="Instructor">{{ $s->tutor->name ?? '—' }}</td>
                    <td data-label="Status"><span class="status-pill {{ $s->status=='ABSENT'?'st-DRAFT':'st-ACTIVE' }}">{{ $s->status }}</span></td>
                    <td data-label="Duration">{{ $s->duration_minutes ? $s->duration_minutes.' min' : '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">No sessions logged yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
