<?php
require_once(__DIR__ . '/db_connection.php');

class Database
{
    private $conn;

    public function __construct()
    {
        $config = include($_SERVER['DOCUMENT_ROOT'] . '/../config/db.php'); // Pfade entsprechend deiner Ordnerstruktur anpassen
        $this->conn = new mysqli(
            $config['host'],
            $config['user'],
            $config['password'],
            $config['database']
        );

        if ($this->conn->connect_error) {
            die("Verbindung fehlgeschlagen: " . $this->conn->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}


