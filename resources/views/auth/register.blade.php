@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
            Daftar Akun Penulis Baru
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-amber-600 hover:text-amber-500 transition duration-150">
                Masuk di sini
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-100 dark:border-gray-700/50">

            <a href="{{ route('google.login') }}" class="w-full flex justify-center items-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150">
                <i class="fab fa-google mr-2"></i> Daftar dengan Google
            </a>

            <div class="my-6 flex items-center">
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                <span class="flex-shrink mx-4 text-gray-500 dark:text-gray-400 text-sm">Atau dengan Email</span>
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 sm:text-sm @error('name') border-red-500 @enderror">
                        @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kata Sandi</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 sm:text-sm @error('password') border-red-500 @enderror">
                        @error('password')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:bg-gray-700 dark:text-white focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                        <i class="fas fa-user-plus mr-2"></i> Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
