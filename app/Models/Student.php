<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Student extends Model {
    protected $fillable = ['parent_id','name','age'];
    public function parent():      BelongsTo { return $this->belongsTo(User::class, 'parent_id'); }
    public function enrollments(): HasMany   { return $this->hasMany(Enrollment::class); }
}
