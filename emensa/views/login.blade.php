@extends('layouts.layout')

@section('content')
    <h2>Anmeldung</h2>
    @if (isset($error) && !empty($error))
        <p style="color:red;">{{ $error }}</p>
    @endif
    @if (isset($success) && !empty($success))
        <p style="color:green;">{{ $success }}</p>
    @endif

    <form action="/anmeldung_verifizieren" method="post">
        @csrf <!-- CSRF-Schutz für Laravel -->
        <label for="email">E-Mail:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Passwort:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Anmeldung</button>
    </form>
@endsection

@section('cssextra')
    <link rel="stylesheet" type="text/css" href="/css/stylesheet.css">
@endsection
