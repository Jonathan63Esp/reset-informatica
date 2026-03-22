@extends('layouts.app')

@section('title', 'Pedido {{ $pedido->numero }} | Reset Informática')

@push('styles')
<style>
    .show-wrap { max-width: 900px; margin: 0 auto; padding: 48px 32px 80px; }
    .show-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
    .show-header h1 { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: #fff; }
    .show-header small { display: block; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 400; color: var(--muted); margin-bottom: 6px; }

    .estado-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 100px; font-size: 13px; font-weight: 600; }
    .badge-pendiente  { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b; }
    .badge-confirmado { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3); color: #3b82f6; }
    .badge-enviado    { background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.3); color: #8b5cf6; }
    .badge-entregado  { background: rgba(34,197,94,0.12);  border: 1px solid rgba(34,197,94,0.3);  color: #22c55e; }
    .badge-cancelado  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #ef4444; }

    .seccion { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 20px; }
    .seccion-titulo { font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); padding: 16px 20px; border-bottom: 1px solid var(--border); }

    .pedido-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 20px; border-bottom: 1px solid var(--border); font-size: 14px; gap: 16px; }
    .pedido-item:last-child { border-bottom: none; }
    .item-img { width: 48px; height: 48px; background: #0d1117; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px; opacity: 0.3; flex-shrink: 0; overflow: hidden; }
    .item-img img { width: 100%; height: 100%; object-fit: contain; padding: 4px; opacity: 1; }
    .item-info { flex: 1; }
    .item-cat { font-size: 11px; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px; }
    .item-nombre { color: #fff; font-weight: 500; }
    .item-cantidad { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .item-precio { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; white-space: nowrap; }

    .pedido-total { display: flex; justify-content: space-between; align-items: baseline; padding: 16px 20px; background: rgba(59,130,246,0.04); border-top: 1px solid var(--border); }
    .pedido-total span { font-size: 14px; color: var(--muted); }
    .pedido-total strong { font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 800; color: #fff; }

    .envio-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
    .envio-campo { padding: 12px 20px; border-bottom: 1px solid var(--border); border-right: 1px solid var(--border); }
    .envio-campo:nth-child(even) { border-right: none; }
    .envio-campo:nth-last-child(-n+2) { border-bottom: none; }
    .envio-campo label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 3px; }
    .envio-campo span { font-size: 14px; color: var(--text); }

    .btn-back { display: inline-flex; align-items: center; gap: 6px; color: var(--muted); text-decoration: none; font-size: 13px; transition: color 0.15s; }
    .btn-back:hover { color: var(--text); }

    @media (max-width: 640px) {
        .show-wrap { padding: 24px 16px 60px; }
        .envio-grid { grid-template-columns: 1fr; }
        .envio-campo { border-right: none; }
        .envio-campo:nth-last-child(-n+2) { border-bottom: 1px solid var(--border); }
        .envio-campo:last-child { border-bottom: none; }
    }
</style>
@endpush

@section('content')
<div class="show-wrap">
    <div class="show-header">
        <div>
            <a href="{{ route('pedidos.historial') }}" class="btn-back" style="margin-bottom:12px;display:inline-flex">← Mis pedidos</a>
            <a href="{{ route('pedidos.factura', $pedido) }}"
   style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:rgba(59,130,246,0.1);border:1px solid rgba(59,130,246,0.2);border-radius:7px;color:var(--accent);font-size:13px;font-weight:600;text-decoration:none;margin-bottom:12px">
    📄 Descargar factura PDF
</a>
            <h1><small>Pedido</small>{{ $pedido->numero }}</h1>
            <div style="font-size:13px;color:var(--muted);margin-top:4px">
                Realizado el {{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}
            </div>
        </div>
        <span class="estado-badge {{ $pedido->estado_badge }}">{{ $pedido->estado_label }}</span>
    </div>

    {{-- Productos --}}
    <div class="seccion">
        <div class="seccion-titulo">Productos</div>
        @foreach($pedido->items as $item)
        <div class="pedido-item">
            <div class="item-img">
                @if($item->producto->imagen)
                    <img src="{{ $item->producto->imagen_url }}" alt="{{ $item->producto->nombre }}">
                @else
                    📦
                @endif
            </div>
            <div class="item-info">
                <span class="item-cat">{{ $item->producto->categoria->nombre }}</span>
                <div class="item-nombre">{{ $item->producto->nombre }}</div>
                <div class="item-cantidad">{{ number_format($item->precio_unitario, 2, ',', '.') }} € / ud. × {{ $item->cantidad }}</div>
            </div>
            <div class="item-precio">{{ number_format($item->subtotal, 2, ',', '.') }} €</div>
        </div>
        @endforeach
        <div class="pedido-total">
            <span>Total</span>
            <strong>{{ number_format($pedido->total, 2, ',', '.') }} €</strong>
        </div>
    </div>

    {{-- Datos de envío --}}
    <div class="seccion">
        <div class="seccion-titulo">Datos de envío</div>
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
            <div class="envio-campo" style="grid-column:1/-1;border-right:none">
                <label>Notas</label>
                <span>{{ $pedido->notas }}</span>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection