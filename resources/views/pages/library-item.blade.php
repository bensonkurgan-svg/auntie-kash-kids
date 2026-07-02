@extends('layouts.app')
@section('title', $item->title)
@section('content')
<section class="section">
    <div class="container-narrow">
        <a href="{{ route('library') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Library</a>
        <div style="margin:24px 0;">
            <div class="flex gap-2 mb-4"><span class="badge badge-blue">{{ $item->category }}</span><span class="badge badge-green">{{ $item->age_range }}</span></div>
            <h1 style="font-size:clamp(28px,4vw,40px);color:var(--navy);margin-bottom:16px;">{{ $item->title }}</h1>
            <p style="font-size:18px;color:#555;line-height:1.8;margin-bottom:24px;">{{ $item->excerpt }}</p>
            <div style="font-size:16px;line-height:1.8;color:#333;">{!! $item->body !!}</div>
        </div>
    </div>
</section>
@endsection
