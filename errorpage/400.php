<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - 400 Bad Request</title>
        <?php include dirname(__DIR__).'/include/head.php' ?>
    </head>
    <body>
        <?php include dirname(__DIR__).'./include/header.php' ?>
        <?php include dirname(__DIR__).'./include/menu.php' ?>
        <div id="content">
            <h2>400 Bad Request</h2>
            <p>Your client has issued a malfomed or illegal request.</p>
        </div>
        <?php include dirname(__DIR__).'./include/footer.php' ?>
    </body>
</html>