<?php
/**
 * Praktikum DBWT. Autoren:
 * Sofia, Moll, Matrikelnummer1
 * Rabia, Türe, Matrikelnummer2
 */

const GET_PARAM_MIN_STARS = 'search_min_stars';
const GET_PARAM_SEARCH_TEXT = 'search_text';
const GET_PARAM_SHOW_DESCRIPTION = 'show_description';
const GET_PARAM_LANGUAGE = 'language';
const GET_PARAM_TOP_FLOP = 'top_flop';

$languages = [
    'de' => [
        'meal' => 'Gericht',
        'description' => 'Beschreibung',
        'ratings' => 'Bewertungen',
        'filter' => 'Filter',
        'search' => 'Suchen',
        'text' => 'Text',
        'stars' => 'Sterne',
        'author' => 'Autor',
        'allergens' => 'Allergene',
        'price_intern' => 'Preis intern',
        'price_extern' => 'Preis extern',
        'mean_rating' => 'Insgesamt',
        'top' => 'Top-Bewertungen',
        'flop' => 'Flop-Bewertungen'
    ],
    'en' => [
        'meal' => 'Meal',
        'description' => 'Description',
        'ratings' => 'Ratings',
        'filter' => 'Filter',
        'search' => 'Search',
        'text' => 'Text',
        'stars' => 'Stars',
        'author' => 'Author',
        'allergens' => 'Allergens',
        'price_intern' => 'Price internal',
        'price_extern' => 'Price external',
        'mean_rating' => 'Overall',
        'top' => 'Top ratings',
        'flop' => 'Flop ratings'
    ]
];

// Set default language
$language = 'de';
if (!empty($_GET[GET_PARAM_LANGUAGE]) && array_key_exists($_GET[GET_PARAM_LANGUAGE], $languages)) {
    $language = $_GET[GET_PARAM_LANGUAGE];
}
$trans = $languages[$language];

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
} else if (!empty($_GET[GET_PARAM_TOP_FLOP])) {
    if ($_GET[GET_PARAM_TOP_FLOP] == 'TOP') {
        $maxStars = max(array_column($ratings, 'stars'));
        foreach ($ratings as $rating) {
            if ($rating['stars'] == $maxStars) {
                $showRatings[] = $rating;
            }
        }
    } else if ($_GET[GET_PARAM_TOP_FLOP] == 'FLOP') {
        $minStars = min(array_column($ratings, 'stars'));
        foreach ($ratings as $rating) {
            if ($rating['stars'] == $minStars) {
                $showRatings[] = $rating;
            }
        }
    }
} else {
    $showRatings = $ratings;
}

function calcMeanStars(array $ratings): float {
    if (count($ratings) == 0) return 0;
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
<html lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8"/>
    <title><?php echo $trans['meal']; ?>: <?php echo $meal['name']; ?></title>
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
<h1><?php echo $trans['meal']; ?>: <?php echo $meal['name']; ?></h1>
<?php if ($showDescription) : ?>
    <p><?php echo $trans['description']; ?>: <?php echo $meal['description']; ?></p>
<?php endif; ?>
<h2><?php echo $trans['allergens']; ?>:</h2>
<ul>
    <?php foreach ($meal['allergens'] as $allergen) : ?>
        <li><?php echo $allergens[$allergen]; ?></li>
    <?php endforeach; ?>
</ul>
<p><?php echo $trans['price_intern']; ?>: <?php echo number_format($meal['price_intern'], 2, ',', '') . '€'; ?></p>
<p><?php echo $trans['price_extern']; ?>: <?php echo number_format($meal['price_extern'], 2, ',', '') . '€'; ?></p>
<h1><?php echo $trans['ratings']; ?> (<?php echo $trans['mean_rating']; ?>: <?php echo calcMeanStars($ratings); ?>)</h1>
<form method="get">
    <label for="search_text"><?php echo $trans['filter']; ?>:</label>
    <input id="search_text" type="text" name="search_text"
           value="<?php echo !empty($_GET[GET_PARAM_SEARCH_TEXT]) ? $_GET[GET_PARAM_SEARCH_TEXT] : ''; ?>">
    <input type="submit" value="<?php echo $trans['search']; ?>">
    <input type="hidden" name="<?php echo GET_PARAM_LANGUAGE; ?>" value="<?php echo $language; ?>">
</form>
<table class="rating">
    <thead>
    <tr>
        <td><?php echo $trans['text']; ?></td>
        <td><?php echo $trans['stars']; ?></td>
        <td><?php echo $trans['author']; ?></td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($showRatings as $rating) : ?>
        <tr>
            <td><?php echo $rating['text']; ?></td>
            <td><?php echo str_repeat('⭐', $rating['stars']); ?></td>
            <td><?php echo $rating['author']; ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<a href="?<?php echo GET_PARAM_LANGUAGE; ?>=de"><?php echo $languages['de']['meal']; ?></a> | <a href="?<?php echo GET_PARAM_LANGUAGE; ?>=en"><?php echo $languages['en']['meal']; ?></a>
</body>
</html>
