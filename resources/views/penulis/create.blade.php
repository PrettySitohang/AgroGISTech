{{-- resources/views/penulis/create.blade.php --}}
@extends('layouts.app')
@section('title','Buat Artikel')
@section('content')
<h2>Buat Artikel</h2>
<form method="POST" action="{{ route('penulis.store') }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input name="title" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Konten</label>
    <textarea name="content" rows="10" class="form-control" required></textarea>
  </div>
  <button class="btn btn-primary">Kirim untuk Review</button>
</form>
@endsection
