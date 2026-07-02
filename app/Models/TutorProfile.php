<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class TutorProfile extends Model {
    protected $fillable = ['user_id','bio','specialties','video_url','rating','review_count','phone','work_email','photo_url','qualifications','country','availability'];
    protected $casts = ['specialties' => 'array', 'rating' => 'float'];
    protected $attributes = ['specialties' => '[]'];
    public function user():    BelongsTo { return $this->belongsTo(User::class); }
    public function courses(): HasMany   { return $this->hasMany(Course::class); }
    public function reviews(): HasMany   { return $this->hasMany(Review::class); }
}
