@extends('layouts.app')
@section('title', 'Blog')
@section('content')
<section class="hero-bg" style="padding:60px 0;">
    <div class="container text-center">
        <span class="badge badge-pink mb-4" style="display:inline-block;">Our Blog</span>
        <h1 style="font-size:clamp(32px,5vw,48px);color:var(--navy);margin-bottom:12px;">Stories, Tips & Inspiration</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Insights for parents and children on learning, creativity, and growth.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid md:grid-2 lg:grid-3">
            @foreach($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" class="card" data-reveal>
                    @if($post->featured)<span class="badge badge-pink mb-2" style="display:inline-block;">Featured</span>@endif
                    <span class="badge badge-purple mb-2" style="display:inline-block;">{{ $post->category }}</span>
                    <h3 style="font-size:19px;color:var(--navy);margin:8px 0;line-height:1.3;">{{ $post->title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.6;margin-bottom:12px;">{{ Str::limit($post->excerpt, 110) }}</p>
                    <div class="flex items-center justify-between" style="font-size:13px;color:var(--muted);">
                        <span>{{ $post->read_time }}</span>
                        <span style="color:var(--purple);font-weight:700;">Read →</span>
                    </div>
                </a>
            @endforeach
        </div>
        @if($posts->isEmpty())<p class="text-center text-muted">No blog posts yet. Check back soon!</p>@endif
    </div>
</section>
@endsection
