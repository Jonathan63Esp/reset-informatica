<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #1a1a2e; background: #fff; }

        .header { background: #1a1a2e; color: #fff; padding: 28px 36px; display: flex; justify-content: space-between; align-items: flex-start; }
        .logo { font-size: 26px; font-weight: 900; letter-spacing: -1px; }
        .logo span { color: #3b82f6; }
        .logo-sub { font-size: 11px; color: #9ca3af; margin-top: 3px; }
        .factura-info { text-align: right; }
        .factura-info .num { font-size: 18px; font-weight: 700; color: #3b82f6; }
        .factura-info .fecha { font-size: 11px; color: #9ca3af; margin-top: 4px; }

        .body { padding: 32px 36px; }

        .datos-grid { display: flex; gap: 24px; margin-bottom: 28px; }
        .datos-box { flex: 1; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; }
        .datos-box h3 { font-size: 10px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #6b7280; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; }
        .datos-box p { font-size: 12px; color: #374151; line-height: 1.7; }
        .datos-box .nombre { font-weight: 700; font-size: 14px; color: #1a1a2e; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { background: #1a1a2e; color: #fff; padding: 10px 12px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; }
        thead th:last-child { text-align: right; }
        tbody tr { border-bottom: 1px solid #f3f4f6; }
        tbody tr:nth-child(even) { background: #f9fafb; }
        tbody td { padding: 11px 12px; font-size: 12px; color: #374151; vertical-align: middle; }
        tbody td:last-child { text-align: right; font-weight: 700; }
        .td-cat { font-size: 10px; color: #3b82f6; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px; }
        .td-nombre { font-weight: 600; color: #1a1a2e; }
        .td-precio-unit { font-size: 11px; color: #9ca3af; margin-top: 2px; }

        .totales { margin-left: auto; width: 280px; }
        .totales-fila { display: flex; justify-content: space-between; padding: 7px 0; font-size: 13px; color: #6b7280; border-bottom: 1px solid #f3f4f6; }
        .totales-fila.total { border-top: 2px solid #1a1a2e; border-bottom: none; padding-top: 12px; margin-top: 4px; font-size: 16px; font-weight: 900; color: #1a1a2e; }
        .totales-fila span:last-child { font-weight: 600; }

        .footer { margin-top: 40px; padding: 20px 36px; background: #f9fafb; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .footer-text { font-size: 11px; color: #9ca3af; line-height: 1.6; }
        .footer-gracias { font-size: 14px; font-weight: 700; color: #1a1a2e; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <div class="logo">Reset<span>.</span></div>
        <div class="logo-sub">Reset Informática · I.E.S. La Marisma, Huelva</div>
    </div>
    <div class="factura-info">
        <div class="num">FACTURA #{{ $pedido->numero }}</div>
        <div class="fecha">Fecha: {{ $pedido->created_at->format('d/m/Y') }}</div>
    </div>
</div>

<div class="body">

    <div class="datos-grid">
        <div class="datos-box">
            <h3>Datos del cliente</h3>
            <p class="nombre">{{ $pedido->nombre_completo }}</p>
            <p>{{ $pedido->direccion }}</p>
            <p>{{ $pedido->ciudad }}, {{ $pedido->codigo_postal }}</p>
            <p>{{ $pedido->provincia }} — {{ $pedido->pais }}</p>
            <p>Tel: {{ $pedido->telefono }}</p>
        </div>
        <div class="datos-box">
            <h3>Datos del pedido</h3>
            <p><strong>Número de pedido:</strong> {{ $pedido->numero }}</p>
            <p><strong>Fecha de pedido:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Usuario:</strong> {{ $pedido->user->username }}</p>
            <p><strong>Envío:</strong> Gratuito</p>
            @if($pedido->notas)
            <p><strong>Notas:</strong> {{ $pedido->notas }}</p>
            @endif
        </div>
        <div class="datos-box">
            <h3>Emisor</h3>
            <p class="nombre">Reset Informática</p>
            <p>I.E.S. La Marisma</p>
            <p>Huelva, España</p>
            <p>info@resetinformatica.es</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:50%">Producto</th>
                <th>Categoría</th>
                <th>Precio/ud.</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->items as $item)
            <tr>
                <td>
                    <span class="td-nombre">{{ $item->producto->nombre }}</span>
                </td>
                <td>
                    <span class="td-cat">{{ $item->producto->categoria->nombre }}</span>
                </td>
                <td>{{ number_format($item->precio_unitario, 2, ',', '.') }} €</td>
                <td>{{ $item->cantidad }}</td>
                <td>{{ number_format($item->subtotal, 2, ',', '.') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totales">
        <div class="totales-fila">
            <span>Subtotal</span>
            <span>{{ number_format($pedido->total, 2, ',', '.') }} €</span>
        </div>
        <div class="totales-fila">
            <span>Envío</span>
            <span style="color:#22c55e">Gratis</span>
        </div>
        <div class="totales-fila">
            <span>IVA (21%)</span>
            <span>Incluido</span>
        </div>
        <div class="totales-fila total">
            <span>TOTAL</span>
            <span>{{ number_format($pedido->total, 2, ',', '.') }} €</span>
        </div>
    </div>

</div>

<div class="footer">
    <div class="footer-text">
        Este documento es una factura simplificada.<br>
        IVA incluido en el precio final. Conserve este documento como justificante de compra.
    </div>
    <div class="footer-gracias">¡Gracias por tu compra!</div>
</div>

</body>
</html>