<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['email','password','name','role','avatar_url',
        'must_change_password','is_active','phone','work_email','photo_url'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['locked_until' => 'datetime', 'email_verified_at' => 'datetime',
        'must_change_password' => 'boolean', 'is_active' => 'boolean'];

    public function isCEO():    bool { return $this->role === 'CEO'; }
    public function isAdmin():  bool { return $this->role === 'ADMIN'; }
    public function isTutor():  bool { return $this->role === 'TUTOR'; }
    public function isParent(): bool { return $this->role === 'PARENT'; }
    public function isStudent():bool { return $this->role === 'STUDENT'; }
    public function canManageContent(): bool { return in_array($this->role, ['CEO','ADMIN']); }

    public function tutorProfile(): HasOne  { return $this->hasOne(TutorProfile::class); }
    public function students():     HasMany { return $this->hasMany(Student::class, 'parent_id'); }
    public function enrollments():  HasMany { return $this->hasMany(Enrollment::class); }
    public function reviews():      HasMany { return $this->hasMany(Review::class); }
    public function cmsPages():     HasMany { return $this->hasMany(CmsPage::class, 'created_by'); }
    public function cmsRequests():  HasMany { return $this->hasMany(CmsChangeRequest::class, 'requested_by'); }
    public function contentPosts(): HasMany { return $this->hasMany(ContentPost::class, 'author_id'); }

    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function photo(): string
    {
        return $this->photo_url ?: ($this->avatar_url ?: '');
    }

    public function dashboardRoute(): string
    {
        return match($this->role) {
            'CEO'    => '/dashboard/ceo',
            'ADMIN'  => '/dashboard/admin',
            'TUTOR'  => '/dashboard/tutor',
            'PARENT' => '/dashboard/parent',
            default  => '/dashboard/student',
        };
    }
}
