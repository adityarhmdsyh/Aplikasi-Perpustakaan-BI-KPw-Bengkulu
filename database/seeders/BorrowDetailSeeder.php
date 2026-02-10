<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use Illuminate\Database\Seeder;

class BorrowDetailSeeder extends Seeder
{
    public function run(): void
    {
        $borrows = Borrow::all();
        $books = Book::all();

        foreach ($borrows as $borrow) {
            $books->random(2)->each(function ($book) use ($borrow) {
                BorrowDetail::create([
                    'borrow_id' => $borrow->id,
                    'book_id' => $book->id,
                    'quantity' => 1
                ]);
            });
        }
    }
}
