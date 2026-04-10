<!--// Détruit la session et redirige vers login.php    -->

<?php
session_start();
$_SESSION = array();
session_destroy();
header("Location: login.php");
exit();
?>