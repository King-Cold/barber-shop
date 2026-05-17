<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-barber-black text-white border-r border-white/5 transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-2xl flex flex-col">
    
    <!-- Logo Section -->
    <div class="flex items-center justify-center pt-8 pb-6 border-b border-white/5">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center w-full px-4 text-center group">
            <div class="mb-3 relative">
                <div class="absolute -inset-0.5 bg-gradient-to-tr from-vintage-gold to-yellow-600 rounded-full blur opacity-30 group-hover:opacity-60 transition duration-300"></div>
                <img src="{{ asset('images/logo.jpeg') }}" class="relative w-20 h-20 rounded-full object-cover border border-vintage-gold/50 shadow-2xl transition-transform duration-300 group-hover:scale-105" alt="Logo">
            </div>
            <h1 class="text-xl font-bold text-white tracking-widest font-barber uppercase">
                Barber <span class="text-vintage-gold group-hover:text-yellow-400 transition-colors">Shop</span>
            </h1>
            <span class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-1 block">Panel Administrativo</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-6 flex flex-col space-y-2 overflow-y-auto font-sans">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-vintage-gold text-barber-black font-bold shadow-lg shadow-yellow-500/10' : 'text-gray-400 hover:text-vintage-gold hover:bg-white/5' }}">
            <i class="fa-solid fa-chart-line w-6 text-center text-base"></i>
            <span class="ml-3 text-[14px] font-medium tracking-wide uppercase font-barber">Dashboard</span>
        </a>
        
        <div class="h-px bg-white/5 my-2 mx-2"></div>

        {{-- Citas y Clientes visibles para Administradores y Super Administradores --}}
        @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
            <a href="{{ route('appointments') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('appointments*') ? 'bg-vintage-gold text-barber-black font-bold shadow-lg shadow-yellow-500/10' : 'text-gray-400 hover:text-vintage-gold hover:bg-white/5' }}">
                <i class="fa-regular fa-calendar-days w-6 text-center text-base"></i>
                <span class="ml-3 text-[14px] font-medium tracking-wide uppercase font-barber">Citas</span>
            </a>

            <div class="h-px bg-white/5 my-2 mx-2"></div>

            <a href="{{ route('clients') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('clients*') ? 'bg-vintage-gold text-barber-black font-bold shadow-lg shadow-yellow-500/10' : 'text-gray-400 hover:text-vintage-gold hover:bg-white/5' }}">
                <i class="fa-solid fa-users w-6 text-center text-base"></i>
                <span class="ml-3 text-[14px] font-medium tracking-wide uppercase font-barber">Clientes</span>
            </a>
            
            <div class="h-px bg-white/5 my-2 mx-2"></div>

            <a href="{{ route('barbers') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('barbers*') ? 'bg-vintage-gold text-barber-black font-bold shadow-lg shadow-yellow-500/10' : 'text-gray-400 hover:text-vintage-gold hover:bg-white/5' }}">
                <i class="fa-solid fa-scissors w-6 text-center text-base"></i>
                <span class="ml-3 text-[14px] font-medium tracking-wide uppercase font-barber">Barberos</span>
            </a>
            
            <div class="h-px bg-white/5 my-2 mx-2"></div>

            <a href="{{ route('services') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('services*') ? 'bg-vintage-gold text-barber-black font-bold shadow-lg shadow-yellow-500/10' : 'text-gray-400 hover:text-vintage-gold hover:bg-white/5' }}">
                <i class="fa-solid fa-spray-can w-6 text-center text-base"></i>
                <span class="ml-3 text-[14px] font-medium tracking-wide uppercase font-barber">Servicios</span>
            </a>
        @endif

        {{-- Solo el Super Administrador puede ver y gestionar el módulo de Usuarios --}}
        @if(auth()->user()->isSuperAdmin())
            <div class="h-px bg-white/5 my-2 mx-2"></div>

            <a href="{{ route('users') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('users*') ? 'bg-vintage-gold text-barber-black font-bold shadow-lg shadow-yellow-500/10' : 'text-gray-400 hover:text-vintage-gold hover:bg-white/5' }}">
                <i class="fa-solid fa-user-shield w-6 text-center text-base"></i>
                <span class="ml-3 text-[14px] font-medium tracking-wide uppercase font-barber">Usuarios</span>
            </a>
        @endif
    </nav>

    <!-- Footer of Sidebar -->
    <div class="p-4 border-t border-white/5 text-center text-[10px] text-gray-600 font-medium tracking-wider">
        King Cold &copy; {{ date('Y') }}
    </div>
</aside>
