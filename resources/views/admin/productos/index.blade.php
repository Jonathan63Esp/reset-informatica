@extends('admin.layout')

@section('title', 'Productos')
@section('topbar_title', 'Gestión de productos')

@push('styles')
<style>
    .admin-wrap { max-width: 1400px; margin: 0 auto; padding: 40px 32px 80px; }
    .admin-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; flex-wrap: wrap; gap: 16px; }
    .admin-header h1 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
    .admin-header h1 small { display: block; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 400; color: var(--muted); letter-spacing: 0; margin-bottom: 4px; }
    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; transition: opacity 0.2s, transform 0.15s; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }

    .filters { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .filter-input { background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; color: var(--text); font-size: 14px; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.2s; }
    .filter-input:focus { border-color: rgba(59,130,246,0.4); }
    .filter-input::placeholder { color: var(--muted); }
    .btn-filter { padding: 10px 18px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 14px; cursor: pointer; transition: background 0.15s; }
    .btn-filter:hover { background: var(--bg-hover); }

    .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); border-radius: 8px; padding: 12px 16px; color: #22c55e; font-size: 14px; margin-bottom: 20px; }

    .tabla-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); border-bottom: 1px solid var(--border); white-space: nowrap; }
    tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }
    tbody td { padding: 14px 16px; font-size: 14px; color: var(--text); vertical-align: middle; }

    .prod-img { width: 48px; height: 48px; border-radius: 8px; background: #0d1117; object-fit: contain; padding: 4px; }
    .prod-img-ph { width: 48px; height: 48px; border-radius: 8px; background: #0d1117; display: flex; align-items: center; justify-content: center; font-size: 20px; opacity: 0.2; }
    .prod-nombre { font-weight: 600; color: #fff; }
    .prod-categoria { font-size: 12px; color: var(--accent); font-weight: 600; letter-spacing: 0.05em; text-transform: uppercase; margin-top: 2px; }
    .badge-stock-ok  { background: rgba(34,197,94,0.12); border: 1px solid rgba(34,197,94,0.25); color: #22c55e; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 100px; }
    .badge-stock-low { background: rgba(245,158,11,0.12); border: 1px solid rgba(245,158,11,0.25); color: #f59e0b; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 100px; }
    .badge-stock-out { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #ef4444; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 100px; }
    .precio-txt { font-family: 'Inter', sans-serif; font-weight: 700; color: #fff; }

    .acciones { display: flex; gap: 8px; }
    .btn-edit { padding: 6px 12px; background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); border-radius: 6px; color: #3b82f6; font-size: 12px; font-weight: 600; text-decoration: none; transition: background 0.15s; }
    .btn-edit:hover { background: rgba(59,130,246,0.2); }
    .btn-delete { padding: 6px 12px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); border-radius: 6px; color: #ef4444; font-size: 12px; font-weight: 600; cursor: pointer; transition: background 0.15s; }
    .btn-delete:hover { background: rgba(239,68,68,0.2); }

    .paginacion { padding: 20px; display: flex; justify-content: center; }
    .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); }
    .empty-state .icon { font-size: 48px; margin-bottom: 16px; opacity: 0.3; }
</style>
@endpush

@section('content')
<div class="admin-wrap">
    <div class="admin-header">
        <h1><small>Panel de administración</small>Productos</h1>
        <a href="{{ route('admin.productos.create') }}" class="btn-primary">+ Nuevo producto</a>
    </div>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    <form method="GET" action="{{ route('admin.productos.index') }}" class="filters">
        <input type="text" name="buscar" placeholder="Buscar producto..." value="{{ request('buscar') }}" class="filter-input" style="flex:1;min-width:200px">
        <select name="categoria" class="filter-input">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->nombre }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn-filter">Filtrar</button>
        @if(request('buscar') || request('categoria'))
            <a href="{{ route('admin.productos.index') }}" class="btn-filter">✕ Limpiar</a>
        @endif
    </form>

    <div class="tabla-wrap">
        @if($productos->isEmpty())
            <div class="empty-state">
                <div class="icon">📦</div>
                <p>No hay productos que mostrar.</p>
            </div>
        @else
        <table>
            @php
    $orden = request('orden', 'nombre');
    $dir   = request('dir', 'asc');
    $toggleDir = $dir === 'asc' ? 'desc' : 'asc';
@endphp
<thead>
    <tr>
        <th>Imagen</th>
        <th>
            <a href="{{ request()->fullUrlWithQuery(['orden' => 'nombre', 'dir' => $orden === 'nombre' ? $toggleDir : 'asc']) }}"
               style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px">
                Producto
                @if($orden === 'nombre') <span>{{ $dir === 'asc' ? '↑' : '↓' }}</span> @else <span style="opacity:0.3">↕</span> @endif
            </a>
        </th>
        <th>
            <a href="{{ request()->fullUrlWithQuery(['orden' => 'precio', 'dir' => $orden === 'precio' ? $toggleDir : 'asc']) }}"
               style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px">
                Precio
                @if($orden === 'precio') <span>{{ $dir === 'asc' ? '↑' : '↓' }}</span> @else <span style="opacity:0.3">↕</span> @endif
            </a>
        </th>
        <th>
            <a href="{{ request()->fullUrlWithQuery(['orden' => 'stock', 'dir' => $orden === 'stock' ? $toggleDir : 'asc']) }}"
               style="color:inherit;text-decoration:none;display:flex;align-items:center;gap:4px">
                Stock
                @if($orden === 'stock') <span>{{ $dir === 'asc' ? '↑' : '↓' }}</span> @else <span style="opacity:0.3">↕</span> @endif
            </a>
        </th>
        <th>Acciones</th>
    </tr>
</thead>
            <tbody>
                @foreach($productos as $producto)
                <tr>
                    <td>
                        @if($producto->imagen)
                            <img src="{{ $producto->imagen_url }}" class="prod-img" alt="{{ $producto->nombre }}">
                        @else
                            <div class="prod-img-ph">📦</div>
                        @endif
                    </td>
                    <td>
                        <div class="prod-nombre">{{ $producto->nombre }}</div>
                        <div class="prod-categoria">{{ $producto->categoria->nombre }}</div>
                    </td>
                    <td><span class="precio-txt">{{ number_format($producto->precio, 2, ',', '.') }} €</span></td>
                    <td>
                        @if($producto->stock === 0)
                            <span class="badge-stock-out">Sin stock</span>
                        @elseif($producto->stock <= 5)
                            <span class="badge-stock-low">{{ $producto->stock }} uds.</span>
                        @else
                            <span class="badge-stock-ok">{{ $producto->stock }} uds.</span>
                        @endif
                    </td>
                    <td>
                        <div class="acciones">
                            <a href="{{ route('admin.productos.edit', $producto) }}" class="btn-edit">✏ Editar</a>
                            <form action="{{ route('admin.productos.destroy', $producto) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete"
                                    onclick="return confirm('¿Eliminar {{ addslashes($producto->nombre) }}?')">
                                    🗑 Borrar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="paginacion">{{ $productos->links() }}</div>
        @endif
    </div>
</div>
@endsection