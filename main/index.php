<?php
    session_start();
    include dirname(__FILE__).'./path_to_head.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <title>TorSharing - <?php echo __("website_title_home"); ?></title>
    </head>
    <body>
        <?php include dirname(__FILE__).'./path_to_header.php' ?>
        <?php include dirname(__FILE__).'./path_to_menu.php' ?>
        <div id="content">
            <h2>Welcome to TorSharing!</h2>
            <p>TorSharing is a forums that you can post anything here. We have a strict <a href="/about.php#no-logs-policy">no-logs policy</a> to keep you anonymous while visiting our website.</p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut sem nulla pharetra diam sit amet nisl suscipit adipiscing. In cursus turpis massa tincidunt dui ut. Sit amet commodo nulla facilisi. Enim facilisis gravida neque convallis a. Augue eget arcu dictum varius duis at. Scelerisque purus semper eget duis at tellus at urna. A condimentum vitae sapien pellentesque. Volutpat lacus laoreet non curabitur. In hac habitasse platea dictumst quisque sagittis purus sit. Lorem ipsum dolor sit amet consectetur adipiscing elit pellentesque habitant. Nibh ipsum consequat nisl vel pretium lectus quam id. Velit egestas dui id ornare arcu odio ut sem. Quisque id diam vel quam elementum pulvinar. Tempus imperdiet nulla malesuada pellentesque elit eget. Etiam tempor orci eu lobortis elementum nibh tellus molestie. Et sollicitudin ac orci phasellus egestas tellus rutrum. Amet porttitor eget dolor morbi non arcu risus quis varius.</p>
            <h2>Recent Updates</h2>
            <p><strong>[2022/06/26]</strong> |Version 1.0.5| Add article posting, comment posting, profile viewing function. <a href="/logs/.update/version_1.0.5.txt" target="_blank">Read more&#8230;</a></p>
        </div>
        <?php include dirname(__FILE__).'./path_to_footer.php' ?>
    </body>
</html>
