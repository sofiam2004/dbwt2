<?php

session_start();

// Verbindung zur Datenbank herstellen
$servername = "localhost";
$username = "root";
$password = "emiliebff";
$dbname = "emensawerbeseite";

$conn = new mysqli($servername, $username, $password, $dbname);

// Überprüfen, ob die Verbindung erfolgreich hergestellt wurde
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Benutzereingaben sichern
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Starten der Transaktion
    $conn->begin_transaction();

    try {
        // SQL-Injection verhindern (optional, wenn bereits durch Prepared Statements gesichert)
        $email = mysqli_real_escape_string($conn, $email);

        // Benutzer abfragen
        $stmt = $conn->prepare("SELECT id, name, passwort, admin, anzahlfehler, anzahlanmeldungen 
                FROM emensawerbeseite.benutzer WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Benutzer gefunden, Passwort überprüfen
            $row = $result->fetch_assoc();
            $stored_password = $row['passwort'];
            if (password_verify($password, $stored_password)) {
                // Passwort stimmt überein, Benutzer ist erfolgreich angemeldet
                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['admin'] = $row['admin'];

                // Letzte Anmeldung aktualisieren
                $now = date('Y-m-d H:i:s');
                $stmt_update_last_login = $conn->prepare("UPDATE emensawerbeseite.benutzer SET letzteanmeldung = ? WHERE id = ?");
                $stmt_update_last_login->bind_param("si", $now, $_SESSION['user_id']);
                $stmt_update_last_login->execute();

                // Anzahl der erfolgreichen Anmeldungen erhöhen
                $anzahlanmeldungen = $row['anzahlanmeldungen'] + 1;
                $stmt_update_login_count = $conn->prepare("UPDATE emensawerbeseite.benutzer 
                                                                    SET anzahlanmeldungen = ? WHERE id = ?");
                $stmt_update_login_count->bind_param("ii", $anzahlanmeldungen, $_SESSION['user_id']);
                $stmt_update_login_count->execute();

                // Transaktion bestätigen
                $conn->commit();

                // Weiterleitung oder andere Aktion nach erfolgreicher Anmeldung
                header("Location: /werbeseite.php");
                exit();
            } else {
                // Falsches Passwort, Anzahl der Fehler erhöhen
                $anzahlfehler = $row['anzahlfehler'] + 1;
                $stmt_update_error_count = $conn->prepare("UPDATE emensawerbeseite.benutzer 
                                                                SET anzahlfehler = ?, letzterfehler = NOW() WHERE id = ?");
                $stmt_update_error_count->bind_param("ii", $anzahlfehler, $row['id']);
                $stmt_update_error_count->execute();

                // Transaktion rückgängig machen (bei fehlerhaftem Login)
                $conn->rollback();

                // Fehlermeldung oder andere Aktion bei falschem Passwort
                $error_message = "Falsches Passwort. Bitte versuchen Sie es erneut.";
            }
        } else {
            // Benutzer nicht gefunden
            $error_message = "Benutzer mit dieser E-Mail-Adresse existiert nicht.";
        }

        $stmt->close();
    } catch (Exception $e) {
        // Bei einem Fehler die Transaktion rückgängig machen
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }
}

// Verbindung zur Datenbank schließen
$conn->close();
?>