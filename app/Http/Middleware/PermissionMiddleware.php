<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (empty($permissions)) {
            return $next($request);
        }

        $requireAll = false;
        if (str_starts_with($permissions[0], 'all:')) {
            $requireAll = true;
            $permissions[0] = substr($permissions[0], 4);
        }

        if ($requireAll) {
            if (!$user->hasAllPermissions($permissions)) {
                abort(403, 'Bạn không có quyền thực hiện hành động này.');
            }
        } else {
            if (!$user->hasAnyPermission($permissions)) {
                abort(403, 'Bạn không có quyền thực hiện hành động này.');
            }
        }

        return $next($request);
    }
}
