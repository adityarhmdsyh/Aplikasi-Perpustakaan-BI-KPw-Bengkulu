<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Borrow;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BorrowSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {

            $borrowDate = Carbon::now();
            $dueDate = $borrowDate->copy()->addDays(7);

            Borrow::create([
                'user_id' => $user->id,
                'borrow_date' => $borrowDate,
                'original_due_date' => $dueDate,
                'current_due_date' => null,
                'extended_count' => 0,
                'pickup_at' => $borrowDate->copy()->addHour(), // simulasi sudah diambil
                'status' => 'picked_up',
                'approved_by' => 1,
                'late_days' => 0,
                'fine_amount' => 0,
            ]);
        }
    }
}


