@extends('layouts.dashboard')
@section('title', 'CEO Dashboard')
@section('content')
<div class="mb-8">
    <h1 style="font-size:30px;color:var(--navy);">CEO Dashboard</h1>
    <p style="color:var(--muted);margin-top:4px;">Welcome back, {{ Auth::user()->name }}. Here's your platform overview.</p>
</div>

@if($stats['discoveryNew'] > 0)
<div style="background:linear-gradient(135deg,#FFF0FB,#F9F0FF);border:1.5px solid #E8D0FF;border-radius:16px;padding:16px;margin-bottom:24px;" class="flex items-center gap-4">
    <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#FF3E9E,#7B2FF7);display:flex;align-items:center;justify-content:center;">📋</div>
    <div><strong style="color:var(--navy);">{{ $stats['discoveryNew'] }} new discovery call submission(s)</strong><p style="color:var(--muted);font-size:14px;">Check the Admin dashboard to review them.</p></div>
</div>
@endif

<div class="grid md:grid-3" style="gap:16px;margin-bottom:32px;">
    @foreach([
        ['Total Users',$stats['users'],'#2AA7FF','👥'],
        ['Active Enrollments',$stats['enrollments'],'#7ED321','📈'],
        ['Active Courses',$stats['courses'],'#7B2FF7','📚'],
        ['Discovery Calls',$stats['discoveryTotal'],'#FF3E9E','📞'],
        ['Pending Approvals',$stats['pendingApprovals'],'#E67E22','⏰'],
        ['Published Content',$stats['publishedContent'],'#1ABC9C','📝'],
    ] as [$label,$value,$color,$icon])
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $color }}22;">{{ $icon }}</div>
            <div style="font-family:var(--font-fredoka);font-weight:700;font-size:28px;color:var(--navy);">{{ $value }}</div>
            <div style="color:var(--muted);font-size:14px;">{{ $label }}</div>
        </div>
    @endforeach
</div>

@if($pendingRequests->count() > 0)
<section style="margin-bottom:32px;">
    <h2 style="font-size:20px;color:var(--navy);margin-bottom:16px;">Pending CMS Approvals</h2>
    <div class="stat-card" style="padding:0;overflow:hidden;">
        <table>
            <thead><tr><th>Page</th><th>Requested By</th><th>Date</th><th>Action</th></tr></thead>
            <tbody>
                @foreach($pendingRequests as $req)
                    <tr>
                        <td style="font-weight:600;color:var(--navy);">{{ ucfirst($req->page_key) }}</td>
                        <td>{{ $req->requester->name ?? '—' }}</td>
                        <td>{{ $req->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="flex gap-2">
                                <form method="POST" action="{{ route('cms.approve') }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="id" value="{{ $req->id }}"><button class="badge badge-green" style="border:none;cursor:pointer;">Approve</button></form>
                                <form method="POST" action="{{ route('cms.reject') }}"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="id" value="{{ $req->id }}"><button class="badge" style="border:none;cursor:pointer;background:#FFF0F0;color:#C0392B;">Reject</button></form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

<section>
    <h2 style="font-size:20px;color:var(--navy);margin-bottom:16px;">Recent Enrollments</h2>
    <div class="stat-card" style="padding:0;overflow:hidden;">
        @if($recentEnrollments->count() > 0)
        <table>
            <thead><tr><th>Student</th><th>Course</th><th>Parent</th><th>Progress</th></tr></thead>
            <tbody>
                @foreach($recentEnrollments as $e)
                    <tr>
                        <td style="font-weight:600;color:var(--navy);">{{ $e->student->name ?? '—' }}</td>
                        <td>{{ $e->course->icon ?? '' }} {{ $e->course->title ?? '—' }}</td>
                        <td>{{ $e->user->name ?? '—' }}</td>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="progress-bar" style="width:80px;"><div class="progress-bar-fill" style="width:{{ $e->progress }}%;"></div></div>
                                <span style="font-size:12px;font-weight:700;color:var(--purple);">{{ round($e->progress) }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @else<div class="text-center" style="padding:32px;color:var(--muted);">No enrollments yet.</div>@endif
    </div>
</section>
@endsection
