@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Tambah Pengguna Baru</h2>
    <p class="text-gray-400 light:text-gray-600">Isi detail di bawah ini untuk mendaftarkan akun pengguna baru.</p>

    {{-- Card Form --}}
    <div class="bg-bg-dark light:bg-white rounded-xl p-8 shadow-xl border border-sienna/50 light:border-gray-200">

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label for="name" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Nama Lengkap *</label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}"
                       class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text @error('name') border-red-500 @enderror">
                @error('name')<p class="mt-2 text-sm text-terracotta">{{ $message }}</p>@enderror
            </div>

            {{-- Alamat Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Alamat Email *</label>
                <input type="email" id="email" name="email" required value="{{ old('email') }}"
                       class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text @error('email') border-red-500 @enderror">
                @error('email')<p class="mt-2 text-sm text-terracotta">{{ $message }}</p>@enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Password *</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text @error('password') border-red-500 @enderror">
                @error('password')<p class="mt-2 text-sm text-terracotta">{{ $message }}</p>@enderror
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Konfirmasi Password *</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text">
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-medium text-cream-text light:text-light-text mb-1">Role *</label>
                <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-sienna/70 rounded-lg bg-bg-dark text-cream-text focus:ring-terracotta focus:border-terracotta light:bg-white light:border-gray-300 light:text-light-text">
                    <option value="writer">Writer</option>
                    <option value="editor">Editor</option>
                    <option value="admin">Admin</option>
                </select>
                @error('role')<p class="mt-2 text-sm text-terracotta">{{ $message }}</p>@enderror
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="{{ route('admin.users.index') }}" class="text-terracotta hover:text-sienna transition">
                    &leftarrow; Kembali ke Daftar
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-sawit-green text-bg-dark rounded-lg font-bold hover:bg-sawit-green/80 transition shadow-lg shadow-sawit-green/40">
                    <i class="fas fa-user-plus mr-2"></i> Daftarkan Pengguna
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
