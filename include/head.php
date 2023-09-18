<?php
    ini_set("display_errors", 0);
    include dirname(__FILE__).'/language.php';
?>
<meta property="og:url" content="torsharing.ddns.net" />
<meta property="og:type" content="Forum" />
<meta property="og:description" content="A forums on Tor, a community for the world." />
<meta property="og:site_name" content="TorSharing" />
<meta property="og:url" content="<?php echo $_SERVER["REQUEST_URI"]; ?>" />
<meta name="viewport" content="width=device-width">
<link href="/style.css" rel="stylesheet" type="text/css" />
<script>
    // Form resubmit preventer
    if(window.history.replaceState){window.history.replaceState(null, null, window.location.href);}
</script>