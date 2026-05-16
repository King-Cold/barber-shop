<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    
    // Users
    Route::get('users', \App\Livewire\Admin\UserManager::class)->name('users');
    Route::get('users/create', \App\Livewire\Admin\UserForm::class)->name('users.create');
    Route::get('users/{user}/edit', \App\Livewire\Admin\UserForm::class)->name('users.edit');

    // Barbers
    Route::get('barbers', \App\Livewire\Admin\BarberManager::class)->name('barbers');
    Route::get('barbers/create', \App\Livewire\Admin\BarberForm::class)->name('barbers.create');
    Route::get('barbers/{barber}/edit', \App\Livewire\Admin\BarberForm::class)->name('barbers.edit');

    // Clients
    Route::get('clients', \App\Livewire\Admin\ClientManager::class)->name('clients');
    Route::get('clients/create', \App\Livewire\Admin\ClientForm::class)->name('clients.create');
    Route::get('clients/{client}/edit', \App\Livewire\Admin\ClientForm::class)->name('clients.edit');

    // Services
    Route::get('services', \App\Livewire\Admin\ServiceManager::class)->name('services');
    Route::get('services/create', \App\Livewire\Admin\ServiceForm::class)->name('services.create');
    Route::get('services/{service}/edit', \App\Livewire\Admin\ServiceForm::class)->name('services.edit');

    Route::get('appointments', \App\Livewire\Admin\AppointmentManager::class)->name('appointments');
});

require __DIR__.'/auth.php';
