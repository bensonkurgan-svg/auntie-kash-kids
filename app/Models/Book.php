<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Book extends Model
{
    protected $fillable = [
        'title','slug','author','country','age_group','genre','reading_level',
        'reading_time','category','about','what_children_learn','themes',
        'why_recommend','where_to_find','purchase_url','cover_image',
        'is_featured','is_book_of_month','is_published','created_by',
    ];
    protected $casts = [
        'is_featured' => 'boolean',
        'is_book_of_month' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function ($book) {
            if (empty($book->slug) && $book->title) {
                $base = Str::slug($book->title); $slug = $base; $i = 1;
                while (static::where('slug', $slug)->where('id', '!=', $book->id ?? 0)->exists()) {
                    $slug = $base.'-'.$i++;
                }
                $book->slug = $slug;
            }
        });
    }

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q) { return $q->where('is_published', true); }

    public function countryFlag(): string
    {
        return match(strtolower($this->country ?? '')) {
            'nigeria' => '🇳🇬', 'kenya' => '🇰🇪', 'ghana' => '🇬🇭',
            'south africa' => '🇿🇦', 'canada' => '🇨🇦', 'usa', 'united states' => '🇺🇸',
            'uk', 'united kingdom' => '🇬🇧', 'japan' => '🇯🇵', 'india' => '🇮🇳',
            default => '🌍',
        };
    }
}
