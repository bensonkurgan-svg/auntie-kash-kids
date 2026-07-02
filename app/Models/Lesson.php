<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Lesson extends Model {
    protected $fillable = ['module_id','title','content','video_url','order'];
    public function module():      BelongsTo { return $this->belongsTo(Module::class); }
    public function completions(): HasMany   { return $this->hasMany(LessonCompletion::class); }
}
