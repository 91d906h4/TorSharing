<div id="menu">
    <ul>
        <li><a href="/"><?php echo __("menu_title_home"); ?></a></li>
        <li><a href="/community.php?"><?php echo __("menu_title_community"); ?></a></li>
        <hr width="50%">
        <?php
            if(isset($_SESSION["torsharing_username"]) && !isset($_SESSION["torsharing_2fa"])){
                echo '<li><a href="/profile.php">'.__("menu_title_profile").'</a></li>
                <li><a href="/logout.php">'.__("menu_title_logout").'</a></li>';
            }
            else{
                echo '<li><a href="/login.php">'.__("menu_title_login").'</a></li>';
            }
        ?>
        <hr width="50%">
        <li><a href="/about.php"><?php echo __("menu_title_about"); ?></a></li>
        <li><a href="/contact.php"><?php echo __("menu_title_contact_us"); ?></a></li>
    </ul>
</div>