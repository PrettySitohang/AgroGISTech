@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Manajemen Data Master</h2>
    <p class="text-gray-400 light:text-gray-600">Kelola kategori dan tag yang digunakan untuk mengelompokkan artikel.</p>

    {{-- TAB NAVIGATION (Tag Aktif) --}}
    <div class="flex border-b border-sienna/50 light:border-gray-300 mb-6">

        {{-- Tab Kategori (INACTIVE) --}}
        <a href="{{ route('admin.categories.index') }}"
           class="px-6 py-2 text-gray-500 font-semibold hover:text-terracotta transition border-b-2 border-transparent hover:border-sienna/50">
            <i class="fas fa-folder-open mr-2"></i> Kategori
        </a>

        {{-- Tab Tag (ACTIVE) --}}
        <a href="{{ route('admin.tags.index') }}"
           class="px-6 py-2 text-terracotta font-bold border-b-2 border-terracotta -mb-px bg-sienna/10 light:bg-gray-100 transition duration-300">
            <i class="fas fa-tag mr-2"></i> Tag
        </a>
    </div>

    {{-- KONTEN UTAMA: FORM & TABEL TAG --}}

    {{-- Form Tambah Tag Baru --}}
    <div class="bg-bg-dark light:bg-white rounded-xl p-6 shadow-xl border border-sienna/50 light:border-gray-200 mb-6">
        <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Tambah Tag Baru</h3>

        <form action="{{ route('admin.tags.store') }}" method="POST" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-end">
            @csrf
            <div class="flex-grow w-full">
                <label for="name" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Nama Tag</label>
                <input type="text" id="name" name="name" required placeholder="Contoh: Drone, IoT, Hilirisasi"
                       class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text">
            </div>
            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-terracotta text-bg-dark rounded-lg font-bold hover:bg-sienna transition shadow-md shadow-terracotta/40">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </form>
    </div>

    {{-- Tabel Daftar Tag --}}
    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl overflow-hidden border border-sienna/50 light:border-gray-200">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Tag Tersedia</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sienna/30 light:divide-gray-200">
                    <thead class="bg-sienna/20 light:bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider w-1/12">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Nama Tag</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Jumlah Artikel</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider w-2/12">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sienna/30 light:divide-gray-200">
                        {{-- Contoh Data Tag --}}
                        @php
                            $tags = [
                                ['id' => 1, 'name' => 'Drone', 'count' => 25],
                                ['id' => 2, 'name' => 'PKS4.0', 'count' => 15],
                            ];
                        @endphp
                        @foreach ($tags as $tag)
                            <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-cream-text light:text-light-text">{{ $tag['id'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cream-text light:text-light-text">{{ $tag['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">{{ $tag['count'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    {{-- Menggunakan route yang sudah ada --}}
                                    <a href="{{ route('admin.tags.edit', $tag['id']) }}" class="text-terracotta hover:text-sienna transition light:text-terracotta light:hover:text-sienna">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.tags.delete', $tag['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus tag ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
