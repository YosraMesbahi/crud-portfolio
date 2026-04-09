<?php
session_start();
if (!isset($_SESSION['login'])) { header("Location: ../login.php"); exit(); }
include_once('../connexion.php');

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre       = mysqli_real_escape_string($conn, $_POST['titre']);
    $type        = mysqli_real_escape_string($conn, $_POST['type']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $client      = mysqli_real_escape_string($conn, $_POST['client']);
    $problem     = mysqli_real_escape_string($conn, $_POST['problem']);
    $solution    = mysqli_real_escape_string($conn, $_POST['solution']);
    $github      = mysqli_real_escape_string($conn, $_POST['github']);
    $demo        = mysqli_real_escape_string($conn, $_POST['demo']);
    $ordre       = (int)$_POST['ordre'];
    $visible     = isset($_POST['visible']) ? 1 : 0;

    $technologies      = isset($_POST['technologies']) ? $_POST['technologies'] : [];
    $technologies_json = json_encode($technologies, JSON_UNESCAPED_UNICODE);

    $skills_raw   = $_POST['skills'];
    $skills_array = array_filter(array_map('trim', explode("\n", $skills_raw)));
    $skills_json  = json_encode($skills_array, JSON_UNESCAPED_UNICODE);

    $image_path = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir     = '../assets/uploads/';
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed        = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $taille_max     = 5 * 1024 * 1024;

        if ($_FILES['image']['size'] > $taille_max) {
            $message = "L'image est trop lourde (5 Mo maximum).";
        } elseif (!in_array($file_extension, $allowed)) {
            $message = "Format non autorisé (jpg, png, webp uniquement).";
        } else {
            $new_filename = 'projet-' . time() . '.' . $file_extension;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $new_filename)) {
                $image_path = './assets/uploads/' . $new_filename;
            } else {
                $message = "Erreur lors de l'upload.";
            }
        }
    }

    if (empty($message)) {
        $requete = "INSERT INTO projet (titre, type, description, client, problem, solution, technologies, skills, image, github, demo, ordre, visible)
                    VALUES ('$titre','$type','$description','$client','$problem','$solution','$technologies_json','$skills_json','$image_path','$github','$demo',$ordre,$visible)";
        $resultat = mysqli_query($conn, $requete);
        $message = $resultat ? "Projet ajouté !" : "Erreur : " . mysqli_error($conn);
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
    <link rel="stylesheet" href="../style-back.css">
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
            <div class="alert <?php echo strpos($message,'Erreur') !== false ? 'alert-error' : 'alert-success'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="back-card">
            <h2>Nouveau projet</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Titre :</label>
                    <input type="text" name="titre" required>
                </div>
                <div class="form-group">
                    <label>Type :</label>
                    <select name="type" required>
                        <option value="code">Code</option>
                        <option value="design">Design</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Description :</label>
                    <textarea name="description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label>Client :</label>
                    <input type="text" name="client">
                </div>
                <div class="form-group">
                    <label>Problématique :</label>
                    <textarea name="problem" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Solution :</label>
                    <textarea name="solution" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Technologies :</label>
                    <textarea name="technologies" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Compétences <small>(une par ligne)</small> :</label>
                    <textarea name="skills" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label>Image :</label>
                    <div id="drop-zone" style="border:2px dashed rgb(163,163,163);border-radius:1rem;padding:2rem;text-align:center;cursor:pointer;color:#666;transition:all 0.3s ease;">
                        <p>Glissez-déposez une image ici ou <u>cliquez pour parcourir</u></p>
                        <p id="drop-filename" style="margin-top:0.5rem;font-weight:600;color:rgb(53,53,53);"></p>
                        <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" style="display:none;">
                    </div>
                    <small style="color:#666;">JPG, PNG, WEBP — max 5 Mo</small>
                </div>
                <div class="form-group">
                    <label>GitHub :</label>
                    <input type="url" name="github">
                </div>
                <div class="form-group">
                    <label>Démo :</label>
                    <input type="url" name="demo">
                </div>
                <div class="form-group">
                    <label>Ordre d'affichage :</label>
                    <input type="number" name="ordre" value="0" min="0">
                </div>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="visible" value="1" checked>
                        <span>Visible sur le portfolio</span>
                    </label>
                </div>
                <div class="btn-container">
                    <button type="submit" class="btn btn-success">Ajouter le projet</button>
                </div>
            </form>
        </div>
    </div>
    <footer class="back-footer"><p>Back Office Portfolio - <?php echo date('Y'); ?></p></footer>
    <script>
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('image');
    const dropFilename = document.getElementById('drop-filename');
    dropZone.addEventListener('click', () => fileInput.click());
    dropZone.addEventListener('dragover', (e) => { e.preventDefault(); dropZone.style.borderColor = 'rgb(53,53,53)'; dropZone.style.backgroundColor = 'rgb(245,245,245)'; });
    dropZone.addEventListener('dragleave', () => { dropZone.style.borderColor = 'rgb(163,163,163)'; dropZone.style.backgroundColor = ''; });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.style.borderColor = 'rgb(163,163,163)';
        dropZone.style.backgroundColor = '';
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            dropFilename.textContent = '✅ ' + e.dataTransfer.files[0].name;
        }
    });
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) dropFilename.textContent = '✅ ' + fileInput.files[0].name;
    });
    </script>
</body>
</html>