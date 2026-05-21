<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware CheckRole
 * 
 * Este middleware actúa como un guardia de seguridad. Revisa cada vez que un usuario 
 * intenta acceder a una ruta protegida y verifica si su Rol tiene permiso para entrar.
 */
class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificamos si hay un usuario logueado. Si no lo hay, lo enviamos al login.
        if (! $request->user()) {
            return redirect('login');
        }

        // 2. Obtenemos el ID del rol del usuario actual (ej. 1 para Admin, 2 para SuperAdmin, etc.)
        $userRoleId = (string) $request->user()->role_id;

        // 3. Revisamos si el ID de su rol está dentro de la lista de roles permitidos (...$roles)
        if (! in_array($userRoleId, $roles)) {
            
            // Si el rol NO está permitido:
            // Cerramos la sesión del usuario inmediatamente. Esto evita problemas de seguridad 
            // y bucles infinitos de redirección si un cliente o barbero intenta forzar la entrada.
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Lo regresamos a la pantalla de login con un mensaje visual de error (SweetAlert).
            return redirect('login')->with('swal', [
                'title' => 'Acceso Denegado',
                'text' => 'Tu cuenta no tiene permisos para acceder al sistema administrativo.',
                'icon' => 'error'
            ]);
        }

        // 4. Si el rol SI está permitido, dejamos que la petición continúe normalmente.
        return $next($request);
    }
}
