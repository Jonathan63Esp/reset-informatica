@extends('admin.layout')

@section('title', 'Usuarios')
@section('topbar_title', 'Gestión de usuarios')

@push('head_styles')
<style>
    .filtros { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
    .filtro-input { background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; padding: 9px 14px; color: var(--text); font-size: 13px; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.2s; }
    .filtro-input:focus { border-color: rgba(59,130,246,0.4); }
    .filtro-input::placeholder { color: var(--muted); }
    .btn-filtrar { padding: 9px 18px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; color: var(--text); font-size: 13px; cursor: pointer; }
    .btn-filtrar:hover { background: var(--bg-hover); }
    .btn-limpiar { padding: 9px 14px; background: transparent; border: 1px solid var(--border); border-radius: 8px; color: var(--muted); font-size: 13px; text-decoration: none; }

    .tabla-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: var(--muted); border-bottom: 1px solid var(--border); }
    tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }
    tbody td { padding: 13px 16px; font-size: 13px; color: var(--text); vertical-align: middle; }

    .user-avatar { width: 32px; height: 32px; border-radius: 8px; background: rgba(59,130,246,0.15); display: inline-flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: var(--accent); margin-right: 10px; }
    .badge-admin { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 100px; }
    .badge-user  { background: rgba(107,114,128,0.12); border: 1px solid rgba(107,114,128,0.3); color: var(--muted); font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 100px; }

    .acciones { display: flex; gap: 8px; }
    .btn-toggle-admin { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; border: 1px solid; transition: background 0.15s; }
    .btn-make-admin  { background: rgba(239,68,68,0.08); border-color: rgba(239,68,68,0.2); color: #ef4444; }
    .btn-make-admin:hover  { background: rgba(239,68,68,0.15); }
    .btn-remove-admin { background: rgba(107,114,128,0.08); border-color: rgba(107,114,128,0.2); color: var(--muted); }
    .btn-remove-admin:hover { background: rgba(107,114,128,0.15); }
    .btn-delete { padding: 5px 10px; background: transparent; border: 1px solid rgba(239,68,68,0.2); border-radius: 6px; color: #ef4444; font-size: 11px; font-weight: 600; cursor: pointer; transition: background 0.15s; }
    .btn-delete:hover { background: rgba(239,68,68,0.1); }

    .paginacion { padding: 16px; display: flex; justify-content: center; }
    .empty { text-align: center; padding: 48px; color: var(--muted); }
</style>
@endpush

@section('content')

<form method="GET" action="{{ route('admin.usuarios.index') }}" class="filtros">
    <input type="text" name="buscar" placeholder="Buscar usuario..." value="{{ request('buscar') }}" class="filtro-input" style="flex:1;min-width:200px">
    <select name="tipo" class="filtro-input">
        <option value="">Todos</option>
        <option value="admin" {{ request('tipo') === 'admin' ? 'selected' : '' }}>Administradores</option>
        <option value="usuario" {{ request('tipo') === 'usuario' ? 'selected' : '' }}>Usuarios</option>
    </select>
    <button type="submit" class="btn-filtrar">Filtrar</button>
    @if(request('buscar') || request('tipo'))
        <a href="{{ route('admin.usuarios.index') }}" class="btn-limpiar">✕ Limpiar</a>
    @endif
</form>

<div class="tabla-wrap">
    @if($usuarios->isEmpty())
        <div class="empty">No hay usuarios que mostrar.</div>
    @else
    <table>
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Pedidos</th>
                <th>Registro</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($usuarios as $usuario)
            <tr>
                <td>
                    <div style="display:flex;align-items:center">
                        <span class="user-avatar">{{ strtoupper(substr($usuario->username, 0, 1)) }}</span>
                        {{ $usuario->username }}
                        @if($usuario->id === Auth::id())
                            <span style="font-size:11px;color:var(--muted);margin-left:6px">(tú)</span>
                        @endif
                    </div>
                </td>
                <td>
                    @if($usuario->is_admin)
                        <span class="badge-admin">Admin</span>
                    @else
                        <span class="badge-user">Usuario</span>
                    @endif
                </td>
                <td style="color:var(--muted)">{{ $usuario->pedidos()->count() }}</td>
                <td style="color:var(--muted)">{{ $usuario->created_at->format('d/m/Y') }}</td>
                <td>
                    @if($usuario->id !== Auth::id())
                    <div class="acciones">
                        <form action="{{ route('admin.usuarios.toggle-admin', $usuario) }}" method="POST">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="btn-toggle-admin {{ $usuario->is_admin ? 'btn-remove-admin' : 'btn-make-admin' }}"
                                onclick="return confirm('¿{{ $usuario->is_admin ? 'Quitar' : 'Dar' }} permisos de admin a {{ $usuario->username }}?')">
                                {{ $usuario->is_admin ? '↓ Quitar admin' : '↑ Hacer admin' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete"
                                onclick="return confirm('¿Eliminar usuario {{ $usuario->username }}? Esta acción no se puede deshacer.')">
                                🗑 Borrar
                            </button>
                        </form>
                    </div>
                    @else
                        <span style="font-size:12px;color:var(--muted)">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="paginacion">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection