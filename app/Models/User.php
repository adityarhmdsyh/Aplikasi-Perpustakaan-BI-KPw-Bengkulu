<?php

namespace App\Models;

use App\Models\Borrow;
use App\Models\AppReview;
use App\Models\BookRequest;
use App\Models\Notification;
use App\Models\BorrowExtension;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'alamat',
        'foto_profile',
        'foto_ktp',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* ================= RELATIONS ================= */

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function approvedBorrows()
    {
        return $this->hasMany(Borrow::class, 'approved_by');
    }

    public function borrowExtensionsApproved()
    {
        return $this->hasMany(BorrowExtension::class, 'approved_by');
    }

    public function bookRequests()
    {
        return $this->hasMany(BookRequest::class);
    }

    public function appReview()
    {
        return $this->hasOne(AppReview::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
