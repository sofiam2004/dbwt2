<?php

class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function authenticate($email, $password)
    {
        $sql = "SELECT * FROM emensawerbeseite.benutzer WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Verwenden Sie den in der Datenbank gespeicherten Salt
            $salt = 'wxyz'; // Oder nehmen Sie den Salt aus der Datenbank, falls vorhanden
            $password_with_salt = $password . $salt;

            // Vergleichen Sie das gehashte Passwort in der Datenbank mit dem eingegebenen Passwort
            if (password_verify($password_with_salt, $user['passwort'])) {
                return true; // Authentifizierung erfolgreich
            }
        }
        return false; // Authentifizierung fehlgeschlagen
    }
}
