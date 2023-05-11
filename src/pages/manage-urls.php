<?php
require_once __DIR__ . "/../api/db.php";

if (isset($_POST["delete-urlid"])) {
    $s = $dbh->prepare("DELETE FROM shorturls WHERE urlID = ?");
    $s->execute(array($_POST["delete-urlid"]));
}

$s = $dbh->prepare("SELECT *, (SELECT COUNT(*) FROM stats WHERE stats.urlID = shorturls.urlID) as calls  FROM `shorturls` WHERE userID = ?");
$s->execute(array((int) $odmin->session->user_id));
?>

<div class="container">

<table>

    <tr>
        <th scope="col">Url</th>
        <th scope="col">Erstellt</th>
        <th scope="col">Aufrufe</th>
        <th scope="col">Löschen</th>
        <th scope="col">Link</th>
    </tr>
    <?php while($row = $s->fetch()): ?>
    <tr>
        <td><?php echo $row["urlID"]; ?></td>
        <td><?php echo $row["created"]; ?></td>
        <td><?php echo $row["calls"]; ?></td>
        <td>
            <form method="POST">
                <button type="submit" name="delete-urlid" value="<?php echo $row['urlID']; ?>">Löschen</button>
            </form>
        </td>
        <td><?php echo $row["link"]; ?></td>

    </tr>
    <?php endwhile; ?>

</table>

</div>