<?php
// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "root";
$password = "emiliebff";
$dbname = "emensawerbeseite";

// Überprüfen, ob das Formular abgeschickt wurde und alle benötigten Felder vorhanden sind
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['gericht_name']) && isset($_POST['beschreibung'])) {
    // Verbindung zur Datenbank herstellen
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Überprüfen der Verbindung
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    // Daten aus dem Formular erhalten
    $gericht_name = $_POST['gericht_name'];
    $beschreibung = $_POST['beschreibung'];
    $ersteller_name = isset($_POST['ersteller_name']) ? $_POST['ersteller_name'] : 'anonym';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    // SQL-Anweisung zum Einfügen der Daten in die Tabelle
    $sql = "INSERT INTO emensawerbeseite.wunschgerichte (name, beschreibung, ersteller_name, email) VALUES (?, ?, ?, ?)";

    // Vorbereitung der SQL-Anweisung
    $stmt = $conn->prepare($sql);

    // Binden der Parameter und Ausführung der SQL-Anweisung
    $stmt->bind_param("ssss", $gericht_name, $beschreibung, $ersteller_name, $email);
    $stmt->execute();

    // Erfolgsmeldung
    echo "<div id='success-message'>Vielen Dank! Dein Wunschgericht wurde gemeldet.</div>";


    // Datenbankverbindung schließen
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <style>

        #success-message{
            text-align: center;
            color: green;
            font-size: 18px;
            margin-bottom: 20px;
        }
        form {
            max-width: 300px;
            margin: 0 auto;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 5px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
<h2>Wunschgericht nennen</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <label for="gericht_name">Name des Gerichts:</label>
    <input type="text" id="gericht_name" name="gericht_name" required>

    <label for="beschreibung">Beschreibung:</label>
    <textarea id="beschreibung" name="beschreibung" rows="4" cols="50"></textarea>

    <label for="ersteller_name">Dein Name:</label>
    <input type="text" id="ersteller_name" name="ersteller_name">

    <label for="email">Deine E-Mail:</label>
    <input type="email" id="email" name="email">

    <input type="submit" value="Wunsch abschicken">
</form>
</body>
</html>
