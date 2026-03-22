@extends('layouts.app')

@section('title', "Configurador — Paso {$paso} de {$totalPasos} | Reset Informática")

@push('styles')
<style>
    .wizard-wrap {
        max-width: 1280px; margin: 0 auto; padding: 40px 32px 80px;
        display: grid; grid-template-columns: 260px 1fr; gap: 36px; align-items: start;
    }
    .steps-sidebar {
        position: sticky; top: 84px;
        background: var(--bg-card); border: 1px solid var(--border);
        border-radius: 14px; padding: 24px 20px;
    }
    .steps-title { font-family: 'Syne', sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); margin-bottom: 20px; }
    .step-list { list-style: none; display: flex; flex-direction: column; gap: 4px; }
    .step-item { display: flex; align-items: center; border-radius: 8px; font-size: 14px; color: var(--muted); transition: background 0.15s; }
    .step-item.done   { color: #22c55e; }
    .step-item.active { color: #fff; background: rgba(59,130,246,0.1); font-weight: 600; }
    .step-item:hover  { background: var(--bg-hover); }
    .step-num { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; flex-shrink: 0; background: #374151; color: var(--muted); transition: background 0.2s, color 0.2s; }
    .step-item.done  .step-num { background: #22c55e; color: #fff; }
    .step-item.active .step-num { background: #3b82f6; color: #fff; }
    .sidebar-total { margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border); }
    .sidebar-total span { font-size: 12px; color: var(--muted); display: block; margin-bottom: 4px; }
    .sidebar-total strong { font-family: 'Inter', sans-serif; font-size: 26px; font-weight: 800; color: #fff; }
    .sidebar-total strong em { font-family: 'Inter', sans-serif; font-style: normal; font-size: 14px; color: var(--muted); font-weight: 400; }

    .wizard-header { margin-bottom: 28px; }
    .wizard-header small { font-size: 12px; font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; color: var(--accent); display: block; margin-bottom: 6px; }
    .wizard-header h1 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; color: #fff; letter-spacing: -0.5px; }

    .productos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }

    .producto-card {
        background: var(--bg-card); border: 1.5px solid var(--border);
        border-radius: 12px; overflow: hidden; display: flex; flex-direction: column;
        transition: border-color 0.2s, transform 0.15s, box-shadow 0.2s;
        position: relative; cursor: pointer;
    }
    .producto-card:hover { border-color: rgba(59,130,246,0.35); transform: translateY(-2px); box-shadow: 0 8px 30px rgba(0,0,0,0.3); }
    .producto-card.incompatible { opacity: 0.45; filter: grayscale(0.4); }
    .producto-card.seleccionado { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.2); }

    .badge { position: absolute; top: 10px; right: 10px; font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 100px; border: 1px solid; z-index: 2; }
    .badge-incompatible { background: rgba(239,68,68,0.15); border-color: rgba(239,68,68,0.3); color: #ef4444; }
    .badge-seleccionado { background: rgba(59,130,246,0.15); border-color: rgba(59,130,246,0.35); color: #3b82f6; }
    .badge-sinstock { position: absolute; top: 10px; left: 10px; background: rgba(107,114,128,0.2); border: 1px solid rgba(107,114,128,0.3); color: var(--muted); font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 100px; z-index: 2; }

    .producto-img { aspect-ratio: 16/9; background: #0d1117; display: flex; align-items: center; justify-content: center; overflow: hidden; }
    .producto-img img { width: 100%; height: 100%; object-fit: contain; padding: 12px; }
    .producto-img .placeholder-icon { font-size: 48px; opacity: 0.15; }
    .producto-body { padding: 16px; flex: 1; display: flex; flex-direction: column; gap: 8px; }
    .producto-nombre { font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; color: #fff; line-height: 1.3; }
    .producto-atributos { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 2px; }
    .attr-tag { background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 4px; padding: 2px 7px; font-size: 11px; color: var(--muted); font-family: 'Space Mono', monospace; }
    .razones-incompatibilidad { font-size: 12px; color: #ef4444; line-height: 1.5; margin-top: 4px; }
    .producto-footer { padding: 12px 16px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; gap: 12px; }
    .producto-precio { font-family: 'Inter', sans-serif; font-size: 20px; font-weight: 800; color: #fff; }
    .producto-precio span { font-size: 13px; font-weight: 400; color: var(--muted); }
    .btn-seleccionar { padding: 8px 16px; background: #3b82f6; color: #fff; border: none; border-radius: 7px; font-size: 13px; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.15s; }
    .btn-seleccionar:hover { opacity: 0.88; transform: scale(1.02); }
    .btn-seleccionar.ya-seleccionado { background: #22c55e; }
    .card-hint { font-size: 11px; color: var(--muted); padding: 0 16px 10px; opacity: 0; transition: opacity 0.2s; display: flex; align-items: center; gap: 4px; }
    .producto-card:hover .card-hint { opacity: 1; }

    .wizard-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border); }
    .btn-ghost { padding: 10px 20px; background: transparent; color: var(--muted); border: 1px solid var(--border); border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; text-decoration: none; transition: color 0.2s, border-color 0.2s; }
    .btn-ghost:hover { color: var(--text); border-color: rgba(255,255,255,0.15); }
    .btn-omitir { padding: 10px 20px; background: transparent; color: var(--muted); border: 1px dashed rgba(255,255,255,0.12); border-radius: 8px; font-size: 13px; cursor: pointer; transition: color 0.2s; }
    .btn-omitir:hover { color: var(--text); }

    /* ── Drawer ── */
    .drawer-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px); z-index: 100;
        opacity: 0; pointer-events: none;
        transition: opacity 0.3s;
    }
    .drawer-overlay.visible { opacity: 1; pointer-events: all; }

    .drawer {
        position: fixed; top: 0; right: 0; bottom: 0;
        width: 480px; max-width: 95vw;
        background: var(--bg-card);
        border-left: 1px solid var(--border);
        z-index: 101; display: flex; flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .drawer.visible { transform: translateX(0); }

    .drawer-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 20px 24px; border-bottom: 1px solid var(--border);
        flex-shrink: 0;
    }
    .drawer-categoria { font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--accent); }
    .drawer-close {
        width: 32px; height: 32px; border-radius: 8px;
        background: rgba(255,255,255,0.06); border: 1px solid var(--border);
        color: var(--muted); font-size: 16px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        transition: background 0.15s, color 0.15s;
    }
    .drawer-close:hover { background: rgba(255,255,255,0.12); color: #fff; }

    .drawer-body { flex: 1; overflow-y: auto; padding: 0; }
    .drawer-body::-webkit-scrollbar { width: 4px; }
    .drawer-body::-webkit-scrollbar-track { background: transparent; }
    .drawer-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

    .drawer-img {
        width: 100%; aspect-ratio: 16/9;
        background: #0d1117;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
    }
    .drawer-img img { width: 100%; height: 100%; object-fit: contain; padding: 24px; }
    .drawer-img .ph { font-size: 80px; opacity: 0.08; }

    .drawer-info { padding: 24px; }
    .drawer-nombre { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; color: #fff; line-height: 1.2; margin-bottom: 12px; }

    .drawer-badges { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 16px; }
    .drawer-badge-sel { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.3); border-radius: 100px; font-size: 12px; color: #3b82f6; font-weight: 600; }
    .drawer-badge-incompat { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); border-radius: 100px; font-size: 12px; color: #ef4444; font-weight: 600; }
    .drawer-badge-stock { display: inline-flex; align-items: center; gap: 5px; padding: 4px 10px; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); border-radius: 100px; font-size: 12px; color: #22c55e; font-weight: 600; }

    .drawer-seccion-titulo { font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--muted); margin-bottom: 12px; margin-top: 24px; }
    .drawer-descripcion { font-size: 14px; color: var(--muted); line-height: 1.8; }

    .drawer-atributos { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .drawer-attr { background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 10px 14px; }
    .drawer-attr-label { font-size: 11px; color: var(--muted); font-weight: 600; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 3px; }
    .drawer-attr-valor { font-family: 'Space Mono', monospace; font-size: 13px; color: #fff; font-weight: 600; }

    .drawer-razones { margin-top: 12px; }
    .drawer-razon { display: flex; align-items: flex-start; gap: 8px; font-size: 13px; color: #ef4444; line-height: 1.5; margin-bottom: 6px; }

    .drawer-footer {
        padding: 20px 24px; border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        flex-shrink: 0; background: var(--bg-card);
    }
    .drawer-precio { font-family: 'Inter', sans-serif; font-size: 32px; font-weight: 800; color: #fff; line-height: 1; }
    .drawer-precio em { font-style: normal; font-size: 16px; color: var(--muted); font-weight: 400; }
    .drawer-stock-txt { font-size: 12px; color: var(--muted); margin-top: 4px; }
    .btn-seleccionar-drawer { padding: 13px 28px; background: #3b82f6; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s; }
    .btn-seleccionar-drawer:hover { opacity: 0.9; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(59,130,246,0.3); }
    .btn-seleccionar-drawer.ya-sel { background: #22c55e; }
    .btn-seleccionar-drawer:disabled { background: #374151; cursor: not-allowed; opacity: 0.5; transform: none; box-shadow: none; }

    @media (max-width: 900px) {
        .wizard-wrap { grid-template-columns: 1fr; padding: 24px 16px 60px; }
        .steps-sidebar { position: static; }
        .step-list { flex-direction: row; flex-wrap: wrap; gap: 8px; }
        .step-item { padding: 6px 10px; font-size: 12px; }
        .step-item span:last-child { display: none; }
        .drawer { width: 100%; }
        .drawer-atributos { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
<div class="wizard-wrap">

    <aside class="steps-sidebar">
        <p class="steps-title">Tu configuración</p>

        @if(isset($plataforma))
        <div style="display:flex;align-items:center;gap:10px;background:rgba(59,130,246,0.08);border:1px solid rgba(59,130,246,0.2);border-radius:8px;padding:10px 14px;margin-bottom:20px;">
            <span style="font-size:18px">🔌</span>
            <div>
                <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:0.06em">Plataforma</div>
                <div style="font-family:'Syne',sans-serif;font-weight:800;color:#fff;font-size:15px">{{ $plataforma }}</div>
            </div>
            <a href="{{ route('configurador.plataforma') }}"
               style="margin-left:auto;font-size:11px;color:var(--accent);text-decoration:none;opacity:0.7"
               onclick="return confirm('¿Cambiar plataforma? Se perderá la configuración actual.')">cambiar</a>
        </div>
        @endif

        <ul class="step-list">
            @foreach($categorias as $i => $cat)
                @php
                    $numPaso  = $i + 1;
                    $isDone   = isset($config['seleccion'][$cat->nombre]);
                    $isActive = $numPaso === $paso;
                @endphp
                <li class="step-item {{ $isDone ? 'done' : ($isActive ? 'active' : '') }}">
                    <a href="{{ route('configurador.index', ['paso' => $numPaso]) }}"
                       style="display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;width:100%;padding:9px 12px;">
                        <div class="step-num">{{ $isDone ? '✓' : $numPaso }}</div>
                        <span>{{ $cat->nombre }}</span>
                    </a>
                </li>
            @endforeach
            {{-- Paso montaje en sidebar --}}
            @php
                $numPasoMontaje = $categorias->count() + 1;
                $montajeDone    = isset($config['montaje']);
                $montajeActive  = $paso === $numPasoMontaje;
            @endphp
            <li class="step-item {{ $montajeDone ? 'done' : ($montajeActive ? 'active' : '') }}">
                <a href="{{ route('configurador.index', ['paso' => $numPasoMontaje]) }}"
                   style="display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;width:100%;padding:9px 12px;">
                    <div class="step-num">{{ $montajeDone ? '✓' : $numPasoMontaje }}</div>
                    <span>Montaje</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-total">
            <span>Total estimado</span>
            <strong>{{ number_format($totalPrecio, 2, ',', '.') }}<em> €</em></strong>
        </div>

        @if(!empty($config['seleccion']))
        <form action="{{ route('configurador.reiniciar') }}" method="POST" style="margin-top:16px">
            @csrf
            <button type="submit" class="btn-ghost" style="width:100%;font-size:12px;padding:8px"
                onclick="return confirm('¿Reiniciar la configuración?')">↺ Empezar de nuevo</button>
        </form>
        @endif
    </aside>

    <div class="wizard-main">
        <div class="wizard-header">
            <small>Paso {{ $paso }} de {{ $totalPasos }}</small>
            <h1>{{ isset($esPasoMontaje) && $esPasoMontaje ? 'Servicio de montaje' : 'Elige tu ' . $categoriaActual->nombre }}</h1>
        </div>

        @if(isset($esPasoMontaje) && $esPasoMontaje)

            {{-- ── PASO MONTAJE ── --}}
            @php $montajeSeleccionado = $config['montaje'] ?? null; @endphp
            <div class="productos-grid">

                {{-- Sin montaje --}}
                <div class="producto-card {{ $montajeSeleccionado === 'sin_montaje' ? 'seleccionado' : '' }}" style="cursor:default">
                    @if($montajeSeleccionado === 'sin_montaje')
                        <div class="badge badge-seleccionado">✓ Seleccionado</div>
                    @endif
                    <div class="producto-img"><div class="placeholder-icon">📦</div></div>
                    <div class="producto-body">
                        <div class="producto-nombre">Sin montaje</div>
                        <div class="producto-atributos">
                            <span class="attr-tag">Lo monto yo mismo</span>
                            <span class="attr-tag">Sin coste adicional</span>
                        </div>
                        <div style="font-size:13px;color:var(--muted);margin-top:4px">
                            Recibirás los componentes por separado y los montarás tú mismo.
                        </div>
                    </div>
                    <div class="producto-footer">
                        <div class="producto-precio">0,00<span> €</span></div>
                        <form action="{{ route('configurador.seleccionarMontaje') }}" method="POST">
                            @csrf
                            <input type="hidden" name="montaje" value="sin_montaje">
                            <button type="submit" class="btn-seleccionar {{ $montajeSeleccionado === 'sin_montaje' ? 'ya-seleccionado' : '' }}">
                                {{ $montajeSeleccionado === 'sin_montaje' ? '✓ Elegido' : 'Seleccionar' }}
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Con montaje --}}
                <div class="producto-card {{ $montajeSeleccionado === 'con_montaje' ? 'seleccionado' : '' }}" style="cursor:default">
                    @if($montajeSeleccionado === 'con_montaje')
                        <div class="badge badge-seleccionado">✓ Seleccionado</div>
                    @endif
                    <div class="producto-img"><div class="placeholder-icon">🔧</div></div>
                    <div class="producto-body">
                        <div class="producto-nombre">Servicio de montaje profesional</div>
                        <div class="producto-atributos">
                            <span class="attr-tag">Montaje incluido</span>
                            <span class="attr-tag">Garantía de montaje</span>
                        </div>
                        <div style="font-size:13px;color:var(--muted);margin-top:4px">
                            Nuestros técnicos montarán y probarán tu PC antes de enviártelo.
                        </div>
                    </div>
                    <div class="producto-footer">
                        <div class="producto-precio">{{ number_format($preciomontaje, 2, ',', '.') }}<span> €</span></div>
                        <form action="{{ route('configurador.seleccionarMontaje') }}" method="POST">
                            @csrf
                            <input type="hidden" name="montaje" value="con_montaje">
                            <button type="submit" class="btn-seleccionar {{ $montajeSeleccionado === 'con_montaje' ? 'ya-seleccionado' : '' }}">
                                {{ $montajeSeleccionado === 'con_montaje' ? '✓ Elegido' : 'Seleccionar' }}
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <div class="wizard-actions">
                <a href="{{ route('configurador.index', ['paso' => $paso - 1]) }}" class="btn-ghost">← Anterior</a>
                <div></div>
            </div>

        @else

            {{-- ── PASOS NORMALES ── --}}
            <div style="display:flex;align-items:center;justify-content:flex-end;margin-bottom:20px;">
                <div style="display:flex;align-items:center;gap:8px">
                    <span style="font-size:13px;color:var(--muted)">Ordenar por:</span>
                    <select onchange="ordenarProductos(this.value)"
                        style="padding:7px 32px 7px 12px;background:var(--bg-card);border:1px solid var(--border);border-radius:7px;color:var(--text);font-size:13px;cursor:pointer;outline:none;appearance:none;-webkit-appearance:none;background-image:url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2212%22 height=%2212%22 viewBox=%220 0 12 12%22><path fill=%22%236b7280%22 d=%22M6 8L1 3h10z%22/></svg>');background-repeat:no-repeat;background-position:right 10px center;">
                        <option value="nombre">Relevancia</option>
                        <option value="precio-asc">Precio más bajo</option>
                        <option value="precio-desc">Precio más alto</option>
                    </select>
                </div>
            </div>

            @if($productos->isEmpty())
                <div style="text-align:center;padding:60px 20px;color:var(--muted)">
                    <div style="font-size:48px;margin-bottom:16px">📦</div>
                    <p>No hay productos disponibles en esta categoría.</p>
                </div>
            @else
                <div class="productos-grid" id="productos-grid">

                    @if(isset($opcionGraficosIntegrados) && $opcionGraficosIntegrados)
                        @php $estaSelecIgpu = ($config['seleccion'][$categoriaActual->nombre] ?? null) === 'igpu'; @endphp
                        <div class="producto-card {{ $estaSelecIgpu ? 'seleccionado' : '' }}" style="border-style:dashed;cursor:default">
                            @if($estaSelecIgpu)
                                <div class="badge badge-seleccionado">✓ Seleccionado</div>
                            @endif
                            <div class="producto-img"><div class="placeholder-icon">🖥️</div></div>
                            <div class="producto-body">
                                <div class="producto-nombre">Usar gráficos integrados</div>
                                <div class="producto-atributos">
                                    <span class="attr-tag">{{ $opcionGraficosIntegrados }}</span>
                                    <span class="attr-tag">Sin coste adicional</span>
                                </div>
                                <div style="font-size:13px;color:var(--muted);margin-top:4px">
                                    Tu procesador incluye gráficos integrados. Válido para ofimática, multimedia y uso básico.
                                </div>
                            </div>
                            <div class="producto-footer">
                                <div class="producto-precio">0,00<span> €</span></div>
                                <form action="{{ route('configurador.seleccionar') }}" method="POST" onclick="event.stopPropagation()">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="-1">
                                    <input type="hidden" name="paso_actual" value="{{ $paso }}">
                                    <button type="submit" class="btn-seleccionar {{ $estaSelecIgpu ? 'ya-seleccionado' : '' }}">
                                        {{ $estaSelecIgpu ? '✓ Elegido' : 'Seleccionar' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if(isset($opcionDisipadorCaja) && $opcionDisipadorCaja)
                        @php $estaSelecDisipador = ($config['seleccion'][$categoriaActual->nombre] ?? null) === 0; @endphp
                        <div class="producto-card {{ $estaSelecDisipador ? 'seleccionado' : '' }}" style="border-style:dashed;cursor:default">
                            @if($estaSelecDisipador)
                                <div class="badge badge-seleccionado">✓ Seleccionado</div>
                            @endif
                            <div class="producto-img"><div class="placeholder-icon">📦</div></div>
                            <div class="producto-body">
                                <div class="producto-nombre">Disipador de caja incluido</div>
                                <div class="producto-atributos">
                                    <span class="attr-tag">Incluido con CPU</span>
                                    <span class="attr-tag">Uso básico</span>
                                </div>
                            </div>
                            <div class="card-hint">Disipador que viene incluido en la caja del procesador</div>
                            <div class="producto-footer">
                                <div class="producto-precio">0,00<span> €</span></div>
                                <form action="{{ route('configurador.seleccionar') }}" method="POST" onclick="event.stopPropagation()">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="0">
                                    <input type="hidden" name="paso_actual" value="{{ $paso }}">
                                    <button type="submit" class="btn-seleccionar {{ $estaSelecDisipador ? 'ya-seleccionado' : '' }}">
                                        {{ $estaSelecDisipador ? '✓ Elegido' : 'Seleccionar' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @foreach($productos as $producto)
                        @php
                            $compatible      = $producto->compatibilidad['compatible'] ?? true;
                            $razonesIncompat = $producto->compatibilidad['razones'] ?? [];
                            $estaSelec       = ($config['seleccion'][$categoriaActual->nombre] ?? null) == $producto->id;
                            $sinStock        = !$producto->enStock();
                        @endphp
                        <div class="producto-card {{ !$compatible ? 'incompatible' : '' }} {{ $estaSelec ? 'seleccionado' : '' }}"
                             onclick="abrirDrawer({{ $producto->id }})"
                             data-precio="{{ $producto->precio }}"
                             data-nombre="{{ $producto->nombre }}">
                            @if($estaSelec)
                                <div class="badge badge-seleccionado">✓ Seleccionado</div>
                            @elseif(!$compatible)
                                <div class="badge badge-incompatible">⚠ Incompatible</div>
                            @endif
                            @if($sinStock)<div class="badge-sinstock">Sin stock</div>@endif
                            <div class="producto-img">
                                @if($producto->imagen)
                                    <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}" loading="lazy">
                                @else
                                    <div class="placeholder-icon">🖥️</div>
                                @endif
                            </div>
                            <div class="producto-body">
                                <div class="producto-nombre">{{ $producto->nombre }}</div>
                                @if($producto->atributoValores->isNotEmpty())
                                <div class="producto-atributos">
                                    @foreach($producto->atributoValores->take(3) as $av)
                                        <span class="attr-tag">{{ $av->atributo->nombre }}: {{ $av->valor }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="card-hint">👁 Ver ficha completa</div>
                            <div class="producto-footer">
                                <div class="producto-precio">
                                    {{ number_format($producto->precio, 2, ',', '.') }}<span> €</span>
                                </div>
                                @if($compatible && !$sinStock)
                                <form action="{{ route('configurador.seleccionar') }}" method="POST" onclick="event.stopPropagation()">
                                    @csrf
                                    <input type="hidden" name="producto_id" value="{{ $producto->id }}">
                                    <input type="hidden" name="paso_actual" value="{{ $paso }}">
                                    <button type="submit" class="btn-seleccionar {{ $estaSelec ? 'ya-seleccionado' : '' }}">
                                        {{ $estaSelec ? '✓ Elegido' : 'Seleccionar' }}
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @endforeach

                </div>
            @endif

            <div class="wizard-actions">
                <div style="display:flex;gap:12px;align-items:center">
                    @if($paso > 1)
                    <a href="{{ route('configurador.index', ['paso' => $paso - 1]) }}" class="btn-ghost">← Anterior</a>
                    @endif
                    <form action="{{ route('configurador.omitir') }}" method="POST">
                        @csrf
                        <input type="hidden" name="paso_actual" value="{{ $paso }}">
                        <button type="submit" class="btn-omitir">Omitir este paso</button>
                    </form>
                </div>
                @if(!empty($config['seleccion']))
                <a href="{{ route('configurador.resumen') }}" class="btn-ghost" style="color:var(--accent);border-color:rgba(59,130,246,0.3)">
                    Ver resumen →
                </a>
                @endif
            </div>

        @endif
    </div>
</div>

{{-- Overlay --}}
<div class="drawer-overlay" id="drawer-overlay" onclick="cerrarDrawer()"></div>

{{-- Drawer --}}
<div class="drawer" id="drawer">
    <div class="drawer-header">
        <span class="drawer-categoria" id="drawer-categoria"></span>
        <button class="drawer-close" onclick="cerrarDrawer()">✕</button>
    </div>
    <div class="drawer-body">
        <div class="drawer-img" id="drawer-img"></div>
        <div class="drawer-info">
            <div class="drawer-nombre" id="drawer-nombre"></div>
            <div class="drawer-badges" id="drawer-badges"></div>
            <div id="drawer-razones"></div>
            <div class="drawer-seccion-titulo">Descripción</div>
            <div class="drawer-descripcion" id="drawer-descripcion"></div>
            <div class="drawer-seccion-titulo">Especificaciones</div>
            <div class="drawer-atributos" id="drawer-atributos"></div>
        </div>
    </div>
    <div class="drawer-footer">
        <div>
            <div class="drawer-precio" id="drawer-precio"></div>
            <div class="drawer-stock-txt" id="drawer-stock"></div>
        </div>
        <div id="drawer-accion"></div>
    </div>
</div>

<script>
const productos = {!! $productosJson !!};
const paso = {{ $paso }};
const routeSeleccionar = "{{ route('configurador.seleccionar') }}";
const csrfToken = "{{ csrf_token() }}";
</script>
@endsection

@push('scripts')
<script>
function abrirDrawer(id) {
    const p = productos.find(x => x.id === id);
    if (!p) return;
    document.getElementById('drawer-categoria').textContent = p.categoria;
    const imgEl = document.getElementById('drawer-img');
    imgEl.innerHTML = p.imagen ? `<img src="${p.imagen}" alt="${p.nombre}">` : `<div class="ph">🖥️</div>`;
    document.getElementById('drawer-nombre').textContent = p.nombre;
    let badges = '';
    if (p.seleccionado) badges += `<span class="drawer-badge-sel">✓ Seleccionado</span>`;
    if (!p.sinStock)    badges += `<span class="drawer-badge-stock">● En stock (${p.stock} uds.)</span>`;
    document.getElementById('drawer-badges').innerHTML = badges;
    let razones = '';
    if (!p.compatible && p.razones.length) {
        razones = '<div class="drawer-razones">' + p.razones.map(r => `<div class="drawer-razon">⚠ ${r}</div>`).join('') + '</div>';
    }
    document.getElementById('drawer-razones').innerHTML = razones;
    document.getElementById('drawer-descripcion').textContent = p.descripcion;
    const attrs = p.atributos.map(a => `<div class="drawer-attr"><div class="drawer-attr-label">${a.nombre}</div><div class="drawer-attr-valor">${a.valor}</div></div>`).join('');
    document.getElementById('drawer-atributos').innerHTML = attrs;
    document.getElementById('drawer-precio').innerHTML = `${p.precio}<em> €</em>`;
    document.getElementById('drawer-stock').textContent = p.sinStock ? 'Sin stock' : `${p.stock} unidades disponibles`;
    let accion = '';
    if (!p.sinStock && p.compatible) {
        const cls = p.seleccionado ? 'ya-sel' : '';
        const txt = p.seleccionado ? '✓ Ya elegido' : 'Seleccionar';
        accion = `<form method="POST" action="${routeSeleccionar}"><input type="hidden" name="_token" value="${csrfToken}"><input type="hidden" name="producto_id" value="${p.id}"><input type="hidden" name="paso_actual" value="${paso}"><button type="submit" class="btn-seleccionar-drawer ${cls}">${txt}</button></form>`;
    } else if (p.sinStock) {
        accion = `<button class="btn-seleccionar-drawer" disabled>Sin stock</button>`;
    } else {
        accion = `<button class="btn-seleccionar-drawer" disabled>Incompatible</button>`;
    }
    document.getElementById('drawer-accion').innerHTML = accion;
    document.getElementById('drawer-overlay').classList.add('visible');
    document.getElementById('drawer').classList.add('visible');
    document.body.style.overflow = 'hidden';
}

function ordenarProductos(tipo) {
    const grid = document.getElementById('productos-grid');
    if (!grid) return;
    const cards = Array.from(grid.querySelectorAll('.producto-card[data-precio]'));
    cards.sort((a, b) => {
        if (tipo === 'nombre') return a.dataset.nombre.localeCompare(b.dataset.nombre);
        else if (tipo === 'precio-asc') return parseFloat(a.dataset.precio) - parseFloat(b.dataset.precio);
        else return parseFloat(b.dataset.precio) - parseFloat(a.dataset.precio);
    });
    cards.forEach(card => grid.appendChild(card));
}

function cerrarDrawer() {
    document.getElementById('drawer-overlay').classList.remove('visible');
    document.getElementById('drawer').classList.remove('visible');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarDrawer(); });
</script>
@endpush