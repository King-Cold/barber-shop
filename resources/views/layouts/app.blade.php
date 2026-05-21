<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Barber Shop') }} - Admin</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Oswald:wght@400;600;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo-navegador.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F3F4F6] text-slate-dark selection:bg-vintage-gold selection:text-barber-black" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen overflow-hidden lg:pl-64">
                <!-- Top Navbar -->
                <header class="h-20 bg-barber-black border-b border-white/5 flex items-center justify-between px-8 shadow-lg z-30">
                    <!-- Sidebar Mobile Toggle -->
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-400 hover:text-vintage-gold focus:outline-none transition-colors duration-200 lg:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <!-- Page Header Title -->
                    <div class="hidden lg:block">
                        <h2 class="text-lg font-bold text-white tracking-widest font-barber uppercase">
                            @if(request()->routeIs('admin.dashboard'))
    <i class="fa-solid fa-chart-line mr-2 text-vintage-gold"></i> Dashboard
@elseif(request()->routeIs('admin.appointments*'))
    <i class="fa-regular fa-calendar-days mr-2 text-vintage-gold"></i> Citas
@elseif(request()->routeIs('admin.clients*'))
    <i class="fa-solid fa-users mr-2 text-vintage-gold"></i> Clientes
@elseif(request()->routeIs('admin.barbers*'))
    <i class="fa-solid fa-scissors mr-2 text-vintage-gold"></i> Barberos
@elseif(request()->routeIs('admin.services*'))
    <i class="fa-solid fa-spray-can mr-2 text-vintage-gold"></i> Servicios
@elseif(request()->routeIs('admin.users*'))
    <i class="fa-solid fa-user-shield mr-2 text-vintage-gold"></i> Usuarios
@elseif(request()->routeIs('profile'))
    <i class="fa-solid fa-user-gear mr-2 text-vintage-gold"></i> Mi Perfil
@else
    {{ $header ?? '' }}
@endif
                        </h2>
                    </div>

                    <!-- User Information Section -->
                    <div class="flex items-center space-x-6" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none group">
                                <span class="hidden sm:inline-block font-medium text-gray-300 group-hover:text-white transition-colors text-sm font-sans">{{ Auth::user()->name }}</span>
                                <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-vintage-gold font-bold border border-vintage-gold/30 overflow-hidden shadow-md group-hover:border-vintage-gold transition-colors">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset(Auth::user()->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-500 transition-transform duration-200 group-hover:text-gray-300" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- User Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute right-0 mt-3 w-48 bg-barber-black border border-white/10 rounded-xl shadow-2xl py-2 z-50">
                                <a href="{{ route('profile') }}" class="flex items-center px-4 py-2.5 text-sm text-gray-300 hover:text-barber-black hover:bg-vintage-gold font-medium transition-colors">
                                    <i class="fa-solid fa-user w-5 text-center mr-2"></i> Mi Perfil
                                </a>
                                <div class="border-t border-white/5 my-1.5"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center px-4 py-2.5 text-sm text-gray-300 hover:text-barber-black hover:bg-vintage-gold font-medium transition-colors">
                                        <i class="fa-solid fa-right-from-bracket w-5 text-center mr-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content Container -->
                <main class="flex-1 overflow-y-auto p-4 md:p-8">
                    @if(session()->has('swal'))
                        <script>
                            window.addEventListener('DOMContentLoaded', () => {
                                window.dispatchEvent(new CustomEvent('swal', { 
                                    detail: @json(session('swal')) 
                                }));
                            });
                        </script>
                    @endif
                    <div class="max-w-7xl mx-auto">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>

        @stack('scripts')
        @livewireScripts
    </body>
</html>
