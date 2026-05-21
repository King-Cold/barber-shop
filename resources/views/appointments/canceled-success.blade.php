<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Cancelada - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Oswald:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .font-barber { font-family: 'Oswald', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100 transform transition-all">
        <div class="bg-[#1F232D] p-8 text-center relative">
            <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg border-4 border-slate-50">
                <i class="fa-solid fa-xmark text-4xl text-red-500"></i>
            </div>
            <h1 class="text-white font-barber text-3xl uppercase tracking-widest mb-2">Cancelada</h1>
            <p class="text-slate-400 text-sm">Tu cita ha sido cancelada exitosamente</p>
        </div>
        
        <div class="pt-16 pb-10 px-8">
            <div class="space-y-6">
                <div class="flex items-center space-x-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-12 h-12 bg-[#8B7355]/10 rounded-xl flex items-center justify-center text-[#8B7355]">
                        <i class="fa-solid fa-scissors text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Servicio</p>
                        <p class="text-slate-700 font-semibold">{{ $appointment->service->name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Fecha</p>
                        <p class="text-slate-700 font-semibold">{{ \Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Hora</p>
                        <p class="text-slate-700 font-semibold">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="w-12 h-12 bg-[#8B7355]/10 rounded-xl flex items-center justify-center text-[#8B7355]">
                        <i class="fa-solid fa-user-tie text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">Barbero</p>
                        <p class="text-slate-700 font-semibold">{{ $appointment->barber->name }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center">
                <p class="text-slate-500 text-sm mb-6">Si cambias de opinión, estaremos encantados de agendarte nuevamente.</p>
                <div class="flex flex-col space-y-3">
                    <a href="{{ config('app.url') }}" class="w-full bg-[#8B7355] hover:bg-[#746048] text-white font-bold py-4 rounded-2xl shadow-lg shadow-[#8B7355]/30 transition-all uppercase tracking-widest text-sm">
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-50 py-4 text-center border-t border-slate-100">
            <p class="text-slate-400 text-xs font-medium uppercase tracking-widest">© {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
