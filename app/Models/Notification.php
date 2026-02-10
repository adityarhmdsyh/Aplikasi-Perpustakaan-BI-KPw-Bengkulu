<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const TYPE_BORROW = 'borrow';
    const TYPE_RETURN = 'return';
    const TYPE_LATE = 'late';
    const TYPE_INFO = 'info';
    const TYPE_ANNOUNCEMENT = 'announcement';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
