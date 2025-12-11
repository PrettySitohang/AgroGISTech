<header class="fixed top-0 left-0 right-0 z-50 bg-bg-dark/95 backdrop-blur-sm shadow-xl shadow-bg-dark/50 border-b border-sienna/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            <div class="flex items-center space-x-2">
                @if($logoPath)
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" class="h-10 w-auto">
                @else
                    <i class="fas fa-leaf text-terracotta text-2xl"></i>
                @endif
                <a href="{{ url('/') }}" class="text-2xl font-extrabold text-cream-text tracking-wider">
                    {{ $siteName ?? 'AgroGISTech' }}
                </a>
            </div>

            <nav class="hidden md:flex space-x-8">
                <a href="{{ url('/teknologi') }}"
                   class="text-cream-text/80 font-semibold hover:text-terracotta transition duration-300">
                    Teknologi
                </a>
                <a href="{{ url('/riset') }}"
                   class="text-cream-text/80 font-semibold hover:text-terracotta transition duration-300">
                    Riset & Data
                </a>
                <a href="{{ url('/berita') }}"
                   class="text-cream-text/80 font-semibold hover:text-terracotta transition duration-300">
                    Berita
                </a>
                <a href="{{ url('/tentang') }}"
                   class="text-cream-text/80 font-semibold hover:text-terracotta transition duration-300">
                    Tentang Kami
                </a>
            </nav>

            <div class="flex items-center space-x-4">
                <a href="{{ route('login') }}"
                   class="hidden lg:inline-flex text-cream-text/80 font-semibold hover:text-terracotta transition duration-300">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="px-5 py-2.5 text-bg-dark font-bold rounded-xl bg-terracotta hover:bg-sienna hover:text-cream-text transition duration-300 shadow-lg shadow-terracotta/40">
                    Gabung Komunitas <i class="fas fa-arrow-up-right-from-square ml-1 text-sm"></i>
                </a>
            </div>

            <button class="md:hidden text-cream-text hover:text-terracotta focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>

        </div>
    </div>
</header>
