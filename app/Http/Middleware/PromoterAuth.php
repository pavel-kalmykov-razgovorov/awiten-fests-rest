<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PromoterAuth
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
        return Auth::user()->role === 'promoter'
            ? $next($request)
            : response()->json(['message' => 'Only promoters allowed'], Response::HTTP_UNAUTHORIZED);
    }
}
