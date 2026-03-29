<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Pedido - {{ $order->document_number }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 2cm;
            color: #303334;
            line-height: 1.5;
        }
        .header {
            border-bottom: 2px solid #be004c;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header table {
            width: 100%;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #be004c;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .document-type {
            text-align: right;
            font-size: 14px;
            text-transform: uppercase;
            color: #5d5f60;
            letter-spacing: 1px;
        }
        .document-number {
            text-align: right;
            font-size: 22px;
            font-weight: bold;
            color: #303334;
        }
        .info-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-section td {
            vertical-align: top;
            width: 50%;
        }
        .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #5d5f60;
            margin-bottom: 4px;
            letter-spacing: 1px;
        }
        .value {
            font-size: 14px;
            font-weight: bold;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f3f3f4;
            padding: 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            color: #5d5f60;
            border-bottom: 1px solid #e1e3e3;
        }
        .items-table td {
            padding: 12px 10px;
            font-size: 13px;
            border-bottom: 1px solid #f3f3f4;
        }
        .item-name {
            font-weight: bold;
            display: block;
        }
        .item-variant {
            font-size: 11px;
            color: #5d5f60;
            font-style: italic;
        }
        .totals-section {
            width: 100%;
        }
        .totals-table {
            float: right;
            width: 250px;
        }
        .totals-table td {
            padding: 8px 10px;
        }
        .totals-label {
            text-align: left;
            font-size: 12px;
            color: #5d5f60;
        }
        .totals-value {
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
        .total-row {
            background-color: #303334;
            color: white;
        }
        .total-row .totals-label {
            color: rgba(255,255,255,0.7);
            font-size: 10px;
            text-transform: uppercase;
        }
        .total-row .totals-value {
            font-size: 20px;
        }
        .footer {
            position: fixed;
            bottom: 1cm;
            left: 2cm;
            right: 2cm;
            text-align: center;
            font-size: 10px;
            color: #5d5f60;
            border-top: 1px solid #f3f3f4;
            padding-top: 10px;
        }
        .status-stamp {
            position: absolute;
            top: 200px;
            right: 50px;
            border: 4px solid #be004c;
            color: #be004c;
            padding: 10px 20px;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            opacity: 0.2;
            transform: rotate(-20deg);
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td class="logo">Aura Glam</td>
                <td class="document-type">
                    Comprobante de Pedido<br>
                    <span class="document-number">{{ $order->document_number }}</span>
                </td>
            </tr>
        </table>
    </div>

    @if($order->status == 'completed')
        <div class="status-stamp">Pagado</div>
    @endif

    <table class="info-section">
        <tr>
            <td>
                <div class="label">Cliente</div>
                <div class="value">{{ $order->customer_name }}</div>
            </td>
            <td style="text-align: right;">
                <div class="label">Fecha de Emisión</div>
                <div class="value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Producto</th>
                <th style="text-align: center;">Cantidad</th>
                <th style="text-align: right;">Precio Unit.</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>
                        <span class="item-name">{{ $item->product->name }}</span>
                        @if($item->variant)
                            <span class="item-variant">{{ $item->variant->label }}</span>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">${{ number_format($item->unit_price, 2) }}</td>
                    <td style="text-align: right;">${{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td class="totals-label">Subtotal</td>
                <td class="totals-value">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
            @if($order->exchangeRate)
                <tr>
                    <td class="totals-label">Tasa ({{ $order->exchangeRate->currency }})</td>
                    <td class="totals-value">{{ number_format($order->exchangeRate->value, 2) }}</td>
                </tr>
                <tr>
                    <td class="totals-label">Total en BsS</td>
                    <td class="totals-value">BsS {{ number_format($order->total_amount * $order->exchangeRate->value, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="totals-label">Total a Pagar</td>
                <td class="totals-value">${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <div style="margin-top: 50px;">
        <div class="label" style="margin-bottom: 10px;">Resumen de Pagos</div>
        <table style="width: 100%; font-size: 12px; border-top: 1px solid #f3f3f4; padding-top: 10px;">
            @php $totalPaid = $order->payments->sum('amount'); @endphp
            @forelse($order->payments as $payment)
                <tr>
                    <td style="padding: 4px 0;">{{ $payment->payment_date }} - {{ $payment->paymentMethod->name }}</td>
                    <td style="text-align: right; font-weight: bold;">${{ number_format($payment->amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="color: #5d5f60; font-style: italic;">No se han registrado pagos para este pedido.</td>
                </tr>
            @endforelse
            @if($totalPaid > 0)
                <tr>
                    <td style="padding-top: 10px; border-top: 1px solid #f3f3f4; font-weight: bold;">Total Pagado</td>
                    <td style="padding-top: 10px; border-top: 1px solid #f3f3f4; text-align: right; font-weight: bold; color: #be004c;">${{ number_format($totalPaid, 2) }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Pendiente</td>
                    <td style="text-align: right; font-weight: bold; color: {{ ($order->total_amount - $totalPaid) > 0 ? '#f97386' : '#303334' }}">
                        ${{ number_format($order->total_amount - $totalPaid, 2) }}
                    </td>
                </tr>
            @endif
        </table>
    </div>

    <div class="footer">
        Aura Glam - Calidad y Estilo en cada detalle.<br>
        Este documento es un comprobante de pedido y no posee validez fiscal.
    </div>
</body>
</html>
