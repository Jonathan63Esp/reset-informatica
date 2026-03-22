@if ($paginator->hasPages())
<nav style="display:flex;align-items:center;gap:6px;justify-content:center;flex-wrap:wrap">

    {{-- Anterior --}}
    @if ($paginator->onFirstPage())
        <span style="padding:7px 14px;border-radius:7px;background:var(--bg);border:1px solid var(--border);color:var(--muted);font-size:13px;cursor:not-allowed;opacity:0.4">← Anterior</span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" style="padding:7px 14px;border-radius:7px;background:var(--bg-card);border:1px solid var(--border);color:var(--text);font-size:13px;text-decoration:none;transition:border-color 0.15s" onmouseover="this.style.borderColor='rgba(59,130,246,0.4)'" onmouseout="this.style.borderColor='var(--border)'">← Anterior</a>
    @endif

    {{-- Páginas --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span style="padding:7px 10px;color:var(--muted);font-size:13px">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span style="padding:7px 13px;border-radius:7px;background:var(--accent);border:1px solid var(--accent);color:#fff;font-size:13px;font-weight:700">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" style="padding:7px 13px;border-radius:7px;background:var(--bg-card);border:1px solid var(--border);color:var(--text);font-size:13px;text-decoration:none" onmouseover="this.style.borderColor='rgba(59,130,246,0.4)'" onmouseout="this.style.borderColor='var(--border)'">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Siguiente --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" style="padding:7px 14px;border-radius:7px;background:var(--bg-card);border:1px solid var(--border);color:var(--text);font-size:13px;text-decoration:none" onmouseover="this.style.borderColor='rgba(59,130,246,0.4)'" onmouseout="this.style.borderColor='var(--border)'">Siguiente →</a>
    @else
        <span style="padding:7px 14px;border-radius:7px;background:var(--bg);border:1px solid var(--border);color:var(--muted);font-size:13px;cursor:not-allowed;opacity:0.4">Siguiente →</span>
    @endif

</nav>
@endif