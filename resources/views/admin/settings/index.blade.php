@extends('admin.layouts.app')

@section('page_title', 'Pengaturan Situs')

@section('content')
<div class="p-8 max-w-7xl mx-auto">
    {{-- Page Header --}}
    <div class="mb-12">
        <h1 class="text-5xl font-bold text-cream-text mb-3">Pengaturan Situs</h1>
        <p class="text-lg text-gray-400">Kelola pengaturan umum dan konfigurasi situs Anda</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-8 p-6 bg-green-900/30 border border-green-600/50 text-green-400 rounded-xl flex items-center gap-4 shadow-lg">
            <i class="fas fa-check-circle text-2xl"></i>
            <span class="text-lg">{{ session('success') }}</span>
        </div>
    @endif

    {{-- Settings Form --}}
    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-bg-dark border border-sienna/40 rounded-2xl p-12 shadow-2xl">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Site Name Field --}}
            <div>
                <label for="site_name" class="block text-base font-bold text-cream-text mb-4 uppercase tracking-widest">
                    <i class="fas fa-globe mr-3 text-terracotta text-lg"></i>Nama Situs
                </label>
                <input 
                    type="text" 
                    id="site_name"
                    name="site_name" 
                    value="{{ old('site_name', $siteName) }}" 
                    class="w-full px-6 py-4 text-lg bg-sienna/20 border border-sienna/50 text-cream-text rounded-xl focus:outline-none focus:ring-2 focus:ring-terracotta focus:border-transparent placeholder-gray-500 transition duration-200"
                    placeholder="Masukkan nama situs"
                    required>
                @error('site_name') 
                    <div class="text-red-400 text-sm mt-3 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div> 
                @enderror
            </div>

            {{-- Logo Upload Field --}}
            <div>
                <label for="logo" class="block text-base font-bold text-cream-text mb-4 uppercase tracking-widest">
                    <i class="fas fa-image mr-3 text-terracotta text-lg"></i>Logo Situs
                </label>

                {{-- Current Logo Preview --}}
                @if($logoPath)
                    <div class="mb-6 p-6 bg-sienna/10 border border-sienna/40 rounded-xl">
                        <p class="text-sm text-gray-400 mb-4 font-semibold">Logo Saat Ini:</p>
                        <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo Situs" class="h-32 w-auto rounded-lg">
                    </div>
                @endif

                {{-- File Input --}}
                <input 
                    type="file" 
                    id="logo"
                    name="logo" 
                    accept="image/*"
                    class="w-full px-6 py-4 text-base bg-sienna/20 border border-sienna/50 text-cream-text rounded-xl cursor-pointer file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-base file:font-bold file:bg-terracotta file:text-bg-dark hover:file:bg-sienna focus:outline-none focus:ring-2 focus:ring-terracotta focus:border-transparent transition duration-200">
                <p class="text-sm text-gray-500 mt-3">Format: JPG, PNG, SVG (Max 2MB). Biarkan kosong jika tidak ingin mengubah.</p>
            </div>
        </div>

        @error('logo') 
            <div class="text-red-400 text-sm mt-4 flex items-center gap-2 col-span-full">
                <i class="fas fa-exclamation-circle"></i>
                {{ $message }}
            </div> 
        @enderror

        {{-- Submit Buttons --}}
        <div class="mt-12 pt-8 border-t border-sienna/30 flex flex-wrap gap-4">
            <button type="submit" class="px-8 py-4 text-lg bg-gradient-to-r from-terracotta to-sienna text-bg-dark font-bold rounded-xl hover:shadow-2xl hover:shadow-terracotta/50 transition-all duration-200 flex items-center gap-3 transform hover:scale-105">
                <i class="fas fa-save text-xl"></i>Simpan Perubahan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-8 py-4 text-lg bg-sienna/20 border-2 border-sienna/50 text-cream-text font-bold rounded-xl hover:bg-sienna/40 hover:border-sienna/70 transition-colors duration-200 flex items-center gap-3">
                <i class="fas fa-times text-xl"></i>Batal
            </a>
        </div>
    </form>
</div>
@endsection
