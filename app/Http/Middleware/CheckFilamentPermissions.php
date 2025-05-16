<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckFilamentPermissions
{
    public function handle(Request $request, Closure $next)
    {
        // Solo verificar permisos si el usuario está autenticado
        if (auth()->check()) {
            // Si es la ruta de login, permitir acceso
            if ($request->path() === 'admin/login' || $request->path() === 'admin') {
                return $next($request);
            }

            $user = auth()->user();
            
            // Depuración
            if (config('app.debug')) {
                \Log::info('Usuario autenticado', [
                    'id' => $user->id,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(),
                    'permissions' => $user->getAllPermissions()->pluck('name'),
                    'path' => $request->path(),
                    'segments' => $request->segments()
                ]);
            }
            
            // Verificar si el usuario es super admin
            if ($user->hasRole('Super Admin')) {
                return $next($request);
            }

            $segments = $request->segments();
            
            if (count($segments) >= 2) {
                $resource = $segments[1];
                
                $permission = match ($resource) {
                    'users' => 'view_usuarios',
                    'roles' => 'view_roles',
                    'permissions' => 'view_permisos',
                    default => null,
                };

                if ($permission) {
                    if (!$user->hasPermissionTo($permission)) {
                        abort(403, 'No tienes permiso para acceder a esta página');
                    }
                }
            }
        }

        return $next($request);
    }
}