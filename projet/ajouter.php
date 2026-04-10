<?php
session_start();
// Redirection vers login.php si l'utilisateur n'est pas connecté
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$message = "";

// Récupérer types et compétences pour le formulaire
$types       = mysqli_query($conn, "SELECT * FROM type_projet ORDER BY nom");
$competences = mysqli_query($conn, "SELECT * FROM competences ORDER BY type, id");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titre       = mysqli_real_escape_string($conn, $_POST['titre']);
    $lien_demo   = mysqli_real_escape_string($conn, $_POST['lien_demo']);
    $lien_github = mysqli_real_escape_string($conn, $_POST['lien_github']);
    $type_id     = (int)$_POST['type_id'];
    $date        = mysqli_real_escape_string($conn, $_POST['date']);
    $comp_array  = $_POST['competences'] ?? [];

    // --- UPLOAD IMAGE ---
    $image = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        // Dossier de destination dédié aux images de projets (critère : rangés dans un dossier)
        $upload_dir = dirname(__DIR__) . '/assets/uploads/projets/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Vérification du type MIME réel (plus fiable que l'extension)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $_FILES['image']['tmp_name']);
        finfo_close($finfo);

        // Limite de taille : 2 Mo (critère : limite de taille)
        $max_size = 2 * 1024 * 1024;

        if (!in_array($mime, $allowed_types)) {
            $message = "Format invalide. Formats acceptés : JPG, PNG, GIF, WEBP.";
        } elseif ($_FILES['image']['size'] > $max_size) {
            $message = "Image trop lourde. Taille maximum : 2 Mo.";
        } else {
            // Renommage automatique avec timestamp + identifiant unique (critère : fichiers renommés)
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $new_name  = 'projet-' . time() . '-' . uniqid() . '.' . $extension;
            $dest_abs  = $upload_dir . $new_name;
            // Chemin relatif stocké en base, utilisé depuis index.php à la racine
            $dest_web  = './assets/uploads/projets/' . $new_name;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest_abs)) {
                $image = $dest_web;
            } else {
                $message = "Erreur lors du déplacement du fichier.";
            }
        }
    }

    // Insertion en base uniquement si pas d'erreur d'upload
    if (empty($message)) {
        $image_escape = mysqli_real_escape_string($conn, $image);
        $req = mysqli_query($conn, "
            INSERT INTO projet (titre, image, lien_demo, lien_github, type_id, date)
            VALUES ('$titre','$image_escape','$lien_demo','$lien_github',$type_id,'$date')
        ");

        if ($req) {
            $projet_id = mysqli_insert_id($conn);
            // Liaison des compétences sélectionnées
            foreach ($comp_array as $comp_id) {
                $comp_id = (int)$comp_id;
                mysqli_query($conn, "
                    INSERT INTO projet_competence (projet_id, competence_id)
                    VALUES ($projet_id, $comp_id)
                ");
            }
            $message = "Projet ajouté avec succès !";
        } else {
            $message = "Erreur SQL : " . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un projet</title>
    <link rel="stylesheet" href="../includes/style-back.css">
    <style>
        /* Zone de dépôt drag & drop */
        #drop-zone {
            border: 2px dashed rgb(163, 163, 163);
            border-radius: 1rem;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: border-color 250ms ease, background 250ms ease;
            background: #fafafa;
        }
        #drop-zone.drag-over {
            border-color: #28a745;
            background: #f0fff4;
        }
        #drop-zone p {
            margin: 0;
            color: rgb(85, 85, 85);
        }
        #drop-zone label {
            color: #28a745;
            cursor: pointer;
            text-decoration: underline;
        }
        #preview-container {
            display: none;
            margin-top: 1rem;
        }
        #preview {
            max-width: 200px;
            max-height: 150px;
            border-radius: 0.5rem;
            object-fit: cover;
            border: 1px solid rgb(220,220,220);
        }
        #preview-name {
            display: block;
            font-size: 0.85rem;
            color: rgb(85, 85, 85);
            margin-top: 0.4rem;
        }
    </style>
