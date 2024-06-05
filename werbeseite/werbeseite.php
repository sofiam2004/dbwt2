<?php
/**
 * Praktikum DBWT. Autoren:
 * Rabia, Türe, 3674806
 * Sofia, Moll, 3637355
 */

include 'gerichte.php';
global $gerichte;
$count_gerichte_aus_db = 0;

// Besucherzähler erhöhen
$counterFile = 'counter.txt';
$counter = (file_exists($counterFile)) ? intval(file_get_contents($counterFile)) : 0;
$counter++;
file_put_contents($counterFile, $counter);

// Anzahl der Anmeldungen zum Newsletter zählen
$newsletterFile = 'newsletter_anmeldungen';
$numberOfNewsletterSignups = (file_exists($newsletterFile)) ? count(file($newsletterFile)) : 0;


# Newsletteranmeldung
#hi rabia
$error = ""; // Variable für Fehlermeldung

// Funktion zur Überprüfung der E-Mail-Adresse
function isValidEmail($email): bool {
    // Überprüfung auf gültiges E-Mail-Format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Überprüfung auf unerwünschte Domains
    $unwanted_domains = array("rcpt.at", "damnthespam.at", "wegwerfmail.de", "trashmail.");
    foreach ($unwanted_domains as $domain) {
        if (str_contains($email, $domain)) {
            return false;
        }
    }
    return true;
}

// Funktion zum Speichern der Newsletter-Anmeldung in einer Textdatei
function saveNewsletterSignup($data): void {
    $file = 'newsletter_anmeldungen';
    file_put_contents($file, $data, FILE_APPEND);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Überprüfung, ob alle erforderlichen Felder ausgefüllt wurden
    if (empty($_POST["email"]) || empty($_POST["Vorname"]) || empty($_POST["Nachname"])) {
        $error = "Bitte füllen Sie alle erforderlichen Felder aus.";
    } elseif (!isset($_POST["datenschutz"])) {
        $error = "Bitte stimmen Sie den Datenschutzbestimmungen zu.";
    } elseif (!isValidEmail($_POST["email"])) {
        $error = "Die eingegebene E-Mail-Adresse entspricht nicht den Vorgaben.";
    } else {
        // Anmeldung erfolgreich
        $newsletterData = "E-Mail: {$_POST['email']}, Anrede: {$_POST['Anrede']}, Vorname: {$_POST['Vorname']}, Nachname: {$_POST['Nachname']}, Sprache Newsletter: {$_POST['Newsletter_bitte_in']}\n";
        saveNewsletterSignup($newsletterData);
        echo "<span class='erfolgreich'>Anmeldung erfolgreich!</span>";
    }

    // Anzeige der Fehlermeldung, falls vorhanden
    if ($error) {
        echo "<span class='error-message'>$error</span>";
    }
}


// Verbindung zur Datenbank herstellen
$link = mysqli_connect(
    "localhost",      // Host der Datenbank
    "root",           // Benutzername zur Anmeldung
    "emiliebff",       // Passwort
    "emensawerbeseite" // Datenbankschema
);

if (!$link) {
    // Verbindungsfehler anzeigen und beenden, falls die Verbindung fehlschlägt
    echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
    exit();
}

// SQL-Abfrage, um Daten aus der Tabelle 'gericht' auszuwählen
// Code wählt ersten fünf Gerichte nach Namen sortiert aus und fügt dann die
// Allergencodes dieser Gerichte hinzu, falls vorhanden
$sql = "SELECT g.id, g.name, g.beschreibung, g.preis_intern, g.preis_extern, ga.code
        FROM (
            SELECT id, name, beschreibung, preis_intern, preis_extern
            FROM emensawerbeseite.gericht
            ORDER BY name 
            LIMIT 5
        ) AS g
        LEFT JOIN emensawerbeseite.gericht_hat_allergen ga ON g.id = ga.gericht_id
        ORDER BY g.name 
        ";

$res = mysqli_query($link, $sql);

if (!$res) {
    die("Abfragefehler: " . mysqli_error($link));
}

// Array für Gerichte und deren Allergene
$ger = array();

