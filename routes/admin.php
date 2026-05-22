<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BarberController;
use App\Http\Controllers\Admin\ClientController;

// =========================================================================
// RUTAS ADMINISTRATIVAS (routes/admin.php)
// =========================================================================
// Este archivo contiene todas las rutas internas del panel de administración.
// Todas estas rutas heredan automáticamente:
// 1. El prefijo '/admin' en la URL (ej. misitio.com/admin/users).
// 2. El prefijo 'admin.' en los nombres de ruta (ej. route('admin.users.index')).
// 3. Los middlewares 'auth' (requiere login) y 'role:1,2' (solo Administradores 
//    y SuperAdministradores). Esta configuración global viene de bootstrap/app.php.
// =========================================================================

// ----------------------------------------
// 1. Seguridad Adicional: Solo Super Administrador (Rol ID: 2)
// ----------------------------------------
Route::middleware(['role:2'])->group(function () {
    // Users Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
});

// ----------------------------------------
// 2. Administrador & Super Administrador (IDs 1, 2)
// ----------------------------------------
Route::middleware(['role:1,2'])->group(function () {
    // Barbers CRUD
    Route::resource('barbers', BarberController::class)->except(['show']);

    // Clients CRUD
    Route::resource('clients', ClientController::class)->except(['show']);

    // Services CRUD
    Route::resource('services', \App\Http\Controllers\Admin\ServiceController::class)->except(['show']);
});

// ----------------------------------------
// 3. Barber, Administrador & Super Administrador (IDs 1, 2, 3)
// ----------------------------------------
Route::middleware(['role:1,2,3'])->group(function () {
    Route::get('barbers/{barber}/records', \App\Livewire\Admin\BarberRecords::class)->name('barbers.records');
    Route::get('barbers/{barber}/schedule', \App\Livewire\Admin\BarberSchedule::class)->name('barbers.schedule');
});

// ----------------------------------------
// 4. Client, Administrador & Super Administrador (IDs 1, 2, 4)
// ----------------------------------------
Route::middleware(['role:1,2,4'])->group(function () {
    Route::get('clients/{client}/records', \App\Livewire\Admin\ClientRecords::class)->name('clients.records');
});

// ----------------------------------------
// 5. All Authorized Roles: SuperAdmin, Admin, Barber, Client (IDs 1, 2, 3, 4)
// ----------------------------------------
Route::middleware(['role:1,2,3,4'])->group(function () {
    // Appointments CRUD & Ticket printing
    Route::get('appointments/slots', [\App\Http\Controllers\Admin\AppointmentController::class, 'getSlots'])->name('appointments.slots');
    Route::patch('appointments/{appointment}/complete', [\App\Http\Controllers\Admin\AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::resource('appointments', \App\Http\Controllers\Admin\AppointmentController::class)->except(['show']);
    Route::get('appointments/{appointment}/ticket', [\App\Http\Controllers\AppointmentTicketController::class, 'download'])->name('appointments.ticket');
    Route::get('appointments/{appointment}/preview', [\App\Http\Controllers\AppointmentTicketController::class, 'preview'])->name('appointments.preview');
});

