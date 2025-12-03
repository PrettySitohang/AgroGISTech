{{-- resources/views/superadmin/users.blade.php --}}
@extends('layouts.app')
@section('title','Manajemen Pengguna')
@section('content')
<h2>Manajemen Pengguna</h2>
<table class="table table-striped">
  <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
  <tbody>
    @foreach($users as $u)
      <tr>
        <td>{{ $u->id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->email }}</td>
        <td>{{ $u->role }}</td>
        <td><a class="btn btn-sm btn-primary" href="{{ route('superadmin.users.edit', $u) }}">Edit</a></td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $users->links() }}
@endsection
