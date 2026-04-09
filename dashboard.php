<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Back Office</title>
    <link rel="stylesheet" href="style-back.css">
</head>
<body>
    <header class="back-header">
        <h1>Dashboard</h1>
        <p>Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['login']); ?></strong></p>
        <nav class="back-nav">
            <a href="index.php">Voir le portfolio</a>
            <a href="logout.php">Se déconnecter</a>
        </nav>
    </header>

    <div class="back-container">
        <div class="dashboard-grid">
            <div class="dashboard-item">
                <h2>Profil</h2>
                <a href="crud-profil.php" class="btn btn-secondary" style="margin-top:0.5rem;">Gérer</a>
            </div>
            <div class="dashboard-item">
                <h2>Compétences</h2>
                <a href="competence/liste.php" class="btn btn-secondary" style="margin-top:0.5rem;">Gérer</a>
            </div>
            <div class="dashboard-item">
                <h2>Projets</h2>
                <a href="projet/liste.php" class="btn btn-secondary" style="margin-top:0.5rem;">Gérer</a>
            </div>
            <div class="dashboard-item">
                <h2>Réseaux</h2>
                <a href="reseau/liste.php" class="btn btn-secondary" style="margin-top:0.5rem;">Gérer</a>
            </div>
            <div class="dashboard-item">
                <h2>Messages</h2>
                <a href="crud-messages.php" class="btn btn-secondary" style="margin-top:0.5rem;">Gérer</a>
            </div>
            <div class="dashboard-item">
                <h2>Navigation</h2>
                <a href="crud-menu.php" class="btn btn-secondary" style="margin-top:0.5rem;">Gérer</a>
            </div> 
            <div class="dashboard-item">
                <h2>Type de projets</h2>
                <a href="type_projets/liste.php" class="btn btn-secondary" style="margin-top:0.5rem;">Gérer</a>
            </div>   
                    
        </div>
    </div>

    <footer class="back-footer">
        <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
    </footer>
</body>
</html>