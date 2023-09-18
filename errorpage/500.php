<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - 500 Internal Server Error</title>
        <?php include dirname(__DIR__).'/include/head.php' ?>
    </head>
    <body>
        <?php include dirname(__DIR__).'./include/header.php' ?>
        <?php include dirname(__DIR__).'./include/menu.php' ?>
        <div id="content">
            <h2>500 Internal Server Error</h2>
            <p>There was an error. Please try again later.</p>
        </div>
        <?php include dirname(__DIR__).'./include/footer.php' ?>
    </body>
</html>