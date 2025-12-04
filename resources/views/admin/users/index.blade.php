@extends('admin.layouts.app')

@section('content')
{{-- ... Bagian atas (header, tombol, pesan sesi) tidak berubah ... --}}

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
                        {{-- ** INI ADALAH PERBAIKAN UTAMA: MENGGUNAKAN $users DARI CONTROLLER ** --}}
                        @foreach ($users as $user)
                            <tr class="hover:bg-sienna/10 light:hover:bg-gray-50">
                                {{-- Gunakan index paginator untuk penomoran --}}
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-cream-text light:text-light-text">{{ $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-cream-text light:text-light-text">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{-- Perhatikan penyesuaian Role Anda (super_admin, editor, penulis) --}}
                                            @if($user->role == 'super_admin') bg-terracotta/40 text-cream-text
                                            @elseif($user->role == 'editor') bg-sawit-green/40 text-cream-text
                                            @else bg-sienna/40 text-cream-text @endif">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 light:text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-terracotta hover:text-sienna transition light:text-terracotta light:hover:text-sienna">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                            <i class="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        {{-- ** HAPUS BLOK @php YANG MENSIMULASIKAN DATA ** --}}

                    </tbody>
                </table>
            </div>

            {{-- Menambahkan Paginasi dari Eloquent Paginator --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
