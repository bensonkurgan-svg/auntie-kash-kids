<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Student extends Model {
    protected $fillable = ['parent_id','name','age','assigned_tutor_id','grade_level','emergency_contact_name','emergency_contact_phone','emergency_contact_relationship','medical_notes'];
    public function parent():      BelongsTo { return $this->belongsTo(User::class, 'parent_id'); }
    public function enrollments(): HasMany   { return $this->hasMany(Enrollment::class); }
    public function assignedTutor(): BelongsTo { return $this->belongsTo(TutorProfile::class, 'assigned_tutor_id'); }
}
