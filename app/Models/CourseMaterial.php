<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseMaterial extends Model
{
    protected $fillable = [
        'course_id','module_id','title','description','type',
        'file_path','external_url','status','review_note',
        'submitted_by','reviewed_by','reviewed_at',
    ];
    protected $casts = ['reviewed_at' => 'datetime'];

    public function course():    BelongsTo { return $this->belongsTo(Course::class); }
    public function module():    BelongsTo { return $this->belongsTo(Module::class); }
    public function submitter(): BelongsTo { return $this->belongsTo(User::class, 'submitted_by'); }
    public function reviewer():  BelongsTo { return $this->belongsTo(User::class, 'reviewed_by'); }

    public function isApproved():    bool { return $this->status === 'APPROVED'; }
    public function isRejected():    bool { return $this->status === 'REJECTED'; }
    public function isUnderReview(): bool { return $this->status === 'UNDER_REVIEW'; }

    public function statusLabel(): string
    {
        return match($this->status) {
            'APPROVED'     => 'Approved',
            'REJECTED'     => 'Rejected',
            default        => 'Under Review',
        };
    }

    public function downloadUrl(): string
    {
        if ($this->external_url) return $this->external_url;
        if ($this->file_path)    return route('materials.download', $this->id);
        return '#';
    }
}
