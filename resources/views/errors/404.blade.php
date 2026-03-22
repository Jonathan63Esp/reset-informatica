@extends('layouts.app')

@section('title', 'Página no encontrada | Reset Informática')

@push('styles')
<style>
    .error-wrap {
        min-height: calc(100vh - 68px);
        display: flex; align-items: center; justify-content: center;
        padding: 60px 32px; position: relative; overflow: hidden;
    }

    .error-bg {
        position: absolute; inset: 0;
        background:
            radial-gradient(ellipse 60% 50% at 50% 0%, rgba(59,130,246,0.1) 0%, transparent 60%),
            var(--bg);
        z-index: 0;
    }

    .error-bg::after {
        content: ''; position: absolute; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
        background-size: 60px 60px;
        mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 0%, transparent 80%);
    }

    .error-content { position: relative; z-index: 1; text-align: center; max-width: 560px; }

    .error-code {
        font-family: 'Syne', sans-serif;
        font-size: clamp(80px, 15vw, 140px);
        font-weight: 800; line-height: 1;
        background: linear-gradient(120deg, rgba(59,130,246,0.4), rgba(6,182,212,0.4));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        margin-bottom: 8px; letter-spacing: -4px;
        filter: blur(0px);
    }

    .error-titulo {
        font-family: 'Syne', sans-serif;
        font-size: clamp(22px, 4vw, 32px);
        font-weight: 800; color: #fff;
        margin-bottom: 16px; letter-spacing: -0.5px;
    }

    .error-desc { font-size: 16px; color: var(--muted); line-height: 1.7; margin-bottom: 36px; }

    .error-acciones { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

    .btn-primary { display: inline-flex; align-items: center; gap: 8px; padding: 13px 24px; background: var(--accent); color: #fff; border-radius: 8px; font-size: 15px; font-weight: 600; text-decoration: none; transition: opacity 0.2s, transform 0.15s; }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-2px); }

    .btn-ghost { display: inline-flex; align-items: center; gap: 8px; padding: 13px 24px; background: transparent; color: var(--text); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; text-decoration: none; transition: background 0.15s; }
    .btn-ghost:hover { background: var(--bg-hover); }

    .error-sugerencias { margin-top: 48px; padding-top: 32px; border-top: 1px solid var(--border); }
    .error-sugerencias p { font-size: 13px; color: var(--muted); margin-bottom: 16px; }
    .sugerencias-links { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }
    .sugerencia-link { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; background: var(--bg-card); border: 1px solid var(--border); border-radius: 8px; color: var(--text); text-decoration: none; font-size: 13px; transition: border-color 0.15s, color 0.15s; }
    .sugerencia-link:hover { border-color: rgba(59,130,246,0.3); color: var(--accent); }

    @media (max-width: 480px) {
        .error-acciones { flex-direction: column; align-items: center; }
    }
</style>
@endpush

@section('content')
<div class="error-wrap">
    <div class="error-bg"></div>
    <div class="error-content">

        <div class="error-code">404</div>
        <h1 class="error-titulo">Página no encontrada</h1>
        <p class="error-desc">
            Parece que la página que buscas no existe o ha sido movida.<br>
            No te preocupes, aquí tienes algunas opciones.
        </p>

        <div class="error-acciones">
            <a href="{{ route('home') }}" class="btn-primary">← Volver al inicio</a>
            <a href="{{ route('configurador.plataforma') }}" class="btn-ghost">⚙ Configurar PC</a>
        </div>

        <div class="error-sugerencias">
            <p>O explora nuestras categorías:</p>
            <div class="sugerencias-links">
                @php
                $cats = \App\Models\Categoria::orderByRaw("FIELD(nombre,'Procesadores','Placas base','Tarjetas gráficas','Memoria RAM')")->limit(4)->get();
                @endphp
                @foreach($cats as $cat)
                <a href="{{ route('catalogo.categoria', $cat->slug) }}" class="sugerencia-link">
                    {{ $cat->nombre }}
                </a>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection