@extends('admin.layout')

@section('title', 'Dashboard')
@section('topbar_title', 'Dashboard')

@push('head_styles')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 32px; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 20px 24px; position: relative; overflow: hidden; }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px; background: var(--stat-color, var(--accent)); }
    .stat-label { font-size: 12px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); margin-bottom: 8px; }
    .stat-valor { font-family: 'Inter', sans-serif; font-size: 28px; font-weight: 800; color: #fff; line-height: 1; }
    .stat-sub { font-size: 12px; color: var(--muted); margin-top: 6px; }
    .stat-icon { position: absolute; top: 16px; right: 16px; font-size: 24px; opacity: 0.15; }

    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .grid-3 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 20px; }

    .panel { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    .panel-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
    .panel-title { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: #fff; }
    .panel-link { font-size: 12px; color: var(--accent); text-decoration: none; }
    .panel-link:hover { text-decoration: underline; }

    .pedido-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px; border-bottom: 1px solid var(--border); font-size: 13px; }
    .pedido-row:last-child { border-bottom: none; }
    .pedido-num { font-family: 'Space Mono', monospace; color: var(--accent); font-size: 12px; }
    .pedido-user { color: var(--text); font-weight: 500; }
    .pedido-fecha { color: var(--muted); font-size: 11px; }
    .pedido-total { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; }

    .estado-badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 100px; font-size: 11px; font-weight: 600; }
    .badge-pendiente  { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b; }
    .badge-confirmado { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3); color: #3b82f6; }
    .badge-enviado    { background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.3); color: #8b5cf6; }
    .badge-entregado  { background: rgba(34,197,94,0.12);  border: 1px solid rgba(34,197,94,0.3);  color: #22c55e; }
    .badge-cancelado  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #ef4444; }

    .top-producto { display: flex; align-items: center; justify-content: space-between; padding: 10px 20px; border-bottom: 1px solid var(--border); font-size: 13px; }
    .top-producto:last-child { border-bottom: none; }
    .top-nombre { color: var(--text); flex: 1; }
    .top-vendido { font-family: 'Space Mono', monospace; font-size: 12px; color: var(--muted); margin: 0 16px; }
    .top-ingresos { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; }

    /* Gráfico */
    .chart-wrap { padding: 20px; }
    .chart-bars { display: flex; align-items: flex-end; gap: 8px; height: 160px; }
    .chart-bar-wrap { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; justify-content: flex-end; }
    .chart-bar { width: 100%; background: var(--accent); border-radius: 4px 4px 0 0; transition: height 0.3s; min-height: 4px; opacity: 0.8; }
    .chart-bar:hover { opacity: 1; }
    .chart-label { font-size: 10px; color: var(--muted); white-space: nowrap; }
    .chart-valor { font-size: 10px; color: var(--text); font-family: 'Space Mono', monospace; }

    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card" style="--stat-color:#3b82f6">
        <div class="stat-icon">🛒</div>
        <div class="stat-label">Total pedidos</div>
        <div class="stat-valor">{{ $totalPedidos }}</div>
        <div class="stat-sub">+{{ $pedidosHoy }} hoy</div>
    </div>
    <div class="stat-card" style="--stat-color:#22c55e">
        <div class="stat-icon">💶</div>
        <div class="stat-label">Ingresos totales</div>
        <div class="stat-valor">{{ number_format($ingresoTotal, 0, ',', '.') }} €</div>
        <div class="stat-sub">+{{ number_format($ingresoHoy, 2, ',', '.') }} € hoy</div>
    </div>
    <div class="stat-card" style="--stat-color:#f59e0b">
        <div class="stat-icon">⏳</div>
        <div class="stat-label">Pedidos pendientes</div>
        <div class="stat-valor">{{ $pedidosPendientes }}</div>
        <div class="stat-sub">Requieren atención</div>
    </div>
    <div class="stat-card" style="--stat-color:#8b5cf6">
        <div class="stat-icon">👥</div>
        <div class="stat-label">Usuarios registrados</div>
        <div class="stat-valor">{{ $totalUsuarios }}</div>
        <div class="stat-sub">{{ $totalProductos }} productos en catálogo</div>
    </div>
</div>

{{-- Gráfico + Top productos --}}
<div class="grid-3">
    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Ventas últimos 7 días</span>
        </div>
        <div class="chart-wrap">
            @php $maxVenta = $ventasSemana->max('total') ?: 1; @endphp
            <div class="chart-bars">
                @foreach($ventasSemana as $dia)
                <div class="chart-bar-wrap">
                    <div class="chart-valor">{{ $dia['total'] > 0 ? number_format($dia['total'], 0, ',', '.') . '€' : '' }}</div>
                    <div class="chart-bar" style="height: {{ max(4, ($dia['total'] / $maxVenta) * 140) }}px"
                         title="{{ $dia['fecha'] }}: {{ number_format($dia['total'], 2, ',', '.') }} €"></div>
                    <div class="chart-label">{{ $dia['fecha'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <span class="panel-title">Top productos</span>
        </div>
        @if($productosTop->isEmpty())
            <div style="padding:20px;text-align:center;color:var(--muted);font-size:13px">Sin datos aún</div>
        @else
            @foreach($productosTop as $p)
            <div class="top-producto">
                <div class="top-nombre">{{ Str::limit($p->nombre, 28) }}</div>
                <div class="top-vendido">{{ $p->total_vendido }} uds.</div>
                <div class="top-ingresos">{{ number_format($p->ingresos, 0, ',', '.') }}€</div>
            </div>
            @endforeach
        @endif
    </div>
</div>

{{-- Pedidos recientes --}}
<div class="panel">
    <div class="panel-header">
        <span class="panel-title">Pedidos recientes</span>
        <a href="{{ route('admin.pedidos.index') }}" class="panel-link">Ver todos →</a>
    </div>
    @if($pedidosRecientes->isEmpty())
        <div style="padding:20px;text-align:center;color:var(--muted);font-size:13px">No hay pedidos aún</div>
    @else
        @foreach($pedidosRecientes as $pedido)
        <div class="pedido-row">
            <div>
                <div class="pedido-num">{{ $pedido->numero }}</div>
                <div class="pedido-user">{{ $pedido->user->username }}</div>
            </div>
            <div style="text-align:center">
                <span class="estado-badge badge-{{ $pedido->estado }}">{{ $pedido->estado_label }}</span>
            </div>
            <div style="text-align:right">
                <div class="pedido-total">{{ number_format($pedido->total, 2, ',', '.') }} €</div>
                <div class="pedido-fecha">{{ $pedido->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @endforeach
    @endif
</div>

@endsection