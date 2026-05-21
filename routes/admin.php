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
    Route::get('users', \App\Livewire\Admin\UserManager::class)->name('users.index');
    Route::get('users/create', \App\Livewire\Admin\UserForm::class)->name('users.create');
    Route::get('users/{user}/edit', \App\Livewire\Admin\UserForm::class)->name('users.edit');
});

// ----------------------------------------
// 2. Administrador & Super Administrador (IDs 1, 2)
// ----------------------------------------
Route::middleware(['role:1,2'])->group(function () {
    // Barbers CRUD
    Route::get('barbers', \App\Livewire\Admin\BarberManager::class)->name('barbers.index');
    Route::get('barbers/create', [BarberController::class, 'create'])->name('barbers.create');
    Route::post('barbers', [BarberController::class, 'store'])->name('barbers.store');
    Route::get('barbers/{barber}/edit', [BarberController::class, 'edit'])->name('barbers.edit');
    Route::put('barbers/{barber}', [BarberController::class, 'update'])->name('barbers.update');
    Route::delete('barbers/{barber}', [BarberController::class, 'destroy'])->name('barbers.destroy');

    // Clients CRUD
    Route::get('clients', \App\Livewire\Admin\ClientManager::class)->name('clients.index');
    Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
    Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
    Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
    Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Services CRUD
    Route::get('services', \App\Livewire\Admin\ServiceManager::class)->name('services.index');
    Route::get('services/create', \App\Livewire\Admin\ServiceForm::class)->name('services.create');
    Route::get('services/{service}/edit', \App\Livewire\Admin\ServiceForm::class)->name('services.edit');
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
    Route::get('appointments', \App\Livewire\Admin\AppointmentManager::class)->name('appointments.index');
    Route::get('appointments/create', \App\Livewire\Admin\AppointmentForm::class)->name('appointments.create');
    Route::get('appointments/{appointment}/edit', \App\Livewire\Admin\AppointmentForm::class)->name('appointments.edit');
    Route::get('appointments/{appointment}/ticket', [\App\Http\Controllers\AppointmentTicketController::class, 'download'])->name('appointments.ticket');
    Route::get('appointments/{appointment}/preview', [\App\Http\Controllers\AppointmentTicketController::class, 'preview'])->name('appointments.preview');
});
