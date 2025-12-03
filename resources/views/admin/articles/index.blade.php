@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Manajemen Artikel</h2>
        <a href="{{ route('admin.articles.create') }}"
           class="px-4 py-2 bg-terracotta text-bg-dark rounded-lg font-bold hover:bg-sienna/80 transition shadow-md shadow-terracotta/40">
            <i class="fas fa-pen-nib mr-2"></i> Buat Artikel Baru
        </a>
    </div>

    <p class="text-gray-400 light:text-gray-600">Kelola semua konten, dari draf hingga artikel yang diarsipkan.</p>

    {{-- Filter Status --}}
    <div class="flex space-x-4">
        <button class="px-4 py-2 text-cream-text bg-sienna/50 rounded-lg font-semibold light:bg-gray-200 light:text-light-text">Semua (120)</button>
        <button class="px-4 py-2 text-cream-text bg-sawit-green/40 rounded-lg font-semibold light:bg-sawit-green/20 light:text-sawit-green">Diterbitkan (90)</button>
        <button class="px-4 py-2 text-cream-text bg-terracotta/40 rounded-lg font-semibold light:bg-terracotta/20 light:text-terracotta">Draf (25)</button>
        <button class="px-4 py-2 text-cream-text bg-gray-500/40 rounded-lg font-semibold light:bg-gray-200 light:text-gray-600">Diarsipkan (5)</button>
    </div>

    {{-- Tabel Daftar Artikel --}}
    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl overflow-hidden border border-sienna/50 light:border-gray-200">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Daftar Artikel</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sienna/30 light:divide-gray-200">
                    <thead class="bg-sienna/20 light:bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Judul</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Penulis</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sienna/30 light:divide-gray-200">
                        {{-- Contoh Artikel --}}
                        @php
                            $articles = [
                                ['id' => 1, 'title' => 'IoT Monitoring Lahan Sawit', 'author' => 'Suci A.', 'category' => 'IoT', 'status' => 'published'],
                                ['id' => 2, 'title' => 'Riset Genomik Bibit Unggul', 'author' => 'Kurnia S.', 'category' => 'Biotek', 'status' => 'draft'],
                            ];
                        @endphp
                        @foreach ($articles as $article)
                            <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-cream-text light:text-light-text">{{ Str::limit($article['title'], 40) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">{{ $article['author'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">{{ $article['category'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                 @if($article['status'] == 'published') bg-sawit-green/40 text-cream-text
                                                 @else bg-terracotta/40 text-cream-text @endif">
                                        {{ ucfirst($article['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.articles.edit', $article['id']) }}" class="text-terracotta hover:text-sienna transition light:text-terracotta light:hover:text-sienna">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            <i class="fas fa-trash-alt"></i>
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
