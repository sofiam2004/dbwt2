<?php


        function isEmailValid($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) &&
                !preg_match('/@(rcpt\.at|damnthespam\.at|wegwerfmail\.de|trashmail\.)/', $email);
        }

        function isValidName($name) {
            return !empty(trim($name));
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $sprache = trim($_POST['sprache']);
            $datenschutz = isset($_POST['datenschutzbestimmungen']) ? $_POST['datenschutzbestimmungen'] : '';

            $errors = [];

            if (!isValidName($name)) {
                $errors[] = "Der Name darf nicht leer sein.";
            }

            if (!isEmailValid($email)) {
                $errors[] = "Die angegebene E-Mail Adresse entspricht nicht den Vorgaben.";
            }

            if (empty($datenschutz)) {
                $errors[] = "Sie müssen den Datenschutzbestimmungen zustimmen.";
            }

            if (empty($errors)) {
                $filePath =__DIR__ . 'anmeldungen.txt';
                $entry = "$name|$email|$sprache|" . date('Y-m-d H:i:s') . PHP_EOL;
                if (file_put_contents($filePath, $entry, FILE_APPEND)) {
                    $successMessage = "Danke für Ihre Anmeldung, $name! Ihre Daten wurden erfolgreich gespeichert.";
                } else {
                    $errors[] = "Leider ist ein Fehler beim Speichern Ihrer Daten aufgetreten. Bitte versuchen Sie es erneut.";
                }
            }
        }
        ?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Newsletter-Anmeldung</title>
</head>
<body>
    <h1>Newsletter-Anmeldung</h1>
    <?php if (!empty($errors)): ?>
        <p>Fehler bei der Anmeldung:</p>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
        <p><a href="werbeseite.php">Zurück zur Anmeldeseite</a></p>
    <?php elseif (isset($successMessage)): ?>
        <p><?php echo htmlspecialchars($successMessage); ?></p>
        <p><a href="werbeseite.php">Zurück zur Startseite</a></p>
    <?php endif; ?>
</body>
</html>
