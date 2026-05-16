<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-dark text-white transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-xl flex flex-col">
    
    <!-- Logo Section -->
    <div class="flex items-center justify-center pt-10 pb-6">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center w-full px-4 text-center">
            <div class="mb-4">
                <i class="fa-solid fa-scissors text-6xl text-bronze-gold"></i>
            </div>
            <h1 class="text-2xl font-bold text-white tracking-widest font-barber uppercase">
                Barber <span class="text-bronze-gold">Shop</span>
            </h1>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-4 flex flex-col space-y-3 overflow-y-auto font-sans">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-md transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-bronze-gold text-white font-medium' : 'text-gray-300 hover:text-white hover:bg-slate-700/50' }}">
            <i class="fa-solid fa-chart-line w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Dashboard</span>
        </a>
        
        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="{{ route('appointments') }}" class="flex items-center px-4 py-3 rounded-md transition-colors duration-200 {{ request()->routeIs('appointments') ? 'bg-bronze-gold text-white font-medium' : 'text-gray-300 hover:text-white hover:bg-slate-700/50' }}">
            <i class="fa-regular fa-calendar-days w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Citas</span>
        </a>

        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="{{ route('clients') }}" class="flex items-center px-4 py-3 rounded-md transition-colors duration-200 {{ request()->routeIs('clients') ? 'bg-bronze-gold text-white font-medium' : 'text-gray-300 hover:text-white hover:bg-slate-700/50' }}">
            <i class="fa-solid fa-users w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Clientes</span>
        </a>
        
        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="{{ route('barbers') }}" class="flex items-center px-4 py-3 rounded-md transition-colors duration-200 {{ request()->routeIs('barbers') ? 'bg-bronze-gold text-white font-medium' : 'text-gray-300 hover:text-white hover:bg-slate-700/50' }}">
            <i class="fa-solid fa-scissors w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Barberos</span>
        </a>
        
        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="{{ route('services') }}" class="flex items-center px-4 py-3 rounded-md transition-colors duration-200 {{ request()->routeIs('services') ? 'bg-bronze-gold text-white font-medium' : 'text-gray-300 hover:text-white hover:bg-slate-700/50' }}">
            <i class="fa-solid fa-spray-can w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Servicios</span>
        </a>

        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="{{ route('users') }}" class="flex items-center px-4 py-3 rounded-md transition-colors duration-200 {{ request()->routeIs('users') ? 'bg-bronze-gold text-white font-medium' : 'text-gray-300 hover:text-white hover:bg-slate-700/50' }}">
            <i class="fa-solid fa-user-shield w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Usuarios</span>
        </a>
    </nav>
</aside>
