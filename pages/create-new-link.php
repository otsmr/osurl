<?php

if($rand && $rand !== "error"): ?>

    <main class='form' style='height: 202px; max-width: 365px;'>
        <header>
            <h1>Kurz-URL:</h1>
        </header>
        <div onclick='copyToClipboard("https://osurl.de/<?php echo $rand;?>", this)' class='link'>osurl.de/<?php echo $rand;?></div>
        <br>
        <a href='/'>Einen weiteren Link kürzen.</a>
        <?php include "../includes/footer.php" ?>
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
        <?php include "../includes/footer.php" ?>
    </main>

<?php endif; ?>