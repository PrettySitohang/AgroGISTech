<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sawit Tech Insight - @yield('title', 'Inovasi Kelapa Sawit')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
        }
        .min-h-screen {
            min-height: 100vh;
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'bg-dark': '#1C0E0B',
                        'sienna': '#8B3A2C',
                        'terracotta': '#D36B5E',
                        'cream-text': '#F5F5DC',
                        'sawit-green': '#10b981'
                    },
                },
            }
        }
    </script>
</head>

<body class="bg-bg-dark text-cream-text min-h-screen antialiased">

    @include('layouts.partials.header')

    <div class="pt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-sawit-green rounded-lg bg-sawit-green/20" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="p-4 mb-4 text-sm text-terracotta rounded-lg bg-terracotta/20" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    @include('layouts.partials.footer')

</body>
</html>
