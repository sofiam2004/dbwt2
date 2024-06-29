<!-- In resources/views/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="gerichte-uebersicht">
        @foreach($gerichte as $gericht)
            <div class="gericht">
                <h2>{{ $gericht['name'] }}</h2>
                <p>{{ $gericht['beschreibung'] }}</p>
                @php
                    $bildPath = '/public/img/gerichte/' . $gericht['bildname'];
                    $platzhalterPath = '/public/img/gerichte/00_image_missing.jpg';
                @endphp
                @if($gericht['bildname'] && file_exists(public_path($bildPath)))
                    <img src="{{ asset($bildPath) }}" width="200" height="150" alt="{{ $gericht['name'] }}">
                @else
                    <img src="{{ asset($platzhalterPath) }}" width="200" height="150" alt="Bild nicht verfügbar">
                @endif
            </div>
        @endforeach
    </div>
@endsection
