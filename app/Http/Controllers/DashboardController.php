<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Borrow;

class DashboardController extends Controller
{


public function index()
{
    $totalBooks = Book::count();
    $totalUsers = User::count();
    $totalBorrows = Borrow::count();

    $activeBorrows = Borrow::where('status', '!=', 'returned')->count();

    $totalFines = Borrow::sum('fine_amount');

    $latestBorrows = Borrow::with('user')
        ->latest()
        ->take(5)
        ->get();

    return view('dashboard.index', compact(
        'totalBooks',
        'totalUsers',
        'totalBorrows',
        'activeBorrows',
        'totalFines',
        'latestBorrows'
    ));
}

}
