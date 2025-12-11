{{-- editor/partials/sidebar.blade.php --}}

<aside class="w-64 shrink-0 bg-terracotta/45 shadow-2xl shadow-terracotta/30 light:bg-sienna/95 transition-colors duration-500">
    <div class="h-full flex flex-col">

        {{-- Logo & Judul --}}
        <div class="flex items-center justify-center h-20 bg-sienna/80 light:bg-terracotta/80 p-4 border-b border-terracotta/50 light:border-sienna/50">
            <div class="flex items-center space-x-2 w-full justify-center">
                @if($logoPath)
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="h-12 w-auto">
                @else
                    <i class="fas fa-leaf text-cream-text text-2xl"></i>
                @endif
                <span class="text-lg font-extrabold text-cream-text tracking-wider text-center">
                    {{ $siteName ?? 'AgroGISTech' }}
                </span>
            </div>
        </div>

        {{-- Navigasi Menu --}}
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto text-cream-text font-semibold">
            {{-- Menggunakan route editor --}}
            @php $currentRouteName = Route::currentRouteName() ?? 'editor.dashboard'; @endphp

            {{-- Divider Tugas Editor --}}
            <div class="text-sm pt-4 pb-2 text-cream-text/70 light:text-bg-dark/70 font-bold uppercase">Tugas Editor</div>

            {{-- 2. Antrian Review (Antrian Klaim Artikel) --}}
            <a href="{{ route('editor.reviews.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                       @if(Str::startsWith($currentRouteName, 'editor.reviews'))
                           bg-cream-text text-terracotta shadow-md
                       @else
                           hover:bg-terracotta/60 hover:text-bg-dark
                       @endif">
                <i class="fas fa-clipboard-list w-6"></i>
                <span class="ml-3">Antrian Review</span>
            </a>

            {{-- 3. Daftar Artikel (Yang Sudah Di-edit/Terbit) --}}
            <a href="{{ route('editor.articles.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                       @if(Str::startsWith($currentRouteName, 'editor.articles') && !Str::startsWith($currentRouteName, 'editor.articles.create'))
                           bg-cream-text text-terracotta shadow-md
                       @else
                           hover:bg-terracotta/60 hover:text-bg-dark
                       @endif">
                <i class="fas fa-newspaper w-6"></i>
                <span class="ml-3">Daftar Artikel</span>
            </a>


            {{-- Divider Data Master --}}
            <div class="text-sm pt-4 pb-2 text-cream-text/70 light:text-bg-dark/70 font-bold uppercase">Data Master</div>

            {{-- 5. Kategori & Tag --}}
            {{-- Kita grup kategori dan tag di sini --}}
            <a href="{{ route('editor.categories.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                       @if(Str::startsWith($currentRouteName, 'editor.categories') || Str::startsWith($currentRouteName, 'editor.tags'))
                           bg-cream-text text-terracotta shadow-md
                       @else
                           hover:bg-terracotta/60 hover:text-bg-dark
                       @endif">
                <i class="fas fa-tags w-6"></i>
                <span class="ml-3">Kategori & Tag</span>
            </a>

        </nav>

        {{-- Tombol Logout --}}
        <div class="p-4 border-t border-terracotta/50 light:border-sienna/50">
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="w-full flex items-center justify-center p-3 rounded-xl bg-sienna hover:bg-bg-dark text-cream-text transition duration-200 shadow-lg shadow-sienna/40">
                <i class="fas fa-sign-out-alt mr-2"></i>
                Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</aside>
