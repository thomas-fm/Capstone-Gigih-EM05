<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'grade',
        'progress',
        'due',
        'expired',
    ];

    public function user_profile() {
        return $this->belongsTo(UserProfile::class);
    }

    public function course() {
        return $this->belongsTo(Course::class);
    }

}
