@extends('layouts.app_dashboard')

@section('page_title', 'Pengaturan Profil')

@section('content')
    <div class="max-w-xl mx-auto bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Perbarui Informasi Profil</h2>

        <form action="{{ route('penulis.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                <input id="name" name="name" type="text" required value="{{ old('name', Auth::user()->name) }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input id="email" name="email" type="email" required value="{{ old('email', Auth::user()->email) }}"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('email') border-red-500 @enderror">
                @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <h3 class="text-lg font-semibold text-gray-800 dark:text-white pt-4 border-t border-gray-200 dark:border-gray-700">Ganti Kata Sandi (Opsional)</h3>

            <!-- Kata Sandi Baru -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kata Sandi Baru</label>
                <input id="password" name="password" type="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 @error('password') border-red-500 @enderror">
                @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Konfirmasi Kata Sandi Baru -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password"
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-6 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition duration-150 shadow-md">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
