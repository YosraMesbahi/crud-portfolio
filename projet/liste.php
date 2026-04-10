<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }

include_once('../classes/Database.php');
include_once('../classes/Projet.php');

$db = new Database();
$conn = $db->connect();

$projet = new Projet($conn);
$liste = $projet->getAll();
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
        <a href="../index.php">Voir le portfolio</a>
        <a href="../logout.php">Se déconnecter</a>
    </nav>
</header>

<div class="back-container">
    <div class="back-card">

        <h2>Tous les projets</h2>
        <a href="ajouter.php">+ Ajouter</a>        

        <?php if ($liste && $liste->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                <?php while ($p = $liste->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $p['id']; ?></td>

                        <td>
                            <?php echo htmlspecialchars($p['titre'] ?? ''); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($p['type'] ?? ''); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($p['date'] ?? ''); ?>
                        </td>

                        <td>
                            <a href="modifier.php?id=<?php echo $p['id']; ?>" class="btn btn-edit">
                                Modifier
                            </a>

                            <a href="supprimer.php?id=<?php echo $p['id']; ?>" class="btn btn-delete"
                               onclick="return confirm('Supprimer ce projet ?')">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p class="no-data">Aucun projet trouvé.</p>
        <?php endif; ?>

    </div>
</div>

<footer class="back-footer">
    <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
</footer>

</body>
</html>