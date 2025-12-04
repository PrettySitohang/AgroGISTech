@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Sukses!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('info'))
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Informasi:</strong>
            <span class="block sm:inline">{{ session('info') }}</span>
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

    <div class="flex border-b border-sienna/50 light:border-gray-300 mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="px-6 py-2 text-terracotta font-bold border-b-2 border-terracotta -mb-px bg-sienna/10 light:bg-gray-100 transition duration-300">
            <i class="fas fa-folder-open mr-2"></i> Kategori
        </a>

        <a href="{{ route('admin.tags.index') }}"
           class="px-6 py-2 text-gray-500 font-semibold hover:text-terracotta transition border-b-2 border-transparent hover:border-sienna/50">
            <i class="fas fa-tag mr-2"></i> Tag
        </a>
    </div>

    <div class="bg-bg-dark light:bg-white rounded-xl p-6 shadow-xl border border-sienna/50 light:border-gray-200 mb-6">
        <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Tambah Kategori Baru</h3>

        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 items-end">
            @csrf
            <div class="flex-grow w-full">
                <label for="new_category_name" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Nama Kategori</label>
                <input type="text" id="new_category_name" name="name" required placeholder="Contoh: Agronomi Presisi"
                        class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text">
            </div>
            <button type="submit" class="w-full md:w-auto px-4 py-2 bg-terracotta text-bg-dark rounded-lg font-bold hover:bg-sienna transition shadow-md shadow-terracotta/40">
                <i class="fas fa-save mr-1"></i> Simpan
            </button>
        </form>
    </div>

    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl overflow-hidden border border-sienna/50 light:border-gray-200">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Kategori Tersedia</h3>

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
                        @foreach ($categories as $category)
                            <tr id="category-row-{{ $category->category_id }}" class="hover:bg-sienna/10 light:hover:bg-gray-50 transition-colors">

                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-cream-text light:text-light-text">
                                    {{ $category->category_id }}
                                </td>

                                <td class="px-6 py-4">
                                    <input type="text"
                                           data-category-id="{{ $category->category_id }}"
                                           data-original-name="{{ $category->name }}"
                                           value="{{ $category->name }}"
                                           class="editable-category-name bg-transparent border border-transparent focus:border-terracotta focus:ring-terracotta light:text-light-text text-cream-text w-full p-1 -m-1 rounded-md transition duration-150"
                                           onchange="handleCategoryUpdate(this)">
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">
                                    {{ $category->articles_count ?? 0 }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button type="button"
                                            onclick="focusCategoryInput({{ $category->category_id }})"
                                            class="text-terracotta hover:text-sienna transition cursor-pointer bg-transparent border-none p-0 inline-flex items-center">
                                         <i class="fas fa-edit mr-1"></i> Edit
                                    </button>

                                    <form action="{{ route('admin.categories.delete', $category->category_id) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('Hapus kategori {{ $category->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition ml-2">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </button>
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<script>
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    function focusCategoryInput(categoryId) {
        const input = document.querySelector(`input[data-category-id="${categoryId}"]`);
        if (input) {
            input.focus();
            input.select();
        }
    }

    function handleCategoryUpdate(inputElement) {
        const categoryId = inputElement.dataset.categoryId;
        const newName = inputElement.value.trim();
        const originalName = inputElement.dataset.originalName;

        if (!csrfToken) {
            alert('Error: CSRF Token tidak ditemukan.');
            inputElement.value = originalName;
            return;
        }

        if (newName === originalName) return;
        if (newName === '') {
            alert('Nama kategori tidak boleh kosong!');
            inputElement.value = originalName;
            return;
        }

        const url = `{{ url('admin/masters/categories') }}/${categoryId}`;
        const row = document.getElementById(`category-row-${categoryId}`);

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                name: newName,
                _method: 'PUT'
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
            if (row) {
                row.classList.add('bg-green-700/30');
                setTimeout(() => row.classList.remove('bg-green-700/30'), 1500);
            }
        })
        .catch(error => {
            alert(`Gagal Update: ${error.message}`);
            inputElement.value = originalName;
            if (row) {
                row.classList.add('bg-red-700/30');
                setTimeout(() => row.classList.remove('bg-red-700/30'), 2000);
            }
        });
    }
</script>
@endsection
