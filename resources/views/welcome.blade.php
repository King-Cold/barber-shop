<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Barber Shop') }} - Experiencia Premium</title>

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Oswald:wght@400;600;700&family=Cinzel:wght@400;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-barber-black text-white selection:bg-vintage-gold selection:text-barber-black scroll-smooth">

        @php
            // Fetch services and barbers dynamically from the database
            $services = \App\Models\Service::all();
            $barbers = \App\Models\Barber::all();
        @endphp

        <!-- HERO SECTION WITH LARGE BACKGROUND IMAGE -->
        <div class="relative min-h-screen flex flex-col justify-between bg-cover bg-center bg-no-repeat" style="background-image: linear-gradient(rgba(15, 15, 15, 0.75), rgba(15, 15, 15, 0.9)), url('{{ asset('images/barber_bg.png') }}');">
            
            <!-- Header -->
            <header class="w-full max-w-7xl mx-auto px-6 py-6 flex justify-between items-center z-20">
                <div class="flex items-center space-x-3">
                    <img src="{{ asset('images/logo.jpeg') }}" class="w-12 h-12 rounded-full object-cover border border-vintage-gold/40 shadow-lg" alt="Logo">
                    <span class="text-xl font-bold tracking-widest font-barber uppercase text-white">
                        Barber <span class="text-vintage-gold">Shop</span>
                    </span>
                </div>
                
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-vintage-gold hover:bg-yellow-600 text-barber-black font-bold font-barber tracking-wider rounded-lg transition-colors shadow-lg text-sm uppercase">
                            <i class="fa-solid fa-chart-line mr-2"></i> Panel de Control
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 bg-vintage-gold hover:bg-yellow-600 text-barber-black font-bold font-barber tracking-wider rounded-lg transition-colors shadow-lg text-sm uppercase">
                            <i class="fa-solid fa-right-to-bracket mr-2"></i> Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Hero Centered Content -->
            <div class="flex-1 flex flex-col items-center justify-center text-center px-4 py-12 z-10 max-w-4xl mx-auto">
                <!-- Vintage Circular Badge -->
                <div class="mb-6 p-4 rounded-full border border-vintage-gold/30 bg-barber-black/60 backdrop-blur-sm animate-pulse">
                    <div class="w-20 h-20 rounded-full border border-dashed border-vintage-gold/50 flex flex-col items-center justify-center">
                        <i class="fa-solid fa-scissors text-3xl text-vintage-gold"></i>
                    </div>
                </div>
                
                <span class="text-xs uppercase tracking-widest font-bold text-vintage-gold mb-3 block">Est. 1995 &bull; Barbería Tradicional</span>
                
                <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold font-cinzel leading-tight tracking-wide text-white uppercase text-shadow-lg">
                    King Cold <br>
                    <span class="text-vintage-gold">Barber Shop</span>
                </h1>
                
                <div class="w-24 h-1 bg-vintage-gold my-6 mx-auto"></div>
                
                <p class="text-gray-300 text-base sm:text-xl font-light leading-relaxed max-w-2xl font-serif italic mb-8">
                    "El verdadero estilo es atemporal. Cortes de cabello clásicos y afeitado a navaja que destacan el carácter del caballero moderno."
                </p>

                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="#nosotros" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white font-medium border border-white/10 rounded-lg transition-all">
                        Conócenos <i class="fa-solid fa-arrow-down ml-2"></i>
                    </a>
                    <a href="#servicios" class="px-8 py-3 bg-vintage-gold hover:bg-yellow-600 text-barber-black font-bold font-barber tracking-wider rounded-lg transition-all uppercase">
                        Ver Menú de Servicios
                    </a>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="pb-10 text-center z-10 animate-bounce">
                <a href="#nosotros" class="text-vintage-gold text-lg">
                    <i class="fa-solid fa-chevron-down"></i>
                </a>
            </div>
        </div>

        <!-- ABOUT US SECTION -->
        <section id="nosotros" class="py-20 md:py-28 bg-barber-black relative overflow-hidden">
            <div class="absolute top-[20%] left-[-10%] w-[350px] h-[350px] rounded-full bg-barber-gold/5 blur-[100px] pointer-events-none"></div>
            
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Image Collage Column -->
                <div class="relative flex justify-center items-center">
                    <div class="relative w-full max-w-md">
                        <!-- Main large image -->
                        <div class="p-3 bg-gradient-to-tr from-vintage-gold/20 via-white/5 to-white/10 rounded-2xl border border-white/10 shadow-2xl overflow-hidden">
                            <img src="{{ asset('images/barber_about.png') }}" class="w-full h-auto rounded-xl object-cover" alt="Barbero Trabajando">
                        </div>
                        
                        <!-- Overlay smaller logo/badge -->
                        <div class="absolute -bottom-6 -right-6 p-2 bg-barber-black border border-vintage-gold/30 rounded-xl shadow-2xl max-w-[150px]">
                            <img src="{{ asset('images/logo.jpeg') }}" class="w-full h-auto rounded-lg" alt="Estilo Vintage">
                        </div>
                    </div>
                </div>

                <!-- Text Content Column -->
                <div class="space-y-6">
                    <div class="inline-flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-vintage-gold">
                        <span class="w-6 h-px bg-vintage-gold"></span>
                        <span>Más de 20 años de experiencia</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold font-cinzel leading-tight text-white">
                        Tu imagen merece estar en manos de profesionales
                    </h2>
                    <p class="text-gray-400 leading-relaxed text-sm sm:text-base">
                        En **King Cold Barber Shop** nos enorgullecemos de ofrecer una experiencia insuperable en cuidado masculino. Combinamos la precisión del afeitado tradicional con toalla caliente con las últimas tendencias de diseño y estilismo para el cabello.
                    </p>
                    <p class="text-gray-400 leading-relaxed text-sm sm:text-base">
                        Cada detalle está pensado para tu comodidad. Ven y relájate mientras nuestros experimentados barberos transforman tu estilo en una atmósfera clásica, acogedera y de primer nivel.
                    </p>

                    <!-- Info Icons Grid -->
                    <div class="grid grid-cols-2 gap-6 pt-4 border-t border-white/5">
                        <div class="flex items-start space-x-3">
                            <div class="text-2xl text-vintage-gold mt-1">
                                <i class="fa-solid fa-medal"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white font-cinzel">Calidad de Elite</h4>
                                <p class="text-xs text-gray-500 mt-1">Productos premium y cortes impecables.</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="text-2xl text-vintage-gold mt-1">
                                <i class="fa-solid fa-mug-hot"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-white font-cinzel">Ambiente Premium</h4>
                                <p class="text-xs text-gray-500 mt-1">Bebida de cortesía y espacio clásico.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- PRICING MENU (VINTAGE PRICE TABLE) -->
        @if($services->count() > 0)
            <section id="servicios" class="py-20 md:py-28 bg-barber-black relative overflow-hidden" style="background-image: linear-gradient(rgba(15, 15, 15, 0.9), rgba(15, 15, 15, 0.9)), url('{{ asset('images/barber_bg.png') }}'); background-attachment: fixed; background-size: cover;">
                <div class="max-w-4xl mx-auto px-6">
                    <div class="text-center space-y-4 mb-16">
                        <span class="text-xs uppercase tracking-widest font-bold text-vintage-gold block">&bull; Carta de Precios &bull;</span>
                        <h2 class="text-3xl sm:text-5xl font-extrabold font-cinzel text-white uppercase">Menú de Tarifas</h2>
                        <div class="w-16 h-1 bg-vintage-gold mx-auto"></div>
                    </div>

                    <!-- Pricing List Container -->
                    <div class="bg-[#121212]/90 border border-vintage-gold/20 rounded-2xl p-6 sm:p-10 shadow-2xl backdrop-blur-sm space-y-6">
                        @foreach($services as $service)
                            <div class="group">
                                <div class="flex justify-between items-baseline py-2">
                                    <h4 class="font-bold text-base sm:text-lg text-white font-cinzel group-hover:text-vintage-gold transition-colors">
                                        {{ $service->name }}
                                    </h4>
                                    <div class="flex-grow border-b border-dotted border-white/20 mx-2"></div>
                                    <span class="font-bold text-vintage-gold text-base sm:text-lg">
                                        ${{ number_format($service->price, 0) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-xs text-gray-500">
                                    <p class="italic max-w-lg truncate">{{ $service->description }}</p>
                                    <span class="whitespace-nowrap"><i class="fa-regular fa-clock text-vintage-gold/80"></i> {{ $service->duration }} min</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        <!-- STYLISTS / BARBERS SECTION -->
        <section class="py-20 md:py-28 bg-[#121212]">
            <div class="max-w-7xl mx-auto px-6 text-center space-y-4 mb-16">
                <div class="inline-flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-vintage-gold">
                    <span class="w-6 h-px bg-vintage-gold"></span>
                    <span>El Equipo de Trabajo</span>
                    <span class="w-6 h-px bg-vintage-gold"></span>
                </div>
                <h2 class="text-3xl sm:text-5xl font-extrabold font-cinzel text-white">Nuestros Estilistas Profesionales</h2>
                <p class="text-gray-400 max-w-xl mx-auto text-sm sm:text-base">
                    Conoce a los maestros de la tijera y la navaja que le darán forma a tu mejor versión.
                </p>
            </div>

            <!-- Dynamic Barbers Grid -->
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($barbers as $barber)
                    <!-- Barber Card -->
                    <div class="bg-barber-black border border-white/5 rounded-xl overflow-hidden shadow-lg group hover:border-vintage-gold/30 transition-all">
                        <div class="relative overflow-hidden aspect-square bg-slate-dark flex items-center justify-center">
                            @if($barber->photo && file_exists(public_path($barber->photo)))
                                <img src="{{ asset($barber->photo) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $barber->name }}">
                            @else
                                <img src="{{ asset('images/logo.jpeg') }}" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-500" alt="{{ $barber->name }}">
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                                <div class="text-xs text-vintage-gold">
                                    <i class="fa-solid fa-scissors"></i> Master Barber
                                </div>
                            </div>
                        </div>
                        <div class="p-6 text-center space-y-1 bg-barber-black">
                            <h3 class="text-lg font-bold font-cinzel text-white group-hover:text-vintage-gold transition-colors">
                                {{ $barber->name }}
                            </h3>
                            <p class="text-xs text-gray-500 font-semibold uppercase tracking-wider">
                                {{ $barber->specialty }}
                            </p>
                        </div>
                    </div>
                @empty
                    <!-- Fallback Barbers -->
                    <div class="bg-barber-black border border-white/5 p-8 rounded-xl shadow-lg col-span-4 text-center">
                        <i class="fa-solid fa-users-slash text-3xl text-vintage-gold mb-3"></i>
                        <p class="text-gray-400">Nuestro equipo está preparándose para recibirte. Muy pronto listaremos a nuestros barberos aquí.</p>
                    </div>
                @endforelse
            </div>
        </section>

        <!-- FOOTER -->
        <footer class="bg-barber-black border-t border-white/5 py-16 text-gray-500 relative">
            <div class="absolute bottom-0 left-0 w-full h-[5px] bg-gradient-to-r from-vintage-gold/20 via-vintage-gold to-vintage-gold/20"></div>
            
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <!-- Branding -->
                <div class="space-y-4 text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start space-x-3">
                        <img src="{{ asset('images/logo.jpeg') }}" class="w-12 h-12 rounded-full object-cover border border-vintage-gold/30 shadow-lg" alt="Logo">
                        <span class="text-xl font-bold tracking-widest font-barber uppercase text-white">
                            Barber <span class="text-vintage-gold">Shop</span>
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-gray-400 leading-relaxed">
                        Cortes clásicos, afeitado tradicional con navaja y los mejores estilos modernos. Diseñado exclusivamente para el hombre contemporáneo que valora su imagen.
                    </p>
                    <div class="flex justify-center md:justify-start space-x-4 pt-2">
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 hover:bg-vintage-gold hover:text-barber-black flex items-center justify-center text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 hover:bg-vintage-gold hover:text-barber-black flex items-center justify-center text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="w-8 h-8 rounded-full bg-white/5 hover:bg-vintage-gold hover:text-barber-black flex items-center justify-center text-white transition-colors"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>

                <!-- Contact & Location -->
                <div class="space-y-4 text-center md:text-left">
                    <h4 class="text-lg font-bold font-cinzel text-white border-b border-white/5 pb-2">Ubicación y Contacto</h4>
                    <p class="text-xs sm:text-sm text-gray-400"><i class="fa-solid fa-location-dot text-vintage-gold mr-2"></i> Av. Principal de la Ciudad #123, Zona Centro</p>
                    <p class="text-xs sm:text-sm text-gray-400"><i class="fa-solid fa-phone text-vintage-gold mr-2"></i> +123 456 7890</p>
                    <p class="text-xs sm:text-sm text-gray-400"><i class="fa-solid fa-envelope text-vintage-gold mr-2"></i> contacto@barbershop.com</p>
                </div>

                <!-- Open Hours -->
                <div class="space-y-4 text-center md:text-left">
                    <h4 class="text-lg font-bold font-cinzel text-white border-b border-white/5 pb-2">Horarios de Atención</h4>
                    <div class="text-xs sm:text-sm text-gray-400 space-y-2">
                        <div class="flex justify-between">
                            <span>Lunes a Viernes:</span>
                            <span class="text-vintage-gold font-semibold">9:00 AM - 8:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Sábados:</span>
                            <span class="text-vintage-gold font-semibold">9:00 AM - 6:00 PM</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Domingos:</span>
                            <span class="text-red-500 font-semibold">Cerrado</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-6 border-t border-white/5 pt-8 text-center text-xs">
                <p class="mb-2">&copy; {{ date('Y') }} Barber Shop - Todos los derechos reservados.</p>
                <p>Desarrollado con Laravel v{{ Illuminate\Foundation\Application::VERSION }} y Livewire.</p>
            </div>
        </footer>
    </body>
</html>
