@extends('editor.layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-bg-dark border border-sienna/30 rounded-2xl p-8 shadow-xl">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-extrabold text-cream-text mb-2">Edit Profil</h1>
            <p class="text-cream-text/70">Perbarui informasi profil akun Anda</p>
        </div>

        {{-- Form --}}
        <form action="{{ route('editor.profile.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-bold text-cream-text mb-2">Nama Lengkap</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full px-4 py-3 bg-sienna/20 border border-sienna/50 rounded-lg text-cream-text placeholder-cream-text/50 focus:border-terracotta focus:ring-2 focus:ring-terracotta/50 transition"
                       required>
                @error('name')
                    <p class="text-terracotta text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-bold text-cream-text mb-2">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full px-4 py-3 bg-sienna/20 border border-sienna/50 rounded-lg text-cream-text placeholder-cream-text/50 focus:border-terracotta focus:ring-2 focus:ring-terracotta/50 transition"
                       required>
                @error('email')
                    <p class="text-terracotta text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-bold text-cream-text mb-2">Password Baru <span class="text-cream-text/50 text-xs">(Kosongkan jika tidak ingin mengubah)</span></label>
                <input type="password" id="password" name="password"
                       class="w-full px-4 py-3 bg-sienna/20 border border-sienna/50 rounded-lg text-cream-text placeholder-cream-text/50 focus:border-terracotta focus:ring-2 focus:ring-terracotta/50 transition"
                       placeholder="Minimal 8 karakter">
                @error('password')
                    <p class="text-terracotta text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Confirmation --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-cream-text mb-2">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="w-full px-4 py-3 bg-sienna/20 border border-sienna/50 rounded-lg text-cream-text placeholder-cream-text/50 focus:border-terracotta focus:ring-2 focus:ring-terracotta/50 transition"
                       placeholder="Ulangi password baru">
                @error('password_confirmation')
                    <p class="text-terracotta text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex gap-4 pt-6">
                <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-terracotta to-sienna text-cream-text font-bold rounded-lg hover:shadow-lg hover:shadow-terracotta/50 transition duration-300">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
                <a href="{{ route('editor.dashboard') }}" class="flex-1 px-6 py-3 bg-sienna/20 text-cream-text font-bold rounded-lg border border-sienna/50 hover:bg-sienna/40 transition duration-300 text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
@endsection
