@extends('layouts.app')

@section('title', $categoria->nombre . ' | Reset Informática')

@push('styles')
<style>
    .catalogo-wrap { max-width: 1280px; margin: 0 auto; padding: 40px 32px 80px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; align-items: start; }

    .catalogo-sidebar { position: sticky; top: 84px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .sidebar-titulo { font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); padding: 16px 20px 12px; border-bottom: 1px solid var(--border); }
    .cat-lista { list-style: none; }
    .cat-item a { display: flex; align-items: center; gap: 10px; padding: 11px 20px; font-size: 14px; color: var(--muted); text-decoration: none; transition: background 0.15s, color 0.15s; border-bottom: 1px solid var(--border); }
    .cat-item:last-child a { border-bottom: none; }
    .cat-item a:hover { background: var(--bg-hover); color: var(--text); }
    .cat-item.active a { background: rgba(59,130,246,0.08); color: #fff; font-weight: 600; border-left: 2px solid var(--accent); }
    .cat-icon { font-size: 16px; width: 20px; text-align: center; }

    .catalogo-header { margin-bottom: 20px; }
    .breadcrumb { font-size: 12px; color: var(--muted); margin-bottom: 8px; }
    .breadcrumb a { color: var(--muted); text-decoration: none; }
    .breadcrumb a:hover { color: var(--accent); }
    .catalogo-header h1 { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
    .catalogo-header p { font-size: 13px; color: var(--muted); margin-top: 4px; }

    .catalogo-toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding: 12px 16px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 10px; flex-wrap: wrap; gap: 10px; }
    .toolbar-orden { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); }
    .btn-orden { padding: 6px 12px; background: var(--bg); border: 1px solid var(--border); border-radius: 6px; color: var(--text); font-size: 12px; cursor: pointer; text-decoration: none; transition: border-color 0.15s, color 0.15s; white-space: nowrap; }
    .btn-orden:hover { border-color: rgba(59,130,246,0.4); color: var(--accent); }
    .btn-orden.active { border-color: var(--accent); color: var(--accent); background: rgba(59,130,246,0.08); }

    /* Lista de productos */
    .productos-lista { display: flex; flex-direction: column; gap: 12px; }

    .producto-row {
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 12px; display: grid;
        grid-template-columns: 140px 1fr auto;
        gap: 0; overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .producto-row:hover { border-color: rgba(59,130,246,0.3); box-shadow: 0 4px 20px rgba(0,0,0,0.2); }

    .row-img { width: 140px; height: 140px; background: #0d1117; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border-right: 1px solid var(--border); }
    .row-img img { width: 100%; height: 100%; object-fit: contain; padding: 16px; }
    .row-img .ph { font-size: 48px; opacity: 0.08; }

    .row-info { padding: 18px 20px; display: flex; flex-direction: column; justify-content: center; gap: 10px; flex: 1; min-width: 0; }
    .row-nombre { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #fff; line-height: 1.3; }
    .row-atributos { display: flex; flex-wrap: wrap; gap: 5px; }
    .attr-tag { background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 4px; padding: 2px 7px; font-size: 11px; color: var(--muted); font-family: 'Space Mono', monospace; }
    .row-descripcion { font-size: 13px; color: var(--muted); line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .badge-sinstock { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 100px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; display: inline-block; }
    .badge-stock { font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 100px; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: #22c55e; display: inline-block; }

    .row-accion { padding: 18px 20px; display: flex; flex-direction: column; align-items: flex-end; justify-content: center; gap: 12px; border-left: 1px solid var(--border); min-width: 160px; flex-shrink: 0; }
    .row-precio { font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 800; color: #fff; text-align: right; line-height: 1; }
    .row-precio span { font-size: 14px; font-weight: 400; color: var(--muted); }
    .btn-anadir { width: 100%; padding: 9px 16px; background: var(--accent); color: #fff; border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; transition: opacity 0.2s, transform 0.15s; }
    .btn-anadir:hover { opacity: 0.88; transform: translateY(-1px); }
    .btn-configurar { width: 100%; padding: 8px 16px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 7px; font-size: 12px; cursor: pointer; text-decoration: none; text-align: center; transition: color 0.15s, border-color 0.15s; }
    .btn-configurar:hover { color: var(--accent); border-color: rgba(59,130,246,0.3); }

    .paginacion { margin-top: 24px; }
    .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; }
    .empty-state .icon { font-size: 48px; margin-bottom: 16px; opacity: 0.3; }

    @media (max-width: 900px) {
        .catalogo-wrap { grid-template-columns: 1fr; padding: 24px 16px 60px; }
        .catalogo-sidebar { position: static; }
        .cat-lista { display: flex; flex-wrap: wrap; gap: 0; }
        .cat-item a { padding: 8px 12px; font-size: 12px; border-bottom: none; border-right: 1px solid var(--border); }
        .producto-row { grid-template-columns: 100px 1fr; }
        .row-img { width: 100px; height: 100px; }
        .row-accion { display: none; }
    }
</style>
@endpush

@section('content')
<div class="catalogo-wrap">

    {{-- Sidebar --}}
    <aside class="catalogo-sidebar">
        <p class="sidebar-titulo">Categorías</p>
        <ul class="cat-lista">
            @php
            $iconos = [
                'Procesadores' => '🔬',
                'Placas base' => '🔧',
                'Memoria RAM' => '💾',
                'Tarjetas gráficas' => '🎮',
                'Fuentes de alimentación' => '⚡',
                'Almacenamiento' => '💿',
                'Refrigeración' => '❄️',
                'Cajas' => '🖥️',
            ];
            @endphp
            @foreach($categorias as $cat)
            <li class="cat-item {{ $cat->id === $categoria->id ? 'active' : '' }}">
                <a href="{{ route('catalogo.categoria', $cat->slug) }}">
                    <span class="cat-icon">{{ $iconos[$cat->nombre] ?? '📦' }}</span>
                    {{ $cat->nombre }}
                </a>
            </li>
            @endforeach
        </ul>
    </aside>

    <div>
        {{-- Header --}}
        <div class="catalogo-header">
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Inicio</a> / {{ $categoria->nombre }}
            </div>
            <h1>{{ $categoria->nombre }}</h1>
            <p>{{ $productos->total() }} producto(s) disponibles</p>
        </div>

        {{-- Toolbar --}}
        <div class="catalogo-toolbar">
            <div class="toolbar-orden">
                <span>Ordenar:</span>
                <a href="{{ request()->fullUrlWithQuery(['orden' => 'nombre', 'dir' => 'asc']) }}"
                   class="btn-orden {{ $orden === 'nombre' ? 'active' : '' }}">Nombre</a>
                <a href="{{ request()->fullUrlWithQuery(['orden' => 'precio', 'dir' => $orden === 'precio' && $direccion === 'asc' ? 'desc' : 'asc']) }}"
                   class="btn-orden {{ $orden === 'precio' ? 'active' : '' }}">
                    Precio {{ $orden === 'precio' ? ($direccion === 'asc' ? '↑' : '↓') : '' }}
                </a>
            </div>
            <span style="font-size:13px;color:var(--muted)">{{ $productos->total() }} resultados</span>
        </div>

        {{-- Lista productos --}}
        @if($productos->isEmpty())
            <div class="empty-state">
                <div class="icon">📦</div>
                <p>No hay productos en esta categoría.</p>
            </div>
        @else
        <div class="productos-lista">
            @foreach($productos as $producto)
            <div class="producto-row">
                <div class="row-img">
                    @if($producto->imagen)
                        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" loading="lazy">
                    @else
                        <div class="ph">🖥️</div>
                    @endif
                </div>
                <div class="row-info">
                    <div class="row-nombre">{{ $producto->nombre }}</div>
                    @if($producto->atributoValores->isNotEmpty())
                    <div class="row-atributos">
                        @foreach($producto->atributoValores->take(5) as $av)
                            <span class="attr-tag">{{ $av->atributo->nombre }}: {{ $av->valor }}</span>
                        @endforeach
                    </div>
                    @endif
                    @if($producto->descripcion)
                        <div class="row-descripcion">{{ $producto->descripcion }}</div>
                    @endif
                    @if($producto->enStock())
                        <span class="badge-stock">● En stock ({{ $producto->stock }} uds.)</span>
                    @else
                        <span class="badge-sinstock">Sin stock</span>
                    @endif
                </div>
                <div class="row-accion">
                    <div class="row-precio">
                        {{ number_format($producto->precio, 2, ',', '.') }}<span> €</span>
                    </div>
                    @if($producto->enStock())
                        <form action="{{ route('carrito.anadir') }}" method="POST" style="width:100%">
    @csrf
    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
    <input type="hidden" name="cantidad" value="1">
    <button type="submit" class="btn-anadir">🛒 Añadir</button>
</form>
                    @endif
                    <a href="{{ route('configurador.plataforma') }}" class="btn-configurar">⚙ Configurar PC</a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="paginacion">
            {{ $productos->links() }}
        </div>
        @endif
    </div>

</div>
@endsection