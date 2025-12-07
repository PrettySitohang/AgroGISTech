@extends('layouts.app')

@section('title', $page_title ?? 'Dashboard')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </div>
@endsection
