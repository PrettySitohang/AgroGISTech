{{-- Sidebar untuk Panel Administrasi AgroGISTech --}}

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
            @php $currentRouteName = Route::currentRouteName() ?? 'admin.dashboard'; @endphp

            {{-- 1. Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                      @if(Str::startsWith($currentRouteName, 'admin.dashboard'))
                         bg-cream-text text-terracotta shadow-md
                      @else
                         hover:bg-terracotta/60 hover:text-bg-dark
                      @endif">
                <i class="fas fa-tachometer-alt w-6"></i>
                <span class="ml-3">Dashboard</span>
            </a>

            {{-- Divider Manajemen --}}
            <div class="text-sm pt-4 pb-2 text-cream-text/70 light:text-bg-dark/70 font-bold uppercase">Manajemen</div>

            {{-- 2. Pengguna (User Management) --}}
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                      @if(Str::startsWith($currentRouteName, 'admin.users'))
                         bg-cream-text text-terracotta shadow-md
                      @else
                         hover:bg-terracotta/60 hover:text-bg-dark
                      @endif">
                <i class="fas fa-users w-6"></i>
                <span class="ml-3">Pengguna</span>
            </a>

            {{-- 3. Artikel --}}
            <a href="{{ route('admin.articles.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                      @if(Str::startsWith($currentRouteName, 'admin.articles'))
                         bg-cream-text text-terracotta shadow-md
                      @else
                         hover:bg-terracotta/60 hover:text-bg-dark
                      @endif">
                <i class="fas fa-newspaper w-6"></i>
                <span class="ml-3">Artikel</span>
            </a>

            {{-- 4. Kategori & Tag --}}
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                      @if(Str::startsWith($currentRouteName, 'admin.categories.index'))
                         bg-cream-text text-terracotta shadow-md
                      @else
                         hover:bg-terracotta/60 hover:text-bg-dark
                      @endif">
                <i class="fas fa-tags w-6"></i>
                <span class="ml-3">Kategori & Tag</span>
            </a>

            {{-- 5. Laporan & Log --}}
            <a href="{{ route('admin.logs.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                      @if(Str::startsWith($currentRouteName, 'admin.logs.index'))
                         bg-cream-text text-terracotta shadow-md
                      @else
                         hover:bg-terracotta/60 hover:text-bg-dark
                      @endif">
                <i class="fas fa-chart-line w-6"></i>
                <span class="ml-3">Laporan & Log</span>
            </a>

            {{-- 6. Pengaturan Situs --}}
            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center p-3 rounded-xl transition duration-200
                      @if(Str::startsWith($currentRouteName, 'admin.settings'))
                         bg-cream-text text-terracotta shadow-md
                      @else
                         hover:bg-terracotta/60 hover:text-bg-dark
                      @endif">
                <i class="fas fa-cog w-6"></i>
                <span class="ml-3">Pengaturan</span>
            </a>
        </nav>

        {{-- Logout Button (Bottom) --}}
        <div class="p-4 border-t border-terracotta/50 light:border-sienna/50">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center p-3 rounded-xl text-cream-text font-semibold transition duration-200 hover:bg-terracotta/60 hover:text-bg-dark">
                    <i class="fas fa-sign-out-alt w-6"></i>
                    <span class="ml-3">Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
