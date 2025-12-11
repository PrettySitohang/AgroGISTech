@extends('editor.layouts.app')

{{-- Perbaikan Page Title: Menggunakan Blade Guard untuk menangani mode Daftar/Edit --}}
@section('page_title')
    @if (isset($article))
        Workspace Editorial: {{ $article->title }}
    @elseif (isset($articles))
        Manajemen Artikel (Daftar)
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

    {{-- Logika Pengecekan View --}}

    {{-- ========================================================================= --}}
    {{-- 1. Tampilan Edit Artikel Tunggal (Ketika $article ada) --}}
    {{-- ========================================================================= --}}
    @if (isset($article))

        {{-- Tampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Error Validasi!</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Kolom Kiri: Form Editor Utama --}}
            <div class="lg:col-span-2 bg-bg-dark light:bg-white shadow-xl rounded-xl p-6 border border-sienna/50 light:border-gray-200">
                <h2 class="text-2xl font-bold text-cream-text light:text-light-text mb-4">Edit Artikel (Status: {{ ucfirst(str_replace('_', ' ', $article->status)) }})</h2>

                <form action="{{ route('editor.articles.update', $article) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="title" class="block text-sm font-medium text-cream-text light:text-light-text">Judul Artikel</label>
                        <input id="title" name="title" type="text" required value="{{ old('title', $article->title) }}"
                                class="mt-1 block w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text @error('title') border-red-500 @enderror">
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-medium text-cream-text light:text-light-text">Isi Konten Artikel</label>
                        <textarea id="content" name="content" rows="15" required
                                    class="mt-1 block w-full px-4 py-2 border border-sienna/70 rounded-lg shadow-sm bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text @error('content') border-red-500 @enderror">{{ old('content', $article->content) }}</textarea>
                    </div>

                    {{-- START: INPUT CATEGORY DAN TAG (Melengkapi TODO) --}}

                    {{-- Dropdown Kategori (Single Select) --}}
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-cream-text light:text-light-text">Kategori</label>
                        <select id="category_id" name="category_id"
                                class="mt-1 block w-full px-4 py-2 border border-sienna/70 rounded-lg shadow-sm bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Dropdown Tag (Multi-select) --}}
                    <div>
                        <label for="tags" class="block text-sm font-medium text-cream-text light:text-light-text">Tag</label>
                        <select id="tags" name="tags[]" multiple
                                class="mt-1 block w-full px-4 py-2 border border-sienna/70 rounded-lg shadow-sm bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text"
                                style="min-height: 100px;">
                            @php
                                // Ambil ID tag yang sudah terpilih di artikel ini
                                $currentTags = old('tags', $article->tags ? $article->tags->pluck('id')->toArray() : []);
                            @endphp
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}"
                                    {{ in_array($tag->id, $currentTags) ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Pilih satu atau lebih tag. Tahan Ctrl/Cmd untuk memilih beberapa.</p>
                        @error('tags')
                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- END: INPUT CATEGORY DAN TAG --}}

                    <div>
                        <label for="review_notes" class="block text-sm font-medium text-cream-text light:text-light-text">Catatan Revisi / Tindakan (<span class="text-terracotta">Wajib</span>)</label>
                        <textarea id="review_notes" name="review_notes" rows="3" required placeholder="Tuliskan alasan perubahan, atau tindakan (Publish/Archive/Kirim Balik) di sini."
                                    class="mt-1 block w-full px-4 py-2 border border-sienna/70 rounded-lg shadow-sm bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text @error('review_notes') border-red-500 @enderror">{{ old('review_notes') }}</textarea>
                    </div>

                    <div class="pt-4 border-t border-sienna/50 dark:border-gray-700">
                        <label for="status" class="block text-sm font-medium text-cream-text light:text-light-text">Ubah Status dan Tindakan</label>
                        <select id="status" name="status" required
                                class="mt-1 block w-full px-4 py-2 border border-sienna/70 rounded-lg shadow-sm bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text">

                            {{-- Asumsi Controller menggunakan status 'review' --}}
                            <option value="review" {{ $article->status === 'review' ? 'selected' : '' }}>Simpan Revisi (Dalam Review)</option>
                            <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Kirim Balik ke Penulis (Ganti Status ke Draft)</option>
                            <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Publish Artikel</option>
                            <option value="archived" {{ $article->status === 'archived' ? 'selected' : '' }}>Archive Artikel</option>
                        </select>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-6 py-2 bg-terracotta text-bg-dark rounded-lg font-bold hover:bg-sienna transition duration-150 shadow-md shadow-terracotta/40">
                            <i class="fas fa-paper-plane mr-2"></i> Proses Revisi & Simpan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Kolom Kanan: Info & Riwayat Revisi --}}
            <div class="lg:col-span-1 space-y-8">

                {{-- Info Penulis --}}
                <div class="bg-sienna/30 light:bg-gray-100 shadow-lg rounded-xl p-6 border border-sienna/50 light:border-gray-200">
                    <h3 class="text-lg font-semibold text-cream-text light:text-light-text mb-3">Info Penulis</h3>
                    <p class="text-sm text-cream-text/80 light:text-gray-600">
                        *Penulis:* <a href="{{ route('editor.user.profile', $article->author) }}" class="text-terracotta hover:underline">{{ $article->author->name ?? 'N/A' }}</a><br>
                        *Email:* {{ $article->author->email ?? 'N/A' }}<br>
                        *Diajukan:* {{ $article->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Riwayat Revisi --}}
                <div class="bg-bg-dark light:bg-white shadow-xl rounded-xl p-6 border border-sienna/50 light:border-gray-200">
                    <h3 class="text-lg font-semibold text-cream-text light:text-light-text mb-4">Riwayat Revisi ({{ $revisions->count() }})</h3>
                    <div class="space-y-4 max-h-96 overflow-y-auto pr-2">
                        @forelse ($revisions as $revision)
                            <div class="p-3 border-l-4 border-terracotta/70 bg-sienna/20 light:bg-gray-50 rounded-md">
                                <p class="text-xs text-cream-text/90 light:text-gray-700 font-semibold">
                                    {{ $revision->created_at->format('d M Y H:i') }} - Oleh {{ $revision->editor->name ?? 'Admin' }}
                                </p>
                                <p class="text-sm italic text-cream-text/70 light:text-gray-500 mt-1">
                                    *Catatan:* {{ $revision->notes }}
                                </p>
                                <details class="text-xs text-cream-text/60 light:text-gray-500 mt-2">
                                    <summary>Lihat Perubahan Konten Lama</summary>
                                    <pre class="mt-2 p-2 bg-sienna/40 light:bg-gray-200 rounded-md whitespace-pre-wrap">{{ Str::limit($revision->content_before, 200) }}</pre>
                                </details>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat revisi.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    {{-- ========================================================================= --}}
    {{-- 2. Tampilan Daftar Artikel (Ketika $articles ada, menggunakan kode dari prompt sebelumnya) --}}
    {{-- ========================================================================= --}}
    @elseif (isset($articles))

        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Manajemen Artikel</h2>
        </div>

        <p class="text-gray-400 light:text-gray-600">Kelola semua konten, dari draf hingga artikel yang diarsipkan.</p>

        <div class="flex space-x-4">
            {{-- Tombol Counter --}}
            <button class="px-4 py-2 text-cream-text bg-sienna/50 rounded-lg font-semibold light:bg-gray-200 light:text-light-text">Semua ({{ $totalArticles }})</button>
            <button class="px-4 py-2 text-cream-text bg-sawit-green/40 rounded-lg font-semibold light:bg-sawit-green/20 light:text-sawit-green">Diterbitkan ({{ $publishedCount }})</button>
            <button class="px-4 py-2 text-cream-text bg-terracotta/40 rounded-lg font-semibold light:bg-terracotta/20 light:text-terracotta">Draf ({{ $draftCount }})</button>
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
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-cream-text light:text-light-text">{{ Str::limit($article->title, 50) }}</div>
                                        <div class="text-xs text-gray-500 light:text-gray-400 mt-1">
                                            Dibuat: {{ $article->created_at->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">
                                        <span class="font-semibold text-cream-text light:text-light-text">{{ $article->author->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-terracotta/20 text-terracotta light:bg-terracotta/10">
                                                {{ $article->category->name ?? 'Tidak Ada' }}
                                        </span>
                                    </td>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('editor.articles.edit', $article->id) }}"
                                        class="text-terracotta hover:text-sienna transition font-medium inline-flex items-center" title="Edit Artikel">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
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
                <div class="mt-6">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

    @else
        {{-- Jika tidak ada variabel yang dikirim (misalnya, error rute atau variabel kosong) --}}
        <h1 class="text-2xl font-extrabold text-red-500">ERROR: Variabel $articles atau $article tidak ditemukan.</h1>
    @endif
</div>
@endsection
