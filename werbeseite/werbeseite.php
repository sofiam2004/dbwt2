<?php
/**
 * Praktikum DBWT. Autoren:
 * Rabia, Türe, 3674806
 * Sofia, Moll, 3637355
 */

// Besucherzähler erhöhen
global $gerichte;
$counterFile = 'counter.txt';
$counter = (file_exists($counterFile)) ? intval(file_get_contents($counterFile)) : 0;
$counter++;
file_put_contents($counterFile, $counter);

// Anzahl der Anmeldungen zum Newsletter zählen
$newsletterFile = 'newsletter_anmeldungen';
$numberOfNewsletterSignups = (file_exists($newsletterFile)) ? count(file($newsletterFile)) : 0;

$error = ""; // Variable für Fehlermeldung

// Funktion zum Überprüfen der E-Mail-Adresse
function isValidEmail($email): bool
{
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

// Funktion zum Speichern der Anmeldedaten
function saveNewsletterSignup($data) {
    $file = 'newsletter_anmeldungen';
    $handle = fopen($file, 'a');
    fwrite($handle, $data);
    fclose($handle);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Überprüfung, ob alle erforderlichen Felder ausgefüllt wurden
    if (empty($_POST["email"]) || empty($_POST["Vorname"]) || empty($_POST["Nachname"])) {
        $error = "Bitte füllen Sie alle erforderlichen Felder aus.";
    } elseif (!isset($_POST["INXMAIL_TRACKINGPERMISSION"])) {
        $error = "Bitte stimmen Sie den Datenschutzbestimmungen zu.";
    } elseif (!isValidEmail($_POST["email"])) {
        $error = "Die eingegebene E-Mail-Adresse entspricht nicht den Vorgaben.";
    } else {
        // Anmeldung erfolgreich
        $newsletterData = "E-Mail: {$_POST['email']}, Anrede: {$_POST['Anrede']}, Vorname: {$_POST['Vorname']}, Nachname: {$_POST['Nachname']}, Sprache Newsletter: {$_POST['Newsletter_bitte_in:']}\n";
        saveNewsletterSignup($newsletterData);
    }
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Ihre E-Mensa</title>
    <style>
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
            border: 1px solid lightgray;
            padding: 10px;
            max-width: 70%;
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
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's
            standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make
            a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing
            Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions
            of Lorem Ipsum.
        </p>
    </div>

    <?php
    // Externe Datei mit den Gerichten einbinden
    include 'gerichte.php';

    // Anzahl der Gerichte zählen
    $numberOfDishes = count($gerichte);

    ?>

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
            ?>

            </tbody>
        </table>
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
                <input type="hidden" name="INXMAIL_SUBSCRIPTION" value="[Listenname]">
                <input type="hidden" name="INXMAIL_HTTP_REDIRECT" value="[URL für Landeseite Erfolg]">
                <input type="hidden" name="INXMAIL_HTTP_REDIRECT_ERROR" value="[URL für Landeseite Fehler]">
                <input type="hidden" name="INXMAIL_CHARSET" value="UTF-8">
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
                    <select name="Newsletter bitte in:">
                        <option value="Deutsch">Deutsch</option>
                        <option value="Englisch">Englisch</option>
                    </select>
                </label>
                <label>
                    <input type="checkbox" name="INXMAIL_TRACKINGPERMISSION"> Hiermit stimme ich den Datenschutzbestimmungen zu
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
