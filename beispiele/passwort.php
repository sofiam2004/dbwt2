<?php
// Setzen der Zeitzone auf Mitteleuropäische Zeit (Berlin)
date_default_timezone_set('Europe/Berlin');

// Administrator-Email
$email = 'admin@emensa.example';

// Verbindung zur Datenbank herstellen (bitte Ihre Verbindungsdaten anpassen)
$servername = "localhost";
$username = "root";
$password = "emiliebff";
$dbname = "emensawerbeseite";

// Erstellen einer Verbindung
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung überprüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Überprüfen, ob die E-Mail-Adresse bereits existiert und aktuellen Wert von anzahlanmeldungen lesen
$stmt_check = $conn->prepare("SELECT id, anzahlanmeldungen FROM emensawerbeseite.benutzer WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$stmt_check->store_result();

if ($stmt_check->num_rows > 0) {
    // Benutzer mit dieser E-Mail-Adresse bereits vorhanden: Inkrementiere anzahlanmeldungen
    $stmt_check->bind_result($id, $anzahlanmeldungen);
    $stmt_check->fetch();

    $anzahlanmeldungen++;

    // Aktualisiere die Anzahl der Anmeldungen und setze die letzte Anmeldung auf die aktuelle Zeit
    $stmt_update = $conn->prepare("UPDATE emensawerbeseite.benutzer SET anzahlanmeldungen = ?, letzteanmeldung = NOW() WHERE id = ?");
    $stmt_update->bind_param("ii", $anzahlanmeldungen, $id);

    if ($stmt_update->execute()) {
        echo "Anzahlanmeldungen und letzte Anmeldung erfolgreich aktualisiert.";
    } else {
        echo "Fehler beim Aktualisieren der Anzahlanmeldungen und der letzten Anmeldung: " . $stmt_update->error;
    }

    $stmt_update->close();

} else {
    // Benutzer mit dieser E-Mail-Adresse nicht gefunden: Fehlgeschlagene Anmeldung
    // Hier könnten Sie entsprechende Fehlerbehandlung durchführen oder einen neuen Benutzer hinzufügen
    echo "Benutzer mit der E-Mail-Adresse nicht gefunden.";
}

$stmt_check->close();
$conn->close();

