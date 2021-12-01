
<?php if($status["shorten_url"] !== ""): ?>

    <main class='form' style='height: 202px; max-width: 365px;'>
        <header>
            <h1>Kurz-URL</h1>
        </header>
        <div onclick="copyToClipboard('https://osurl.de/<?php echo $status["shorten_url"];?>', this)" class="link">osurl.de/<?php echo $status["shorten_url"];?></div>
        <br>
        <a href='/'>Einen weiteren Link kürzen.</a>
        <?php include __DIR__ . "/../includes/footer.php" ?>
    </main>

<?php else: ?>

    <main class='form'>
        <form action='/' method='post'>
            <header>
                <h1>Kurz-URL-Dienst</h1>
                <?php if ($status["error_message"] !== ""): ?>
                    <p><?php echo $status["error_message"]; ?></p>
                <?php endif;?>
            </header>
            <input autofocus name='link' placeholder='Link' required>
            <input name='custom' placeholder='Individuelle Kurz-Url' type="text">
            <input name='pass' placeholder='Passwort' type='password'>
            <div class="flex">
                <div>
                    <input type="checkbox" name="enable-statistics" id="a">
                </div>
                <label for="a">
                    Statistik für diesen Link aktivieren
                </label>
            </div>
            <button class='button' type='submit'>Erstellen</button>
        </form>
        <?php include __DIR__ . "/../includes/footer.php" ?>
    </main>

<?php endif; ?>