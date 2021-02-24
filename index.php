<?php

$pages = [
    "statistics",
    "needpass"
];

require_once "config.php";
require_once "api/odmin/init.php";
require_once "api/short-url.php";


$footer = "<ul>
<a target='_blank' href='https://github.com/otsmr/osurl'>
    <li>Projekt auf Github</li>
</a>
<a target='_blank' href='https://oproj.de/privacy'>
    <li>Datenschutz</li>
</a>
<a target='_blank' href='https://oproj.de/imprint'>
    <li>Impressum</li>
</a>
</ul>";

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="robots" content="noindex, nofollow">
    <title>Kurz-URL-Dienst</title>
    <link rel="shortcut icon" href="assets/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/main.css">
</head>
<body>

    <?php

    if (in_array($url, $pages)) {
        require_once "./pages/$url.php";
    }
    else if(!$odmin->is_logged_in()) {
        require_once "./pages/startpage.php";
    }
    else if($rand && $rand !== "error"): ?>

        <main class='form' style='height: 202px; max-width: 365px;'>
            <header>
                <h1>Kurz-URL:</h1>
            </header>
            <div onclick='copyToClipboard("https://osurl.de/<?php echo $rand;?>", this)' class='link'>osurl.de/<?php echo $rand;?></div>
            <br>
            <a href='/'>Einen weiteren Link kürzen.</a>
            <?php echo $footer; ?>
        </main>

    <?php else: ?>

        <main class='form'>
            <form action='/' method='post'>
                <header>
                    <h1>Kurz-URL-Dienst</h1>
                    <?php if ($rand === "error"): ?>
                        <p>Kurz-URL nicht verfügbar</p>
                    <?php endif;?>
                </header>
                <input autofocus name='link' placeholder='Link' required>
                <input name='custom' placeholder='Individuelle Kurz-Url' type="text">
                <input name='pass' placeholder='Passwort' type='password'>
                <button class='button' type='submit'>Erstellen</button>
            </form>
            <?php echo $footer; ?>
        </main>
    
    <?php endif; ?>


    <?php if ($odmin->is_logged_in()): ?>
        <a class='logout' href='<?php echo $odmin->get_signout_url(); ?>'>Abmelden</a>
    <?php endif; ?>

    <script src="assets/main.js"></script>

</body>
</html>