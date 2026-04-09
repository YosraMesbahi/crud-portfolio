<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $req = mysqli_query($conn, "INSERT INTO type_projet (nom) VALUES ('$nom')");
    $message = $req ? "Type ajouté !" : "Erreur : " . mysqli_error($conn);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un type de projet</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
<header class="back-header">
    <h1>Ajouter un type de projet</h1>
    <nav class="back-nav">
        <a href="liste.php">← Retour à la liste</a>
        <a href="../logout.php">Se déconnecter</a>
    </nav>
</header>
<div class="back-container">
    <?php if ($message): ?>
        <div class="alert <?php echo strpos($message,'Erreur') !== false ? 'alert-error' : 'alert-success'; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <div class="back-card">
        <form action="" method="POST">
            <div class="form-group">
                <label>Nom :</label>
                <input type="text" name="nom" placeholder="Ex: Développement, Création, Audiovisuel..." required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-success">Ajouter</button>
            </div>
        </form>
    </div>
</div>
<footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
</body>
</html>