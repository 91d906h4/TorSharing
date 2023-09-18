<?php
    session_start();
    unset($_SESSION["torsharing_captcha"], $_SESSION["torsharing_username"], $_SESSION["torsharing_password"], $_SESSION["torsharing_userid"], $_SESSION["torsharing_language"], $_SESSION["torsharing_2fa"]);
    header("Location: /login.php");
?>