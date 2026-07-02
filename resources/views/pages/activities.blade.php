@extends('layouts.app')
@section('title', 'Printable Activities')
@section('content')
<section class="hero-bg" style="padding:50px 0;">
    <div class="container text-center">
        <span class="welcome-pill">FUN & LEARNING</span>
        <h1 style="font-size:clamp(30px,4vw,46px);color:var(--navy);margin:12px 0;">Printable Activities</h1>
        <p style="color:#555;font-size:18px;max-width:600px;margin:0 auto;">Word searches, crosswords, mazes, matching games and more — free to download and print!</p>
    </div>
</section>

<section class="section">
    <div class="container">
        {{-- Filters --}}
        <div class="panel" style="margin-bottom:24px;">
            <div class="panel-body padded">
                <form method="GET" class="flex gap-3" style="flex-wrap:wrap;align-items:flex-end;">
                    <div style="flex:1;min-width:160px;">
                        <label class="label">Theme</label>
                        <select name="theme" class="input" onchange="this.form.submit()">
                            <option value="">All Themes</option>
                            @foreach($themes as $t)<option value="{{ $t }}" {{ $theme==$t?'selected':'' }}>{{ $t }}</option>@endforeach
                        </select>
                    </div>
                    <div style="flex:1;min-width:160px;">
                        <label class="label">Age Group</label>
                        <select name="age" class="input" onchange="this.form.submit()">
                            <option value="">All Ages</option>
                            @foreach($ages as $a)<option value="{{ $a }}" {{ $age==$a?'selected':'' }}>{{ $a }} years</option>@endforeach
                        </select>
                    </div>
                    @if($theme || $age)<a href="{{ route('activities') }}" class="btn-secondary" style="min-height:44px;">Clear</a>@endif
                </form>
            </div>
        </div>

        @if($activities->count())
        <div class="grid md:grid-3 gap-6">
            @foreach($activities as $act)
                <div class="card" data-reveal
                     data-title="{{ e($act->title) }}" data-desc="{{ e($act->description) }}"
                     data-image="{{ $act->image_path ?: '' }}" data-type="{{ $act->typeLabel() }}"
                     data-download="{{ $act->file_path ? route('activities.download', $act) : '' }}">
                    <div style="aspect-ratio:4/3;border-radius:12px;overflow:hidden;background:var(--surface);margin-bottom:12px;display:flex;align-items:center;justify-content:center;">
                        @if($act->image_path)<img src="{{ $act->image_path }}" alt="{{ $act->title }}" style="width:100%;height:100%;object-fit:cover;">
                        @else<span style="font-size:44px;">🧩</span>@endif
                    </div>
                    <div class="flex gap-2 mb-2" style="flex-wrap:wrap;">
                        <span class="badge badge-purple" style="font-size:11px;">{{ $act->typeLabel() }}</span>
                        @if($act->age_bracket)<span class="badge badge-pink" style="font-size:11px;">{{ $act->age_bracket }} yrs</span>@endif
                    </div>
                    <h3 style="font-size:17px;color:var(--navy);margin-bottom:4px;">{{ $act->title }}</h3>
                    @if($act->theme)<p class="dash-sub" style="font-size:13px;margin-bottom:10px;">Theme: {{ $act->theme }}</p>@endif
                    <div class="flex gap-2">
                        <button onclick="previewActivity(this.closest('.card'))" class="btn-secondary" style="flex:1;min-height:40px;">Preview</button>
                        @if($act->file_path)<a href="{{ route('activities.download', $act) }}" class="btn-pink" style="flex:1;min-height:40px;">⬇️ Download</a>@endif
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-muted" style="padding:60px 0;">No activities yet{{ $theme || $age ? ' for these filters' : '' }}. Check back soon! 🧩</p>
        @endif
    </div>
</section>

{{-- Preview modal --}}
<div id="actModal" class="shop-modal" onclick="if(event.target===this)closeAct()">
    <div class="shop-modal-inner">
        <button class="shop-modal-close" onclick="closeAct()">×</button>
        <div id="actImg" style="border-radius:14px;overflow:hidden;background:var(--surface);margin-bottom:16px;max-height:400px;display:flex;align-items:center;justify-content:center;"></div>
        <span class="badge badge-purple" id="actType" style="font-size:11px;"></span>
        <h2 id="actTitle" style="font-size:24px;color:var(--navy);margin:10px 0;"></h2>
        <p id="actDesc" style="font-size:15px;color:#555;line-height:1.7;margin-bottom:20px;"></p>
        <a id="actDownload" href="#" class="btn-pink w-full" style="display:none;">⬇️ Download PDF</a>
    </div>
</div>
<style>
.shop-modal { display:none; position:fixed; inset:0; background:rgba(29,27,78,0.55); backdrop-filter:blur(4px); z-index:200; align-items:center; justify-content:center; padding:20px; }
.shop-modal.open { display:flex; }
.shop-modal-inner { background:#fff; border-radius:24px; padding:32px; max-width:600px; width:100%; max-height:90vh; overflow:auto; position:relative; box-shadow:0 30px 80px rgba(0,0,0,0.3); }
.shop-modal-close { position:absolute; top:16px; right:20px; background:var(--surface); border:none; width:38px; height:38px; border-radius:50%; font-size:24px; cursor:pointer; color:var(--navy); }
</style>
<script>
function previewActivity(card){
    var d = card.dataset;
    document.getElementById('actTitle').textContent = d.title;
    document.getElementById('actType').textContent = d.type;
    document.getElementById('actDesc').textContent = d.desc || 'No description provided.';
    var img = document.getElementById('actImg');
    img.innerHTML = d.image ? '<img src="'+d.image+'" style="width:100%;object-fit:contain;">' : '<span style="font-size:72px;padding:40px;">🧩</span>';
    var dl = document.getElementById('actDownload');
    if (d.download) { dl.href = d.download; dl.style.display='block'; } else { dl.style.display='none'; }
    document.getElementById('actModal').classList.add('open');
}
function closeAct(){ document.getElementById('actModal').classList.remove('open'); }
document.addEventListener('keydown', e => { if(e.key==='Escape') closeAct(); });
</script>
@endsection
