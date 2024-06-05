<?php
// Verbindung zur Datenbank herstellen
$link = mysqli_connect(
    "localhost",      // Host der Datenbank
    "root",           // Benutzername zur Anmeldung
    "emiliebff",            // Passwort
    "emensawerbeseite" // Datenbankschema
// Optionaler Port der Datenbank
);

if (!$link) {
    // Verbindungsfehler anzeigen und beenden, falls die Verbindung fehlschlägt
    echo "Verbindung fehlgeschlagen: ", mysqli_connect_error();
    exit();
}

// SQL-Abfrage, um Daten aus der Tabelle 'gericht' auszuwählen
$sql = "SELECT code, name, typ FROM emensawerbeseite.allergen";

$res = mysqli_query($link, $sql);

if (!$res) {
    // Abfragefehler anzeigen und beenden, falls die Abfrage fehlschlägt
    echo "Fehler während der Abfrage: ", mysqli_error($link);
    exit();
}

// Jede Zeile des Ergebnisses abrufen und anzeigen
while ($row = mysqli_fetch_assoc($res)) {
    echo '<li>' . $row['code'] . ': ' . $row['name'] . ': ' . $row['typ'] .'</li>';
}

// Das Ergebnis freigeben
mysqli_free_result($res);

// Die Datenbankverbindung schließen
mysqli_close($link);

