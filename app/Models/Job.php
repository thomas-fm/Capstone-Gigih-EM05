<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'position', 'type', 'description',
        'isRemote', 'city', 'province',
        'duration', 'minSalary', 'maxSalary',
        'expired', 'active', 'courseRequirement'
    ];

    public function company_profiles()
    {
        return $this->hasOne(CompanyProfile::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, JobCategory::class);
    }
}
