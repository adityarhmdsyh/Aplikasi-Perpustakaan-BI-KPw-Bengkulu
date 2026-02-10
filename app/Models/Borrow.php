<?php

namespace App\Models;

use App\Models\User;
use App\Models\BorrowDetail;
use App\Models\BorrowExtension;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    protected $fillable = [
        'user_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'approved_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details()
    {
        return $this->hasMany(BorrowDetail::class);
    }

    public function extensions()
    {
        return $this->hasMany(BorrowExtension::class);
    }
}
