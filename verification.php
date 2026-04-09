<?php
session_start();
include_once('connexion.php');

if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = mysqli_real_escape_string($conn, $_POST['login']);
    $password_saisi = $_POST['password'];

    $requete = "SELECT * FROM user WHERE login = '$login'";
    $resultat = mysqli_query($conn, $requete);
    $utilisateur = mysqli_fetch_assoc($resultat);

    if ($utilisateur) {
        $hashed_password = $utilisateur['password'];
        if (hash_equals($hashed_password, crypt($password_saisi, $hashed_password))) {
            $_SESSION['login'] = $utilisateur['login'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Identifiants incorrects.";
        echo '<br><a href="login.php">Réessayer</a>';
    }
} else {
    header("Location: login.php");
    exit();
}
?>