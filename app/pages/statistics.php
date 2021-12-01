<?php
require_once __DIR__ . "/../api/db.php";
$s = $dbh->prepare("SELECT * FROM `stats` WHERE urlID IN (SELECT urlID FROM shorturls WHERE userID = ?)");
$s->execute(array((int) $odmin->session->user_id));
?>

<div class="container">

<table>

    <tr>
        <th scope="col">Url</th>
        <th scope="col">IP</th>
        <th scope="col">Datum</th>
        <th scope="col">Land</th>
        <th scope="col">Stadt</th>
    </tr>
    <?php while($row = $s->fetch()): ?>
    <tr>
        <td><?php echo $row["urlID"]; ?></td>
        <td><?php echo $row["ip"]; ?></td>
        <td><?php echo $row["date"]; ?></td>
        <td><?php echo $row["country"]; ?></td>
        <td><?php echo $row["city"]; ?></td>

    </tr>
    <?php endwhile; ?>

</table>

</div>