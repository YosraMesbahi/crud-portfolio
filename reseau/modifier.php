<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

if (!isset($_GET['id'])) { header("Location: liste.php"); exit(); }
$id = intval($_GET['id']);
$social = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM socials WHERE id = $id LIMIT 1"));
if (!$social) { header("Location: liste.php"); exit(); }

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom       = mysqli_real_escape_string($conn, $_POST['nom']);
    $url       = mysqli_real_escape_string($conn, $_POST['url']);
    $icon_path = mysqli_real_escape_string($conn, $_POST['icon_path']);
    $req = mysqli_query($conn, "UPDATE socials SET nom='$nom', url='$url', icon_path='$icon_path' WHERE id=$id");
    $message = $req ? "Modifié !" : "Erreur : " . mysqli_error($conn);
    $social = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM socials WHERE id = $id LIMIT 1"));
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un réseau</title>
    <link rel="stylesheet" href="../includes/style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Modifier un réseau social</h1>
        <nav class="back-nav">
            <a href="../dashboard.php">← Dashboard</a>
            <a href="liste.php">← Liste</a>
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
                    <input type="text" name="nom" value="<?php echo htmlspecialchars($social['nom']); ?>" required>
                </div>
                <div class="form-group">
                    <label>URL :</label>
                    <input type="url" name="url" value="<?php echo htmlspecialchars($social['url']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Chemin icône :</label>
                    <input type="text" name="icon_path" value="<?php echo htmlspecialchars($social['icon_path']); ?>" required>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                    <a href="liste.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
    <footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
</body>
</html>