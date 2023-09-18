<?php
    session_start();
    include dirname(__FILE__).'./include/head.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - <?php echo __("website_title_home"); ?></title>
    </head>
    <body>
        <?php include dirname(__FILE__).'./include/header.php' ?>
        <?php include dirname(__FILE__).'./include/menu.php' ?>
        <div id="content">
            <h2>Welcome to TorSharing!</h2>
            <p>TorSharing is a forums that you can post anything here. We have a strict <a href="/about.php#no-logs-policy">no-logs policy</a> to keep you anonymous while visiting our website.</p>
            <h2>Recent Updates</h2>
            <p>
                <strong>[2022/07/06]</strong> |Version 1.1.0| Add channel, 2AF function. / Home pgae update. <a href="/logs/.update/version_1.1.0.txt" target="_blank">Read more&#8230;</a><br>
                <strong>[2022/06/26]</strong> |Version 1.0.5| Add article posting, comment posting, profile viewing function. <a href="/logs/.update/version_1.0.5.txt" target="_blank">Read more&#8230;</a>
            </p>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>