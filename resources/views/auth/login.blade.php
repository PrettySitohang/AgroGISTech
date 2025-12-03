@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-4xl font-extrabold text-cream-text light:text-light-text">
            Masuk ke Agro<span class="text-terracotta">GISTech</span>
        </h2>
        <p class="mt-2 text-center text-sm text-gray-400 light:text-gray-600">
            atau
            <a href="{{ route('register') }}" class="font-medium text-terracotta hover:text-sienna transition duration-150">
                daftar akun baru
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        {{-- Card Login --}}
        <div class="bg-bg-dark py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-sienna/50 light:bg-white light:shadow-gray-300/50 light:border-gray-200">

            {{-- Pesan Sesi --}}
            @if (session('error'))
                <div class="bg-red-900/40 p-3 mb-4 rounded-lg text-red-300 dark:bg-red-100 dark:text-red-700">{{ session('error') }}</div>
            @endif
            @if (session('success'))
                <div class="bg-sawit-green/20 p-3 mb-4 rounded-lg text-sawit-green dark:bg-green-100 dark:text-green-700">{{ session('success') }}</div>
            @endif

            <!-- Login Google (Socialite) -->
            <a href="{{ route('google.login') }}" class="w-full flex justify-center items-center py-2 px-4 border border-sienna/40 rounded-lg shadow-sm text-sm font-medium text-cream-text bg-bg-dark hover:bg-sienna/20 transition duration-150 light:border-gray-300 light:text-light-text light:bg-gray-50 light:hover:bg-gray-100">
                <i class="fab fa-google mr-2"></i> Masuk dengan Google
            </a>

            <div class="my-6 flex items-center">
                <div class="flex-grow border-t border-sienna/50 light:border-gray-300"></div>
                <span class="flex-shrink mx-4 text-gray-500 light:text-gray-500 text-sm">Atau dengan Email</span>
                <div class="flex-grow border-t border-sienna/50 light:border-gray-300"></div>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-cream-text light:text-light-text">
                        Alamat Email
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="appearance-none block w-full px-3 py-2 border border-sienna/70 rounded-lg shadow-sm placeholder-gray-500 bg-bg-dark text-cream-text focus:outline-none focus:ring-terracotta focus:border-terracotta sm:text-sm
                                     light:border-gray-300 light:bg-white light:text-light-text @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-2 text-sm text-terracotta">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-cream-text light:text-light-text">
                        Kata Sandi
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-sienna/70 rounded-lg shadow-sm placeholder-gray-500 bg-bg-dark text-cream-text focus:outline-none focus:ring-terracotta focus:border-terracotta sm:text-sm
                                     light:border-gray-300 light:bg-white light:text-light-text @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-2 text-sm text-terracotta">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-terracotta focus:ring-terracotta border-gray-300 rounded bg-bg-dark light:bg-white">
                        <label for="remember" class="ml-2 block text-sm text-cream-text light:text-light-text">
                            Ingat Saya
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-bg-dark bg-terracotta hover:bg-sienna focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-terracotta transition duration-150 shadow-lg shadow-terracotta/40">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
