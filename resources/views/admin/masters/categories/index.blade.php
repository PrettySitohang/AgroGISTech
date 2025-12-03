@extends('layouts.app_dashboard')

@section('page_title', 'Data Master Kategori')

@section('content')
    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-6">Pengelolaan Kategori Artikel</h2>

        <!-- Form Tambah Kategori -->
        <form action="{{ route('admin.categories.store') }}" method="POST" class="flex gap-3 mb-8 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
            @csrf
            <input type="text" name="name" placeholder="Nama Kategori Baru (misal: Panen)" required
                   class="flex-grow p-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-800 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror">

            <button type="submit" class="p-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700 transition duration-150 flex-shrink-0">
                <i class="fas fa-plus"></i> Tambah
            </button>
            @error('name')<p class="mt-2 text-sm text-red-600 w-full">{{ $message }}</p>@enderror
        </form>

        <!-- Daftar Kategori -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Kategori</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($categories as $category)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $category->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="flex items-center space-x-3">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $category->name }}" required
                                           class="p-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 text-sm">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">Update</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <form action="{{ route('admin.categories.delete', $category) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini? Semua artikel yang terkait akan terpengaruh.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
