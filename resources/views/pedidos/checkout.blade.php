@extends('layouts.app')

@section('title', 'Finalizar compra | Reset Informática')

@push('styles')
<style>
    .checkout-wrap {
        max-width: 1100px; margin: 0 auto; padding: 48px 32px 80px;
        display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start;
    }
    .checkout-header { margin-bottom: 32px; grid-column: 1 / -1; }
    .checkout-header h1 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
    .checkout-header p { color: var(--muted); font-size: 14px; margin-top: 4px; }

    .form-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 28px; margin-bottom: 20px; }
    .form-card h2 { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--border); }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-group { display: flex; flex-direction: column; gap: 6px; }
    .form-group.full { grid-column: 1 / -1; }
    .form-label { font-size: 13px; font-weight: 600; color: var(--text); }
    .form-input, .form-textarea {
        background: var(--bg); border: 1px solid var(--border);
        border-radius: 8px; padding: 10px 14px; color: var(--text);
        font-size: 14px; font-family: 'Inter', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s; outline: none; width: 100%;
    }
    .form-input:focus, .form-textarea:focus { border-color: rgba(59,130,246,0.5); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .form-input.is-invalid { border-color: rgba(239,68,68,0.5); }
    .form-textarea { resize: vertical; min-height: 80px; }
    .form-error { font-size: 12px; color: #ef4444; }

    .resumen-card { position: sticky; top: 84px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 24px; }
    .resumen-titulo { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 20px; }
    .resumen-item { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 13px; }
    .resumen-item:last-child { border-bottom: none; }
    .resumen-item-nombre { color: var(--text); flex: 1; line-height: 1.4; }
    .resumen-item-cat { font-size: 11px; color: var(--accent); font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 2px; }
    .resumen-item-precio { color: #fff; font-weight: 600; white-space: nowrap; font-family: 'Inter', sans-serif; }
    .resumen-total { display: flex; justify-content: space-between; align-items: baseline; padding-top: 16px; margin-top: 8px; border-top: 1px solid var(--border); }
    .resumen-total-label { font-size: 14px; color: var(--muted); }
    .resumen-total-valor { font-family: 'Inter', sans-serif; font-size: 26px; font-weight: 800; color: #fff; }
    .resumen-total-valor em { font-style: normal; font-size: 14px; color: var(--muted); }

    .btn-submit { display: block; width: 100%; padding: 14px; margin-top: 20px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.15s; font-family: 'Syne', sans-serif; }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-back { display: inline-flex; align-items: center; gap: 6px; color: var(--muted); text-decoration: none; font-size: 13px; margin-top: 12px; transition: color 0.15s; }
    .btn-back:hover { color: var(--text); }

    .seguridad { display: flex; align-items: center; gap: 8px; margin-top: 14px; font-size: 12px; color: var(--muted); justify-content: center; }

    @media (max-width: 900px) {
        .checkout-wrap { grid-template-columns: 1fr; padding: 24px 16px 60px; }
        .resumen-card { position: static; }
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="checkout-wrap">
    <div class="checkout-header">
        <h1>Finalizar compra</h1>
        <p>Introduce tus datos de envío para completar el pedido.</p>
    </div>

    <div>
        <form action="{{ route('checkout.confirmar') }}" method="POST" id="checkout-form">
            @csrf

            <div class="form-card">
                <h2>Datos personales</h2>
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Nombre completo *</label>
                        <input type="text" name="nombre_completo" value="{{ old('nombre_completo') }}"
                            class="form-input {{ $errors->has('nombre_completo') ? 'is-invalid' : '' }}"
                            placeholder="Juan García López">
                        @error('nombre_completo')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teléfono *</label>
                        <input type="tel" name="telefono" value="{{ old('telefono') }}"
                            class="form-input {{ $errors->has('telefono') ? 'is-invalid' : '' }}"
                            placeholder="600 000 000">
                        @error('telefono')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h2>Dirección de envío</h2>
                <div class="form-grid">
                    <div class="form-group full">
                        <label class="form-label">Dirección *</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}"
                            class="form-input {{ $errors->has('direccion') ? 'is-invalid' : '' }}"
                            placeholder="Calle Mayor, 1, 2º A">
                        @error('direccion')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ciudad *</label>
                        <input type="text" name="ciudad" value="{{ old('ciudad') }}"
                            class="form-input {{ $errors->has('ciudad') ? 'is-invalid' : '' }}"
                            placeholder="Madrid">
                        @error('ciudad')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Código postal *</label>
                        <input type="text" name="codigo_postal" value="{{ old('codigo_postal') }}"
                            class="form-input {{ $errors->has('codigo_postal') ? 'is-invalid' : '' }}"
                            placeholder="28001">
                        @error('codigo_postal')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Provincia *</label>
                        <input type="text" name="provincia" value="{{ old('provincia') }}"
                            class="form-input {{ $errors->has('provincia') ? 'is-invalid' : '' }}"
                            placeholder="Madrid">
                        @error('provincia')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">País *</label>
                        <input type="text" name="pais" value="{{ old('pais', 'España') }}"
                            class="form-input {{ $errors->has('pais') ? 'is-invalid' : '' }}">
                        @error('pais')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Notas adicionales</label>
                        <textarea name="notas" class="form-textarea"
                            placeholder="Instrucciones especiales para la entrega...">{{ old('notas') }}</textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div>
        <div class="resumen-card">
            <div class="resumen-titulo">Resumen del pedido</div>
            @foreach($items as $item)
            <div class="resumen-item">
                <div class="resumen-item-nombre">
                    <span class="resumen-item-cat">{{ $item->producto->categoria->nombre }}</span>
                    {{ $item->producto->nombre }}
                    @if($item->cantidad > 1)
                        <span style="color:var(--muted)"> x{{ $item->cantidad }}</span>
                    @endif
                </div>
                <div class="resumen-item-precio">{{ number_format($item->subtotal, 2, ',', '.') }} €</div>
            </div>
            @endforeach

            <div class="resumen-total">
                <span class="resumen-total-label">Total</span>
                <span class="resumen-total-valor">{{ number_format($total, 2, ',', '.') }}<em> €</em></span>
            </div>

            <button type="submit" form="checkout-form" class="btn-submit">
                Confirmar pedido →
            </button>
            <div class="seguridad">🔒 Pago seguro y envío gratuito</div>
            <div style="text-align:center">
                <a href="{{ route('carrito.index') }}" class="btn-back">← Volver al carrito</a>
            </div>
        </div>
    </div>
</div>
@endsection