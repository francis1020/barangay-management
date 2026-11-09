<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$abilities): Response
    {
        if (!$request->user()) {
            return redirect('login');
        }

        // Check if any of the passed abilities match either a role slug OR a permission slug
        foreach ($abilities as $ability) {
            if ($request->user()->role && $request->user()->role->slug === $ability) {
                return $next($request);
            }
            
            if ($request->user()->hasPermission($ability)) {
                return $next($request);
            }
        }

        abort(403, 'Unauthorized action.');
    }
}
