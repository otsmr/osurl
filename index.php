<?php
error_reporting(-1);
require_once __DIR__ . "/api/shortUrl.php";

?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kurz-URL-Dienst</title>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="main.css">
</head>
<body>

    <?php if($url === "needPass"): ?>

    <main class='form' style='height: 202px; max-width: 365px;'>
        <form method='post'>
            <header>
                <h1>Passwortgeschützt</h1>
            </header>
            <input autofocus name='passProt' placeholder='Passwort' type='password'>

            <button class='button' type='submit'>Weiter</button>
        </form>
    </main>

    <?php elseif(!$logged): ?>

        <main>
            <header>
                <h1>Kurz-URL-Dienst</h1>
                <p class='desc'>Ein Service von <a href='https://oproj.de'>oproj.de.</a></p>
            </header>
            <a class='button' href="<?php echo $CONFIG["odmin_base_url"] ?>/login?service=<?php echo $CONFIG["odmin_service_name"] ?>" >Anmelden<a>
            <a class='imprint' href='https://oproj.de/privacy'>Privacy & Imprint</a>
        </main>

    <?php elseif($rand && $rand !== "error"): ?>

        <main class='form' style='height: 202px; max-width: 365px;'>
            <form method='post'>
                <header>
                    <h1>Kurz-URL:</h1>
                </header>
                <div onclick='copyToClipboard("https://osurl.de/<?php echo $rand;?>", this)' class='link'>osurl.de/<?php echo $rand;?></div>
                <br>
                <a href='/'>Einen weiteren Link kürzen.</a>
                <a class='imprint'>Zum Kopieren den Link anklicken.</a>
            </form>
        </main>

        <script>

        function copyToClipboard(text, e) {
            if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                const textarea = document.createElement("textarea");
                textarea.textContent = text;
                textarea.style.position = "fixed";
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    e.className = "link copyed";
                    setTimeout(() => {
                        e.className = "link";
                    }, 100);
                    return document.execCommand("copy");
                } catch (ex) {
                    return false;
                } finally {
                    document.body.removeChild(textarea);
                }
            }
        }
        </script>
    
    <?php else: ?>

        <a class='logout' href='<?php echo $CONFIG["odmin_base_url"] ?>/api/logout/<?php echo $_COOKIE["token"] ?>?service=<?php echo $CONFIG["odmin_service_name"] ?>'>Logout</a>

        <main class='form'>
            <form action='/'  method='post'>
                <header>
                    <h1>Kurz-URL-Dienst</h1>
                    <?php if ($rand === "error"): ?>
                        <p>Kurz-URL nicht verfügbar</p>
                    <?php endif;?>
                </header>
                <input autofocus name='link' placeholder='Link eingeben.' required>
                <input name='custom' placeholder='Kurz-URL' type="text">
                <input name='pass' placeholder='Passwort' type='password'>
                <button class='button' type='submit'>Erstellen</button>
            </form>
        </main>
    
    <?php endif; ?>
</body>
</html>