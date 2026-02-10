<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AppReview extends Model
{
    protected $fillable = [
        'user_id',
        'rating',
        'review',
        'is_anonymous',
        'is_show',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
