<?php
require_once(__DIR__ . '/../models/database.php');
require_once(__DIR__ . '/../models/benutzer.php');
require_once(__DIR__ . '/../models/db_connection.php');

class LoginController
{
    private $user;

    public function __construct()
    {
        $database = new Database(); // Datenbankverbindung herstellen
        $db = $database->getConnection();
        $this->user = new User($db); // Benutzer-Objekt für die Authentifizierung
    }

    public function showLoginForm($error = '', $success = '')
    {
        // Sicherstellen, dass die Variablen Strings sind
        $error = $_SESSION["error"] ?? '';
        unset($_SESSION["error"]);

        // Verwenden Sie Laravel's View-Rendering mit Variablen
        return view('login', ['error' => $error]);
    }

    public function verifyLogin(): void
    {
        $anmeldungLog = FrontController::logger();
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->authenticate($email, $password)) {
                // Benutzer erfolgreich authentifiziert, inkrementiere_anmeldungen aufrufen
                $this->inkrementiere_anmeldungen($email);

                header('Location: /werbeseite');
                $anmeldungLog->info("Erfolgreich angemeldet");
                exit();
            } else {
                $error = "Falsche E-Mail oder Passwort.";
                $_SESSION["error"] = $error;

                header('Location: /anmeldung');
                $anmeldungLog->warning("Anmeldung fehlgeschlagen: E-Mail oder Passwort falsch.");
                exit();
            }
        } else {
            $error = "Bitte füllen Sie beide Felder aus.";
            $anmeldungLog->warning("Anmeldung fehlgeschlagen: Felder nicht ausgefüllt");
            $_SESSION["error"] = $error;

            header('Location: /anmeldung');
            exit();
        }
    }

    private function inkrementiere_anmeldungen($email)
    {
        $database = new Database();
        $db = $database->getConnection();

        try {
            // Benutzer-ID anhand der E-Mail abrufen
            $stmt = $db->prepare("SELECT id FROM emensawerbeseite.benutzer WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $benutzer_id = $row['id'];

                // Prozedur inkrementiere_anmeldungen aufrufen
                $stmt_increment = $db->prepare("CALL inkrementiere_anmeldungen(?)");
                $stmt_increment->bind_param("i", $benutzer_id);
                $stmt_increment->execute();
                $stmt_increment->close();
            }

            $stmt->close();
        } catch (Exception $e) {
            // Fehlerbehandlung
            error_log("Fehler bei inkrementiere_anmeldungen: " . $e->getMessage());
        }
    }

    public function logout(): void
    {
        session_status();
        $_SESSION = array();

        session_destroy();
        session_regenerate_id();

        header('Location: /werbeseite');

        $abmeldungLog = FrontController::logger();
        $abmeldungLog->info("Erfolgreich abgemeldet");
        exit();
    }
}
