@extends('layouts.app')
@section('title', $book->title)
@section('content')
<section class="section">
    <div class="container">
        <a href="{{ route('reading.library') }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Library</a>
        <div class="grid md:grid-2 gap-8 mt-4" style="align-items:start;">
            {{-- Cover --}}
            <div class="text-center" style="position:sticky;top:90px;">
                @if($book->cover_image)
                    <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" style="max-height:420px;border-radius:16px;box-shadow:0 16px 40px rgba(80,60,160,0.25);">
                @else
                    <div style="aspect-ratio:3/4;max-width:300px;margin:0 auto;background:linear-gradient(135deg,#F0E8FF,#FFF0F8);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:80px;">📚</div>
                @endif
                @if($book->purchase_url)
                    <a href="{{ $book->purchase_url }}" target="_blank" rel="noopener" class="btn-pink w-full mt-4" style="margin-top:20px;">🛒 Find / Buy This Book</a>
                @endif
            </div>

            {{-- Details --}}
            <div>
                @if($book->category)<span class="badge badge-purple">{{ $book->category }}</span>@endif
                <h1 style="font-size:clamp(26px,3.5vw,40px);color:var(--navy);margin:10px 0;">{{ $book->title }}</h1>
                @if($book->author)<p style="font-size:18px;color:#666;margin-bottom:16px;">by {{ $book->author }} {{ $book->countryFlag() }}</p>@endif

                {{-- Metadata grid --}}
                <div class="grid md:grid-2 gap-3 mb-6" style="padding:18px;background:var(--surface);border-radius:14px;">
                    @if($book->country)<div><span class="label">Country</span><div style="font-weight:600;color:var(--navy);">{{ $book->countryFlag() }} {{ $book->country }}</div></div>@endif
                    @if($book->age_group)<div><span class="label">Age</span><div style="font-weight:600;color:var(--navy);">Ages {{ $book->age_group }}</div></div>@endif
                    @if($book->genre)<div><span class="label">Genre</span><div style="font-weight:600;color:var(--navy);">{{ $book->genre }}</div></div>@endif
                    @if($book->reading_level)<div><span class="label">Reading Level</span><div style="font-weight:600;color:var(--navy);">{{ $book->reading_level }}</div></div>@endif
                    @if($book->reading_time)<div><span class="label">Reading Time</span><div style="font-weight:600;color:var(--navy);">{{ $book->reading_time }}</div></div>@endif
                    @if($book->themes)<div><span class="label">Themes</span><div style="font-weight:600;color:var(--navy);">{{ $book->themes }}</div></div>@endif
                </div>

                @if($book->about)
                    <h3 style="font-size:20px;color:var(--navy);margin-bottom:8px;">About the Book</h3>
                    <p style="color:#555;line-height:1.8;margin-bottom:20px;">{{ $book->about }}</p>
                @endif
                @if($book->what_children_learn)
                    <h3 style="font-size:20px;color:var(--navy);margin-bottom:8px;">What Children Will Learn</h3>
                    <p style="color:#555;line-height:1.8;margin-bottom:20px;">{{ $book->what_children_learn }}</p>
                @endif
                @if($book->why_recommend)
                    <h3 style="font-size:20px;color:var(--navy);margin-bottom:8px;">Why We Recommend It</h3>
                    <p style="color:#555;line-height:1.8;margin-bottom:20px;">{{ $book->why_recommend }}</p>
                @endif
                @if($book->where_to_find)
                    <div style="padding:16px;background:#F0FFF4;border-left:4px solid #7ED321;border-radius:10px;margin-bottom:20px;">
                        <h4 style="color:var(--navy);margin-bottom:6px;">📍 Where to Find This Book</h4>
                        <p style="color:#555;line-height:1.7;font-size:14px;">{{ $book->where_to_find }}</p>
                    </div>
                @endif

                <p style="font-size:12px;color:#999;font-style:italic;">Auntie Kash Kids does not host copyrighted books. We introduce quality literature and direct families to legal sources to borrow or purchase.</p>
            </div>
        </div>

        {{-- Related --}}
        @if($related->count())
            <div style="margin-top:48px;">
                <h2 style="font-size:24px;color:var(--navy);margin-bottom:20px;">Related Books</h2>
                <div class="grid md:grid-4 gap-6">
                    @foreach($related as $b)
                        @include('partials.book-card', ['book' => $b])
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
