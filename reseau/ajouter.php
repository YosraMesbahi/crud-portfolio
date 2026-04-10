<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = mysqli_real_escape_string($conn, $_POST['nom']);
    $url       = mysqli_real_escape_string($conn, $_POST['url']);
    $icon_path = mysqli_real_escape_string($conn, $_POST['icon_path']);
    $req = mysqli_query($conn, "INSERT INTO socials (nom, url, icon_path) VALUES ('$nom','$url','$icon_path')");
    $message = $req ? "Réseau ajouté !" : "Erreur : " . mysqli_error($conn);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un réseau</title>
    <link rel="stylesheet" href="../includes/style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Ajouter un réseau social</h1>
        <nav class="back-nav">
            <a href="../dashboard.php">← Dashboard</a>
            <a href="liste.php">Voir la liste</a>
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
            <h2>Nouveau réseau</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Nom :</label>
                    <input type="text" name="nom" placeholder="LinkedIn, GitHub..." required>
                </div>
                <div class="form-group">
                    <label>URL :</label>
                    <input type="url" name="url" placeholder="https://..." required>
                </div>
                <div class="form-group">
                    <label>Chemin icône :</label>
                    <input type="text" name="icon_path" placeholder="./assets/linkedin.png" required>
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