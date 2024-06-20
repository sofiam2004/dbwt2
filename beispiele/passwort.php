<?php
// Administrator-Email
$email = 'admin@emensa.example';

// Name des Administrators
$name = 'Administrator';

// Passwort
$plain_password = 'emensa123456789';

// Salt für die gesamte Anwendung (mindestens 4 Zeichen)
$salt = 'wxyz'; // Sie können einen längeren und sichereren Salt verwenden

// Kombination aus Passwort und Salt
$password_with_salt = $plain_password . $salt;

// Hash des Passworts mit Salt
$hashed_password = password_hash($password_with_salt, PASSWORD_BCRYPT);

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

// Initialwerte für die neuen Felder
$anzahlfehler = 0;
$anzahlanmeldungen = 0;
$admin = true;

// SQL-Anweisung zum Einfügen des neuen Benutzers vorbereiten
$stmt = $conn->prepare("INSERT INTO emensawerbeseite.benutzer (name, email, passwort, admin, anzahlfehler, anzahlanmeldungen) 
                       VALUES (?, ?, ?, ?, ?, ?)");

// Hier binden wir die Parameter an die SQL-Anweisung
$stmt->bind_param("ssssii", $name, $email, $hashed_password, $admin, $anzahlfehler, $anzahlanmeldungen);

// SQL-Anweisung ausführen und Ergebnis überprüfen
if ($stmt->execute()) {
    echo "Neuer Administrator-Benutzer erfolgreich angelegt.";
} else {
    echo "Fehler: " . $stmt->error;
}

// Statement schließen
$stmt->close();

// Verbindung schließen
$conn->close();
