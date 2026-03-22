@extends('admin.layout')

@section('title', isset($producto) ? 'Editar producto' : 'Nuevo producto')
@section('topbar_title', isset($producto) ? 'Editar producto' : 'Nuevo producto')

@section('topbar_actions')
    <a href="{{ route('admin.productos.index') }}" class="topbar-link">← Volver a productos</a>
@endsection

@push('styles')
<style>
    .form-wrap { max-width: 900px; margin: 0 auto; padding: 40px 32px 80px; }
    .form-header { display: flex; align-items: center; gap: 16px; margin-bottom: 36px; }
    .btn-back { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; color: var(--muted); font-size: 13px; text-decoration: none; transition: color 0.15s; }
    .btn-back:hover { color: var(--text); }
    .form-header h1 { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: #fff; }

    .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 32px; margin-bottom: 24px; }
    .form-card h2 { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid var(--border); }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .form-group { display: flex; flex-direction: column; gap: 7px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 13px; font-weight: 600; color: var(--text); }
    .form-input, .form-select, .form-textarea {
        background: var(--bg); border: 1px solid var(--border);
        border-radius: 8px; padding: 11px 14px; color: var(--text);
        font-size: 14px; font-family: 'Inter', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s; outline: none;
        width: 100%;
    }
    .form-input:focus, .form-select:focus, .form-textarea:focus {
        border-color: rgba(59,130,246,0.5); box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .form-input.is-invalid, .form-select.is-invalid, .form-textarea.is-invalid { border-color: rgba(239,68,68,0.5); }
    .form-textarea { resize: vertical; min-height: 120px; }
    .form-error { font-size: 12px; color: #ef4444; }
    .form-hint { font-size: 12px; color: var(--muted); }

    /* Imagen actual */
    .imagen-actual { display: flex; align-items: center; gap: 16px; padding: 16px; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; margin-bottom: 12px; }
    .imagen-actual img { width: 80px; height: 80px; object-fit: contain; border-radius: 6px; background: #0d1117; padding: 4px; }
    .imagen-actual-info { flex: 1; }
    .imagen-actual-info p { font-size: 13px; color: var(--muted); margin-bottom: 8px; }
    .check-eliminar { display: flex; align-items: center; gap: 8px; font-size: 13px; color: #ef4444; cursor: pointer; }
    .check-eliminar input { accent-color: #ef4444; }

    /* Atributos */
    .atributos-lista { display: flex; flex-direction: column; gap: 10px; }
    .atributo-fila { display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: center; }
    .btn-add-attr { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: transparent; border: 1px dashed rgba(59,130,246,0.3); border-radius: 8px; color: var(--accent); font-size: 13px; cursor: pointer; margin-top: 12px; transition: background 0.15s; }
    .btn-add-attr:hover { background: rgba(59,130,246,0.06); }
    .btn-remove-attr { width: 32px; height: 32px; border-radius: 6px; background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #ef4444; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: background 0.15s; }
    .btn-remove-attr:hover { background: rgba(239,68,68,0.2); }

    .form-actions { display: flex; gap: 12px; justify-content: flex-end; }
    .btn-submit { padding: 13px 28px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.15s; }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-cancel { padding: 13px 20px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; text-decoration: none; transition: color 0.2s; }
    .btn-cancel:hover { color: var(--text); }

    @media (max-width: 640px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-wrap { padding: 24px 16px 60px; }
        .atributo-fila { grid-template-columns: 1fr auto; }
        .atributo-fila select { grid-column: 1 / -1; }
    }
</style>
@endpush

@section('content')
<div class="form-wrap">
    <div class="form-header">
        <a href="{{ route('admin.productos.index') }}" class="btn-back">← Volver</a>
        <h1>{{ isset($producto) ? 'Editar producto' : 'Nuevo producto' }}</h1>
    </div>

    <form action="{{ isset($producto) ? route('admin.productos.update', $producto) : route('admin.productos.store') }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($producto)) @method('PUT') @endif

        {{-- Info básica --}}
        <div class="form-card">
            <h2>Información básica</h2>
            <div class="form-grid">
                <div class="form-group full">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre ?? '') }}"
                        class="form-input {{ $errors->has('nombre') ? 'is-invalid' : '' }}"
                        placeholder="Ej: AMD Ryzen 5 7600X">
                    @error('nombre')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Categoría *</label>
                    <select name="categoria_id" class="form-select {{ $errors->has('categoria_id') ? 'is-invalid' : '' }}">
                        <option value="">Selecciona categoría</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}"
                                {{ old('categoria_id', $producto->categoria_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Precio (€) *</label>
                    <input type="number" name="precio" step="0.01" min="0"
                        value="{{ old('precio', $producto->precio ?? '') }}"
                        class="form-input {{ $errors->has('precio') ? 'is-invalid' : '' }}"
                        placeholder="0.00">
                    @error('precio')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Stock *</label>
                    <input type="number" name="stock" min="0"
                        value="{{ old('stock', $producto->stock ?? 0) }}"
                        class="form-input {{ $errors->has('stock') ? 'is-invalid' : '' }}">
                    @error('stock')<span class="form-error">{{ $message }}</span>@enderror
                </div>
                <div class="form-group full">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-textarea" placeholder="Descripción detallada del producto...">{{ old('descripcion', $producto->descripcion ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Imagen --}}
        <div class="form-card">
            <h2>Imagen del producto</h2>
            @if(isset($producto) && $producto->imagen)
                <div class="imagen-actual">
                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                    <div class="imagen-actual-info">
                        <p>Imagen actual. Sube una nueva para reemplazarla.</p>
                        <label class="check-eliminar">
                            <input type="checkbox" name="eliminar_imagen" value="1">
                            Eliminar imagen actual
                        </label>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label class="form-label">{{ isset($producto) && $producto->imagen ? 'Nueva imagen (opcional)' : 'Imagen' }}</label>
                <input type="file" name="imagen" accept="image/*" class="form-input {{ $errors->has('imagen') ? 'is-invalid' : '' }}">
                <span class="form-hint">JPG, PNG o WebP. Máximo 2MB.</span>
                @error('imagen')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        {{-- Atributos --}}
        <div class="form-card">
            <h2>Especificaciones técnicas</h2>
            <div class="atributos-lista" id="atributos-lista">
                @if(isset($producto) && $producto->atributoValores->isNotEmpty())
                    @foreach($producto->atributoValores as $av)
                    <div class="atributo-fila">
                        <select name="atributo_ids[]" class="form-select">
                            <option value="">Selecciona atributo</option>
                            @foreach($atributos as $atr)
                                <option value="{{ $atr->id }}" {{ $atr->id == $av->atributo_id ? 'selected' : '' }}>
                                    {{ $atr->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="atributo_valores[]" value="{{ $av->valor }}"
                            class="form-input" placeholder="Valor">
                        <button type="button" class="btn-remove-attr" onclick="this.closest('.atributo-fila').remove()">✕</button>
                    </div>
                    @endforeach
                @else
                    <div class="atributo-fila">
                        <select name="atributo_ids[]" class="form-select">
                            <option value="">Selecciona atributo</option>
                            @foreach($atributos as $atr)
                                <option value="{{ $atr->id }}">{{ $atr->nombre }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="atributo_valores[]" class="form-input" placeholder="Valor">
                        <button type="button" class="btn-remove-attr" onclick="this.closest('.atributo-fila').remove()">✕</button>
                    </div>
                @endif
            </div>
            <button type="button" class="btn-add-attr" onclick="addAtributo()">+ Añadir especificación</button>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.productos.index') }}" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-submit">
                {{ isset($producto) ? '💾 Guardar cambios' : '✓ Crear producto' }}
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const atributosOpciones = `@foreach($atributos as $atr)<option value="{{ $atr->id }}">{{ $atr->nombre }}</option>@endforeach`;

function addAtributo() {
    const fila = document.createElement('div');
    fila.className = 'atributo-fila';
    fila.innerHTML = `
        <select name="atributo_ids[]" class="form-select">
            <option value="">Selecciona atributo</option>
            ${atributosOpciones}
        </select>
        <input type="text" name="atributo_valores[]" class="form-input" placeholder="Valor">
        <button type="button" class="btn-remove-attr" onclick="this.closest('.atributo-fila').remove()">✕</button>
    `;
    document.getElementById('atributos-lista').appendChild(fila);
}
</script>
@endpush