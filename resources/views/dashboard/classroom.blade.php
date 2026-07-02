@extends('layouts.dashboard')
@section('title', 'Classroom')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Classroom</h1><p class="dash-sub">Assignments, grades, scoreboard, and attendance. (Live video coming soon.)</p></div>
</div>

<div class="dash-tabs">
    <button class="dash-tab active" data-tab="assignments">📝 Assignments</button>
    <button class="dash-tab" data-tab="scoreboard">🏆 Scoreboard</button>
    <button class="dash-tab" data-tab="attendance">📅 Attendance</button>
    <button class="dash-tab" data-tab="meetings">🎥 Class Meetings</button>
</div>

{{-- Assignments --}}
<div class="tab-panel active" data-panel="assignments">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">➕ New Assignment</div></div>
        <div class="panel-body padded">
            <form method="POST" action="{{ route('classroom.assignment') }}">@csrf
                <div class="grid md:grid-2 gap-4 mb-3">
                    <div><label class="label">Program</label><select name="course_id" class="input" required>@foreach($myCourses as $c)<option value="{{ $c->id }}">{{ $c->title }}</option>@endforeach</select></div>
                    <div><label class="label">Title</label><input type="text" name="title" class="input" required></div>
                </div>
                <div class="mb-3"><label class="label">Instructions</label><textarea name="instructions" class="input" rows="2"></textarea></div>
                <div class="grid md:grid-2 gap-4 mb-3">
                    <div><label class="label">Max Score</label><input type="number" name="max_score" class="input" value="100" required></div>
                    <div><label class="label">Due Date</label><input type="date" name="due_date" class="input"></div>
                </div>
                <button class="btn-primary">Create Assignment</button>
            </form>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Assignments & Submissions</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Assignment</th><th>Program</th><th>Submissions</th><th>Due</th></tr></thead>
                <tbody>
                @forelse($assignments as $a)
                    <tr>
                        <td data-label="Assignment" style="font-weight:700;color:var(--navy);">{{ $a->title }}</td>
                        <td data-label="Program">{{ $a->course->title ?? '—' }}</td>
                        <td data-label="Submissions">{{ $a->submissions->where('status','SUBMITTED')->count() }} new / {{ $a->submissions->count() }} total</td>
                        <td data-label="Due" class="dash-sub">{{ $a->due_date ? $a->due_date->format('M j, Y') : '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:24px;">No assignments yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Scoreboard --}}
<div class="tab-panel" data-panel="scoreboard">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">🏆 Top Scores</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Rank</th><th>Student</th><th>Assignment</th><th>Program</th><th>Score</th></tr></thead>
                <tbody>
                @forelse($scoreboard as $i => $s)
                    <tr>
                        <td data-label="Rank" style="font-weight:700;font-size:18px;">{{ $i==0?'🥇':($i==1?'🥈':($i==2?'🥉':'#'.($i+1))) }}</td>
                        <td data-label="Student" style="font-weight:700;color:var(--navy);">{{ $s->student->name ?? '—' }}</td>
                        <td data-label="Assignment">{{ $s->assignment->title ?? '—' }}</td>
                        <td data-label="Program" class="dash-sub">{{ $s->assignment->course->title ?? '—' }}</td>
                        <td data-label="Score"><span class="prog-num" style="font-size:16px;">{{ $s->score }}/{{ $s->assignment->max_score ?? 100 }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:24px;">No graded work yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Attendance --}}
<div class="tab-panel" data-panel="attendance">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Log a Session</div></div>
        <div class="panel-body padded">
            <form method="POST" action="{{ route('classroom.attendance') }}">@csrf
                <div class="grid md:grid-3 gap-4 mb-3">
                    <div><label class="label">Program</label><select name="course_id" class="input" required>@foreach($myCourses as $c)<option value="{{ $c->id }}">{{ $c->title }}</option>@endforeach</select></div>
                    <div><label class="label">Student</label><select name="student_id" class="input" required>@foreach($myStudents as $s)<option value="{{ $s->id }}">{{ $s->name }}</option>@endforeach</select></div>
                    <div><label class="label">Status</label><select name="status" class="input"><option value="PRESENT">Present</option><option value="ABSENT">Absent</option><option value="COMPLETED">Completed</option></select></div>
                </div>
                <div class="grid md:grid-2 gap-4 mb-3">
                    <div><label class="label">Duration (mins)</label><input type="number" name="duration_minutes" class="input" value="60"></div>
                    <div><label class="label">Notes</label><input type="text" name="notes" class="input"></div>
                </div>
                <div class="grid md:grid-2 gap-4 mb-3">
                    <div><label class="label">Meeting Platform</label><select name="meeting_platform" class="input"><option value="">None</option><option value="ZOOM">Zoom</option><option value="MEET">Google Meet</option></select></div>
                    <div><label class="label">Meeting Link</label><input type="url" name="meeting_link" id="meetingLink" class="input" placeholder="Paste your Zoom / Meet link"></div>
                </div>
                <div style="background:#F0F7FF;border-radius:10px;padding:14px;margin-bottom:12px;">
                    <p style="font-size:13px;color:#333;margin-bottom:10px;">💡 <strong>Create a free meeting</strong>, then copy the link back here:</p>
                    <div class="flex gap-2" style="flex-wrap:wrap;">
                        <a href="https://zoom.us/meeting/schedule" target="_blank" class="btn-secondary" style="min-height:38px;font-size:13px;">🎥 Create Zoom Meeting</a>
                        <a href="https://meet.google.com/new" target="_blank" class="btn-secondary" style="min-height:38px;font-size:13px;">🎥 Create Google Meet</a>
                    </div>
                    <p style="font-size:11px;color:var(--muted);margin-top:8px;">Both are free. Zoom gives 40-min group meetings; Google Meet is unlimited for 1-on-1. Copy the link they generate and paste it above.</p>
                </div>
                <button class="btn-primary">Log Session</button>
            </form>
        </div>
    </div>
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Recent Sessions</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Student</th><th>Program</th><th>Status</th><th>Duration</th><th>When</th><th>Meeting</th></tr></thead>
                <tbody>
                @forelse($sessions as $s)
                    <tr>
                        <td data-label="Student" style="font-weight:700;color:var(--navy);">{{ $s->student->name ?? '—' }}</td>
                        <td data-label="Program">{{ $s->course->title ?? '—' }}</td>
                        <td data-label="Status"><span class="status-pill {{ $s->status=='ABSENT'?'st-DRAFT':'st-ACTIVE' }}">{{ $s->status }}</span></td>
                        <td data-label="Duration">{{ $s->duration_minutes ? $s->duration_minutes.' min' : '—' }}</td>
                        <td data-label="When" class="dash-sub">{{ $s->scheduled_at?->format('M j, g:i a') ?? $s->created_at->format('M j') }}</td>
                        <td data-label="Meeting">@if($s->meeting_link)<a href="{{ $s->meeting_link }}" target="_blank" class="status-pill st-ACTIVE" style="text-decoration:none;">{{ $s->meeting_platform=='ZOOM'?'🎥 Zoom':'🎥 Meet' }} — Join</a>@else<span class="dash-sub">—</span>@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:24px;">No sessions logged yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Class Meetings --}}
<div class="tab-panel" data-panel="meetings">
    <div class="panel" style="border:1.5px solid #D6EAF8;">
        <div class="panel-head" style="background:linear-gradient(90deg,#F0F8FF,#F4FBFF);"><div class="panel-title">🎥 How to Set Up Free Video Meetings</div></div>
        <div class="panel-body padded">
            <div class="grid md:grid-2 gap-6">
                <div style="padding:16px;background:var(--surface);border-radius:12px;">
                    <h4 style="color:var(--navy);margin-bottom:8px;">🔵 Google Meet (100% free)</h4>
                    <ol style="font-size:13px;color:#555;line-height:1.9;padding-left:18px;">
                        <li>Go to <strong>meet.google.com</strong> and sign in with any Google account</li>
                        <li>Click <strong>"New meeting"</strong> → "Create a meeting for later"</li>
                        <li>Copy the link (looks like meet.google.com/abc-defg-hij)</li>
                        <li>Paste it below — the same link works every time</li>
                    </ol>
                </div>
                <div style="padding:16px;background:var(--surface);border-radius:12px;">
                    <h4 style="color:var(--navy);margin-bottom:8px;">🎥 Zoom (free — 40 min limit)</h4>
                    <ol style="font-size:13px;color:#555;line-height:1.9;padding-left:18px;">
                        <li>Sign up free at <strong>zoom.us</strong></li>
                        <li>Click <strong>"Host a Meeting"</strong> or set up a recurring meeting</li>
                        <li>Copy your <strong>Personal Meeting Link</strong></li>
                        <li>Paste it below — reuse it for every class</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head"><div class="panel-title">Your Program Meeting Rooms</div></div>
        <div class="panel-body padded">
            @forelse($myCourses as $c)
                <form method="POST" action="{{ route('classroom.meeting') }}" style="padding:16px;border:1px solid var(--border);border-radius:12px;margin-bottom:14px;">@csrf
                    <input type="hidden" name="course_id" value="{{ $c->id }}">
                    <div class="flex items-center justify-between mb-3" style="flex-wrap:wrap;gap:8px;">
                        <strong style="color:var(--navy);">{{ $c->icon }} {{ $c->title }}</strong>
                        @if($c->meeting_link)<a href="{{ $c->meeting_link }}" target="_blank" class="status-pill st-ACTIVE" style="text-decoration:none;">{{ $c->meeting_platform=='ZOOM'?'🎥 Zoom':'🔵 Meet' }} — Test Join</a>@endif
                    </div>
                    <div class="grid md:grid-3 gap-3">
                        <div><label class="label">Platform</label><select name="meeting_platform" class="input"><option value="">None</option><option value="MEET" {{ $c->meeting_platform=='MEET'?'selected':'' }}>Google Meet</option><option value="ZOOM" {{ $c->meeting_platform=='ZOOM'?'selected':'' }}>Zoom</option></select></div>
                        <div style="grid-column:span 2;"><label class="label">Meeting Link</label><input type="url" name="meeting_link" value="{{ $c->meeting_link }}" class="input" placeholder="Paste your Meet / Zoom link"></div>
                    </div>
                    <div class="mt-3"><label class="label">Schedule (optional)</label><input type="text" name="meeting_schedule" value="{{ $c->meeting_schedule }}" class="input" placeholder="e.g. Tuesdays & Thursdays, 5:00pm GMT"></div>
                    <button class="btn-primary mt-3" style="min-height:42px;">Save Meeting Room</button>
                </form>
            @empty
                <p class="dash-sub text-center" style="padding:24px;">You have no assigned programs yet.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.dash-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.dash-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.querySelector(`.tab-panel[data-panel="${tab.dataset.tab}"]`).classList.add('active');
    });
});
</script>
@endsection
