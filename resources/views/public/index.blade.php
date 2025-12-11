@extends('layouts.app')

{{--
    Pastikan tag <script> ini dimuat HANYA JIKA Anda tidak menggunakan build Tailwind lokal.
    Jika Anda mengandalkan CDN Tailwind, Anda perlu menambahkan script ini di <head> atau sebelum penggunaan:
    <script src="https://cdn.tailwindcss.com"></script>
--}}
<script>
    // Konfigurasi ini mendefinisikan warna kustom agar Tailwind mengenalnya.
    // Jika Anda menggunakan build tool (seperti Vite/Webpack), konfigurasi ini seharusnya berada di tailwind.config.js,
    // tetapi diletakkan di sini untuk memastikan preview Canvas merender warna dengan benar.
    tailwind.config = {
        darkMode: 'class',
        theme: {
            extend: {
                colors: {
                    // Berdasarkan Palet Warna yang Diberikan (Merah-Coklat/Maroon)
                    'bg-dark': '#1C0E0B', // Maroon paling gelap (Latar Belakang Utama)
                    'sienna': '#8B3A2C', // Merah-coklat sedang (Aksen Kuat)
                    'terracotta': '#D36B5E', // Aksen tombol/highlight (Warna Terang, seperti warna PRO CREATOR CTA)
                    'cream-text': '#F5F5DC', // Off-white untuk teks
                    'sawit-green': '#10b981', // Hijau sekunder (opsional untuk kesan 'berkelanjutan')
                },
                // Mengganti font menjadi Inter atau yang lebih tebal untuk kesan modern
                fontFamily: {
                    sans: ['Inter', 'sans-serif'],
                },
            }
        }
    }
</script>


@section('content')

