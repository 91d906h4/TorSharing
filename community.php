<?php
    session_start();
    include dirname(__FILE__).'./include/head.php';
    include dirname(__FILE__).'./include/function.php';
    $C_ID = parse_url($_SERVER["REQUEST_URI"], PHP_URL_QUERY);
    if(!isset($_SESSION["torsharing_field"])){$_SESSION["torsharing_field"] = "Main";}

    // Connect MySQL Database
    $link = mysqli_connect('localhost:3306', 'root', 'password');
    $db = mysqli_select_db($link, 'torsharing');

    if(in_array($C_ID, ["Main", "Anime", "NSFW", "News"])){
        $_SESSION["torsharing_field"] = $C_ID;
        header("Location: /community.php");
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - Community - <?php echo $_SESSION["torsharing_field"]; ?></title>
    </head>
    <body>
        <?php include dirname(__FILE__).'./include/header.php' ?>
        <?php include dirname(__FILE__).'./include/menu.php' ?>
        <div id="content">
            <form action="" method="POST" enctype="multipart/form-data">
                <?php
                    if(!isset($_SESSION["torsharing_username"]) || isset($_SESSION["torsharing_2fa"])){echo '<h2>Community</h2><p>Please <a href="/login.php">login</a> to contine.</p>';}
                    elseif(empty($C_ID)){
                        echo '<h2>Community - '.htmlspecialchars($_SESSION["torsharing_field"]).'</h2>
                            <div id="article_area">
                                <p><h3><a href="?Main">Main</a> <a href="?Anime">Anime</a> <a href="?News">News</a> <a href="?NSFW">NSFW</a></h3></p>
                            </div>
                            <div id="article_area"><p>
                            <input type="button" onclick="location.href=`/post.php`" value="Post Article" /><hr>
                                <input type="input" name="sreach_condition" placehplder="Search..." />
                                <select name="search_area">
                                    <option value="content">Content</option>
                                    <option value="author">Author</option>
                                    <option value="time">Time</option>
                                </select>
                                <input type="submit" name="search" value="Search" />
                            </p></div>';
                        if(isset($_POST["search"]) && !empty($_POST["sreach_condition"])){
                            $serch_area = array("content" => "C_Content", "author" => "C_Author", "time" => "C_Time")[$_POST["search_area"]];
                            $sql = "SELECT * FROM community LEFT JOIN users ON community.C_Author = users.U_Username WHERE C_Field = '".mysqli_real_escape_string($link, $_SESSION["torsharing_field"])."' AND ".mysqli_real_escape_string($link, $serch_area)." LIKE '%".mysqli_real_escape_string($link, $_POST["sreach_condition"])."%' ORDER BY C_Time DESC";
                        }
                        else{
                            $sql = "SELECT * FROM community LEFT JOIN users ON community.C_Author = users.U_Username WHERE C_Field = '".mysqli_real_escape_string($link, $_SESSION["torsharing_field"])."' ORDER BY C_Time DESC";
                        }
                        $result = mysqli_query($link, $sql);
                        if($result -> num_rows == 0){
                            echo '<div id="article_area">
                                <p>No article found.</p>
                            </div>';
                        }
                        while($article = mysqli_fetch_assoc($result)){
                            $color = "rgb(".(128 - $article["C_Score"] * 3).", ".(128 + $article["C_Score"] * 3).", 0)";
                            echo '<div id="article_area">
                                <h3 style="margin: 5px;" id="title_preview"><a href="?'.$article["C_ID"].'">'.htmlspecialchars($article["C_Title"]).'</a></h3>
                                <p id="article_preview">'.htmlspecialchars($article["C_Content"]).'</p>
                                <p>Score : <span style="color: '.$color.';">'.$article["C_Score"].'</span><br>'.$article["C_Time"].' By : <a href="/profile.php?'.$article["U_ID"].'">'.htmlspecialchars($article["C_Author"]).'</a><p>';
                            if($article["C_Author"] == $_SESSION["torsharing_username"]){echo '<p><a href="/post.php?'.$article["C_ID"].'">Edit</a> <a href="/community.php?DELETE_'.$article["C_ID"].'">Delete</a></p>';}
                            echo '</div>';
                        }
                    }
                    else{
                        if(str_starts_with($C_ID, "DELETE_")){
                            $temp = str_replace("DELETE_", "", $C_ID);
                            if(strlen($temp) == 97 && $temp[64] == "?"){
                                $temp = explode("?", $temp);
                                $result = mysqli_query($link, "SELECT * FROM torsharing_2.".mysqli_real_escape_string($link, $temp[0])." WHERE M_ID = '".mysqli_real_escape_string($link, $temp[1])."'");
                                if($result -> num_rows > 0 && mysqli_fetch_assoc($result)["M_Author"] == $_SESSION["torsharing_username"]){
                                    mysqli_query($link, "DELETE FROM torsharing_2.".mysqli_real_escape_string($link, $temp[0])." WHERE M_ID = '".mysqli_real_escape_string($link, $temp[1])."'");
                                    mysqli_query($link, "UPDATE torsharing.community SET C_Score = C_Score - 1 WHERE C_ID = '".mysqli_real_escape_string($link, $temp[0])."'");
                                    remove_dir('./file/'.strval($temp[1]).'/');
                                    echo '<script type="text/javascript">alert("Delete comment successful."); window.location.href = "/community.php?'.htmlspecialchars($temp[0]).'";</script>';
                                }
                            }
                            else{
                                $result = mysqli_query($link, "SELECT C_Author FROM torsharing.community WHERE C_ID = '".mysqli_real_escape_string($link, $temp)."'");
                                if($result -> num_rows > 0 && mysqli_fetch_assoc($result)["C_Author"] == $_SESSION["torsharing_username"]){
                                    mysqli_query($link, "DELETE FROM torsharing.community WHERE C_ID = '".mysqli_real_escape_string($link, $temp)."'");
                                    remove_dir('./file/'.$temp.'/');
                                    $tempcomment = mysqli_fetch_assoc(mysqli_query($link, "SELECT M_ID FROM torsharing_2.".mysqli_real_escape_string($link, $temp)));
                                    if($tempcomment){foreach($tempcomment as $item){remove_dir('./file/'.$item.'/');}}
                                    mysqli_query($link, "DROP TABLE IF EXISTS torsharing_2.".mysqli_real_escape_string($link, $temp));
                                    echo '<script type="text/javascript">alert("Delete article successful."); window.location.href = "/community.php";</script>';
                                }
                            }
                        }
                        $db = mysqli_select_db($link, 'torsharing');
                        $sql = "SELECT * FROM community LEFT JOIN users ON community.C_Author = users.U_Username WHERE C_ID = '".mysqli_real_escape_string($link, $C_ID)."'";
                        $result = mysqli_query($link, $sql);
                        if($result -> num_rows > 0){
                            $article = mysqli_fetch_assoc($result);
                            $color = "rgb(".(128 - $article["C_Score"] * 3).", ".(128 + $article["C_Score"] * 3).", 0)";
                            if($article["C_Update"]){$update = '<br>Last Update : '.$article["C_Update"];}
                            else{$update = '';}
                            echo '
                            <h2><a href="/community.php" style="text-decoration: none;">< '.$_SESSION["torsharing_field"].'</a> - '.htmlspecialchars($article["C_Title"]).'</h2>
                            <p>Score : <span style="color: '.$color.';">'.$article["C_Score"].'</span><br>'.$article["C_Time"].' By : <a href="/profile.php?'.$article["U_ID"].'">'.htmlspecialchars($article["C_Author"]).'</a>'.$update.'<p>
                            <p style="margin-left: 10px;">'.nl2br(htmlspecialchars($article["C_Content"])).'</p>';
                            if($article["C_File"] > 0){
                                echo '<p>';
                                $root = "./file/".$article["C_ID"]."/";
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
                            if($article["C_Author"] == $_SESSION["torsharing_username"]){echo '<p><a href="/post.php?'.htmlspecialchars($article["C_ID"]).'">Edit</a> <a href="/community.php?DELETE_'.htmlspecialchars($article["C_ID"]).'">Delete</a></p>';}
                            echo '<hr>';
                            include dirname(__FILE__).'./include/comment.php';
                        }
                        else{
                            echo "<h2>Community</h2>
                            <p>Article ID '".htmlspecialchars($C_ID)."' not found.<br><a href='/community.php'>Back to community.</a></p>";
                        }
                    }
                ?>
            </form>
        </div>
        <?php include dirname(__FILE__).'./include/footer.php' ?>
    </body>
</html>