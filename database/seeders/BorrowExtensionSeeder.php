<?php

namespace Database\Seeders;

use App\Models\Borrow;
use App\Models\BorrowExtension;
use Illuminate\Database\Seeder;

class BorrowExtensionSeeder extends Seeder
{
    public function run(): void
    {
        Borrow::take(3)->get()->each(function ($borrow) {
            BorrowExtension::create([
                'borrow_id' => $borrow->id,
                'requested_due_date' => now()->addDays(14),
                'status' => 'pending'
            ]);
        });
    }
}

