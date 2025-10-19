{{-- resources/views/layouts/admin.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - Admin</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <header>
     <h1>Gestion de congés</h1>
        <p>Connecté en tant que : {{ auth()->user()->nom ?? 'Invité' }}</p>
    </header>

    <main>
        @yield('content')
    </main>
    <body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen">
    {{-- Header --}}
    @include('layouts.header')
    
    {{-- Main Content --}}
    <main class="flex-1">
        @yield('content')
    </main>
    
    {{-- Footer --}}
    @include('layouts.footer')
    
</body>
</html>
