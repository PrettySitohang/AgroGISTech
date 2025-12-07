@extends('editor.layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Article Review Queue</h2>
    <p class="text-gray-400 light:text-gray-600">Daftar artikel draf dan artikel yang sedang dalam proses penyuntingan oleh Anda.</p>

    {{-- Area Search --}}
    <form method="GET" action="{{ route('editor.reviews.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
        <input type="text" name="search" placeholder="Cari judul artikel..."
               class="flex-grow p-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text"
               value="{{ request('search') }}">
        <button type="submit" class="p-2 bg-terracotta text-bg-dark rounded-lg font-bold hover:bg-sienna transition duration-150 flex-shrink-0">
            <i class="fas fa-search mr-1"></i> Search
        </button>
    </form>

    {{-- Tabel Antrian --}}
    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl overflow-hidden border border-sienna/50 light:border-gray-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sienna/30 light:divide-gray-200">
                <thead class="bg-sienna/20 light:bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Article Title</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Writer</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sienna/30 light:divide-gray-200">
                    @forelse ($articles as $article)
                        <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
                            {{-- TITLE --}}
                            <td class="px-6 py-4 text-sm font-medium text-cream-text light:text-light-text max-w-xs truncate">{{ $article->title }}</td>

                            {{-- WRITER --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">
                                {{ $article->author->name ?? 'N/A' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $status_color = '';
                                    switch ($article->status) {
                                        case 'draft':
                                            $status_color = 'bg-terracotta/40 text-cream-text light:bg-terracotta/20 light:text-terracotta';
                                            break;
                                        case 'review':
                                            $status_color = 'bg-blue-600/40 text-cream-text light:bg-blue-100/40 light:text-blue-600';
                                            break;
                                        case 'published':
                                            $status_color = 'bg-green-600/40 text-cream-text light:bg-green-100/40 light:text-green-600';
                                            break;
                                        default:
                                            $status_color = 'bg-yellow-600/40 text-cream-text light:bg-yellow-100/40 light:text-yellow-600';
                                    }
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $status_color }}">
                                    {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                                </span>
                            </td>

                            {{-- ACTION --}}
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2 flex justify-end items-center">

                                @if ($article->status == 'draft' && !$article->editor_id)
                                    {{-- Tombol Klaim (Jika masih draft dan belum ada editor_id) --}}
                                    <form action="{{ route('editor.reviews.claim', $article) }}" method="POST" class="inline" onsubmit="return confirm('Klaim artikel ini untuk diedit? Status akan berubah menjadi In Review.');">
                                        @csrf
                                        <button type="submit" class="text-terracotta hover:text-sienna transition" title="Klaim & Edit">
                                            <i class="fas fa-hand-paper"></i> Klaim & Mulai
                                        </button>
                                    </form>
                                @elseif ($article->status == 'review' && !$article->editor_id)
                                    {{-- Tombol Klaim (Jika review status tapi belum ada editor) --}}
                                    <form action="{{ route('editor.reviews.claim', $article) }}" method="POST" class="inline" onsubmit="return confirm('Klaim artikel ini untuk diedit?');">
                                        @csrf
                                        <button type="submit" class="text-terracotta hover:text-sienna transition" title="Klaim & Edit">
                                            <i class="fas fa-hand-paper"></i> Klaim & Mulai
                                        </button>
                                    </form>
                                @elseif ($article->status == 'review' && $article->editor_id == Auth::id())
                                    {{-- Tombol Lanjutkan Edit (Jika sudah diklaim oleh Anda) --}}
                                    <a href="{{ route('editor.articles.edit', $article) }}" class="text-sawit-green hover:text-green-700 transition" title="Lanjutkan Edit">
                                        <i class="fas fa-edit"></i> Lanjutkan Edit
                                    </a>
                                @elseif ($article->status == 'review' && $article->editor_id && $article->editor_id != Auth::id())
                                    {{-- Diklaim Editor Lain --}}
                                    <span class="text-gray-500 dark:text-gray-600 italic">Diklaim Editor Lain</span>
                                @endif

                                {{-- Tombol Delete dan Publish dihilangkan dari sini --}}

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">There are no articles in the review queue.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-6">
            {{ $articles->links() }}
        </div>
    </div>
</div>
@endsection
