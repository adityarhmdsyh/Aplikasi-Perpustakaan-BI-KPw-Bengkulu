<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Akses ditolak. Hanya admin.'
            ], 403);
        }

        return $next($request);
    }
}
