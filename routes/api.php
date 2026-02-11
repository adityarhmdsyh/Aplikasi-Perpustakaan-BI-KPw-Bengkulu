<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\BorrowController;
use App\Http\Controllers\Api\V1\CategoryController;

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', [CategoryController::class, 'index']);

    Route::get('/books', [BookController::class, 'index']);
    Route::get('/books/{id}', [BookController::class, 'show']);

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);


    /*
    |--------------------------------------------------------------------------
    | USER ROUTES (AUTH REQUIRED)
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/borrow', [BorrowController::class, 'store']);
        Route::get('/borrows', [BorrowController::class, 'index']);
        Route::post('/borrows/{id}/extend-request', [BorrowController::class, 'requestExtend']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::post('/borrow/{id}/approve', [BorrowController::class, 'approve']);
        Route::post('/borrow/{id}/reject', [BorrowController::class, 'reject']);

        Route::post('/borrow/{id}/pickup', [BorrowController::class, 'pickedUp']);
        Route::post('/borrow/{id}/return', [BorrowController::class, 'return']);

        Route::post('/extensions/{id}/approve', [BorrowController::class, 'approveExtend']);
        Route::post('/extensions/{id}/reject', [BorrowController::class, 'rejectExtend']);

    });

});