// Alle Gerichte und deren Allergene erfassen
while ($row = mysqli_fetch_assoc($res)) {
    // Die ID des aktuellen Gerichts aus der Datenbankzeile holen
    $gericht_id = $row['id'];

    // Prüfen, ob das Gericht bereits im Array 'ger' existiert
    if (!isset($ger[$gericht_id])) {
        // Wenn das Gericht noch nicht existiert, initialisiere es mit den Grunddaten
        $ger[$gericht_id] = array(
            'name' => $row['name'],
            'beschreibung' => $row['beschreibung'],
            'preis_intern' => $row['preis_intern'],
            'preis_extern' => $row['preis_extern'],
            'allergene' => array()                  // Initialisiere ein leeres Array für Allergene
        );
    }

    // Wenn der Allergencode nicht leer ist
    if (!empty($row['code'])) {
        // Füge den Allergencode dem Array der Allergene des Gerichts hinzu
        $ger[$gericht_id]['allergene'][] = $row['code'];
    }
}


// SQL für Allergencodes, die unten aufgelistet werden
$sql2 = "SELECT DISTINCT code FROM emensawerbeseite.allergen";
$allergene_res = mysqli_query($link, $sql2);


// Anzahl der Gerichte zählen
$count_gerichte_aus_db = count($ger); // Gerichte aus DB
$numberOfDishes = count($gerichte); // Gerichte aus gerichte.php
$numberOfDishes += $count_gerichte_aus_db;


// Statistiken in Datenbank darstellen

// löscht alles aus "zahlen", damit immer 1 Zeile in der Tabelle ist
$del = "DELETE FROM emensawerbeseite.zahlen";
$loesch = mysqli_query($link, $del);

// Hinzufügen der neuen Statistiken zur Tabelle "zahlen"
$sql_zahlen = "INSERT INTO zahlen (anzahl_gerichte, anzahl_anmeldungen, anzahl_besucher) 
                VALUES ($numberOfDishes, $numberOfNewsletterSignups, $counter)";
$zahlen = mysqli_query($link,$sql_zahlen);

?>



<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Ihre E-Mensa</title>
    <style>

        .error-message {
            font-size: 20px;
            color: red;
        }
        .erfolgreich {
            font-size: 20px;
            color: green;
        }
        body {
            width: 1200px;
            margin: 0 auto;
            padding: 0;
            height: 100%;
            text-align: center;
        }
        main {
            display: block;
        }
        h1 {
            margin-top: 60px;
        }
        .container_nav {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        nav ul {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 5px 0;
            list-style: none;
            letter-spacing: 2px;
            width: 600px;
            border: 1px solid #ccc;
            margin: auto;
        }
        nav ul li {
            padding: 10px;
        }
        .container_nav img {
            width: 200px;
            margin-left: 20px;
        }
        p {
            padding: 10px;
            max-width: 80%;
            text-align: left;
            margin: auto;
        }
        #tabelle {
            margin: auto;
            border: 2px solid lightgray;
            border-collapse: collapse;
            text-align: center;
        }
        th, td {
            border: 1px solid lightgray;
            padding: 8px;
        }
        .zahlen_liste {
            display: flex;
            list-style: none;
            justify-content: center;
            padding: 0;
            font-size: 23px;
            font-weight: bold;
        }
        .zahlen_liste li {
            padding: 20px;
        }
        #wichtig ul {
            display: flex;
            flex-direction: column;
            margin: auto;
            padding: 0;
            width: 300px;
        }
        #wichtig ul li {
            width: 290px;
            margin-left: 10px;
            text-align: left;
        }
        input[type=text], select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 10px;
        }
        .container {
            margin: auto;
            width: 400px;
            border-radius: 7px;
            background-color: #FAFAFA;
            padding: 20px;
            text-align: left;
        }
        button {
            background-color: #4b4241;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color: #00a19c;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        .impressum {
            display: flex;
            list-style: none;
            justify-content: center;
            padding: 0;
            margin-top: 50px;
            margin-bottom: 40px;
        }
        .impressum li:not(:last-child):after {
            content: "|";
            margin-left: 20px;
            margin-right: 20px;
            color: coral;
        }
        .impressum a:hover {
            color: #ff0000;
            text-decoration: underline;
        }

        .allergene-list {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .allergene-list li {
            display: inline-block;
            margin-right: 10px; /* Abstand zwischen den Allergenen */
        }

        #link_wunschgericht {
            text-align: center;
            margin-bottom: 30px;
        }

        #link_wunschgericht a {
            display: inline-block;
            padding: 10px 20px;
            border: 1px solid green; /* Dünner grüner Rahmen */
            color: green; /* Grüne Schriftfarbe */
            text-decoration: none;
            font-size: 16px;
        }

        #link_wunschgericht a:hover {
            background-color: lightgreen; /* Helle grüne Hintergrundfarbe beim Hovern */
        }

    </style>
