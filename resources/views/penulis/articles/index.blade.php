@extends('layouts.app_dashboard')

@section('page_title', 'Draf Artikel Saya')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Daftar Draf dan Artikel yang Diajukan</h2>
            <a href="{{ route('penulis.articles.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-150 shadow-md">
                <i class="fas fa-plus mr-2"></i> Buat Artikel Baru
            </a>
        </div>

        <!-- Area Search dan Filter Status -->
        <form method="GET" action="{{ route('penulis.articles.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" placeholder="Cari judul artikel..."
                   class="flex-grow p-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500"
                   value="{{ request('search') }}">

            <select name="status" class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
                <option value="all">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draf</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="in_review" {{ request('status') == 'in_review' ? 'selected' : '' }}>Dalam Review</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
            </select>

            <button type="submit" class="p-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition duration-150 flex-shrink-0">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>

        <!-- Tabel Artikel -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Terakhir Diubah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($articles as $article)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate">{{ $article->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status_color = match($article->status) {
                                        'draft' => 'bg-gray-100 text-gray-800',
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_review' => 'bg-blue-100 text-blue-800',
                                        'published' => 'bg-green-100 text-green-800',
                                        default => 'bg-red-100 text-red-800',
                                    };
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status_color }} dark:bg-opacity-20 dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $article->updated_at->format('d M Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">

                                @if ($article->status === 'draft')
                                    <a href="{{ route('penulis.articles.edit', $article) }}" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="{{ route('penulis.articles.submit', $article) }}" method="POST" class="inline" onsubmit="return confirm('Ajukan artikel untuk di-review?');">
                                        @csrf
                                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <i class="fas fa-paper-plane"></i> Ajukan
                                        </button>
                                    </form>

                                    <form action="{{ route('penulis.articles.delete', $article) }}" method="POST" class="inline" onsubmit="return confirm('Hapus draf ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-400 dark:text-gray-600 italic">Terkunci</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Anda belum memiliki draf artikel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
