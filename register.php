<?php
    session_start();
    if(isset($_SESSION["torsharing_username"], $_SESSION["torsharing_password"], $_SESSION["torsharing_userid"])){header("Location: /");}
    include dirname(__FILE__).'./include/head.php';
    include dirname(__FILE__).'./include/function.php';
    require dirname(__FILE__).'./include/GoogleAuthenticator.php';
    $error_message = "";
    $FAT = 1; // If 2FA function enable

    // 2FA
    $ga = new PHPGangsta_GoogleAuthenticator();
    if(!isset($_SESSION["torsharing_2fa"])){$_SESSION["torsharing_2fa"] = $Secret = $ga -> createSecret(128);}
    else{$Secret = $_SESSION["torsharing_2fa"];}
    $QRCodeURL = $ga -> getQRCodeGoogleUrl("TorSharing", $Secret);

    // Connect MySQL Database
    $link = mysqli_connect('localhost:3306', 'root', 'password');
    $db = mysqli_select_db($link, 'torsharing');

    if(isset($_POST["register"]) && check_input()){
        if($_SESSION["torsharing_captcha"] != $_POST["captcha"]){$error_message = "Captcha is incorrect.";}
        elseif($_POST["password"] != $_POST["confirm_password"]){$error_message = "Passwords do not match.";}
        elseif(strlen($_POST["username"]) > 32){$error_message = "Length of username cannot be longer than 32 characters.";}
        elseif(strlen($_POST["email"]) > 256){$error_message = "Length of E-Mail cannot be longer than 256 characters.";}
        elseif(isset($_POST["2fa"]) && ($ga -> getCode($Secret) != $_POST["2fa_confirm"])){$error_message = "Please makesure you enter the right 2FA code.";}
        else{
            unset($_SESSION["torsharing_captcha"]);
            $sql = "SELECT * FROM users WHERE U_Username = '".mysqli_real_escape_string($link, $_POST["username"])."'";
            $result = mysqli_fetch_assoc(mysqli_query($link, $sql));
            if(!isset($_POST["2fa"])){$FAT = 0;}
            if(!$result){
                $salt = substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);
                $id = substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyz"), 0, 16);
                $sql = "INSERT INTO users (U_ID, U_Username, U_Password, U_Salt, U_Mail, U_2FA, U_2FAT) VALUES ('".$id."', '".mysqli_real_escape_string($link, $_POST["username"])."', '".encypt($_POST["password"], $salt)."', '".$salt."', '".mysqli_real_escape_string($link, $_POST["email"])."', '".$Secret."', '".$FAT."')";
                mysqli_query($link, $sql);
                echo '<script type="text/javascript">alert("Register successful."); window.location.href = "/login.php";</script>';
            }
            else{$error_message = "*Username already exists.";}
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - Register</title>
        <script>
            function check(){
                if(document.getElementById("2fa").checked){
                    document.getElementById("2faimg").hidden = false;
                }
                else{
                    document.getElementById("2faimg").hidden = true;
                }
            }
        </script>
    </head>
    <body>
        <?php include dirname(__FILE__).'./include/header.php' ?>
        <?php include dirname(__FILE__).'./include/menu.php' ?>
        <div id="content">
            <h2>Register</h2>
            <form action="" method="POST">
                <p>Username : <input type="input" name="username" /></p>
                <p>Password : <input type="password" name="password" /></p>
                <p>Confirm Password : <input type="password" name="confirm_password" /></p>
                <p>E-Mail (Optional) : <input type="input" name="email" /></p>
                <p>2FA : <input type="checkbox" name="2fa" id="2fa" onchange="check();" /></p>
                <div id="2faimg" hidden>
                    <img src="<?php echo $QRCodeURL; ?>" width="150" />
                    <p><details style="cursor: pointer;"><summary>Details</summary><?php echo $Secret; ?></details></p>
                    <p style="font-size: 12px;">(Google Authenticator is recommended)</p>
                    <p>2FA Code : <input type="input" name="2fa_confirm" /></p>
                </div>
                <p>Captcha : <input type="input" name="captcha" /></p>
                <img src="/include/captcha.php" width="150" onclick="location.href=''" style="cursor: pointer;" /><br>
                <p style="font-size: 12px;">(Click image to generate a new captcha)</p>
                <p style="color: red;"><?php echo $error_message; ?></p>
                <input type="submit" name="register" value="Register" /> <input type="button" onclick="location.href='/login.php'" value="Cancel" />
            </form>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>