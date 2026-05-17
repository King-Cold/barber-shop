<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="font-sans">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h3 class="text-xl font-bold font-cinzel text-white">¡Bienvenido de nuevo!</h3>
        <p class="text-xs text-gray-400 mt-1">Ingresa tus credenciales para acceder al sistema</p>
    </div>

    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-vintage-gold mb-1.5">
                Correo Electrónico
            </label>
            <div class="relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-regular fa-envelope text-gray-400"></i>
                </div>
                <input wire:model="form.email" id="email" 
                    class="block w-full pl-10 pr-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-vintage-gold focus:border-vintage-gold text-sm transition-colors" 
                    type="email" name="email" required autofocus autocomplete="username" placeholder="tu@email.com" />
            </div>
            <x-input-error :messages="$errors->get('form.email')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-vintage-gold">
                    Contraseña
                </label>
                @if (Route::has('password.request'))
                    <a class="text-xs text-gray-400 hover:text-vintage-gold transition-colors font-medium" href="{{ route('password.request') }}" wire:navigate>
                        ¿Olvidaste la clave?
                    </a>
                @endif
            </div>
            <div class="relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-lock text-gray-400"></i>
                </div>
                <input wire:model="form.password" id="password" 
                    class="block w-full pl-10 pr-3 py-2.5 bg-white/5 border border-white/10 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-vintage-gold focus:border-vintage-gold text-sm transition-colors"
                    type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->get('form.password')" class="mt-1 text-xs text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" 
                    class="rounded border-white/10 bg-white/5 text-vintage-gold shadow-sm focus:ring-0 focus:ring-offset-0 cursor-pointer w-4 h-4" name="remember">
                <span class="ms-2 text-xs text-gray-300 select-none">Recordar mi sesión</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full py-3 px-4 bg-vintage-gold hover:bg-yellow-600 text-barber-black font-bold font-barber tracking-widest rounded-lg text-center shadow-lg hover:shadow-yellow-500/25 transition-all transform hover:-translate-y-0.5 text-sm uppercase">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Iniciar Sesión
            </button>
        </div>
    </form>
</div>
