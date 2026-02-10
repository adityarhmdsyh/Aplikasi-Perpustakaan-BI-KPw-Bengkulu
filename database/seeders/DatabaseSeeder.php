<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\BookSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BorrowSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\AppReviewSeeder;
use Database\Seeders\BookRequestSeeder;
use Database\Seeders\BorrowDetailSeeder;
use Database\Seeders\NotificationSeeder;
use Database\Seeders\BorrowExtensionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
   public function run(): void
{
    $this->call([
        UserSeeder::class,
        CategorySeeder::class,
        BookSeeder::class,
        BorrowSeeder::class,
        BorrowDetailSeeder::class,
        BorrowExtensionSeeder::class,
        BookRequestSeeder::class,
        AppReviewSeeder::class,
        NotificationSeeder::class,
    ]);
}

}
