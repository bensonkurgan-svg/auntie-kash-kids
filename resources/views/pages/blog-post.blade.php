@extends('layouts.app')
@section('title', $post->title)
@section('content')
<article class="section">
    <div class="container-narrow">
        <a href="{{ route('blog') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Blog</a>
        <div style="margin:24px 0;">
            <span class="badge badge-purple mb-4" style="display:inline-block;">{{ $post->category }}</span>
            <h1 style="font-size:clamp(28px,4vw,44px);color:var(--navy);margin:12px 0;line-height:1.2;">{{ $post->title }}</h1>
            <p style="color:var(--muted);font-size:14px;">{{ $post->read_time }} · By {{ $post->author->name ?? 'Auntie Kash' }}</p>
        </div>
        <div style="font-size:17px;line-height:1.9;color:#333;" class="prose">
            {!! $post->body !!}
        </div>
    </div>
</article>
@if($related->count() > 0)
<section class="section" style="background:var(--surface);">
    <div class="container">
        <h2 style="font-size:24px;color:var(--navy);margin-bottom:24px;">More from the Blog</h2>
        <div class="grid md:grid-3">
            @foreach($related as $r)
                <a href="{{ route('blog.show', $r->slug) }}" class="card">
                    <span class="badge badge-purple mb-2" style="display:inline-block;">{{ $r->category }}</span>
                    <h3 style="font-size:17px;color:var(--navy);margin:8px 0;">{{ $r->title }}</h3>
                    <p style="font-size:14px;color:#666;">{{ Str::limit($r->excerpt, 80) }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif
<style>.prose h2{font-size:26px;color:var(--navy);margin:32px 0 16px;}.prose p{margin-bottom:20px;}</style>
@endsection
