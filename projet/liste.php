<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$liste = mysqli_query($conn, "
    SELECT 
        p.id,
        p.titre,
        p.image,
        p.lien_demo,
        p.lien_github,
        p.date,
        t.nom AS type,
        COALESCE(GROUP_CONCAT(c.tech SEPARATOR ', '), '') AS competences
    FROM projet p
    LEFT JOIN type_projet t ON p.type_id = t.id
    LEFT JOIN projet_competence pc ON p.id = pc.projet_id
    LEFT JOIN competences c ON pc.competence_id = c.id
    GROUP BY p.id
    ORDER BY p.date DESC
");

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
        <a href="ajouter.php">+ Ajouter</a>
        <a href="../logout.php">Se déconnecter</a>
    </nav>
</header>

<div class="back-container">
<div class="back-card">

<h2>Tous les projets</h2>

<?php if (mysqli_num_rows($liste) > 0): ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Type</th>
            <th>Compétences</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php while ($p = mysqli_fetch_assoc($liste)): ?>
        <tr>
            <td><?php echo $p['id']; ?></td>

            <td>
                <?php echo htmlspecialchars($p['titre'] ?? ''); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($p['type'] ?? ''); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($p['competences'] ?? ''); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($p['date'] ?? ''); ?>
            </td>

            <td>
                <a href="modifier.php?id=<?php echo $p['id']; ?>" class="btn btn-edit">Modifier</a>
                <a href="supprimer.php?id=<?php echo $p['id']; ?>" class="btn btn-delete"
                   onclick="return confirm('Supprimer ?')">Supprimer</a>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>

</table>

<?php else: ?>
    <p class="no-data">Aucun projet.</p>
<?php endif; ?>

</div>
</div>

<footer class="back-footer">
    <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
</footer>

</body>
</html>