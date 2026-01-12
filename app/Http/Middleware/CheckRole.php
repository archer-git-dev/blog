<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->role !== 'admin' ) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You do not have required permissions.'
            ], 403);
        }

        return $next($request);
    }
}
