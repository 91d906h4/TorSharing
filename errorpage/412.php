<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - 412 Precondition Failed</title>
        <?php include dirname(__DIR__).'/include/head.php' ?>
    </head>
    <body>
        <?php include dirname(__DIR__).'./include/header.php' ?>
        <?php include dirname(__DIR__).'./include/menu.php' ?>
        <div id="content">
            <h2>412 Precondition Failed</h2>
            <p>The request was not completed due to preconditions that are set in the request header.</p>
        </div>
        <?php include dirname(__DIR__).'./include/footer.php' ?>
    </body>
</html>