{{-- resources/views/superadmin/logs.blade.php --}}
@extends('layouts.app')
@section('title','Log Aktivitas')
@section('content')
<h2>Log Aktivitas Sistem</h2>
<table class="table table-sm">
  <thead><tr><th>ID</th><th>Waktu</th><th>Action</th><th>Actor</th><th>Entity</th><th>Meta</th><th>Aksi</th></tr></thead>
  <tbody>
    @foreach($logs as $l)
      <tr>
        <td>{{ $l->id }}</td>
        <td>{{ $l->created_at->format('d M Y H:i') }}</td>
        <td>{{ $l->action }}</td>
        <td>{{ $l->actor?->name ?? '-' }}</td>
        <td>{{ $l->entity_type }} #{{ $l->entity_id }}</td>
        <td><pre>{{ json_encode($l->meta) }}</pre></td>
        <td>
          <form method="POST" action="{{ route('superadmin.logs.delete', $l) }}">@csrf<button class="btn btn-sm btn-danger">Hapus</button></form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
{{ $logs->links() }}
@endsection
