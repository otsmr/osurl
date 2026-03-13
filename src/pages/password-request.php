<?php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header("Location: /");
    die();
}
?>

<main class='form' style='height: 202px; max-width: 365px;'>
    <form method='post'>
        <header>
            <h1>Passwortgeschützt</h1>
        </header>
        <input autofocus name='passProt' placeholder='Passwort' type='password'>

        <button class='button' type='submit'>Weiter</button>
        <?php include __DIR__ . "/../includes/footer.php" ?>
    </form>
</main>
