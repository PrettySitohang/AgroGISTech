@extends('layouts.app')

@section('title', $article->title)

@section('content')
<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 min-h-screen bg-bg-dark">
    {{-- Breadcrumb Navigation --}}
    <div class="mb-8 flex items-center gap-2 text-sm text-gray-400">
        <a href="{{ route('public.index') }}" class="hover:text-terracotta transition">Beranda</a>
        <span>/</span>
        <span class="text-terracotta">{{ $article->title }}</span>
    </div>

    {{-- Article Header --}}
    <article class="bg-bg-dark rounded-2xl overflow-hidden border border-sienna/40">
        {{-- Cover Image --}}
        @if($article->cover_image)
            <div class="relative overflow-hidden h-96">
                <img src="{{ asset($article->cover_image) }}"
                    alt="{{ $article->title }}"
                    class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-bg-dark via-transparent to-transparent"></div>
            </div>
        @endif

        {{-- Article Content --}}
        <div class="p-8 lg:p-12">
            {{-- Category Badge --}}
            @if($article->category)
                <span class="inline-block px-4 py-1 mb-4 text-xs font-extrabold uppercase tracking-widest bg-sienna/20 text-terracotta rounded-full">
                    {{ $article->category->name }}
                </span>
            @endif

            {{-- Article Title --}}
            <h1 class="text-4xl lg:text-5xl font-extrabold text-cream-text mb-4 leading-tight">
                {{ $article->title }}
            </h1>

            {{-- Article Meta --}}
            <div class="flex flex-wrap items-center gap-6 mb-8 pb-8 border-b border-sienna/30">
                {{-- Author Info --}}
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-12 w-12 rounded-full bg-terracotta/20">
                            <i class="fas fa-user-circle text-terracotta text-xl"></i>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Oleh</p>
                        <p class="text-cream-text font-semibold">{{ $article->author->name ?? 'Anonim' }}</p>
                    </div>
                </div>

                {{-- Publish Date --}}
                <div class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-calendar-alt text-terracotta"></i>
                    <span>{{ $article->published_at?->format('d M Y') ?? '-' }}</span>
                </div>

                {{-- Status --}}
                <div class="ml-auto">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full
                        @if($article->status === 'published') bg-green-900/30 text-green-400
                        @elseif($article->status === 'review') bg-yellow-900/30 text-yellow-400
                        @else bg-gray-900/30 text-gray-400 @endif">
                        {{ ucfirst(str_replace('_', ' ', $article->status)) }}
                    </span>
                </div>
            </div>

            {{-- Article Body --}}
            <div class="prose prose-invert max-w-none mb-12">
                <div class="text-cream-text/90 leading-relaxed text-lg space-y-6">
                    {!! nl2br(e($article->content)) !!}
                </div>
            </div>

            {{-- Tags --}}
            @if($article->tags->count() > 0)
                <div class="pt-8 border-t border-sienna/30">
                    <h3 class="text-sm font-semibold text-gray-400 mb-3">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($article->tags as $tag)
                            <span class="px-3 py-1 text-sm bg-sienna/20 text-sienna rounded-full hover:bg-sienna/30 transition cursor-pointer">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Article Info Section --}}
            <div class="mt-12 pt-8 border-t border-sienna/30 grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Status Info --}}
                <div class="p-4 bg-sienna/10 rounded-lg border border-sienna/30">
                    <p class="text-xs text-gray-400 mb-1">Status Publikasi</p>
                    <p class="text-cream-text font-semibold">{{ ucfirst(str_replace('_', ' ', $article->status)) }}</p>
                </div>

                {{-- Editor Info --}}
                @if($article->editor)
                    <div class="p-4 bg-sienna/10 rounded-lg border border-sienna/30">
                        <p class="text-xs text-gray-400 mb-1">Diedit oleh</p>
                        <p class="text-cream-text font-semibold">{{ $article->editor->name }}</p>
                    </div>
                @endif

                {{-- Last Updated --}}
                <div class="p-4 bg-sienna/10 rounded-lg border border-sienna/30">
                    <p class="text-xs text-gray-400 mb-1">Diperbarui</p>
                    <p class="text-cream-text font-semibold">{{ $article->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </article>

    {{-- Back to Articles Button --}}
    <div class="mt-12 flex justify-center">
        <a href="{{ route('public.index') }}"
            class="px-8 py-3 bg-terracotta text-bg-dark rounded-lg font-semibold hover:bg-sienna transition duration-300 shadow-lg shadow-terracotta/30">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
        </a>
    </div>

    {{-- Related Articles Section (Optional) --}}
    @if($relatedArticles->count() > 0)
        <div class="mt-20 pt-12 border-t border-sienna/30">
            <h2 class="text-3xl font-bold text-cream-text mb-8">Artikel Terkait</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedArticles->take(3) as $related)
                    <a href="{{ route('public.show', $related->slug) }}"
                        class="group block bg-bg-dark rounded-xl overflow-hidden transition duration-300 border border-sienna/40 hover:border-terracotta/80 hover:scale-[1.02]">
                        @if($related->cover_image)
                            <div class="relative overflow-hidden h-40">
                                <img src="{{ asset($related->cover_image) }}"
                                    alt="{{ $related->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-cream-text group-hover:text-terracotta transition line-clamp-2">
                                {{ $related->title }}
                            </h3>
                            <p class="text-sm text-gray-400 mt-2 line-clamp-2">
                                {{ Str::limit(strip_tags($related->content), 80) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</main>
@endsection
