@extends('layouts.dashboard')
@section('title', 'Shop')
@section('content')
<div class="dash-header">
    <div><h1 class="dash-title">Shop Manager</h1><p class="dash-sub">Add products, books, and merchandise shown on the public shop & homepage.</p></div>
    <a href="{{ route('shop') }}" target="_blank" class="btn-secondary" style="min-height:42px;">View Shop ↗</a>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">➕ Add Product</div></div>
    <div class="panel-body padded">
        <form method="POST" action="{{ route('shop.store') }}" enctype="multipart/form-data">@csrf
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Product Name</label><input type="text" name="name" class="input" required></div>
                <div><label class="label">Type</label><select name="type" class="input"><option value="MERCH">Merchandise (T-shirt, mug…)</option><option value="BOOK">Book (Amazon link)</option><option value="OTHER">Other</option></select></div>
            </div>
            <div class="mb-3"><label class="label">Description</label><textarea name="description" class="input" rows="2"></textarea></div>
            <div class="grid md:grid-2 gap-4 mb-3">
                <div><label class="label">Price (USD)</label><input type="number" step="0.01" name="price" class="input" value="0" required></div>
                <div><label class="label">Buy / Amazon Link (optional)</label><input type="url" name="external_url" class="input" placeholder="https://amazon.com/..."></div>
            </div>
            <div class="mb-3"><label class="label">Product Image</label><input type="file" name="image" accept="image/*" class="input"></div>
            <label class="flex items-center gap-2 mb-4" style="font-size:14px;"><input type="checkbox" name="is_featured" value="1"> Feature on homepage</label>
            <button class="btn-primary">Add Product</button>
        </form>
    </div>
</div>

<div class="panel">
    <div class="panel-head"><div class="panel-title">All Products</div><span class="dash-sub">{{ $products->count() }} total</span></div>
    <div class="panel-body" style="overflow-x:auto;">
        <table class="dtable">
            <thead><tr><th>Product</th><th>Type</th><th>Price</th><th>Featured</th><th>Status</th><th style="text-align:right;">Action</th></tr></thead>
            <tbody>
            @forelse($products as $p)
                <tr>
                    <td data-label="Product">
                        <div class="user-cell">
                            @if($p->image_url)<img src="{{ $p->image_url }}" style="width:40px;height:40px;border-radius:9px;object-fit:cover;">@else<span style="font-size:26px;">{{ $p->type=='BOOK'?'📚':'🎁' }}</span>@endif
                            <span class="nm">{{ $p->name }}</span>
                        </div>
                    </td>
                    <td data-label="Type">{{ $p->typeLabel() }}</td>
                    <td data-label="Price" style="font-weight:700;">{{ $p->currency }} {{ number_format($p->price,2) }}</td>
                    <td data-label="Featured">{{ $p->is_featured ? '⭐ Yes' : '—' }}</td>
                    <td data-label="Status"><span class="status-pill {{ $p->is_active ? 'st-ACTIVE' : 'st-DRAFT' }}">{{ $p->is_active ? 'Active' : 'Hidden' }}</span></td>
                    <td data-label="Action" style="text-align:right;">
                        <div class="flex gap-2" style="justify-content:flex-end;">
                            <button type="button" onclick="document.getElementById('edit-prod-{{ $p->id }}').style.display=(document.getElementById('edit-prod-{{ $p->id }}').style.display==='table-row'?'none':'table-row')" class="status-pill st-CONTACTED" style="border:none;cursor:pointer;">✏️ Edit</button>
                            <form method="POST" action="{{ route('shop.destroy',$p) }}" onsubmit="return confirm('Remove this product?');" style="display:inline;">@csrf @method('DELETE')<button class="status-pill" style="border:none;cursor:pointer;background:#FFECEC;color:#c0392b;">Delete</button></form>
                        </div>
                    </td>
                </tr>
                {{-- Inline edit form (hidden until Edit clicked) --}}
                <tr id="edit-prod-{{ $p->id }}" style="display:none;background:#FAFAFE;">
                    <td colspan="6" style="padding:20px 22px;">
                        <form method="POST" action="{{ route('shop.update',$p) }}" enctype="multipart/form-data">@csrf @method('PUT')
                            <div class="grid md:grid-2 gap-4 mb-3">
                                <div><label class="label">Name</label><input type="text" name="name" value="{{ $p->name }}" class="input" required></div>
                                <div><label class="label">Type</label><select name="type" class="input"><option value="MERCH" {{ $p->type=='MERCH'?'selected':'' }}>Merchandise</option><option value="BOOK" {{ $p->type=='BOOK'?'selected':'' }}>Book</option><option value="OTHER" {{ $p->type=='OTHER'?'selected':'' }}>Other</option></select></div>
                            </div>
                            <div class="mb-3"><label class="label">Description</label><textarea name="description" class="input" rows="2">{{ $p->description }}</textarea></div>
                            <div class="grid md:grid-2 gap-4 mb-3">
                                <div><label class="label">Price (USD)</label><input type="number" step="0.01" name="price" value="{{ $p->price }}" class="input" required></div>
                                <div><label class="label">Buy / Amazon Link</label><input type="url" name="external_url" value="{{ $p->external_url }}" class="input"></div>
                            </div>
                            <div class="mb-3">
                                <label class="label">Replace Image (leave empty to keep current)</label>
                                <input type="file" name="image" accept="image/*" class="input">
                                @if($p->image_url)<p style="font-size:12px;color:var(--muted);margin-top:4px;">Current: <a href="{{ $p->image_url }}" target="_blank" style="color:var(--purple);">view image</a></p>@endif
                            </div>
                            <div class="flex gap-4 mb-4">
                                <label class="flex items-center gap-2" style="font-size:14px;"><input type="checkbox" name="is_featured" value="1" {{ $p->is_featured?'checked':'' }}> Feature on homepage</label>
                                <label class="flex items-center gap-2" style="font-size:14px;"><input type="checkbox" name="is_active" value="1" {{ $p->is_active?'checked':'' }}> Active (visible)</label>
                            </div>
                            <button class="btn-primary" style="min-height:42px;">Save Changes</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:30px;">No products yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
