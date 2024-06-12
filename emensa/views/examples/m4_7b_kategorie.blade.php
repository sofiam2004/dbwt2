
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategorien der Gerichte</title>
    <style>
        .fett {
    font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Kategorien der Gerichte</h1>
    <ul>
@foreach($kategorien as $key => $kategorie)
    @if($key % 2 == 1)
        <li class="fett">{{ $kategorie }}</li>
    @else
        <li>{{ $kategorie }}</li>
        @endif
        @endforeach
        </ul>
        </body>
        </html>

