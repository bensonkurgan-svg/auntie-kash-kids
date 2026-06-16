<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class CmsChangeRequest extends Model {
    protected $table = 'cms_change_requests';
    protected $fillable = ['page_key','changes','status','requested_by','reviewed_by','review_notes','reviewed_at'];
    protected $casts = ['changes' => 'array', 'reviewed_at' => 'datetime'];
    public function requester(): BelongsTo { return $this->belongsTo(User::class, 'requested_by'); }
    public function reviewer():  BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }
}
