<?php
$password = "2202";
$salt = "rl";
$hash = crypt($password, $salt);
echo $hash;
?>