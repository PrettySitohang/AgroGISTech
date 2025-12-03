{{-- resources/views/layouts/partials/header.blade.php --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('public.index') }}">AgroGISTech</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="#nav" aria-expanded="false">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="nav">
      <ul class="navbar-nav ms-auto">
        @auth
          <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-link nav-link">Logout</button></form>
          </li>
        @else
          <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Daftar</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>
