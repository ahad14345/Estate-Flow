<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle(Request $request, Closure $next): Response
{
    $user = $request->user();

    // Case-insensitive role check
    if (! $user || strtolower($user->role) !== 'admin') {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Forbidden: Admin access required.'], 403);
        }
        abort(403, 'Access Denied: Admin privileges required.');
    }

    return $next($request);
}
}