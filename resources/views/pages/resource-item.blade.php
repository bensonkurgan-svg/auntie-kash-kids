@extends('layouts.app')
@section('title', $resource->title)
@section('content')
<section class="section">
    <div class="container-narrow">
        <a href="{{ route('resources') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Resources</a>
        <div style="margin:24px 0;">
            <span class="badge badge-amber mb-4" style="display:inline-block;">{{ $resource->category }}</span>
            <h1 style="font-size:clamp(28px,4vw,40px);color:var(--navy);margin:12px 0;">{{ $resource->title }}</h1>
            <p style="font-size:18px;color:#555;line-height:1.8;margin-bottom:24px;">{{ $resource->excerpt }}</p>
            <div style="font-size:16px;line-height:1.8;color:#333;">{!! $resource->body !!}</div>
        </div>
    </div>
</section>
@endsection
