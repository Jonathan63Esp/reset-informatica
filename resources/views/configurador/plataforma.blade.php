@extends('layouts.app')

@section('title', 'Elige tu plataforma | Reset Informática')

@push('styles')
<style>
    .plataforma-wrap {
        min-height: calc(100vh - 68px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 32px;
        position: relative;
        overflow: hidden;
    }

    .plataforma-bg {
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 20% 50%, rgba(239,68,68,0.08) 0%, transparent 60%),
            radial-gradient(ellipse 60% 50% at 80% 50%, rgba(59,130,246,0.08) 0%, transparent 60%),
            var(--bg);
        z-index: 0;
    }

    .plataforma-bg::after {
        content: '';
        position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 60px 60px;
        mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 0%, transparent 80%);
    }

    .plataforma-content {
        position: relative; z-index: 1;
        width: 100%; max-width: 1100px;
        text-align: center;
    }

    .plataforma-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2);
        border-radius: 100px; padding: 6px 16px;
        font-size: 12px; font-weight: 600; color: var(--accent);
        letter-spacing: 0.08em; text-transform: uppercase;
        margin-bottom: 24px;
    }

    .plataforma-titulo {
        font-family: 'Syne', sans-serif;
        font-size: clamp(32px, 5vw, 56px);
        font-weight: 800; line-height: 1.1;
        letter-spacing: -1px; color: #fff;
        margin-bottom: 16px;
    }

    .plataforma-titulo span {
        background: linear-gradient(120deg, var(--accent), var(--accent2));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }

    .plataforma-subtitulo {
        font-size: 17px; color: var(--muted);
        line-height: 1.6; margin-bottom: 56px;
    }

    /* ── Grupos AMD / Intel ── */
    .fabricantes-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 20px;
    }

    .fabricante-grupo {
        background: var(--bg-card);
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 32px 28px;
        text-align: left;
        transition: border-color 0.3s;
    }

    .fabricante-grupo.amd  { --fab-color: #ef4444; --fab-glow: rgba(239,68,68,0.15); }
    .fabricante-grupo.intel { --fab-color: #3b82f6; --fab-glow: rgba(59,130,246,0.15); }

    .fabricante-grupo:hover {
        border-color: var(--fab-color);
    }

    .fabricante-header {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 24px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }

    .fabricante-logo {
        width: 48px; height: 48px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 22px; font-weight: 900;
        font-family: 'Syne', sans-serif;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        color: var(--fab-color);
        flex-shrink: 0;
    }

    .fabricante-info h3 {
        font-family: 'Syne', sans-serif;
        font-size: 20px; font-weight: 800; color: #fff;
    }

    .fabricante-info p {
        font-size: 13px; color: var(--muted); margin-top: 2px;
    }

    /* ── Tarjetas de socket ── */
    .sockets-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .socket-card {
        display: block;
        background: var(--bg);
        border: 1.5px solid var(--border);
        border-radius: 12px;
        padding: 20px 18px;
        text-decoration: none;
        color: var(--text);
        transition: border-color 0.2s, background 0.2s, transform 0.15s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .socket-card::before {
        content: '';
        position: absolute; top: 0; left: 0; right: 0; height: 2px;
        background: var(--fab-color);
        opacity: 0;
        transition: opacity 0.2s;
    }

    .socket-card:hover {
        border-color: var(--fab-color);
        background: var(--bg-hover);
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.3);
    }

    .socket-card:hover::before { opacity: 1; }

    .socket-nombre {
        font-family: 'Syne', sans-serif;
        font-size: 18px; font-weight: 800; color: #fff;
        margin-bottom: 4px;
    }

    .socket-generacion {
        font-size: 12px; color: var(--muted);
        margin-bottom: 12px;
    }

    .socket-chips {
        display: flex; flex-wrap: wrap; gap: 5px;
        margin-bottom: 14px;
    }

    .socket-chip {
        font-size: 11px;
        font-family: 'Space Mono', monospace;
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        border-radius: 4px;
        padding: 2px 7px;
        color: var(--muted);
    }

    .socket-arrow {
        font-size: 12px; color: var(--fab-color);
        font-weight: 600; opacity: 0;
        transform: translateX(-4px);
        transition: opacity 0.2s, transform 0.2s;
        display: flex; align-items: center; gap: 4px;
    }

    .socket-card:hover .socket-arrow {
        opacity: 1;
        transform: translateX(0);
    }

    .socket-badge-nuevo {
        position: absolute; top: 12px; right: 12px;
        background: rgba(34,197,94,0.15);
        border: 1px solid rgba(34,197,94,0.3);
        color: #22c55e;
        font-size: 10px; font-weight: 700;
        padding: 2px 7px; border-radius: 100px;
        letter-spacing: 0.05em;
    }

    /* ── Nota inferior ── */
    .plataforma-nota {
        margin-top: 32px;
        font-size: 13px; color: var(--muted);
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }

    @media (max-width: 900px) {
        .fabricantes-grid { grid-template-columns: 1fr; }
        .plataforma-wrap { padding: 40px 16px; }
    }

    @media (max-width: 480px) {
        .sockets-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="plataforma-wrap">
    <div class="plataforma-bg"></div>

    <div class="plataforma-content">
        <div class="plataforma-badge">Paso previo</div>
        <h1 class="plataforma-titulo">Elige tu <span>plataforma</span></h1>
        <p class="plataforma-subtitulo">
            Selecciona el fabricante y socket para filtrar los componentes compatibles.
        </p>

        <div class="fabricantes-grid">

            {{-- ── AMD ── --}}
            <div class="fabricante-grupo amd">
                <div class="fabricante-header">
                    <div class="fabricante-logo" style="font-size:11px">AMD</div>
                    <div class="fabricante-info">
                        <h3>AMD</h3>
                        <p>Plataformas Ryzen</p>
                    </div>
                </div>
                <div class="sockets-grid">
                    <a href="{{ route('configurador.index', ['plataforma' => 'AM4']) }}" class="socket-card">
                        <div class="socket-nombre">AM4</div>
                        <div class="socket-generacion">Ryzen 1000 — 5000</div>
                        <div class="socket-chips">
                            <span class="socket-chip">DDR4</span>
                            <span class="socket-chip">PCIe 4.0</span>
                            <span class="socket-chip">B550 / X570</span>
                        </div>
                        <div class="socket-arrow">Seleccionar →</div>
                    </a>
                    <a href="{{ route('configurador.index', ['plataforma' => 'AM5']) }}" class="socket-card">
                        <span class="socket-badge-nuevo">NUEVO</span>
                        <div class="socket-nombre">AM5</div>
                        <div class="socket-generacion">Ryzen 7000 — 9000</div>
                        <div class="socket-chips">
                            <span class="socket-chip">DDR5</span>
                            <span class="socket-chip">PCIe 5.0</span>
                            <span class="socket-chip">B650 / X670</span>
                        </div>
                        <div class="socket-arrow">Seleccionar →</div>
                    </a>
                </div>
            </div>

            {{-- ── Intel ── --}}
            <div class="fabricante-grupo intel">
                <div class="fabricante-header">
                    <div class="fabricante-logo" style="font-size:11px">Intel</div>
                    <div class="fabricante-info">
                        <h3>Intel</h3>
                        <p>Plataformas Core</p>
                    </div>
                </div>
                <div class="sockets-grid">
                    <a href="{{ route('configurador.index', ['plataforma' => 'LGA1200']) }}" class="socket-card">
                        <div class="socket-nombre">LGA1200</div>
                        <div class="socket-generacion">Core 10ª — 11ª gen</div>
                        <div class="socket-chips">
                            <span class="socket-chip">DDR4</span>
                            <span class="socket-chip">PCIe 4.0</span>
                            <span class="socket-chip">B460 / Z490</span>
                        </div>
                        <div class="socket-arrow">Seleccionar →</div>
                    </a>
                    <a href="{{ route('configurador.index', ['plataforma' => 'LGA1700']) }}" class="socket-card">
                        <span class="socket-badge-nuevo">NUEVO</span>
                        <div class="socket-nombre">LGA1700</div>
                        <div class="socket-generacion">Core 12ª — 14ª gen</div>
                        <div class="socket-chips">
                            <span class="socket-chip">DDR4 / DDR5</span>
                            <span class="socket-chip">PCIe 5.0</span>
                            <span class="socket-chip">B760 / Z790</span>
                        </div>
                        <div class="socket-arrow">Seleccionar →</div>
                    </a>
                </div>
            </div>

        </div>

        <p class="plataforma-nota">
            💡 Podrás cambiar la plataforma en cualquier momento durante la configuración
        </p>
    </div>
</div>
@endsection