</head>
<body>
    <header class="back-header">
        <h1>Ajouter un projet</h1>
        <nav class="back-nav">
            <a href="../dashboard.php">← Dashboard</a>
            <a href="liste.php">Voir la liste</a>
            <a href="../logout.php">Se déconnecter</a>
        </nav>
    </header>

    <div class="back-container">
        <?php if ($message): ?>
            <div class="alert <?php echo strpos($message, 'Erreur') !== false || strpos($message, 'invalide') !== false || strpos($message, 'lourde') !== false ? 'alert-error' : 'alert-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="back-card">
            <h2>Nouveau projet</h2>

            <!-- enctype obligatoire pour l'upload de fichier -->
            <form action="" method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label>Titre :</label>
                    <input type="text" name="titre" placeholder="Nom du projet" required>
                </div>

                <!-- CHAMP IMAGE : zone drag & drop (critère : glisser-déposer) -->
                <div class="form-group">
                    <label>Image du projet :</label>

                    <div id="drop-zone">
                        <p id="drop-text">
                            Glissez une image ici ou
                            <label for="image">parcourir</label>
                        </p>
                        <!-- input masqué, déclenché par le clic ou le drop -->
                        <input type="file" id="image" name="image"
                               accept="image/jpeg,image/png,image/gif,image/webp"
                               style="display:none;">
                        <div id="preview-container">
                            <img id="preview" src="" alt="Aperçu de l'image">
                            <span id="preview-name"></span>
                        </div>
                    </div>

                    <small style="color:#666; display:block; margin-top:0.5rem;">
                        JPG, PNG, GIF ou WEBP · 2 Mo maximum
                    </small>
                </div>

                <div class="form-group">
                    <label>Lien démo :</label>
                    <input type="text" name="lien_demo" placeholder="https://...">
                </div>

                <div class="form-group">
                    <label>Lien GitHub :</label>
                    <input type="text" name="lien_github" placeholder="https://github.com/...">
                </div>

                <div class="form-group">
                    <label>Type :</label>
                    <select name="type_id" required>
                        <option value="">Choisir</option>
                        <?php while ($t = mysqli_fetch_assoc($types)): ?>
                            <option value="<?php echo $t['id']; ?>">
                                <?php echo htmlspecialchars($t['nom']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date :</label>
                    <input type="date" name="date" required>
                </div>

                <div class="form-group">
                    <label>Compétences :</label><br>
                    <?php while ($c = mysqli_fetch_assoc($competences)): ?>
                        <input type="checkbox" name="competences[]" value="<?php echo $c['id']; ?>">
                        <?php echo htmlspecialchars($c['tech']); ?><br>
                    <?php endwhile; ?>
                </div>

                <div class="btn-container">
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>

            </form>
        </div>
    </div>

    <footer class="back-footer">
        <p>Back Office Portfolio - <?php echo date('Y'); ?></p>
    </footer>

    <script>
    // --- DRAG & DROP + APERÇU ---
    const dropZone  = document.getElementById('drop-zone');
    const fileInput = document.getElementById('image');
    const preview   = document.getElementById('preview');
    const previewContainer = document.getElementById('preview-container');
    const dropText  = document.getElementById('drop-text');
    const previewName = document.getElementById('preview-name');

    // Clic sur la zone → ouvre l'explorateur de fichiers
    dropZone.addEventListener('click', (e) => {
        // Évite le double déclenchement si on clique directement sur le <label>
        if (e.target.tagName !== 'LABEL') {
            fileInput.click();
        }
    });

    // Survol : feedback visuel
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('drag-over');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('drag-over');
    });

    // Dépôt du fichier (critère : glisser-déposer)
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('drag-over');
        const file = e.dataTransfer.files[0];
        if (file) {
            // Transfère le fichier dans l'input natif pour qu'il parte avec le formulaire
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            afficherApercu(file);
        }
    });

    // Sélection via l'explorateur
    fileInput.addEventListener('change', () => {
        if (fileInput.files[0]) {
            afficherApercu(fileInput.files[0]);
        }
    });

    // Affiche l'aperçu et le nom du fichier
    function afficherApercu(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.src = e.target.result;
            previewContainer.style.display = 'block';
            dropText.style.display = 'none';
            previewName.textContent = file.name + ' (' + (file.size / 1024).toFixed(0) + ' Ko)';
        };
        reader.readAsDataURL(file);
    }
    </script>

</body>
</html>