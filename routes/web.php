<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    
    // SuperAdmin ONLY (ID 2)
    Route::middleware(['role:2'])->group(function () {
        // Users
        Route::get('users', \App\Livewire\Admin\UserManager::class)->name('users');
        Route::get('users/create', \App\Livewire\Admin\UserForm::class)->name('users.create');
        Route::get('users/{user}/edit', \App\Livewire\Admin\UserForm::class)->name('users.edit');
    });

    // Admin & SuperAdmin Sections (ID 1 and 2)
    Route::middleware(['role:1,2'])->group(function () {
        // Barbers
        Route::get('barbers', \App\Livewire\Admin\BarberManager::class)->name('barbers');
        Route::get('barbers/create', \App\Livewire\Admin\BarberForm::class)->name('barbers.create');
        Route::get('barbers/{barber}/edit', \App\Livewire\Admin\BarberForm::class)->name('barbers.edit');
        Route::get('barbers/{barber}/records', \App\Livewire\Admin\BarberRecords::class)->name('barbers.records');

        // Services
        Route::get('services', \App\Livewire\Admin\ServiceManager::class)->name('services');
        Route::get('services/create', \App\Livewire\Admin\ServiceForm::class)->name('services.create');
        Route::get('services/{service}/edit', \App\Livewire\Admin\ServiceForm::class)->name('services.edit');

        // Clients
        Route::get('clients', \App\Livewire\Admin\ClientManager::class)->name('clients');
        Route::get('clients/create', \App\Livewire\Admin\ClientForm::class)->name('clients.create');
        Route::get('clients/{client}/edit', \App\Livewire\Admin\ClientForm::class)->name('clients.edit');
        Route::get('clients/{client}/records', \App\Livewire\Admin\ClientRecords::class)->name('clients.records');

        // Appointments
        Route::get('appointments', \App\Livewire\Admin\AppointmentManager::class)->name('appointments');
        Route::get('appointments/create', \App\Livewire\Admin\AppointmentForm::class)->name('appointments.create');
        Route::get('appointments/{appointment}/edit', \App\Livewire\Admin\AppointmentForm::class)->name('appointments.edit');
        Route::get('appointments/{appointment}/ticket', [\App\Http\Controllers\AppointmentTicketController::class, 'download'])->name('appointments.ticket');
        Route::get('appointments/{appointment}/preview', [\App\Http\Controllers\AppointmentTicketController::class, 'preview'])->name('appointments.preview');
    });
});

require __DIR__.'/auth.php';

// Public route for customer appointment confirmation
Route::get('appointments/{appointment}/confirm', function (App\Models\Appointment $appointment) {
    if (! request()->hasValidSignature()) {
        abort(401, 'El enlace de confirmación ha expirado o es inválido.');
    }

    $appointment->update(['status' => 'confirmed']);

    return view('appointments.confirmed-success', compact('appointment'));
})->name('appointments.client-confirm');
