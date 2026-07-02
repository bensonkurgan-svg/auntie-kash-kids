@extends('layouts.dashboard')
@section('title', 'CEO Dashboard')
@section('content')
@php
    $avatarColors = ['#FF3E9E','#7B2FF7','#2AA7FF','#E67E22','#7ED321','#1ABC9C'];
    $initials = fn($n) => strtoupper(\Illuminate\Support\Str::substr($n,0,2));
@endphp

<div class="dash-header">
    <div>
        <h1 class="dash-title">CEO Dashboard</h1>
        <p class="dash-sub">Welcome back, {{ Auth::user()->name }} — here's how Auntie Kash Kids is performing.</p>
    </div>
    <div class="dash-date">📅 {{ now()->format('l, j F Y') }}</div>
</div>

{{-- KPI cards --}}
<div class="kpi-grid">
    <div class="kpi" style="--kpi-accent:#7B2FF7;--kpi-bg:#F0E8FF;">
        <div class="kpi-icon">💰</div>
        <div class="kpi-value">${{ number_format($stats['revenue'], 0) }}</div>
        <div class="kpi-label">Est. Monthly Revenue</div>
        <div class="kpi-trend up">▲ active enrolments</div>
    </div>
    <div class="kpi" style="--kpi-accent:#2AA7FF;--kpi-bg:#E8F4FF;">
        <div class="kpi-icon">👥</div>
        <div class="kpi-value">{{ $stats['users'] }}</div>
        <div class="kpi-label">Total Users</div>
        <div class="kpi-trend flat">{{ $stats['parents'] }} parents · {{ $stats['tutors'] }} tutors</div>
    </div>
    <div class="kpi" style="--kpi-accent:#7ED321;--kpi-bg:#EFFAEA;">
        <div class="kpi-icon">🎓</div>
        <div class="kpi-value">{{ $stats['students'] }}</div>
        <div class="kpi-label">Enrolled Students</div>
        <div class="kpi-trend up">{{ $stats['enrollments'] }} active enrolments</div>
    </div>
    <div class="kpi" style="--kpi-accent:#FF3E9E;--kpi-bg:#FFE8F5;">
        <div class="kpi-icon">📈</div>
        <div class="kpi-value">{{ $stats['avgProgress'] }}%</div>
        <div class="kpi-label">Avg. Student Progress</div>
        <div class="kpi-trend flat">{{ $stats['completed'] }} completed</div>
    </div>
    <div class="kpi" style="--kpi-accent:#E67E22;--kpi-bg:#FFF1E0;">
        <div class="kpi-icon">📞</div>
        <div class="kpi-value">{{ $stats['discoveryNew'] }}</div>
        <div class="kpi-label">New Discovery Calls</div>
        <div class="kpi-trend flat">{{ $stats['discoveryTotal'] }} all-time</div>
    </div>
    <div class="kpi" style="--kpi-accent:#1ABC9C;--kpi-bg:#E3F8F3;">
        <div class="kpi-icon">⏰</div>
        <div class="kpi-value">{{ $stats['pendingApprovals'] }}</div>
        <div class="kpi-label">Pending Approvals</div>
        <div class="kpi-trend flat">{{ $stats['publishedContent'] }} published</div>
    </div>
</div>

{{-- Pending approvals (action needed) --}}
@if($pendingRequests->count() > 0)
<div class="panel" style="border:1.5px solid #E8D0FF;">
    <div class="panel-head" style="background:linear-gradient(90deg,#FBF4FF,#FFF4FB);">
        <div class="panel-title">⏳ Content Awaiting Your Approval</div>
        <span class="status-pill st-PENDING">{{ $pendingRequests->count() }} pending</span>
    </div>
    <div class="panel-body">
        <table class="dtable">
            <thead><tr><th>Page</th><th>Requested By</th><th>Date</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
            @foreach($pendingRequests as $req)
                <tr>
                    <td data-label="Page" style="font-weight:700;color:var(--navy);">{{ ucfirst($req->page_key) }}</td>
                    <td data-label="Requested By">{{ $req->requester->name ?? '—' }}</td>
                    <td data-label="Date">{{ $req->created_at->format('M j, Y') }}</td>
                    <td data-label="Action" style="text-align:right;">
                        <div class="flex gap-2" style="justify-content:flex-end;">
                            <form method="POST" action="{{ route('cms.approve') }}">@csrf<input type="hidden" name="id" value="{{ $req->id }}"><button class="status-pill st-ENROLLED" style="border:none;cursor:pointer;">✓ Approve</button></form>
                            <form method="POST" action="{{ route('cms.reject') }}">@csrf<input type="hidden" name="id" value="{{ $req->id }}"><button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">✕ Reject</button></form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Course materials awaiting review --}}
