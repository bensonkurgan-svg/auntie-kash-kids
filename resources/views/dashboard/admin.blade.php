@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('content')
<div class="mb-8">
    <h1 style="font-size:30px;color:var(--navy);">Admin Dashboard</h1>
    <p style="color:var(--muted);margin-top:4px;">Manage content, courses, and discovery submissions.</p>
</div>

<div class="grid md:grid-4" style="gap:16px;margin-bottom:32px;">
    @foreach([
        ['Total Users',$stats['users'],'#2AA7FF','👥'],
        ['Total Courses',$stats['courses'],'#7ED321','📚'],
        ['Pending Reviews',$stats['pendingReviews'],'#E67E22','⏰'],
        ['New Discovery Calls',$stats['newDiscovery'],'#FF3E9E','📞'],
    ] as [$label,$value,$color,$icon])
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $color }}22;">{{ $icon }}</div>
            <div style="font-family:var(--font-fredoka);font-weight:700;font-size:28px;color:var(--navy);">{{ $value }}</div>
            <div style="color:var(--muted);font-size:14px;">{{ $label }}</div>
        </div>
    @endforeach
</div>

<div class="mb-8">
    <a href="{{ route('cms.editor') }}" class="card flex items-center gap-4" style="text-decoration:none;">
        <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);display:flex;align-items:center;justify-content:center;font-size:22px;">✏️</div>
        <div><div style="font-weight:700;color:var(--navy);">CMS Content Editor</div><div style="color:var(--muted);font-size:14px;">Edit homepage and about page content</div></div>
    </a>
</div>

<section id="discovery">
    <h2 style="font-size:20px;color:var(--navy);margin-bottom:16px;">Discovery Call Submissions</h2>
    <div class="stat-card" style="padding:0;overflow:hidden;">
        @if($discoveryForms->count() > 0)
        <table>
            <thead><tr><th>Parent</th><th>Child</th><th>Age</th><th>Contact</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @foreach($discoveryForms as $d)
                    <tr>
                        <td style="font-weight:600;color:var(--navy);">{{ $d->parent_name }}<br><span style="font-weight:400;color:var(--muted);font-size:12px;">{{ $d->parent_email }}</span></td>
                        <td>{{ $d->child_name }}</td>
                        <td>{{ $d->child_age }}</td>
                        <td>{{ $d->preferred_contact }}</td>
                        <td><span class="badge {{ $d->status === 'NEW' ? 'badge-pink' : ($d->status === 'CONTACTED' ? 'badge-blue' : 'badge-green') }}">{{ $d->status }}</span></td>
                        <td>{{ $d->created_at->format('M j') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else<div class="text-center" style="padding:32px;color:var(--muted);">No discovery submissions yet.</div>@endif
    </div>
</section>
@endsection
