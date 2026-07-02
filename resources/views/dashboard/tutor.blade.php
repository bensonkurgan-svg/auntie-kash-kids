@extends('layouts.dashboard')
@section('title', 'Tutor Dashboard')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Welcome, {{ explode(' ',Auth::user()->name)[0] }}! 👋</h1><p class="dash-sub">Your courses, students, and learning materials.</p></div>
    <a href="{{ route('profile.edit') }}" class="btn-secondary" style="min-height:42px;">Edit Profile</a>
</div>

<div class="kpi-grid">
    <div class="kpi" style="--kpi-accent:#2AA7FF;--kpi-bg:#E8F4FF;"><div class="kpi-icon">📚</div><div class="kpi-value">{{ $profile->courses->count() ?? 0 }}</div><div class="kpi-label">My Courses</div></div>
    <div class="kpi" style="--kpi-accent:#7ED321;--kpi-bg:#EFFAEA;"><div class="kpi-icon">👥</div><div class="kpi-value">{{ $totalStudents }}</div><div class="kpi-label">Total Students</div></div>
    <div class="kpi" style="--kpi-accent:#FFB400;--kpi-bg:#FFF6E0;"><div class="kpi-icon">⭐</div><div class="kpi-value">{{ $profile->rating ?? '—' }}</div><div class="kpi-label">Average Rating</div></div>
</div>

@if($profile && $profile->courses->count())
{{-- Student progress --}}
<div class="panel">
    <div class="panel-head"><div class="panel-title">📈 Student Progress</div></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Student</th><th>Program</th><th>Progress</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($studentProgress as $sp)
                <tr>
                    <td data-label="Student" style="font-weight:700;color:var(--navy);">{{ $sp->student }}</td>
                    <td data-label="Program">{{ $sp->course }}</td>
                    <td data-label="Progress"><div class="prog-wrap"><span class="mini-prog"><span style="width:{{ $sp->progress }}%;"></span></span><span class="prog-num">{{ round($sp->progress) }}%</span></div></td>
                    <td data-label="Status"><span class="status-pill st-{{ $sp->status }}">{{ $sp->status }}</span></td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:24px;">No enrolled students yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Upload material --}}
<div class="panel">
    <div class="panel-head"><div class="panel-title">📤 Upload Course Material</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('materials.store') }}" enctype="multipart/form-data">@csrf
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Program</label><select name="course_id" class="input" required>@foreach($profile->courses as $c)<option value="{{ $c->id }}">{{ $c->title }}</option>@endforeach</select></div>
                <div><label class="label">Type</label><select name="type" class="input" id="matType" required><option value="DOCUMENT">Document</option><option value="IMAGE">Image</option><option value="VIDEO_LINK">Video Link</option><option value="LINK">Web Link</option></select></div>
            </div>
            <div class="mb-3"><label class="label">Title</label><input type="text" name="title" class="input" required></div>
            <div class="mb-3"><label class="label">Description (optional)</label><input type="text" name="description" class="input"></div>
            <div class="mb-3" id="fileField"><label class="label">File</label><input type="file" name="file" class="input"></div>
            <div class="mb-3" id="urlField" style="display:none;"><label class="label">URL</label><input type="url" name="external_url" class="input" placeholder="https://..."></div>
            <button class="btn-primary">Submit for Review</button>
            <p style="font-size:12px;color:var(--muted);margin-top:8px;">Submissions are reviewed by the CEO. You'll see the status below.</p>
        </form>
    </div>
</div>

{{-- My submissions status --}}
<div class="panel">
    <div class="panel-head"><div class="panel-title">📋 My Submissions</div></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Material</th><th>Program</th><th>Status</th><th>Review Note</th></tr></thead>
            <tbody>
            @forelse($mySubmissions as $m)
                <tr>
                    <td data-label="Material" style="font-weight:700;color:var(--navy);">{{ $m->title }}</td>
                    <td data-label="Program">{{ $m->course->title ?? '—' }}</td>
                    <td data-label="Status"><span class="status-pill {{ $m->status=='APPROVED'?'st-APPROVED':($m->status=='REJECTED'?'st-DRAFT':'st-PENDING') }}">{{ $m->statusLabel() }}</span></td>
                    <td data-label="Review Note" class="dash-sub">{{ $m->review_note ?: '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:24px;">No submissions yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@else
<div class="panel"><div class="panel-body padded text-center" style="padding:40px;"><p class="dash-sub">You don't have any assigned courses yet. Please contact the CEO.</p></div></div>
@endif

<script>
document.getElementById('matType')?.addEventListener('change', function(){
    const isUrl = (this.value==='VIDEO_LINK'||this.value==='LINK');
    document.getElementById('urlField').style.display = isUrl ? 'block' : 'none';
    document.getElementById('fileField').style.display = isUrl ? 'none' : 'block';
});
</script>
@endsection
