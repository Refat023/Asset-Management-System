<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guard = $guards[0] ?? null;

        if (Auth::guard($guard)->check()) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthenticated',
        ], 401);
    }
}
