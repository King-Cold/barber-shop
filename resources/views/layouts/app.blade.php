<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Barber Shop') }}</title>

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-chalk-white text-dark-carbon" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
                <!-- Top Navbar -->
                <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
                    <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 hover:text-barber-gold focus:outline-none transition-colors duration-200 lg:hidden">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>

                    <div class="hidden lg:block">
                        <h2 class="text-xl font-bold text-slate-dark uppercase">
                            @if(request()->routeIs('dashboard'))
                                <i class="fa-solid fa-house mr-2 text-bronze-gold"></i> Dashboard
                            @elseif(request()->routeIs('appointments*'))
                                <i class="fa-solid fa-calendar-check mr-2 text-bronze-gold "></i> Citas
                            @elseif(request()->routeIs('clients*'))
                                <i class="fa-solid fa-users mr-2 text-bronze-gold"></i> Clientes
                            @elseif(request()->routeIs('barbers*'))
                                <i class="fa-solid fa-scissors mr-2 text-bronze-gold"></i> Barberos
                            @elseif(request()->routeIs('services*'))
                                <i class="fa-solid fa-spray-can mr-2 text-bronze-gold"></i> Servicios
                            @elseif(request()->routeIs('users*'))
                                <i class="fa-solid fa-user-shield mr-2 text-bronze-gold"></i> Usuarios
                            @elseif(request()->routeIs('profile'))
                                <i class="fa-solid fa-user-gear mr-2 text-bronze-gold"></i> Mi Perfil
                            @else
                                {{ $header ?? '' }}
                            @endif
                        </h2>
                    </div>

                    <div class="flex items-center space-x-6" x-data="{ open: false }">
                        <div class="relative">
                            <button @click="open = !open" class="flex items-center space-x-3 focus:outline-none">
                                <span class="hidden sm:inline-block font-medium text-gray-700">{{ Auth::user()->name }}</span>
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold border border-blue-200 overflow-hidden">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset(Auth::user()->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    @endif
                                </div>
                                <i class="fa-solid fa-chevron-down text-xs text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- User Dropdown -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute right-0 mt-3 w-48 bg-white rounded-lg shadow-lg py-2 border border-gray-100 z-50">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="fa-solid fa-user mr-2 text-gray-400"></i> Profile
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fa-solid fa-right-from-bracket mr-2 text-gray-400"></i> Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 md:p-8 bg-slate-50">
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
