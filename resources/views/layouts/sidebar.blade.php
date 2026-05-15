<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-dark text-white transition-transform duration-300 transform lg:translate-x-0 lg:static lg:inset-0 shadow-xl flex flex-col">
    
    <!-- Logo Section -->
    <div class="flex items-center justify-center pt-8 pb-6">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center w-full px-4">
            <div class="w-24 h-auto flex items-center justify-center mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Barber Shop Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-2xl font-bold text-white tracking-widest font-sans uppercase">
                Barber <span class="text-bronze-gold">Shop</span>
            </h1>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-4 flex flex-col space-y-3 overflow-y-auto font-sans">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-md transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-bronze-gold text-white font-medium' : 'text-gray-300 hover:text-white hover:bg-slate-700/50' }}">
            <i class="fa-solid fa-chart-line w- text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Dashboard</span>
        </a>
        
        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="#" class="flex items-center px-4 py-3 rounded-md text-gray-300 hover:text-white hover:bg-slate-700/50 transition-colors duration-200">
            <i class="fa-regular fa-calendar-days w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Citas</span>
        </a>

        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="#" class="flex items-center px-4 py-3 rounded-md text-gray-300 hover:text-white hover:bg-slate-700/50 transition-colors duration-200">
            <i class="fa-solid fa-users w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Clientes</span>
        </a>
        
        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="#" class="flex items-center px-4 py-3 rounded-md text-gray-300 hover:text-white hover:bg-slate-700/50 transition-colors duration-200">
            <i class="fa-solid fa-scissors w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Barberos</span>
        </a>
        
        <div class="h-px bg-slate-700/50 mx-2"></div>

        <a href="#" class="flex items-center px-4 py-3 rounded-md text-gray-300 hover:text-white hover:bg-slate-700/50 transition-colors duration-200">
            <i class="fa-solid fa-spray-can w-6 text-center text-sm"></i>
            <span class="ml-3 text-[15px]">Servicios</span>
        </a>
    </nav>
</aside>
