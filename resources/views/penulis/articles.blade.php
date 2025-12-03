{{-- resources/views/penulis/articles.blade.php --}}
@extends('layouts.app')
@section('title','Artikel Saya')
@section('content')
<h2>Artikel Saya</h2>
<a class="btn btn-success mb-3" href="{{ route('penulis.create') }}">Buat Artikel</a>
<table class="table">
  <thead><tr><th>Judul</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
  <tbody>
    @foreach($articles as $a)
      <tr>
        <td>{{ $a->title }}</td>
        <td>{{ $a->status }}</td>
        <td>{{ $a->created_at->format('d M Y') }}</td>
        <td>
          <a class="btn btn-sm btn-primary" href="{{ route('penulis.edit', $a) }}">Edit</a>
          <form method="POST" action="{{ route('penulis.destroy', $a) }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-danger">Hapus</button></form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $articles->links() }}
@endsection
