<!--Script de hashage du mot de passe pour l'insertion dans la base de données-->
<?php
$password = "2202";
$salt = "rl";
$hash = crypt($password, $salt);
echo $hash;
?>