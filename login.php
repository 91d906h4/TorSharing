<?php
    session_start();
    if(isset($_SESSION["torsharing_username"], $_SESSION["torsharing_password"], $_SESSION["torsharing_userid"]) && !isset($_SESSION["torsharing_2fa"])){header("Location: /");}
    include dirname(__FILE__).'./include/head.php';
    include dirname(__FILE__).'./include/function.php';
    $error_message = "";

    // Connect MySQL Database
    $link = mysqli_connect('localhost:3306', 'root', 'password');
    $db = mysqli_select_db($link, 'torsharing');

    if(isset($_POST["login"]) && check_input()){
        if($_SESSION["torsharing_captcha"] != $_POST["captcha"]){$error_message = "Captcha is incorrect.";}
        else{
            unset($_SESSION["torsharing_captcha"]);
            $sql = "SELECT * FROM users WHERE U_Username = '".mysqli_real_escape_string($link, $_POST["username"])."'";
            $result = mysqli_fetch_assoc(mysqli_query($link, $sql));
            if($result){
                if(encypt($_POST["password"], $result["U_Salt"]) == $result["U_Password"]){
                    $_SESSION["torsharing_username"] = $_POST["username"];
                    $_SESSION["torsharing_password"] = $result["U_Password"];
                    $_SESSION["torsharing_userid"] = $result["U_ID"];
                    if($result["U_2FAT"] == 1){
                        $_SESSION["torsharing_2fa"] = $result["U_2FA"];
                        header("Location: /2fa.php");
                    }
                    else{
                        unset($_SESSION["torsharing_2fa"]);
                        header("Location: /");
                    }
                }
                else{$error_message = "*Username or password incorrect.";}
            }
            else{$error_message = "*Username or password incorrect.";}
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - Login</title>
    </head>
    <body>
        <?php include dirname(__FILE__).'./include/header.php' ?>
        <?php include dirname(__FILE__).'./include/menu.php' ?>
        <div id="content">
            <h2>Login</h2>
            <form action="" method="POST">
                <p>Username : <input type="input" name="username" /></p>
                <p>Password : <input type="password" name="password" /></p>
                <p>Captcha : <input type="input" name="captcha" /></p>
                <img src="/include/captcha.php" width="150" onclick="location.href=''" style="cursor: pointer;" /><br>
                <p style="font-size: 12px;">(Click image to generate a new captcha)</p>
                <p style="color: red;"><?php echo $error_message; ?></p>
                <input type="submit" name="login" value="Login" /> <input type="button" onclick="location.href='/register.php'" value="Register" />
            </form>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>