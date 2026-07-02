@extends('layouts.app')
@section('title', 'Parent Resource Centre')
@section('content')
<section class="hero-bg" style="padding:50px 0;">
    <div class="container text-center">
        <span class="welcome-pill">FOR FAMILIES</span>
        <h1 style="font-size:clamp(30px,4vw,46px);color:var(--navy);margin:12px 0;">Parent Resource Centre</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto 24px;">Articles, tips, and guides to support your child's learning journey.</p>
        {{-- Search --}}
        <form method="GET" style="max-width:480px;margin:0 auto;display:flex;gap:8px;">
            <input type="text" name="q" value="{{ $search }}" class="input" placeholder="Search resources…" style="flex:1;">
            <button class="btn-primary" style="min-height:48px;">Search</button>
        </form>
    </div>
</section>

{{-- Featured --}}
@if($featured->count() && !$search && !$category)
<section class="section" style="padding-bottom:0;">
    <div class="container">
        <h2 style="font-size:24px;color:var(--navy);margin-bottom:20px;">⭐ Featured</h2>
        <div class="grid md:grid-3 gap-6">
            @foreach($featured as $r)
                <a href="{{ route('parent.article', $r) }}" class="card" data-reveal>
                    <span class="badge badge-purple" style="font-size:11px;">{{ $r->category }}</span>
                    <h3 style="font-size:18px;color:var(--navy);margin:8px 0;">{{ $r->title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.6;">{{ \Illuminate\Support\Str::limit($r->description, 100) }}</p>
                    <span style="color:var(--purple);font-weight:700;font-size:14px;">Read more →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section">
    <div class="container">
        <div class="grid" style="grid-template-columns:220px 1fr;gap:32px;align-items:start;">
            {{-- Category sidebar --}}
            <div>
                <h3 style="font-size:16px;color:var(--navy);margin-bottom:12px;">Categories</h3>
                <a href="{{ route('parent.resources') }}" class="cat-link {{ !$category ? 'active' : '' }}">All Resources</a>
                @foreach($categories as $cat)
                    <a href="{{ route('parent.resources', ['category'=>$cat]) }}" class="cat-link {{ $category==$cat ? 'active' : '' }}">{{ $cat }}</a>
                @endforeach
            </div>
            {{-- Results --}}
            <div>
                @if($search)<p class="dash-sub mb-4">{{ $resources->count() }} result(s) for "{{ $search }}"</p>@endif
                @forelse($resources as $r)
                    <a href="{{ route('parent.article', $r) }}" class="card mb-4" data-reveal style="display:block;">
                        <div class="flex items-center gap-2 mb-1">
                            @if($r->category)<span class="badge badge-pink" style="font-size:11px;">{{ $r->category }}</span>@endif
                        </div>
                        <h3 style="font-size:19px;color:var(--navy);margin-bottom:6px;">{{ $r->title }}</h3>
                        <p style="font-size:14px;color:#666;line-height:1.6;">{{ \Illuminate\Support\Str::limit($r->description, 140) }}</p>
                        <span style="color:var(--purple);font-weight:700;font-size:14px;">Read more →</span>
                    </a>
                @empty
                    <p class="text-muted text-center" style="padding:50px;">No resources found{{ $search ? ' for your search' : '' }}. Check back soon!</p>
                @endforelse
            </div>
        </div>
    </div>
</section>

{{-- Newsletter signup --}}
<section class="section" style="background:linear-gradient(135deg,#7B2FF7,#FF3E9E);">
    <div class="container-narrow text-center">
        <h2 class="text-white mb-2" style="font-size:clamp(24px,3vw,36px);">📬 Stay in the Loop</h2>
        <p style="color:rgba(255,255,255,0.9);margin-bottom:20px;">Get new parenting resources and updates delivered to your inbox.</p>
        <form method="POST" action="{{ route('contact') }}" style="max-width:420px;margin:0 auto;display:flex;gap:8px;">@csrf
            <input type="email" name="email" class="input" placeholder="Your email" required style="flex:1;">
            <button class="btn-white" style="min-height:48px;">Subscribe</button>
        </form>
    </div>
</section>

<style>
.cat-link { display:block; padding:10px 14px; border-radius:10px; font-size:14px; color:#555; font-weight:600; margin-bottom:4px; transition:all 140ms; }
.cat-link:hover { background:var(--surface); color:var(--purple); }
.cat-link.active { background:#F0E8FF; color:var(--purple); }
@media (max-width:768px){ .container .grid[style*="220px"] { grid-template-columns:1fr !important; } }
</style>
@endsection
