@extends('layouts.app')
@section('title', 'Shop')
@section('content')
<section class="hero-bg" style="padding:50px 0;">
    <div class="container text-center">
        <span class="welcome-pill">OUR SHOP</span>
        <h1 style="font-size:clamp(30px,4vw,46px);color:var(--navy);margin:12px 0;">Auntie Kash Kids Shop</h1>
        <p style="color:#555;font-size:18px;max-width:560px;margin:0 auto;">Branded merchandise, recommended books, and goodies for young learners.</p>
    </div>
</section>
<section class="section">
    <div class="container">
        @if($products->count())
        <div class="grid md:grid-3 gap-6" id="shopGrid">
            @foreach($products as $p)
                <div class="card shop-card" data-reveal
                     data-name="{{ $p->name }}" data-type="{{ $p->typeLabel() }}"
                     data-price="{{ number_format($p->price,2) }}" data-currency="{{ $p->currency }}"
                     data-image="{{ $p->image_url ?: '' }}" data-desc="{{ e($p->description) }}"
                     data-url="{{ $p->buyUrl() }}">
                    <div style="aspect-ratio:1;border-radius:14px;overflow:hidden;background:var(--surface);margin-bottom:14px;display:flex;align-items:center;justify-content:center;">
                        @if($p->image_url)<img src="{{ $p->image_url }}" alt="{{ $p->name }}" style="width:100%;height:100%;object-fit:cover;">
                        @else<span style="font-size:48px;">{{ $p->type=='BOOK'?'📚':'🎁' }}</span>@endif
                    </div>
                    <span class="badge badge-purple" style="font-size:11px;">{{ $p->typeLabel() }}</span>
                    <h3 style="font-size:17px;color:var(--navy);margin:8px 0 4px;">{{ $p->name }}</h3>
                    <p style="font-size:18px;font-weight:700;color:var(--pink);margin-bottom:12px;">{{ $p->currency }} {{ number_format($p->price,2) }}</p>
                    <button onclick="openProduct(this.closest('.shop-card'))" class="btn-secondary w-full" style="min-height:42px;">View Details</button>
                </div>
            @endforeach
        </div>
        @else
        <p class="text-center text-muted" style="padding:60px 0;">Our shop is being stocked — check back soon! 🛍️</p>
        @endif
    </div>
</section>

{{-- Popup (modal) product detail — opens instantly, no page load --}}
<div id="productModal" class="shop-modal" onclick="if(event.target===this)closeProduct()">
    <div class="shop-modal-inner">
        <button class="shop-modal-close" onclick="closeProduct()">×</button>
        <div class="grid md:grid-2 gap-6">
            <div id="mImg" style="aspect-ratio:1;border-radius:16px;overflow:hidden;background:var(--surface);display:flex;align-items:center;justify-content:center;"></div>
            <div>
                <span class="badge badge-purple" id="mType" style="font-size:11px;"></span>
                <h2 id="mName" style="font-size:26px;color:var(--navy);margin:10px 0;"></h2>
                <p id="mPrice" style="font-size:24px;font-weight:700;color:var(--pink);margin-bottom:16px;"></p>
                <p id="mDesc" style="font-size:15px;color:#555;line-height:1.7;margin-bottom:24px;"></p>
                <a id="mBuy" href="#" target="_blank" class="btn-pink w-full" style="min-height:48px;">Buy Now →</a>
            </div>
        </div>
    </div>
</div>
<style>
.shop-modal { display:none; position:fixed; inset:0; background:rgba(29,27,78,0.55); backdrop-filter:blur(4px); z-index:200; align-items:center; justify-content:center; padding:20px; animation:fadeIn 200ms; }
.shop-modal.open { display:flex; }
.shop-modal-inner { background:#fff; border-radius:24px; padding:32px; max-width:760px; width:100%; max-height:90vh; overflow:auto; position:relative; box-shadow:0 30px 80px rgba(0,0,0,0.3); animation:popIn 240ms; }
.shop-modal-close { position:absolute; top:16px; right:20px; background:var(--surface); border:none; width:38px; height:38px; border-radius:50%; font-size:24px; cursor:pointer; color:var(--navy); line-height:1; }
@keyframes popIn { from{transform:scale(0.94);opacity:0;} to{transform:scale(1);opacity:1;} }
</style>
<script>
function openProduct(card){
    const d = card.dataset;
    document.getElementById('mName').textContent = d.name;
    document.getElementById('mType').textContent = d.type;
    document.getElementById('mPrice').textContent = d.currency + ' ' + d.price;
    document.getElementById('mDesc').textContent = d.desc || 'No description available.';
    const img = document.getElementById('mImg');
    img.innerHTML = d.image ? `<img src="${d.image}" style="width:100%;height:100%;object-fit:cover;">` : `<span style="font-size:64px;">${d.type==='Book'?'📚':'🎁'}</span>`;
    const buy = document.getElementById('mBuy');
    if (d.url && d.url !== '#') { buy.href = d.url; buy.style.display='block'; buy.textContent = d.type==='Book' ? 'View on Amazon →' : 'Buy Now →'; }
    else { buy.href='{{ route('contact') }}'; buy.textContent='Enquire to Buy →'; }
    document.getElementById('productModal').classList.add('open');
}
function closeProduct(){ document.getElementById('productModal').classList.remove('open'); }
document.addEventListener('keydown', e => { if(e.key==='Escape') closeProduct(); });
</script>
@endsection
