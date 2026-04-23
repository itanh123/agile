<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $currentRole = $request->user()->role?->slug;
        
        // Admin luôn có quyền truy cập
        if ($currentRole === 'admin') {
            return $next($request);
        }

        foreach ($roles as $roleGroup) {
            $roleList = explode(',', $roleGroup);
            foreach ($roleList as $role) {
                $role = trim($role);
                
                // Logic đặc biệt cho customer (cho phép cả user)
                if ($role === 'customer' && in_array($currentRole, ['customer', 'user'], true)) {
                    return $next($request);
                }

                if ($currentRole === $role) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Bạn không có quyền truy cập vào khu vực này.');
    }
}
