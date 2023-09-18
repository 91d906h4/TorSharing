<?php
    session_start();
    include dirname(__FILE__).'./include/head.php';
    $U_ID = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
    include dirname(__FILE__).'./include/function.php';
    require dirname(__FILE__).'./include/GoogleAuthenticator.php';
    $error_message = "";
    $temp_2fat = "";

    // 2FA
    $ga = new PHPGangsta_GoogleAuthenticator();

    // Connect MySQL Database
    $link = mysqli_connect('localhost:3306', 'root', 'password');
    $db = mysqli_select_db($link, 'torsharing');

    if(isset($_POST["save"])){
        $temp = mysqli_fetch_assoc(mysqli_query($link, "SELECT U_Salt, U_Password FROM users WHERE U_Username = '".mysqli_real_escape_string($link, $_SESSION["torsharing_username"])."'"));
        if(encypt($_POST["old_password"], $temp["U_Salt"]) != $temp["U_Password"]){echo '<script type="text/javascript">alert("Old password incorrect.");</script>';}
        elseif($_POST["new_password"] != $_POST["confirm_password"]){echo '<script type="text/javascript">alert("Passwords do not match.");</script>';}
        elseif(strlen($_POST["email"]) > 256){echo '<script type="text/javascript">alert("Length of E-Mail cannot be longer than 256 characters."); </script>';}
        elseif(strlen($_POST["introduction"]) > 65535){echo '<script type="text/javascript">alert("Length of introduction cannot be longer than 65,5635 characters."); </script>';}
        else{
            if($_POST["new_password"]){$password = encypt($_POST["new_password"], $temp["U_Salt"]);}
            else{$password = $temp["U_Password"];}
            if(isset($_POST["emable_2fa"])){$FAT = 1;}
            else{$FAT = 0;}
            $sql = "UPDATE users SET U_Password = '".mysqli_real_escape_string($link, $password)."', U_Introduction = '".mysqli_real_escape_string($link, $_POST["introduction"])."', U_Mail = '".mysqli_real_escape_string($link, $_POST["email"])."', U_2FAT = '".$FAT."' WHERE U_Username = '".mysqli_real_escape_string($link, $_SESSION["torsharing_username"])."'";
            mysqli_query($link, $sql);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - Profile</title>
        <script>
            function check(){
                if(document.getElementById("emable_2fa").checked){
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
            <h2>Profile</h2>
            <form action="" method="POST">
                <?php
                    if((!isset($_SESSION["torsharing_username"]) || !isset($_SESSION["torsharing_password"]) || !isset($_SESSION["torsharing_userid"])) || isset($_SESSION["torsharing_2fa"])){
                        echo '<p>Please <a href="/login.php">login</a> to contine.</p>';
                    }
                    elseif(empty($U_ID) || $U_ID == $_SESSION["torsharing_userid"]){
                        if(isset($_POST["edit"])){
                            $sql = "SELECT * FROM users WHERE U_Username = '".mysqli_real_escape_string($link, $_SESSION["torsharing_username"])."'";
                            $result = mysqli_fetch_assoc(mysqli_query($link, $sql));
                            if($result["U_2FAT"] == 1){$temp_2fat = "checked";}
                            $Secret = $result["U_2FA"];
                            $QRCodeURL = $ga -> getQRCodeGoogleUrl("TorSharing", $Secret);
                            echo '<h3 style="margin-top: 10px;">'.htmlspecialchars($result["U_Username"]).'</h3>
                            <p>Introduction</p>
                            <p><textarea style="width: 100%; height: 100px; resize: vertical;" name="introduction" placeholder="Introduce your self...">'.nl2br(htmlspecialchars(strval($result["U_Introduction"]))).'</textarea></p>
                            <p>Enable 2FA : <input type="checkbox" name="emable_2fa" id="emable_2fa" onchange="check();" '.$temp_2fat.' /></p>
                            <div id="2faimg" hidden>
                                <img src="'.$QRCodeURL.'" width="150" />
                                <p><details style="cursor: pointer;"><summary>Details</summary>'.$Secret.'</details></p>
                            </div>
                            <p>E-Mail : <input type="input" name="email" value="'.htmlspecialchars(strval($result["U_Mail"])).'" /></p>
                            <p>Old Password : <input type="password" name="old_password" /></p>
                            <p>New Password : <input type="password" name="new_password" /></p>
                            <p>Confirm New Password : <input type="password" name="confirm_password" /></p>
                            <p style="font-size: 12px;">(Leave the New Password field blank to keep your old password.)</p>
                            <div style="text-align: right;"><input type="submit" name="save" value="Save" /> <input type="button" onclick="location.href=`/profile.php`" value="Cancel" /></div>
                            <hr>';
                        }
                        else{
                            $sql = "SELECT * FROM users WHERE U_Username = '".mysqli_real_escape_string($link, $_SESSION["torsharing_username"])."'";
                            $result = mysqli_fetch_assoc(mysqli_query($link, $sql));
                            echo '<h3 style="margin-top: 10px;">'.htmlspecialchars($result["U_Username"]).'</h3>
                            <p>Introduction</p>
                            <p style="margin-left: 10px;">'.nl2br(htmlspecialchars(strval($result["U_Introduction"]))).'</p>
                            <div style="text-align: right;"><input type="submit" name="edit" value="Edit" /></div>';
                            echo '<hr>';
                            $sql = "SELECT * FROM community WHERE C_Author = '".mysqli_real_escape_string($link, $_SESSION["torsharing_username"])."' ORDER BY C_Time DESC";
                            $result = mysqli_query($link, $sql);
                            echo '<p>Article('.$result -> num_rows.')</p>';
                            if($result -> num_rows == 0){
                                echo '<div id="article_area">
                                    <p>You have not posted any article.</p>
                                </div>';
                            }
                            while($article = mysqli_fetch_assoc($result)){
                                $color = "rgb(".(128 - $article["C_Score"] * 3).", ".(128 + $article["C_Score"] * 3).", 0)";
                                echo '<div id="article_area">
                                    <h3 style="margin: 5px;" id="title_preview"><a href="/community.php?'.$article["C_ID"].'">'.htmlspecialchars($article["C_Title"]).'</a></h3>
                                    <p id="article_preview">'.htmlspecialchars($article["C_Content"]).'</p><p>Score : <span style="color: '.$color.';">'.$article["C_Score"].'</span><br>'.$article["C_Time"].'<p>
                                    <p><a href="/post.php?'.$article["C_ID"].'">Edit</a> <a href="/community.php?DELETE_'.$article["C_ID"].'">Delete</a></p>
                                </div>';
                            }
                        }
                    }
                    else{
                        $sql = "SELECT * FROM users WHERE U_ID = '".mysqli_real_escape_string($link, $U_ID)."'";
                        $return = mysqli_query($link, $sql);
                        $result = mysqli_fetch_assoc($return);
                        if($return -> num_rows == 0){
                            echo "<p>User ID '".$U_ID."' not found.</p>";
                        }
                        else{
                            echo '<h3 style="margin-top: 10px;">'.htmlspecialchars($result["U_Username"]).'</h3>
                            <p>Introduction</p>
                            <p style="margin-left: 10px;">'.nl2br(htmlspecialchars(strval($result["U_Introduction"]))).'</p>
                            <hr>';
                        }
                    }
                ?>
            </form>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>