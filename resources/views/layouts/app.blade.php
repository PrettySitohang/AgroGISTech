{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="id">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgroGISTech - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>
    @include('layouts.partials.header')
    <div class="container-fluid">
      <div class="row">
        @include('layouts.partials.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          @yield('content')
        </main>
      </div>
    </div>
    @include('layouts.partials.footer')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
