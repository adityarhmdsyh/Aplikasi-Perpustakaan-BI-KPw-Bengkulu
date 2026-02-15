<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Borrow;

use App\Models\BorrowDetail;
use App\Models\BorrowExtension;
use Carbon\Carbon;
use Illuminate\Foundation\Exceptions\Renderer\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class BorrowController extends Controller
{

public function index(Request $request)
{
    $user = auth()->user();

    $query = Borrow::with(['user', 'details.book'])
                    ->latest();

    // Kalau bukan admin → hanya lihat miliknya sendiri
    if ($user->role !== 'admin') {
        $query->where('user_id', $user->id);
    }

    // Kalau admin bisa filter status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    $borrows = $query->paginate(10);

    return response()->json([
        'status' => true,
        'message' => 'List borrow',
        'data' => $borrows
    ]);
}

use Illuminate\Support\Facades\DB;
use App\Models\Borrow;
use App\Models\BorrowDetail;
use App\Models\Book;

public function store(Request $request)
{
    $request->validate([
        'books' => 'required|array|min:1',
        'books.*.book_id' => 'required|exists:books,id',
        'books.*.quantity' => 'required|integer|min:1'
    ]);

    $user = auth()->user();

    // 1️⃣ Cek status user
    if ($user->status !== 'active') {
        return response()->json([
            'status' => false,
            'message' => 'Akun belum aktif atau diblokir'
        ], 403);
    }

    // 2️⃣ Cek apakah masih ada borrow aktif
    $activeBorrow = Borrow::where('user_id', $user->id)
        ->whereIn('status', ['pending', 'approved', 'borrowed'])
        ->exists();

    if ($activeBorrow) {
        return response()->json([
            'status' => false,
            'message' => 'Masih ada peminjaman aktif'
        ], 400);
    }

    // 3️⃣ Maksimal 3 buku
    if (count($request->books) > 2) {
        return response()->json([
            'status' => false,
            'message' => 'Maksimal 3 buku dalam satu peminjaman'
        ], 400);
    }

    // 4️⃣ Cek duplikasi book
    $bookIds = collect($request->books)->pluck('book_id');

    if ($bookIds->count() !== $bookIds->unique()->count()) {
        return response()->json([
            'status' => false,
            'message' => 'Tidak boleh ada buku yang sama dalam satu request'
        ], 400);
    }

    // 5️⃣ Cek stok cukup
    foreach ($request->books as $item) {

        $book = Book::find($item['book_id']);

        if ($book->stock < $item['quantity']) {
            return response()->json([
                'status' => false,
                'message' => "Stok buku '{$book->title}' tidak mencukupi"
            ], 400);
        }
    }

    DB::beginTransaction();

    try {

        $borrow = Borrow::create([
            'user_id' => $user->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'pending'
        ]);

        foreach ($request->books as $item) {

            BorrowDetail::create([
                'borrow_id' => $borrow->id,
                'book_id' => $item['book_id'],
                'quantity' => $item['quantity']
            ]);
        }

        DB::commit();

        return response()->json([
            'status' => true,
            'message' => 'Request peminjaman berhasil dikirim',
            'data' => $borrow->load('details.book')
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        return response()->json([
            'status' => false,
            'message' => 'Terjadi kesalahan server',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function approve($id)
{
    $borrow = Borrow::with('details.book')->find($id);

    if (!$borrow) {
        return response()->json([
            'status' => false,
            'message' => 'Borrow tidak ditemukan'
        ], 404);
    }

    if ($borrow->status !== 'pending') {
        return response()->json([
            'status' => false,
            'message' => 'Borrow sudah diproses'
        ]);
    }

    // Kurangi stock
    foreach ($borrow->details as $detail) {
        $detail->book->decrement('stock', $detail->quantity);
    }

    
    $borrow->status = 'approved';
    $borrow->approved_by = auth()->id(); 
    $borrow->save();

    return response()->json([
        'status' => true,
        'message' => 'Borrow berhasil diapprove'
    ]);
}


public function reject($id)
{
    $borrow = Borrow::findOrFail($id);

    if ($borrow->status !== 'pending') {
        return response()->json([
            'status' => false,
            'message' => 'Borrow sudah diproses'
        ], 400);
    }

    $borrow->update([
        'status' => 'rejected',
        'approved_by' => 1
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Borrow berhasil ditolak'
    ]);
}

public function return($id)
{
    $borrow = Borrow::with('borrowDetails.book')->find($id);

    if (!$borrow) {
        return response()->json([
            'status' => false,
            'message' => 'Borrow tidak ditemukan'
        ], 404);
    }

    if ($borrow->status !== 'picked_up') {
        return response()->json([
            'status' => false,
            'message' => 'Borrow belum bisa dikembalikan'
        ], 400);
    }

    // Tambah stock kembali
    foreach ($borrow->borrowDetails as $detail) {
        $detail->book->increment('stock', $detail->quantity);
    }

    $now = now();
    $lateDays = 0;
    $finePerDay = 2000; // bisa kamu pindahkan ke config nanti
    $fineAmount = 0;

    if ($now->gt($borrow->due_date)) {
        $lateDays = $now->diffInDays($borrow->due_date);
        $fineAmount = $lateDays * $finePerDay;
    }

    $borrow->update([
        'status'       => 'returned',
        'return_date'  => $now,
        'late_days'    => $lateDays,
        'fine_amount'  => $fineAmount,
        'approved_by'  => auth()->id()
    ]);

    return response()->json([
        'status' => true,
        'message' => $lateDays > 0
            ? "Buku dikembalikan dengan keterlambatan {$lateDays} hari"
            : "Buku berhasil dikembalikan tepat waktu",
        'data' => [
            'late_days' => $lateDays,
            'fine_amount' => $fineAmount
        ]
    ]);
}


public function pickedUp($id)
{
    $borrow = Borrow::find($id);

    if (!$borrow) {
        return response()->json([
            'status' => false,
            'message' => 'Borrow tidak ditemukan'
        ], 404);
    }

    if ($borrow->status !== 'approved') {
        return response()->json([
            'status' => false,
            'message' => 'Borrow belum bisa diambil'
        ], 400);
    }

    $borrow->update([
        'status'    => 'picked_up',
        'pickup_at' => now()
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Buku berhasil diambil'
    ]);
}

public function requestExtend(Request $request, $id)
{
    $request->validate([
        'requested_due_date' => 'required|date|after:today'
    ]);

    $borrow = Borrow::with('extensions')->find($id);

    if (!$borrow) {
        return response()->json([
            'status' => false,
            'message' => 'Data peminjaman tidak ditemukan'
        ], 404);
    }

    // Hanya pemilik yang bisa request
    if ($borrow->user_id !== auth()->id()) {
        return response()->json([
            'status' => false,
            'message' => 'Tidak diizinkan'
        ], 403);
    }

    // Harus sudah diambil
    if ($borrow->status !== 'picked_up') {
        return response()->json([
            'status' => false,
            'message' => 'Buku belum diambil'
        ], 400);
    }

    // Hitung extension yang sudah approved
    $approvedCount = $borrow->extensions
        ->where('status', 'approved')
        ->count();

    if ($approvedCount >= 2) {
        return response()->json([
            'status' => false,
            'message' => 'Maksimal perpanjangan sudah 2 kali'
        ], 400);
    }

    // Cek apakah masih ada pending
    $hasPending = $borrow->extensions
        ->where('status', 'pending')
        ->count();

    if ($hasPending > 0) {
        return response()->json([
            'status' => false,
            'message' => 'Masih ada permintaan perpanjangan yang belum diproses'
        ], 400);
    }

    // Ambil due date aktif
    $activeDueDate = $borrow->current_due_date ?? $borrow->original_due_date;

    if ($request->requested_due_date <= $activeDueDate) {
        return response()->json([
            'status' => false,
            'message' => 'Tanggal harus lebih dari jatuh tempo sekarang'
        ], 400);
    }

    // Simpan request
    BorrowExtension::create([
        'borrow_id' => $borrow->id,
        'requested_due_date' => $request->requested_due_date,
        'status' => 'pending'
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Permintaan perpanjangan dikirim, menunggu persetujuan admin'
    ]);
}


public function approveExtend($id)
{
    return DB::transaction(function () use ($id) {

        $extension = BorrowExtension::with('borrow.extensions')->find($id);

        if (!$extension) {
            return response()->json([
                'status' => false,
                'message' => 'Data extension tidak ditemukan'
            ], 404);
        }

        if ($extension->status !== 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Extension sudah diproses'
            ], 400);
        }

        $borrow = $extension->borrow;

        if (!$borrow) {
            return response()->json([
                'status' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        // Pastikan belum returned
        if ($borrow->status === 'returned') {
            return response()->json([
                'status' => false,
                'message' => 'Buku sudah dikembalikan'
            ], 400);
        }

        // Update borrow
        $borrow->update([
            'current_due_date' => $extension->requested_due_date,
            'extended_count' => $borrow->extended_count + 1
        ]);

        // Update extension
        $extension->update([
            'status' => 'approved',
            'approved_due_date' => $extension->requested_due_date,
            'approved_by' => auth()->id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Perpanjangan disetujui',
            'data' => [
                'new_due_date' => $extension->requested_due_date,
                'total_extend' => $borrow->extended_count + 1
            ]
        ]);
    });
}


public function rejectExtend($id)
{
    return DB::transaction(function () use ($id) {

        $extension = BorrowExtension::with('borrow')->find($id);

        if (!$extension) {
            return response()->json([
                'status' => false,
                'message' => 'Data extension tidak ditemukan'
            ], 404);
        }

        if ($extension->status !== 'pending') {
            return response()->json([
                'status' => false,
                'message' => 'Extension sudah diproses'
            ], 400);
        }

        $borrow = $extension->borrow;

        if (!$borrow) {
            return response()->json([
                'status' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        // Optional: jangan proses kalau sudah returned
        if ($borrow->status === 'returned') {
            return response()->json([
                'status' => false,
                'message' => 'Buku sudah dikembalikan'
            ], 400);
        }

        $extension->update([
            'status' => 'rejected',
            'approved_by' => auth()->id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Perpanjangan ditolak'
        ]);
    });
}

public function returnBook($id)
{
    return DB::transaction(function () use ($id) {

        $borrow = Borrow::find($id);

        if (!$borrow) {
            return response()->json([
                'status' => false,
                'message' => 'Data peminjaman tidak ditemukan'
            ], 404);
        }

        if ($borrow->status !== 'picked_up') {
            return response()->json([
                'status' => false,
                'message' => 'Buku belum dipinjam atau sudah dikembalikan'
            ], 400);
        }

        $now = Carbon::now();

        // Ambil due date aktif
        $activeDueDate = $borrow->current_due_date ?? $borrow->original_due_date;
        $dueDate = Carbon::parse($activeDueDate);

        $lateDays = 0;
        $finePerDay = 2000;
        $fineAmount = 0;

        if ($now->gt($dueDate)) {
            $lateDays = $dueDate->diffInDays($now);
            $fineAmount = $lateDays * $finePerDay;
        }

        $borrow->update([
            'return_date' => $now,
            'late_days' => $lateDays,
            'fine_amount' => $fineAmount,
            'status' => 'returned'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Buku berhasil dikembalikan',
            'data' => [
                'return_date' => $now,
                'late_days' => $lateDays,
                'fine_amount' => $fineAmount
            ]
        ]);
    });
}




}
