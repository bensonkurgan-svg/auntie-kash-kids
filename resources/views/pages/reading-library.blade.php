@extends('layouts.app')
@section('title', 'Reading Library')
@section('content')
@php $catMeta = [
    'Nigerian Classics' => ['🇳🇬','#7ED321'], 'African Classics' => ['🌍','#E67E22'],
    'World Classics' => ['🌎','#2AA7FF'], 'Folktales' => ['📖','#7B2FF7'],
    'Biographies' => ['👤','#FF3E9E'], 'STEM & Science' => ['🔬','#1ABC9C'], 'Bible Stories' => ['✝️','#F5A623'],
]; @endphp

<section class="hero-bg" style="padding:56px 0;">
    <div class="container text-center">
        <span class="welcome-pill">📚 READING LIBRARY</span>
        <h1 style="font-size:clamp(30px,4.5vw,52px);color:var(--navy);margin:12px 0;">Auntie Kash Kids Reading Library</h1>
        <p style="color:#555;font-size:19px;max-width:640px;margin:0 auto 24px;">Discover inspiring books from around the world. Read. Learn. Imagine. Grow.</p>
        <form method="GET" style="max-width:520px;margin:0 auto;display:flex;gap:8px;">
            <input type="text" name="q" value="{{ $search }}" class="input" placeholder="Search by title, author, genre, or theme…" style="flex:1;">
            <button class="btn-pink" style="min-height:48px;">Search</button>
        </form>
    </div>
</section>

{{-- Filter results view --}}
@if($isFiltering)
<section class="section">
    <div class="container">
        <div class="flex items-center justify-between mb-6" style="flex-wrap:wrap;gap:12px;">
            <h2 style="font-size:24px;color:var(--navy);">{{ $books->count() }} book(s) found</h2>
            <a href="{{ route('reading.library') }}" class="btn-secondary" style="min-height:42px;">← Back to Library</a>
        </div>
        @if($books->count())
            <div class="grid md:grid-3 lg:grid-4 gap-6">
                @foreach($books as $book)
                    @include('partials.book-card', ['book' => $book])
                @endforeach
            </div>
        @else
            <p class="text-center text-muted" style="padding:50px;">No books match your search. Try different terms or <a href="{{ route('reading.library') }}" style="color:var(--purple);">browse all books</a>.</p>
        @endif
    </div>
</section>
@else

{{-- Book of the Month --}}
@if($bookOfMonth)
<section class="section" style="padding-bottom:0;">
    <div class="container">
        <div class="card" style="background:linear-gradient(135deg,#FFF6FB,#F4F0FF);border:2px solid #F0D9F5;">
            <div class="grid md:grid-2 gap-8 items-center">
                <div class="text-center">
                    @if($bookOfMonth->cover_image)
                        <img src="{{ $bookOfMonth->cover_image }}" alt="{{ $bookOfMonth->title }}" style="max-height:280px;border-radius:12px;box-shadow:0 12px 30px rgba(80,60,160,0.25);">
                    @else
                        <div style="height:220px;display:flex;align-items:center;justify-content:center;font-size:72px;background:#fff;border-radius:12px;">📕</div>
                    @endif
                </div>
                <div>
                    <span class="badge badge-pink">⭐ Book of the Month</span>
                    <h2 style="font-size:30px;color:var(--navy);margin:10px 0;">{{ $bookOfMonth->title }}</h2>
                    @if($bookOfMonth->author)<p style="color:#666;font-size:16px;">by {{ $bookOfMonth->author }} {{ $bookOfMonth->countryFlag() }}</p>@endif
                    <p style="color:#555;line-height:1.7;margin:14px 0;">{{ \Illuminate\Support\Str::limit($bookOfMonth->about, 220) }}</p>
                    <a href="{{ route('reading.book', $bookOfMonth) }}" class="btn-purple">Read More →</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

{{-- Category cards --}}
<section class="section">
    <div class="container">
        <h2 class="text-center" style="font-size:28px;color:var(--navy);margin-bottom:8px;">Browse by Category</h2>
        <p class="text-center text-muted" style="margin-bottom:32px;">Explore books from Nigeria, Africa, and around the world.</p>
        <div class="grid md:grid-2 lg:grid-4 gap-5">
            @foreach($catMeta as $cat => $meta)
                <a href="{{ route('reading.library', ['category' => $cat]) }}" class="card text-center" data-reveal style="border-top:4px solid {{ $meta[1] }};">
                    <div style="font-size:44px;margin-bottom:10px;">{{ $meta[0] }}</div>
                    <h3 style="font-size:17px;color:var(--navy);">{{ $cat }}</h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- Browse by age --}}
<section class="section" style="background:var(--surface);padding-top:40px;padding-bottom:40px;">
    <div class="container">
        <h2 class="text-center" style="font-size:26px;color:var(--navy);margin-bottom:24px;">📚 Books by Age</h2>
        <div class="grid md:grid-4 gap-4" style="max-width:720px;margin:0 auto;">
            @foreach(['3-5'=>'Ages 3–5','6-8'=>'Ages 6–8','9-12'=>'Ages 9–12','13-18'=>'Ages 13–18'] as $ag => $label)
                <a href="{{ route('reading.library', ['age' => $ag]) }}" class="btn-white text-center" style="padding:18px;font-weight:700;color:var(--purple);">{{ $label }}</a>
            @endforeach
        </div>
    </div>
</section>

{{-- Recently added --}}
@if($recent->count())
<section class="section">
    <div class="container">
        <h2 style="font-size:26px;color:var(--navy);margin-bottom:24px;">🆕 Recently Added</h2>
        <div class="grid md:grid-3 lg:grid-6 gap-4">
            @foreach($recent as $book)
                @include('partials.book-card', ['book' => $book, 'compact' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Reading Challenge banner --}}
<section class="section" style="padding-top:0;">
    <div class="container">
        <div class="card text-center" style="background:linear-gradient(135deg,#7B2FF7,#2AA7FF);padding:44px;">
            <div style="font-size:52px;margin-bottom:12px;">🌍</div>
            <h2 class="text-white" style="font-size:28px;margin-bottom:8px;">Read Around the World Challenge</h2>
            <p style="color:rgba(255,255,255,0.9);max-width:520px;margin:0 auto 20px;">Read books from different countries, earn stamps on your digital passport, and unlock certificates and badges. Coming soon!</p>
            <span class="btn-white" style="cursor:default;opacity:0.85;">🎫 Passport Coming Soon</span>
        </div>
    </div>
</section>
@endif
@endsection
