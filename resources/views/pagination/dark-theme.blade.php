{{-- Blade View Kustom untuk Paginasi Tema Gelap --}}

@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination') }}" class="flex items-center justify-between space-x-2">

    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="px-4 py-2 text-gray-500 bg-gray-700/50 rounded-lg cursor-not-allowed text-sm font-medium">
            &laquo; Sebelumnya
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}"
           class="px-4 py-2 text-cream-text bg-bg-dark border border-sienna/50 rounded-lg text-sm font-medium hover:bg-sienna/50 transition duration-300">
            &laquo; Sebelumnya
        </a>
    @endif

    {{-- Pagination Elements --}}
    <div class="hidden sm:flex space-x-1">
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="px-3 py-2 text-gray-400 bg-bg-dark rounded-lg text-sm font-medium">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        {{-- Halaman Aktif --}}
                        <span class="px-4 py-2 text-bg-dark bg-terracotta rounded-lg text-sm font-bold shadow-md shadow-terracotta/40">
                            {{ $page }}
                        </span>
                    @else
                        {{-- Halaman Lain --}}
                        <a href="{{ $url }}"
                           class="px-4 py-2 text-cream-text bg-bg-dark border border-sienna/50 rounded-lg text-sm font-medium hover:bg-sienna/50 transition duration-300">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}"
           class="px-4 py-2 text-cream-text bg-bg-dark border border-sienna/50 rounded-lg text-sm font-medium hover:bg-sienna/50 transition duration-300">
            Berikutnya &raquo;
        </a>
    @else
        <span class="px-4 py-2 text-gray-500 bg-gray-700/50 rounded-lg cursor-not-allowed text-sm font-medium">
            Berikutnya &raquo;
        </span>
    @endif
</nav>
@endif
