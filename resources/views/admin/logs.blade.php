@extends('admin.layouts.app')
@section('title','Log Aktivitas')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h2 class="h5 m-0 font-weight-bold text-primary">Log Aktivitas Sistem</h2>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                   <tr>
                        {{-- ID: Tetap Rata Tengah --}}
                        <th class="text-center" style="width: 50px;">ID</th>
                        {{-- Waktu: Rata Kiri (Hapus text-center) --}}
                        <th style="width: 220px;">Waktu</th>
                        {{-- Action: Rata Kiri (Hapus text-center) --}}
                        <th style="width: 220px;">Action</th>
                        {{-- Actor: Rata Kiri (Hapus text-center) --}}
                        <th style="width: 220px;">Actor</th>
                        {{-- Entity: Rata Kiri (Hapus text-center) --}}
                        <th>Entity</th>
                        {{-- Meta Data: Rata Kiri (Hapus text-center) --}}
                        <th>Meta Data</th>
                        {{-- Aksi: Rata Kiri (Hapus text-center) --}}
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logs as $l)
                    <tr>
                        {{-- ID: RATA TENGAH --}}
                        <td class="text-center small">{{ $l->id }}</td>

                        {{-- Waktu: RATA KIRI --}}
                        <td class="small">{{ $l->created_at->format('d M Y H:i') }}</td>

                        {{-- Action: RATA KIRI --}}
                        <td>
                            @php
                                $badgeClass = match($l->action) {
                                    'created' => 'success',
                                    'updated' => 'info',
                                    'deleted' => 'danger',
                                    default => 'secondary',
                                };
                            @endphp
                            <span class="badge bg-{{ $badgeClass }} text-uppercase">{{ $l->action }}</span>
                        </td>

                        {{-- Actor: DIGESER KE KANAN (ps-4 = padding-start: 4) --}}
                        <td class="ps-4">{{ $l->actor?->name ?? 'Guest' }}</td>

                        {{-- Entity: DIGESER KE KANAN (ps-8 = padding-start: 8) --}}
                        <td class="ps-8">{{ $l->entity_type }} #{{ $l->entity_id }}</td>

                        {{-- Meta Data: DIGESER KE KANAN (ps-4 = padding-start: 4) --}}
                        <td class="ps-4">
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#metaModal{{ $l->id }}">
                                Lihat JSON
                            </button>
                        </td>

                        {{-- Aksi: RATA KIRI --}}
                        <td>
                            <form method="POST" action="{{ route('admin.logs.delete', $l) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus log ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus Log">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>

{{-- MODAL UNTUK MENAMPILKAN META DATA (JSON) --}}
@foreach($logs as $l)
<div class="modal fade" id="metaModal{{ $l->id }}" tabindex="-1" aria-labelledby="metaModalLabel{{ $l->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="metaModalLabel{{ $l->id }}">Meta Data Log #{{ $l->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <pre style="white-space: pre-wrap; word-wrap: break-word; background-color: #f8f9fa; padding: 10px; border-radius: 5px;">{{ json_encode($l->meta, JSON_PRETTY_PRINT) }}</pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
