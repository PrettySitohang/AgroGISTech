@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Edit Artikel</h2>
        <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 bg-sienna text-cream-text rounded-lg hover:bg-terracotta transition">
            Kembali
        </a>
    </div>

    {{-- Pesan Sesi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            <strong>Sukses!</strong> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <strong>Error Validasi!</strong>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Edit Artikel --}}
    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl p-6 border border-sienna/50 light:border-gray-200">
        <form action="{{ route('admin.articles.update', $article->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div class="mb-6">
                <label class="block text-cream-text light:text-light-text font-semibold mb-2">Judul Artikel</label>
                <input type="text" name="title" value="{{ old('title', $article->title) }}" class="w-full p-3 border border-sienna/70 rounded-lg bg-bg-dark light:bg-white text-cream-text light:text-light-text" required>
            </div>

            {{-- Konten --}}
            <div class="mb-6">
                <label class="block text-cream-text light:text-light-text font-semibold mb-2">Konten Artikel</label>
                <textarea name="content" rows="15" class="w-full p-3 border border-sienna/70 rounded-lg bg-bg-dark light:bg-white text-cream-text light:text-light-text" required>{{ old('content', $article->content) }}</textarea>
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label class="block text-cream-text light:text-light-text font-semibold mb-2">Status</label>
                <select name="status" class="w-full p-3 border border-sienna/70 rounded-lg bg-bg-dark light:bg-white text-cream-text light:text-light-text" required>
                    <option value="draft" {{ old('status', $article->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="review" {{ old('status', $article->status) === 'review' ? 'selected' : '' }}>Review</option>
                    <option value="published" {{ old('status', $article->status) === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            {{-- Info Artikel --}}
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-sienna/10 p-4 rounded-lg">
                    <p class="text-sm text-cream-text/70">Penulis</p>
                    <p class="text-lg font-semibold text-cream-text">{{ $article->author->name }}</p>
                </div>
                <div class="bg-sienna/10 p-4 rounded-lg">
                    <p class="text-sm text-cream-text/70">Editor</p>
                    <p class="text-lg font-semibold text-cream-text">{{ $article->editor?->name ?? 'Belum ada' }}</p>
                </div>
                <div class="bg-sienna/10 p-4 rounded-lg">
                    <p class="text-sm text-cream-text/70">Dibuat</p>
                    <p class="text-lg font-semibold text-cream-text">{{ $article->created_at->format('d M Y') }}</p>
                </div>
                <div class="bg-sienna/10 p-4 rounded-lg">
                    <p class="text-sm text-cream-text/70">Diubah</p>
                    <p class="text-lg font-semibold text-cream-text">{{ $article->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-4">
                <button type="submit" class="px-6 py-3 bg-sawit-green text-cream-text rounded-lg font-semibold hover:bg-green-700 transition">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.articles.index') }}" class="px-6 py-3 bg-gray-500 text-cream-text rounded-lg font-semibold hover:bg-gray-600 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
