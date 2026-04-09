<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');
$liste = mysqli_query($conn, "SELECT * FROM competences ORDER BY type, id DESC");
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des compétences</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Liste des compétences</h1>
        <nav class="back-nav">
            <a href="../dashboard.php">← Dashboard</a>
            <a href="../logout.php">Se déconnecter</a>
        </nav>
    </header>
    <div class="back-container">
        <div class="back-card">
            <h2>Toutes les compétences</h2>
            <a href="ajouter.php">+ Ajouter</a>
            <?php if (mysqli_num_rows($liste) > 0): ?>
                <table class="table">
                    <thead>
                        <tr><th>ID</th><th>Type</th><th>Technologie</th><th>Niveau</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($c = mysqli_fetch_assoc($liste)): ?>
                        <tr>
                            <td><?php echo $c['id']; ?></td>
                            <td><?php echo htmlspecialchars($c['type']); ?></td>
                            <td><strong><?php echo htmlspecialchars($c['tech']); ?></strong></td>
                            <td><?php echo htmlspecialchars($c['niveau']); ?></td>
                            <td>
                                <a href="modifier.php?id=<?php echo $c['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="supprimer.php?id=<?php echo $c['id']; ?>" class="btn btn-delete"
                                   onclick="return confirm('Supprimer ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucune compétence.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
</body>
</html>