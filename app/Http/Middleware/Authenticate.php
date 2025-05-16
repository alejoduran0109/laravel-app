<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        dd('Llegando al middleware de autenticación', auth()->check(), auth()->user());
        
        if (!$request->expectsJson()) {
            return route('filament.auth.login');
        }
    }
}