</head>
<body>
<header>
    <div class="container_nav">
        <img src="logo.jpeg" alt="Logo" title="Logo">
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
</header>
<main>
    <img src="bild_werbeseite.jpeg" alt="Beispiel-Bild" title="Beispiel-Bild">
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

            <p id="link_wunschgericht"><a href="wunschgericht.php">Klicke hier, um uns dein Wunschgericht zu nennen</a></p>


            <?php
            // Dynamische Darstellung der Gerichte
            foreach ($gerichte as $gericht) {
                echo "<tr>";
                echo "<td>{$gericht['name']}</td>";
                echo "<td>{$gericht['description']}</td>";
                echo "<td>{$gericht['price_intern']}</td>";
                echo "<td>{$gericht['price_extern']}</td>";
                echo "<td>";
                echo "<ul>";
                foreach ($gericht['allergens'] as $allergen) {
                    echo "<li>{$allergen}</li>";
                }
                echo "</ul>";
                echo "</td>";
                echo "<td><img src=\"{$gericht['image']}\" alt=\"{$gericht['name']}\" width='100'></td>";
                echo "</tr>";
            }


            // Dynamische Darstellung der Gerichte aus der Datenbank
            foreach ($ger as $gericht) {
                echo "<tr>";
                echo "<td>{$gericht['name']}</td>";
                echo "<td>{$gericht['beschreibung']}</td>";
                echo "<td>{$gericht['preis_intern']}</td>";
                echo "<td>{$gericht['preis_extern']}</td>";
                echo "<td>";
                echo "<ul>";
                // Iteriere über jedes Allergen des aktuellen Gerichts
                foreach ($gericht['allergene'] as $allergen) {
                    // Gib jedes Allergen als Listenelement in der ungeordneten Liste aus
                    echo "<li>{$allergen}</li>";
                }
                echo "</ul>";
                echo "</td>";
                echo "</tr>";
            }
            ?>


            </tbody>
        </table>
    </div>

    <div id="allergene">
        <h2>Verwendete Allergene</h2>
        <ul class="allergene-list">
            <?php
            // Liste der verwendeten Allergene anzeigen
            while ($allergen = mysqli_fetch_assoc($allergene_res)) {
                echo "<li>{$allergen['code']}</li>";
            }
            ?>
        </ul>
    </div>

    <div id="zahlen">
        <h1>E-Mensa in Zahlen</h1>
        <ul class="zahlen_liste">
            <li><?php echo $counter; ?> Besuche</li>
            <li><?php echo $numberOfNewsletterSignups; ?> Newsletter Anmeldungen</li>
            <li><?php echo $numberOfDishes; ?> Gerichte</li>

        </ul>
    </div>

    <div id="kontakt">
        <h1>Interesse geweckt? Wir informieren Sie!</h1>
        <div class="container">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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


            <?php
            // Erfolgsmeldung oder Fehlermeldung anzeigen
            if ($_SERVER["REQUEST_METHOD"] == "POST" && empty($error)) {
                echo "<p style='color: green;'>Vielen Dank für Ihre Anmeldung zum Newsletter!</p>";
            } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($error)) {
                echo "<p style='color: red;'>$error</p>";
            }
            ?>
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
        <li><a href="impressum.html">Impressum</a></li>
    </ul>
</footer>
</body>
</html>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
