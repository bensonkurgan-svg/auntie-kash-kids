<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CmsPage extends Model {
    protected $table = 'cms_pages';
    protected $fillable = ['page_key','content','status','version','created_by','reviewed_by','approved_at'];
    protected $casts = ['content' => 'array', 'approved_at' => 'datetime'];
    public function creator():  BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function reviewer(): BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
