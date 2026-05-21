<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita - Barber Shop</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
        }
        .ticket-container {
            width: 380px;
            margin: 0 auto;
            border: 2px dashed #d4af37;
            padding: 25px;
            background-color: #fafafa;
            border-radius: 8px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .logo-text {
            font-family: 'Georgia', serif;
            font-size: 26px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .logo-subtext {
            font-size: 9px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 5px;
        }
        .ticket-title {
            font-size: 14px;
            font-weight: bold;
            color: #d4af37;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 15px;
            display: inline-block;
            background-color: #111827;
            padding: 5px 15px;
            color: #ffffff;
            border-radius: 3px;
        }
        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 3px;
        }
        .info-row {
            margin-bottom: 12px;
            font-size: 13px;
        }
        .info-label {
            color: #64748b;
            display: inline-block;
            width: 90px;
        }
        .info-value {
            color: #0f172a;
            font-weight: bold;
        }
        .time-highlight {
            background-color: #f1f5f9;
            border-left: 4px solid #d4af37;
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .time-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #64748b;
            letter-spacing: 1px;
        }
        .time-value {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-top: 3px;
        }
        .footer {
            border-top: 2px dashed #cbd5e1;
            padding-top: 15px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            margin-top: 25px;
            line-height: 1.4;
        }
        .barcode-placeholder {
            margin: 15px auto 5px auto;
            width: 180px;
            height: 35px;
            border-left: 2px solid #111827;
            border-right: 2px solid #111827;
            background: repeating-linear-gradient(
                90deg,
                #111827,
                #111827 2px,
                transparent 2px,
                transparent 5px
            );
        }
    </style>
</head>
<body>

    <div class="ticket-container">
        <div class="header">
            @php
                $logoPath = public_path('images/logo-barber.png');
                $logoBase64 = '';
                if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp

            @if($logoBase64)
                <img src="{{ $logoBase64 }}" class="logo">
            @endif

            <div class="logo-text">Barber <span style="color: #d4af37;">Shop</span></div>
            <div class="logo-subtext">Cita Confirmada</div>
            <div class="ticket-title">Voucher de Cita</div>
        </div>

        <div class="time-highlight">
            <div class="time-label">Fecha y Hora de la Cita:</div>
            <div class="time-value">
                {{ \Carbon\Carbon::parse($appointment->date)->locale('es')->isoFormat('dddd D [de] MMMM') }}<br>
                <span style="color: #d4af37;">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</span>
            </div>
        </div>

        <div>
            <div class="section-title">Detalles del Cliente</div>
            <div class="info-row">
                <span class="info-label">Cliente:</span>
                <span class="info-value">{{ $appointment->client->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Teléfono:</span>
                <span class="info-value">{{ $appointment->client->phone ?? 'Sin teléfono' }}</span>
            </div>
        </div>

        <div style="margin-top: 20px;">
            <div class="section-title">Detalles del Servicio</div>
            <div class="info-row">
                <span class="info-label">Servicio:</span>
                <span class="info-value">{{ $appointment->service->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Barbero:</span>
                <span class="info-value">{{ $appointment->barber->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Duración:</span>
                <span class="info-value">{{ $appointment->service->duration }} min</span>
            </div>
            <div class="info-row" style="margin-top: 8px; border-top: 1px solid #e2e8f0; padding-top: 8px;">
                <span class="info-label" style="font-size: 14px; color: #111827;">Precio Total:</span>
                <span class="info-value" style="font-size: 16px; color: #166534;">${{ number_format($appointment->service->price, 2) }}</span>
            </div>
        </div>

        <div style="text-align: center;">
            <div class="barcode-placeholder"></div>
            <span style="font-size: 9px; color: #94a3b8; font-family: monospace; letter-spacing: 2px;">#APP-{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>

        <div class="footer">
            <strong>¡Te esperamos!</strong><br>
            Por favor, llega 10 minutos antes de tu cita.<br>
            Si necesitas cancelar, avísanos con 2 horas de anticipación.<br>
            <span style="font-size: 8px; margin-top: 5px; display: block;">&copy; {{ date('Y') }} Barber Shop. Todos los derechos reservados.</span>
        </div>
    </div>

</body>
</html>
