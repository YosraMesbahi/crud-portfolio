<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

if (!isset($_GET['id'])) { header("Location: liste.php"); exit(); }
$id = intval($_GET['id']);
$comp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competences WHERE id = $id LIMIT 1"));
if (!$comp) { header("Location: liste.php"); exit(); }

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tech   = mysqli_real_escape_string($conn, $_POST['tech']);
    $niveau = mysqli_real_escape_string($conn, $_POST['niveau']);
    $type   = mysqli_real_escape_string($conn, $_POST['type']);
    $req    = mysqli_query($conn, "UPDATE competences SET tech='$tech', niveau='$niveau', type='$type' WHERE id=$id");
    $message = $req ? "Modifié !" : "Erreur : " . mysqli_error($conn);
    $comp = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM competences WHERE id = $id LIMIT 1"));
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une compétence</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Modifier une compétence</h1>
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
                    <label>Technologie :</label>
                    <input type="text" name="tech" value="<?php echo htmlspecialchars($comp['tech']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Type :</label>
                    <select name="type">
                        <?php foreach (['Front-end','Back-End','Outils','Design'] as $t): ?>
                            <option value="<?php echo $t; ?>" <?php echo $comp['type']===$t?'selected':''; ?>><?php echo $t; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Niveau :</label>
                    <select name="niveau">
                        <?php foreach (['Débutant','Intermédiaire','Avancé'] as $n): ?>
                            <option value="<?php echo $n; ?>" <?php echo $comp['niveau']===$n?'selected':''; ?>><?php echo $n; ?></option>
                        <?php endforeach; ?>
                    </select>
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