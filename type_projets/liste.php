<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$message = "";

// Supprimer un type si demandé
if (isset($_GET['supprimer'])) {
    $id = (int)$_GET['supprimer'];
    $req = mysqli_query($conn, "DELETE FROM type_projet WHERE id = $id");
    $message = $req ? "Type de projet supprimé !" : "Erreur : " . mysqli_error($conn);
}

// Récupérer tous les types
$liste = mysqli_query($conn, "SELECT * FROM type_projet ORDER BY nom");
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Types de projet</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
<header class="back-header">
    <h1>Types de projet</h1>
    <nav class="back-nav">
        <a href="../dashboard.php">← Dashboard</a>
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
        <h2>Liste des types de projet</h2>
        <a href="ajouter.php">+ Ajouter</a>
        <?php if (mysqli_num_rows($liste) > 0): ?>
            <table class="table">
                <thead>
                    <tr><th>ID</th><th>Nom</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php while ($t = mysqli_fetch_assoc($liste)): ?>
                    <tr>
                        <td><?php echo $t['id']; ?></td>
                        <td><?php echo htmlspecialchars($t['nom']); ?></td>
                        <td>
                            <a href="type_projet_modifier.php?id=<?php echo $t['id']; ?>" class="btn btn-edit">Modifier</a>
                            <a href="?supprimer=<?php echo $t['id']; ?>" class="btn btn-delete"
                               onclick="return confirm('Supprimer ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Aucun type de projet.</p>
        <?php endif; ?>
    </div>
</div>
<footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
</body>
</html>