<?php
/**
 * Praktikum DBWT. Autoren:
 * Rabia, Türe, 3674806
 * Sofia, Moll, 3637355
 */

// Dateipfad zur Übersetzungsdatei
$filename = "en.txt";

// Überprüfen, ob das Suchwort über GET-Parameter überhaupt übergeben wurde
if(isset($_GET['suche'])) {
    // Das Suchwort aus dem GET-Parameter wird gelesen
    $searchWord = $_GET['suche'];

    // Datei öffnen und nach dem Suchwort suchen
    $fileContent = file_get_contents($filename);
    $lines = explode("\n", $fileContent);
    $foundTranslation = false;

    foreach ($lines as $line) {
        $parts = explode(";", $line);
        // Überprüfen, ob das Suchwort in der aktuellen Zeile enthalten ist
        if (isset($parts[0]) && trim($parts[0]) === $searchWord) {
            // Wenn das Suchwort gefunden wurde, gebe die Übersetzung aus
            echo "Übersetzung des Wortes '{$searchWord}': {$parts[1]}";
            $foundTranslation = true;
            break;
        }
    }

    // Wenn das Suchwort nicht gefunden wurde, gib einen Hinweistext aus
    if (!$foundTranslation) {
        echo "Das gesuchte Wort '{$searchWord}'ist nicht enthalten.";
    }
} else {
    // Wenn kein Suchwort übergeben wurde, gib einen Hinweistext aus
    echo "Geben Sie ein Suchwort über den GET-Parameter 'suche' an.";
}
?>
