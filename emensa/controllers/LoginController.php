<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/../models/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/../models/benutzer.php');

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

    public function verifyLogin()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->authenticate($email, $password)) {
                header('Location: /werbeseite');
                exit();
            } else {
                $error = "Falsche E-Mail oder Passwort.";
                $_SESSION["error"] = $error;

                header('Location: /anmeldung');
                exit();
            }
        } else {
            $error = "Bitte füllen Sie beide Felder aus.";
            $_SESSION["error"] = $error;

            header('Location: /anmeldung');
            exit();
        }
    }

    public function logout()
    {
        session_destroy();
        session_regenerate_id();

        header('Location: /werbeseite');
        exit();
    }
}
