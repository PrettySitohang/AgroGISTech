@extends('layouts.app')

@section('title', 'Teknologi')

@section('content')
    <h1 class="text-3xl font-extrabold mb-4">Teknologi</h1>

    @if($category)
        <p class="text-sm text-gray-400 mb-4">Menampilkan artikel pada kategori: <strong>{{ $category->name }}</strong></p>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($articles as $article)
            <article class="bg-bg-dark/80 p-4 rounded">
                <h2 class="text-xl font-semibold"><a href="{{ route('public.show', $article) }}">{{ $article->title }}</a></h2>
                <p class="text-sm text-gray-400 mt-2">{{ Str::limit(strip_tags($article->content ?? ''), 150) }}</p>
                <div class="mt-3 text-xs text-gray-500">{{ $article->created_at->format('d M Y') }} — {{ $article->author->name ?? 'N/A' }}</div>
            </article>
        @empty
            <p>Tidak ada artikel teknologi.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $articles->links() }}</div>
@endsection
