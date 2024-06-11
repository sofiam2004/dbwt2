<?php
require_once __DIR__ . '/../models/kategorie.php';


class ExampleController
{
    public function m4_6a_queryparameter(RequestData $rd) {
        /*
           Wenn Sie hier landen:
           bearbeiten Sie diese Action,
           so dass Sie die Aufgabe löst
        */

        return view('notimplemented', [
            'request'=>$rd,
            'url' => 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}"
        ]);
    }


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

    public function m4_7a_queryparameter()
    {
        // Den Wert des Abfrageparameters "name" aus der URL erhalten
        $name = isset($_GET['name']) ? $_GET['name'] : null;

        // Daten an die Blade-View übergeben und die View rendern
        return view('examples.m4_7a_queryparameter', ['name' => $name]);
    }

    public function m4_7b_kategorie()
    {
        // Verbindung zur Datenbank herstellen
        $link = $this->establishConnectionDb();

        // Query zum Abrufen der Kategorienamen ausführen
        $result = mysqli_query($link, "SELECT name FROM emensawerbeseite.kategorie ORDER BY name");

        // Array zum Speichern der Kategorienamen initialisieren
        $kategorien = [];

        // Kategorienamen aus dem Ergebnis holen und in das Array speichern
        while ($row = mysqli_fetch_assoc($result)) {
            $kategorien[] = $row['name'];
        }

        // Verbindung schließen
        mysqli_close($link);

        // Daten an die Blade-View übergeben und die View rendern
        return view('examples.m4_7b_kategorie', ['kategorien' => $kategorien]);
    }


    public function m4_7c_gerichte()
    {
        // Verbindung zur Datenbank herstellen
        $link = $this->establishConnectionDb();

        // Query zum Abrufen der Gerichte mit internem Preis über 2€ ausführen
        $result = mysqli_query($link, "SELECT name, preis_intern FROM emensawerbeseite.gericht 
                      WHERE preis_intern > 2 ORDER BY name DESC");

        // Array zum Speichern der Gerichte initialisieren
        $gerichte = [];

        // Gerichte aus dem Ergebnis holen und in das Array speichern
        while ($row = mysqli_fetch_assoc($result)) {
            // Nur Gerichte mit einem Namen hinzufügen
            if (!empty($row['name'])) {
                $gerichte[] = $row;
            }
        }

        // Wenn keine Gerichte gefunden wurden, setzen Sie eine entsprechende Nachricht
        $message = empty($gerichte) ? "Es sind keine Gerichte vorhanden" : null;

        // Verbindung schließen
        mysqli_close($link);

        // Daten an die Blade-View übergeben und die View rendern
        return view('examples.m4_7c_gerichte', ['gerichte' => $gerichte, 'message' => $message]);
    }



    public function m4_7d_layout()
    {
        // Den Wert des Abfrageparameters "no" aus der URL erhalten
        $no = isset($_GET['no']) ? $_GET['no'] : 1;

        // Je nach Wert von "no" die entsprechende Seite laden
        if ($no == 2) {
            return view('examples.pages.m4_7d_page_2');
        } else {
            return view('examples.pages.m4_7d_page_1');
        }
    }
}