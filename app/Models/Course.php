<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Course extends Model {
    protected $fillable = ['slug','title','description','icon','image_url','price','tutor_profile_id','is_published','meeting_platform','meeting_link','meeting_schedule'];
    protected $casts = ['is_published' => 'boolean', 'price' => 'float'];
    public function tutorProfile(): BelongsTo { return $this->belongsTo(TutorProfile::class); }
    public function modules():      HasMany   { return $this->hasMany(Module::class)->orderBy('order'); }
    public function enrollments():  HasMany   { return $this->hasMany(Enrollment::class); }
    public function materials():    HasMany   { return $this->hasMany(CourseMaterial::class); }
    public function getLessonCountAttribute(): int {
        return $this->modules->sum(fn($m) => $m->lessons->count());
    }
}
