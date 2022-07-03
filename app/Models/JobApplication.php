<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'status'
    ];

    public function user_profile()
    {
        return $this->belongsTo(UserProfile::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function skill_assesment()
    {
        return $this->belongsTo(SkillAssesment::class);
    }

    public function enrollments()
    {
        return $this->belongsToMany(Enrollment::class, 'application_courses');
    }

    public function user_documents()
    {
        return $this->belongsToMany(UserDocument::class, 'application_documents');
    }
}
