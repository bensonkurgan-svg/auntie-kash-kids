<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $fillable = [
        'title','theme','age_bracket','activity_type','description',
        'image_path','file_path','is_published','sort_order','created_by',
    ];
    protected $casts = ['is_published' => 'boolean'];

    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function scopePublished($q) { return $q->where('is_published', true); }

    public function downloadUrl(): string
    {
        return $this->file_path ? route('activities.download', $this->id) : '#';
    }

    public function themeIcon(): string
    {
        return match(strtolower($this->theme ?? '')) {
            'animals'   => '🐾', 'pirates' => '🏴‍☠️', 'grammar' => '📝',
            'financial literacy', 'money' => '💰', 'space' => '🚀',
            'nature'    => '🌿', 'ocean' => '🌊', 'food' => '🍎',
            default     => '🎨',
        };
    }

    public function typeLabel(): string
    {
        return match($this->activity_type) {
            'WORD_SEARCH'     => 'Word Search',
            'CROSSWORD'       => 'Crossword',
            'MATCHING'        => 'Matching Game',
            'MAZE'            => 'Maze',
            'I_SPY'           => 'I Spy',
            'SPOT_DIFFERENCE' => 'Spot the Difference',
            'COLOURING'       => 'Colouring',
            'WORKSHEET'       => 'Worksheet',
            'OTHER'           => 'Other',
            default           => $this->activity_type ?: '—',
        };
    }
}
