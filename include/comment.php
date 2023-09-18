<?php
    $error_message = "";

    // Connect MySQL Database
    $link = mysqli_connect('localhost:3306', 'root', 'password');
    
    if(isset($_POST["comment"])){
        if(strlen($_POST["article"]) < 10 || strlen($_POST["article"]) >65535){$error_message = "*Comment must be 10 - 65,535 characters in length.";}
        else{
            $M_ID = substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 32);
            mysqli_query($link, "UPDATE torsharing.community SET C_Score = C_Score + 1 WHERE C_ID = '".mysqli_real_escape_string($link, $C_ID)."'");
            $count = count($_FILES['uploadfile']['name']);
            if($_FILES['uploadfile']['name'][0] == ""){$count = 0;}
            if($count > 0 && $count <= 10){
                mkdir("./file/".$M_ID."/");
                for($i = 0; $i <= $count; $i++){
                    if(!empty($_FILES['uploadfile']['tmp_name'][$i]) && pathinfo($_FILES['uploadfile']['tmp_name'][$i])["extension"] != "php"){
                        $tempfile = strval($_FILES['uploadfile']['tmp_name'][$i]);
                        $filename = "./file/".strval($M_ID)."/".strval($_FILES['uploadfile']['name'][$i]);
                        move_uploaded_file($tempfile, $filename);
                    }
                }
            }
            elseif($count > 10){$error_message = "*The maximum number of uploading files is 10.";}
            mysqli_query($link, "INSERT INTO torsharing_2.".mysqli_real_escape_string($link, $C_ID)." (M_ID, M_Author, M_Comment, M_Time, M_File) VALUES ('".mysqli_real_escape_string($link, $M_ID)."', '".mysqli_real_escape_string($link, $_SESSION["torsharing_username"])."', '".mysqli_real_escape_string($link, $_POST["article"])."', '".date("Y/m/d H:i:s")."', $count)");
        }
    }
?>
<p>Comment</p>
<div id="article_area">
    <p>Username</p>
    <p><input style="width: 100%;" type="input" value="<?php echo htmlspecialchars($_SESSION["torsharing_username"]); ?>" disabled /></p>
    <p>Comment</p>
    <p><textarea style="width: 100%; height: 100px; resize: vertical;" name="article" placeholder="Write your article here..."></textarea></p>
    <p style="color: red;"><?php echo $error_message; ?></p>
    <p><input type="file" name="uploadfile[]" multiple /> <input type="submit" name="comment" value="Post" /></p>
</div>
<hr>
<?php
    $db = mysqli_select_db($link, 'torsharing_2');
    $sql = "CREATE TABLE IF NOT EXISTS `".mysqli_real_escape_string($link, $C_ID)."` (
    M_ID VARCHAR(32) NOT NULL PRIMARY KEY,
    M_Author VARCHAR(64) NOT NULL,
    M_Comment BLOB NOT NULL,
    M_Time VARCHAR(45) NOT NULL,
    M_File INT NULL DEFAULT 0)";
    mysqli_query($link, $sql);

    $result = mysqli_query($link, "SELECT * FROM `".mysqli_real_escape_string($link, $C_ID)."` LEFT JOIN torsharing.users ON torsharing.users.U_Username = torsharing_2.".mysqli_real_escape_string($link, $C_ID).".M_Author ORDER BY M_Time DESC");
    while($comment = mysqli_fetch_assoc($result)){
        echo '<div id="article_area">
            <p><a href="/profile.php?'.$comment["U_ID"].'">'.htmlspecialchars($comment["M_Author"]).'</a> '.$comment["M_Time"].'</p>
            <p style="margin-left: 10px;">'.nl2br(htmlspecialchars($comment["M_Comment"])).'</p>';
            if($comment["M_File"] > 0){
                echo '<p>';
                $root = "./file/".strval($comment["M_ID"])."/";
                $file = scandir($root."/");
                for($i = 2; $i < count($file); $i++){
                    if(getimagesize($root.$file[$i])){
                        echo '<a href="'.htmlspecialchars($root.$file[$i]).'" target="_blanck"><img src="'.htmlspecialchars($root.$file[$i]).'" style="max-height: 200px;"title="'.htmlspecialchars($file[$i]).'" /></a>';
                    }
                    else{
                        echo 'FILE : <a download href="'.htmlspecialchars($root.$file[$i]).'" target="_blanck">'.htmlspecialchars($file[$i]).'</a>';
                    }
                    echo '<br>';
                }
                echo '</p>';
            }
            if($comment["M_Author"] == $_SESSION["torsharing_username"]){echo '<p><a href="?DELETE_'.htmlspecialchars($C_ID).'?'.htmlspecialchars($comment["M_ID"]).'">Delete</a></p>';}
        echo '</div>';
    }
?>