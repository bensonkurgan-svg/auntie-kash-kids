<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssignmentSubmission extends Model
{
    protected $fillable = ['assignment_id','student_id','response','file_path','status','score','feedback','submitted_at','graded_at'];
    protected $casts = ['submitted_at' => 'datetime', 'graded_at' => 'datetime'];

    public function assignment(): BelongsTo { return $this->belongsTo(Assignment::class); }
    public function student():    BelongsTo { return $this->belongsTo(Student::class); }
}
