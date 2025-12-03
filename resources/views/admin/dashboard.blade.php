@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">

    <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Selamat Datang, Admin!</h2>
    <p class="text-gray-400 light:text-gray-600">Kelola konten dan fungsionalitas Teknologi Sawit dengan mudah di sini.</p>

    {{-- Ringkasan Situs (Statistik Cards) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card 1: Jumlah Artikel --}}
        <div class="bg-sienna/80 light:bg-white rounded-xl p-6 shadow-lg border border-sienna/60 light:border-gray-200">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-cream-text/80 light:text-gray-600 mb-2">Jumlah Artikel</h3>
            <p class="text-4xl font-bold text-cream-text light:text-terracotta">120</p>
            <i class="fas fa-book-open text-3xl float-right -mt-8 text-cream-text/40 light:text-terracotta/40"></i>
        </div>

        {{-- Card 2: Pengguna Aktif --}}
        <div class="bg-sienna/80 light:bg-white rounded-xl p-6 shadow-lg border border-sienna/60 light:border-gray-200">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-cream-text/80 light:text-gray-600 mb-2">Pengguna Aktif (Online)</h3>
            <p class="text-4xl font-bold text-cream-text light:text-terracotta">15</p>
            <i class="fas fa-user-check text-3xl float-right -mt-8 text-cream-text/40 light:text-terracotta/40"></i>
        </div>

        {{-- Card 3: Total Trafik --}}
        <div class="bg-sienna/80 light:bg-white rounded-xl p-6 shadow-lg border border-sienna/60 light:border-gray-200">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-cream-text/80 light:text-gray-600 mb-2">Total Trafik (Views)</h3>
            <p class="text-4xl font-bold text-cream-text light:text-terracotta">1024</p>
            <i class="fas fa-eye text-3xl float-right -mt-8 text-cream-text/40 light:text-terracotta/40"></i>
        </div>
    </div>

    {{-- Grafik Penggunaan Website Bulanan --}}
    <div class="bg-bg-dark light:bg-white rounded-xl p-6 shadow-xl border border-sienna/50 light:border-gray-200">
        <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Grafik Penggunaan Website Bulanan</h3>

        {{-- Placeholder Grafik (Gunakan library JS seperti Chart.js di implementasi nyata) --}}
        <div class="h-80 flex items-center justify-center border border-dashed border-sienna/50 rounded-lg light:border-gray-300">
            <p class="text-gray-500 light:text-gray-500">
                [Placeholder Grafik Line Chart (Chart.js/Recharts) akan dimuat di sini]
            </p>
        </div>

    </div>

    {{-- Ringkasan Tugas / Aksi Cepat --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-bg-dark light:bg-white rounded-xl p-6 shadow-xl border border-sienna/50 light:border-gray-200">
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Aksi Cepat</h3>
            <ul class="space-y-3">
                <li>
                    <a href="{{ route('admin.articles.create') }}" class="flex items-center justify-between p-3 bg-sienna/20 hover:bg-sienna/40 rounded-lg transition light:bg-gray-100 light:hover:bg-gray-200">
                        <span class="text-cream-text light:text-light-text"><i class="fas fa-plus mr-2"></i> Buat Artikel Baru</span>
                        <i class="fas fa-arrow-right text-terracotta"></i>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.create') }}" class="flex items-center justify-between p-3 bg-sienna/20 hover:bg-sienna/40 rounded-lg transition light:bg-gray-100 light:hover:bg-gray-200">
                        <span class="text-cream-text light:text-light-text"><i class="fas fa-user-plus mr-2"></i> Tambah Pengguna</span>
                        <i class="fas fa-arrow-right text-terracotta"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</div>
@endsection
