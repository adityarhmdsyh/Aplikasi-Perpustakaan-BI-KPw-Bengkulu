<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Borrow;
use Illuminate\Database\Seeder;

class BorrowSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            Borrow::create([
                'user_id' => $user->id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(7),
                'status' => 'approved',
                'approved_by' => 1 // admin
            ]);
        }
    }
}

