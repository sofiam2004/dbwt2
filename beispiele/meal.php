<?php
/**
 * Praktikum DBWT. Autoren:
 * Rabia, Türe, 3674806
 * Sofia, Moll, 3637355
 */

const GET_PARAM_MIN_STARS = 'search_min_stars';
const GET_PARAM_SEARCH_TEXT = 'search_text';
const GET_PARAM_SHOW_DESCRIPTION = 'show_description';
const GET_PARAM_LANGUAGE = 'sprache';
const GET_PARAM_RATING_TYPE = 'rating_type';

/**
 * List of all allergens.
 */
$allergens = [
    11 => 'Gluten',
    12 => 'Krebstiere',
    13 => 'Eier',
    14 => 'Fisch',
    17 => 'Milch',
];

$meal = [
    'name' => 'Süßkartoffeltaschen mit Frischkäse und Kräutern gefüllt',
    'description' => 'Die Süßkartoffeln werden vorsichtig aufgeschnitten und der Frischkäse eingefüllt.',
    'price_intern' => 2.90,
    'price_extern' => 3.90,
    'allergens' => [11, 13],
    'amount' => 42  // Number of available meals
];

$ratings = [
    [   'text' => 'Die Kartoffel ist einfach klasse. Nur die Fischstäbchen schmecken nach Käse.',
        'author' => 'Ute U.',
        'stars' => 2 ],
    [   'text' => 'Sehr gut. Immer wieder gerne',
        'author' => 'Gustav G.',
        'stars' => 4 ],
    [   'text' => 'Der Klassiker für den Wochenstart. Frisch wie immer',
        'author' => 'Renate R.',
        'stars' => 4 ],
    [   'text' => 'Kartoffel ist gut. Das Grüne ist mir suspekt.',
        'author' => 'Marta M.',
        'stars' => 3 ]
];

// Default language is German
$lang = 'de';
if (!empty($_GET[GET_PARAM_LANGUAGE]) && in_array($_GET[GET_PARAM_LANGUAGE], ['de', 'en'])) {
    $lang = $_GET[GET_PARAM_LANGUAGE];
}

// Language texts
$texts = [
    'de' => [
        'meal' => 'Gericht',
        'description_toggle' => 'Beschreibung ein-/ausblenden',
        'price_intern' => 'Preis intern',
        'price_extern' => 'Preis extern',
        'ratings' => 'Bewertungen',
        'filter' => 'Filter',
        'search' => 'Suchen',
        'author' => 'Autor:in',
        'allergens' => 'Zugehörige Allergene',
        'mean_rating' => 'Insgesamt',
        'top' => 'TOP Bewertungen',
        'flopp' => 'FLOPP Bewertungen',
        'all' => 'Alle Bewertungen'
    ],
    'en' => [
        'meal' => 'Meal',
        'description_toggle' => 'Toggle description',
        'price_intern' => 'Price internal',
        'price_extern' => 'Price external',
        'ratings' => 'Ratings',
        'filter' => 'Filter',
        'search' => 'Search',
        'author' => 'Author',
        'allergens' => 'Related allergens',
        'mean_rating' => 'Overall',
        'top' => 'TOP ratings',
        'flopp' => 'FLOPP ratings',
        'all' => 'All ratings'
    ]
];

//4g)

