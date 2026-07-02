<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ContentPost extends Model {
    protected $fillable = ['type','title','slug','excerpt','body','cover_image','tags','status',
        'file_url','file_type','age_range','read_time','category','featured',
        'author_id','reviewed_by','published_at'];
    protected $casts = ['tags' => 'array', 'featured' => 'boolean', 'published_at' => 'datetime'];
    protected $attributes = ['tags' => '[]'];
    public function author():   BelongsTo { return $this->belongsTo(User::class, 'author_id'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
