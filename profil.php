<?php
include_once('connexion.php');

$requete_profil = "SELECT * FROM profil WHERE id = 1 LIMIT 1";
$resultat_profil = mysqli_query($conn, $requete_profil);
$profil = mysqli_fetch_assoc($resultat_profil);
?>