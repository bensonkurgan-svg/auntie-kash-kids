@extends('layouts.dashboard')
@section('title', 'Instructor Portal')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Instructor Portal</h1><p class="dash-sub">Your resources, handbook, policies, and assigned classes.</p></div>
</div>

{{-- Assigned classes --}}
<div class="panel">
    <div class="panel-head"><div class="panel-title">📅 My Upcoming Classes</div></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>When</th><th>Program</th><th>Student</th></tr></thead>
            <tbody>
            @forelse($upcomingClasses as $c)
                <tr>
                    <td data-label="When" style="font-weight:700;">{{ $c->scheduled_at?->format('M j, g:i a') }}</td>
                    <td data-label="Program">{{ $c->course->icon ?? '' }} {{ $c->course->title ?? '—' }}</td>
                    <td data-label="Student">{{ $c->student->name ?? '—' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" style="text-align:center;color:var(--muted);padding:24px;">No upcoming classes scheduled.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Resource library grouped by category --}}
@forelse($resources as $category => $items)
    <div class="panel">
        <div class="panel-head"><div class="panel-title">📚 {{ $category ?: 'General Resources' }}</div></div>
        <div class="panel-body padded">
            <div class="grid md:grid-2 gap-4">
                @foreach($items as $r)
                    <div class="flex items-center justify-between" style="padding:14px;background:var(--surface);border-radius:12px;">
                        <div style="min-width:0;">
                            <div style="font-weight:700;color:var(--navy);">{{ $r->typeIcon() }} {{ $r->title }}</div>
                            @if($r->description)<div class="dash-sub" style="font-size:13px;">{{ \Illuminate\Support\Str::limit($r->description, 80) }}</div>@endif
                        </div>
                        @if($r->content_type === 'ARTICLE')
                            <a href="#" onclick="alert('{{ addslashes(strip_tags($r->body)) }}');return false;" class="status-pill st-CONTACTED" style="text-decoration:none;flex-shrink:0;">Read</a>
                        @else
                            <a href="{{ $r->downloadUrl() }}" target="_blank" class="status-pill st-CONTACTED" style="text-decoration:none;flex-shrink:0;">Open ↗</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@empty
    <div class="panel"><div class="panel-body padded text-center" style="padding:40px;"><p class="dash-sub">No instructor resources have been uploaded yet.</p></div></div>
@endforelse
@endsection
