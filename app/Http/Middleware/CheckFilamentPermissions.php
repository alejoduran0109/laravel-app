<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFilamentPermissions
{
    public function handle(Request $request, Closure $next)
    {
        // Solo verificar permisos si el usuario está autenticado Y no es la ruta de login
        if (auth()->check() && $request->path() !== 'admin/login') {
            $segments = $request->segments();
            
            if (count($segments) >= 2) {
                $resource = $segments[1];
                
                $permission = match ($resource) {
                    'users' => 'view_usuarios',
                    'roles' => 'view_roles',
                    'permissions' => 'view_permisos',
                    default => null,
                };

                if ($permission && !auth()->user()->hasPermissionTo($permission)) {
                    abort(403, 'No tienes permiso para acceder a esta página');
                }
            }
        }

        return $next($request);
    }
}