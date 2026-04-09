<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$message = "";

// Récupérer types et compétences pour le formulaire
$types = mysqli_query($conn, "SELECT * FROM type_projet ORDER BY nom");
$competences = mysqli_query($conn, "SELECT * FROM competences ORDER BY type, id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre      = mysqli_real_escape_string($conn, $_POST['titre']);
    $image      = mysqli_real_escape_string($conn, $_POST['image']);
    $lien_demo  = mysqli_real_escape_string($conn, $_POST['lien_demo']);
    $lien_github= mysqli_real_escape_string($conn, $_POST['lien_github']);
    $type_id    = (int)$_POST['type_id'];
    $date       = mysqli_real_escape_string($conn, $_POST['date']);
    $comp_array = $_POST['competences'] ?? [];

    // Ajouter le projet
    $req = mysqli_query($conn, "INSERT INTO projet (titre, image, lien_demo, lien_github, type_id, date) 
                                VALUES ('$titre','$image','$lien_demo','$lien_github',$type_id,'$date')");
    if ($req) {
        $projet_id = mysqli_insert_id($conn);
        // Ajouter les compétences liées
        foreach ($comp_array as $comp_id) {
            $comp_id = (int)$comp_id;
            mysqli_query($conn, "INSERT INTO projet_competence (projet_id, competence_id) VALUES ($projet_id, $comp_id)");
        }
        $message = "Projet ajouté avec succès !";
    } else {
        $message = "Erreur : " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Ajouter un projet</h1>
        <nav class="back-nav">
            <a href="../dashboard.php">← Dashboard</a>
            <a href="projets.php">Voir la liste</a>
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
            <h2>Nouveau projet</h2>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Titre :</label>
                    <input type="text" name="titre" placeholder="Nom du projet" required>
                </div>
                <div class="form-group">
                    <label>Image :</label>
                    <input type="text" name="image" placeholder="Chemin ou URL de l'image">
                </div>
                <div class="form-group">
                    <label>Lien démo :</label>
                    <input type="text" name="lien_demo" placeholder="https://...">
                </div>
                <div class="form-group">
                    <label>Lien GitHub :</label>
                    <input type="text" name="lien_github" placeholder="https://github.com/...">
                </div>
                <div class="form-group">
                    <label>Type :</label>
                    <select name="type_id" required>
                        <option value="">Choisir</option>
                        <?php while ($t = mysqli_fetch_assoc($types)): ?>
                            <option value="<?php echo $t['id']; ?>"><?php echo htmlspecialchars($t['nom']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date :</label>
                    <input type="date" name="date" required>
                </div>
                <div class="form-group">
                    <label>Compétences :</label><br>
                    <?php while ($c = mysqli_fetch_assoc($competences)): ?>
                        <input type="checkbox" name="competences[]" value="<?php echo $c['id']; ?>"> <?php echo htmlspecialchars($c['tech']); ?><br>
                    <?php endwhile; ?>
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