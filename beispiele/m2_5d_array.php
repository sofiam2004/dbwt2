<?php
/**
 * Praktikum DBWT. Autoren:
 * Rabia, Türe, 3674806
 * Sofia, Moll, 3637355
 */
// Array mit berühmten Mahlzeiten und den dazugehörigen Gewinnjahren
$famousMeals = [
    1 => ['name' => 'Currywurst mit Pommes', 'winner' => [2001, 2003, 2007, 2010, 2020]],
    2 => ['name' => 'Hähnchencrossies mit Paprikareis', 'winner' => [2002, 2004, 2008]],
    3 => ['name' => 'Spaghetti Bolognese', 'winner' => [2011, 2012, 2017]],
    4 => ['name' => 'Jägerschnitzel mit Pommes', 'winner' => [2019]]
];

// Funktion zur Ausgabe der geordneten Liste mit Namen und Gewinnjahren
function printFamousMeals($meals): void
{
    // Sortieren des Arrays nach dem Namen der Mahlzeiten
    ksort($meals);

    // Iteration über jedes Element des Arrays
    foreach ($meals as $key => $meal) {
        // Ausgabe des Namens der Mahlzeit
        echo "$key. {$meal['name']} <br>";

        // Sortieren der Gewinnjahre in aufsteigender Reihenfolge
        sort($meal['winner']);

        // Ausgabe der Gewinnjahre mit Komma getrennt
        echo implode(", ", $meal['winner']) . "<br><br>";
    }
}

// Ausgabe der geordneten Liste
printFamousMeals($famousMeals);

// Funktion zur Berechnung der Jahre ohne Gewinner seit 2000
function findYearsWithoutWinner($meals): array
{
    $years = range(2000, date('Y')); // Array mit den Jahren von 2000 bis zum aktuellen Jahr
    $winningYears = []; // Array zur Speicherung der Jahre mit Gewinnern

    // Iteration über jedes Element des Arrays
    foreach ($meals as $meal) {
        // Zusammenführen der Gewinnjahre aller Mahlzeiten
        $winningYears = array_merge($winningYears, $meal['winner']);
    }

    // Rückgabe der Jahre ohne Gewinner seit 2000
    return array_diff($years, $winningYears);
}

// Ausgabe der Jahre ohne Gewinner seit 2000
echo "Jahre ohne Gewinner seit 2000: " . implode(", ", findYearsWithoutWinner($famousMeals));

