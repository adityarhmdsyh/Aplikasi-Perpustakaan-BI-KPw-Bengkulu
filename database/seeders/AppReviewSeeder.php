<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AppReview;
use Illuminate\Database\Seeder;

class AppReviewSeeder extends Seeder
{
    public function run(): void
    {
        User::where('role', 'user')->take(5)->get()->each(function ($user) {
            AppReview::create([
                'user_id' => $user->id,
                'rating' => rand(3, 5),
                'review' => 'Aplikasi sangat membantu',
            ]);
        });
    }
}

