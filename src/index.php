<?php

$status = [
    "error_message" => "",
    "shorten_url" => ""
];

$url = "";
if (isset($_GET["url"]))
    $url = (string) $_GET["url"];
    
$pages = [
    "statistics",
    "password-request",
    "manage-urls"
];

if ($url !== "" && !in_array($url, $pages)) {
    require_once "api/redirect.php";
}

require_once "api/odmin/init.php";
$odmin->init_session_from_cookie();

if ($_SERVER["REQUEST_METHOD"] === "POST" && $odmin->is_logged_in()) {

    if (isset($_POST["link"]) && $_POST["link"] !== "") {
        require_once "api/short-url.php";
    }
}

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

    if ($odmin->is_logged_in()) {

        include __DIR__ . "/includes/nav.php";

        if (in_array($url, $pages)) {
            require_once __DIR__ . "/pages/$url.php";
        } 
        else require_once __DIR__ . "/pages/create-new-link.php";
    
        ?>
        <a class='logout' href='<?php echo $odmin->get_signout_url(); ?>'>Abmelden</a>
        <?php

    } else {
        require_once __DIR__ . "/pages/startpage.php";
    }
    ?>

    <script src="assets/main.js"></script>

</body>
</html>