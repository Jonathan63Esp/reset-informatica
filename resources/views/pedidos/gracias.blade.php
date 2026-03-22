@extends('layouts.app')

@section('title', '¡Pedido confirmado! | Reset Informática')

@push('styles')
<style>
    .gracias-wrap { max-width: 800px; margin: 0 auto; padding: 60px 32px 80px; }

    .gracias-hero { text-align: center; margin-bottom: 48px; }
    .check-circle {
        width: 80px; height: 80px; border-radius: 50%;
        background: rgba(34,197,94,0.12); border: 2px solid rgba(34,197,94,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 36px; margin: 0 auto 24px; animation: pop 0.4s ease;
    }
    @keyframes pop { from { transform: scale(0.5); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    .gracias-hero h1 { font-family: 'Syne', sans-serif; font-size: 36px; font-weight: 800; color: #fff; margin-bottom: 8px; }
    .gracias-hero p { color: var(--muted); font-size: 16px; line-height: 1.6; }
    .numero-pedido { display: inline-block; margin-top: 16px; padding: 8px 20px; background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.25); border-radius: 8px; font-family: 'Space Mono', monospace; font-size: 18px; font-weight: 700; color: var(--accent); }

    .pedido-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 24px; }
    .pedido-card-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
    .pedido-card-header h2 { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; color: #fff; }

    .pedido-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 24px; border-bottom: 1px solid var(--border); font-size: 14px; }
    .pedido-item:last-child { border-bottom: none; }
    .pedido-item-info { flex: 1; }
    .pedido-item-cat { font-size: 11px; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px; }
    .pedido-item-nombre { color: #fff; font-weight: 500; }
    .pedido-item-precio { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; }

    .pedido-total { display: flex; justify-content: space-between; padding: 20px 24px; background: rgba(59,130,246,0.05); border-top: 1px solid var(--border); }
    .pedido-total span { font-size: 14px; color: var(--muted); }
    .pedido-total strong { font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 800; color: #fff; }

    .envio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 20px 24px; }
    .envio-campo label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 3px; }
    .envio-campo span { font-size: 14px; color: var(--text); }

    .estado-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 600; }
    .badge-pendiente  { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b; }
    .badge-confirmado { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3); color: #3b82f6; }
    .badge-enviado    { background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.3); color: #8b5cf6; }
    .badge-entregado  { background: rgba(34,197,94,0.12);  border: 1px solid rgba(34,197,94,0.3);  color: #22c55e; }
    .badge-cancelado  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #ef4444; }

    .gracias-acciones { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-top: 32px; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 13px 24px; background: var(--accent); color: #fff; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; transition: opacity 0.2s; }
    .btn-primary:hover { opacity: 0.9; }
    .btn-ghost { display: inline-flex; align-items: center; gap: 8px; padding: 13px 24px; background: transparent; color: var(--text); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; text-decoration: none; transition: background 0.15s; }
    .btn-ghost:hover { background: var(--bg-hover); }

    @media (max-width: 640px) {
        .gracias-wrap { padding: 32px 16px 60px; }
        .envio-grid { grid-template-columns: 1fr; }
        .gracias-acciones { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="gracias-wrap">

    <div class="gracias-hero">
        <div class="check-circle">✓</div>
        <h1>¡Pedido confirmado!</h1>
        <p>Gracias por tu compra. Recibirás tu pedido en los próximos días.</p>
        <div class="numero-pedido">{{ $pedido->numero }}</div>
    </div>

    {{-- Productos --}}
    <div class="pedido-card">
        <div class="pedido-card-header">
            <h2>Productos del pedido</h2>
            <span class="estado-badge {{ $pedido->estado_badge }}">{{ $pedido->estado_label }}</span>
        </div>
        @foreach($pedido->items as $item)
        <div class="pedido-item">
            <div class="pedido-item-info">
                <span class="pedido-item-cat">{{ $item->producto->categoria->nombre }}</span>
                <span class="pedido-item-nombre">{{ $item->producto->nombre }}</span>
                @if($item->cantidad > 1)
                    <span style="color:var(--muted);font-size:12px"> x{{ $item->cantidad }}</span>
                @endif
            </div>
            <div class="pedido-item-precio">{{ number_format($item->subtotal, 2, ',', '.') }} €</div>
        </div>
        @endforeach
        <div class="pedido-total">
            <span>Total pagado</span>
            <strong>{{ number_format($pedido->total, 2, ',', '.') }} €</strong>
        </div>
    </div>

    {{-- Datos de envío --}}
    <div class="pedido-card">
        <div class="pedido-card-header">
            <h2>Datos de envío</h2>
        </div>
        <div class="envio-grid">
            <div class="envio-campo">
                <label>Nombre</label>
                <span>{{ $pedido->nombre_completo }}</span>
            </div>
            <div class="envio-campo">
                <label>Teléfono</label>
                <span>{{ $pedido->telefono }}</span>
            </div>
            <div class="envio-campo">
                <label>Dirección</label>
                <span>{{ $pedido->direccion }}</span>
            </div>
            <div class="envio-campo">
                <label>Ciudad</label>
                <span>{{ $pedido->ciudad }}, {{ $pedido->codigo_postal }}</span>
            </div>
            <div class="envio-campo">
                <label>Provincia</label>
                <span>{{ $pedido->provincia }}</span>
            </div>
            <div class="envio-campo">
                <label>País</label>
                <span>{{ $pedido->pais }}</span>
            </div>
            @if($pedido->notas)
            <div class="envio-campo" style="grid-column:1/-1">
                <label>Notas</label>
                <span>{{ $pedido->notas }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="gracias-acciones">
        <a href="{{ route('pedidos.historial') }}" class="btn-primary">📦 Ver mis pedidos</a>
        <a href="{{ route('home') }}" class="btn-ghost">← Volver al inicio</a>
    </div>

</div>
@endsection