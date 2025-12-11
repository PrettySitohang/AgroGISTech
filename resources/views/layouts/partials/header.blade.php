<header class="fixed top-0 left-0 right-0 z-50 bg-bg-dark/95 backdrop-blur-sm shadow-xl shadow-bg-dark/50 border-b border-sienna/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">

            {{-- Logo / Nama Platform (dari settings bila ada) --}}
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

            {{-- Navigasi Utama (Di tengah) --}}
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
                {{-- Dark Mode Toggle --}}
                <button onclick="window.toggleTheme()" class="p-2 rounded-full text-cream-text hover:bg-sienna/50 transition duration-300 light:text-light-text light:hover:bg-gray-200">
                    <i class="fas fa-sun text-lg hidden dark:block"></i>
                    <i class="fas fa-moon text-lg block dark:hidden"></i>
                </button>

                @auth
                    {{-- User Dropdown (Logged In) --}}
                    <div class="relative group flex items-center space-x-2 cursor-pointer">
                        <span class="hidden sm:block text-cream-text font-semibold">{{ Auth::user()->name }}</span>
                        <i class="fas fa-user-circle text-2xl text-terracotta group-hover:text-sienna transition"></i>

                        {{-- Dropdown Menu --}}
                        <div class="absolute right-0 top-full mt-2 w-48 bg-sienna/95 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-300 z-50">
                            <a href="{{ route('penulis.profile.edit') }}" class="block px-4 py-3 text-cream-text hover:bg-terracotta/50 rounded-t-lg transition">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profil
                            </a>
                            <a href="{{ route('penulis.dashboard') }}" class="block px-4 py-3 text-cream-text hover:bg-terracotta/50 transition border-t border-terracotta/30">
                                <i class="fas fa-home mr-2"></i>Dashboard
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="border-t border-terracotta/30">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-3 text-cream-text hover:bg-terracotta/50 rounded-b-lg transition">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    {{-- Not Logged In --}}
                    <a href="{{ route('login') }}"
                       class="hidden lg:inline-flex text-cream-text/80 font-semibold hover:text-terracotta transition duration-300">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 text-bg-dark font-bold rounded-xl bg-terracotta hover:bg-sienna hover:text-cream-text transition duration-300 shadow-lg shadow-terracotta/40">
                        Gabung Komunitas <i class="fas fa-arrow-up-right-from-square ml-1 text-sm"></i>
                    </a>
                @endauth
            </div>

            <button class="md:hidden text-cream-text hover:text-terracotta focus:outline-none">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</header>
