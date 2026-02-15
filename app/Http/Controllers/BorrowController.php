<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\User;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BorrowController extends Controller
{
    public function index()
    {
        $borrows = Borrow::with(['user','details.book','approver'])
                    ->latest()
                    ->get();

        return view('borrows.index', compact('borrows'));
    }

    public function create()
    {
        $users = User::whereDoesntHave('borrows', function ($query) {
            $query->where('status', '!=', 'returned');
            })->where('role','user')->where('status', 'active')->get();
        $books = Book::where('stock','>',0)->get();

        return view('borrows.create', compact('users','books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'borrow_date' => 'required|date',
            'original_due_date' => 'required|date|after_or_equal:borrow_date',
            'books' => 'required|array',
        ]);

        $borrow = Borrow::create([
            'user_id' => $request->user_id,
            'borrow_date' => $request->borrow_date,
            'original_due_date' => $request->original_due_date,
            'current_due_date' => $request->original_due_date,
            'status' => 'pending',
        ]);

        foreach ($request->books as $bookId => $qty) {

            if ($qty <= 0) continue;

            $book = Book::find($bookId);

            if ($book && $book->stock >= $qty) {

                BorrowDetail::create([
                    'borrow_id' => $borrow->id,
                    'book_id' => $bookId,
                    'quantity' => $qty,
                ]);

                $book->decrement('stock', $qty);
            }
        }

        return redirect()->route('borrows.index')
            ->with('success','Borrow berhasil dibuat');
    }

    public function approve(Borrow $borrow)
    {
        $borrow->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success','Borrow approved');
    }

    public function reject(Borrow $borrow)
    {
        foreach ($borrow->details as $detail) {
            $detail->book->increment('stock', $detail->quantity);
        }

        $borrow->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success','Borrow rejected');
    }

    public function pickup(Borrow $borrow)
    {
        $borrow->update([
            'status' => 'picked_up',
            'pickup_at' => now(),
        ]);

        return back()->with('success','Buku sudah diambil');
    }

    public function returnBook(Borrow $borrow)
    {
        $today = Carbon::now();
        $due = $borrow->current_due_date ?? $borrow->original_due_date;

        $lateDays = 0;
        $fine = 0;

        if ($today->gt($due)) {
            $lateDays = $today->diffInDays($due);
            $fine = $lateDays * 2000;
        }

        foreach ($borrow->details as $detail) {
            $detail->book->increment('stock', $detail->quantity);
        }

        $borrow->update([
            'status' => 'returned',
            'return_date' => $today,
            'late_days' => $lateDays,
            'fine_amount' => $fine,
        ]);

        return back()->with('success','Buku dikembalikan');
    }

    public function destroy(Borrow $borrow)
    {
        $borrow->delete();
        return back()->with('success','Data dihapus');
    }
    public function show(Borrow $borrow)
{
    $borrow->load([
        'user',
        'details.book',
        'extensions.approver'
    ]);

    return view('borrows.show', compact('borrow'));
}


}
