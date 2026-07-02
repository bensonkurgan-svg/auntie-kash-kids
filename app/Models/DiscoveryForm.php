<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class DiscoveryForm extends Model {
    protected $fillable = [
        'parent_name','parent_email','parent_phone','parent_country','parent_city','preferred_contact',
        'child_name','child_age','child_grade','child_country','primary_language',
        'interests','strengths','skills_to_develop','learning_preferences',
        'parent_goals','preferred_days','preferred_time','time_zone',
        'wants_recommendation','hear_about_us','status','assigned_to',
        'assigned_staff_id','assigned_at'
    ];
    protected $casts = [
        'interests' => 'array', 'strengths' => 'array',
        'skills_to_develop' => 'array', 'learning_preferences' => 'array',
        'preferred_days' => 'array', 'wants_recommendation' => 'boolean',
        'assigned_at' => 'datetime'
    ];

    public function assignedStaff(): BelongsTo { return $this->belongsTo(User::class, 'assigned_staff_id'); }
}
