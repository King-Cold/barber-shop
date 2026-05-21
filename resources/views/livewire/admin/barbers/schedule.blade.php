<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header & Breadcrumbs -->
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <x-breadcrumbs :links="[
                ['label' => 'Barberos', 'url' => route('admin.barbers.index')],
                ['label' => 'Horario de Trabajo']
            ]" />
            <h1 class="text-2xl font-bold text-slate-dark mt-1">Horario de Trabajo</h1>
            <p class="text-gray-500 text-sm">Define los días de la semana y las horas en que labora este profesional.</p>
        </div>
        <div>
            <a href="{{ route('admin.barbers.index') }}" class="inline-flex items-center bg-barber-black hover:bg-slate-800 text-white font-medium rounded-lg text-sm px-4 py-2.5 shadow-sm transition-colors font-sans" wire:navigate>
                <i class="fa-solid fa-arrow-left mr-2 text-vintage-gold"></i> Volver a Barberos
            </a>
        </div>
    </div>

    <!-- Professional Profile Banner -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 flex flex-col md:flex-row items-center gap-6 relative overflow-hidden">
        <div class="absolute left-0 top-0 h-full w-1.5 bg-vintage-gold"></div>
        
        <!-- Large Avatar -->
        <div class="relative group">
            <div class="absolute -inset-0.5 bg-gradient-to-tr from-vintage-gold to-yellow-600 rounded-full blur opacity-25 group-hover:opacity-40 transition duration-300"></div>
            <div class="relative w-24 h-24 rounded-full bg-slate-100 flex items-center justify-center text-vintage-gold font-bold text-3xl border-2 border-vintage-gold/30 overflow-hidden shadow-md">
                @if($barber->photo)
                    <img src="{{ asset($barber->photo) }}" class="w-full h-full object-cover">
                @else
                    {{ substr($barber->name, 0, 1) }}
                @endif
            </div>
        </div>

        <!-- Professional Details -->
        <div class="flex-1 text-center md:text-left">
            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-md bg-vintage-gold/15 text-vintage-gold border border-vintage-gold/20 uppercase tracking-widest font-barber">
                {{ $barber->specialty ?? 'Especialista General' }}
            </span>
            <h2 class="text-2xl font-bold text-slate-dark mt-2 font-cinzel tracking-wide">{{ $barber->name }}</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 text-sm text-gray-500 font-sans">
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <i class="fa-solid fa-envelope text-vintage-gold"></i>
                    <span>{{ $barber->email ?? 'Sin correo registrado' }}</span>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <i class="fa-solid fa-phone text-vintage-gold"></i>
                    <span>{{ $barber->phone ?? 'Sin teléfono registrado' }}</span>
                </div>
                <div class="flex items-center justify-center md:justify-start gap-2">
                    <i class="fa-solid fa-calendar-days text-vintage-gold"></i>
                    <span>Gestión de Disponibilidad Semanal</span>
                </div>
            </div>
        </div>
    </div>

    @php
    $dayNames = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo',
    ];
    $dayIcons = [
        1 => 'fa-calendar-day',
        2 => 'fa-calendar-day',
        3 => 'fa-calendar-day',
        4 => 'fa-calendar-day',
        5 => 'fa-calendar-day',
        6 => 'fa-calendar-day',
        7 => 'fa-mug-hot',
    ];
    @endphp

    <!-- Schedule Form -->
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
            <h3 class="text-lg font-bold text-slate-dark mb-6 flex items-center">
                <i class="fa-solid fa-clock mr-2 text-vintage-gold"></i>
                Días y Horarios Laborables
            </h3>

            <div class="space-y-6 divide-y divide-gray-100">
                @foreach($dayNames as $dayNum => $dayName)
                    <div class="py-6 first:pt-0 last:pb-0 grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                        
                        <!-- Day Info & Switch -->
                        <div class="lg:col-span-3 flex items-center justify-between lg:justify-start gap-4">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-lg bg-gray-55 flex items-center justify-center text-gray-500 border border-gray-200">
                                    <i class="fa-solid {{ $dayIcons[$dayNum] }} text-vintage-gold"></i>
                                </span>
                                <span class="font-bold text-slate-dark text-base">{{ $dayName }}</span>
                            </div>
                            
                            <!-- Toggle switch -->
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="schedules.{{ $dayNum }}.is_working" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vintage-gold"></div>
                            </label>
                        </div>

                        <!-- Schedule settings (only shown if working) -->
                        <div class="lg:col-span-9">
                            @if($schedules[$dayNum]['is_working'])
                                <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                    
                                    <!-- Work Hours -->
                                    <div class="md:col-span-5 grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wider">Hora entrada</label>
                                            <input type="time" wire:model="schedules.{{ $dayNum }}.start_time" class="block w-full border-gray-200 focus:border-vintage-gold focus:ring-vintage-gold rounded-lg shadow-sm text-sm">
                                            @error("schedules.{$dayNum}.start_time") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wider">Hora salida</label>
                                            <input type="time" wire:model="schedules.{{ $dayNum }}.end_time" class="block w-full border-gray-200 focus:border-vintage-gold focus:ring-vintage-gold rounded-lg shadow-sm text-sm">
                                            @error("schedules.{$dayNum}.end_time") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <!-- Lunch Break Toggle -->
                                    <div class="md:col-span-3 flex items-center justify-start gap-2 pt-4 md:pt-0 pl-0 md:pl-4">
                                        <input type="checkbox" id="lunch_{{ $dayNum }}" wire:model.live="schedules.{{ $dayNum }}.has_lunch" class="rounded border-gray-300 text-vintage-gold focus:ring-vintage-gold">
                                        <label for="lunch_{{ $dayNum }}" class="text-xs font-bold text-gray-500 cursor-pointer">Hora de Almuerzo</label>
                                    </div>

                                    <!-- Lunch Hours (only if lunch enabled) -->
                                    <div class="md:col-span-4 grid grid-cols-2 gap-3">
                                        @if($schedules[$dayNum]['has_lunch'])
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wider">Inicio receso</label>
                                                <input type="time" wire:model="schedules.{{ $dayNum }}.lunch_start_time" class="block w-full border-gray-200 focus:border-vintage-gold focus:ring-vintage-gold rounded-lg shadow-sm text-sm">
                                                @error("schedules.{$dayNum}.lunch_start_time") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wider">Fin receso</label>
                                                <input type="time" wire:model="schedules.{{ $dayNum }}.lunch_end_time" class="block w-full border-gray-200 focus:border-vintage-gold focus:ring-vintage-gold rounded-lg shadow-sm text-sm">
                                                @error("schedules.{$dayNum}.lunch_end_time") <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                            </div>
                                        @else
                                            <div class="col-span-2 text-center text-xs text-gray-400 italic bg-gray-50 border border-dashed border-gray-200 py-3 rounded-lg">
                                                Sin receso de almuerzo configurado.
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @else
                                <div class="text-sm text-gray-400 italic bg-gray-55 border border-dashed border-gray-200 py-3 px-4 rounded-xl flex items-center justify-between">
                                    <span>Cerrado / Descanso - No trabaja este día.</span>
                                    <i class="fa-solid fa-moon text-vintage-gold/50"></i>
                                </div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-4 border-t border-gray-150 pt-6">
            <a href="{{ route('admin.barbers.index') }}" class="px-8 py-3 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-55 transition-all shadow-sm" wire:navigate>
                Cancelar
            </a>
            <button type="submit" class="px-10 py-3 text-sm font-bold text-white bg-vintage-gold hover:bg-yellow-600 rounded-lg shadow-md hover:shadow-lg transition-all flex items-center">
                <i class="fa-solid fa-save mr-2 text-white"></i> Guardar Horario
            </button>
        </div>
    </form>
</div>
