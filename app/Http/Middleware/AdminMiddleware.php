<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user(); // usuário autenticado via Sanctum

        if (!$user || $user->role !== 'admin') {
            return response()->json([
                'message' => 'Access denied. Admins only.'
            ], 403);
        }

        return $next($request);
    }
}
