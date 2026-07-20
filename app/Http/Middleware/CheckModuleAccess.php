<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasModuleAccess($module)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => "Access denied to {$module} module."], 403);
            }
            abort(403, "Access Denied: You do not have permission to access the {$module} module.");
        }

        return $next($request);
    }
}
