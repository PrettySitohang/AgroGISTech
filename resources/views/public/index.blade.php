@extends('layouts.app')

@section('content')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-gray-50 dark:bg-gray-900 transition-colors duration-500">
    <div class="text-center mb-20">
        <h1 class="text-6xl font-extrabold text-gray-900 dark:text-gray-50 mb-6 leading-tight">
            Inovasi dan Teknologi <span class="text-teal-600 dark:text-teal-400">Kelapa Sawit</span>
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-10 max-w-4xl mx-auto">
            Temukan artikel terbaru dan riset seputar teknologi perkebunan kelapa sawit.
        </p>
    </div>

    <div class="mb-16 p-6 rounded-2xl bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700">
        <form method="GET" action="{{ route('public.index') }}" class="flex flex-col md:flex-row gap-4 items-center">
            <input type="text" name="search" placeholder="Cari judul, kata kunci, atau penulis..."
                class="flex-grow p-4 border border-gray-300 dark:border-gray-700 rounded-xl focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-gray-100"
                value="{{ request('search') }}">

            <select name="category"
                class="p-4 border border-gray-300 dark:border-gray-700 rounded-xl dark:bg-gray-700 dark:text-gray-100 w-full md:w-auto">
                <option value="">Semua Kategori</option>
                <option value="budidaya" {{ request('category') == 'budidaya' ? 'selected' : '' }}>Budidaya</option>
                <option value="pengolahan" {{ request('category') == 'pengolahan' ? 'selected' : '' }}>Pengolahan</option>
            </select>

            <button type="submit"
                class="p-4 bg-teal-600 text-white rounded-xl font-semibold hover:bg-teal-700 transition w-full md:w-auto">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">

        @forelse ($articles as $article)
            <a href="{{ route('public.show', $article->slug) }}"
                class="block bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden hover:scale-[1.03] transition duration-500 border border-gray-100 dark:border-gray-700">

                <img src="{{ $article->cover_image }}"
                     onerror="this.src='https://placehold.co/600x400/0f766e/ffffff?text=No+Image';"
                     alt="{{ $article->title }}"
                     class="w-full h-52 object-cover">

                <div class="p-6">
                    <span class="text-sm font-bold uppercase tracking-widest text-teal-600 dark:text-teal-400">
                        {{ $article->category ?? 'Umum' }}
                    </span>

                    <h2 class="mt-3 text-2xl font-bold text-gray-900 dark:text-gray-50 line-clamp-2">
                        {{ $article->title }}
                    </h2>

                    <p class="mt-4 text-gray-600 dark:text-gray-400 text-base line-clamp-3">
                        {{ Str::limit(strip_tags($article->content), 150) }}
                    </p>

                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700 flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center">
                            <i class="fas fa-user-circle mr-2 text-teal-500"></i>
                            {{ $article->author->name ?? 'Tidak diketahui' }}
                        </span>
                        <span>
                            <i class="fas fa-calendar-alt mr-1 text-teal-500"></i>
                            {{ $article->published_at?->format('d M Y') ?? '-' }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <p class="text-center text-gray-600 dark:text-gray-300 text-lg col-span-3">
                Tidak ada artikel ditemukan.
            </p>
        @endforelse

    </div>

    {{-- PAGINATION --}}
    <div class="mt-16 flex justify-center">
        {{ $articles->links() }}
    </div>

</main>

@endsection
