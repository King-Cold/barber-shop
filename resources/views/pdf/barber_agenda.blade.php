<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Agenda de Trabajo - Barber Shop</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }
        .header {
            border-bottom: 3px solid #d4af37;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo-area {
            float: left;
            width: 50%;
        }
        .logo-text {
            font-family: 'Georgia', serif;
            font-size: 28px;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .logo-subtext {
            font-size: 10px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-top: 5px;
        }
        .date-area {
            float: right;
            width: 50%;
            text-align: right;
        }
        .date-title {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .date-value {
            font-size: 18px;
            font-weight: bold;
            color: #d4af37;
            margin-top: 5px;
        }
        .clearfix {
            clear: both;
        }
        .barber-info {
            background-color: #f8fafc;
            border-left: 4px solid #d4af37;
            padding: 15px 20px;
            margin-bottom: 30px;
            border-radius: 4px;
        }
        .barber-name {
            font-size: 18px;
            font-weight: bold;
            color: #0f172a;
        }
        .barber-specialty {
            font-size: 13px;
            color: #64748b;
            margin-top: 3px;
        }
        .agenda-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .agenda-table th {
            background-color: #111827;
            color: #ffffff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 12px 15px;
            text-align: left;
            border-bottom: 2px solid #d4af37;
        }
        .agenda-table td {
            padding: 14px 15px;
            font-size: 13px;
            border-bottom: 1px solid #e2e8f0;
        }
        .agenda-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .time-badge {
            font-weight: bold;
            color: #111827;
            font-size: 14px;
        }
        .service-name {
            font-weight: 600;
            color: #0f172a;
        }
        .service-duration {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        .price-tag {
            font-weight: bold;
            color: #475569;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 4px;
            letter-spacing: 0.5px;
        }
        .status-confirmed {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-pending {
            background-color: #fef9c3;
            color: #854d0e;
        }
        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            margin-top: 50px;
        }
        .summary-box {
            float: right;
            width: 250px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
            margin-top: -10px;
        }
        .summary-row {
            font-size: 13px;
            margin-bottom: 8px;
            color: #475569;
        }
        .summary-row:last-child {
            margin-bottom: 0;
            font-weight: bold;
            color: #0f172a;
            border-top: 1px solid #cbd5e1;
            padding-top: 8px;
            margin-top: 8px;
        }
        .summary-label {
            float: left;
        }
        .summary-value {
            float: right;
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo-area">
            <div class="logo-text">Barber <span style="color: #d4af37;">Shop</span></div>
            <div class="logo-subtext">Agenda Diaria de Trabajo</div>
        </div>
        <div class="date-area">
            <div class="date-title">Fecha de la Agenda</div>
            <div class="date-value">{{ \Carbon\Carbon::parse($date)->locale('es')->isoFormat('dddd D [de] MMMM, Y') }}</div>
        </div>
        <div class="clearfix"></div>
    </div>

    <div class="barber-info">
        <div class="barber-name"><span style="color: #64748b; font-weight: normal;">Barbero:</span> {{ $barber->name }}</div>
        <div class="barber-specialty"><span style="font-weight: 500; color: #475569;">Especialidad:</span> {{ $barber->specialty ?? 'General' }}</div>
    </div>

    <table class="agenda-table">
        <thead>
            <tr>
                <th style="width: 15%;">Hora</th>
                <th style="width: 25%;">Cliente</th>
                <th style="width: 20%;">Teléfono</th>
                <th style="width: 25%;">Servicio</th>
                <th style="width: 15%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $appointment)
                <tr>
                    <td>
                        <span class="time-badge">
                            {{ \Carbon\Carbon::parse($appointment->time)->format('h:i A') }}
                        </span>
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $appointment->client->name }}</div>
                    </td>
                    <td>
                        {{ $appointment->client->phone ?? 'Sin teléfono' }}
                    </td>
                    <td>
                        <div class="service-name">{{ $appointment->service->name }}</div>
                        <div class="service-duration"><i class="fa-regular fa-clock"></i> Duración: {{ $appointment->service->duration }} min</div>
                    </td>
                    <td>
                        <span class="status-badge {{ $appointment->status === 'confirmed' ? 'status-confirmed' : 'status-pending' }}">
                            {{ $appointment->status === 'confirmed' ? 'Confirmada' : 'Pendiente' }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary-box">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px; color: #475569;">
            <tr>
                <td style="padding: 4px 0; text-align: left;">Total Citas:</td>
                <td style="padding: 4px 0; text-align: right; font-weight: bold; color: #0f172a;">{{ $appointments->count() }}</td>
            </tr>
            <tr>
                <td style="padding: 4px 0; text-align: left;">Citas Confirmadas:</td>
                <td style="padding: 4px 0; text-align: right; font-weight: bold; color: #0f172a;">{{ $appointments->where('status', 'confirmed')->count() }}</td>
            </tr>
            <tr style="border-top: 1px solid #cbd5e1;">
                <td style="padding: 8px 0 0 0; text-align: left; font-weight: bold; color: #0f172a; margin-top: 5px;">Estimado de Ingreso:</td>
                <td style="padding: 8px 0 0 0; text-align: right; font-weight: bold; color: #166534; font-size: 15px; margin-top: 5px;">${{ number_format($appointments->sum(function($app) { return $app->service->price; }), 2) }}</td>
            </tr>
        </table>
    </div>
    <div class="clearfix"></div>

    <div class="footer">
        Este documento es un reporte oficial de la agenda diaria generado automáticamente por King Cold Barber Shop.<br>
        &copy; {{ date('Y') }} Barber Shop. Todos los derechos reservados.
    </div>

</body>
</html>
