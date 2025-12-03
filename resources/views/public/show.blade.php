{{-- resources/views/public/show.blade.php --}}
@extends('layouts.app')
@section('title',$article->title)
@section('content')
<div class="card">
  <div class="card-body">
    <h1>{{ $article->title }}</h1>
    <p class="text-muted">Dipublikasikan: {{ $article->published_at?->format('d M Y H:i') }}</p>
    <div>{!! $article->content !!}</div>
  </div>
</div>
@endsection
