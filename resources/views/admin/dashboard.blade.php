@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Judul --}}
    <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">
        Selamat Datang, Admin!
    </h2>
    <p class="text-gray-400 light:text-gray-600">
        Kelola konten dan fungsionalitas Teknologi Sawit dengan mudah di sini.
    </p>

    {{-- Statistik Cards (Menggunakan Warna Berbeda) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card 1: Jumlah Artikel (Tema Hijau Sawit) --}}
        <div class="bg-sawit-green/20 light:bg-green-100 rounded-xl p-6 shadow-xl border border-sawit-green/20 light:border-green-300 transform hover:scale-[1.01] transition duration-300 relative overflow-hidden">
            <span class="absolute top-0 right-0 p-2 bg-sawit-green/50 light:bg-green-300 rounded-bl-xl">
                <i class="fas fa-book-open text-xl text-cream-text light:text-sawit-green"></i>
            </span>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-cream-text/90 light:text-gray-600 mb-2">
                Jumlah Artikel
            </h3>
            <p class="text-4xl font-bold text-cream-text light:text-sawit-green">
                {{ $articleCount }}
            </p>
        </div>

        {{-- Card 2: Pengguna Aktif (Tema Terakota/Cokelat Merah) --}}
        <div class="bg-terracotta/30 light:bg-red-100 rounded-xl p-6 shadow-xl border border-terracotta/30 light:border-red-300 transform hover:scale-[1.01] transition duration-300 relative overflow-hidden">
            <span class="absolute top-0 right-0 p-2 bg-terracotta/50 light:bg-red-300 rounded-bl-xl">
                <i class="fas fa-user-check text-xl text-cream-text light:text-terracotta"></i>
            </span>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-cream-text/90 light:text-gray-600 mb-2">
                Pengguna Aktif (Penulis)
            </h3>
            <p class="text-4xl font-bold text-cream-text light:text-terracotta">
                {{ $userCount }}
            </p>
        </div>

        {{-- Card 3: Total Log Aktivitas (Sienna) --}}
        <div class="bg-sienna/40 light:bg-yellow-100 rounded-xl p-6 shadow-xl border border-sienna/40 light:border-yellow-300 transform hover:scale-[1.01] transition duration-300 relative overflow-hidden">
            <span class="absolute top-0 right-0 p-2 bg-sienna/50 light:bg-yellow-300 rounded-bl-xl">
                <i class="fas fa-history text-xl text-cream-text light:text-sienna"></i>
            </span>
            <h3 class="text-sm font-semibold uppercase tracking-wider text-cream-text/90 light:text-gray-600 mb-2">
                Total Log Aktivitas
            </h3>
            <p class="text-4xl font-bold text-cream-text light:text-sienna">{{ $logCount }}</p>
        </div>

    </div>

    {{-- Grafik Baru: Log Aktivitas per Role --}}
    <div class="bg-bg-dark light:bg-white p-5 rounded-xl shadow-xl border border-sienna/50 light:border-gray-200">
        <h2 class="font-semibold text-lg mb-4 text-cream-text light:text-light-text">Aktivitas Log Pengguna Berdasarkan Role</h2>
        <canvas id="logActivityChart" height="100"></canvas>
    </div>

    {{-- Aksi Cepat --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-bg-dark light:bg-white rounded-xl p-6 shadow-xl border border-sienna/50 light:border-gray-200">
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">
                Aksi Cepat
            </h3>

            <ul class="space-y-3">
                <li>
                    <a href="{{ route('admin.users.create') }}"
                        class="flex items-center justify-between p-3 bg-sawit-green/20 hover:bg-sawit-green/40 rounded-lg transition light:bg-gray-100 light:hover:bg-gray-200">

                        <span class="text-cream-text light:text-light-text">
                            <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
                        </span>

                        <i class="fas fa-arrow-right text-sawit-green"></i>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('admin.logs.index') }}"
                        class="flex items-center justify-between p-3 bg-sienna/20 hover:bg-sienna/40 rounded-lg transition light:bg-gray-100 light:hover:bg-gray-200">

                        <span class="text-cream-text light:text-light-text">
                            <i class="fas fa-history mr-2"></i> Lihat Log Aktivitas
                        </span>

                        <i class="fas fa-arrow-right text-sienna"></i>
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-bg-dark light:bg-white rounded-xl p-6 shadow-xl border border-sienna/50 light:border-gray-200">
             <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">
                Informasi Sistem
            </h3>
            <p class="text-sm text-gray-400 light:text-gray-600">
                Waktu Server Saat Ini: {{ now()->format('d M Y, H:i:s') }}
            </p>
            <p class="text-sm text-gray-400 light:text-gray-600 mt-2">
                Versi Aplikasi: 1.0.0
            </p>
        </div>

    </div>

</div>
@endsection

@push('scripts')
{{-- CDN Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Dapatkan konteks untuk chart aktivitas log
    const ctx = document.getElementById('logActivityChart').getContext('2d');

    // Warna untuk Editor (Hijau Sawit) dan Penulis (Kuning/Cokelat)
    const roleColors = [
        '#34D399', // Green (Editor)
        '#FBBF24'  // Amber/Yellow (Penulis)
    ];

    const chartConfig = {
        type: 'bar',
        data: {
            labels: ['Editor', 'Penulis'],
            datasets: [{
                label: 'Jumlah Log Aktivitas',
                // Menggunakan data log yang baru dari Controller
                data: [{{ $editorLogCount }}, {{ $penulisLogCount }}],
                backgroundColor: roleColors,
                borderColor: roleColors.map(color => color + 'AA'),
                borderWidth: 1.5,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    // Pastikan tick hanya berupa bilangan bulat
                    ticks: {
                        color: 'rgb(209, 213, 219)',
                        stepSize: 1,
                        callback: function(value) {
                            if (value % 1 === 0) return value;
                        }
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)',
                        borderColor: 'rgba(255, 255, 255, 0.2)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgb(209, 213, 219)'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    };

    new Chart(ctx, chartConfig);

    // Fungsi untuk menyesuaikan warna Chart saat Light/Dark Mode
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

    function updateChartColors(isDarkMode) {
        const chart = Chart.getChart('logActivityChart');
        if (chart) {
            // Tentukan warna tick dan grid berdasarkan mode
            const tickColor = isDarkMode ? 'rgb(209, 213, 219)' : 'rgb(55, 65, 81)';
            const gridColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)';

            // Terapkan perubahan
            chart.options.scales.y.ticks.color = tickColor;
            chart.options.scales.x.ticks.color = tickColor;
            chart.options.scales.y.grid.color = gridColor;

            chart.update();
        }
    }

    // Panggil saat dimuat untuk setelan awal
    updateChartColors(darkModeMediaQuery.matches);

    // Tambahkan listener untuk perubahan tema
    darkModeMediaQuery.addEventListener('change', (e) => {
        updateChartColors(e.matches);
    });
});
</script>
@endpush
