<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ShopProduct;

class ShopController extends Controller
{
    /** Public shop page. */
    public function index()
    {
        $products = ShopProduct::active()->orderBy('sort_order')->orderBy('name')->get();
        return view('pages.shop', compact('products'));
    }

    /** CEO/Admin management screen. */
    public function manage()
    {
        $products = ShopProduct::orderBy('sort_order')->orderBy('name')->get();
        return view('dashboard.shop-manager', compact('products'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->canManageContent()) abort(403);

        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
            'type'        => ['required','in:MERCH,BOOK,OTHER'],
            'price'       => ['required','numeric','min:0'],
            'external_url'=> ['nullable','url'],
            'image'       => ['nullable','image','max:5120'],
            'is_featured' => ['nullable','boolean'],
        ]);

        $imageUrl = null;
        if ($request->hasFile('image')) {
            $imageUrl = '/storage/'.$request->file('image')->store('shop', 'public');
        }

        ShopProduct::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'type'        => $data['type'],
            'price'       => $data['price'],
            'external_url'=> $data['external_url'] ?? null,
            'image_url'   => $imageUrl,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => true,
        ]);

        return back()->with('success', 'Product added to the shop.');
    }

    public function update(Request $request, ShopProduct $product)
    {
        if (! Auth::user()->canManageContent()) abort(403);

        $data = $request->validate([
            'name'        => ['required','string','max:255'],
            'description' => ['nullable','string','max:1000'],
            'type'        => ['required','in:MERCH,BOOK,OTHER'],
            'price'       => ['required','numeric','min:0'],
            'external_url'=> ['nullable','url'],
            'image'       => ['nullable','image','max:5120'],
            'is_featured' => ['nullable','boolean'],
            'is_active'   => ['nullable','boolean'],
        ]);

        $imageUrl = $product->image_url;
        if ($request->hasFile('image')) {
            $imageUrl = '/storage/'.$request->file('image')->store('shop', 'public');
        }

        $product->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? $product->description,
            'type'        => $data['type'],
            'price'       => $data['price'],
            'external_url'=> $data['external_url'] ?? null,
            'image_url'   => $imageUrl,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active'),
        ]);

        return back()->with('success', 'Product updated.');
    }

    public function destroy(ShopProduct $product)
    {
        if (! Auth::user()->canManageContent()) abort(403);
        $product->delete();
        return back()->with('success', 'Product removed.');
    }
}
