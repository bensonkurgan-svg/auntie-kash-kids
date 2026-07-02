@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('content')
@php
    $avatarColors = ['#FF3E9E','#7B2FF7','#2AA7FF','#E67E22','#7ED321','#1ABC9C'];
    $initials = fn($n) => strtoupper(\Illuminate\Support\Str::substr($n,0,2));
@endphp

<div class="dash-header">
    <div>
        <h1 class="dash-title">Admin Dashboard</h1>
        <p class="dash-sub">Operations centre — discovery calls, content, and family management.</p>
    </div>
    <div class="dash-date">📅 {{ now()->format('l, j F Y') }}</div>
</div>

{{-- KPI cards (operational — no revenue) --}}
<div class="kpi-grid">
    <div class="kpi" style="--kpi-accent:#FF3E9E;--kpi-bg:#FFE8F5;">
        <div class="kpi-icon">📞</div>
        <div class="kpi-value">{{ $stats['newDiscovery'] }}</div>
        <div class="kpi-label">New Discovery Calls</div>
        <div class="kpi-trend up">needs follow-up</div>
    </div>
    <div class="kpi" style="--kpi-accent:#2AA7FF;--kpi-bg:#E8F4FF;">
        <div class="kpi-icon">🔄</div>
        <div class="kpi-value">{{ $stats['contactedDisc'] }}</div>
        <div class="kpi-label">In Conversation</div>
        <div class="kpi-trend flat">{{ $stats['enrolledDisc'] }} enrolled</div>
    </div>
    <div class="kpi" style="--kpi-accent:#E67E22;--kpi-bg:#FFF1E0;">
        <div class="kpi-icon">⏰</div>
        <div class="kpi-value">{{ $stats['pendingReviews'] }}</div>
        <div class="kpi-label">Content to Review</div>
        <div class="kpi-trend flat">{{ $stats['draftContent'] }} drafts</div>
    </div>
    <div class="kpi" style="--kpi-accent:#7ED321;--kpi-bg:#EFFAEA;">
        <div class="kpi-icon">👨‍👩‍👧</div>
        <div class="kpi-value">{{ $stats['students'] }}</div>
        <div class="kpi-label">Enrolled Students</div>
        <div class="kpi-trend flat">across {{ $stats['courses'] }} programs</div>
    </div>
    <div class="kpi" style="--kpi-accent:#7B2FF7;--kpi-bg:#F0E8FF;">
        <div class="kpi-icon">📝</div>
        <div class="kpi-value">{{ $stats['publishedContent'] }}</div>
        <div class="kpi-label">Published Content</div>
        <div class="kpi-trend flat">{{ $stats['draftContent'] }} in draft</div>
    </div>
</div>

{{-- Quick action --}}
<a href="{{ route('cms.editor') }}" class="panel" style="display:flex;align-items:center;gap:16px;padding:18px 22px;text-decoration:none;margin-bottom:24px;">
    <div style="width:48px;height:48px;border-radius:13px;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);display:flex;align-items:center;justify-content:center;font-size:22px;">✏️</div>
    <div><div style="font-weight:700;color:var(--navy);">Open CMS Content Editor</div><div class="dash-sub">Edit homepage & about page — changes go to the CEO for approval</div></div>
    <span style="margin-left:auto;color:var(--purple);font-weight:700;">Open →</span>
</a>

{{-- Tabs --}}
<div class="dash-tabs">
    <button class="dash-tab active" data-tab="discovery">📞 Discovery Pipeline</button>
    <button class="dash-tab" data-tab="content">📝 Content Library</button>
    <button class="dash-tab" data-tab="parents">👨‍👩‍👧 Recent Families</button>
    @if($recentRequests->count() > 0)<button class="dash-tab" data-tab="requests">⏳ My Submissions</button>@endif
</div>

