<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;

class BookController extends Controller
{
  public function index(Request $request)
{
    $books = Book::with('category')
        ->when($request->category, function ($q) use ($request) {
            $q->whereHas('category', function ($query) use ($request) {
        $query->where('name', $request->category);
    });
})

        ->when($request->search, function ($q) use ($request) {
            $q->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('author', 'like', '%' . $request->search . '%');
            });
        })
        ->where('stock', '>', 0)
        ->orderBy('created_at', 'desc')
        ->paginate($request->limit ?? 10);

    return response()->json([
        'status' => true,
        'message' => 'List buku',
        'data' => $books
    ]);
}


    public function show($id)
    {
        $book = Book::with('category')->findOrFail($id);

        return response()->json([
            'status' => true,
            'message' => 'Detail buku',
            'data' => $book
        ]);
    }
}