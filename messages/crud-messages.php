<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

include_once('../connexion.php');

$message = "";

// Récupération des messages
$liste = mysqli_query($conn, "SELECT * FROM messages ORDER BY date DESC");
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des messages - Back Office</title>
    <link rel="stylesheet" href="../style-back.css">
</head>
<body>

<header class="back-header">
    <h1>Messages reçus</h1>
    <nav class="back-nav">
        <a href="../dashboard.php">← Dashboard</a>
        <a href="../index.php">Voir le portfolio</a>
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
        <h2>Liste des messages</h2>

        <?php if (mysqli_num_rows($liste) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($msg = mysqli_fetch_assoc($liste)): ?>
                    <tr>
                        <td><?php echo $msg['id']; ?></td>
                        <td><?php echo htmlspecialchars($msg['prenom'] . ' ' . $msg['nom']); ?></td>
                        <td><?php echo htmlspecialchars($msg['email']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($msg['message'])); ?></td>
                        <td><?php echo $msg['date']; ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data">Aucun message.</p>
        <?php endif; ?>

    </div>
</div>

<footer class="back-footer">
    <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
</footer>

</body>
</html>