@extends('layouts.dashboard')
@section('title', 'Students')
@section('content')
@php $avatarColors=['#FF3E9E','#7B2FF7','#2AA7FF','#E67E22','#7ED321','#1ABC9C']; @endphp
<div class="dash-header">
    <div><h1 class="dash-title">Students</h1><p class="dash-sub">All enrolled students — filter by program or instructor.</p></div>
    <div class="dash-date">{{ $students->count() }} students</div>
</div>

{{-- Filters --}}
<div class="panel">
    <div class="panel-body padded">
        <form method="GET" class="flex gap-3" style="flex-wrap:wrap;align-items:flex-end;">
            <div style="flex:1;min-width:180px;">
                <label class="label">Filter by Program</label>
                <select name="course" class="input" onchange="this.form.submit()">
                    <option value="">All Programs</option>
                    @foreach($courses as $c)<option value="{{ $c->id }}" {{ $courseId==$c->id?'selected':'' }}>{{ $c->icon }} {{ $c->title }}</option>@endforeach
                </select>
            </div>
            <div style="flex:1;min-width:180px;">
                <label class="label">Filter by Instructor</label>
                <select name="tutor" class="input" onchange="this.form.submit()">
                    <option value="">All Instructors</option>
                    @foreach($tutors as $t)<option value="{{ $t->id }}" {{ $tutorId==$t->id?'selected':'' }}>{{ $t->user->name ?? 'Tutor' }}</option>@endforeach
                </select>
            </div>
            @if($courseId || $tutorId)<a href="{{ route('directory.students') }}" class="btn-secondary" style="min-height:44px;">Clear</a>@endif
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Student</th><th>Parent / Contact</th><th>Programs</th><th>Instructor</th><th>Progress</th><th></th></tr></thead>
            <tbody>
            @forelse($students as $i => $s)
                <tr>
                    <td data-label="Student">
                        <div class="user-cell">
                            <span class="avatar-chip" style="background:{{ $avatarColors[$i % count($avatarColors)] }};">{{ strtoupper(substr($s->name,0,2)) }}</span>
                            <span><span class="nm">{{ $s->name }}</span><br><span class="em">{{ $s->age ? "Age {$s->age}" : '' }}{{ $s->grade_level ? " · {$s->grade_level}" : '' }}</span></span>
                        </div>
                    </td>
                    <td data-label="Parent" style="font-size:13px;">
                        <span style="font-weight:600;color:var(--navy);">{{ $s->parent->name ?? '—' }}</span><br>
                        <span class="em">{{ $s->parent->email ?? '' }}</span>
                        @if($s->parent && $s->parent->phone)<br><span class="em">📞 {{ $s->parent->phone }}</span>@endif
                    </td>
                    <td data-label="Programs" style="font-size:13px;">
                        @forelse($s->enrollments as $e){{ $e->course->icon ?? '' }} {{ $e->course->title ?? '—' }}@if(!$loop->last)<br>@endif @empty<span class="dash-sub">None</span>@endforelse
                    </td>
                    <td data-label="Instructor">{{ $s->assignedTutor->user->name ?? '—' }}</td>
                    <td data-label="Progress">
                        @php $avg = $s->enrollments->count() ? round($s->enrollments->avg('progress')) : 0; @endphp
                        <div class="prog-wrap"><span class="mini-prog"><span style="width:{{ $avg }}%;"></span></span><span class="prog-num">{{ $avg }}%</span></div>
                    </td>
                    <td data-label="" style="text-align:right;"><a href="{{ route('directory.student',$s) }}" class="status-pill st-CONTACTED" style="text-decoration:none;">View →</a></td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:40px;">No students match these filters.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