@if($pendingMaterials->count() > 0)
<div class="panel" style="border:1.5px solid #FFD8A8;">
    <div class="panel-head" style="background:linear-gradient(90deg,#FFF8F0,#FFFBF4);">
        <div class="panel-title">📋 Course Materials Awaiting Review</div>
        <span class="status-pill st-PENDING">{{ $pendingMaterials->count() }} pending</span>
    </div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Material</th><th>Program</th><th>Submitted By</th><th style="text-align:right;">Decision</th></tr></thead>
            <tbody>
            @foreach($pendingMaterials as $mat)
                <tr>
                    <td data-label="Material">
                        <span style="font-weight:700;color:var(--navy);">{{ $mat->title }}</span>
                        @if($mat->file_path)<a href="{{ route('materials.download',$mat) }}" style="color:var(--purple);font-size:12px;margin-left:6px;">(view)</a>
                        @elseif($mat->external_url)<a href="{{ $mat->external_url }}" target="_blank" style="color:var(--purple);font-size:12px;margin-left:6px;">(link)</a>@endif
                    </td>
                    <td data-label="Program">{{ $mat->course->title ?? '—' }}</td>
                    <td data-label="Submitted By">{{ $mat->submitter->name ?? '—' }}</td>
                    <td data-label="Decision" style="text-align:right;">
                        <div class="flex gap-2" style="justify-content:flex-end;flex-wrap:wrap;">
                            <form method="POST" action="{{ route('materials.review',$mat) }}">@csrf<input type="hidden" name="decision" value="APPROVED"><button class="status-pill st-APPROVED" style="border:none;cursor:pointer;">✓ Approve</button></form>
                            <form method="POST" action="{{ route('materials.review',$mat) }}">@csrf<input type="hidden" name="decision" value="UNDER_REVIEW"><button class="status-pill st-PENDING" style="border:none;cursor:pointer;">⏳ Keep Reviewing</button></form>
                            <form method="POST" action="{{ route('materials.review',$mat) }}" onsubmit="this.querySelector('[name=review_note]').value = prompt('Reason for rejection (optional):') || '';">@csrf<input type="hidden" name="decision" value="REJECTED"><input type="hidden" name="review_note"><button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">✕ Reject</button></form>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Discovery calls + staff assignment --}}
@if($discoveryForms->count() > 0)
<div class="panel">
    <div class="panel-head" style="background:linear-gradient(90deg,#FFF4FB,#F4F9FF);">
        <div class="panel-title">📞 Discovery Calls & Staff Assignment</div>
        <span class="dash-sub">Assign a team member to each call</span>
    </div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Parent</th><th>Child</th><th>Status</th><th>Assigned To</th><th>Assigned</th></tr></thead>
            <tbody>
            @foreach($discoveryForms as $d)
                <tr>
                    <td data-label="Parent" style="font-weight:700;color:var(--navy);">{{ $d->parent_name }}</td>
                    <td data-label="Child">{{ $d->child_name }} <span class="dash-sub">· {{ $d->child_age }}</span></td>
                    <td data-label="Status"><span class="status-pill st-{{ $d->status }}">{{ $d->status }}</span></td>
                    <td data-label="Assigned To">
                        <form method="POST" action="{{ route('discovery.assign', $d->id) }}">@csrf
                            <select name="assigned_staff_id" onchange="this.form.submit()" style="font-size:12px;padding:5px 8px;border-radius:8px;border:1px solid var(--border);cursor:pointer;max-width:160px;">
                                <option value="">— Unassigned —</option>
                                @foreach($assignableStaff as $staff)
                                    <option value="{{ $staff->id }}" {{ $d->assigned_staff_id==$staff->id?'selected':'' }}>{{ $staff->name }} ({{ ucfirst(strtolower($staff->role)) }})</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td data-label="Assigned" class="dash-sub">{{ $d->assigned_at ? $d->assigned_at->format('M j, g:i a') : '—' }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Tabbed detail sections --}}
<div class="dash-tabs">
    <button class="dash-tab active" data-tab="users">👥 All Users</button>
    <button class="dash-tab" data-tab="tutors">🎓 Tutor Performance</button>
    <button class="dash-tab" data-tab="courses">📚 Course Analytics</button>
    <button class="dash-tab" data-tab="activity">🕐 Recent Activity</button>
