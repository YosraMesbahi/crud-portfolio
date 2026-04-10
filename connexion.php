<!--Connexion à la base de données MySQL via mysqli--> 
<?php
$serveur = "mysql-yosra-sae203.alwaysdata.net";
$utilisateur = "yosra-sae203";
$mot_de_passe = "M@s@bih4620!+";
$base_de_donnees = "yosra-sae203_socials-medias";

$conn = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

if (!$conn) {
    die("Connexion échouée : " . mysqli_connect_error());
}
?>