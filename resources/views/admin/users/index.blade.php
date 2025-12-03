@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-3xl font-extrabold text-cream-text light:text-light-text">Manajemen Pengguna</h2>
        <a href="{{ route('admin.users.create') }}"
           class="px-4 py-2 bg-sawit-green text-bg-dark rounded-lg font-bold hover:bg-sawit-green/80 transition shadow-md shadow-sawit-green/40">
            <i class="fas fa-user-plus mr-2"></i> Tambah Pengguna Baru
        </a>
    </div>

    <p class="text-gray-400 light:text-gray-600">Kelola semua akun pengguna terdaftar pada platform, termasuk peran (role).</p>

    {{-- Session Message Placeholder (Contoh) --}}
    @if (session('status'))
        <div class="p-4 rounded-lg bg-sawit-green/20 text-sawit-green border border-sawit-green/50 light:bg-green-100 light:text-green-700">
            {{ session('status') }}
        </div>
    @endif

    {{-- Tabel Daftar Pengguna --}}
    <div class="bg-bg-dark light:bg-white rounded-xl shadow-xl overflow-hidden border border-sienna/50 light:border-gray-200">
        <div class="p-6">
            <h3 class="text-xl font-semibold text-cream-text light:text-light-text mb-4">Daftar Pengguna</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sienna/30 light:divide-gray-200">
                    <thead class="bg-sienna/20 light:bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">No</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Nama</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Role</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Terdaftar Sejak</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-cream-text/80 light:text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sienna/30 light:divide-gray-200">
                        {{-- Contoh Data Pengguna (Laravel loop akan berada di sini) --}}
                        @php
                            $users = [
                                ['id' => 1, 'name' => 'Suci Andestia', 'role' => 'editor', 'email' => 'suci@editor.ac.id', 'registered_at' => '20 Nov 2025'],
                                ['id' => 2, 'name' => 'Kurnia Setiawan', 'role' => 'writer', 'email' => 'kurnia@writer.ac.id', 'registered_at' => '20 Nov 2025'],
                            ];
                        @endphp

                        @foreach ($users as $index => $user)
                            <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-cream-text light:text-light-text">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cream-text light:text-light-text">{{ $user['name'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                 @if($user['role'] == 'admin') bg-terracotta/40 text-cream-text
                                                 @elseif($user['role'] == 'editor') bg-sawit-green/40 text-cream-text
                                                 @else bg-sienna/40 text-cream-text @endif">
                                        {{ $user['role'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">{{ $user['email'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">{{ $user['registered_at'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.users.edit', $user['id']) }}" class="text-terracotta hover:text-sienna transition light:text-terracotta light:hover:text-sienna">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.delete', $user['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Tambahkan Paginasi di sini jika menggunakan Eloquent Paginator --}}
        </div>
    </div>
</div>
@endsection
