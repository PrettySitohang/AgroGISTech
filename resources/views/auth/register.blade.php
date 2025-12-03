@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-4xl font-extrabold text-cream-text light:text-light-text">
            Daftar Akun <span class="text-terracotta">Penulis</span> Baru
        </h2>
        <p class="mt-2 text-center text-sm text-gray-400 light:text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-terracotta hover:text-sienna transition duration-150">
                Masuk di sini
            </a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        {{-- Card Register --}}
        <div class="bg-bg-dark py-8 px-4 shadow-2xl sm:rounded-2xl sm:px-10 border border-sienna/50 light:bg-white light:shadow-gray-300/50 light:border-gray-200">

            <!-- Daftar Google (Socialite) -->
            <a href="{{ route('google.login') }}" class="w-full flex justify-center items-center py-2 px-4 border border-sienna/40 rounded-lg shadow-sm text-sm font-medium text-cream-text bg-bg-dark hover:bg-sienna/20 transition duration-150 light:border-gray-300 light:text-light-text light:bg-gray-50 light:hover:bg-gray-100">
                <i class="fab fa-google mr-2"></i> Daftar dengan Google
            </a>

            <div class="my-6 flex items-center">
                <div class="flex-grow border-t border-sienna/50 light:border-gray-300"></div>
                <span class="flex-shrink mx-4 text-gray-500 light:text-gray-500 text-sm">Atau dengan Email</span>
                <div class="flex-grow border-t border-sienna/50 light:border-gray-300"></div>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-cream-text light:text-light-text">Nama Lengkap</label>
                    <div class="mt-1">
                        <input id="name" name="name" type="text" required value="{{ old('name') }}"
                               class="appearance-none block w-full px-3 py-2 border border-sienna/70 rounded-lg shadow-sm placeholder-gray-500 bg-bg-dark text-cream-text focus:outline-none focus:ring-terracotta focus:border-terracotta sm:text-sm
                                     light:border-gray-300 light:bg-white light:text-light-text @error('name') border-red-500 @enderror">
                        @error('name')<p class="mt-2 text-sm text-terracotta">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-cream-text light:text-light-text">Alamat Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                               class="appearance-none block w-full px-3 py-2 border border-sienna/70 rounded-lg shadow-sm placeholder-gray-500 bg-bg-dark text-cream-text focus:outline-none focus:ring-terracotta focus:border-terracotta sm:text-sm
                                     light:border-gray-300 light:bg-white light:text-light-text @error('email') border-red-500 @enderror">
                        @error('email')<p class="mt-2 text-sm text-terracotta">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-cream-text light:text-light-text">Kata Sandi</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-sienna/70 rounded-lg shadow-sm placeholder-gray-500 bg-bg-dark text-cream-text focus:outline-none focus:ring-terracotta focus:border-terracotta sm:text-sm
                                     light:border-gray-300 light:bg-white light:text-light-text @error('password') border-red-500 @enderror">
                        @error('password')<p class="mt-2 text-sm text-terracotta">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-cream-text light:text-light-text">Konfirmasi Kata Sandi</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-sienna/70 rounded-lg shadow-sm placeholder-gray-500 bg-bg-dark text-cream-text focus:outline-none focus:ring-terracotta focus:border-terracotta sm:text-sm
                                     light:border-gray-300 light:bg-white light:text-light-text">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-bg-dark bg-terracotta hover:bg-sienna focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-terracotta transition duration-150 shadow-lg shadow-terracotta/40">
                        <i class="fas fa-user-plus mr-2"></i> Daftar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
