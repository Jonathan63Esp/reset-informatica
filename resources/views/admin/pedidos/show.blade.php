@extends('admin.layout')

@section('title', 'Pedido ' . $pedido->numero)
@section('topbar_title', 'Detalle del pedido')

@section('topbar_actions')
    <a href="{{ route('admin.pedidos.index') }}" class="topbar-link">← Volver a pedidos</a>
@endsection

@push('head_styles')
<style>
    .pedido-grid { display: grid; grid-template-columns: 1fr 320px; gap: 20px; align-items: start; }
    .panel { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
    .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .panel-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: #fff; }

    .estado-badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 600; }
    .badge-pendiente  { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b; }
    .badge-confirmado { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3); color: #3b82f6; }
    .badge-enviado    { background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.3); color: #8b5cf6; }
    .badge-entregado  { background: rgba(34,197,94,0.12);  border: 1px solid rgba(34,197,94,0.3);  color: #22c55e; }
    .badge-cancelado  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #ef4444; }

    .item-row { display: flex; align-items: center; gap: 14px; padding: 14px 20px; border-bottom: 1px solid var(--border); }
    .item-row:last-child { border-bottom: none; }
    .item-img { width: 48px; height: 48px; background: #0d1117; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .item-img img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
    .item-img .ph { font-size: 20px; opacity: 0.2; }
    .item-cat { font-size: 11px; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; }
    .item-nombre { color: #fff; font-weight: 500; font-size: 14px; }
    .item-qty { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .item-precio { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; margin-left: auto; white-space: nowrap; }

    .total-row { display: flex; justify-content: space-between; padding: 16px 20px; border-top: 1px solid var(--border); }
    .total-row span { color: var(--muted); font-size: 14px; }
    .total-row strong { font-family: 'Inter', sans-serif; font-size: 22px; font-weight: 800; color: #fff; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
    .info-campo { padding: 12px 20px; border-bottom: 1px solid var(--border); }
    .info-campo:nth-child(odd) { border-right: 1px solid var(--border); }
    .info-campo:nth-last-child(-n+2) { border-bottom: none; }
    .info-campo label { font-size: 11px; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 3px; }
    .info-campo span { font-size: 13px; color: var(--text); }

    .estado-form { padding: 20px; }
    .estado-select { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; color: var(--text); font-size: 14px; font-family: 'Inter', sans-serif; outline: none; margin-bottom: 12px; }
    .btn-actualizar { width: 100%; padding: 11px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
    .btn-actualizar:hover { opacity: 0.9; }

    .usuario-info { padding: 20px; }
    .usuario-nombre { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 4px; }
    .usuario-meta { font-size: 13px; color: var(--muted); }

    @media (max-width: 900px) { .pedido-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div style="margin-bottom:20px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px">
    <div>
        <h1 style="font-family:'Syne',sans-serif;font-size:24px;font-weight:800;color:#fff">{{ $pedido->numero }}</h1>
        <div style="font-size:13px;color:var(--muted);margin-top:4px">{{ $pedido->created_at->format('d/m/Y \a \l\a\s H:i') }}</div>
    </div>
    <span class="estado-badge badge-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span>
</div>

<div class="pedido-grid">
    <div>
        {{-- Productos --}}
        <div class="panel">
            <div class="panel-header"><span class="panel-title">Productos</span></div>
            @foreach($pedido->items as $item)
            <div class="item-row">
                <div class="item-img">
                    @if($item->producto->imagen)
                        <img src="{{ $item->producto->imagen_url }}" alt="{{ $item->producto->nombre }}">
                    @else
                        <div class="ph">📦</div>
                    @endif
                </div>
                <div style="flex:1">
                    <span class="item-cat">{{ $item->producto->categoria->nombre }}</span>
                    <div class="item-nombre">{{ $item->producto->nombre }}</div>
                    <div class="item-qty">{{ number_format($item->precio_unitario, 2, ',', '.') }} € × {{ $item->cantidad }}</div>
                </div>
                <div class="item-precio">{{ number_format($item->subtotal, 2, ',', '.') }} €</div>
            </div>
            @endforeach
            <div class="total-row">
                <span>Total</span>
                <strong>{{ number_format($pedido->total, 2, ',', '.') }} €</strong>
            </div>
        </div>

        {{-- Datos de envío --}}
        <div class="panel">
            <div class="panel-header"><span class="panel-title">Datos de envío</span></div>
            <div class="info-grid">
                <div class="info-campo"><label>Nombre</label><span>{{ $pedido->nombre_completo }}</span></div>
                <div class="info-campo"><label>Teléfono</label><span>{{ $pedido->telefono }}</span></div>
                <div class="info-campo"><label>Dirección</label><span>{{ $pedido->direccion }}</span></div>
                <div class="info-campo"><label>Ciudad</label><span>{{ $pedido->ciudad }}, {{ $pedido->codigo_postal }}</span></div>
                <div class="info-campo"><label>Provincia</label><span>{{ $pedido->provincia }}</span></div>
                <div class="info-campo"><label>País</label><span>{{ $pedido->pais }}</span></div>
                @if($pedido->notas)
                <div class="info-campo" style="grid-column:1/-1;border-right:none"><label>Notas</label><span>{{ $pedido->notas }}</span></div>
                @endif
            </div>
        </div>
    </div>

    <div>
        {{-- Cambiar estado --}}
        <div class="panel" style="margin-bottom:16px">
            <div class="panel-header"><span class="panel-title">Cambiar estado</span></div>
            <div class="estado-form">
                <form action="{{ route('admin.pedidos.estado', $pedido) }}" method="POST">
                    @csrf @method('PATCH')
                    <select name="estado" class="estado-select">
                        @foreach(['pendiente','confirmado','enviado','entregado','cancelado'] as $e)
                            <option value="{{ $e }}" {{ $pedido->estado === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-actualizar">Actualizar estado</button>
                </form>
            </div>
        </div>

        {{-- Usuario --}}
        <div class="panel">
            <div class="panel-header"><span class="panel-title">Cliente</span></div>
            <div class="usuario-info">
                <div class="usuario-nombre">{{ $pedido->user->username }}</div>
                <div class="usuario-meta">{{ $pedido->user->pedidos()->count() }} pedido(s) en total</div>
                <div style="margin-top:12px">
                    <a href="{{ route('admin.usuarios.index', ['buscar' => $pedido->user->username]) }}"
                       style="font-size:12px;color:var(--accent);text-decoration:none">Ver perfil →</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection