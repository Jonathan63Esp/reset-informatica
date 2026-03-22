@extends('layouts.app')

@section('title', 'Reset Informática — Tu tienda de componentes')

@push('styles')
<style>
    .hero {
        position: relative;
        min-height: 600px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        overflow: hidden;
        padding: 100px 20px;
    }
    .hero-bg {
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 70% 60% at 50% 0%, rgba(59,130,246,0.18) 0%, transparent 65%),
            radial-gradient(ellipse 50% 40% at 80% 80%, rgba(6,182,212,0.10) 0%, transparent 60%),
            var(--bg);
        z-index: 0;
    }
    .hero-bg::after {
        content: '';
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
        background-size: 60px 60px;
        mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 0%, transparent 80%);
    }
    .hero-content { position: relative; z-index: 1; max-width: 720px; }
    .hero-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25);
        border-radius: 100px; padding: 6px 14px;
        font-size: 12px; font-weight: 600; color: var(--accent);
        letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 24px;
    }
    .hero-badge::before {
        content: ''; width: 6px; height: 6px;
        background: var(--accent); border-radius: 50%;
        animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
    .hero h1 {
        font-family: 'Syne', sans-serif;
        font-size: clamp(36px, 6vw, 64px); font-weight: 800;
        line-height: 1.1; letter-spacing: -1.5px; color: #fff; margin-bottom: 20px;
    }
    .hero h1 span {
        background: linear-gradient(120deg, var(--accent), var(--accent2));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .hero p { font-size: 18px; color: var(--muted); line-height: 1.6; margin-bottom: 36px; }
    .hero-actions { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
    .btn-primary {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 26px; background: var(--accent); color: white;
        text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
    }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); box-shadow: 0 8px 30px var(--glow); }
    .btn-outline {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 13px 26px; background: transparent; color: var(--text);
        text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 15px;
        border: 1px solid var(--border); transition: background 0.2s, transform 0.15s;
    }
    .btn-outline:hover { background: var(--bg-hover); transform: translateY(-2px); }

    .stats-bar { background: var(--bg-card); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
    .stats-inner {
        max-width: 1200px; margin: auto; padding: 28px 40px;
        display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; text-align: center;
    }
    .stat-item strong { display: block; font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; }
    .stat-item span { font-size: 13px; color: var(--muted); margin-top: 4px; display: block; }

    .section { max-width: 1200px; margin: auto; padding: 72px 40px; }
    .section-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 40px; }
    .section-header h2 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; letter-spacing: -0.5px; color: #fff; }
    .section-header h2 small { display: block; font-family: 'Inter', sans-serif; font-size: 13px; font-weight: 400; color: var(--muted); letter-spacing: 0.06em; text-transform: uppercase; margin-bottom: 6px; }

    .categories-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
    .category-card {
        background: var(--bg-card); border: 1.5px solid var(--border);
        border-radius: 12px; padding: 28px 24px;
        text-decoration: none; color: var(--text);
        transition: border-color 0.25s, background 0.25s, transform 0.2s, box-shadow 0.25s;
        position: relative; overflow: hidden;
    }
    .category-card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
        opacity: 0; transition: opacity 0.3s;
    }
    .category-card:hover { border-color: rgba(59,130,246,0.3); background: var(--bg-hover); transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,0.3); }
    .category-card:hover::before { opacity: 1; }
    .category-icon { width: 48px; height: 48px; border-radius: 10px; background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2); display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 18px; }
    .category-card h3 { font-family: 'Syne', sans-serif; font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 8px; }
    .category-card p { font-size: 14px; color: var(--muted); line-height: 1.6; }
    .category-arrow { margin-top: 20px; font-size: 13px; color: var(--accent); font-weight: 600; display: flex; align-items: center; gap: 4px; opacity: 0; transform: translateX(-6px); transition: opacity 0.2s, transform 0.2s; }
    .category-card:hover .category-arrow { opacity: 1; transform: translateX(0); }
</style>
@endpush

@section('content')

<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <div class="hero-badge">Nuevo stock disponible</div>
        <h1>Tu tienda de<br><span>informática</span></h1>
        <p>Componentes, ordenadores y configuraciones personalizadas para cada presupuesto.</p>
        <div class="hero-actions">
            <a href="{{ route('configurador.index') }}" class="btn-primary">Configurar mi PC →</a>
            <a href="{{ route('sobre-nosotros') }}" class="btn-outline">Sobre nosotros</a>
        </div>
    </div>
</section>

<div class="stats-bar">
    <div class="stats-inner">
        <div class="stat-item"><strong>+2.400</strong><span>Productos en catálogo</span></div>
        <div class="stat-item"><strong>48h</strong><span>Envío estándar</span></div>
        <div class="stat-item"><strong>3 años</strong><span>Garantía oficial</span></div>
        <div class="stat-item"><strong>24/7</strong><span>Soporte técnico</span></div>
    </div>
</div>

<div class="section">
    <div class="section-header">
        <h2><small>Catálogo</small>Categorías principales</h2>
    </div>
    <div class="categories-grid">
    @foreach(\App\Models\Categoria::orderByRaw("FIELD(nombre,'Procesadores','Placas base','Memoria RAM','Tarjetas gráficas','Fuentes de alimentación','Almacenamiento','Refrigeración','Cajas')")->get() as $cat)
    <a href="{{ route('catalogo.categoria', $cat->slug) }}" class="category-card">
        <div class="category-icon">
            @switch($cat->nombre)
                @case('Procesadores') 🔬 @break
                @case('Placas base') 🔧 @break
                @case('Memoria RAM') 💾 @break
                @case('Tarjetas gráficas') 🎮 @break
                @case('Fuentes de alimentación') ⚡ @break
                @case('Almacenamiento') 💿 @break
                @case('Refrigeración') ❄️ @break
                @case('Cajas') 🖥️ @break
                @default 📦
            @endswitch
        </div>
        <h3>{{ $cat->nombre }}</h3>
        <p>{{ $cat->productos()->count() }} productos disponibles</p>
        <div class="category-arrow">Ver productos →</div>
    </a>
    @endforeach
</div>
</div>

@endsection