<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reset Informática')</title>
    <meta name="description" content="@yield('meta_description', 'Tu tienda de informática. Componentes, ordenadores y configuraciones personalizadas.')">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Space+Mono:wght@400;700&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

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
            --glow:     rgba(59,130,246,0.25);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(10,12,16,0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            padding: 0 40px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .logo-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, var(--accent), var(--accent2));
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-family: 'Space Mono', monospace;
            font-size: 14px; font-weight: 700; color: white;
        }
        .logo-text { font-family: 'Syne', sans-serif; font-size: 20px; font-weight: 800; color: var(--text); }
        .logo-text span { color: var(--accent); }

        nav { display: flex; align-items: center; gap: 4px; }
        nav a {
            color: var(--muted); text-decoration: none;
            font-size: 14px; font-weight: 500;
            padding: 7px 14px; border-radius: 6px;
            transition: color 0.2s, background 0.2s;
        }
        nav a:hover, nav a.active { color: var(--text); background: var(--bg-hover); }
        nav a.active { color: var(--accent); }

        .nav-cta {
            margin-left: 8px; padding: 8px 18px !important;
            background: var(--accent) !important; color: white !important;
            border-radius: 6px; font-weight: 600 !important;
            transition: opacity 0.2s !important;
        }
        .nav-cta:hover { opacity: 0.88; background: var(--accent) !important; }

        .menu-toggle {
            display: none; flex-direction: column; gap: 5px;
            cursor: pointer; padding: 6px; border: none; background: none;
        }
        .menu-toggle span { display: block; width: 22px; height: 2px; background: var(--text); border-radius: 2px; }

        main { flex: 1; }

        .flash-container {
            position: fixed; top: 80px; right: 20px;
            z-index: 200; display: flex; flex-direction: column; gap: 10px;
        }
        .flash {
            padding: 14px 20px; border-radius: 8px;
            font-size: 14px; font-weight: 500; border: 1px solid;
            animation: slideIn 0.3s ease; max-width: 360px;
        }
        .flash-success { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: #10b981; }
        .flash-error   { background: rgba(239,68,68,0.1);  border-color: rgba(239,68,68,0.3);  color: #ef4444; }
        .flash-warning { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3); color: #f59e0b; }
        .flash-info    { background: rgba(59,130,246,0.1); border-color: rgba(59,130,246,0.3); color: #3b82f6; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border);
            margin-top: auto;
        }
        .footer-inner {
            max-width: 1200px; margin: auto; padding: 48px 40px 28px;
            display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr; gap: 40px;
        }
        .footer-brand p { color: var(--muted); font-size: 14px; line-height: 1.7; margin-top: 12px; max-width: 260px; }
        .footer-col h4 {
            font-family: 'Syne', sans-serif; font-size: 13px; font-weight: 700;
            letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); margin-bottom: 16px;
        }
        .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-col ul a { color: var(--text); text-decoration: none; font-size: 14px; opacity: 0.7; transition: opacity 0.2s, color 0.2s; }
        .footer-col ul a:hover { opacity: 1; color: var(--accent); }
        .footer-bottom {
            max-width: 1200px; margin: auto; padding: 20px 40px;
            border-top: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
            font-size: 13px; color: var(--muted);
        }

        @media (max-width: 900px) {
            header { padding: 0 20px; }
            .menu-toggle { display: flex; }
            nav {
                display: none; position: absolute; top: 68px; left: 0; right: 0;
                background: rgba(10,12,16,0.97); border-bottom: 1px solid var(--border);
                flex-direction: column; padding: 16px 20px; gap: 4px;
            }
            nav.open { display: flex; }
            nav a { width: 100%; padding: 10px 14px; }
            .nav-cta { margin-left: 0; }
            .footer-inner { grid-template-columns: 1fr 1fr; }
            .footer-brand { grid-column: span 2; }
        }
    </style>

    @stack('styles')
</head>
<body>


<header>
    <div style="display:flex;align-items:center;gap:12px;flex-shrink:0">
        <a href="{{ route('home') }}" class="logo">
            <div class="logo-icon">RI</div>
            <span class="logo-text">Reset<span>.</span></span>
        </a>

        <div style="position:relative" id="menu-categorias-wrap">
            <button onclick="toggleMenuCat()" id="btn-categorias"
                style="display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:6px;background:transparent;border:none;color:var(--muted);font-size:14px;font-weight:500;cursor:pointer;transition:color 0.2s,background 0.2s;font-family:'Inter',sans-serif">
                ☰ Categorías
                <span id="arrow-cat" style="font-size:10px;transition:transform 0.2s">▼</span>
            </button>
            <div id="dropdown-categorias"
                style="display:none;position:absolute;top:calc(100% + 8px);left:0;width:240px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow:0 16px 40px rgba(0,0,0,0.4);z-index:200">
                @php
                $iconosCat = [
                    'Procesadores' => '🔬',
                    'Placas base' => '🔧',
                    'Memoria RAM' => '💾',
                    'Tarjetas gráficas' => '🎮',
                    'Fuentes de alimentación' => '⚡',
                    'Almacenamiento' => '💿',
                    'Refrigeración' => '❄️',
                    'Cajas' => '🖥️',
                ];
                $todasCats = \App\Models\Categoria::orderByRaw("FIELD(nombre,'Procesadores','Placas base','Memoria RAM','Tarjetas gráficas','Fuentes de alimentación','Almacenamiento','Refrigeración','Cajas')")->get();
                @endphp
                @foreach($todasCats as $cat)
                <a href="{{ route('catalogo.categoria', $cat->slug) }}"
                    style="display:flex;align-items:center;gap:12px;padding:11px 16px;color:var(--text);text-decoration:none;font-size:14px;border-bottom:1px solid var(--border);transition:background 0.15s"
                    onmouseover="this.style.background='var(--bg-hover)'"
                    onmouseout="this.style.background='transparent'">
                    <span style="font-size:18px;width:24px;text-align:center">{{ $iconosCat[$cat->nombre] ?? '📦' }}</span>
                    {{ $cat->nombre }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Barra búsqueda central --}}
    <form action="{{ route('buscar') }}" method="GET"
        style="flex:1;max-width:560px;margin:0 24px">
        <div style="display:flex;align-items:center;background:var(--bg-card);border:1px solid var(--border);border-radius:8px;overflow:hidden;transition:border-color 0.2s"
             onfocusin="this.style.borderColor='rgba(59,130,246,0.5)'"
             onfocusout="this.style.borderColor='var(--border)'">
            <input type="text" name="q"
                value="{{ request('q') }}"
                placeholder="Buscar Productos"
                style="flex:1;background:transparent;border:none;padding:10px 16px;color:var(--text);font-size:14px;font-family:'Inter',sans-serif;outline:none;"
                autocomplete="off">
            <button type="submit"
                style="padding:10px 16px;background:var(--accent);color:#fff;border:none;font-size:14px;cursor:pointer;transition:opacity 0.2s;flex-shrink:0"
                onmouseover="this.style.opacity='0.88'"
                onmouseout="this.style.opacity='1'">
                🔍
            </button>
        </div>
    </form>

    <button class="menu-toggle" aria-label="Abrir menú" onclick="this.nextElementSibling.classList.toggle('open')">
        <span></span><span></span><span></span>
    </button>

  <nav>
    <a href="{{ route('configurador.plataforma') }}" class="{{ request()->routeIs('configurador.*') ? 'active' : '' }}">Configurador</a>

    @if(Auth::check() && Auth::user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}" style="color:#ef4444;">
            ⚙ Admin
        </a>
    @endif

    @auth
        <a href="{{ route('carrito.index') }}" class="{{ request()->routeIs('carrito.*') ? 'active' : '' }}" style="position:relative">
            🛒 Carrito
            @php $totalItems = auth()->user()->carritoItems()->sum('cantidad'); @endphp
            @if($totalItems > 0)
                <span style="position:absolute;top:-6px;right:-8px;background:var(--accent);color:#fff;font-size:10px;font-weight:700;width:17px;height:17px;border-radius:50%;display:flex;align-items:center;justify-content:center;">{{ $totalItems > 9 ? '9+' : $totalItems }}</span>
            @endif
        </a>

        {{-- Menú usuario --}}
        <div style="position:relative" id="user-menu-wrap">
            <button onclick="toggleUserMenu()"
                style="display:flex;align-items:center;gap:6px;padding:7px 12px;border-radius:6px;background:transparent;border:none;color:var(--muted);font-size:13px;font-weight:500;cursor:pointer;font-family:'Inter',sans-serif">
                👤 {{ Auth::user()->username }}
                <span style="font-size:10px;color:var(--muted)">▼</span>
            </button>
            <div id="user-menu-dropdown"
                style="display:none;position:absolute;top:calc(100% + 8px);right:0;width:200px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;overflow:hidden;box-shadow:0 16px 40px rgba(0,0,0,0.4);z-index:200">
                <a href="{{ route('perfil') }}"
    style="display:flex;align-items:center;gap:10px;padding:11px 16px;color:var(--text);text-decoration:none;font-size:14px;border-bottom:1px solid var(--border);transition:background 0.15s"
    onmouseover="this.style.background='var(--bg-hover)'"
    onmouseout="this.style.background='transparent'">
    👤 Mi perfil
