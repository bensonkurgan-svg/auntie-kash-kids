@extends('layouts.dashboard')
@section('title', 'Recruitment & Waitlist')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Recruitment & Waitlist</h1><p class="dash-sub">Instructor applications and family waitlist signups.</p></div>
</div>

<div class="dash-tabs">
    <button class="dash-tab active" data-tab="applications">🧑‍🏫 Applications ({{ $applications->count() }})</button>
    <button class="dash-tab" data-tab="waitlist">✨ Waitlist ({{ $waitlist->count() }})</button>
</div>

{{-- Applications --}}
<div class="tab-panel active" data-panel="applications">
    <div class="panel">
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Applicant</th><th>Contact</th><th>Subjects</th><th>Qualifications</th><th>CV</th><th>When</th></tr></thead>
                <tbody>
                @forelse($applications as $a)
                    <tr>
                        <td data-label="Applicant" style="font-weight:700;color:var(--navy);">{{ $a->name }}<br><span class="em">{{ $a->country }}</span></td>
                        <td data-label="Contact" style="font-size:13px;">{{ $a->email }}<br>{{ $a->phone }}</td>
                        <td data-label="Subjects">{{ $a->subjects ?: '—' }}</td>
                        <td data-label="Qualifications" style="font-size:13px;">{{ $a->qualifications ?: '—' }}</td>
                        <td data-label="CV">@if($a->cv_path)<span style="color:var(--purple);font-weight:600;">📎 On file</span>@else <span class="dash-sub">—</span>@endif</td>
                        <td data-label="When" class="dash-sub">{{ $a->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">No applications yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Waitlist --}}
<div class="tab-panel" data-panel="waitlist">
    <div class="panel">
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Name</th><th>Contact</th><th>Child</th><th>Interest</th><th>Type</th><th>When</th></tr></thead>
                <tbody>
                @forelse($waitlist as $w)
                    <tr>
                        <td data-label="Name" style="font-weight:700;color:var(--navy);">{{ $w->parent_name }}</td>
                        <td data-label="Contact" style="font-size:13px;">{{ $w->email }}<br>{{ $w->phone }}</td>
                        <td data-label="Child">{{ $w->child_name ?: '—' }}{{ $w->child_age ? " ({$w->child_age})" : '' }}</td>
                        <td data-label="Interest">{{ $w->program_interest ?: '—' }}</td>
                        <td data-label="Type"><span class="status-pill {{ $w->type=='FOUNDING_FAMILY'?'st-APPROVED':'st-CONTACTED' }}">{{ $w->type=='FOUNDING_FAMILY'?'Founding':'Waitlist' }}</span></td>
                        <td data-label="When" class="dash-sub">{{ $w->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">No waitlist signups yet.</td></tr>
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
