@extends('layouts.app')

@section('title', 'Crear cuenta | Reset Informática')

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
        position: relative; z-index: 1; width: 100%; max-width: 420px;
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
    .form-hint { font-size: 11px; color: var(--muted); margin-top: 4px; display: block; }
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
    .password-strength { display: flex; gap: 4px; margin-top: 8px; }
    .strength-bar { height: 3px; flex: 1; border-radius: 2px; background: var(--border); transition: background 0.3s; }
    .strength-bar.active-weak   { background: #ef4444; }
    .strength-bar.active-medium { background: #f59e0b; }
    .strength-bar.active-strong { background: #22c55e; }
    .strength-label { font-size: 11px; color: var(--muted); margin-top: 4px; display: block; text-align: right; }
    .btn-submit {
        width: 100%; padding: 13px; background: var(--accent); color: #fff;
        border: none; border-radius: 8px; font-size: 15px; font-weight: 600;
        cursor: pointer; margin-top: 8px;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        font-family: 'Syne', sans-serif;
    }
    .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,130,246,0.3); }
    .divider { display: flex; align-items: center; gap: 12px; margin: 24px 0; color: var(--muted); font-size: 12px; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .auth-footer { text-align: center; font-size: 14px; color: var(--muted); }
    .auth-footer a { color: var(--accent); text-decoration: none; font-weight: 500; }
    .auth-footer a:hover { text-decoration: underline; }
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
        <h1 class="auth-title">Crea tu cuenta</h1>
        <p class="auth-subtitle">Únete a Reset Informática</p>

        <form action="{{ route('register') }}" method="POST" novalidate>
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">Nombre de usuario</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                    placeholder="tu_usuario" autocomplete="username" autofocus
                    class="form-input {{ $errors->has('username') ? 'is-invalid' : '' }}">
                <span class="form-hint">Solo letras, números y guiones bajos. Mínimo 3 caracteres.</span>
                @error('username')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <div style="position:relative">
    <input type="password" id="password" name="password"
        placeholder="••••••••" autocomplete="new-password"
        class="form-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
        style="padding-right:42px">
    <button type="button" onclick="togglePassword1()"
        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px"
        id="toggle-btn1">👁</button>
</div>
                <div class="password-strength">
                    <div class="strength-bar" id="bar1"></div>
                    <div class="strength-bar" id="bar2"></div>
                    <div class="strength-bar" id="bar3"></div>
                </div>
                <span class="strength-label" id="strength-label"></span>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <div style="position:relative">
    <input type="password" id="password_confirmation" name="password_confirmation"
        placeholder="••••••••" autocomplete="new-password" class="form-input"
        style="padding-right:42px">
    <button type="button" onclick="togglePassword2()"
        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--muted);font-size:16px"
        id="toggle-btn2">👁</button>
</div>
            </div>
            <button type="submit" class="btn-submit">Crear cuenta</button>
        </form>

        <div class="divider">¿Ya tienes cuenta?</div>
        <div class="auth-footer">
            <a href="{{ route('login') }}">← Iniciar sesión</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('password');
    const bars  = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3')];
    const label = document.getElementById('strength-label');

    input.addEventListener('input', () => {
        const v = input.value;
        bars.forEach(b => b.className = 'strength-bar');
        label.textContent = '';
        if (!v) return;

        let lvl = 0;
        if (v.length >= 8) lvl = 1;
        if (v.length >= 10 && /[0-9]/.test(v)) lvl = 2;
        if (v.length >= 12 && /[^a-zA-Z0-9]/.test(v)) lvl = 3;

        const clases = ['active-weak', 'active-medium', 'active-strong'];
        const textos = ['Débil', 'Media', 'Fuerte'];
        const colores = ['#ef4444', '#f59e0b', '#22c55e'];

        if (lvl > 0) {
            for (let i = 0; i < lvl; i++) bars[i].classList.add(clases[lvl - 1]);
            label.textContent = textos[lvl - 1];
            label.style.color = colores[lvl - 1];
        }
    });
</script>

<script>
function togglePassword1() {
    const input = document.getElementById('password');
    const btn = document.getElementById('toggle-btn1');
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '👁';
}
function togglePassword2() {
    const input = document.getElementById('password_confirmation');
    const btn = document.getElementById('toggle-btn2');
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '👁';
}
</script>
@endpush