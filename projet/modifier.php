<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

if (!isset($_GET['id'])) { header("Location: projets.php"); exit(); }

$id = intval($_GET['id']);

/* PROJET */
$projet = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT * FROM projet WHERE id = $id LIMIT 1
"));
if (!$projet) { header("Location: projets.php"); exit(); }

/* TYPES */
$types = mysqli_query($conn, "SELECT * FROM type_projet ORDER BY nom");

/* COMPÉTENCES */
$competences = mysqli_query($conn, "SELECT * FROM competences ORDER BY type, id");

/* COMPÉTENCES SÉLECTIONNÉES */
$selected = [];
$resSel = mysqli_query($conn, "SELECT competence_id FROM projet_competence WHERE projet_id=$id");
while ($r = mysqli_fetch_assoc($resSel)) {
    $selected[] = $r['competence_id'];
}

$message = "";

/* UPDATE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre       = mysqli_real_escape_string($conn, $_POST['titre']);
    $image       = mysqli_real_escape_string($conn, $_POST['image']);
    $lien_demo   = mysqli_real_escape_string($conn, $_POST['lien_demo']);
    $lien_github = mysqli_real_escape_string($conn, $_POST['lien_github']);
    $type_id     = (int)$_POST['type_id'];
    $date        = mysqli_real_escape_string($conn, $_POST['date']);

    $req = mysqli_query($conn, "
        UPDATE projet SET
        titre='$titre',
        image='$image',
        lien_demo='$lien_demo',
        lien_github='$lien_github',
        type_id=$type_id,
        date='$date'
        WHERE id=$id
    ");

    /* RESET COMPÉTENCES */
    mysqli_query($conn, "DELETE FROM projet_competence WHERE projet_id=$id");

    $comp_array = $_POST['competences'] ?? [];

    foreach ($comp_array as $comp_id) {
        $comp_id = (int)$comp_id;
        mysqli_query($conn, "
            INSERT INTO projet_competence (projet_id, competence_id)
            VALUES ($id, $comp_id)
        ");
    }

    $message = $req ? "Projet modifié !" : "Erreur : " . mysqli_error($conn);

    /* refresh */
    $projet = mysqli_fetch_assoc(mysqli_query($conn, "
        SELECT * FROM projet WHERE id = $id LIMIT 1
    "));

    $selected = [];
    $resSel = mysqli_query($conn, "SELECT competence_id FROM projet_competence WHERE projet_id=$id");
    while ($r = mysqli_fetch_assoc($resSel)) {
        $selected[] = $r['competence_id'];
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
    <link rel="stylesheet" href="../includes/style-back.css">
</head>
<body>

<header class="back-header">
    <h1>Modifier un projet</h1>
    <nav class="back-nav">
        <a href="liste.php">← Liste</a>
        <a href="../dashboard.php">Dashboard</a>
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

<form method="POST">

    <div class="form-group">
        <label>Titre :</label>
        <input type="text" name="titre" value="<?php echo htmlspecialchars($projet['titre']); ?>" required>
    </div>

    <div class="form-group">
        <label>Image :</label>
        <input type="text" name="image" value="<?php echo htmlspecialchars($projet['image']); ?>">
    </div>

    <div class="form-group">
        <label>Lien démo :</label>
        <input type="text" name="lien_demo" value="<?php echo htmlspecialchars($projet['lien_demo']); ?>">
    </div>

    <div class="form-group">
        <label>Lien GitHub :</label>
        <input type="text" name="lien_github" value="<?php echo htmlspecialchars($projet['lien_github']); ?>">
    </div>

    <div class="form-group">
        <label>Type :</label>
        <select name="type_id">
            <?php while ($t = mysqli_fetch_assoc($types)): ?>
                <option value="<?php echo $t['id']; ?>"
                    <?php echo ($t['id'] == $projet['type_id']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($t['nom']); ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Date :</label>
        <input type="date" name="date" value="<?php echo htmlspecialchars($projet['date']); ?>">
    </div>

    <div class="form-group">
        <label>Compétences :</label><br>

        <?php mysqli_data_seek($competences, 0); ?>
        <?php while ($c = mysqli_fetch_assoc($competences)): ?>
            <input type="checkbox" name="competences[]"
                   value="<?php echo $c['id']; ?>"
                   <?php echo in_array($c['id'], $selected) ? 'checked' : ''; ?>>
            <?php echo htmlspecialchars($c['tech']); ?><br>
        <?php endwhile; ?>

    </div>

    <div class="btn-container">
        <button type="submit" class="btn btn-success">Enregistrer</button>
        <a href="projets.php" class="btn btn-secondary">Annuler</a>
    </div>

</form>

</div>
</div>

<footer class="back-footer">
    <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
</footer>

</body>
</html>