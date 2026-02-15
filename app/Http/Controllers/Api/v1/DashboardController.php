<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\BookRequest;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{


public function index()
{
    $totalUsers = User::where('role', 'user')->count();

    $activeUsers = User::where('role', 'user')
        ->where('status', 'active')
        ->count();

    $totalBooks = Book::count();

    $totalBorrowPending = Borrow::where('status', 'pending')->count();

    $totalBorrowApproved = Borrow::whereIn('status', ['approved'])->count();

    $totalBorrowPickedUp = Borrow::whereIn('status', ['picked_up'])->count();

    $totalLateBorrow = Borrow::where('status', 'borrowed')
        ->where('original_due_date', '<', now())
        ->count();

    $totalBookRequests = BookRequest::count();

    $borrowThisMonth = Borrow::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    return response()->json([
        'status' => true,
        'data' => [
            'users' => [
                'total' => $totalUsers,
                'active' => $activeUsers
            ],
            'books' => [
                'total' => $totalBooks
            ],
            'borrows' => [
                'pending' => $totalBorrowPending,
                'approve' => $totalBorrowApproved,
                'picked_up' => $totalBorrowPickedUp,
                'late' => $totalLateBorrow,
                'this_month' => $borrowThisMonth
            ],
            'book_requests' => [
                'total' => $totalBookRequests
            ]
        ]
    ]);
}

}
