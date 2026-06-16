@extends('layouts.app')
@section('title', 'Events')
@section('content')
<section class="hero-bg" style="padding:60px 0;">
    <div class="container text-center">
        <div style="font-size:48px;margin-bottom:16px;">🎉</div>
        <h1 style="font-size:clamp(32px,5vw,48px);color:var(--navy);margin-bottom:12px;">Upcoming Events</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Workshops, showcases, and special live sessions for our learning community.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="grid md:grid-2 lg:grid-3">
            @foreach([
                ['🎤','Young Speakers Showcase','Watch our public speaking students shine on stage.','Saturday, July 12'],
                ['📖','Storytelling Festival','A celebration of African oral traditions and tales.','Saturday, July 26'],
                ['🎨','Art Exhibition Online','Our young artists present their best creative work.','Saturday, August 9'],
            ] as [$icon,$title,$desc,$date])
                <div class="card" data-reveal>
                    <div style="font-size:40px;margin-bottom:12px;">{{ $icon }}</div>
                    <span class="badge badge-pink mb-2" style="display:inline-block;">{{ $date }}</span>
                    <h3 style="font-size:19px;color:var(--navy);margin:8px 0;">{{ $title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.6;margin-bottom:16px;">{{ $desc }}</p>
                    <a href="{{ route('discovery') }}" class="btn-secondary" style="font-size:14px;min-height:40px;">Register Interest</a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
