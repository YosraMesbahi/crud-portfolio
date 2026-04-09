<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

if (!isset($_GET['id'])) { header("Location: liste.php"); exit(); }
$id = intval($_GET['id']);
$projet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM projet WHERE id = $id LIMIT 1"));
if (!$projet) { header("Location: liste.php"); exit(); }

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = mysqli_real_escape_string($conn, $_POST['titre']);
    $type        = mysqli_real_escape_string($conn, $_POST['type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $github      = mysqli_real_escape_string($conn, $_POST['github']);
    $demo        = mysqli_real_escape_string($conn, $_POST['demo']);
    $ordre       = (int)$_POST['ordre'];
    $visible     = isset($_POST['visible']) ? 1 : 0;
    $image_path  = $projet['image'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg','jpeg','png','webp']) && $_FILES['image']['size'] <= 5*1024*1024) {
            $filename = 'projet-' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['image']['tmp_name'], '../assets/uploads/' . $filename)) {
                $image_path = './assets/uploads/' . $filename;
            }
        }
    }

    $image_escape = mysqli_real_escape_string($conn, $image_path);
    $requete = "UPDATE projet SET titre='$titre', type='$type', description='$description',
                github='$github', demo='$demo', ordre=$ordre, visible=$visible, image='$image_escape'
                WHERE id=$id";

    if (mysqli_query($conn, $requete)) {
        $message = "Projet modifié !";
        $projet = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM projet WHERE id = $id LIMIT 1"));
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
    <title>Modifier un projet</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Modifier un projet</h1>
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
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Titre :</label>
                    <input type="text" name="titre" value="<?php echo htmlspecialchars($projet['titre']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Type :</label>
                    <select name="type">
                        <option value="code" <?php echo $projet['type']==='code'?'selected':''; ?>>Code</option>
                        <option value="design" <?php echo $projet['type']==='design'?'selected':''; ?>>Design</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description :</label>
                    <textarea name="description" rows="4"><?php echo htmlspecialchars($projet['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label>GitHub :</label>
                    <input type="url" name="github" value="<?php echo htmlspecialchars($projet['github']); ?>">
                </div>
                <div class="form-group">
                    <label>Démo :</label>
                    <input type="url" name="demo" value="<?php echo htmlspecialchars($projet['demo']); ?>">
                </div>
                <div class="form-group">
                    <label>Nouvelle image <small>(laisser vide pour garder l'actuelle)</small> :</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp">
                    <?php if ($projet['image']): ?>
                        <small>Actuelle : <a href="../<?php echo htmlspecialchars($projet['image']); ?>" target="_blank">voir</a></small>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label>Ordre :</label>
                    <input type="number" name="ordre" value="<?php echo $projet['ordre']; ?>">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="visible" value="1" <?php echo $projet['visible']?'checked':''; ?>>
                        <span>Visible sur le portfolio</span>
                    </label>
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