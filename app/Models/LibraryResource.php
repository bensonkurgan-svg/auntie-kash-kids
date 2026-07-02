<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryResource extends Model
{
    protected $fillable = [
        'title','description','category','audience','content_type',
        'body','file_path','external_url','cover_image',
        'is_featured','is_published','created_by',
    ];
    protected $casts = ['is_featured' => 'boolean', 'is_published' => 'boolean'];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q) { return $q->where('is_published', true); }
    public function scopeForInstructors($q) { return $q->where('audience', 'INSTRUCTOR'); }
    public function scopeForParents($q) { return $q->where('audience', 'PARENT'); }

    public function downloadUrl(): string
    {
        if ($this->external_url) return $this->external_url;
        if ($this->file_path)    return route('library.download', $this->id);
        return '#';
    }
    public function typeIcon(): string
    {
        return match($this->content_type) {
            'ARTICLE' => '📄', 'LINK' => '🔗', 'VIDEO' => '🎬', default => '📎',
        };
    }
}
