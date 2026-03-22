@extends('layouts.app')

@section('title', 'Mis pedidos | Reset Informática')

@push('styles')
<style>
    .historial-wrap { max-width: 900px; margin: 0 auto; padding: 48px 32px 80px; }
    .historial-header { margin-bottom: 32px; }
    .historial-header h1 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
    .historial-header p { color: var(--muted); font-size: 14px; margin-top: 4px; }

    .pedido-card {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 12px; overflow: hidden; margin-bottom: 16px;
        transition: border-color 0.2s;
    }
    .pedido-card:hover { border-color: rgba(59,130,246,0.3); }

    .pedido-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 16px 20px; border-bottom: 1px solid var(--border);
        flex-wrap: wrap; gap: 12px;
    }
    .pedido-numero { font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; color: var(--accent); }
    .pedido-fecha { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .pedido-meta { display: flex; align-items: center; gap: 12px; }

    .estado-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 100px; font-size: 12px; font-weight: 600; }
    .badge-pendiente  { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.3); color: #f59e0b; }
    .badge-confirmado { background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3); color: #3b82f6; }
    .badge-enviado    { background: rgba(139,92,246,0.12); border: 1px solid rgba(139,92,246,0.3); color: #8b5cf6; }
    .badge-entregado  { background: rgba(34,197,94,0.12);  border: 1px solid rgba(34,197,94,0.3);  color: #22c55e; }
    .badge-cancelado  { background: rgba(239,68,68,0.12);  border: 1px solid rgba(239,68,68,0.3);  color: #ef4444; }

    .pedido-body { padding: 16px 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
    .pedido-items-txt { font-size: 13px; color: var(--muted); }
    .pedido-total-txt { font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 800; color: #fff; }
    .pedido-total-txt span { font-size: 13px; font-weight: 400; color: var(--muted); }
    .btn-ver { padding: 8px 16px; background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); border-radius: 7px; color: var(--accent); font-size: 13px; font-weight: 600; text-decoration: none; transition: background 0.15s; }
    .btn-ver:hover { background: rgba(59,130,246,0.2); }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; }
    .empty-state .icon { font-size: 48px; margin-bottom: 16px; opacity: 0.3; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; background: var(--accent); color: #fff; border-radius: 8px; font-size: 14px; font-weight: 600; text-decoration: none; margin-top: 16px; }

    .paginacion { margin-top: 24px; }

    @media (max-width: 640px) {
        .historial-wrap { padding: 24px 16px 60px; }
        .pedido-header { flex-direction: column; align-items: flex-start; }
    }
</style>
@endpush

@section('content')
<div class="historial-wrap">
    <div class="historial-header">
        <h1>Mis pedidos</h1>
        <p>Historial de todos tus pedidos realizados.</p>
    </div>

    @if($pedidos->isEmpty())
        <div class="empty-state">
            <div class="icon">📦</div>
            <p>Todavía no has realizado ningún pedido.</p>
            <a href="{{ route('configurador.plataforma') }}" class="btn-primary">Configurar mi PC →</a>
        </div>
    @else
        @foreach($pedidos as $pedido)
        <div class="pedido-card">
            <div class="pedido-header">
                <div>
                    <div class="pedido-numero">{{ $pedido->numero }}</div>
                    <div class="pedido-fecha">{{ $pedido->created_at->format('d/m/Y H:i') }}</div>
                </div>
                <div class="pedido-meta">
                    <span class="estado-badge {{ $pedido->estado_badge }}">{{ $pedido->estado_label }}</span>
                    <a href="{{ route('pedidos.show', $pedido) }}" class="btn-ver">Ver detalle</a>
                    <a href="{{ route('pedidos.factura', $pedido) }}" class="btn-ver" style="background:rgba(59,130,246,0.05)">📄 Factura</a>
                </div>
            </div>
            <div class="pedido-body">
                <div class="pedido-items-txt">{{ $pedido->items->count() }} producto(s)</div>
                <div class="pedido-total-txt">{{ number_format($pedido->total, 2, ',', '.') }}<span> €</span></div>
            </div>
        </div>
        @endforeach

        <div class="paginacion">{{ $pedidos->links() }}</div>
    @endif
</div>
@endsection