{{-- Discovery pipeline tab --}}
<div class="tab-panel active" data-panel="discovery">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Discovery Call Submissions</div><span class="dash-sub">{{ $discoveryForms->count() }} recent</span></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Parent</th><th>Child</th><th>Contact</th><th>Assigned To</th><th>Status</th><th style="text-align:right;">Schedule / Update</th></tr></thead>
                <tbody>
                @forelse($discoveryForms as $d)
                    @php
                        $waNumber = preg_replace('/[^0-9]/', '', $d->parent_phone ?? '');
                        $waText = rawurlencode("Hi {$d->parent_name}, this is Auntie Kash Kids Academy. We received your request for {$d->child_name} and would love to schedule a free discovery call. When suits you best?");
                        $mailSubject = rawurlencode("Your Auntie Kash Kids discovery call for {$d->child_name}");
                        $mailBody = rawurlencode("Hi {$d->parent_name},\n\nThank you for your interest in Auntie Kash Kids Academy! We'd love to schedule a free discovery call to talk about {$d->child_name}'s learning journey.\n\nPlease let us know a few times that work for you.\n\nWarm regards,\nAuntie Kash Kids Team");
                    @endphp
                    <tr>
                        <td data-label="Parent">
                            <span class="nm">{{ $d->parent_name }}</span><br>
                            <span class="em">{{ $d->child_name }} · age {{ $d->child_age }}</span>
                        </td>
                        <td data-label="Child" style="font-size:13px;color:#666;">
                            ✉️ {{ $d->parent_email }}<br>
                            📞 {{ $d->parent_phone ?: '—' }}<br>
                            <span class="dash-sub">Prefers: {{ $d->preferred_contact }}</span>
                        </td>
                        <td data-label="Schedule">
                            <div class="flex gap-2" style="flex-wrap:wrap;">
                                <a href="mailto:{{ $d->parent_email }}?subject={{ $mailSubject }}&body={{ $mailBody }}" class="status-pill st-CONTACTED" style="text-decoration:none;">✉️ Email</a>
                                @if($waNumber)
                                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" class="status-pill st-APPROVED" style="text-decoration:none;">💬 WhatsApp</a>
                                @endif
                            </div>
                        </td>
                        <td data-label="Assigned To">
                            @if(Auth::user()->isCEO())
                                <form method="POST" action="{{ route('discovery.assign', $d->id) }}">@csrf
                                    <select name="assigned_staff_id" onchange="this.form.submit()" style="font-size:12px;padding:5px 8px;border-radius:8px;border:1px solid var(--border);cursor:pointer;max-width:150px;">
                                        <option value="">— Unassigned —</option>
                                        @foreach($assignableStaff as $staff)
                                            <option value="{{ $staff->id }}" {{ $d->assigned_staff_id==$staff->id?'selected':'' }}>{{ $staff->name }} ({{ ucfirst(strtolower($staff->role)) }})</option>
                                        @endforeach
                                    </select>
                                </form>
                            @else
                                @if($d->assignedStaff)
                                    <span class="role-pill role-{{ $d->assignedStaff->role }}">{{ $d->assignedStaff->name }}</span>
                                @else
                                    <span class="dash-sub">Unassigned</span>
                                @endif
                            @endif
                        </td>
                        <td data-label="Status"><span class="status-pill st-{{ $d->status }}">{{ $d->status }}</span></td>
                        <td data-label="Update" style="text-align:right;">
                            <form method="POST" action="{{ route('discovery.status', $d->id) }}" style="display:inline-flex;gap:6px;align-items:center;">@csrf
                                <select name="status" onchange="this.form.submit()" style="font-size:12px;padding:5px 8px;border-radius:8px;border:1px solid var(--border);cursor:pointer;">
                                    <option value="NEW" {{ $d->status=='NEW'?'selected':'' }}>New</option>
                                    <option value="CONTACTED" {{ $d->status=='CONTACTED'?'selected':'' }}>Contacted</option>
                                    <option value="ENROLLED" {{ $d->status=='ENROLLED'?'selected':'' }}>Enrolled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">No discovery submissions yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Content library tab --}}
<div class="tab-panel" data-panel="content">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Content Library</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Title</th><th>Type</th><th>Author</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                @forelse($contentPosts as $p)
                    <tr>
                        <td data-label="Title" style="font-weight:700;color:var(--navy);max-width:280px;">{{ $p->title }}</td>
                        <td data-label="Type" style="font-size:12px;color:#666;">{{ str_replace('_',' ', $p->type) }}</td>
                        <td data-label="Author">{{ $p->author->name ?? '—' }}</td>
                        <td data-label="Status"><span class="status-pill st-{{ $p->status }}">{{ $p->status }}</span></td>
                        <td data-label="Date" style="color:var(--muted);font-size:13px;">{{ $p->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="text-align:center;color:var(--muted);padding:30px;">No content yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent families tab --}}
<div class="tab-panel" data-panel="parents">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">Recently Joined Families</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Parent</th><th>Email</th><th>Children</th><th>Joined</th></tr></thead>
                <tbody>
                @forelse($recentParents as $i => $p)
                    <tr>
                        <td data-label="Parent">
                            <div class="user-cell">
                                <span class="avatar-chip" style="background:{{ $avatarColors[$i % count($avatarColors)] }};">{{ $initials($p->name) }}</span>
                                <span class="nm">{{ $p->name }}</span>
                            </div>
                        </td>
                        <td data-label="Email" style="color:#555;">{{ $p->email }}</td>
                        <td data-label="Children"><strong>{{ $p->students_count }}</strong></td>
                        <td data-label="Joined" style="color:var(--muted);font-size:13px;">{{ $p->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--muted);padding:30px;">No parents registered yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- My submissions tab --}}
@if($recentRequests->count() > 0)
<div class="tab-panel" data-panel="requests">
    <div class="panel">
        <div class="panel-head"><div class="panel-title">My Content Submissions (awaiting CEO)</div></div>
        <div class="panel-body" style="overflow-x:auto;">
            <table class="dtable">
                <thead><tr><th>Page</th><th>Submitted By</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                @foreach($recentRequests as $req)
                    <tr>
                        <td data-label="Page" style="font-weight:700;color:var(--navy);">{{ ucfirst($req->page_key) }}</td>
                        <td data-label="Submitted By">{{ $req->requester->name ?? '—' }}</td>
                        <td data-label="Status"><span class="status-pill st-PENDING">Awaiting approval</span></td>
                        <td data-label="Date" style="color:var(--muted);font-size:13px;">{{ $req->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

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
