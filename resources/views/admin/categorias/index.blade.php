@extends('admin.layout')

@section('title', 'Categorías')
@section('topbar_title', 'Gestión de categorías')

@push('head_styles')
<style>
    .categorias-grid { display: grid; grid-template-columns: 1fr 340px; gap: 24px; align-items: start; }

    .tabla-wrap { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
    table { width: 100%; border-collapse: collapse; }
    thead th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 700; letter-spacing: 0.07em; text-transform: uppercase; color: var(--muted); border-bottom: 1px solid var(--border); }
    tbody tr { border-bottom: 1px solid var(--border); transition: background 0.15s; }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: var(--bg-hover); }
    tbody td { padding: 13px 16px; font-size: 13px; color: var(--text); vertical-align: middle; }

    .cat-nombre { font-weight: 600; color: #fff; }
    .cat-slug { font-family: 'Space Mono', monospace; font-size: 11px; color: var(--muted); margin-top: 2px; }
    .badge-productos { background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2); color: var(--accent); font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 100px; }

    .acciones { display: flex; gap: 8px; align-items: center; }
    .btn-edit { padding: 5px 10px; background: rgba(59,130,246,0.08); border: 1px solid rgba(59,130,246,0.2); border-radius: 6px; color: var(--accent); font-size: 11px; font-weight: 600; cursor: pointer; transition: background 0.15s; }
    .btn-edit:hover { background: rgba(59,130,246,0.15); }
    .btn-delete { padding: 5px 10px; background: transparent; border: 1px solid rgba(239,68,68,0.2); border-radius: 6px; color: #ef4444; font-size: 11px; font-weight: 600; cursor: pointer; transition: background 0.15s; }
    .btn-delete:hover { background: rgba(239,68,68,0.1); }

    .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 24px; position: sticky; top: 76px; }
    .form-card h2 { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
    .form-group { margin-bottom: 14px; }
    .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--text); margin-bottom: 6px; }
    .form-input { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; color: var(--text); font-size: 14px; font-family: 'Inter', sans-serif; outline: none; transition: border-color 0.2s; }
    .form-input:focus { border-color: rgba(59,130,246,0.5); }
    .form-input.is-invalid { border-color: rgba(239,68,68,0.5); }
    .form-error { font-size: 12px; color: #ef4444; margin-top: 4px; display: block; }
    .btn-submit { width: 100%; padding: 11px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
    .btn-submit:hover { opacity: 0.9; }
    .btn-cancelar { width: 100%; padding: 9px; margin-top: 8px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 13px; cursor: pointer; transition: color 0.15s; }
    .btn-cancelar:hover { color: var(--text); }

    .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); z-index: 100; align-items: center; justify-content: center; }
    .modal-overlay.visible { display: flex; }
    .modal { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 28px; width: 100%; max-width: 400px; }
    .modal h3 { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 8px; }
    .modal p { font-size: 14px; color: var(--muted); margin-bottom: 20px; }
    .modal-acciones { display: flex; gap: 10px; }
    .btn-modal-cancel { flex: 1; padding: 10px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; cursor: pointer; }
    .btn-modal-confirm { flex: 1; padding: 10px; background: #ef4444; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
</style>
@endpush

@section('content')
<div class="categorias-grid">

    <div class="tabla-wrap">
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th>Productos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categorias as $categoria)
                <tr>
                    <td>
                        <div class="cat-nombre">{{ $categoria->nombre }}</div>
                        <div class="cat-slug">/categorias/{{ $categoria->slug }}</div>
                    </td>
                    <td><span class="badge-productos">{{ $categoria->productos_count }} productos</span></td>
                    <td>
                        <div class="acciones">
                            <button class="btn-edit" onclick="editarCategoria({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}')">
                                ✏ Editar
                            </button>
                            @if($categoria->productos_count === 0)
                            <button class="btn-delete" onclick="confirmarBorrar({{ $categoria->id }}, '{{ addslashes($categoria->nombre) }}')">
                                🗑 Borrar
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        <div class="form-card" id="form-card">
            <h2 id="form-titulo">Nueva categoría</h2>
            <form id="cat-form" method="POST" action="{{ route('admin.categorias.store') }}">
                @csrf
                <span id="method-field"></span>
                <div class="form-group">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" id="input-nombre"
                        class="form-input {{ $errors->has('nombre') ? 'is-invalid' : '' }}"
                        placeholder="Ej: Monitores" value="{{ old('nombre') }}">
                    @error('nombre')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <button type="submit" class="btn-submit" id="btn-submit-txt">Crear categoría</button>
                <button type="button" class="btn-cancelar" id="btn-cancelar" style="display:none" onclick="resetForm()">Cancelar</button>
            </form>
        </div>
    </div>
</div>

{{-- Modal borrar --}}
<div class="modal-overlay" id="modal-borrar">
    <div class="modal">
        <h3>¿Eliminar categoría?</h3>
        <p id="modal-texto">Esta acción no se puede deshacer.</p>
        <div class="modal-acciones">
            <button class="btn-modal-cancel" onclick="cerrarModal()">Cancelar</button>
            <form id="form-borrar" method="POST">
                @csrf @method('DELETE')
                <button type="submit" class="btn-modal-confirm">Eliminar</button>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editarCategoria(id, nombre) {
    document.getElementById('form-titulo').textContent = 'Editar categoría';
    document.getElementById('input-nombre').value = nombre;
    document.getElementById('btn-submit-txt').textContent = 'Guardar cambios';
    document.getElementById('btn-cancelar').style.display = 'block';

    const form = document.getElementById('cat-form');
    form.action = `/admin/categorias/${id}`;

    const methodField = document.getElementById('method-field');
    methodField.innerHTML = '<input type="hidden" name="_method" value="PATCH">';

    document.getElementById('form-card').scrollIntoView({ behavior: 'smooth' });
    document.getElementById('input-nombre').focus();
}

function resetForm() {
    document.getElementById('form-titulo').textContent = 'Nueva categoría';
    document.getElementById('input-nombre').value = '';
    document.getElementById('btn-submit-txt').textContent = 'Crear categoría';
    document.getElementById('btn-cancelar').style.display = 'none';
    document.getElementById('cat-form').action = '{{ route('admin.categorias.store') }}';
    document.getElementById('method-field').innerHTML = '';
}

function confirmarBorrar(id, nombre) {
    document.getElementById('modal-texto').textContent = `¿Seguro que quieres eliminar "${nombre}"?`;
    document.getElementById('form-borrar').action = `/admin/categorias/${id}`;
    document.getElementById('modal-borrar').classList.add('visible');
}

function cerrarModal() {
    document.getElementById('modal-borrar').classList.remove('visible');
}

document.getElementById('modal-borrar').addEventListener('click', function(e) {
    if (e.target === this) cerrarModal();
});
</script>
@endpush