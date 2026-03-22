@extends('admin.layout')

@section('title', 'Pedidos')
@section('topbar_title', 'Gestión de pedidos')

@push('head_styles')
<style>
    .filtros { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .filtro-input { background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; padding: 9px 14px; color: var(--text); font-size: 13px; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.2s; }
    .filtro-input:focus { border-color: rgba(59,130,246,0.4); }
    .filtro-input::placeholder { color: var(--muted); }
    .btn-filtrar { padding: 9px 18px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 13px; cursor: pointer; transition: background 0.15s; }
    .btn-filtrar:hover { background: var(--bg-hover); }
    .btn-limpiar { padding: 9px 14px; background: transparent; border: 1px solid var(--border); border-radius: 8px; color: var(--muted); font-size: 13px; cursor: pointer; text-decoration: none; }

    .tabla-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: var(--muted); border-bottom: 1px solid var(--border); }
    tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }
    tbody td { padding: 13px 16px; font-size: 13px; color: var(--text); vertical-align: middle; }

    .pedido-num { font-family: 'Space Mono', monospace; color: var(--accent); font-weight: 700; }
    .estado-badge { display: inline-flex; align-items: center; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 600; }
    .badge-pendiente  { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b; }
    .badge-confirmado { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3); color: #3b82f6; }
    .badge-enviado    { background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.3); color: #8b5cf6; }
    .badge-entregado  { background: rgba(34,197,94,0.12);  border: 1px solid rgba(34,197,94,0.3);  color: #22c55e; }
    .badge-cancelado  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #ef4444; }

    .precio-txt { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; }
    .btn-ver { padding: 5px 12px; background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); border-radius: 6px; color: var(--accent); font-size: 12px; font-weight: 600; text-decoration: none; }
    .btn-ver:hover { background: rgba(59,130,246,0.2); }
    .paginacion { padding: 16px; display: flex; justify-content: center; }
    .empty { text-align: center; padding: 48px; color: var(--muted); }
</style>
@endpush

@section('content')

<form method="GET" action="{{ route('admin.pedidos.index') }}" class="filtros">
    <input type="text" name="buscar" placeholder="Buscar por número o usuario..." value="{{ request('buscar') }}" class="filtro-input" style="flex:1;min-width:200px">
    <select name="estado" class="filtro-input">
        <option value="">Todos los estados</option>
        @foreach(['pendiente','confirmado','enviado','entregado','cancelado'] as $e)
            <option value="{{ $e }}" {{ request('estado') === $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn-filtrar">Filtrar</button>
    @if(request('buscar') || request('estado'))
        <a href="{{ route('admin.pedidos.index') }}" class="btn-limpiar">✕ Limpiar</a>
    @endif
</form>

<div class="tabla-wrap">
    @if($pedidos->isEmpty())
        <div class="empty">No hay pedidos que mostrar.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Número</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedidos as $pedido)
            <tr>
                <td><span class="pedido-num">{{ $pedido->numero }}</span></td>
                <td>{{ $pedido->user->username }}</td>
                <td><span class="estado-badge badge-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span></td>
                <td><span class="precio-txt">{{ number_format($pedido->total, 2, ',', '.') }} €</span></td>
                <td style="color:var(--muted)">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                <td><a href="{{ route('admin.pedidos.show', $pedido) }}" class="btn-ver">Ver detalle</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="paginacion">{{ $pedidos->links() }}</div>
    @endif
</div>

@endsection