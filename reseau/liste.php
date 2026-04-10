<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');
$liste = mysqli_query($conn, "SELECT * FROM socials ORDER BY id DESC");
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des réseaux</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Liste des réseaux sociaux</h1>
        <nav class="back-nav">
        <a href="../dashboard.php">← Dashboard</a>
        <a href="../index.php">Voir le portfolio</a>
        <a href="../logout.php">Se déconnecter</a>
        </nav>
    </header>
    <div class="back-container">
        <div class="back-card">
            <h2>Tous les réseaux</h2>
            <a href="ajouter.php">+ Ajouter</a>
            <?php if (mysqli_num_rows($liste) > 0): ?>
                <table class="table">
                    <thead>
                        <tr><th>ID</th><th>Icône</th><th>Nom</th><th>URL</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                    <?php while ($s = mysqli_fetch_assoc($liste)): ?>
                        <tr>
                            <td><?php echo $s['id']; ?></td>
                            <td><img src="../<?php echo htmlspecialchars($s['icon_path']); ?>" style="width:35px;height:35px;object-fit:contain;"></td>
                            <td><strong><?php echo htmlspecialchars($s['nom']); ?></strong></td>
                            <td><a href="<?php echo htmlspecialchars($s['url']); ?>" target="_blank"><?php echo htmlspecialchars(substr($s['url'],0,40)); ?>...</a></td>
                            <td>
                                <a href="modifier.php?id=<?php echo $s['id']; ?>" class="btn btn-edit">Modifier</a>
                                <a href="supprimer.php?id=<?php echo $s['id']; ?>" class="btn btn-delete"
                                   onclick="return confirm('Supprimer ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucun réseau.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
</body>
</html>