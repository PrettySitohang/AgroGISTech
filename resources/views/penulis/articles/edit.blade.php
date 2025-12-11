@extends('layouts.app_dashboard')

@section('page_title', 'Edit Artikel')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Edit Artikel</h2>

        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                <strong>Error:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('penulis.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Judul -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Artikel</label>
                <input id="title" name="title" type="text" required value="{{ old('title', $article->title) }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('title') border-red-500 @enderror">
                @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Isi Konten -->
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Isi Konten Artikel</label>
                <textarea id="content" name="content" rows="15" required
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('content') border-red-500 @enderror">{{ old('content', $article->content) }}</textarea>
                @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Cover Image -->
            <div>
                <label for="cover_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sampul Artikel (Gambar)</label>
                @if($article->cover_image)
                    <div class="mt-2 mb-4">
                        <img src="{{ asset($article->cover_image) }}" alt="Cover" class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600">
                    </div>
                @endif
                <input id="cover_image" name="cover_image" type="file" accept="image/*"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 cursor-pointer @error('cover_image') border-red-500 @enderror">
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPG, PNG, GIF (Max 4MB). Biarkan kosong jika tidak ingin mengubah.</p>
                @error('cover_image')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('penulis.articles.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">Batal</a>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition duration-150 shadow-md">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
