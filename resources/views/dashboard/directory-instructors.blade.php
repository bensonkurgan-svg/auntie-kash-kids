@extends('layouts.dashboard')
@section('title', 'Instructors')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Instructors</h1><p class="dash-sub">Full instructor directory — profiles, contacts, programs, and status.</p></div>
    <div class="dash-date">{{ $instructors->count() }} instructors</div>
</div>

<div class="grid md:grid-2 lg:grid-3 gap-6">
    @forelse($instructors as $u)
        @php $p = $u->tutorProfile; @endphp
        <div class="card" data-reveal style="border-top:4px solid {{ $u->is_active ? '#7ED321' : '#ccc' }};">
            <div class="flex items-center gap-3 mb-3">
                @if($u->photo())
                    <img src="{{ $u->photo() }}" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid var(--purple);">
                @else
                    <span style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,#7B2FF7,#FF3E9E);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:22px;">{{ strtoupper(substr($u->name,0,2)) }}</span>
                @endif
                <div style="min-width:0;">
                    <div style="font-weight:700;color:var(--navy);">{{ $u->name }}</div>
                    <span class="status-pill {{ $u->is_active ? 'st-ACTIVE' : 'st-DRAFT' }}">{{ $u->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>

            @if($p && $p->qualifications)<div style="font-size:13px;color:#555;margin-bottom:8px;">🎓 {{ $p->qualifications }}</div>@endif

            <div style="font-size:13px;color:#555;line-height:1.9;border-top:1px solid var(--border);padding-top:10px;margin-top:8px;">
                <div>✉️ {{ $p->work_email ?? $u->work_email ?? $u->email }}</div>
                @if($p && $p->phone)<div>📞 {{ $p->phone }}</div>@endif
                @if($p && $p->country)<div>🌍 {{ $p->country }}</div>@endif
                @if($p && $p->availability)<div>🕐 {{ $p->availability }}</div>@endif
            </div>

            @if($p && $p->courses->count())
                <div style="margin-top:10px;">
                    <div class="label">Programs Taught</div>
                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                        @foreach($p->courses as $c)<span class="badge badge-purple" style="font-size:11px;">{{ $c->icon }} {{ $c->title }}</span>@endforeach
                    </div>
                </div>
            @endif

            @if($p && $p->rating > 0)<div style="margin-top:10px;font-size:13px;"><span style="color:#F5A623;">★</span> <strong>{{ $p->rating }}</strong> <span class="dash-sub">({{ $p->review_count }} reviews)</span></div>@endif
        </div>
    @empty
        <p class="dash-sub" style="grid-column:1/-1;text-align:center;padding:40px;">No instructors yet. Add them from Staff & Tutors.</p>
    @endforelse
</div>
@endsection
