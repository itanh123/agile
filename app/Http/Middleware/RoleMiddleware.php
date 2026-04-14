<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $currentRole = $request->user()->role?->slug;
        $allowed = $role === 'customer'
            ? in_array($currentRole, ['customer', 'user'], true)
            : $currentRole === $role;

        if (! $allowed) {
            abort(403);
        }

        return $next($request);
    }
}
