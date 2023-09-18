<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - 401 Unauthorized</title>
        <?php include dirname(__DIR__).'/include/head.php' ?>
    </head>
    <body>
        <?php include dirname(__DIR__).'./include/header.php' ?>
        <?php include dirname(__DIR__).'./include/menu.php' ?>
        <div id="content">
            <h2>401 Unauthorized</h2>
            <p>Access is denied due to invalid credentials.</p>
        </div>
        <?php include dirname(__DIR__).'./include/footer.php' ?>
    </body>
</html>