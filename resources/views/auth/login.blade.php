@extends('layouts.app')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900 dark:text-white">
            Masuk ke Agro<span class="text-amber-600">GISTech</span>
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
            atau
            <a href="{{ route('register') }}" class="font-medium text-amber-600 hover:text-amber-500 transition duration-150">
                daftar akun baru
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-gray-100 dark:border-gray-700/50">

            @if (session('error'))
                <div class="bg-red-100 p-3 mb-4 rounded-lg text-red-700 dark:bg-red-900 dark:text-red-300">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="bg-green-100 p-3 mb-4 rounded-lg text-green-700 dark:bg-green-900 dark:text-green-300">{{ session('success') }}</div>
            @endif

            <!-- Login Google (Socialite) -->
            <a href="{{ route('google.login') }}" class="w-full flex justify-center items-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 transition duration-150">
                <i class="fab fa-google mr-2"></i> Masuk dengan Google
            </a>

            <div class="my-6 flex items-center">
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
                <span class="flex-shrink mx-4 text-gray-500 dark:text-gray-400 text-sm">Atau dengan Email</span>
                <div class="flex-grow border-t border-gray-300 dark:border-gray-600"></div>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Alamat Email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Kata Sandi
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm placeholder-gray-400 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded dark:bg-gray-700">
                        <label for="remember" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
