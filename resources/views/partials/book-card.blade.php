@php $isCompact = $compact ?? false; @endphp
<a href="{{ route('reading.book', $book) }}" class="card" data-reveal style="padding:0;overflow:hidden;display:flex;flex-direction:column;">
    <div style="aspect-ratio:{{ $isCompact ? '3/4' : '3/4' }};background:linear-gradient(135deg,#F0E8FF,#FFF0F8);display:flex;align-items:center;justify-content:center;overflow:hidden;">
        @if($book->cover_image)
            <img src="{{ $book->cover_image }}" alt="{{ $book->title }}" style="width:100%;height:100%;object-fit:cover;">
        @else
            <span style="font-size:{{ $isCompact ? '40px' : '56px' }};">📚</span>
        @endif
    </div>
    <div style="padding:{{ $isCompact ? '10px' : '14px' }};flex:1;">
        <h3 style="font-size:{{ $isCompact ? '13px' : '15px' }};color:var(--navy);line-height:1.3;margin-bottom:4px;">{{ \Illuminate\Support\Str::limit($book->title, $isCompact ? 34 : 50) }}</h3>
        @if(!$isCompact)
            <p style="font-size:12px;color:#888;">{{ $book->author ? 'by '.$book->author : '' }}</p>
            <div style="display:flex;flex-wrap:wrap;gap:4px;margin-top:8px;">
                @if($book->age_group)<span class="badge" style="background:#F0E8FF;color:#7B2FF7;font-size:10px;">Ages {{ $book->age_group }}</span>@endif
                @if($book->country)<span class="badge" style="background:#FFF0F8;color:#FF3E9E;font-size:10px;">{{ $book->countryFlag() }} {{ $book->country }}</span>@endif
            </div>
        @endif
    </div>
</a>
