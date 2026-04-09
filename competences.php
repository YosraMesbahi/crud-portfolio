<?php
include_once('connexion.php');

$requete_competences = "SELECT * FROM competences";
$resultat_competences = mysqli_query($conn, $requete_competences);
?>