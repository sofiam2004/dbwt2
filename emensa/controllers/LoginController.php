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
        $error = is_string($error) ? $error : '';
        $success = is_string($success) ? $success : '';

        // Verwenden Sie Laravel's View-Rendering mit Variablen
        return view('login', ['error' => $error, 'success' => $success]);
    }

    public function verifyLogin()
    {
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if ($this->user->authenticate($email, $password)) {
                $success = "Anmeldung erfolgreich!";
                return $this->showLoginForm('', $success); // Login-Formular mit Erfolgsmeldung anzeigen
            } else {
                $error = "Falsche E-Mail oder Passwort.";
                return $this->showLoginForm($error); // Login-Formular mit Fehlermeldung anzeigen
            }
        } else {
            $error = "Bitte füllen Sie beide Felder aus.";
            return $this->showLoginForm($error); // Login-Formular mit Fehlermeldung anzeigen
        }
    }
}
