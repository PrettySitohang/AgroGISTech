{{-- resources/views/layouts/partials/sidebar.blade.php --}}
<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" id="sidebarMenu">
  <div class="position-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="{{ route('public.index') }}">Beranda</a></li>
      @auth
        @if(auth()->user()->role === 'penulis')
          <li class="nav-item"><a class="nav-link" href="{{ route('penulis.articles') }}">Artikel Saya</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('penulis.create') }}">Buat Artikel</a></li>
        @endif
        @if(auth()->user()->role === 'editor')
          <li class="nav-item"><a class="nav-link" href="{{ route('editor.review') }}">Review Artikel</a></li>
        @endif
        @if(auth()->user()->role === 'super_admin')
          <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.users') }}">Manajemen Pengguna</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('superadmin.logs') }}">Log Aktivitas</a></li>
        @endif
      @endauth
    </ul>
  </div>
</div>
