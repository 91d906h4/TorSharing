<?php
    session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - 404 Not Found</title>
        <?php include dirname(__DIR__).'/include/head.php' ?>
    </head>
    <body>
        <?php include dirname(__DIR__).'./include/header.php' ?>
        <?php include dirname(__DIR__).'./include/menu.php' ?>
        <div id="content">
            <h2>404 Not Found</h2>
            <p>The requested URL '<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]); ?>' was not found on this server.</p>
        </div>
        <?php include dirname(__DIR__).'./include/footer.php' ?>
    </body>
</html>