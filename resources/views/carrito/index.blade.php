@extends('layouts.app')

@section('title', 'Mi carrito | Reset Informática')

@push('styles')
<style>
    .carrito-wrap {
        max-width: 1000px; margin: 0 auto; padding: 48px 32px 80px;
        display: grid; grid-template-columns: 1fr 320px; gap: 32px; align-items: start;
    }
    .carrito-header { margin-bottom: 28px; }
    .carrito-header h1 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
    .carrito-header p { color: var(--muted); font-size: 14px; margin-top: 4px; }

    .carrito-lista { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .carrito-item { display: grid; grid-template-columns: 64px 1fr auto; gap: 16px; align-items: center; padding: 16px 20px; border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .carrito-item:last-child { border-bottom: none; }
    .carrito-item:hover { background: var(--bg-hover); }
    .item-img { width: 64px; height: 64px; background: #0d1117; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .item-img img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
    .item-img .ph { font-size: 24px; opacity: 0.2; }
    .item-categoria { font-size: 11px; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: var(--accent); margin-bottom: 2px; }
    .item-nombre { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: #fff; }
    .item-precio-unit { font-size: 13px; color: var(--muted); margin-top: 4px; }
    .item-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
    .item-subtotal { font-family: 'Inter', sans-serif; font-size: 17px; font-weight: 700; color: #fff; }
    .item-subtotal span { font-size: 12px; color: var(--muted); font-weight: 400; }
    .cantidad-form { display: flex; align-items: center; gap: 6px; }
    .cantidad-input { width: 54px; text-align: center; background: var(--bg); border: 1px solid var(--border); color: #fff; border-radius: 6px; padding: 5px 8px; font-size: 14px; font-family: 'Space Mono', monospace; }
    .btn-actualizar { padding: 5px 10px; background: transparent; border: 1px solid var(--border); border-radius: 6px; color: var(--muted); font-size: 12px; cursor: pointer; transition: color 0.15s, border-color 0.15s; }
    .btn-actualizar:hover { color: var(--text); border-color: rgba(255,255,255,0.2); }
    .btn-eliminar { background: transparent; border: none; color: var(--muted); font-size: 12px; cursor: pointer; transition: color 0.15s; padding: 0; }
    .btn-eliminar:hover { color: #ef4444; }

    .carrito-vacio { text-align: center; padding: 60px 20px; color: var(--muted); }
    .carrito-vacio .icon { font-size: 48px; margin-bottom: 16px; opacity: 0.3; }
    .carrito-vacio p { margin-bottom: 20px; font-size: 15px; }

    .carrito-resumen { position: sticky; top: 84px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 24px; }
    .resumen-titulo { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 20px; }
    .resumen-fila { display: flex; justify-content: space-between; font-size: 14px; color: var(--muted); margin-bottom: 10px; }
    .resumen-total-fila { display: flex; justify-content: space-between; align-items: baseline; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--border); }
    .resumen-total-label { font-size: 14px; color: var(--muted); }
    .resumen-total-valor { font-family: 'Inter', sans-serif; font-size: 28px; font-weight: 800; color: #fff; }
    .resumen-total-valor em { font-style: normal; font-size: 14px; color: var(--muted); }
    .btn-comprar { display: block; width: 100%; padding: 14px; margin-top: 20px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; transition: opacity 0.2s, transform 0.15s; }
    .btn-comprar:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-vaciar { display: block; width: 100%; margin-top: 10px; padding: 10px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; cursor: pointer; transition: color 0.15s; }
    .btn-vaciar:hover { color: #ef4444; border-color: rgba(239,68,68,0.3); }

    @media (max-width: 768px) {
        .carrito-wrap { grid-template-columns: 1fr; padding: 24px 16px 60px; }
        .carrito-resumen { position: static; }
        .carrito-item { grid-template-columns: 48px 1fr; }
        .item-actions { grid-column: 2; flex-direction: row; align-items: center; flex-wrap: wrap; }
    }
</style>
@endpush

@section('content')
<div class="carrito-wrap">
    <div>
        <div class="carrito-header">
            <h1>🛒 Mi carrito</h1>
            @if($items->isNotEmpty())
                <p>{{ $items->count() }} producto(s) en tu carrito</p>
            @endif
        </div>

        @if($items->isEmpty())
            <div class="carrito-lista">
                <div class="carrito-vacio">
                    <div class="icon">🛒</div>
                    <p>Tu carrito está vacío</p>
                    <a href="{{ route('configurador.index') }}" class="btn-comprar" style="display:inline-block;width:auto;padding:12px 24px">
                        Ir al configurador
                    </a>
                </div>
            </div>
        @else
            <div class="carrito-lista">
                @foreach($items as $item)
                <div class="carrito-item">
                    <div class="item-img">
                        @if($item->producto->imagen)
                            <img src="{{ $item->producto->imagen_url }}" alt="{{ $item->producto->nombre }}">
                        @else
                            <div class="ph">📦</div>
                        @endif
                    </div>
                    <div>
                        <div class="item-categoria">{{ $item->producto->categoria->nombre }}</div>
                        <div class="item-nombre">{{ $item->producto->nombre }}</div>
                        <div class="item-precio-unit">{{ number_format($item->producto->precio, 2, ',', '.') }} € / ud.</div>
                    </div>
                    <div class="item-actions">
                        <div class="item-subtotal">
                            {{ number_format($item->subtotal, 2, ',', '.') }}<span> €</span>
                        </div>
                        <form action="{{ route('carrito.actualizar-cantidad', $item) }}" method="POST" class="cantidad-form">
                            @csrf @method('PATCH')
                            <input type="number" name="cantidad" value="{{ $item->cantidad }}" min="1" max="99"
    class="cantidad-input"
    data-precio="{{ $item->producto->precio }}"
    data-item="{{ $item->id }}"
    onchange="this.closest('form').submit()">
                            <button type="submit" class="btn-actualizar">↻</button>
                        </form>
                        <form action="{{ route('carrito.eliminar', $item) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-eliminar">✕ Eliminar</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="carrito-resumen">
        <div class="resumen-titulo">Resumen del pedido</div>
        <div class="resumen-fila">
            <span>Subtotal ({{ $items->sum('cantidad') }} uds.)</span>
            <span>{{ number_format($total, 2, ',', '.') }} €</span>
        </div>
        <div class="resumen-fila">
            <span>Envío</span>
            <span style="color:#22c55e">Gratis</span>
        </div>
        <div class="resumen-total-fila">
            <span class="resumen-total-label">Total</span>
            <span class="resumen-total-valor">{{ number_format($total, 2, ',', '.') }}<em> €</em></span>
        </div>

        @if($items->isNotEmpty())
            <a href="{{ route('checkout') }}" class="btn-comprar">Finalizar compra →</a>
            <form action="{{ route('carrito.vaciar') }}" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn-vaciar" onclick="return confirm('¿Vaciar el carrito?')">Vaciar carrito</button>
            </form>
        @endif

        <div style="margin-top:20px;padding-top:16px;border-top:1px solid var(--border)">
            <a href="{{ route('configurador.index') }}" style="display:block;text-align:center;font-size:13px;color:var(--accent);text-decoration:none">
                ⚙ Ir al configurador
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.cantidad-input').forEach(input => {
    input.addEventListener('input', function() {
        const cantidad  = parseInt(this.value) || 1;
        const precio    = parseFloat(this.dataset.precio);
        const subtotal  = (cantidad * precio).toFixed(2).replace('.', ',');
        const row       = this.closest('.carrito-item');
        const subtotalEl = row.querySelector('.item-subtotal');
        if (subtotalEl) subtotalEl.innerHTML = subtotal + '<span> €</span>';

        // Recalcular total
        let total = 0;
        document.querySelectorAll('.cantidad-input').forEach(inp => {
            const c = parseInt(inp.value) || 1;
            const p = parseFloat(inp.dataset.precio);
            total += c * p;
        });
        const totalEl = document.querySelector('.resumen-total-valor');
        if (totalEl) totalEl.innerHTML = total.toFixed(2).replace('.', ',') + '<em> €</em>';
    });
});
</script>
@endpush