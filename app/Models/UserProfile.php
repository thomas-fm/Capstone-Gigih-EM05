<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'fullname',
        'description',
        'city',
        'province',
        'email',
        'phone',
        'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function user_documents()
    {
        return $this->hasMany(UserDocument::class);
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function job_applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
