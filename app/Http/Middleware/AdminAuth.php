<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return Auth::user()->role === 'admin'
            ? $next($request)
            : response()->json(['message' => 'Only admins allowed'], Response::HTTP_UNAUTHORIZED);
    }
}
