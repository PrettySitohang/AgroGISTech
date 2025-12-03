{{-- resources/views/superadmin/edit_user.blade.php --}}
@extends('layouts.app')
@section('title','Edit Pengguna')
@section('content')
<h2>Edit Pengguna</h2>
<form method="POST" action="{{ route('superadmin.users.update', $user) }}">
  @csrf
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input name="name" value="{{ $user->name }}" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Role</label>
    <select name="role" class="form-select">
      <option value="super_admin" {{ $user->role=='super_admin'?'selected':'' }}>Super Admin</option>
      <option value="editor" {{ $user->role=='editor'?'selected':'' }}>Editor</option>
      <option value="penulis" {{ $user->role=='penulis'?'selected':'' }}>Penulis</option>
    </select>
  </div>
  <button class="btn btn-primary">Simpan</button>
</form>
@endsection
