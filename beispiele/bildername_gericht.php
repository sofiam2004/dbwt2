<?php
// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "datenbankname";

// Verbindung zur Datenbank
$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfen der Verbindung
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Beispiel für das Hochladen und Speichern eines Bildes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['bild'])) {
    $gericht_id = $_POST['gericht_id']; // Annahme: ID wird aus dem Formular gesendet
    $bild = $_FILES['bild'];
    $bild_name = $gericht_id . '_' . basename($bild['name']);
    $zielverzeichnis = '/public/img/gerichte/' . $bild_name;

    // Verschieben der hochgeladenen Datei in das Zielverzeichnis
    if (move_uploaded_file($bild['tmp_name'], $zielverzeichnis)) {
        // Aktualisieren der Tabelle gericht mit dem Bildnamen
        $sql = "UPDATE gericht SET bildname = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $bild_name, $gericht_id);
        $stmt->execute();

        echo "Bild erfolgreich hochgeladen und Datenbank aktualisiert.";
    } else {
        echo "Fehler beim Hochladen des Bildes.";
    }
}

// Verbindung schließen
$conn->close();
?><?php
