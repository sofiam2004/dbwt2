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
$sql = "SELECT name, preis_intern, preis_extern FROM gericht ORDER BY name LIMIT 5";

$res = mysqli_query($link, $sql);

if (!$res) {
    // Abfragefehler anzeigen und beenden, falls die Abfrage fehlschlägt
    echo "Fehler während der Abfrage: ", mysqli_error($link);
    exit();
}

// Jede Zeile des Ergebnisses abrufen und anzeigen
while ($row = mysqli_fetch_assoc($res)) {
    echo '<li>' . $row['name'] . ': ' . $row['preis_intern'] . ': ' . $row['preis_extern'] . '</li>';
}

// Das Ergebnis freigeben
//mysqli_free_result($res);

// Die Datenbankverbindung schließen
//mysqli_close($link);


