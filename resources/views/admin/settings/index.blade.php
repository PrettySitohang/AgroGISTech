@extends('admin.layouts.app')

@section('page_title', 'Pengaturan Situs')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Pengaturan Situs</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-semibold mb-1">Nama Situs</label>
            <input type="text" name="site_name" value="{{ old('site_name', $siteName) }}" class="w-full p-2 rounded border" required>
            @error('site_name') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-4">
            <label class="block font-semibold mb-1">Logo Situs (Gambar)</label>
            @if($logoPath)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $logoPath) }}" alt="Logo" style="max-height:80px">
                </div>
            @endif
            <input type="file" name="logo" accept="image/*">
            @error('logo') <div class="text-red-600 mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
            <button class="px-4 py-2 bg-sienna text-cream-text rounded">Simpan</button>
        </div>
    </form>
</div>
@endsection
