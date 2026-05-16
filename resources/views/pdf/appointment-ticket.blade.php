<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Cita</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #fff;
            line-height: 1.5;
        }
        .container {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
            border: 1px solid #e5e7eb;
            padding: 40px;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 2px solid #8B7355;
            padding-bottom: 20px;
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 15px;
        }
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #1F232D;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin: 0;
        }
        .subtitle {
            font-size: 11px;
            color: #8B7355;
            text-transform: uppercase;
            letter-spacing: 4px;
            margin-top: 5px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-item {
            margin-bottom: 20px;
        }
        .info-label {
            font-size: 9px;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 4px;
            font-weight: bold;
        }
        .info-value {
            font-size: 15px;
            color: #1f2937;
            font-weight: 500;
        }
        .divider {
            height: 1px;
            background-color: #f3f4f6;
            margin: 15px 0;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }
        .price-section {
            background-color: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: right;
        }
        .price-label {
            font-size: 12px;
            color: #4b5563;
        }
        .price-value {
            font-size: 24px;
            font-weight: bold;
            color: #8B7355;
        }
        .qr-mock {
            width: 60px;
            height: 60px;
            border: 1px solid #eee;
            margin: 20px auto 10px;
        }
        table {
            width: 100%;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            @php
                $logoPath = public_path('images/logo.jpeg');
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
            
            <h1 class="title">Barber <span style="color: #8B7355;">Shop</span></h1>
            <div class="subtitle">Comprobante de Reservación</div>
        </div>

        <table class="info-grid">
            <tr>
                <td colspan="2" class="info-item">
                    <div class="info-label">Cliente</div>
                    <div class="info-value">{{ $appointment->client->name }}</div>
                </td>
            </tr>
            <tr>
                <td class="info-item" width="50%">
                    <div class="info-label">Fecha</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($appointment->date)->format('d M, Y') }}</div>
                </td>
                <td class="info-item" width="50%">
                    <div class="info-label">Hora</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}</div>
                </td>
            </tr>
            <tr>
                <td class="info-item">
                    <div class="info-label">Servicio</div>
                    <div class="info-value">{{ $appointment->service->name }}</div>
                </td>
                <td class="info-item">
                    <div class="info-label">Barbero</div>
                    <div class="info-value">{{ $appointment->barber->name }}</div>
                </td>
            </tr>
        </table>

        <div class="price-section">
            <div class="price-label">Monto Total</div>
            <div class="price-value">${{ number_format($appointment->service->price, 2) }}</div>
        </div>

        <div class="footer">
            <p>ID de Cita: #{{ str_pad($appointment->id, 6, '0', STR_PAD_LEFT) }}</p>
            <p>Gracias por elegirnos. Si necesita cancelar, por favor hágalo con 24h de anticipación.</p>
            <p style="margin-top: 10px; font-weight: bold; color: #1F232D;">{{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
