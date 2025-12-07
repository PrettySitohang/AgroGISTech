<!DOCTYPE html>
{{-- Menggunakan class dark secara default, tapi tetap mendukung light/dark toggle --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- JUDUL DIPERBARUI UNTUK EDITOR --}}
    <title>Editor - AgroGISTech</title>

    {{-- Tailwind CSS CDN, Font, dan Ikon --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; }
        .min-h-screen { min-height: 100vh; }
        /* Transisi untuk sidebar */
        .sidebar-transition { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>

    <script>
        // Konfigurasi Tema Kustom (Sama seperti layout publik)
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'bg-dark': '#1C0E0B',
                        'sienna': '#8B3A2C',
                        'terracotta': '#D36B5E',
                        'cream-text': '#F5F5DC',
                        'bg-light': '#F5F5F5',
                        'light-text': '#1F2937',
                        'sawit-green': '#10b981',
                    },
                },
            }
        }

        // Script Dark Mode Toggle (Sama seperti layout publik)
        const theme = localStorage.getItem('theme');
        if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else if (theme === 'light') {
            document.documentElement.classList.remove('dark');
        }
        window.toggleTheme = function() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        };
    </script>
</head>

{{-- Menggunakan kelas body tema gelap default --}}
<body class="bg-bg-dark text-cream-text antialiased transition-colors duration-500 light:bg-gray-100 light:text-light-text">
    <div class="flex min-h-screen">

        {{-- 1. SIDEBAR (Fixed) --}}
        {{-- MENGUBAH INCLUDE KE DIREKTORI PARTIAL EDITOR --}}
        @include('editor.partials.sidebar')

        {{-- 2. KONTEN UTAMA & HEADER --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- Header/Top Bar Admin --}}
            <header class="flex items-center justify-between p-4 bg-bg-dark border-b border-sienna/50 light:bg-white light:border-gray-200 shadow-md">
                {{-- MENGGANTI HEADING MENJADI PANEL EDITOR --}}
                <h1 class="text-xl font-semibold text-cream-text light:text-light-text">Panel Editor</h1>

                {{-- User Profile & Theme Toggle --}}
                <div class="flex items-center space-x-4">
                    {{-- Theme Toggle --}}
                    <button onclick="window.toggleTheme()" class="p-2 rounded-full text-cream-text hover:bg-sienna/50 transition duration-300 light:text-light-text light:hover:bg-gray-200">
                        <i class="fas fa-sun text-xl hidden dark:block"></i>
                        <i class="fas fa-moon text-xl block dark:hidden"></i>
                    </button>

                    {{-- User Dropdown --}}
                    <div class="text-cream-text light:text-light-text flex items-center space-x-2">
                        {{-- MENGGANTI NAMA PENGGUNA MENJADI EDITOR --}}
                        <span class="font-medium hidden sm:block">Pretty (Editor)</span>
                        <i class="fas fa-user-circle text-2xl text-terracotta"></i>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                {{-- Tempat Session Messages Laravel ditampilkan --}}
                @if(session('success'))
                    <div class="p-4 mb-4 text-sm text-sawit-green rounded-lg bg-sawit-green/20 light:bg-sawit-green/10">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="p-4 mb-4 text-sm text-terracotta rounded-lg bg-terracotta/20 light:bg-terracotta/10">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
