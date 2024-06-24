<?php

// Verbindung zur Datenbank herstellen und Link zurückgeben
function connectdb() {
$link = mysqli_connect(
"localhost",    // Host der Datenbank
"root",         // Benutzername zur Anmeldung
"emiliebff",    // Passwort
"emensawerbeseite" // Auswahl der Datenbanken (bzw. des Schemas)
);

// Überprüfen, ob die Verbindung erfolgreich hergestellt wurde
if (!$link) {
die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

return $link;
}