<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ShopProduct extends Model
{
    protected $fillable = [
        'name','description','type','price','currency',
        'image_url','external_url','is_featured','is_active','sort_order',
    ];
    protected $casts = [
        'is_featured' => 'boolean',
        'is_active'   => 'boolean',
        'price'       => 'decimal:2',
    ];

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }

    public function typeLabel(): string
    {
        return match($this->type) {
            'BOOK'  => 'Book',
            'OTHER' => 'Item',
            default => 'Merch',
        };
    }

    /** Books link out to Amazon; other items use the internal buy flow/contact. */
    public function buyUrl(): string
    {
        return $this->external_url ?: '#';
    }
}
