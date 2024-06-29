<?php
require_once(__DIR__ . '/db_connection.php');

function getAllergeneFromDatabase(): array
{
    $link = connectdb();

    $sql = "SELECT DISTINCT code FROM emensawerbeseite.allergen";

    $allergene_res = mysqli_query($link, $sql);

    $allergene = array();
    while ($allergen = mysqli_fetch_assoc($allergene_res)) {
        $allergene[] = $allergen['code'];
    }

    mysqli_close($link);

    return $allergene;
}