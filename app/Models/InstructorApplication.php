<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class InstructorApplication extends Model
{
    protected $fillable = ['name','email','phone','country','qualifications','experience','subjects','cv_path','cover_note','status'];
}
