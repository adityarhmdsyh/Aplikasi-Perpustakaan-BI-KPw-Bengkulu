<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\BookRequest;
use Illuminate\Database\Seeder;

class BookRequestSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'user')->take(5)->get()->each(function ($user) {
            BookRequest::create([
                'user_id' => $user->id,
                'book_title' => 'Buku Usulan ' . fake()->word(),
                'reason' => 'Referensi akademik'
            ]);
        });
    }
}
