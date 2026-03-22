<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Panel Admin') | Reset Informática</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Space+Mono:wght@400;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --bg:       #0a0c10;
            --bg-card:  #111520;
            --bg-hover: #161b28;
            --border:   rgba(255,255,255,0.06);
            --accent:   #3b82f6;
            --accent2:  #06b6d4;
            --text:     #e8eaf0;
            --muted:    #6b7280;
            --sidebar:  #0d1117;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; }

        /* Sidebar */
        .admin-sidebar {
            width: 240px; min-height: 100vh; background: var(--sidebar);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0;
            z-index: 50;
        }

        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
            padding: 20px 20px 16px; border-bottom: 1px solid var(--border);
            text-decoration: none;
        }
        .sidebar-logo-icon {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 7px; display: flex; align-items: center; justify-content: center;
            font-family: 'Space Mono', monospace; font-size: 11px; font-weight: 700; color: #fff;
        }
        .sidebar-logo-text { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 800; color: var(--text); }
        .sidebar-logo-text span { color: var(--accent); }
        .sidebar-admin-badge { font-size: 10px; font-weight: 700; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #ef4444; padding: 2px 6px; border-radius: 4px; margin-left: auto; }

        .sidebar-nav { flex: 1; padding: 16px 12px; display: flex; flex-direction: column; gap: 2px; overflow-y: auto; }
        .sidebar-section { font-size: 10px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); padding: 12px 8px 6px; }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 8px;
            color: var(--muted); text-decoration: none; font-size: 14px;
            transition: background 0.15s, color 0.15s;
        }
        .sidebar-link:hover { background: var(--bg-hover); color: var(--text); }
        .sidebar-link.active { background: rgba(59,130,246,0.1); color: #fff; font-weight: 600; }
        .sidebar-link .icon { width: 18px; text-align: center; font-size: 15px; }
        .sidebar-badge { margin-left: auto; background: var(--accent); color: #fff; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 100px; }
        .sidebar-badge.danger { background: #ef4444; }

        .sidebar-footer { padding: 16px 12px; border-top: 1px solid var(--border); }
        .sidebar-user { display: flex; align-items: center; gap: 10px; padding: 8px; border-radius: 8px; }
        .sidebar-user-avatar { width: 32px; height: 32px; border-radius: 8px; background: rgba(59,130,246,0.2); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: var(--accent); flex-shrink: 0; }
        .sidebar-user-name { font-size: 13px; font-weight: 600; color: var(--text); }
        .sidebar-user-role { font-size: 11px; color: var(--muted); }
        .sidebar-link-sm { display: flex; align-items: center; gap: 8px; padding: 7px 8px; border-radius: 6px; color: var(--muted); text-decoration: none; font-size: 13px; transition: color 0.15s; margin-top: 4px; }
        .sidebar-link-sm:hover { color: var(--text); }

        /* Main content */
        .admin-main { margin-left: 240px; flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

        .admin-topbar {
            height: 60px; background: rgba(10,12,16,0.8); backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 32px; position: sticky; top: 0; z-index: 40;
        }
        .topbar-title { font-family: 'Syne', sans-serif; font-size: 18px; font-weight: 700; color: #fff; }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .topbar-link { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 7px; color: var(--muted); font-size: 13px; text-decoration: none; transition: color 0.15s; }
        .topbar-link:hover { color: var(--text); }

        .admin-content { padding: 32px; flex: 1; }

        /* Flash */
        .flash-container { position: fixed; top: 70px; right: 20px; z-index: 200; display: flex; flex-direction: column; gap: 10px; }
        .flash { padding: 12px 18px; border-radius: 8px; font-size: 14px; font-weight: 500; border: 1px solid; animation: slideIn 0.3s ease; max-width: 360px; }
        .flash-success { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: #10b981; }
        .flash-error   { background: rgba(239,68,68,0.1);  border-color: rgba(239,68,68,0.3);  color: #ef4444; }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

        @stack('styles')
    </style>

    @stack('head_styles')
</head>
<body>

<aside class="admin-sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <div class="sidebar-logo-icon">RI</div>
        <span class="sidebar-logo-text">Reset<span>.</span></span>
        <span class="sidebar-admin-badge">ADMIN</span>
    </a>

    <nav class="sidebar-nav">
        <div class="sidebar-section">General</div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="icon">📊</span> Dashboard
        </a>

        <div class="sidebar-section">Tienda</div>
        <a href="{{ route('admin.productos.index') }}" class="sidebar-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
            <span class="icon">📦</span> Productos
        </a>
        <a href="{{ route('admin.categorias.index') }}" class="sidebar-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}">
            <span class="icon">🏷️</span> Categorías
        </a>

        <div class="sidebar-section">Ventas</div>
        <a href="{{ route('admin.pedidos.index') }}" class="sidebar-link {{ request()->routeIs('admin.pedidos.*') ? 'active' : '' }}">
            <span class="icon">🛒</span> Pedidos
            @php $pendientes = \App\Models\Pedido::where('estado','pendiente')->count(); @endphp
            @if($pendientes > 0)
                <span class="sidebar-badge danger">{{ $pendientes }}</span>
            @endif
        </a>

        <div class="sidebar-section">Usuarios</div>
        <a href="{{ route('admin.usuarios.index') }}" class="sidebar-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
            <span class="icon">👥</span> Usuarios
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ strtoupper(substr(Auth::user()->username, 0, 1)) }}</div>
            <div>
                <div class="sidebar-user-name">{{ Auth::user()->username }}</div>
                <div class="sidebar-user-role">Administrador</div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="sidebar-link-sm">🏠 Ver tienda</a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link-sm" style="background:none;border:none;cursor:pointer;width:100%;text-align:left">
                🚪 Cerrar sesión
            </button>
        </form>
    </div>
</aside>

<div class="admin-main">
    <div class="admin-topbar">
        <div class="topbar-title">@yield('topbar_title', 'Panel Admin')</div>
        <div class="topbar-actions">@yield('topbar_actions')</div>
    </div>

    @if(session()->hasAny(['success','error']))
    <div class="flash-container">
        @foreach(['success','error'] as $type)
            @if(session($type))
            <div class="flash flash-{{ $type }}">{{ session($type) }}</div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="admin-content">
        @yield('content')
    </div>
</div>

<script>
document.querySelectorAll('.flash').forEach(el => {
    setTimeout(() => { el.style.opacity = '0'; setTimeout(() => el.remove(), 400); }, 4000);
});
</script>

@stack('scripts')
</body>
</html>