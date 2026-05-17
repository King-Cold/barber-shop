<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <x-breadcrumbs :links="[
        ['label' => 'Barberos', 'url' => route('barbers')],
        ['label' => 'Nuevo Barbero']
    ]" />

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-bold text-slate-dark">
                <i class="fa-solid fa-scissors mr-2 text-bronze-gold"></i>
                Registrar Nuevo Barbero
            </h2>
            <a href="{{ route('barbers') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium transition-colors">
                <i class="fa-solid fa-arrow-left mr-1"></i> Cancelar
            </a>
        </div>

        <form wire:submit.prevent="save" class="p-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Photo Column -->
                <div class="md:col-span-1 flex flex-col items-center">
                    <label class="block text-sm font-semibold text-gray-700 mb-4 w-full text-center">Foto de Perfil</label>
                    <div class="relative group">
                        <div class="w-48 h-48 rounded-full bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden transition-all group-hover:border-bronze-gold">
                            @if ($photo)
                                <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full object-cover rounded-full">
                            @else
                                <div class="text-center p-4">
                                    <i class="fa-solid fa-camera text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-xs text-gray-500">Haz clic para subir</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" wire:model="photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    </div>
                    @error('photo') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                    <div wire:loading wire:target="photo" class="text-xs text-bronze-gold mt-2 italic">Subiendo imagen...</div>
                </div>

                <!-- Fields Column -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2 border-b border-gray-100 pb-2">
                        <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Información Profesional</h3>
                    </div>

                    <!-- Name -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nombre Completo <span class="text-red-500">*</span></label>
                        <input wire:model="name" type="text" id="name" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Specialty -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="specialty" class="block text-sm font-semibold text-gray-700 mb-2">Especialidad <span class="text-red-500">*</span></label>
                        <input wire:model="specialty" type="text" id="specialty" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                        @error('specialty') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">Teléfono</label>
                        <input wire:model="phone" type="text" id="phone" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                    </div>

                    <!-- Email -->
                    <div class="col-span-2 md:col-span-1">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico <span class="text-red-500">*</span></label>
                        <input wire:model="email" type="email" id="email" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm">
                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Address -->
                    <div class="col-span-2">
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Dirección</label>
                        <input wire:model="address" type="text" id="address" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-bronze-gold focus:border-bronze-gold text-sm transition-all shadow-sm" placeholder="Ingresa la dirección del barbero">
                        @error('address') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mt-12 flex items-center justify-end space-x-4 border-t border-gray-100 pt-8">
                <a href="{{ route('barbers') }}" class="px-8 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-10 py-3 text-sm font-bold text-white bg-bronze-gold hover:bg-yellow-600 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2"></i>
                    Registrar Barbero
                </button>
            </div>
        </form>
    </div>
</div>
