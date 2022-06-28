<?php
function encypt($password, $salt = ""){
    if($salt == ""){return hash("XXXXXX", hash("XXXXXX", $password));}
    else{return hash("XXXXXX", $password.hash("XXXXXX", $salt));}
}
