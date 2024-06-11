@extends("layouts.layout")

@section("content")
<div id="wunschgericht_container">
    <h2>Wunschgericht nennen</h2>
    <form action="/wunschgericht" method="post">
        <label for="gericht_name">Name des Gerichts:</label>
        <input type="text" id="gericht_name" name="gericht_name" required>

        <label for="beschreibung">Beschreibung:</label>
        <textarea id="beschreibung" name="beschreibung" rows="4" cols="50"></textarea>

        <label for="ersteller_name">Dein Name:</label>
        <input type="text" id="ersteller_name" name="ersteller_name">

        <label for="email">Deine E-Mail:</label>
        <input type="email" id="email" name="email">

        <input type="submit" value="Wunsch abschicken">
    </form>

    @if($sucess)
    <div id="success-message">
        Vielen Dank! Dein Wunschgericht wurde gemeldet.
    </div>
    @endif
</div>

@endsection

@section("cssextra")
    <link rel="stylesheet" type="text/css" href="/css/stylesheet.css">
@endsection

