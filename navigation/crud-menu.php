<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

include_once('../connexion.php');

$message = "";


// MODIFICATION DE L'ORDRE (flèches haut/bas)
if (isset($_GET['monter'])) {
    $id = intval($_GET['monter']);
    $item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM nav_menu WHERE id = $id"));
    $precedent = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM nav_menu WHERE ordre < {$item['ordre']} ORDER BY ordre DESC LIMIT 1"));
    if ($precedent) {
        mysqli_query($conn, "UPDATE nav_menu SET ordre = {$precedent['ordre']} WHERE id = $id");
        mysqli_query($conn, "UPDATE nav_menu SET ordre = {$item['ordre']} WHERE id = {$precedent['id']}");
    }
    header("Location: crud-menu.php");
    exit();
}

if (isset($_GET['descendre'])) {
    $id = intval($_GET['descendre']);
    $item = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM nav_menu WHERE id = $id"));
    $suivant = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM nav_menu WHERE ordre > {$item['ordre']} ORDER BY ordre ASC LIMIT 1"));
    if ($suivant) {
        mysqli_query($conn, "UPDATE nav_menu SET ordre = {$suivant['ordre']} WHERE id = $id");
        mysqli_query($conn, "UPDATE nav_menu SET ordre = {$item['ordre']} WHERE id = {$suivant['id']}");
    }
    header("Location: crud-menu.php");
    exit();
}

// Récupération de tous les items
$liste = mysqli_query($conn, "SELECT * FROM nav_menu ORDER BY ordre ASC");
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion du menu - Back Office</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Gestion du menu de navigation</h1>
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

        <!-- Liste des items avec modification inline -->
        <div class="back-card">
            <h2>Menu actuel</h2>
            <?php if (mysqli_num_rows($liste) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Titre du lien</th>
                            <th>Déplacer</th>

                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($item = mysqli_fetch_assoc($liste)): ?>
                        <tr>
                            <form action="" method="POST">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <td style="font-weight:600;text-align:center;"><?php echo $item['ordre']; ?></td>
                                <td>
                                    <input type="text" name="menu_item"
                                           value="<?php echo htmlspecialchars($item['menu_item']); ?>"
                                           style="width:100%;padding:0.4rem 0.8rem;border-radius:0.5rem;border:1px solid rgb(163,163,163);font-family:'Poppins',sans-serif;">
                                </td>
                                <td style="text-align:center;">
                                    <a href="?monter=<?php echo $item['id']; ?>" class="btn btn-secondary" style="padding:0.3rem 0.7rem;font-size:0.9rem;">↑</a>
                                    <a href="?descendre=<?php echo $item['id']; ?>" class="btn btn-secondary" style="padding:0.3rem 0.7rem;font-size:0.9rem;">↓</a>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-data">Aucun élément dans le menu.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer class="back-footer">
        <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
    </footer>
</body>
</html>
