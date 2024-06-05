<?php

public function updateStats($numberOfDishes, $numberOfNewsletterSignups, $counter) {
    $del = "DELETE FROM emensawerbeseite.zahlen";
    mysqli_query($this->link, $del);

    $sql_zahlen = "INSERT INTO zahlen (anzahl_gerichte, anzahl_anmeldungen, anzahl_besucher) 
                       VALUES ($numberOfDishes, $numberOfNewsletterSignups, $counter)";
    mysqli_query($this->link, $sql_zahlen);
}
