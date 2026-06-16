@if ($paginator->hasPages())
    <div class="pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span style="opacity:0.4;cursor:not-allowed;display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:14px;">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:14px;color:var(--text-secondary);transition:all 0.2s;" onmouseenter="this.style.borderColor='var(--green-400)';this.style.color='var(--green-700)';" onmouseleave="this.style.borderColor='#e2e8f0';this.style.color='var(--text-secondary)';">‹</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;font-size:14px;color:var(--text-muted);">…</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;background:var(--green-600);color:white;font-size:14px;font-weight:700;font-family:'Inter',sans-serif;">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:14px;color:var(--text-secondary);font-family:'Inter',sans-serif;transition:all 0.2s;" onmouseenter="this.style.borderColor='var(--green-400)';this.style.color='var(--green-700)';this.style.background='var(--green-50)';" onmouseleave="this.style.borderColor='#e2e8f0';this.style.color='var(--text-secondary)';this.style.background='';">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" style="display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:14px;color:var(--text-secondary);transition:all 0.2s;" onmouseenter="this.style.borderColor='var(--green-400)';this.style.color='var(--green-700)';" onmouseleave="this.style.borderColor='#e2e8f0';this.style.color='var(--text-secondary)';">›</a>
        @else
            <span style="opacity:0.4;cursor:not-allowed;display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:14px;">›</span>
        @endif
    </div>
@endif