$showRatings = [];
$searchTerm = '';
if (!empty($_GET[GET_PARAM_SEARCH_TEXT])) {
    $searchTerm = strtolower($_GET[GET_PARAM_SEARCH_TEXT]);
    foreach ($ratings as $rating) {
        if (stripos($rating['text'], $searchTerm) !== false) {
            $showRatings[] = $rating;
        }
    }
    // zu stripos geändert
} else if (!empty($_GET[GET_PARAM_MIN_STARS])) {
    $minStars = $_GET[GET_PARAM_MIN_STARS];
    foreach ($ratings as $rating) {
        if ($rating['stars'] >= $minStars) {
            $showRatings[] = $rating;
        }
    }
} else if (!empty($_GET[GET_PARAM_RATING_TYPE])) {
    $ratingType = $_GET[GET_PARAM_RATING_TYPE];
    if ($ratingType == 'top') {
        $maxStars = max(array_column($ratings, 'stars'));
        foreach ($ratings as $rating) {
            if ($rating['stars'] == $maxStars) {
                $showRatings[] = $rating;
            }
        }
    } else if ($ratingType == 'flopp') {
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

function calcMeanStars(array $ratings) : float {
    $sum = 0; //war vorher 1
    foreach ($ratings as $rating) {
        $sum += $rating['stars'];
    }
    return count($ratings) > 0 ? $sum / count($ratings) : 0;
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
    <title><?php echo $texts[$lang]['meal']; ?>: <?php echo $meal['name']; ?></title>
    <style>
        * {
            font-family: Arial, serif;
        }
        .rating {
            color: darkgray;
        }
        #description {
            display: <?php echo $showDescription ? 'block' : 'none'; ?>;
        }
    </style>
    <script>
        function toggleDescription() {
            var desc = document.getElementById('description');
            if (desc.style.display === 'none') {
                desc.style.display = 'block';
            } else {
                desc.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<h1><?php echo $texts[$lang]['meal']; ?>: <?php echo $meal['name']; ?></h1>
<button onclick="toggleDescription()"><?php echo $texts[$lang]['description_toggle']; ?></button>
<p id="description"><?php echo $meal['description']; ?></p>
<p><?php echo $texts[$lang]['price_intern']; ?>: <?php echo number_format($meal['price_intern'], 2, ',', '.') . '€'; ?></p>
<p><?php echo $texts[$lang]['price_extern']; ?>: <?php echo number_format($meal['price_extern'], 2, ',', '.') . '€'; ?></p>
<!-- Einfügen € -->
<h1><?php echo $texts[$lang]['ratings']; ?> (<?php echo $texts[$lang]['mean_rating']; ?>: <?php echo calcMeanStars($ratings); ?>)</h1>
<form method="get">
    <label for="search_text"><?php echo $texts[$lang]['filter']; ?>:</label>
    <input id="search_text" type="text" name="search_text" value="<?php echo htmlspecialchars($_GET[GET_PARAM_SEARCH_TEXT] ?? '', ENT_QUOTES); ?>">
    <input type="hidden" name="<?php echo GET_PARAM_LANGUAGE; ?>" value="<?php echo $lang; ?>">
    <input type="submit" value="<?php echo $texts[$lang]['search']; ?>">
</form>
<p>
    <a href="?sprache=<?php echo $lang; ?>&rating_type=top"><?php echo $texts[$lang]['top']; ?></a> |
    <a href="?sprache=<?php echo $lang; ?>&rating_type=flopp"><?php echo $texts[$lang]['flopp']; ?></a> |
    <a href="?sprache=<?php echo $lang; ?>"><?php echo $texts[$lang]['all']; ?></a>
</p>
<table class="rating">
    <thead>
    <tr>
        <td>Text</td>
        <td>Sterne</td>
        <td><?php echo $texts[$lang]['author']; ?></td>
        <!-- Author ausgeben -->
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($showRatings as $rating) {
        echo "<tr><td class='rating_text'>{$rating['text']}</td>
                      <td class='rating_stars'>{$rating['stars']}</td>
                      <td class='rating_author'>{$rating['author']}</td></tr>";
    }
    ?>
    </tbody>
</table>

<h2><?php echo $texts[$lang]['allergens']; ?>:</h2>
<ul>
    <?php
    foreach ($meal['allergens'] as $allergenId) {
        if (isset($allergens[$allergenId])) {
            echo "<li>{$allergens[$allergenId]}</li>";
        }
    }
    //Allergene
    ?>
</ul>

<p>
    <a href="?sprache=de">Deutsch</a> | <a href="?sprache=en">English</a>
</p>

</body>
</html>
