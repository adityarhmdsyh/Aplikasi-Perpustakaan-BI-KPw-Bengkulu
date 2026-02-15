<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'publisher' => 'nullable|string|max:255',
        'year' => 'nullable|integer',
        'category_id' => 'required|exists:categories,id',
        'stock' => 'required|integer|min:0',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = $request->except('image');

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('books', 'public');
    }

    $book = Book::create($data);

    return response()->json([
        'status' => true,
        'message' => 'Buku berhasil ditambahkan',
        'data' => $book
    ], 201);
}

public function update(Request $request, $id)
{
    $book = Book::find($id);

    if (!$book) {
        return response()->json([
            'status' => false,
            'message' => 'Buku tidak ditemukan'
        ], 404);
    }

    $request->validate([
        'title' => 'sometimes|required|string|max:255',
        'author' => 'sometimes|required|string|max:255',
        'publisher' => 'nullable|string|max:255',
        'year' => 'nullable|integer',
        'category_id' => 'sometimes|required|exists:categories,id',
        'stock' => 'sometimes|required|integer|min:0',
        'description' => 'nullable|string',
        'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    $data = $request->except('cover');

    if ($request->hasFile('cover')) {
        if ($book->cover && Storage::disk('public')->exists($book->cover)) {
            Storage::disk('public')->delete($book->cover);
        }

        $data['cover'] = $request->file('cover')->store('books', 'public');
    }

    $book->update($data);

    return response()->json([
        'status' => true,
        'message' => 'Buku berhasil diperbarui',
        'data' => $book
    ]);
}

public function destroy($id)
{
    $book = Book::find($id);

    if (!$book) {
        return response()->json([
            'status' => false,
            'message' => 'Buku tidak ditemukan'
        ], 404);
    }

    if ($book->cover && Storage::disk('public')->exists($book->cover)) {
        Storage::disk('public')->delete($book->cover);
    }

    $book->delete();

    return response()->json([
        'status' => true,
        'message' => 'Buku berhasil dihapus'
    ]);
}



}