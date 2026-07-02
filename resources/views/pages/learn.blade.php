@extends('layouts.app')
@section('title', $enrollment->course->title)
@section('content')
<section class="section" style="background:var(--surface);min-height:80vh;">
    <div class="container">
        <a href="{{ Auth::user()->dashboardRoute() }}" style="color:var(--purple);font-weight:600;font-size:14px;">← Back to Dashboard</a>
        <div class="grid lg:grid-3 gap-6 mt-4">
            {{-- Lesson list --}}
            <div>
                <div class="card">
                    <h3 style="color:var(--navy);margin-bottom:6px;">{{ $enrollment->course->icon }} {{ $enrollment->course->title }}</h3>
                    <div class="prog-wrap mb-4"><span class="mini-prog" style="flex:1;"><span style="width:{{ $enrollment->progress }}%;"></span></span><span class="prog-num">{{ round($enrollment->progress) }}%</span></div>
                    @foreach($enrollment->course->modules as $module)
                        <div style="margin-bottom:14px;">
                            <div style="font-weight:700;font-size:13px;color:var(--muted);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:6px;">{{ $module->title }}</div>
                            @foreach($module->lessons as $lsn)
                                <a href="{{ route('learn', [$enrollment->id, $lsn->id]) }}" class="flex items-center gap-2" style="padding:8px 10px;border-radius:9px;font-size:14px;{{ $current && $current->id==$lsn->id ? 'background:#F0E8FF;color:var(--purple);font-weight:700;' : 'color:#444;' }}">
                                    <span>{{ in_array($lsn->id,$completedIds) ? '✅' : '⭕' }}</span> {{ $lsn->title }}
                                </a>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            {{-- Lesson content --}}
            <div style="grid-column:span 2;">
                <div class="card" style="min-height:400px;">
                    @if($current)
                        <span class="badge badge-purple mb-2" style="display:inline-block;">Now Playing</span>
                        <h2 style="font-size:26px;color:var(--navy);margin-bottom:16px;">{{ $current->title }}</h2>
                        @if($current->video_url)
                            <div style="aspect-ratio:16/9;background:#000;border-radius:12px;margin-bottom:16px;overflow:hidden;">
                                <iframe src="{{ $current->video_url }}" style="width:100%;height:100%;border:0;" allowfullscreen></iframe>
                            </div>
                        @endif
                        <div style="font-size:16px;line-height:1.8;color:#333;">{!! $current->content !!}</div>

                        {{-- Approved course materials --}}
                        @php $mats = $enrollment->course->materials->where('status','APPROVED'); @endphp
                        @if($mats->count())
                            <div style="margin-top:24px;padding-top:20px;border-top:1px solid var(--border);">
                                <h4 style="color:var(--navy);margin-bottom:10px;">📎 Course Materials</h4>
                                @foreach($mats as $mat)
                                    <a href="{{ $mat->downloadUrl() }}" target="_blank" class="flex items-center gap-2" style="padding:8px 0;font-size:14px;color:var(--purple);font-weight:600;">
                                        {{ $mat->type=='VIDEO_LINK'?'🎬':'📄' }} {{ $mat->title }}
                                    </a>
                                @endforeach
                            </div>
                        @endif

                        <button onclick="markComplete({{ $current->id }}, {{ $enrollment->id }})" class="btn-pink mt-4" style="margin-top:24px;">✓ Mark Lesson Complete</button>
                    @else
                        <p class="text-muted text-center" style="padding:60px 0;">No lessons available yet for this program.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<script>
function markComplete(lessonId, enrollmentId){
    fetch('{{ route('lessons.complete') }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({lesson_id: lessonId, enrollment_id: enrollmentId})
    }).then(r=>r.json()).then(d=>{ if(d.success){ location.reload(); } });
}
</script>
@endsection