</a>
                <a href="{{ route('pedidos.historial') }}"
                    style="display:flex;align-items:center;gap:10px;padding:11px 16px;color:var(--text);text-decoration:none;font-size:14px;border-bottom:1px solid var(--border);transition:background 0.15s"
                    onmouseover="this.style.background='var(--bg-hover)'"
                    onmouseout="this.style.background='transparent'">
                    📦 Mis pedidos
                </a>
                <a href="{{ route('sobre-nosotros') }}"
                    style="display:flex;align-items:center;gap:10px;padding:11px 16px;color:var(--text);text-decoration:none;font-size:14px;border-bottom:1px solid var(--border);transition:background 0.15s"
                    onmouseover="this.style.background='var(--bg-hover)'"
                    onmouseout="this.style.background='transparent'">
                    ℹ️ Sobre nosotros
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        style="display:flex;align-items:center;gap:10px;padding:11px 16px;color:#ef4444;background:transparent;border:none;font-size:14px;cursor:pointer;width:100%;font-family:'Inter',sans-serif;transition:background 0.15s"
                        onmouseover="this.style.background='rgba(239,68,68,0.08)'"
                        onmouseout="this.style.background='transparent'">
                        🚪 Cerrar sesión
                    </button>
                </form>
            </div>
        </div>
    @else
        <a href="{{ route('sobre-nosotros') }}" class="{{ request()->routeIs('sobre-nosotros') ? 'active' : '' }}">Sobre nosotros</a>
        <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? 'active' : '' }}">Entrar</a>
        <a href="{{ route('register') }}" class="nav-cta">Crear cuenta</a>
    @endauth
