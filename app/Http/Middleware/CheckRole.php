<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect('login');
        }

        // If roles are passed as strings like 'admin', we can map them or just check role_id
        // For simplicity with the user's request of "roles by numbers":
        // Admin = 1, SuperAdmin = 2
        
        $userRoleId = (string) $request->user()->role_id;

        if (! in_array($userRoleId, $roles)) {
            return redirect('dashboard')->with('swal', [
                'title' => 'Acceso Denegado',
                'text' => 'No tienes permisos para acceder a esta sección.',
                'icon' => 'error'
            ]);
        }

        return $next($request);
    }
}
