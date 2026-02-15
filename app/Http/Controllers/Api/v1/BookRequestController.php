<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\BookRequest;
use Illuminate\Http\Request;

class BookRequestController extends Controller
{

    public function indexByUser()
{
    $bookRequests = BookRequest::where('user_id', auth()->id())
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'data' => $bookRequests
    ]);
}

    public function store(Request $request)
{
    $request->validate([
        'book_title' => 'required|string|max:255',
        'author' => 'nullable|string|max:255',
        'publisher' => 'nullable|string|max:255',
        'reason' => 'nullable|string'
    ]);

    $bookRequest = BookRequest::create([
        'user_id' => auth()->id(),
        'book_title' => $request->book_title,
        'author' => $request->author,
        'publisher' => $request->publisher,
        'reason' => $request->reason,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Permintaan buku berhasil dikirim',
        'data' => $bookRequest
    ]);
}

public function index()
{
    $bookRequests = BookRequest::with('user')
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'data' => $bookRequests
    ]);
}

}
