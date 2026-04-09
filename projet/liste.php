<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');
$liste = mysqli_query($conn, "SELECT * FROM projet ORDER BY ordre ASC, id DESC");
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des projets</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Liste des projets</h1>
        <nav class="back-nav">
            <a href="../dashboard.php">← Dashboard</a>
            <a href="../logout.php">Se déconnecter</a>
        </nav>
    </header>
    <div class="back-container">
        <div class="back-card">
            <h2>Tous les projets</h2>
            <a href="ajouter.php">+ Ajouter</a>
            <?php if (mysqli_num_rows($liste) > 0): ?>
                <table class="table">
                    <thead>
                        <tr><th>ID</th><th>Image</th><th>Titre</th><th>Type</th><th>Visible</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($projet = mysqli_fetch_assoc($liste)): ?>
                        <tr>
                            <td><?php echo $projet['id']; ?></td>
                            <td>
                                <?php if ($projet['image']): ?>
                                    <img src="../<?php echo htmlspecialchars($projet['image']); ?>" style="width:60px;height:60px;object-fit:cover;border-radius:0.5rem;">
                                <?php else: ?><em>—</em><?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($projet['titre']); ?></strong></td>
                            <td><?php echo htmlspecialchars($projet['type']); ?></td>
                            <td><?php echo $projet['visible'] ? '✅' : '❌'; ?></td>
                            <td>
                                <a href="modifier.php?id=<?php echo $projet['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="supprimer.php?id=<?php echo $projet['id']; ?>" class="btn btn-delete"
                                   onclick="return confirm('Supprimer ce projet ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucun projet pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
</body>
</html>