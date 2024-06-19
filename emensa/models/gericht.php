<?php
/**
 * Diese Datei enthält alle SQL Statements für die Tabelle "gerichte"
 */
function db_gericht_select_all() {
    try {
        $link = connectdb();

        $sql = 'SELECT id, name, beschreibung FROM emensawerbeseite.gericht ORDER BY name';
        $result = mysqli_query($link, $sql);

        $data = mysqli_fetch_all($result, MYSQLI_BOTH);

        mysqli_close($link);
    }
    catch (Exception $ex) {
        $data = array(
            'id'=>'-1',
            'error'=>true,
            'name' => 'Datenbankfehler '.$ex->getCode(),
            'beschreibung' => $ex->getMessage());
    }
    finally {
        return $data;
    }

}

function getGerichteFromDatabase(): array
{
    $link = connectdb();

    $sql = "SELECT gericht.name, preis_intern, preis_extern, GROUP_CONCAT(gha.code) AS codes FROM gericht JOIN gericht_hat_allergen gha on gericht.id = gha.gericht_id GROUP BY gericht.id LIMIT 5;";

    $result = mysqli_query($link, $sql);

    $gerichte = mysqli_fetch_all($result, MYSQLI_BOTH);

    mysqli_close($link);

    return $gerichte;
}

function getDishNumber()
{
    $link = connectdb();

    $sql = "SELECT COUNT(*) FROM gericht";

    $result = mysqli_query($link, $sql);

    $count = mysqli_fetch_all($result, MYSQLI_BOTH)[0][0];

    mysqli_close($link);

    return $count;
}
