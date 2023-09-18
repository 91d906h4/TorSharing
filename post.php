<?php
    session_start();
    include dirname(__FILE__).'./include/head.php';
    include dirname(__FILE__).'./include/function.php';
    $C_ID = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
    if(!isset($_SESSION["torsharing_field"])){$_SESSION["torsharing_field"] = "Main";}
    $error_message = "";
    $method = "post";
    $title = "";
    $article = "";
    $show_title = "Post";
    $showfileupload = '<input type="file" name="uploadfile[]" multiple />';

    // Connect MySQL Database
    $link = mysqli_connect('localhost:3306', 'root', 'password');
    $db = mysqli_select_db($link, 'torsharing');

    if(!empty($C_ID)){
        $result = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM community WHERE C_ID = '".mysqli_real_escape_string($link, $C_ID)."'"));
        if($result && $result["C_Author"] == $_SESSION["torsharing_username"]){
            $method = "edit";
            $title = $result["C_Title"];
            $article = $result["C_Content"];
            $show_title = "Edit";
            $showfileupload = "";
        }
        else{header("Location: /post.php");}
    }

    if(isset($_POST["post"]) || isset($_POST["edit"])){
        if(strlen($_POST["title"]) < 5 || strlen($_POST["title"]) > 128){$error_message = "*Title must be 5 - 128 characters in length.";}
        elseif(strlen($_POST["article"]) < 15 || strlen($_POST["article"]) > 65535){$error_message = "*Article must be 15 - 65,535 characters in length.";}
        else{
            if(isset($_POST["post"])){
                $ID = hash("sha256", $_SESSION["torsharing_username"].$_POST["article"].date("Y/m/d H:i:s"));
                $count = count($_FILES['uploadfile']['name']);
                if($_FILES['uploadfile']['name'][0] == ""){$count = 0;}
                if($count > 0 && $count <= 10){
                    mkdir("./file/".$ID."/");
                    for($i = 0; $i <= $count; $i++){
                        if(!empty($_FILES['uploadfile']['tmp_name'][$i]) && pathinfo($_FILES['uploadfile']['tmp_name'][$i])["extension"] != "php"){
                            $tempfile = $_FILES['uploadfile']['tmp_name'][$i];
                            $filename = "./file/".$ID."/".$_FILES['uploadfile']['name'][$i];
                            move_uploaded_file($tempfile, $filename);
                        }
                    }
                }
                elseif($count > 10){$error_message = "*The maximum number of uploading files is 10.";}
                $sql = "INSERT INTO community (C_ID, C_Title, C_Field, C_Author, C_Time, C_Content, C_Score, C_File) VALUES ('".mysqli_real_escape_string($link, $ID)."', '".mysqli_real_escape_string($link, $_POST["title"])."', '".mysqli_real_escape_string($link, $_SESSION["torsharing_field"])."', '".mysqli_real_escape_string($link, $_SESSION["torsharing_username"])."', '".date("Y/m/d H:i:s")."', '".mysqli_real_escape_string($link, $_POST["article"])."', 0, $count)";    
            }
            elseif(isset($_POST["edit"])){
                $ID = $C_ID;
                $sql = "UPDATE community SET C_Title = '".mysqli_real_escape_string($link, $_POST["title"])."', C_Update = '".date("Y/m/d H:i:s")."', C_Content = '".mysqli_real_escape_string($link, $_POST["article"])."' WHERE C_ID = '".mysqli_real_escape_string($link, $C_ID)."'";
            }
            mysqli_query($link, $sql);
            header("Location: /community.php?".$ID);
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - Post</title>
    </head>
    <body>
        <?php include dirname(__FILE__).'./include/header.php' ?>
        <?php include dirname(__FILE__).'./include/menu.php' ?>
        <div id="content">
            <h2><?php echo $show_title." - ".$_SESSION["torsharing_field"]; ?></h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <?php
                    if(!isset($_SESSION["torsharing_username"]) || isset($_SESSION["torsharing_2fa"])){echo '<p>Please <a href="/login.php">login</a> to contine.</p>';}
                    else{
                        echo '<p>Username</p>
                        <p><input style="width: 100%;" type="input" value="'.htmlspecialchars($_SESSION["torsharing_username"]).'" disabled /></p>
                        <p>Title</p>
                        <p><input style="width: 100%;" type="input" name="title" value="'.htmlspecialchars($title).'" /></p>
                        <p>Article</p>
                        <p><textarea style="width: 100%; height: 100px; resize: vertical;" name="article" placeholder="Write your article here...">'.htmlspecialchars(strval($article)).'</textarea></p>
                        <p style="color: red;">'.$error_message.'</p>
                        '.$showfileupload.' <input type="submit" name="'.$method.'" value="Post" /> <input type="button" onclick="location.href=`/community.php?'.strval($C_ID).'`" value="Cancel" />';
                    }
                ?>
            </form>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>