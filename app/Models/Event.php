<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = ['title','description','type','starts_at','ends_at','location','image_url','is_public','created_by'];
    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'is_public' => 'boolean'];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopeUpcoming($q) { return $q->where('starts_at', '>=', now()->startOfDay()); }
    public function scopePublic($q)   { return $q->where('is_public', true); }

    public function typeLabel(): string
    {
        return match($this->type) {
            'WORKSHOP'      => 'Workshop',
            'HOLIDAY'       => 'Holiday',
            'ANNOUNCEMENT'  => 'Announcement',
            default         => 'Special Event',
        };
    }
    public function typeColor(): string
    {
        return match($this->type) {
            'WORKSHOP'     => '#2AA7FF',
            'HOLIDAY'      => '#7ED321',
            'ANNOUNCEMENT' => '#E67E22',
            default        => '#7B2FF7',
        };
    }
}
