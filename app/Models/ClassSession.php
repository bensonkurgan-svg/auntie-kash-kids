<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSession extends Model
{
    protected $fillable = ['course_id','tutor_id','student_id','scheduled_at','status','duration_minutes','notes','meeting_platform','meeting_link'];
    protected $casts = ['scheduled_at' => 'datetime'];

    public function course():  BelongsTo { return $this->belongsTo(Course::class); }
    public function tutor():   BelongsTo { return $this->belongsTo(User::class, 'tutor_id'); }
    public function student(): BelongsTo { return $this->belongsTo(Student::class); }
}
