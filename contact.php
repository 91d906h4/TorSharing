<?php
    session_start();
    include dirname(__FILE__).'./include/head.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - Contact Us</title>
    </head>
    <body>
        <?php include dirname(__FILE__).'./include/header.php' ?>
        <?php include dirname(__FILE__).'./include/menu.php' ?>
        <div id="content">
            <h2>Contact us</h2>
            <p>You can contact us with the follwing methods:</p>
            <ul>
                <li>Mail2Tor : <a href="mailto:torsharing@mail2tor.com">torsharing@mail2tor.com</a></li>
                <li>Jabber : <a href="mailto:torsharing@jabbers.one">torsharing@jabbers.one</a></li>
            </ul>
            <h2>PGP public key</h2>
            <p><a href="../../../PGP_PUBLIC_KEY.txt" target="_blank">4C99 344A 9D1E 2F90 0A52 59D5 373E 3146 3A3F 1CFA</a></p>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>