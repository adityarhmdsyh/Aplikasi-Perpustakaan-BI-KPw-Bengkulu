<?php

namespace App\Models;

use App\Models\User;
use App\Models\Borrow;
use Illuminate\Database\Eloquent\Model;

class BorrowExtension extends Model
{
    protected $fillable = [
        'borrow_id',
        'requested_due_date',
        'approved_due_date',
        'status',
        'approved_by',
    ];

    public function borrow()
    {
        return $this->belongsTo(Borrow::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
