<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized access. Required role: '.implode(' or ', $roles),
                ], 403);
            }

            abort(403, 'Unauthorized access. You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
