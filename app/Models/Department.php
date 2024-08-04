<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    /**
     * Get the user profiles for the department.
     */
    public function userProfiles()
    {
        return $this->hasMany(UserProfile::class);
    }
}
