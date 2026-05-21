<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Web (routes/web.php)
|--------------------------------------------------------------------------
| Aquí se registran las rutas principales de la aplicación.
*/

// Ruta pública inicial (Landing Page)
Route::view('/', 'welcome');

// Ruta del Dashboard principal
// Protegida por 3 middlewares:
// 1. 'auth': Solo usuarios logueados.
// 2. 'verified': Solo correos verificados.
// 3. 'role:1,2': Solo Administradores y SuperAdministradores (Bloquea a barberos y clientes).
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'role:1,2'])
    ->name('dashboard');

// Grupo de rutas protegidas para la gestión del perfil de usuario (solo Admins)
Route::middleware(['auth', 'role:1,2'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
});

// Importa las rutas de autenticación (Login, Registro, Recuperación de contraseña, etc.)
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Rutas Públicas Firmadas (Para uso de Clientes vía Email)
|--------------------------------------------------------------------------
| Estas rutas se envían por correo electrónico a los clientes. 
| Usan "Signed URLs" (hasValidSignature) que incluyen un token criptográfico.
| Esto asegura que solo la persona con el enlace exacto del correo pueda 
| confirmar o cancelar la cita, sin necesidad de iniciar sesión.
*/

// Ruta para que el cliente confirme su cita
Route::get('appointments/{appointment}/confirm', function (App\Models\Appointment $appointment) {
    if (! request()->hasValidSignature()) {
        abort(401, 'El enlace de confirmación ha expirado o es inválido.');
    }

    $appointment->update(['status' => 'confirmed']);

    return view('appointments.confirmed-success', compact('appointment'));
})->name('appointments.client-confirm');

// Ruta para que el cliente cancele su cita
Route::get('appointments/{appointment}/cancel', function (App\Models\Appointment $appointment) {
    if (! request()->hasValidSignature()) {
        abort(401, 'El enlace de cancelación ha expirado o es inválido.');
    }

    $appointment->update(['status' => 'canceled']);

    return view('appointments.canceled-success', compact('appointment'));
})->name('appointments.client-cancel');
