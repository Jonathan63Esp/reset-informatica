@extends('layouts.app')

@section('title', 'Resumen de configuración | Reset Informática')

@push('styles')
<style>
    .resumen-wrap { max-width: 860px; margin: 0 auto; padding: 48px 32px 80px; }
    .resumen-header { text-align: center; margin-bottom: 48px; }
    .check-circle {
        width: 64px; height: 64px; border-radius: 50%;
        background: rgba(34,197,94,0.12); border: 2px solid rgba(34,197,94,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; margin: 0 auto 20px;
    }
    .resumen-header h1 { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 800; color: #fff; letter-spacing: -0.5px; margin-bottom: 8px; }
    .resumen-header p { color: var(--muted); font-size: 15px; }

    .componentes-lista { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; margin-bottom: 24px; }
    .componente-row { display: grid; grid-template-columns: 56px 1fr auto; align-items: center; gap: 16px; padding: 16px 20px; border-bottom: 1px solid var(--border); transition: background 0.15s; }
    .componente-row:last-child { border-bottom: none; }
    .componente-row:hover { background: var(--bg-hover); }
    .comp-img { width: 56px; height: 56px; background: #0d1117; border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .comp-img img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
    .comp-img .ph { font-size: 24px; opacity: 0.2; }
    .comp-categoria { font-size: 11px; font-weight: 600; letter-spacing: 0.07em; text-transform: uppercase; color: var(--accent); margin-bottom: 3px; }
    .comp-nombre { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; color: #fff; line-height: 1.2; }
    .comp-atribs { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 6px; }
    .comp-atrib { font-size: 11px; color: var(--muted); font-family: 'Space Mono', monospace; background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 3px; padding: 1px 6px; }
    .comp-precio { font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 700; color: #fff; white-space: nowrap; text-align: right; }
    .comp-precio span { font-size: 13px; font-weight: 400; color: var(--muted); }

    .resumen-total { background: var(--bg-card); border: 1px solid rgba(59,130,246,0.25); border-radius: 14px; padding: 24px 28px; display: flex; align-items: center; justify-content: space-between; margin-bottom: 28px; }
    .total-label { font-size: 14px; color: var(--muted); }
    .total-precio { font-family: 'Inter', sans-serif; font-size: 36px; font-weight: 800; color: #fff; }
    .total-precio em { font-style: normal; font-size: 18px; color: var(--muted); font-weight: 400; }

    .resumen-acciones { display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 13px 24px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; text-decoration: none; transition: opacity 0.2s, transform 0.15s; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-ghost { display: inline-flex; align-items: center; gap: 8px; padding: 13px 24px; background: transparent; color: var(--text); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: border-color 0.2s, background 0.2s; }
    .btn-ghost:hover { background: var(--bg-hover); border-color: rgba(255,255,255,0.15); }
    .btn-danger { display: inline-flex; align-items: center; gap: 8px; padding: 13px 24px; background: transparent; color: #ef4444; border: 1px solid rgba(239,68,68,0.25); border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: background 0.2s; }
    .btn-danger:hover { background: rgba(239,68,68,0.08); }

    @media (max-width: 640px) {
        .resumen-wrap { padding: 24px 16px 60px; }
        .componente-row { grid-template-columns: 44px 1fr; }
        .comp-precio { grid-column: 2; text-align: left; }
        .resumen-total { flex-direction: column; gap: 12px; text-align: center; }
        .resumen-acciones { flex-direction: column; }
    }
</style>
@endpush

@section('content')
<div class="resumen-wrap">

    <div class="resumen-header">
        <div class="check-circle">✓</div>
        <h1>Tu configuración lista</h1>
        <p>Revisa los componentes seleccionados y añádelos al carrito.</p>
    </div>

    <div class="componentes-lista">
        @foreach($productos as $nombreCateg => $producto)
        <div class="componente-row">
            <div class="comp-img">
                @if($producto->imagen)
                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                @else
                    <div class="ph">🖥️</div>
                @endif
            </div>
            <div>
                <div class="comp-categoria">{{ $nombreCateg }}</div>
                <div class="comp-nombre">{{ $producto->nombre }}</div>
                @if($producto->atributoValores->isNotEmpty())
                <div class="comp-atribs">
                    @foreach($producto->atributoValores->take(4) as $av)
                        <span class="comp-atrib">{{ $av->atributo->nombre }}: {{ $av->valor }}</span>
                    @endforeach
                </div>
                @endif
            </div>
            <div class="comp-precio">
                {{ number_format($producto->precio, 2, ',', '.') }}<span> €</span>
            </div>
        </div>
        @endforeach

        {{-- Montaje --}}
        @if(isset($montaje))
        <div class="componente-row">
            <div class="comp-img">
                <div class="ph">{{ $montaje === 'con_montaje' ? '🔧' : '📦' }}</div>
            </div>
            <div>
                <div class="comp-categoria">Montaje</div>
                <div class="comp-nombre">{{ $montaje === 'con_montaje' ? 'Servicio de montaje profesional' : 'Sin montaje' }}</div>
                @if($montaje === 'con_montaje')
                <div class="comp-atribs">
                    <span class="comp-atrib">Montaje incluido</span>
                    <span class="comp-atrib">Garantía de montaje</span>
                </div>
                @endif
            </div>
            <div class="comp-precio">
                {{ number_format($preciomontaje, 2, ',', '.') }}<span> €</span>
            </div>
        </div>
        @endif
    </div>

    <div class="resumen-total">
        <div class="total-label">{{ count($productos) }} componente(s) seleccionados</div>
        <div class="total-precio">{{ number_format($total, 2, ',', '.') }}<em> €</em></div>
    </div>

    <div class="resumen-acciones">
        @auth
        <form action="{{ route('carrito.anadir-configuracion') }}" method="POST">
            @csrf
            @foreach($productos as $producto)
    @if(is_object($producto) && $producto->id > 0)
        <input type="hidden" name="productos[]" value="{{ $producto->id }}">
    @endif
@endforeach
@if(isset($montaje) && $montaje === 'con_montaje' && isset($config['montaje_producto_id']))
    <input type="hidden" name="productos[]" value="{{ $config['montaje_producto_id'] }}">
@endif
            <button type="submit" class="btn-primary">🛒 Añadir todo al carrito</button>
        </form>
        @else
        <a href="{{ route('login') }}" class="btn-primary">🔐 Inicia sesión para añadir al carrito</a>
        @endauth

        <a href="{{ route('configurador.exportar') }}" class="btn-ghost">↓ Exportar configuración</a>
        <a href="{{ route('configurador.index') }}" class="btn-ghost">✏ Editar</a>

        <form action="{{ route('configurador.reiniciar') }}" method="POST">
            @csrf
            <button type="submit" class="btn-danger"
                onclick="return confirm('¿Seguro que quieres reiniciar?')">↺ Reiniciar</button>
        </form>
    </div>

</div>
@endsection