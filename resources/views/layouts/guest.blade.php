<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Barber Shop') }} - Iniciar Sesión</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Oswald:wght@400;600;700&family=Cinzel:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/logo-navegador.png') }}">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans bg-barber-black text-white antialiased selection:bg-vintage-gold selection:text-barber-black">
        <div class="min-h-screen flex flex-col justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
            <!-- Decorative Glowing Background Orbs -->
            <div class="absolute top-[-10%] right-[-10%] w-[400px] h-[400px] rounded-full bg-barber-gold/5 blur-[100px] pointer-events-none"></div>
            <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] rounded-full bg-bronze-gold/5 blur-[100px] pointer-events-none"></div>

            <div class="z-10 flex flex-col items-center mb-6">
                <a href="/" wire:navigate class="flex flex-col items-center group">
                    <x-application-logo class="w-24 h-24 shadow-2xl transition-transform duration-300 group-hover:scale-105" />
                    <h2 class="mt-4 text-2xl font-bold font-barber tracking-widest text-white uppercase">
                        Barber <span class="text-vintage-gold">Shop</span>
                    </h2>
                </a>
            </div>

            <div class="w-full sm:max-w-md px-8 py-8 bg-slate-dark/50 backdrop-blur-md border border-white/10 shadow-2xl overflow-hidden sm:rounded-2xl z-10">
                {{ $slot }}
            </div>
            
            <div class="mt-8 text-center text-xs text-gray-500 z-10">
                <a href="/" wire:navigate class="hover:text-vintage-gold transition-colors font-medium">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Volver al inicio
                </a>
            </div>
        </div>
    </body>
</html>
