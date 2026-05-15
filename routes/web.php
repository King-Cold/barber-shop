<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::view('profile', 'profile')->name('profile');
    
    // Livewire CRUD Routes
    Route::get('clients', \App\Livewire\Admin\ClientManager::class)->name('clients');
    Route::get('barbers', \App\Livewire\Admin\BarberManager::class)->name('barbers');
    Route::get('services', \App\Livewire\Admin\ServiceManager::class)->name('services');
    Route::get('users', \App\Livewire\Admin\UserManager::class)->name('users');
    Route::get('appointments', \App\Livewire\Admin\AppointmentManager::class)->name('appointments');
});

require __DIR__.'/auth.php';