{{-- Bagian Utama: Menggunakan Latar Belakang Gelap Khas Sawit Tech --}}
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 min-h-screen bg-bg-dark transition-colors duration-500">

    {{-- Hero Section: Fokus dan Visual yang Kuat dengan Animasi CSS --}}
    <div class="text-center mb-24 relative overflow-hidden p-8 rounded-3xl">
        {{-- Ilustrasi Sawit Digital (Latar Belakang Abstrak) --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            {{-- Menggunakan SVG yang Diadaptasi dari Garis Daun Sawit dengan Sentuhan Digital --}}
            <style>
                @keyframes pulse-dot {
                    0%, 100% { transform: scale(1); opacity: 0.8; }
                    50% { transform: scale(1.5); opacity: 1; }
                }
                .dot-pulse { animation: pulse-dot 1.5s infinite ease-in-out; }
            </style>
            <svg class="w-full h-full" viewBox="0 0 1000 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M960 150C850 50 600 250 400 100C200 -50 40 200 10 150" stroke="#8B3A2C" stroke-width="4" stroke-linecap="round" stroke-dasharray="10 10"/>
                <circle cx="50" cy="180" r="10" fill="#D36B5E" class="dot-pulse"/>
                <circle cx="950" cy="120" r="10" fill="#D36B5E" class="dot-pulse delay-300"/>
            </svg>
        </div>

        <h1 class="text-6xl md:text-7xl font-extrabold text-cream-text mb-6 leading-tight relative z-10">
            Inovasi dan <span class="text-terracotta">Teknologi Digital</span> Kelapa Sawit
        </h1>
        <p class="text-xl text-gray-400 mb-10 max-w-4xl mx-auto relative z-10">
            Temukan wawasan terbaru, riset mendalam, dan solusi teknologi untuk industri kelapa sawit yang berkelanjutan dan efisien.
        </p>

        {{-- CTA Khusus (Tombol dengan Efek Gradien) --}}
        <button onclick="window.location.href='#articles'"
            class="group relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-lg font-medium text-cream-text rounded-full bg-gradient-to-br from-terracotta to-sienna hover:from-sienna hover:to-terracotta transition-all duration-300 shadow-lg shadow-sienna/50">
            <span class="relative px-8 py-3 transition-all ease-out duration-300 bg-bg-dark rounded-full group-hover:bg-opacity-0">
                Akses Wawasan Terbaru <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </span>
        </button>

    </div>

    {{-- Search & Filter Section: Desain Modern dan Bersih --}}
    <div class="mb-16 p-8 rounded-3xl bg-bg-dark border border-sienna/30 shadow-2xl shadow-sienna/10">
        <form method="GET" action="{{ route('public.index') }}" class="flex flex-col lg:flex-row gap-4 items-center">

            {{-- Input Pencarian --}}
            <input type="text" name="search" placeholder="Cari judul, kata kunci, atau penulis..."
                class="flex-grow p-4 border border-sienna/50 rounded-2xl focus:ring-terracotta focus:border-terracotta bg-bg-dark text-cream-text placeholder-gray-500 transition duration-300 shadow-inner shadow-sienna/10"
                value="{{ request('search') }}">

            {{-- Select Kategori --}}
            <select name="category"
                class="p-4 border border-sienna/50 rounded-2xl bg-bg-dark text-cream-text w-full lg:w-64 appearance-none shadow-inner shadow-sienna/10 cursor-pointer">
                <option value="" class="bg-sienna text-cream-text">Semua Kategori</option>
                <option value="budidaya" class="bg-sienna text-cream-text" {{ request('category') == 'budidaya' ? 'selected' : '' }}>Agronomi Presisi</option>
                <option value="pengolahan" class="bg-sienna text-cream-text" {{ request('category') == 'pengolahan' ? 'selected' : '' }}>Pabrik & Hilirisasi</option>
                <option value="iot" class="bg-sienna text-cream-text" {{ request('category') == 'iot' ? 'selected' : '' }}>IoT & Sensor</option>
            </select>

            {{-- Tombol Cari --}}
            <button type="submit"
                class="p-4 bg-terracotta text-bg-dark rounded-2xl font-bold hover:bg-sienna hover:text-cream-text transition-all duration-300 w-full lg:w-40 shadow-lg shadow-terracotta/40">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>
    </div>

    {{-- Article Grid: Tampilan 3 Kolom Premium --}}
    <div id="articles" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

        {{-- Loop Artikel --}}
        @forelse ($articles as $article)
            <a href="{{ route('public.show', $article->slug) }}"
                class="group block bg-bg-dark rounded-3xl overflow-hidden transition duration-500 border border-sienna/40 hover:border-terracotta/80 shadow-xl hover:shadow-terracotta/20 hover:scale-[1.02] transform">

                {{-- Gambar Cover Artikel dengan Hover Zoom dan Rotasi --}}
                <div class="relative overflow-hidden h-56">
                    <img src="{{ asset($article->cover_image ?? 'images/placeholder.jpg') }}"
                        onerror="this.src='https://placehold.co/600x400/8B3A2C/F5F5DC?text=Tech+Sawit+Image';"
                        alt="{{ $article->title }}"
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 group-hover:rotate-1">
                    {{-- Efek Overlay Gradasi untuk Kedalaman --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-bg-dark/40 to-transparent"></div>
                </div>

                <div class="p-6">
                    {{-- Kategori (Tag Modern) --}}
                    <span class="inline-block px-3 py-1 text-xs font-extrabold uppercase tracking-widest bg-sienna/20 text-terracotta rounded-full">
                        {{ $article->category->name ?? 'Umum' }}
                    </span>

                    {{-- Judul Artikel --}}
                    <h2 class="mt-3 text-2xl font-bold text-cream-text line-clamp-2 group-hover:text-terracotta transition-colors">
                        {{ $article->title }}
                    </h2>

                    {{-- Deskripsi Singkat --}}
                    <p class="mt-4 text-gray-400 text-base line-clamp-3">
                        {{ Str::limit(strip_tags($article->content), 150) }}
                    </p>

                    {{-- Metadata (Penulis & Tanggal) --}}
                    <div class="mt-6 pt-4 border-t border-sienna/30 flex items-center justify-between text-sm text-gray-500">
                        <span class="flex items-center">
                            <i class="fas fa-user-circle mr-2 text-terracotta"></i>
                            {{ $article->author->name ?? 'Anonim' }}
                        </span>
                        <span>
                            <i class="fas fa-calendar-alt mr-1 text-terracotta"></i>
                            {{ $article->published_at?->format('d M Y') ?? '-' }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            {{-- Tidak Ada Artikel Ditemukan --}}
            <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center p-12 bg-sienna/10 rounded-2xl border border-sienna/40">
                <i class="fas fa-seedling text-terracotta text-4xl mb-4"></i>
                <p class="text-cream-text text-lg">
                    Maaf, tidak ada artikel yang ditemukan sesuai kriteria pencarian Anda.
                </p>
            </div>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-16 flex justify-center">
        {{-- Menggunakan file pagination.dark-theme yang baru dibuat --}}
        {{ $articles->links('pagination.dark-theme', ['color' => 'terracotta']) }}
    </div>

</main>

@endsection