</nav>
</header>


@if(session()->hasAny(['success','error','warning','info']))
<div class="flash-container">
    @foreach(['success','error','warning','info'] as $type)
        @if(session($type))
        <div class="flash flash-{{ $type }}">{{ session($type) }}</div>
        @endif
    @endforeach
</div>
@endif

<main>
    @yield('content')
</main>

<footer>
    <div class="footer-inner">
        <div class="footer-brand">
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">RI</div>
                <span class="logo-text">Reset<span>.</span></span>
            </a>
            <p>Tu tienda de referencia en componentes y ordenadores personalizados.</p>
        </div>
        <div class="footer-col">
            <h4>Tienda</h4>
            <ul>
                <li><a href="{{ route('configurador.plataforma') }}">Configurador PC</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Empresa</h4>
            <ul>
                <li><a href="{{ route('sobre-nosotros') }}">Sobre nosotros</a></li>
                <li><a href="#">Contacto</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Legal</h4>
            <ul>
                <li><a href="#">Aviso legal</a></li>
                <li><a href="#">Política de privacidad</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© {{ date('Y') }} Reset Informática</span>
        <span>Hecho con ❤️ en España</span>
    </div>
</footer>

<script>
    document.querySelectorAll('.flash').forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.4s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 400);
        }, 4000);
    });
</script>

@stack('scripts')
<script>
function toggleMenuCat() {
    const dropdown = document.getElementById('dropdown-categorias');
    const arrow    = document.getElementById('arrow-cat');
    const visible  = dropdown.style.display === 'block';
    dropdown.style.display = visible ? 'none' : 'block';
    arrow.style.transform  = visible ? 'rotate(0deg)' : 'rotate(180deg)';
}

document.addEventListener('click', function(e) {
    const wrap = document.getElementById('menu-categorias-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('dropdown-categorias').style.display = 'none';
        document.getElementById('arrow-cat').style.transform = 'rotate(0deg)';
    }
});


</script>

<script>
function toggleUserMenu() {
    const dropdown = document.getElementById('user-menu-dropdown');
    dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(e) {
    const wrap = document.getElementById('user-menu-wrap');
    if (wrap && !wrap.contains(e.target)) {
        const dropdown = document.getElementById('user-menu-dropdown');
        if (dropdown) dropdown.style.display = 'none';
    }
});
</script>
</body>
</html>