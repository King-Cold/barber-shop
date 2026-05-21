@php
// Arreglo de iconos (key-value)
$links = [
    [
        'name' => 'Dashboard',
        'icon' => 'fa-solid fa-chart-line',
        'href' => route('dashboard'),
        'active' => request()->routeIs('dashboard'),
        'visible' => true,
    ],
    [
        'header' => 'Gestión',
        'visible' => auth()->user()->isAdmin() || auth()->user()->isSuperAdmin(),
    ],
    [
        'name' => 'Citas',
        'icon' => 'fa-regular fa-calendar-days',
        'href' => route('admin.appointments.index'),
        'active' => request()->routeIs('admin.appointments.*'),
        'visible' => auth()->user()->isAdmin() || auth()->user()->isSuperAdmin(),
    ],
    [
        'name' => 'Clientes',
        'icon' => 'fa-solid fa-users',
        'href' => route('admin.clients.index'),
        'active' => request()->routeIs('admin.clients.*'),
        'visible' => auth()->user()->isAdmin() || auth()->user()->isSuperAdmin(),
    ],
    [
        'name' => 'Barberos',
        'icon' => 'fa-solid fa-scissors',
        'href' => route('admin.barbers.index'),
        'active' => request()->routeIs('admin.barbers.*'),
        'visible' => auth()->user()->isAdmin() || auth()->user()->isSuperAdmin(),
    ],
    [
        'name' => 'Servicios',
        'icon' => 'fa-solid fa-spray-can',
        'href' => route('admin.services.index'),
        'active' => request()->routeIs('admin.services.*'),
        'visible' => auth()->user()->isAdmin() || auth()->user()->isSuperAdmin(),
    ],
    [
        'name' => 'Usuarios',
        'icon' => 'fa-solid fa-user-shield',
        'href' => route('admin.users.index'),
        'active' => request()->routeIs('admin.users.*'),
        'visible' => auth()->user()->isSuperAdmin(),
    ],
];
@endphp

<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
    class="fixed inset-y-0 left-0 z-50 w-64 bg-barber-black text-white border-r border-white/5 transition-transform duration-300 transform lg:translate-x-0 shadow-2xl flex flex-col">
    
    <!-- Logo Section -->
    <div class="flex items-center justify-center pt-8 pb-6 border-b border-white/5">
        <a href="{{ route('dashboard') }}" class="flex flex-col items-center w-full px-4 text-center group">
            <div class="mb-3 relative">
                <img src="{{ asset('images/logo-barber.png') }}" class="relative w-20  object-cover" alt="Logo">
            </div>
            <h1 class="text-xl font-bold text-white tracking-widest font-barber uppercase">
                Barber <span class="text-vintage-gold group-hover:text-yellow-400 transition-colors">Shop</span>
            </h1>
            <span class="text-[10px] text-gray-500 font-medium uppercase tracking-widest mt-1 block">Panel Administrativo</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-6 flex flex-col space-y-2 overflow-y-auto font-sans">
        <ul class="space-y-2">
            @foreach ($links as $link)
                @if (!isset($link['visible']) || $link['visible'])
                    <li>
                        {{-- Revisa si existe una llave/propiedad llamada header --}}
                        @isset ($link['header'])
                            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                {{ $link['header'] }}
                            </div>
                        @else
                            {{-- Revisa si existe una llave/propiedad llamada submenu --}}
                            @isset ($link['submenu'])
                                <button type="button" class="flex items-center w-full justify-between px-4 py-3 text-[14px] font-medium tracking-wide uppercase font-barber rounded-lg text-gray-400 hover:text-vintage-gold hover:bg-white/5 group" aria-controls="dropdown-example" data-collapse-toggle="dropdown-example">
                                    <span class="w-6 h-6 inline-flex items-center justify-center text-gray-400 group-hover:text-vintage-gold" >
                                        <i class="{{ $link['icon'] }} text-base"></i>
                                    </span>
                                    <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $link['name'] }}</span>
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-vintage-gold" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 9-7 7-7-7"/></svg>
                                </button>

                                <ul id="dropdown-example" class="hidden py-2 space-y-1 pl-6">
                                    @foreach ($link['submenu'] as $item)
                                        <li>
                                            <a href="{{ $item['href'] }}" class="flex items-center px-4 py-2 text-[13px] rounded-lg transition-all duration-200 text-gray-400 hover:text-vintage-gold hover:bg-white/5 font-medium">{{ $item['name'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a href="{{ $link['href'] }}" 
                                   class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ $link['active'] ? 'bg-vintage-gold text-barber-black font-bold shadow-lg shadow-yellow-500/10' : 'text-gray-400 hover:text-vintage-gold hover:bg-white/5' }}">
                                    <span class="w-6 h-6 inline-flex items-center justify-center {{ $link['active'] ? 'text-barber-black' : 'text-gray-400 group-hover:text-vintage-gold' }}" >
                                        <i class="{{ $link['icon'] }} text-center text-base"></i>
                                    </span>
                                    <span class="ml-3 text-[14px] font-medium tracking-wide uppercase font-barber">{{ $link['name'] }}</span>
                                </a>
                            @endisset
                        @endisset
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>
</aside>
