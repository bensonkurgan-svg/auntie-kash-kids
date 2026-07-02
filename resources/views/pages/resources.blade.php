@extends('layouts.app')
@section('title', 'Parent Resources')
@section('content')
<section class="hero-bg" style="padding:60px 0;">
    <div class="container text-center">
        <div style="font-size:48px;margin-bottom:16px;">🌟</div>
        <h1 style="font-size:clamp(32px,5vw,48px);color:var(--navy);margin-bottom:12px;">Parent Resource Centre</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Articles, guides and expert tips to support your child's learning journey at home.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid md:grid-2">
            @foreach($resources as $r)
                <a href="{{ route('resources.show', $r->id) }}" class="card" data-reveal>
                    <span class="badge badge-amber mb-2" style="display:inline-block;">{{ $r->category }}</span>
                    <h3 style="font-size:19px;color:var(--navy);margin:8px 0;">{{ $r->title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.6;">{{ Str::limit($r->excerpt, 130) }}</p>
                </a>
            @endforeach
        </div>
        @if($resources->isEmpty())<p class="text-center text-muted">Resources coming soon!</p>@endif
    </div>
</section>
@endsection
