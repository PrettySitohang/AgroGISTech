{{-- resources/views/penulis/edit.blade.php --}}
@extends('layouts.app')
@section('title','Edit Artikel')
@section('content')
<h2>Edit Artikel</h2>
<form method="POST" action="{{ route('penulis.update', $article) }}">
  @csrf
  @method('PUT')
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input name="title" value="{{ $article->title }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Konten</label>
    <textarea name="content" rows="10" class="form-control" required>{{ $article->content }}</textarea>
  </div>
  <button class="btn btn-primary">Simpan Perubahan</button>
</form>
@endsection
