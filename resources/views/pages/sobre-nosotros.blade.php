@extends('layouts.app')

@section('title', 'Sobre nosotros | Reset Informática')

@push('styles')
<style>
    .sobre-wrap { max-width: 1100px; margin: 0 auto; padding: 60px 32px 80px; }

    /* Hero */
    .sobre-hero {
        text-align: center; margin-bottom: 80px; position: relative;
    }
    .sobre-hero::before {
        content: ''; position: absolute; top: -60px; left: 50%; transform: translateX(-50%);
        width: 600px; height: 300px;
        background: radial-gradient(ellipse at center, rgba(59,130,246,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .sobre-badge {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(59,130,246,0.1); border: 1px solid rgba(59,130,246,0.2);
        border-radius: 100px; padding: 6px 16px;
        font-size: 12px; font-weight: 600; color: var(--accent);
        letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 24px;
    }
    .sobre-hero h1 {
        font-family: 'Syne', sans-serif; font-size: clamp(36px, 5vw, 56px);
        font-weight: 800; color: #fff; letter-spacing: -1px; line-height: 1.1;
        margin-bottom: 20px;
    }
    .sobre-hero h1 span {
        background: linear-gradient(120deg, var(--accent), var(--accent2));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
    .sobre-hero p { font-size: 18px; color: var(--muted); line-height: 1.7; max-width: 600px; margin: 0 auto; }

    /* Stats */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 80px; }
    .stat-item { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 24px; text-align: center; }
    .stat-num { font-family: 'Syne', sans-serif; font-size: 36px; font-weight: 800; color: #fff; line-height: 1; }
    .stat-num span { background: linear-gradient(120deg, var(--accent), var(--accent2)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .stat-txt { font-size: 13px; color: var(--muted); margin-top: 8px; }

    /* Historia */
    .seccion { margin-bottom: 72px; }
    .seccion-header { display: flex; align-items: center; gap: 16px; margin-bottom: 32px; }
    .seccion-icon { width: 48px; height: 48px; border-radius: 12px; background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.2); display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
    .seccion-header h2 { font-family: 'Syne', sans-serif; font-size: 26px; font-weight: 800; color: #fff; }
    .seccion-header p { font-size: 13px; color: var(--muted); margin-top: 2px; }
    .seccion-body { font-size: 16px; color: var(--muted); line-height: 1.8; }
    .seccion-body strong { color: var(--text); }

    /* Valores */
    .valores-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    .valor-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 12px; padding: 24px; transition: border-color 0.2s, transform 0.15s; }
    .valor-card:hover { border-color: rgba(59,130,246,0.3); transform: translateY(-2px); }
    .valor-icon { font-size: 28px; margin-bottom: 14px; }
    .valor-titulo { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 8px; }
    .valor-desc { font-size: 14px; color: var(--muted); line-height: 1.6; }

    /* Equipo */
    .equipo-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .miembro-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: 14px; padding: 28px 24px; text-align: center; transition: border-color 0.2s; }
    .miembro-card:hover { border-color: rgba(59,130,246,0.3); }
    .miembro-avatar { width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), var(--accent2)); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-size: 24px; font-weight: 800; color: #fff; margin: 0 auto 16px; }
    .miembro-nombre { font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 4px; }
    .miembro-rol { font-size: 13px; color: var(--accent); font-weight: 600; margin-bottom: 10px; }
    .miembro-bio { font-size: 13px; color: var(--muted); line-height: 1.6; }

    /* CTA */
    .cta-section { background: var(--bg-card); border: 1px solid rgba(59,130,246,0.2); border-radius: 16px; padding: 48px; text-align: center; position: relative; overflow: hidden; }
    .cta-section::before { content: ''; position: absolute; inset: 0; background: radial-gradient(ellipse at 50% 0%, rgba(59,130,246,0.08) 0%, transparent 60%); pointer-events: none; }
    .cta-section h2 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; margin-bottom: 12px; position: relative; }
    .cta-section p { font-size: 16px; color: var(--muted); margin-bottom: 28px; position: relative; }
    .btn-cta { display: inline-flex; align-items: center; gap: 8px; padding: 14px 28px; background: var(--accent); color: #fff; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; transition: opacity 0.2s, transform 0.15s; position: relative; }
    .btn-cta:hover { opacity: 0.9; transform: translateY(-2px); }

    @media (max-width: 900px) {
        .sobre-wrap { padding: 40px 16px 60px; }
        .stats-row { grid-template-columns: repeat(2, 1fr); }
        .valores-grid { grid-template-columns: 1fr 1fr; }
        .equipo-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 480px) {
        .valores-grid { grid-template-columns: 1fr; }
        .stats-row { grid-template-columns: 1fr 1fr; }
    }
</style>
@endpush

@section('content')
<div class="sobre-wrap">

    {{-- Hero --}}
    <div class="sobre-hero">
        <div class="sobre-badge">Nuestra historia</div>
        <h1>Apasionados por la<br><span>tecnología</span></h1>
        <p>Desde Huelva para el mundo. Somos tu tienda de referencia en componentes y configuraciones personalizadas para PC.</p>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-item">
            <div class="stat-num"><span>+2.400</span></div>
            <div class="stat-txt">Productos en catálogo</div>
        </div>
        <div class="stat-item">
            <div class="stat-num"><span>3</span> años</div>
            <div class="stat-txt">Garantía oficial</div>
        </div>
        <div class="stat-item">
            <div class="stat-num"><span>48h</span></div>
            <div class="stat-txt">Envío estándar</div>
        </div>
        <div class="stat-item">
            <div class="stat-num"><span>24/7</span></div>
            <div class="stat-txt">Soporte técnico</div>
        </div>
    </div>

    {{-- Historia --}}
    <div class="seccion">
        <div class="seccion-header">
            <div class="seccion-icon">🏪</div>
            <div>
                <h2>Nuestra historia</h2>
                <p>Cómo empezó todo</p>
            </div>
        </div>
        <div class="seccion-body">
            <p>Reset Informática nació en <strong>2021</strong> con una idea clara: hacer accesible la tecnología de calidad a todo el mundo. Lo que empezó como una pequeña tienda local en Huelva se ha convertido en una referencia online para amantes de la informática y el gaming.</p>
            <br>
            <p>Creemos que <strong>montar tu propio PC</strong> no debería ser complicado ni caro. Por eso desarrollamos nuestro configurador inteligente, que te guía paso a paso eligiendo los componentes más compatibles y con mejor relación calidad-precio para tu presupuesto.</p>
            <br>
            <p>Hoy seguimos creciendo, siempre con el mismo compromiso: <strong>asesoramiento honesto, precios justos y soporte real</strong>.</p>
        </div>
    </div>

    {{-- Valores --}}
    <div class="seccion">
        <div class="seccion-header">
            <div class="seccion-icon">💡</div>
            <div>
                <h2>Nuestros valores</h2>
                <p>Lo que nos define</p>
            </div>
        </div>
        <div class="valores-grid">
            <div class="valor-card">
                <div class="valor-icon">🔍</div>
                <div class="valor-titulo">Transparencia</div>
                <div class="valor-desc">Sin letra pequeña. Los precios que ves son los que pagas, con envío gratuito incluido.</div>
            </div>
            <div class="valor-card">
                <div class="valor-icon">⚡</div>
                <div class="valor-titulo">Rendimiento</div>
                <div class="valor-desc">Solo trabajamos con marcas y productos que hemos probado y en los que confiamos.</div>
            </div>
            <div class="valor-card">
                <div class="valor-icon">🤝</div>
                <div class="valor-titulo">Cercanía</div>
                <div class="valor-desc">Soporte técnico real con personas reales. Estamos aquí para ayudarte, no para venderte.</div>
            </div>
            <div class="valor-card">
                <div class="valor-icon">🔧</div>
                <div class="valor-titulo">Experiencia</div>
                <div class="valor-desc">Años de experiencia en el sector nos permiten asesorarte con conocimiento real del mercado.</div>
            </div>
            <div class="valor-card">
                <div class="valor-icon">🌱</div>
                <div class="valor-titulo">Sostenibilidad</div>
                <div class="valor-desc">Apostamos por packaging reciclable y colaboramos con fabricantes comprometidos con el medio ambiente.</div>
            </div>
            <div class="valor-card">
                <div class="valor-icon">🛡️</div>
                <div class="valor-titulo">Garantía</div>
                <div class="valor-desc">Todos nuestros productos incluyen garantía oficial de 3 años. Tu inversión está protegida.</div>
            </div>
        </div>
    </div>

    {{-- Equipo --}}
    <div class="seccion">
        <div class="seccion-header">
            <div class="seccion-icon">👥</div>
            <div>
                <h2>El equipo</h2>
                <p>Las personas detrás de Reset</p>
            </div>
        </div>
        <div class="equipo-grid">
            <div class="miembro-card">
                <div class="miembro-avatar">JG</div>
                <div class="miembro-nombre">Jonathan García</div>
                <div class="miembro-rol">Fundador & CEO</div>
                <div class="miembro-bio">Apasionado del hardware y el gaming desde los 12 años. Fundó Reset con la misión de democratizar el acceso a la tecnología.</div>
            </div>
            <div class="miembro-card">
                <div class="miembro-avatar">MR</div>
                <div class="miembro-nombre">María Rodríguez</div>
                <div class="miembro-rol">Directora Técnica</div>
                <div class="miembro-bio">Ingeniera informática con 8 años de experiencia. Responsable de seleccionar los mejores productos del catálogo.</div>
            </div>
            <div class="miembro-card">
                <div class="miembro-avatar">AL</div>
                <div class="miembro-nombre">Alejandro López</div>
                <div class="miembro-rol">Soporte & Atención al cliente</div>
                <div class="miembro-bio">El primero en responder cuando necesitas ayuda. Especialista en compatibilidad y configuraciones personalizadas.</div>
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="cta-section">
        <h2>¿Listo para montar tu PC?</h2>
        <p>Usa nuestro configurador inteligente y encuentra los componentes perfectos para ti.</p>
        <a href="{{ route('configurador.plataforma') }}" class="btn-cta">Configurar mi PC →</a>
    </div>

</div>
@endsection