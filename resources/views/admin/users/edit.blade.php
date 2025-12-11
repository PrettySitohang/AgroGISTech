@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.users.index') }}" class="text-terracotta hover:text-sienna transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Edit Pengguna</h2>
    </div>

    <p class="text-gray-400 light:text-gray-600">Perbarui informasi pengguna dan role mereka.</p>

    {{-- Form Edit Pengguna --}}
    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl border border-sienna/50 light:border-gray-200 p-8">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-cream-text light:text-light-text mb-2">Nama Pengguna</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-3 bg-bg-darker/50 light:bg-gray-50 border border-sienna/30 light:border-gray-300 text-cream-text light:text-light-text rounded-lg focus:outline-none focus:ring-2 focus:ring-terracotta @error('name') is-invalid @enderror"
                    required>
                @error('name')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-cream-text light:text-light-text mb-2">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-3 bg-bg-darker/50 light:bg-gray-50 border border-sienna/30 light:border-gray-300 text-cream-text light:text-light-text rounded-lg focus:outline-none focus:ring-2 focus:ring-terracotta @error('email') is-invalid @enderror"
                    required>
                @error('email')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-semibold text-cream-text light:text-light-text mb-2">Role</label>
                <select
                    id="role"
                    name="role"
                    class="w-full px-4 py-3 bg-bg-darker/50 light:bg-gray-50 border border-sienna/30 light:border-gray-300 text-cream-text light:text-light-text rounded-lg focus:outline-none focus:ring-2 focus:ring-terracotta @error('role') is-invalid @enderror"
                    required>
                    <option value="penulis" {{ old('role', $user->role) === 'penulis' ? 'selected' : '' }}>Penulis</option>
                    <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                    <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password (Optional) --}}
            <div class="border-t border-sienna/30 pt-6">
                <h3 class="text-lg font-semibold text-cream-text light:text-light-text mb-4">Ubah Password (Opsional)</h3>

                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-semibold text-cream-text light:text-light-text mb-2">Password Baru</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-3 bg-bg-darker/50 light:bg-gray-50 border border-sienna/30 light:border-gray-300 text-cream-text light:text-light-text rounded-lg focus:outline-none focus:ring-2 focus:ring-terracotta @error('password') is-invalid @enderror"
                            placeholder="Kosongkan jika tidak ingin mengubah">
                        @error('password')
                            <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-cream-text light:text-light-text mb-2">Konfirmasi Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-3 bg-bg-darker/50 light:bg-gray-50 border border-sienna/30 light:border-gray-300 text-cream-text light:text-light-text rounded-lg focus:outline-none focus:ring-2 focus:ring-terracotta"
                            placeholder="Konfirmasi password baru">
                    </div>
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-3 bg-sawit-green text-cream-text rounded-lg hover:bg-green-700 transition font-semibold">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="px-6 py-3 bg-gray-600 text-cream-text rounded-lg hover:bg-gray-700 transition font-semibold">
                    <i class="fas fa-times mr-2"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
