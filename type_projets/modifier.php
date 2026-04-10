<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

if (!isset($_GET['id'])) { header("Location: type_projet_liste.php"); exit(); }
$id = intval($_GET['id']);
$type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM type_projet WHERE id = $id LIMIT 1"));
if (!$type) { header("Location: type_projet_liste.php"); exit(); }

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $req = mysqli_query($conn, "UPDATE type_projet SET nom='$nom' WHERE id=$id");
    $message = $req ? "Modifié !" : "Erreur : " . mysqli_error($conn);
    $type = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM type_projet WHERE id = $id LIMIT 1"));
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un type de projet</title>
    <link rel="stylesheet" href="../includes/style-back.css">
</head>
<body>
<header class="back-header">
    <h1>Modifier un type de projet</h1>
    <nav class="back-nav">
        <a href="../dashboard.php">← Dashboard</a>
        <a href="type_projet_liste.php">← Liste</a>
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
                <label>Nom du type :</label>
                <input type="text" name="nom" value="<?php echo htmlspecialchars($type['nom']); ?>" required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-success">Enregistrer</button>
                <a href="type_projet_liste.php" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
<footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
</body>
</html>