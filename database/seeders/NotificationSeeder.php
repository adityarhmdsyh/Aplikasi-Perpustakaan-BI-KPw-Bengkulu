<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        User::all()->each(function ($user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => 'Peminjaman Berhasil',
                'message' => 'Buku berhasil dipinjam',
                'type' => 'borrow'
            ]);
        });
    }
}
