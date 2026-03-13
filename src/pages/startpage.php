<?php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header("Location: /");
    die();
}
?>

<main>
    <header>
        <h1>Kurz-URL-Dienst</h1>
        <p class='desc'>Ein Service von <a href='https://tsmr.eu'>tsmr.eu.</a></p>
    </header>
    <a href="<?php echo $odmin->get_signin_url(); ?>" ><button class="button center">Anmelden</button><a>
    <?php include __DIR__ . "/../includes/footer.php" ?>
</main>
