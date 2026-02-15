<?php

use App\Http\Controllers\AppReviewController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BookRequestController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BorrowExtensionController;


Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('borrows', BorrowController::class);

Route::patch('borrows/{borrow}/approve', [BorrowController::class,'approve'])->name('borrows.approve');
Route::patch('borrows/{borrow}/reject', [BorrowController::class,'reject'])->name('borrows.reject');
Route::patch('borrows/{borrow}/pickup', [BorrowController::class,'pickup'])->name('borrows.pickup');
Route::patch('borrows/{borrow}/return', [BorrowController::class,'returnBook'])->name('borrows.return');
Route::patch('borrows/{borrow}/extend', [BorrowController::class,'extend'])->name('borrows.extend');

Route::put('/borrow-extensions/{borrowExtension}',[BorrowExtensionController::class, 'update'])->name('borrow-extensions.update');


    

    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('users', UserController::class);
    Route::resource('book_requests', BookRequestController::class);
    Route::resource('app_reviews', AppReviewController::class);
});

require __DIR__.'/auth.php';


