@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
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
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Daftar Artikel</h3>

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
                                    {{-- Menggunakan relasi 'author' --}}
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
                                    {{-- Tautan Edit --}}
                                    <a href="{{ route('admin.articles.edit', $article->id) }}" class="text-terracotta hover:text-sienna transition" title="Edit Artikel">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    {{-- Tautan Hapus (Force Delete) --}}
                                    <form action="{{ route('admin.articles.delete.force', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini permanen (Force Delete).');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus Permanen">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 light:text-gray-400">
                                    Belum ada artikel yang tersedia.
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
</div>
@endsection
