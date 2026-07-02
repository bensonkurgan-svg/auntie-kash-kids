@extends('layouts.app')
@section('title', $resource->title)
@section('content')
<section class="section">
    <div class="container-narrow">
        <a href="{{ route('parent.resources') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Resources</a>
        @if($resource->category)<div style="margin-top:16px;"><span class="badge badge-pink">{{ $resource->category }}</span></div>@endif
        <h1 style="font-size:clamp(28px,4vw,42px);color:var(--navy);margin:12px 0 20px;">{{ $resource->title }}</h1>
        @if($resource->description)<p style="font-size:18px;color:#555;line-height:1.7;margin-bottom:24px;">{{ $resource->description }}</p>@endif
        <div style="font-size:16px;line-height:1.8;color:#333;">{!! $resource->body !!}</div>
        @if($resource->file_path)
            <a href="{{ route('library.download', $resource) }}" class="btn-primary mt-4" style="margin-top:24px;">⬇️ Download Resource</a>
        @endif
    </div>
</section>

@if($related->count())
<section class="section" style="background:var(--surface);">
    <div class="container">
        <h2 style="font-size:24px;color:var(--navy);margin-bottom:20px;">Related Articles</h2>
        <div class="grid md:grid-4 gap-6">
            @foreach($related as $r)
                <a href="{{ route('parent.article', $r) }}" class="card" data-reveal>
                    <h3 style="font-size:15px;color:var(--navy);margin-bottom:6px;">{{ $r->title }}</h3>
                    <span style="color:var(--purple);font-weight:700;font-size:13px;">Read →</span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
