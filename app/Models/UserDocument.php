<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'url'
    ];

    public function user_profile() {
        return $this->belongsTo(UserProfile::class);
    }
}
