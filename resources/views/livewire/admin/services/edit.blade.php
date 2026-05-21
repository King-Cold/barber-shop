<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-breadcrumbs :links="[
        ['label' => 'Servicios', 'url' => route('admin.services.index')],
        ['label' => 'Editar Servicio']
    ]" />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-bold text-slate-dark">
                <i class="fa-solid fa-bell-concierge mr-2 text-bronze-gold"></i>
                Editar Configuración de Servicio
            </h2>
            <a href="{{ route('admin.services.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Cancelar
            </a>
        </div>

        <form wire:submit.prevent="save" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <div class="col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre del Servicio <span class="text-red-500">*</span></label>
                    <input wire:model="name" type="text" id="name" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                    @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Price -->
                <div class="col-span-2 md:col-span-1">
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Precio ($) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">$</span>
                        </div>
                        <input wire:model="price" type="number" step="0.01" id="price" class="block w-full pl-8 pr-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('price') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                    </div>
                    @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Duration -->
                <div class="col-span-2 md:col-span-1">
                    <label for="duration" class="block text-sm font-semibold text-gray-700 mb-2">Duración (minutos) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-regular fa-clock text-gray-400 text-xs"></i>
                        </div>
                        <input wire:model="duration" type="number" id="duration" class="block w-full pl-10 pr-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('duration') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror">
                    </div>
                    @error('duration') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Descripción</label>
                    <textarea wire:model="description" id="description" rows="5" class="block w-full px-4 py-3 border rounded-lg text-sm transition-all shadow-sm @error('description') border-red-500 focus:ring-red-500 focus:border-red-500 ring-1 ring-red-500/20 @else border-gray-300 focus:ring-bronze-gold focus:border-bronze-gold @enderror"></textarea>
                    @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-12 flex items-center justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('admin.services.index') }}" class="px-8 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-10 py-3 text-sm font-bold text-white bg-slate-dark hover:bg-slate-800 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2"></i>
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>
