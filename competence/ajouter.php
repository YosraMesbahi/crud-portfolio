<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tech   = mysqli_real_escape_string($conn, $_POST['tech']);
    $niveau = mysqli_real_escape_string($conn, $_POST['niveau']);
    $type   = mysqli_real_escape_string($conn, $_POST['type']);
    $req    = mysqli_query($conn, "INSERT INTO competences (tech, niveau, type) VALUES ('$tech','$niveau','$type')");
    $message = $req ? "Compétence ajoutée !" : "Erreur : " . mysqli_error($conn);
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une compétence</title>
    <link rel="stylesheet" href="../includes/style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Ajouter une compétence</h1>
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
            <h2>Nouvelle compétence</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Technologie :</label>
                    <input type="text" name="tech" placeholder="Ex: HTML, CSS, PHP..." required>
                </div>
                <div class="form-group">
                    <label>Type :</label>
                    <select name="type" required>
                        <option value="">Choisir</option>
                        <option value="Front-end">Front-end</option>
                        <option value="Back-End">Back-end</option>
                        <option value="Outils">Outils</option>
                        <option value="Design">Design</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Niveau :</label>
                    <select name="niveau" required>
                        <option value="">Choisir</option>
                        <option value="Débutant">Débutant</option>
                        <option value="Intermédiaire">Intermédiaire</option>
                        <option value="Avancé">Avancé</option>
                    </select>
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