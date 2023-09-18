<?php
    session_start();
    if(isset($_SESSION["torsharing_username"], $_SESSION["torsharing_password"], $_SESSION["torsharing_userid"]) && !isset($_SESSION["torsharing_2fa"])){header("Location: /");}
    include dirname(__FILE__).'./include/head.php';
    include dirname(__FILE__).'./include/function.php';
    require dirname(__FILE__).'./include/GoogleAuthenticator.php';
    $error_message = "";

    $ga = new PHPGangsta_GoogleAuthenticator();

    // Connect MySQL Database
    $link = mysqli_connect('localhost:3306', 'root', 'password');
    $db = mysqli_select_db($link, 'torsharing');

    if(isset($_POST["2faconfirm"])){
        if($ga -> getCode($_SESSION["torsharing_2fa"]) == $_POST["2fa"]){
            unset($_SESSION["torsharing_2fa"]);
            header("Location: /");
        }
        else{$error_message = "*2FA code incorrect.";}
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - 2FA</title>
    </head>
    <body>
        <?php include dirname(__FILE__).'./include/header.php' ?>
        <?php include dirname(__FILE__).'./include/menu.php' ?>
        <div id="content">
            <h2>2FA</h2>
            <form action="" method="POST">
                <p>2FA Code : <input type="input" name="2fa" /></p>
                <p style="color: red;"><?php echo $error_message; ?></p>
                <input type="submit" name="2faconfirm" value="Confirm" /> <input type="button" onclick="location.href='/logout.php'" value="Cancel" />
            </form>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>