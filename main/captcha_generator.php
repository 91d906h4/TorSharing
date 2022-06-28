<?php
    session_start();
    header('Content-type: image/jpeg');

    $random = substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
    $_SESSION["torsharing_captcha"] = $random;

    // Setting
    $width = 200;
    $height = 100;
    $text = $random;
    $fontfile = dirname(__DIR__)."ttf_file_path";

    $image = imagecreatefromgif(dirname(__DIR__)."_gif_file_paath");
    $text_color = imagecolorallocate($image, 255, 255, 255);

    // Generate image
    imagettftext($image, 70, rand(-10, 10), rand(0, 50), rand(90, 110), $text_color, $fontfile, $text);

    // Output image
    imagejpeg($image);
    imagedestroy($image);
?>
