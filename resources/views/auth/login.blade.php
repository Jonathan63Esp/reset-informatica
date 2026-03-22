@extends('layouts.app')

@section('title', 'Iniciar sesión | Reset Informática')

@push('styles')
<style>
    .auth-wrap {
        min-height: calc(100vh - 68px);
        display: flex; align-items: center; justify-content: center;
        padding: 48px 20px; position: relative;
    }
    .auth-bg {
        position: fixed; inset: 0;
        background: radial-gradient(ellipse 60% 50% at 50% 0%, rgba(59,130,246,0.12) 0%, transparent 60%), var(--bg);
        z-index: 0; pointer-events: none;
    }
    .auth-card {
        position: relative; z-index: 1;
        width: 100%; max-width: 420px;
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 16px; padding: 40px 36px;
    }
    .auth-logo { display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 32px; text-decoration: none; }
    .auth-logo-icon {
        width: 40px; height: 40px;
        background: linear-gradient(135deg, var(--accent), #06b6d4);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-family: 'Space Mono', monospace; font-size: 14px; font-weight: 700; color: white;
    }
    .auth-logo-text { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: var(--text); }
    .auth-logo-text span { color: var(--accent); }
    .auth-title { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 6px; text-align: center; }
    .auth-subtitle { font-size: 14px; color: var(--muted); text-align: center; margin-bottom: 32px; }
    .form-group { margin-bottom: 18px; }
    .form-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 7px; }
    .form-input {
        width: 100%; background: var(--bg); border: 1px solid var(--border);
        border-radius: 8px; padding: 11px 14px; color: var(--text);
        font-size: 14px; font-family: 'Inter', sans-serif;
        transition: border-color 0.2s, box-shadow 0.2s; outline: none;
    }
    .form-input::placeholder { color: var(--muted); opacity: 0.7; }
    .form-input:focus { border-color: rgba(59,130,246,0.5); box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .form-input.is-invalid { border-color: rgba(239,68,68,0.5); }
    .form-error { font-size: 12px; color: #ef4444; margin-top: 5px; display: block; }
    .form-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; margin-top: 6px; }
    .form-check { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); cursor: pointer; }
    .form-check input[type="checkbox"] { width: 15px; height: 15px; accent-color: var(--accent); cursor: pointer; }
    .btn-submit {
        width: 100%; padding: 13px; background: var(--accent); color: #fff;
        border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
        cursor: pointer; transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        font-family: 'Syne', sans-serif;
    }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,130,246,0.3); }
    .auth-footer { text-align: center; margin-top: 24px; font-size: 14px; color: var(--muted); }
    .auth-footer a { color: var(--accent); text-decoration: none; font-weight: 500; }
    .auth-footer a:hover { text-decoration: underline; }
    .divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; color: var(--muted); font-size: 12px; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
</style>
@endpush

@section('content')
<div class="auth-wrap">
    <div class="auth-bg"></div>
    <div class="auth-card">
        <a href="{{ route('home') }}" class="auth-logo">
            <div class="auth-logo-icon">RI</div>
            <span class="auth-logo-text">Reset<span>.</span></span>
        </a>
        <h1 class="auth-title">Bienvenido de nuevo</h1>
        <p class="auth-subtitle">Inicia sesión en tu cuenta</p>

        <form action="{{ route('login') }}" method="POST" novalidate>
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                    placeholder="tu_usuario" autocomplete="username" autofocus
                    class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}">
                @error('username')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
              <div style="position:relative">
    <input type="password" name="password" id="password-input"
        placeholder="••••••••" autocomplete="current-password"
        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
        style="padding-right:42px">
    <button type="button" onclick="togglePassword()"
        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px"
        id="toggle-btn">👁</button>
</div>
<script>
function togglePassword() {
    const input = document.getElementById('password-input');
    const btn = document.getElementById('toggle-btn');
    if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '👁';
    } else {
        input.type = 'password';
        btn.textContent = '👁';
    }
}
</script>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-row">
                <label class="form-check">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Recordarme
                </label>
            </div>
            <button type="submit" class="btn-submit">Iniciar sesión</button>
        </form>

        <div class="divider">¿No tienes cuenta?</div>
        <div class="auth-footer">
            <a href="{{ route('register') }}">Crear una cuenta →</a>
        </div>
    </div>
</div>
@endsection