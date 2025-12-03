@extends('layouts.app_dashboard')

@section('page_title', 'Antrian Review Artikel')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Artikel Menunggu dan Dalam Review</h2>

        <!-- Area Search -->
        <form method="GET" action="{{ route('editor.reviews.index') }}" class="flex gap-3 mb-6">
            <input type="text" name="search" placeholder="Cari judul artikel..."
                   class="flex-grow p-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500"
                   value="{{ request('search') }}">
            <button type="submit" class="p-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition duration-150 flex-shrink-0">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>

        <!-- Tabel Antrian -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Judul Artikel</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Penulis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($articles as $article)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white max-w-xs truncate">{{ $article->title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $article->writer->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status_color = $article->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status_color }} dark:bg-opacity-20 dark:text-white">
                                    {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if ($article->status === 'pending')
                                    <form action="{{ route('editor.reviews.claim', $article) }}" method="POST" class="inline" onsubmit="return confirm('Klaim artikel ini untuk diedit?');">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <i class="fas fa-hand-paper"></i> Klaim & Edit
                                        </button>
                                    </form>
                                @elseif ($article->editor_id === Auth::id() && $article->status === 'in_review')
                                    <a href="{{ route('editor.articles.edit', $article) }}" class="text-amber-600 hover:text-amber-900 dark:text-amber-400 dark:hover:text-amber-300">
                                        <i class="fas fa-edit"></i> Lanjutkan Edit
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-600 italic">Diklaim Editor Lain</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Tidak ada artikel di antrian review.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $articles->links() }}
        </div>
    </div>
@endsection
