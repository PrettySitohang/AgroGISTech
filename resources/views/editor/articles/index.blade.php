@extends('editor.layouts.app')

@section('page_title')
    {{-- Menggunakan $articles untuk Daftar, $articles untuk Edit Tunggal --}}
    @if (isset($articles))
        Manajemen Artikel (Daftar)
    @elseif (isset($articles))
        Workspace Editorial: {{ $articles->title }}
    @else
        Manajemen Artikel
    @endif
@endsection

@section('content')
<div class="space-y-6">

    {{-- Pesan Sesi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Logika Pengecekan View: Daftar Artikel (Ketika $articles ada) --}}
    @if (isset($articles))

        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Manajemen Artikel</h2>
        </div>

        <p class="text-gray-400 light:text-gray-600">Kelola semua konten, dari draf hingga artikel yang diarsipkan.</p>

        <div class="flex space-x-4">
            {{-- Total Artikel --}}
            <button class="px-4 py-2 text-cream-text bg-sienna/50 rounded-lg font-semibold light:bg-gray-200 light:text-light-text">Semua ({{ $totalArticles }})</button>

            {{-- Diterbitkan --}}
            <button class="px-4 py-2 text-cream-text bg-sawit-green/40 rounded-lg font-semibold light:bg-sawit-green/20 light:text-sawit-green">Diterbitkan ({{ $publishedCount }})</button>

            {{-- Draf --}}
            <button class="px-4 py-2 text-cream-text bg-terracotta/40 rounded-lg font-semibold light:bg-terracotta/20 light:text-terracotta">Draf ({{ $draftCount }})</button>

            {{-- Review --}}
            <button class="px-4 py-2 text-cream-text bg-gray-500/40 rounded-lg font-semibold light:bg-gray-200 light:text-gray-600">Sedang Direview ({{ $reviewCount }})</button>
        </div>

        {{-- Tabel Daftar Artikel --}}
        <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl overflow-hidden border border-sienna/50 light:border-gray-200">
            <div class="p-6">
                <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Daftar Artikel (Bukan Draft)</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-sienna/30 light:divide-gray-200">
                        <thead class="bg-sienna/20 light:bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Judul & Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Penulis</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-sienna/30 light:divide-gray-200">
                            @forelse ($articles as $article)
                                <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">

                                    {{-- Kolom Judul & Tanggal --}}
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-cream-text light:text-light-text">{{ Str::limit($article->title, 50) }}</div>
                                        <div class="text-xs text-gray-500 light:text-gray-400 mt-1">
                                            Dibuat: {{ $article->created_at->format('d M Y') }}
                                        </div>
                                    </td>

                                    {{-- Kolom Penulis --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">
                                        <span class="font-semibold text-cream-text light:text-light-text">{{ $article->author->name ?? 'N/A' }}</span>
                                    </td>

                                    {{-- Kolom Kategori --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-terracotta/20 text-terracotta light:bg-terracotta/10">
                                                {{ $article->category->name ?? 'Tidak Ada' }}
                                        </span>
                                    </td>

                                    {{-- Kolom Status --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($article->status == 'published')
                                                bg-sawit-green/40 text-cream-text
                                            @elseif($article->status == 'draft')
                                                bg-terracotta/40 text-cream-text
                                            @else
                                                bg-gray-500/40 text-cream-text
                                            @endif">
                                                {{ ucfirst($article->status) }}
                                        </span>
                                    </td>

                                    {{-- Kolom Aksi --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">

                                        {{-- TOMBOL EDIT: hanya boleh edit jika status='review' dan artikel diklaim oleh editor ini --}}
                                        @if($article->status == 'review' && optional($article->editor)->id === auth()->id())
                                            <a href="{{ route('editor.articles.edit', $article->id) }}"
                                            class="text-terracotta hover:text-sienna transition font-medium inline-flex items-center" title="Edit Artikel">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </a>
                                        @elseif($article->status == 'review' && is_null($article->editor_id))
                                            {{-- Jika sedang review tapi belum diklaim, tawarkan klaim & mulai --}}
                                            <form action="{{ route('editor.reviews.claim', $article->id) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-terracotta hover:text-sienna transition font-medium inline-flex items-center" title="Klaim & Mulai">
                                                    <i class="fas fa-hand-paper mr-1"></i> Klaim & Mulai
                                                </button>
                                            </form>
                                        @else
                                            {{-- Artikel yang dipublikasikan tidak dapat diedit oleh editor --}}
                                            <span class="text-gray-500 cursor-not-allowed inline-flex items-center" title="Artikel sudah dipublikasikan, tidak dapat diedit">
                                                <i class="fas fa-edit mr-1"></i> Edit
                                            </span>
                                        @endif

                                        {{-- Tombol HAPUS (Wipe) --}}
                                        <form action="{{ route('editor.articles.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini secara permanen?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700 transition font-medium inline-flex items-center" title="Hapus Permanen">
                                                <i class="fas fa-trash-alt mr-1"></i> Wipe
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 light:text-gray-400">
                                        Tidak ada artikel yang tersedia (atau semua dalam status draft).
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi --}}
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

    {{-- Logika Pengecekan View: Edit Artikel Tunggal (Ketika $articles ada) --}}
    @elseif (isset($article))
        {{-- Tempatkan KODE LENGKAP untuk form EDIT artikel tunggal di sini. --}}
        {{-- Karena Anda tidak memberikan kode form edit, saya meninggalkan bagian ini kosong. --}}
        <h1 class="text-2xl font-extrabold text-red-500">PERINGATAN: Form Edit Tunggal Belum Ada</h1>
        <p>Mohon sertakan kode Blade untuk form edit artikel tunggal (judul, konten, status, revisi) di sini.</p>
    @else
        <h1 class="text-2xl font-extrabold text-red-500">ERROR: Variabel $articles atau $articles tidak ditemukan.</h1>
    @endif
</div>
@endsection
