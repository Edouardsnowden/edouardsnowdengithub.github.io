

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Activation de compte</h1>
    <p>
        Pour activer votre compte, veillez cliquer sur ce lien:
        <a href="<?= WEBSITE_URL.'/activation.php?p='.$pseudo.'&amp;token='.$token; ?>">Lien d'activation</a>
    </p>
</body>
</html>