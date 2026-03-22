@extends('layouts.app')

@section('title', $query ? "Búsqueda: {$query}" : 'Búsqueda')

@push('styles')
<style>
    .busqueda-wrap { max-width: 1280px; margin: 0 auto; padding: 40px 32px 80px; display: grid; grid-template-columns: 220px 1fr; gap: 32px; align-items: start; }

    .busqueda-sidebar { position: sticky; top: 84px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    .sidebar-titulo { font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); padding: 16px 20px 12px; border-bottom: 1px solid var(--border); }

    .filtro-seccion { padding: 16px 20px; border-bottom: 1px solid var(--border); }
    .filtro-seccion:last-child { border-bottom: none; }
    .filtro-label { font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 10px; display: block; }
    .filtro-select { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 7px; padding: 8px 12px; color: var(--text); font-size: 13px; outline: none; }
    .filtro-select:focus { border-color: rgba(59,130,246,0.4); }
    .filtro-range { width: 100%; accent-color: var(--accent); margin-top: 6px; }
    .filtro-range-txt { display: flex; justify-content: space-between; font-size: 12px; color: var(--muted); margin-top: 4px; }
    .btn-aplicar { width: 100%; padding: 9px; background: var(--accent); color: #fff; border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; margin-top: 12px; transition: opacity 0.2s; }
    .btn-aplicar:hover { opacity: 0.9; }
    .btn-limpiar { display: block; text-align: center; font-size: 12px; color: var(--muted); text-decoration: none; margin-top: 8px; }
    .btn-limpiar:hover { color: var(--text); }

    .busqueda-header { margin-bottom: 20px; }
    .busqueda-header h1 { font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800; color: #fff; }
    .busqueda-header p { font-size: 14px; color: var(--muted); margin-top: 4px; }

    .toolbar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 12px; }
    .toolbar-total { font-size: 13px; color: var(--muted); }
    .toolbar-orden select { background: var(--bg-card); border: 1px solid var(--border); border-radius: 7px; padding: 7px 12px; color: var(--text); font-size: 13px; outline: none; }

    .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
    .producto-card { background: var(--bg-card); border: 1.5px solid var(--border); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: border-color 0.2s, transform 0.15s, box-shadow 0.2s; text-decoration: none; }
    .producto-card:hover { border-color: rgba(59,130,246,0.35); transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
    .producto-img { aspect-ratio: 4/3; background: #0d1117; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .producto-img img { width: 100%; height: 100%; object-fit: contain; padding: 16px; }
    .producto-img .ph { font-size: 48px; opacity: 0.08; }
    .producto-body { padding: 14px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
    .producto-cat { font-size: 11px; font-weight: 600; letter-spacing: 0.06em; text-transform: uppercase; color: var(--accent); }
    .producto-nombre { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; color: #fff; line-height: 1.3; }
    .producto-atribs { display: flex; flex-wrap: wrap; gap: 4px; margin-top: 2px; }
    .attr-tag { background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 3px; padding: 1px 6px; font-size: 10px; color: var(--muted); font-family: 'Space Mono', monospace; }
    .producto-footer { padding: 10px 14px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 8px; }
    .producto-precio { font-family: 'Inter', sans-serif; font-size: 18px; font-weight: 800; color: #fff; }
    .producto-precio span { font-size: 12px; font-weight: 400; color: var(--muted); }
    .btn-ver { padding: 6px 12px; background: var(--accent); color: #fff; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: none; white-space: nowrap; }

    .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
    .empty-state .icon { font-size: 48px; margin-bottom: 16px; opacity: 0.3; }
    .paginacion { margin-top: 24px; }

    @media (max-width: 900px) {
        .busqueda-wrap { grid-template-columns: 1fr; padding: 24px 16px 60px; }
        .busqueda-sidebar { position: static; }
    }
</style>
@endpush

@section('content')
<div class="busqueda-wrap">

    {{-- Sidebar filtros --}}
    <aside class="busqueda-sidebar">
        <p class="sidebar-titulo">Filtros</p>
        <form method="GET" action="{{ route('buscar') }}" id="filtros-form">
            <input type="hidden" name="q" value="{{ $query }}">

            <div class="filtro-seccion">
                <label class="filtro-label">Categoría</label>
                <select name="categoria" class="filtro-select" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    @foreach($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filtro-seccion">
                <label class="filtro-label">Precio máximo</label>
                <input type="range" name="precio_max" class="filtro-range"
                    min="0" max="2000" step="50"
                    value="{{ request('precio_max', 2000) }}"
                    oninput="document.getElementById('precio-max-txt').textContent = this.value + ' €'">
                <div class="filtro-range-txt">
                    <span>0 €</span>
                    <span id="precio-max-txt">{{ request('precio_max', 2000) }} €</span>
                </div>
                <button type="submit" class="btn-aplicar">Aplicar</button>
            </div>

            @if(request('categoria') || request('precio_max'))
            <div class="filtro-seccion">
                <a href="{{ route('buscar', ['q' => $query]) }}" class="btn-limpiar">✕ Limpiar filtros</a>
            </div>
            @endif
        </form>
    </aside>

    <div>
        <div class="busqueda-header">
            @if($query)
                <h1>Resultados para "{{ $query }}"</h1>
                <p>{{ $total }} producto(s) encontrado(s)</p>
            @else
                <h1>Búsqueda</h1>
                <p>Escribe algo para buscar productos.</p>
            @endif
        </div>

        @if($query && $productos->isNotEmpty())
        <div class="toolbar">
            <span class="toolbar-total">{{ $total }} resultados</span>
            <div class="toolbar-orden">
                <form method="GET" action="{{ route('buscar') }}">
                    <input type="hidden" name="q" value="{{ $query }}">
                    @if(request('categoria'))<input type="hidden" name="categoria" value="{{ request('categoria') }}">@endif
                    @if(request('precio_max'))<input type="hidden" name="precio_max" value="{{ request('precio_max') }}">@endif
                    <select name="orden" onchange="this.form.submit()">
                        <option value="nombre" {{ request('orden') === 'nombre' ? 'selected' : '' }}>Relevancia</option>
                        <option value="precio-asc" {{ request('orden') === 'precio-asc' ? 'selected' : '' }}>Precio más bajo</option>
                        <option value="precio-desc" {{ request('orden') === 'precio-desc' ? 'selected' : '' }}>Precio más alto</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="productos-grid">
            @foreach($productos as $producto)
            <a href="{{ route('catalogo.categoria', $producto->categoria->slug) }}" class="producto-card">
                <div class="producto-img">
                    @if($producto->imagen)
                        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" loading="lazy">
                    @else
                        <div class="ph">🖥️</div>
                    @endif
                </div>
                <div class="producto-body">
                    <div class="producto-cat">{{ $producto->categoria->nombre }}</div>
                    <div class="producto-nombre">{{ $producto->nombre }}</div>
                    @if($producto->atributoValores->isNotEmpty())
                    <div class="producto-atribs">
                        @foreach($producto->atributoValores->take(3) as $av)
                            <span class="attr-tag">{{ $av->atributo->nombre }}: {{ $av->valor }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="producto-footer">
                    <div class="producto-precio">{{ number_format($producto->precio, 2, ',', '.') }}<span> €</span></div>
                    <span class="btn-ver">Ver →</span>
                </div>
            </a>
            @endforeach
        </div>

        <div class="paginacion">{{ $productos->links() }}</div>

        @elseif($query)
        <div class="empty-state">
            <div class="icon">🔍</div>
            <p>No se encontraron productos para "{{ $query }}".</p>
            <p style="margin-top:8px;font-size:13px">Prueba con otros términos o navega por las categorías.</p>
        </div>
        @endif
    </div>
</div>
@endsection