<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Review extends Model {
    protected $fillable = ['tutor_profile_id','user_id','rating','comment'];
    public function tutorProfile(): BelongsTo { return $this->belongsTo(TutorProfile::class); }
    public function user():         BelongsTo { return $this->belongsTo(User::class); }
}
