<?php

use App\Http\Controllers\Api\v1\BookRequestController;
use App\Http\Controllers\Api\v1\DashboardController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BookController;
use App\Http\Controllers\Api\V1\BorrowController;
use App\Http\Controllers\Api\V1\CategoryController;
use Illuminate\Support\Facades\Route;

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

        Route::put('/update-profile', [AuthController::class, 'updateProfile']);
        Route::get('/profile', [AuthController::class, 'me']);
        Route::put('/change-password', [AuthController::class, 'changePassword']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/borrow', [BorrowController::class, 'store']);
        Route::get('/borrows', [BorrowController::class, 'index']);
        Route::post('/borrows/{id}/extend-request', [BorrowController::class, 'requestExtend']);
        

        Route::get('/book-requests', [BookRequestController::class, 'indexByUser']);
        Route::post('/book-requests', [BookRequestController::class, 'store']);

    });


    /*
    |--------------------------------------------------------------------------
    | ADMIN ROUTES
    |--------------------------------------------------------------------------
    */

    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index']);


        Route::get('/users', [AuthController::class, 'listUsers']);
        Route::post('/users/{id}/activate', [AuthController::class, 'activateUser']);
        Route::post('/users/{id}/block', [AuthController::class, 'blockUser']);

        Route::post('/borrow/{id}/approve', [BorrowController::class, 'approve']);
        Route::post('/borrow/{id}/reject', [BorrowController::class, 'reject']);

        Route::post('/borrow/{id}/pickup', [BorrowController::class, 'pickedUp']);
        Route::post('/borrow/{id}/return', [BorrowController::class, 'return']);

        Route::post('/extensions/{id}/approve', [BorrowController::class, 'approveExtend']);
        Route::post('/extensions/{id}/reject', [BorrowController::class, 'rejectExtend']);

        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{id}', [BookController::class, 'update']);
        Route::delete('/books/{id}', [BookController::class, 'destroy']);

        Route::get('/book-requests', [BookRequestController::class, 'index']);
        


    });

});
