<!--
    Fichier : competences.php
    Description : Ce fichier contient la logique pour récupérer les compétences depuis la base de données.
->
<?php
include_once('connexion.php');

$requete_competences = "SELECT * FROM competences";
$resultat_competences = mysqli_query($conn, $requete_competences);
?>