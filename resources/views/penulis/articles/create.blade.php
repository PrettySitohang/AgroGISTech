@extends('layouts.app_dashboard')

@section('page_title', 'Buat Artikel Baru')

@section('content')
    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Formulir Artikel Baru</h2>

        <form action="{{ route('penulis.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Judul -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Artikel</label>
                <input id="title" name="title" type="text" required value="{{ old('title') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('title') border-red-500 @enderror">
                @error('title')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Pilihan Input: Ketik atau Upload Dokumen -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Sumber Konten</label>
                <div class="flex gap-4 mb-4">
                    <label class="flex items-center">
                        <input type="radio" name="content_source" value="manual" checked class="w-4 h-4 text-amber-600">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Ketik Konten Manual</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="content_source" value="upload" class="w-4 h-4 text-amber-600">
                        <span class="ml-2 text-gray-700 dark:text-gray-300">Upload Dokumen (.docx, .pdf, .txt)</span>
                    </label>
                </div>
            </div>

            <!-- Konten Manual (Textarea) -->
            <div id="manual-content" class="block">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Isi Konten Artikel</label>
                <textarea id="content" name="content" rows="15"
                          class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Upload Dokumen -->
            <div id="upload-content" class="hidden">
                <label for="document" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unggah Dokumen</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-amber-500 transition duration-150">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-2-10l-3.172-3.172a2 2 0 00-2.828 0L28 10m0 0V6m0 4h8m-8 12v8m0-8H12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                            <label for="document" class="relative cursor-pointer rounded-md font-medium text-amber-600 hover:text-amber-700 dark:text-amber-400">
                                <span>Pilih file</span>
                                <input id="document" name="document" type="file" accept=".pdf,.docx,.doc,.txt" class="sr-only">
                            </label>
                            <p class="pl-1">atau drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">PDF, DOCX, DOC atau TXT hingga 10MB</p>
                    </div>
                </div>
                @error('document')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Kategori dan Tags (Gunakan Select2 atau multi-select) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kategori -->
                <div>
                    <label for="categories" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Kategori</label>
                    <select id="categories" name="categories[]" multiple
                            class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500" style="min-height: 100px;">
                        {{-- @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', [])) ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach --}}
                        <option value="1">Budidaya</option>
                        <option value="2">Pengolahan</option>
                    </select>
                </div>
                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Tags</label>
                    <select id="tags" name="tags[]" multiple
                            class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500" style="min-height: 100px;">
                        {{-- @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>{{ $tag->name }}</option>
                        @endforeach --}}
                        <option value="10">IoT</option>
                        <option value="11">Drone</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('penulis.articles.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">Batal</a>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition duration-150 shadow-md">
                    <i class="fas fa-save mr-2"></i> Simpan Draf
                </button>
            </div>
        </form>
    </div>

    <script>
        // Toggle between manual and upload content
        const contentSourceRadios = document.querySelectorAll('input[name="content_source"]');
        const manualContentDiv = document.getElementById('manual-content');
        const uploadContentDiv = document.getElementById('upload-content');
        const contentTextarea = document.getElementById('content');

        contentSourceRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'manual') {
                    manualContentDiv.classList.remove('hidden');
                    uploadContentDiv.classList.add('hidden');
                    contentTextarea.removeAttribute('required');
                } else {
                    manualContentDiv.classList.add('hidden');
                    uploadContentDiv.classList.remove('hidden');
                    contentTextarea.removeAttribute('required');
                }
            });
        });

        // Drag and drop for file upload
        const dropZone = document.getElementById('upload-content');
        const fileInput = document.getElementById('document');

        if (dropZone) {
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('border-amber-500', 'bg-amber-50');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('border-amber-500', 'bg-amber-50');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('border-amber-500', 'bg-amber-50');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                }
            });
        }
    </script>
@endsection
