<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Book::with('category');

    // 🔍 Search
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('author', 'like', '%' . $request->search . '%')
              ->orWhere('isbn', 'like', '%' . $request->search . '%');
        });
    }

    // 📂 Filter kategori
    if ($request->category_id) {
        $query->where('category_id', $request->category_id);
    }

    $books = $query->latest()->paginate(10)->withQueryString();

    $categories = Category::all();

    return view('books.index', compact('books', 'categories'));
}


    /**
     * Show the form for creating a new resource.
     */
   
       public function create()
        {
                $categories = Category::all();
                return view('books.create', compact('categories'));
        }

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required',
        'author' => 'required',
        'isbn' => 'required|unique:books,isbn',
        'lokasi_buku' => 'required',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('books', 'public');
    }

    Book::create($data);

    return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
{
    $categories = Category::all();
    return view('books.edit', compact('book', 'categories'));
}


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
{
    $request->validate([
        'category_id' => 'required|exists:categories,id',
        'title' => 'required',
        'author' => 'required',
        'isbn' => 'required|unique:books,isbn,' . $book->id,
        'lokasi_buku' => 'required',
        'stock' => 'required|integer',
        'image' => 'nullable|image|max:2048'
    ]);

    $data = $request->all();

    if ($request->hasFile('image')) {
        if ($book->image) {
            Storage::disk('public')->delete($book->image);
        }
        $data['image'] = $request->file('image')->store('books', 'public');
    }

    $book->update($data);

    return redirect()->route('books.index')->with('success', 'Buku berhasil diupdate');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
{
    if ($book->image) {
        Storage::disk('public')->delete($book->image);
    }

    $book->delete();

    return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus');
}

}
