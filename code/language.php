<?php
    if(!isset($_SESSION["torsharing_language"])){$_SESSION["torsharing_language"] = "en";}

    // Dictionary
    $language = array(
        "en" => array(
            "menu_title_home" => "Home",
            "menu_title_community" => "Comunity",
            "menu_title_profile" => "Profile",
            "menu_title_logout" => "Logout",
            "menu_title_login" => "Login",
            "menu_title_about" => "About",
            "menu_title_contact_us" => "Contact Us",

            "website_title_home" => "Home",
            "website_title_cpmmunity" => "Comunity",
            "website_title_profile" => "Profile",
            "website_title_login" => "Login",
            "website_title_about" => "About",
            "website_title_contactus" => "Contact Us",
            "website_title_register" => "Register",

            "website_subtitle" => "A forums on Tor, a community for the world.",
            "connect_without_tor_alert_message" => "WARNING : YOU ARE NOT ACCESSING THIS WEBSITE THROUGH TOR NETWORK."
        ),
        "zh" => array(
            "menu_title_home" => "首頁",
            "menu_title_community" => "社群",
            "menu_title_profile" => "個人檔案",
            "menu_title_logout" => "登出",
            "menu_title_login" => "登入",
            "menu_title_about" => "關於",
            "menu_title_contact_us" => "聯繫我們",

            "website_title_home" => "首頁",
            "website_title_cpmmunity" => "社群",
            "website_title_profile" => "個人檔案",
            "website_title_login" => "登入",
            "website_title_about" => "關於",
            "website_title_contactus" => "聯繫我們",
            "website_title_register" => "註冊",

            "website_subtitle" => "一個在 Tor 上的論壇，一個為全世界的社群。",
            "connect_without_tor_alert_message" => "警告 : 您目前的連線並非經由 Tor 網路。"
            )
    );

    function __($string){
        global $language;
        return $language[$_SESSION["torsharing_language"]][$string];
    }
?>
