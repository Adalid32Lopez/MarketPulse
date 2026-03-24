<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BelongsToBusiness
{
    public function handle(Request $request, Closure $next)
    {
        $business = $request->route('business');

        if (!$business) {
            return $next($request);
        }

        $user = auth()->user();

        // El dueño siempre puede acceder
        if ($business->user_id === $user->id) {
            return $next($request);
        }

        // Verificar si es miembro (vendedor)
        if ($business->members->contains($user->id)) {
            return $next($request);
        }

        abort(403, 'No tienes acceso a este negocio.');
    }
}