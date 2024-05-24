<?php
/**
 * Praktikum DBWT. Autoren:
 * Rabia, Türe, 3674806
 * Sofia, Moll, 3637355
 */

function addieren($a, $b)
{
    return $a + $b;
}

function multiplizieren ($a, $b): int {
    return $a * $b;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['addieren']) {
        $ergebnis = addieren($_POST['a'], $_POST['b']);
        echo "Das Ergebnis der Addition von {$_POST['a']} und {$_POST['b']} ist: $ergebnis";
    }
    elseif ($_POST['multiplizieren']){
        $ergebnis = $_POST['a'] * $_POST['b'];
        echo "Das Ergebnis der Multiplikation von {$_POST['a']} und {$_POST['b']} ist: $ergebnis";
    }
}

?>

<h2>Addieren und Multiplizieren</h2>

<form method="post">
    <label for="a">A:</label>
    <input type="text" id="a" name="a"><br><br>

    <label for="b">B:</label>
    <input type="text" id="b" name="b"><br><br>

    <input type="submit" name="addieren" value="Addieren">
    <input type="submit" name="multiplizieren" value="Multiplizieren">
</form>
