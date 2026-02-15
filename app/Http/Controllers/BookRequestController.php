<?php

namespace App\Http\Controllers;

use App\Models\BookRequest;


class BookRequestController extends Controller
{
    public function index()
    {
        $bookRequests = BookRequest::with('user')->latest()->get();
        return view('book_requests.index', compact('bookRequests'));
    }


   
}
