<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerichte mit internem Preis über 2€</title>
</head>
<body>
<h1>Gerichte mit internem Preis über 2€</h1>
@if (!empty($gerichte))
    <ul>
        @foreach($gerichte as $gericht)
            <li>{{ $gericht['name'] }} - {{ $gericht['preis_intern'] }}€</li>
        @endforeach
    </ul>
@else
    <p>{{ $message }}</p>
@endif
</body>
</html>
