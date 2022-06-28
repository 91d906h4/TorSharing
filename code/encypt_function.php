function encypt($string, $salt = ""){
    if($salt == ""){return hash("XXXXXX", hash("XXXXXX", $string));}
    else{return hash("XXXXXX", $string.hash("XXXXXX", $salt));}
}
