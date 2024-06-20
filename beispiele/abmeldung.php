<?php
// Start der Session
session_start();

// Session löschen
$_SESSION = array(); // Alle Session-Variablen löschen

// Session zerstören
session_destroy();

// Weiterleitung zur Hauptseite (werbeseite.blade.php)
header("views/werbeseite");
exit;

