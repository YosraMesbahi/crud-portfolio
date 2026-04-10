<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

include_once('../connexion.php');

$requete_profil = "SELECT * FROM profil WHERE id = 1 LIMIT 1";
$resultat_profil = mysqli_query($conn, $requete_profil);
$profil = mysqli_fetch_assoc($resultat_profil);

if (!$profil) {
    $requete_creation = "INSERT INTO profil (id, titre, statut, presentation, CV) 
                        VALUES (1, 'Yosra MESBAHI', 'Etudiante BUT MMI', '', '')";
    mysqli_query($conn, $requete_creation);
    $resultat_profil = mysqli_query($conn, $requete_profil);
    $profil = mysqli_fetch_assoc($resultat_profil);
}

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre        = mysqli_real_escape_string($conn, $_POST['titre']);
    $statut       = mysqli_real_escape_string($conn, $_POST['statut']);
    $presentation = mysqli_real_escape_string($conn, $_POST['presentation']);

    // Gestion du CV uploadé — on garde l'ancien par défaut
    $cv = $profil['CV'];
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $cv_extension = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        if ($cv_extension === 'pdf') {
            $cv_filename = 'CV-' . time() . '.pdf';
            $cv_path = './assets/uploads/' . $cv_filename;
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $cv_path)) {
                $cv = $cv_path;
            } else {
                $message = "Erreur lors de l'upload du CV.";
            }
        } else {
            $message = "Le CV doit être un fichier PDF.";
        }
    }

    if (empty($message)) {
        $cv_escape = mysqli_real_escape_string($conn, $cv);
        $requete = "UPDATE profil 
                    SET titre = '$titre', 
                        statut = '$statut', 
                        presentation = '$presentation',
                        CV = '$cv_escape'
                    WHERE id = 1";
        $resultat = mysqli_query($conn, $requete);
        if ($resultat) {
            $message = "Le profil a bien été modifié !";
            $resultat_profil = mysqli_query($conn, $requete_profil);
            $profil = mysqli_fetch_assoc($resultat_profil);
        } else {
            $message = "Erreur : " . mysqli_error($conn);
        }
    }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du Profil - Back Office</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Gestion du Profil</h1>
        <nav class="back-nav">
            <a href="dashboard.php">← Dashboard</a>
            <a href="index.php">Voir le portfolio</a>
            <a href="logout.php">Se déconnecter</a>
        </nav>
    </header>

    <div class="back-container">
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message,'Erreur') !== false ? 'alert-error' : 'alert-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="back-card">
            <h2>Profil actuel</h2>
            <?php if ($profil): ?>
                <table class="table">
                    <thead>
                        <tr><th>ID</th><th>Titre</th><th>Statut</th><th>Présentation</th><th>CV</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $profil['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($profil['titre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($profil['statut']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($profil['presentation'])); ?></td>
                            <td>
                                <?php if (!empty($profil['CV'])): ?>
                                    <a href="<?php echo htmlspecialchars($profil['CV']); ?>" target="_blank">Voir le CV</a>
                                <?php else: ?>
                                    <em>Aucun CV</em>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="back-card">
            <h2>Modifier votre profil</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titre">Titre (Nom complet) :</label>
                    <input type="text" id="titre" name="titre"
                           value="<?php echo htmlspecialchars($profil['titre']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="statut">Statut :</label>
                    <input type="text" id="statut" name="statut"
                           value="<?php echo htmlspecialchars($profil['statut']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="presentation">Présentation :</label>
                    <textarea id="presentation" name="presentation" rows="6"><?php echo htmlspecialchars($profil['presentation']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="cv">Uploader un nouveau CV (PDF) :</label>
                    <input type="file" id="cv" name="cv" accept="application/pdf">
                    <small style="display:block;margin-top:0.5rem;color:#666;">
                        PDF uniquement. Laissez vide pour conserver le CV actuel.
                        <?php if (!empty($profil['CV'])): ?>
                            — <a href="<?php echo htmlspecialchars($profil['CV']); ?>" target="_blank">Voir le CV actuel</a>
                        <?php endif; ?>
                    </small>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-success">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="back-footer">
        <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
    </footer>
</body>
</html>