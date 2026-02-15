<?php

namespace App\Http\Controllers;

use App\Models\AppReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppReviewController extends Controller
{
    public function index()
    {
        $reviews = AppReview::with('user')->latest()->get();
        return view('app_reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('app_reviews.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        AppReview::create([
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'review' => $request->review,
            'is_anonymous' => $request->has('is_anonymous'),
            'is_show' => true,
        ]);

        return redirect()->route('app_reviews.index')
            ->with('success','Review berhasil ditambahkan');
    }

    public function edit(AppReview $app_review)
    {
        return view('app_reviews.edit', compact('app_review'));
    }

    public function update(Request $request, AppReview $app_review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $app_review->update([
            'rating' => $request->rating,
            'review' => $request->review,
            'is_anonymous' => $request->has('is_anonymous'),
            'is_show' => $request->has('is_show'),
        ]);

        return redirect()->route('app_reviews.index')
            ->with('success','Review berhasil diupdate');
    }

    public function destroy(AppReview $app_review)
    {
        $app_review->delete();

        return redirect()->route('app_reviews.index')
            ->with('success','Review berhasil dihapus');
    }
}
