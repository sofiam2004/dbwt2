<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body>
<header>
    {{-- Header-Inhalt --}}
    @yield('header')
</header>

<main>
    {{-- Hauptinhalt --}}
    @yield('main')
</main>

<footer>
    {{-- Footer-Inhalt --}}
    @yield('footer')
</footer>
</body>
</html>

