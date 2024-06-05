<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerichte</title>
</head>
<body>
<h1>Gerichte mit internem Preis über 2€</h1>
@if ($message)
    <p>{{ $message }}</p>
@else
    <ul>
        @foreach ($gerichte as $gericht)
            <li>{{ $gericht->name }} - {{ $gericht->preis }}€</li>
        @endforeach
    </ul>
@endif
</body>
</html>

