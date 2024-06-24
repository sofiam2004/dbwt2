<?php
//require_once($_SERVER['DOCUMENT_ROOT'] . '/../models/database.php');
//require_once($_SERVER['DOCUMENT_ROOT'] . '/../models/benutzer.php');
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
