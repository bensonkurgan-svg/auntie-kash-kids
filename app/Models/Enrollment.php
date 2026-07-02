<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Enrollment extends Model {
    protected $fillable = ['student_id','course_id','user_id','status','progress','stripe_payment_id','last_lesson_id'];
    protected $casts = ['progress' => 'float'];
    public function student():     BelongsTo { return $this->belongsTo(Student::class); }
    public function course():      BelongsTo { return $this->belongsTo(Course::class); }
    public function user():        BelongsTo { return $this->belongsTo(User::class); }
    public function completions(): HasMany   { return $this->hasMany(LessonCompletion::class); }
    public function lastLesson():  BelongsTo { return $this->belongsTo(Lesson::class, 'last_lesson_id'); }
}
