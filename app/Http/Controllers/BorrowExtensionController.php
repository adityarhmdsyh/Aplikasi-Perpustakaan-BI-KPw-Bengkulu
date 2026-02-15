<?php

namespace App\Http\Controllers;

use App\Models\BorrowExtension;
use Illuminate\Http\Request;

class BorrowExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, BorrowExtension $borrowExtension)
{
    $request->validate([
        'action' => 'required|in:approve,reject'
    ]);

    if ($borrowExtension->status !== 'pending') {
        return back()->with('error', 'Sudah diproses.');
    }

    $borrow = $borrowExtension->borrow;

    if ($request->action === 'approve') {

        $borrowExtension->update([
            'status' => 'approved',
            'approved_due_date' => $borrowExtension->requested_due_date,
            'approved_by' => auth()->id(),
        ]);

        $borrow->update([
            'current_due_date' => $borrowExtension->requested_due_date,
        ]);

    } else {

        $borrowExtension->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
        ]);
    }

    return back()->with('success', 'Request berhasil diproses.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
