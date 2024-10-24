<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'EntHole (PHP)' ?></title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="shortcut icon" href="/img/favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        Header
    </header>
    <main>
        <?= $content ?>
    </main>
    <footer>
        Footer
    </footer>
</body>
</html>