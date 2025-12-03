@extends('layouts.app_dashboard')

@section('page_title', 'Tambah Pengguna Baru')

@section('content')
    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Form Tambah Pengguna</h2>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-500 @enderror">
                @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Kata Sandi -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kata Sandi</label>
                <input id="password" name="password" type="password" required
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-500 @enderror">
                @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Peran (Role) -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Peran Sistem</label>
                <select id="role" name="role" required
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('role') border-red-500 @enderror">
                    <option value="super_admin" {{ old('role') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                    <option value="editor" {{ old('role') == 'editor' ? 'selected' : '' }}>Editor Konten</option>
                    <option value="penulis" {{ old('role') == 'penulis' ? 'selected' : '' }}>Penulis</option>
                </select>
                @error('role')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">Batal</a>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition duration-150 shadow-md">
                    <i class="fas fa-save mr-2"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
@endsection
