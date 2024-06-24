<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "kategorie"
 */

require_once(__DIR__ . '/db_connection.php');

function db_kategorie_select_all() {
    $link = connectdb();

    $sql = "SELECT * FROM emensawerbeseite.kategorie";
    $result = mysqli_query($link, $sql);

    $data = mysqli_fetch_all($result, MYSQLI_BOTH);

    mysqli_close($link);
    return $data;
}