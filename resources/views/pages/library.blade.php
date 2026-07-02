@extends('layouts.app')
@section('title', 'Reading Library')
@section('content')
<section class="hero-bg" style="padding:60px 0;">
    <div class="container text-center">
        <div style="font-size:48px;margin-bottom:16px;">📚</div>
        <h1 style="font-size:clamp(32px,5vw,48px);color:var(--navy);margin-bottom:12px;">Reading Library</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Curated books, stories, and reading materials for every age group.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid md:grid-2 lg:grid-3">
            @foreach($items as $item)
                <a href="{{ route('library.show', $item->id) }}" class="card" data-reveal>
                    <div class="flex items-center justify-between mb-4">
                        <span class="badge badge-blue">{{ $item->category }}</span>
                        <span class="badge badge-green">{{ $item->age_range }}</span>
                    </div>
                    <h3 style="font-size:18px;color:var(--navy);margin-bottom:8px;">{{ $item->title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.6;">{{ Str::limit($item->excerpt, 100) }}</p>
                </a>
            @endforeach
        </div>
        @if($items->isEmpty())<p class="text-center text-muted">Library items coming soon!</p>@endif
    </div>
</section>
@endsection
