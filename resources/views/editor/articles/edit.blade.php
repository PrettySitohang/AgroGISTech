@extends('layouts.app_dashboard')

@section('page_title', 'Workspace Editorial: ' . $article->title)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Kolom Kiri: Form Edit dan Publikasi -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8 h-full">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">Edit Artikel (Status: {{ ucfirst($article->status) }})</h2>

            <form action="{{ route('editor.articles.update', $article) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Judul -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Artikel</label>
                    <input id="title" name="title" type="text" required value="{{ old('title', $article->title) }}"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                </div>

                <!-- Konten -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Isi Konten Artikel</label>
                    <textarea id="content" name="content" rows="15" required
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror">{{ old('content', $article->content) }}</textarea>
                </div>

                <!-- Catatan Revisi (Wajib) -->
                <div>
                    <label for="review_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Catatan Revisi / Tindakan (<span class="text-red-500">Wajib</span>)</label>
                    <textarea id="review_notes" name="review_notes" rows="3" required placeholder="Tuliskan alasan perubahan, atau tindakan (Publish/Archive/Kirim Balik) di sini."
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('review_notes') border-red-500 @enderror">{{ old('review_notes') }}</textarea>
                </div>

                <!-- Tindakan dan Status -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ubah Status dan Tindakan</label>
                    <select id="status" name="status" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500">
                        <option value="in_review" {{ $article->status === 'in_review' ? 'selected' : '' }}>Simpan Revisi (Dalam Review)</option>
                        <option value="draft" {{ $article->status === 'draft' ? 'selected' : '' }}>Kirim Balik ke Penulis (Ganti Status ke Draf)</option>
                        <option value="published" {{ $article->status === 'published' ? 'selected' : '' }}>Publish Artikel</option>
                        <option value="archived" {{ $article->status === 'archived' ? 'selected' : '' }}>Archive Artikel</option>
                    </select>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition duration-150 shadow-md">
                        <i class="fas fa-paper-plane mr-2"></i> Proses Revisi & Simpan
                    </button>
                </div>
            </form>
        </div>

        <!-- Kolom Kanan: Riwayat Revisi dan Info -->
        <div class="lg:col-span-1 space-y-8">

            <!-- Info Penulis -->
            <div class="bg-gray-50 dark:bg-gray-900 shadow-lg rounded-2xl p-6 border border-amber-500/50">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Info Penulis</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    **Penulis:** <a href="{{ route('editor.user.profile', $article->writer) }}" class="text-amber-600 hover:underline">{{ $article->writer->name ?? 'N/A' }}</a><br>
                    **Email:** {{ $article->writer->email ?? 'N/A' }}<br>
                    **Diajukan:** {{ $article->submitted_at->diffForHumans() ?? 'Belum Diajukan' }}
                </p>
            </div>

            <!-- Riwayat Revisi -->
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Riwayat Revisi ({{ $revisions->count() }})</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse ($revisions as $revision)
                        <div class="p-3 border-l-4 border-gray-400 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 rounded-md">
                            <p class="text-xs text-gray-700 dark:text-gray-300 font-semibold">{{ $revision->created_at->format('d M Y H:i') }} - Oleh {{ $revision->editor->name ?? 'Admin' }}</p>
                            <p class="text-sm italic text-gray-600 dark:text-gray-400 mt-1">
                                **Catatan:** {{ $revision->notes }}
                            </p>
                            <details class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                <summary>Lihat Perubahan Konten Lama</summary>
                                <pre class="mt-2 p-2 bg-gray-100 dark:bg-gray-700 rounded-md whitespace-pre-wrap">{{ Str::limit($revision->content_before, 200) }}</pre>
                            </details>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat revisi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
