@extends('editor.layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Pesan Sesi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Error Validasi!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Manajemen Data Master</h2>
    <p class="text-gray-400 light:text-gray-600">Kelola kategori dan tag yang digunakan untuk mengelompokkan artikel.</p>

    {{-- Navigasi Tab --}}
    <div class="flex border-b border-sienna/50 light:border-gray-300 mb-6">
        <a href="{{ route('editor.categories.index') }}" {{-- <--- PERBAIKAN ROUTE --}}
           class="px-6 py-2 text-terracotta font-bold border-b-2 border-terracotta -mb-px bg-sienna/10 light:bg-gray-100 transition duration-300">
            <i class="fas fa-folder-open mr-2"></i> Kategori
        </a>

        <a href="{{ route('editor.tags.index') }}" {{-- <--- PERBAIKAN ROUTE --}}
           class="px-6 py-2 text-gray-500 font-semibold hover:text-terracotta transition border-b-2 border-transparent hover:border-sienna/50">
            <i class="fas fa-tag mr-2"></i> Tag
        </a>
    </div>

    {{-- Form Tambah Kategori Baru --}}
    <div class="bg-bg-dark light:bg-white rounded-xl p-6 shadow-xl border border-sienna/50 light:border-gray-200 mb-6">
        <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Tambah Kategori Baru</h3>

        <form action="{{ route('editor.categories.store') }}" method="POST" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-end">
            @csrf
            <div class="flex-grow w-full">
                <label for="new_category_name" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Nama Kategori</label>
                <input type="text" id="new_category_name" name="name" required placeholder="Contoh: Agronomi Presisi"
                        class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text">
                {{-- Tampilkan error khusus nama --}}
                @error('name')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-terracotta text-bg-dark rounded-lg font-bold hover:bg-sienna transition shadow-md shadow-terracotta/40">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </form>
    </div>

    {{-- Tabel Kategori Tersedia --}}
    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl overflow-hidden border border-sienna/50 light:border-gray-200">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Kategori Tersedia ({{ $categories->total() }})</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sienna/30 light:divide-gray-200">
                    <thead class="bg-sienna/20 light:bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider w-1/12">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Nama Kategori</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Jumlah Artikel</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider w-2/12">Aksi</th>
                        </tr>
                    </thead>

<tbody class="divide-y divide-sienna/30 light:divide-gray-200">
    @forelse ($categories as $category)
        <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
            {{-- ID --}}
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-cream-text light:text-light-text">
                {{ $category->category_id ?? $category->id ?? '-' }}
            </td>

            {{-- Nama --}}
            <td class="px-6 py-4 whitespace-nowrap text-sm text-cream-text light:text-light-text">
                {{ $category->name }}
            </td>

            {{-- Jumlah Artikel (aman: tangani bila relasi/pivot belum tersedia) --}}
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">
                @php
                    $articleCount = 0;
                    try {
                        if (method_exists($category, 'articles')) {
                            $articleCount = $category->articles()->count();
                        } else {
                            $articleCount = 0;
                        }
                    } catch (\Throwable $e) {
                        // Jika query gagal (mis. pivot table tidak ada), tampilkan 0
                        $articleCount = 0;
                    }
                @endphp
                {{ $articleCount }}
            </td>

            {{-- Aksi --}}
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                {{-- Edit --}}
                <a href="{{ route('editor.categories.edit', $category->category_id ?? $category->id) }}"
                   class="text-terracotta hover:text-sienna transition light:text-terracotta light:hover:text-sienna">
                    <i class="fas fa-edit"></i> Edit
                </a>

                {{-- Delete / Wipe --}}
                <form action="{{ route('editor.categories.destroy', $category->category_id ?? $category->id) }}"
                      method="POST"
                      class="inline-block"
                      onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 hover:text-red-700">
                        <i class="fas fa-trash"></i> Wipe
                    </button>
                </form>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                Tidak ada kategori yang ditemukan.
            </td>
        </tr>
    @endforelse
</tbody>

                </table>
            </div>
             {{-- Pagination --}}
            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

</div>

{{-- Skrip JavaScript untuk Update Inline --}}
<script>
    // Asumsi Anda memiliki meta tag CSRF token di layout utama
    const csrfToken = document.head.querySelector('meta[name="csrf-token"]') ?
                      document.head.querySelector('meta[name="csrf-token"]').content :
                      '';

    function focusCategoryInput(categoryId) {
        const input = document.querySelector(input[data-category-id="${categoryId}"]);
        if (input) {
            input.focus();
            input.select();
        }
    }

    function handleCategoryUpdate(inputElement) {
        // PERBAIKAN: Menggunakan $category->id
        const categoryId = inputElement.dataset.categoryId;
        const newName = inputElement.value.trim();
        const originalName = inputElement.dataset.originalName;

        if (!csrfToken) {
            alert('Error: CSRF Token tidak ditemukan. Tidak dapat menyimpan.');
            inputElement.value = originalName;
            return;
        }

        if (newName === originalName) return;
        if (newName === '') {
            alert('Nama kategori tidak boleh kosong!');
            inputElement.value = originalName;
            return;
        }

        // PERBAIKAN URL: Menggunakan route helper untuk API endpoint
        const url = {{ route('editor.categories.update', ['category' => '___ID___']) }}.replace('ID', categoryId);

        const row = document.getElementById(category-row-${categoryId});

        // Visual Feedback (Opsional)
        if (row) row.classList.add('opacity-50', 'pointer-events-none');

        fetch(url, {
            method: 'POST', // Menggunakan POST dan menimpa dengan _method:PUT
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: newName,
                _method: 'PUT' // Menggunakan PUT untuk route update
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    let message = data.message || 'Gagal memperbarui kategori.';
                    if (data.errors?.name) message = data.errors.name[0];
                    throw new Error(message);
                });
            }
            return response.json();
        })
        .then(data => {
            inputElement.dataset.originalName = newName;
            // Visual feedback sukses
            if (row) {
                row.classList.remove('opacity-50', 'pointer-events-none');
                row.classList.add('bg-sawit-green/30');
                setTimeout(() => row.classList.remove('bg-sawit-green/30'), 1500);
            }
        })
        .catch(error => {
            alert(Gagal Update: ${error.message});
            inputElement.value = originalName; // Rollback
            // Visual feedback gagal
            if (row) {
                row.classList.remove('opacity-50', 'pointer-events-none');
                row.classList.add('bg-red-700/30');
                setTimeout(() => row.classList.remove('bg-red-700/30'), 2000);
            }
        });
    }
</script>
@endsection
