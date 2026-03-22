@extends('layouts.app')

@section('title', 'Mi perfil | Reset Informática')

@push('styles')
<style>
    .perfil-wrap { max-width: 700px; margin: 0 auto; padding: 48px 32px 80px; }
    .perfil-header { margin-bottom: 32px; }
    .perfil-header h1 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }
    .perfil-header p { color: var(--muted); font-size: 14px; margin-top: 4px; }

    .perfil-avatar { width: 72px; height: 72px; border-radius: 16px; background: rgba(59,130,246,0.15); border: 2px solid rgba(59,130,246,0.3); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: var(--accent); margin-bottom: 20px; }

    .info-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 24px; margin-bottom: 20px; }
    .info-card h2 { font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid var(--border); }

    .info-fila { display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 14px; }
    .info-fila:last-child { border-bottom: none; }
    .info-fila label { color: var(--muted); font-weight: 500; }
    .info-fila span { color: var(--text); font-weight: 600; }
    .info-fila .badge-admin { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 100px; }

    .form-group { margin-bottom: 16px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 7px; }
    .form-input { width: 100%; background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 11px 14px; color: var(--text); font-size: 14px; font-family: 'Inter', sans-serif; transition: border-color 0.2s, box-shadow 0.2s; outline: none; }
    .form-input:focus { border-color: rgba(59,130,246,0.5); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .form-input.is-invalid { border-color: rgba(239,68,68,0.5); }
    .form-error { font-size: 12px; color: #ef4444; margin-top: 5px; display: block; }

    .btn-submit { padding: 12px 24px; background: var(--accent); color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.15s; }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 20px; }
    .stat-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 16px 20px; text-align: center; }
    .stat-valor { font-family: 'Inter', sans-serif; font-size: 24px; font-weight: 800; color: #fff; }
    .stat-label { font-size: 12px; color: var(--muted); margin-top: 4px; }

    .alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); border-radius: 8px; padding: 12px 16px; color: #22c55e; font-size: 14px; margin-bottom: 20px; }

    @media (max-width: 640px) {
        .perfil-wrap { padding: 24px 16px 60px; }
        .stats-grid { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="perfil-wrap">
    <div class="perfil-header">
        <div class="perfil-avatar">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</div>
        <h1>Mi perfil</h1>
        <p>Gestiona tu cuenta y preferencias.</p>
    </div>

    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-valor">{{ Auth::user()->pedidos()->count() }}</div>
            <div class="stat-label">Pedidos realizados</div>
        </div>
        <div class="stat-card">
            <div class="stat-valor">{{ Auth::user()->carritoItems()->count() }}</div>
            <div class="stat-label">Items en carrito</div>
        </div>
        <div class="stat-card">
            <div class="stat-valor">{{ (int) Auth::user()->created_at->diffInDays() }}</div>
            <div class="stat-label">Días como miembro</div>
        </div>
    </div>

    {{-- Info cuenta --}}
    <div class="info-card">
        <h2>Información de la cuenta</h2>
        <div class="info-fila">
            <label>Nombre de usuario</label>
            <span>{{ Auth::user()->username }}</span>
        </div>
        <div class="info-fila">
            <label>Rol</label>
            <span>
                @if(Auth::user()->isAdmin())
                    <span class="badge-admin">Administrador</span>
                @else
                    Usuario
                @endif
            </span>
        </div>
        <div class="info-fila">
            <label>Miembro desde</label>
            <span>{{ Auth::user()->created_at->format('d/m/Y') }}</span>
        </div>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="info-card">
        <h2>Cambiar contraseña</h2>
        <form action="{{ route('perfil.password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Contraseña actual</label>
                <input type="password" name="password_actual" autocomplete="current-password"
                    class="form-input {{ $errors->has('password_actual') ? 'is-invalid' : '' }}"
                    placeholder="••••••••">
                @error('password_actual')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Nueva contraseña</label>
                <input type="password" name="password" autocomplete="new-password"
                    class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    placeholder="Mínimo 8 caracteres">
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" autocomplete="new-password"
                    class="form-input" placeholder="••••••••">
            </div>
            <button type="submit" class="btn-submit">Actualizar contraseña</button>
        </form>
    </div>

    <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="{{ route('pedidos.historial') }}" style="font-size:13px;color:var(--accent);text-decoration:none">📦 Ver mis pedidos →</a>
        <a href="{{ route('carrito.index') }}" style="font-size:13px;color:var(--muted);text-decoration:none">🛒 Ver carrito</a>
    </div>
</div>
@endsection