<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification - Back Office</title>
    <link rel="stylesheet" href="../includes/style-back.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <h1>Identification</h1>
            <form action="verification.php" method="post">
                <div class="form-group">
                    <label for="login">Identifiant :</label>
                    <input type="text" id="login" name="login" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-full">Se connecter</button>
            </form>
            <div class="login-footer">
                <a href="index.php" class="btn btn-secondary">Retourner au portfolio</a>
            </div>
        </div>
    </div>
</body>
</html>