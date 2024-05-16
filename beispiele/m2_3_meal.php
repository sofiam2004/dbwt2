<?php
/**
 * Praktikum DBWT. Autoren:
 * Sofia, Moll, Matrikelnummer1
 * Rabia, Türe, Matrikelnummer2
 *
const GET_PARAM_MIN_STARS = 'search_min_stars';
const GET_PARAM_SEARCH_TEXT = 'search_text';
const GET_PARAM_SHOW_DESCRIPTION = 'show_description';

/**
 * List of all allergens.
 */
$allergens = [
    11 => 'Gluten',
    12 => 'Krebstiere',
    13 => 'Eier',
    14 => 'Fisch',
    17 => 'Milch'
];

$meal = [
    'name' => 'Süßkartoffeltaschen mit Frischkäse und Kräutern gefüllt',
    'description' => 'Die Süßkartoffeln werden vorsichtig aufgeschnitten und der Frischkäse eingefüllt.',
    'price_intern' => 2.90,
    'price_extern' => 3.90,
    'allergens' => [11, 13],
    'amount' => 42             // Number of available meals
];

$ratings = [
    [
        'text' => 'Die Kartoffel ist einfach klasse. Nur die Fischstäbchen schmecken nach Käse.',
        'author' => 'Ute U.',
        'stars' => 2
    ],
    [
        'text' => 'Sehr gut. Immer wieder gerne',
        'author' => 'Gustav G.',
        'stars' => 4
    ],
    [
        'text' => 'Der Klassiker für den Wochenstart. Frisch wie immer',
        'author' => 'Renate R.',
        'stars' => 4
    ],
    [
        'text' => 'Kartoffel ist gut. Das Grüne ist mir suspekt.',
        'author' => 'Marta M.',
        'stars' => 3
    ]
];

$showRatings = [];
if (!empty($_GET[GET_PARAM_SEARCH_TEXT])) {
    $searchTerm = strtolower($_GET[GET_PARAM_SEARCH_TEXT]); // Konvertierung des Suchbegriffs in Kleinbuchstaben
    foreach ($ratings as $rating) {
        if (strpos(strtolower($rating['text']), $searchTerm) !== false) { // Konvertierung des Bewertungstexts in Kleinbuchstaben
            $showRatings[] = $rating;
        }
    }
} else if (!empty($_GET[GET_PARAM_MIN_STARS])) {
    $minStars = $_GET[GET_PARAM_MIN_STARS];
    foreach ($ratings as $rating) {
        if ($rating['stars'] >= $minStars) {
            $showRatings[] = $rating;
        }
    }
} else {
    $showRatings = $ratings;
}

function calcMeanStars(array $ratings): float {
    $sum = 0; // Korrektur des Anfangswerts der Summe
    foreach ($ratings as $rating) {
        $sum += $rating['stars']; // Hinzufügen der Sterne jedes Ratings zur Summe
    }
    return $sum / count($ratings); // Berechnung des Mittelwerts
}

// Anzeige der Beschreibung des Gerichts basierend auf dem GET-Parameter "show_description"
$showDescription = true; // Standardwert für die Anzeige der Beschreibung
if (!empty($_GET[GET_PARAM_SHOW_DESCRIPTION]) && $_GET[GET_PARAM_SHOW_DESCRIPTION] == '0') {
    $showDescription = false;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8"/>
    <title>Gericht: <?php echo $meal['name']; ?></title>
    <style>
        * {
            font-family: Arial, serif;
        }

        .rating {
            color: darkgray;
        }
    </style>
</head>
<body>
<h1>Gericht: <?php echo $meal['name']; ?></h1>
<?php if ($showDescription) : ?>
    <p><?php echo $meal['description']; ?></p>
<?php endif; ?>
<h1>Bewertungen (Insgesamt: <?php echo calcMeanStars($ratings); ?>)</h1>
<form method="get">
    <label for="search_text">Filter:</label>
    <input id="search_text" type="text" name="search_text"
           value="<?php echo !empty($_GET[GET_PARAM_SEARCH_TEXT]) ? $_GET[GET_PARAM_SEARCH_TEXT] : ''; ?>">
    <input type="submit" value="Suchen">
</form>
<table class="rating">
    <thead>
    <tr>
        <td>Text</td>
        <td>Sterne</td>
        <td>Autor</td> <!-- Hinzufügen der Spalte für den Autor der Bewert