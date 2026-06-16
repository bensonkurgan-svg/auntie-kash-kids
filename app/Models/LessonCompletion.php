<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LessonCompletion extends Model {
    public $timestamps = false;
    protected $fillable = ['lesson_id','enrollment_id','completed_at'];
    protected $casts = ['completed_at' => 'datetime'];
    public function lesson():     BelongsTo { return $this->belongsTo(Lesson::class); }
    public function enrollment(): BelongsTo { return $this->belongsTo(Enrollment::class); }
}
