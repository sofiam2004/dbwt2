@extends("layouts.layout")

@section("content")
    <header class="mt-5">
        <div class="header-top">
            <div class="angemeldet-info">
                @if(isset($_SESSION['login']) && $_SESSION['login'])
                    Angemeldet als {{ $_SESSION['name'] }}
                    <a href="/abmeldung">Abmelden</a>
                @else
                    <a href="/anmeldung">Anmelden</a>
                @endif
            </div>
            <div class="container_nav">
                <!---<img src="/img/logo.jpeg" alt="Logo" title="Logo" width="200">//--->
                <nav>
                    <ul>
                        <li><a href="#ankündigung">Ankündigung</a></li>
                        <li><a href="#speisen">Speisen</a></li>
                        <li><a href="#zahlen">Zahlen</a></li>
                        <li><a href="#kontakt">Kontakt</a></li>
                        <li><a href="#wichtig">Wichtig für uns</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
        <img id="werbebild" src="/img/bild_werbeseite.jpeg" alt="Beispiel-Bild" title="Beispiel-Bild">
        <div id="ankündigung">
            <h1>Bald gibt es Essen auch online ;)</h1>
            <p>Liebe Studierende,<br><br>herzlich willkommen auf unserer digitalen Mensa-Plattform!
                Wir freuen uns darauf, Ihnen schon bald ein neues kulinarisches Erlebnis bieten zu können.<br>
                In unserer Online-Mensa erwartet Sie eine Vielzahl köstlicher Gerichte, die Ihren Geschmackssinn
                verwöhnen und Ihren Studienalltag bereichern werden. Von klassischen Favoriten bis hin zu innovativen
                Spezialitäten - wir haben für jeden Geschmack etwas dabei. <br> Unsere Webseite wird Ihr neuer
                Anlaufpunkt für frische und schmackhafte Mahlzeiten sein, die Sie bequem von überall aus genießen
                können. Egal, ob Sie auf dem Campus sind oder von zu Hause aus lernen - unsere digitale Mensa steht
                Ihnen rund um die Uhr zur Verfügung. <br> Wir arbeiten daran, Ihnen ein herausragendes kulinarisches
                Erlebnis zu bieten, und freuen uns darauf, Sie schon bald in unserer digitalen Mensa begrüßen zu dürfen.
                <br><br> Bleiben Sie gespannt und halten Sie Ausschau nach weiteren Informationen!
            </p>
        </div>


        <div id="speisen">
            <h1>Köstlichkeiten, die Sie erwarten</h1>
            <table id="tabelle">
                <thead>
                <tr id="first_row">
                    <th>Gericht</th>
                    <th>Beschreibung</th>
                    <th>Preis intern</th>
                    <th>Preis extern</th>
                    <th>Allergene</th>
                    <th>Bild</th>
                </tr>
                </thead>
                <tbody>

                <p id="link_wunschgericht"><a href="/wunschgericht">Klicke hier, um uns dein
                        Wunschgericht zu nennen</a></p>

                @foreach ($gerichte as $gericht)
                    <tr>
                        <td>{{ $gericht['name'] }}</td>
                        <td>{{ $gericht['beschreibung'] }}</td>
                        <td>{{ $gericht['preis_intern'] }}</td>
                        <td>{{ $gericht['preis_extern'] }}</td>
                        <td>{{ $gericht['codes'] }}</td>
                        <td><img src="{{ $gericht['image'] ?? '/img/default.jpg' }}" alt="{{ $gericht['name'] }}" width="100"></td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>

        <div id="allergene">
            <h2>Verwendete Allergene</h2>
            <ul>
                @foreach ($allergene as $allergen)
                    <li>{{ $allergen }}</li>
                @endforeach
            </ul>
        </div>


        <div id="zahlen">
            <h1>E-Mensa in Zahlen</h1>
            <ul class="zahlen_liste">
                <li>{{ $counter }} Besuche</li>
                <li>{{ $numberOfNewsletterSignups }} Newsletter Anmeldungen</li>
                <li>{{ $numberOfDishes }} Gerichte</li>
            </ul>
        </div>

        <div id="kontakt">
            <h1>Interesse geweckt? Wir informieren Sie!</h1>
            <div class="container_kontakt">
                <form action="/werbeseite" method="post">
                    @csrf
                    <label>E-Mail*
                        <input type="text" name="email">
                    </label>
                    <label>Anrede
                        <select name="Anrede">
                            <option value="male">Herr</option>
                            <option value="female">Frau</option>
                            <option value="neutral">Divers</option>
                        </select>
                    </label>
                    <label>Vorname
                        <input type="text" name="Vorname">
                    </label>
                    <label>Nachname
                        <input type="text" name="Nachname">
                    </label>
                    <label>Sprache Newsletter
                        <select name="Newsletter_bitte_in">
                            <option value="Deutsch">Deutsch</option>
                            <option value="Englisch">Englisch</option>
                        </select>
                    </label>
                    <label>
                        <input type="checkbox" name="datenschutz"> Hiermit stimme ich den Datenschutzbestimmungen zu
                    </label>
                    <button type="submit">Newsletter Anmelden</button>
                </form>
                @if (isset($success) && $success)
                    <p style='color: green;'>Vielen Dank für Ihre Anmeldung zum Newsletter!</p>
                @endif
                @if (isset($errors) && count($errors) > 0)
                    @foreach ($errors as $error)
                        <p style='color: red;'>{{ $error }}</p>
                    @endforeach
                @endif
            </div>
        </div>
        <div id="wichtig">
            <h1>Das ist uns wichtig</h1>
            <ul>
                <li>Beste frische saisonale Zutaten</li>
                <li>Ausgewogene abwechslungsreiche Gerichte</li>
                <li>Sauberkeit</li>
            </ul>
        </div>
        <h1>Wir freuen uns auf Ihren Besuch!</h1>
    </main>
    <footer>
        <ul class="impressum">
            <li>(c) E-Mensa GmbH</li>
            <li>Rabia Türe, Sofia Moll</li>
            <li><a href="/werbeseite/impressum.html">Impressum</a></li>
        </ul>
    </footer>

@endsection

@section("cssextra")
    <link rel="stylesheet" type="text/css" href="/css/stylesheet.css">
@endsection
