<div>
    <div class="mb-6">
        <x-breadcrumbs :links="[
            ['label' => 'Barberos']
        ]" />
        <h1 class="text-2xl font-bold text-slate-dark">Gestión de Barberos</h1>
        <p class="text-gray-500 text-sm mt-1">Administra el equipo de profesionales y sus especialidades.</p>
    </div>

    <!-- Top Action Bar -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
        <div class="relative w-full md:w-1/3">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <i class="fa-solid fa-search text-gray-400"></i>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-bronze-gold focus:border-bronze-gold block w-full pl-10 p-2.5 shadow-sm" placeholder="Buscar por nombre o especialidad...">
        </div>
        <a href="{{ route('barbers.create') }}" class="w-full md:w-auto bg-slate-dark hover:bg-slate-800 text-white font-medium rounded-lg text-sm px-5 py-2.5 text-center flex items-center justify-center transition-colors shadow-sm">
            <i class="fa-solid fa-plus mr-2"></i> Nuevo Barbero
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th wire:click="sortBy('id')" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            ID
                            @if($sortField === 'id')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th wire:click="sortBy('name')" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors">
                            Barbero
                            @if($sortField === 'name')
                                <i class="fa-solid fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                            @else
                                <i class="fa-solid fa-sort ml-1 text-gray-300"></i>
                            @endif
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Teléfono
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            Especialidad
                        </th>
                        <th class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($barbers as $barber)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                #{{ $barber->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 rounded-full bg-bronze-gold/10 flex items-center justify-center text-bronze-gold font-bold mr-3 border border-bronze-gold/20 overflow-hidden">
                                        @if($barber->photo)
                                            <img src="{{ asset($barber->photo) }}" class="w-full h-full object-cover rounded-full">
                                        @else
                                            {{ substr($barber->name, 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-dark">{{ $barber->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-dark">
                                {{ $barber->email ?? 'Sin correo' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $barber->phone ?? 'Sin teléfono' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-md bg-slate-100 text-slate-700 border border-slate-200">
                                    {{ $barber->specialty ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('barbers.edit', $barber->id) }}" class="inline-block text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 p-2 rounded-md transition-colors mr-2">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button wire:click="confirmDelete({{ $barber->id }})" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-md transition-colors">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-scissors text-4xl text-gray-300 mb-3"></i>
                                    <p class="text-lg font-medium text-gray-600">No se encontraron barberos.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($barbers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $barbers->links() }}
            </div>
        @endif
    </div>
</div>
