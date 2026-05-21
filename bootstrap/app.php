<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Console\Scheduling\Schedule;

/*
|--------------------------------------------------------------------------
| Archivo de Configuración Principal (bootstrap/app.php)
|--------------------------------------------------------------------------
| Este archivo es el "cerebro" inicial de la aplicación. Aquí se registran
| las rutas, se configuran las tareas automáticas (CRON) y se declaran
| los middlewares (filtros de seguridad).
*/
return Application::configure(basePath: dirname(__DIR__))
    // 1. Configuración de Rutas
    ->withRouting(
        web: __DIR__.'/../routes/web.php',       // Rutas públicas y de clientes
        commands: __DIR__.'/../routes/console.php', // Comandos de consola
        health: '/up',                           // Ruta de estado de salud del servidor
        
        // El bloque 'then' permite cargar rutas adicionales con configuraciones especiales
        then: function () {
            // Registramos el archivo 'routes/admin.php'
            // Le aplicamos seguridad global: solo usuarios logueados ('auth') 
            // y con rol de Administrador(1) o SuperAdmin(2) ('role:1,2') pueden acceder.
            // Además, todas las rutas tendrán el prefijo '/admin' en la URL.
            Route::middleware(['web', 'auth', 'role:1,2'])
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        }
    )
    
    // 2. Tareas Programadas (CRON Jobs)
    // Aquí le decimos a Laravel qué comandos debe ejecutar automáticamente y con qué frecuencia
    ->withSchedule(function (Schedule $schedule) {
        // Ejecuta diariamente el comando que envía los recordatorios por correo
        $schedule->command('barber:reminders')->daily();
        
        // Ejecuta diariamente el comando que cancela las citas atrasadas
        $schedule->command('barber:cancel-past-appointments')->daily();
    })
    
    // 3. Middlewares (Filtros de Seguridad)
    ->withMiddleware(function (Middleware $middleware): void {
        // Registramos un "alias" para nuestro middleware personalizado.
        // Así podemos usar la palabra 'role' en las rutas en lugar de escribir toda la clase.
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    
    // 4. Manejo de Excepciones (Errores)
    ->withExceptions(function (Exceptions $exceptions): void {
        // Configuración para manejar cómo se muestran o registran los errores en la app
    })->create();
