<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WaitlistEntry extends Model
{
    protected $fillable = ['parent_name','email','phone','child_name','child_age','program_interest','type','message','status'];
}