</div>

{{-- Users tab --}}
<div class="tab-panel active" data-panel="users">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Platform Users & Activity</div><span class="dash-sub">{{ $users->count() }} total</span></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>User</th><th>Role</th><th>What they're doing</th><th>Joined</th></tr></thead>
                <tbody>
                @foreach($users as $i => $u)
                    <tr>
                        <td data-label="User">
                            <div class="user-cell">
                                <span class="avatar-chip" style="background:{{ $avatarColors[$i % count($avatarColors)] }};">{{ $initials($u->name) }}</span>
                                <span><span class="nm">{{ $u->name }}</span><br><span class="em">{{ $u->email }}</span></span>
                            </div>
                        </td>
                        <td data-label="Role"><span class="role-pill role-{{ $u->role }}">{{ $u->role }}</span></td>
                        <td data-label="Activity" style="color:#555;">{{ $u->activity }}</td>
                        <td data-label="Joined" style="color:var(--muted);font-size:13px;">{{ $u->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Tutors tab --}}
<div class="tab-panel" data-panel="tutors">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Tutor → Student Progress</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Tutor</th><th>Specialties</th><th>Courses</th><th>Students</th><th>Avg Progress</th><th>Rating</th></tr></thead>
                <tbody>
                @forelse($tutorProgress as $i => $t)
                    <tr>
                        <td data-label="Tutor">
                            <div class="user-cell">
                                <span class="avatar-chip" style="background:{{ $avatarColors[$i % count($avatarColors)] }};">{{ $initials($t->name) }}</span>
                                <span class="nm">{{ $t->name }}</span>
                            </div>
                        </td>
                        <td data-label="Specialties" style="font-size:13px;color:#666;">{{ implode(', ', array_slice($t->specialties,0,2)) ?: '—' }}</td>
                        <td data-label="Courses">{{ $t->courseCount }}</td>
                        <td data-label="Students"><strong>{{ $t->studentCount }}</strong></td>
                        <td data-label="Avg Progress">
                            <div class="prog-wrap"><span class="mini-prog"><span style="width:{{ $t->avgProgress }}%;"></span></span><span class="prog-num">{{ $t->avgProgress }}%</span></div>
                        </td>
                        <td data-label="Rating"><span style="color:#F5A623;">★</span> <strong>{{ $t->rating }}</strong> <span style="color:#999;font-size:12px;">({{ $t->reviewCount }})</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">No tutors yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Courses tab --}}
<div class="tab-panel" data-panel="courses">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Course Enrolment & Revenue</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Program</th><th>Tutor</th><th>Students</th><th>Avg Progress</th><th>Revenue</th></tr></thead>
                <tbody>
                @foreach($courseStats as $c)
                    <tr>
                        <td data-label="Program" style="font-weight:700;color:var(--navy);">{{ $c->icon }} {{ $c->title }}</td>
                        <td data-label="Tutor" style="color:#555;">{{ $c->tutor }}</td>
                        <td data-label="Students"><strong>{{ $c->students }}</strong></td>
                        <td data-label="Avg Progress"><div class="prog-wrap"><span class="mini-prog"><span style="width:{{ $c->avgProgress }}%;"></span></span><span class="prog-num">{{ $c->avgProgress }}%</span></div></td>
                        <td data-label="Revenue" style="font-weight:700;color:#2d9c0f;">${{ number_format($c->revenue, 0) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Activity tab --}}
<div class="tab-panel" data-panel="activity">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Recent Enrolments</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Student</th><th>Program</th><th>Parent</th><th>Progress</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($recentEnrollments as $e)
                    <tr>
                        <td data-label="Student" style="font-weight:700;color:var(--navy);">{{ $e->student->name ?? '—' }}</td>
                        <td data-label="Program">{{ $e->course->icon ?? '' }} {{ $e->course->title ?? '—' }}</td>
                        <td data-label="Parent" style="color:#555;">{{ $e->user->name ?? '—' }}</td>
                        <td data-label="Progress"><div class="prog-wrap"><span class="mini-prog"><span style="width:{{ $e->progress }}%;"></span></span><span class="prog-num">{{ round($e->progress) }}%</span></div></td>
                        <td data-label="Status"><span class="status-pill st-{{ $e->status }}">{{ $e->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">No enrolments yet.</td></tr>
                @endforelse
                </tbody>
            </table>
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
