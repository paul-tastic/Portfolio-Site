<?php

if(!isset($_COOKIE['user'])) {
     echo "Cookie named 'user' is not set!";
} else {
     echo "Cookie '" . $cookie_name . "' is set!<br>";
     echo "Value is: " . $_COOKIE['user'];
}

?>