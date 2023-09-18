<?php
    // Function : Check if input empty
    // For :      /login.php, /register.php
    // Return :   True : input is not empty; False : input is empty.
    function check_input(){
        if(empty($_POST["username"]) || empty($_POST["password"])){
            global $error_message;
            $error_message = "*Username or password cannot be empty.";
            return False;
        }
        return True;
    }

    // Function : Encrypt String (SHA-512, SHA-256 based)
    // For :      *
    // Return :   Encrypted string
    function encypt($string, $salt = ""){
        if($salt == ""){return hash("sha512", hash("sha256", $string));}
        else{return hash("sha512", $string.hash("sha256", $salt));}
    }

    // Function : Check if string length is too long
    // For :      *
    // Return   : True : length of input is less than limit; False : length of input is longer than limit
    function check_string_length($string, $limit){
        return strlen($string) <= $limit;
    }

    // Function : Remove folder on server
    // For      : *
    // Return   : NULL
    function remove_dir($path){
        if(!file_exists($path)) return true;
        if(!is_dir($path)) return unlink($path);
        foreach(scandir($path) as $item){
            if(in_array($item, [".", ".."])) continue;
            if (!remove_dir($path.DIRECTORY_SEPARATOR.$item)) return false;
        }
        rmdir($path);
    }
?>