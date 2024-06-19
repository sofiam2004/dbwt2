<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/gericht.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/../models/allergen.php');

/* Datei: controllers/HomeController.php */
class HomeController
{

    private function establishConnectionDb()
    {
        // Verbindung zur Datenbank herstellen
        $link = mysqli_connect(
            "localhost",   // Host der Datenbank
            "root",        // Benutzername zur Anmeldung
            "emiliebff",         // Passwort
            "emensawerbeseite" // Auswahl der Datenbanken (bzw. des Schemas)
        );

        // Überprüfen, ob die Verbindung erfolgreich hergestellt wurde
        if (!$link) {
            die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
        }

        return $link;
    }


    public function index(RequestData $request) {
        return view('home', ['rd' => $request ]);
    }

    // Newsletteranmeldung, Überprüfung, ob Email valid ist
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

    // Speichern der Newsletteranmeldungen in Textdatei
    function saveNewsletterSignup($data): void {
        $file = '../storage/newsletter_anmeldungen.txt';
        file_put_contents($file, $data, FILE_APPEND);
    }


    // Formularverarbeitung für die Newsletter-Anmeldung
    function displayPage(): string
    {
        $success = false;
        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["email"]) || empty($_POST["Vorname"]) || empty($_POST["Nachname"])) {
                $errors[] = "Bitte füllen Sie alle erforderlichen Felder aus.";
            } elseif (!isset($_POST["datenschutz"])) {
                $errors[] = "Bitte stimmen Sie den Datenschutzbestimmungen zu.";
            } elseif (!$this->isValidEmail($_POST["email"])) {
                $errors[] = "Die eingegebene E-Mail-Adresse entspricht nicht den Vorgaben.";
            } else {
                // Anmeldung erfolgreich
                $newsletterData = "E-Mail: {$_POST['email']}, Anrede: {$_POST['Anrede']}, Vorname: {$_POST['Vorname']}, Nachname: {$_POST['Nachname']}, Sprache Newsletter: {$_POST['Newsletter_bitte_in']}\n";
                $this->saveNewsletterSignup($newsletterData);
                $success = true;
            }
        }

        // Statistiken
        // Besucherzähler erhöhen
        $counterFile = '../storage/counter.txt';
        $counter = (file_exists($counterFile)) ? intval(file_get_contents($counterFile)) : 0;
        $counter++;
        file_put_contents($counterFile, $counter);

        // Anzahl der Anmeldungen zum Newsletter zählen
        $newsletterFile = '../storage/newsletter_anmeldungen.txt';
        $numberOfNewsletterSignups = (file_exists($newsletterFile)) ? count(file($newsletterFile)) : 0;

        // Dynamische Darstellung der Gerichte
        $gerichte = getGerichteFromDatabase();

        // Liste der verwendeten Allergene
        $allergene = getAllergeneFromDatabase();

        // Anzahl der Gerichte zählen
        $numberOfDishes = getDishNumber();

        // Statistiken in Datenbank darstellen
        $link = connectdb();
        $sql_zahlen = "INSERT INTO zahlen (anzahl_gerichte, anzahl_anmeldungen, anzahl_besucher) 
                VALUES ($numberOfDishes, $numberOfNewsletterSignups, $counter)";
        mysqli_query($link, $sql_zahlen);
        mysqli_close($link);

        // Die Variablen werden an die View übergeben
        return view('werbeseite', [
            'counter' => $counter,
            'numberOfNewsletterSignups' => $numberOfNewsletterSignups,
            'gerichte' => $gerichte,
            'allergene' => $allergene,
            'numberOfDishes' => $numberOfDishes,
            'success' => $success,
            'errors' => $errors,
        ]);
    }

    // Wunschgerichte
    public function wunschgericht(RequestData $requestData)
    {
        $sucess = false;

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gericht_name']) && isset($_POST['beschreibung'])) {
            $sucess = true;
            // Verbindung zur Datenbank herstellen
            $conn = connectdb();

            // Daten aus dem Formular erhalten
            $gericht_name = $_POST['gericht_name'];
            $beschreibung = $_POST['beschreibung'];
            $ersteller_name = isset($_POST['ersteller_name']) ? $_POST['ersteller_name'] : 'anonym';
            $email = isset($_POST['email']) ? $_POST['email'] : '';

            if (empty($gericht_name) || !$this->isValidEmail($email)) {
                $sucess = false;
            }
            else {
                // SQL-Anweisung zum Einfügen der Daten in die Tabelle
                $sql = "INSERT INTO emensawerbeseite.wunschgerichte (name, beschreibung, ersteller_name, email) VALUES ('$gericht_name', '$beschreibung', '$ersteller_name', '$email')";

                mysqli_query($conn, $sql);
            }

            mysqli_close($conn);
        }

        return view('wunschgericht', [
            'sucess' => $sucess,
        ]);
    }
}