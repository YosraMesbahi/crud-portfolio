<?php 
include_once('social-media.php'); 
include('profil.php'); 
include('competences.php'); 

// 🔥 POO PROJETS
include_once('classes/Database.php');
include_once('classes/Projet.php');

$db = new Database();
$conn = $db->connect();

$projet = new Projet($conn);
$projects = $projet->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portfolio MMI | Yosra Mesbahi</title>
  <link rel="stylesheet" href="includes/style.css" />
  <link rel="stylesheet" href="includes/mediaqueries.css" />
</head>
<body>

<header>
  <nav id="desktop-nav">
    <div>
      <a href="index.php" class="logo">Yosra Mesbahi</a>
    </div>
    <div>
      <ul class="nav-links">
        <?php
        include_once('menu_nav.php');
        $menuItems = getMenuItems($conn);
        if ($menuItems) {
            mysqli_data_seek($menuItems, 0);
            while ($item = mysqli_fetch_assoc($menuItems)) {
                echo '<li><a href="' . htmlspecialchars($item['ancre']) . '">'
                     . htmlspecialchars($item['menu_item']) . '</a></li>';
            }
        }
        ?>
      </ul>
    </div>
  </nav>
</header>

<main>

<!-- PROFIL -->
<section id="profile">
  <div class="section__text">
    <p class="section__text__p1">Bonjour, je suis</p>
    <h1 class="title"><?php echo htmlspecialchars($profil['titre']); ?></h1>
    <p class="section__text__p2"><?php echo htmlspecialchars($profil['statut']); ?></p>
    <p class="section__text__p1"><?php echo nl2br(htmlspecialchars($profil['presentation'])); ?></p>

    <div class="btn-container">
      <button class="btn btn-color-2" onclick="window.open('<?php echo htmlspecialchars($profil['CV']); ?>', '_blank')">
        Mon CV
      </button>
    </div>

    <div id="socials-container">
      <?php
      $result = mysqli_query($conn, "SELECT * FROM socials");
      while ($row = mysqli_fetch_assoc($result)) {
          echo '<a href="' . htmlspecialchars($row['url']) . '" target="_blank">';
          echo '<img src="' . htmlspecialchars($row['icon_path']) . '" class="icon" />';
          echo '</a>';
      }
      ?>
    </div>
  </div>
</section>

<!-- COMPETENCES -->
<section id="experience">
  <h1 class="title">Mes compétences</h1>

  <div class="experience-details-container">
    <div class="about-containers">

      <?php
      $types = ['Front-end', 'Back-End', 'Outils', 'Design'];

      foreach ($types as $type) {
          $req = mysqli_query($conn, "SELECT * FROM competences WHERE type='$type'");

          if (mysqli_num_rows($req) > 0) {
              echo '<div class="details-container color-container">';
              echo '<h2 class="experience-sub-title project-title">' . $type . '</h2>';
              echo '<div class="article-container skill-list">';

              while ($c = mysqli_fetch_assoc($req)) {
                  echo '<article>';
                  echo '<img src="./assets/checkmark.png" class="icon" />';
                  echo '<div>';
                  echo '<h3>' . htmlspecialchars($c['tech']) . '</h3>';
                  echo '<p>' . htmlspecialchars($c['niveau']) . '</p>';
                  echo '</div>';
                  echo '</article>';
              }

              echo '</div></div>';
          }
      }
      ?>

    </div>
  </div>
</section>

<!-- PROJETS POO -->
<section id="projects">
  <h1 class="title">Mes projets</h1>

  <?php
  // Extrait les types uniques depuis les projets déjà chargés
  $types_list = [];
  if ($projects && $projects->num_rows > 0) {
    // Parcourt les projets pour collecter les types uniques
      $projects->data_seek(0);
      while ($row = $projects->fetch_assoc()) {
          $t = trim($row['type'] ?? '');
          if ($t !== '' && !in_array($t, $types_list)) {
              $types_list[] = $t;
          }
      }
      $projects->data_seek(0);
  }
  ?>

  <!-- Affiche les boutons de filtre si des types sont disponibles -->
  <?php if (!empty($types_list)): ?>
  <div class="filter-container">
    <button class="filter-btn active" data-filter="all">Tous</button>
    <?php foreach ($types_list as $type): ?>
      <button class="filter-btn" data-filter="<?php echo htmlspecialchars($type); ?>">
        <?php echo htmlspecialchars($type); ?>
      </button>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <div class="experience-details-container">
    <div class="about-containers" id="projects-grid">

      <?php if ($projects && $projects->num_rows > 0): ?>
        <?php while ($p = $projects->fetch_assoc()): ?>

          <?php
          // Récupère les compétences liées à ce projet
          $stmt = mysqli_prepare($conn,
            "SELECT c.tech, c.type
             FROM competences c
             INNER JOIN projet_competence pc ON pc.competence_id = c.id
             WHERE pc.projet_id = ?
             ORDER BY c.type, c.tech"
          );

          mysqli_stmt_bind_param($stmt, 'i', $p['id']);
          mysqli_stmt_execute($stmt);
          $comp_result = mysqli_stmt_get_result($stmt);
          $competences = [];
          while ($c = mysqli_fetch_assoc($comp_result)) {
              $competences[] = $c;
          }
          mysqli_stmt_close($stmt);
          ?>
          <!-- Carte de projet -->
          <div class="details-container color-container project-card"
               data-type="<?php echo htmlspecialchars($p['type'] ?? ''); ?>">

        <!-- Affiche l'image du projet ou une image par défaut si aucune n'est fournie -->
            <div class="article-container">
              <img src="<?php echo htmlspecialchars($p['image'] ?? './assets/default.png'); ?>"
                   alt="<?php echo htmlspecialchars($p['titre']); ?>"
                   class="project-img" />
            </div>

        <!-- Affiche le type du projet s'il est disponible -->
            <?php if (!empty($p['type'])): ?>
              <span class="project-type-badge"><?php echo htmlspecialchars($p['type']); ?></span>
            <?php endif; ?>

            <h2 class="experience-sub-title project-title">
              <?php echo htmlspecialchars($p['titre'] ?? ''); ?>
            </h2>

        <!-- Affiche les compétences liées au projet -->
            <?php if (!empty($competences)): ?>
              <div class="project-skills">
                <?php foreach ($competences as $c): ?>
                  <span class="skill-tag skill-tag--<?php echo strtolower(str_replace(['-', ' '], '', $c['type'])); ?>">
                    <?php echo htmlspecialchars($c['tech']); ?>
                  </span>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <div class="btn-container">

              <?php if (!empty($p['lien_github'])): ?>
                <button class="btn btn-color-2 project-btn"
                  onclick="window.open('<?php echo htmlspecialchars($p['lien_github']); ?>', '_blank')">
                  Github
                </button>
              <?php endif; ?>

              <?php if (!empty($p['lien_demo'])): ?>
                <button class="btn btn-color-2 project-btn"
                  onclick="window.open('<?php echo htmlspecialchars($p['lien_demo']); ?>', '_blank')">
                  Live Demo
                </button>
              <?php endif; ?>

            </div>

          </div>

        <?php endwhile; ?>
      <?php else: ?>
        <p>Aucun projet pour le moment.</p>
      <?php endif; ?>

    </div>
  </div>
</section>

<script>
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    // Bouton actif
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const filter = btn.dataset.filter;
    const cards  = document.querySelectorAll('.project-card');

    cards.forEach(card => {
      const match = filter === 'all' || card.dataset.type === filter;
      if (match) {
        card.classList.remove('card-hidden');
        card.classList.add('card-visible');
      } else {
        card.classList.remove('card-visible');
        card.classList.add('card-hidden');
      }
    });
  });
});
</script>

<section id="video">
  <h1 class="title">Ma dernière production</h1>

  <div style="display:flex; justify-content:center; margin-top:2rem;">
    
    <div style="width:100%; max-width:800px; position:relative; padding-bottom:56.25%; height:0;">
      
      <iframe 
        src="https://www.youtube.com/embed/CkW7c6UP7vc"
        title="Ma dernière production"
        frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
        allowfullscreen
        style="position:absolute; top:0; left:0; width:100%; height:100%; border-radius:1rem;">
      </iframe>

    </div>

  </div>
</section>

<section id="contact">
  <p class="section__text__p1">Comment</p>
  <h1 class="title">Me contacter</h1>

  <?php
  $form_prenom = "";
  $form_nom = "";
  $form_email = "";
  $form_message = "";
  $form_succes = false;
  $form_erreur = "";

  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
      $form_prenom  = htmlspecialchars(trim($_POST['prenom']));
      $form_nom     = htmlspecialchars(trim($_POST['nomdefamille']));
      $form_email   = htmlspecialchars(trim($_POST['email']));
      $form_message = htmlspecialchars(trim($_POST['message']));

      if (empty($form_prenom) || empty($form_nom) || empty($form_email) || empty($form_message)) {
          $form_erreur = "Tous les champs sont obligatoires.";
      } elseif (!filter_var($form_email, FILTER_VALIDATE_EMAIL)) {
          $form_erreur = "L'adresse e-mail n'est pas valide.";
      } else {
          $admin_email = "yosra.mesbahipro@gmail.com";
          $headers = "From: contact@portfolio.fr\r\nReply-To: $form_email\r\nContent-Type: text/plain; charset=UTF-8";

          $sujet_admin = "Nouveau message de $form_prenom $form_nom depuis le portfolio";
          $corps_admin = "Prénom : $form_prenom\nNom : $form_nom\nEmail : $form_email\n\nMessage :\n$form_message";
          mail($admin_email, $sujet_admin, $corps_admin, $headers);
          $stmt = mysqli_prepare($conn, "INSERT INTO messages (prenom, nom, email, message) VALUES (?, ?, ?, ?)");
          mysqli_stmt_bind_param($stmt, "ssss", $form_prenom, $form_nom, $form_email, $form_message);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

          $sujet_user = "Votre message a bien été reçu";
          $corps_user = "Bonjour $form_prenom $form_nom,\n\nMerci pour votre message ! Je reviendrai vers vous très prochainement.\n\nCordialement,\nYosra Mesbahi";
          mail($form_email, $sujet_user, $corps_user, $headers);

          $form_succes = true;
          $form_prenom = $form_nom = $form_email = $form_message = "";
      }
  }
  ?>

  <?php if ($form_succes): ?>
    <div style="background:#d4edda;border:1px solid #c3e6cb;color:#155724;padding:1rem 1.5rem;border-radius:1rem;max-width:600px;margin:1rem auto;text-align:center;">
      Message envoyé ! Je vous répondrai rapidement.
    </div>
  <?php endif; ?>

  <?php if ($form_erreur): ?>
    <div style="background:#f8d7da;border:1px solid #f5c6cb;color:#721c24;padding:1rem 1.5rem;border-radius:1rem;max-width:600px;margin:1rem auto;text-align:center;">
      <?php echo $form_erreur; ?>
    </div>
  <?php endif; ?>

  <form action="#contact" method="POST" style="max-width:600px;margin:1.5rem auto;display:flex;flex-direction:column;gap:1rem;">

    <div style="display:flex;gap:1rem;">
      <input type="text" name="prenom" placeholder="Votre prénom" value="<?php echo $form_prenom; ?>" required
        style="flex:1;padding:0.9rem 1.2rem;border-radius:1rem;border:1px solid rgb(163,163,163);font-family:'Poppins',sans-serif;font-size:1rem;">
      <input type="text" name="nomdefamille" placeholder="Votre nom" value="<?php echo $form_nom; ?>" required
        style="flex:1;padding:0.9rem 1.2rem;border-radius:1rem;border:1px solid rgb(163,163,163);font-family:'Poppins',sans-serif;font-size:1rem;">
    </div>

    <input type="email" name="email" placeholder="Votre adresse e-mail" value="<?php echo $form_email; ?>" required
      style="padding:0.9rem 1.2rem;border-radius:1rem;border:1px solid rgb(163,163,163);font-family:'Poppins',sans-serif;font-size:1rem;">

    <textarea name="message" placeholder="Votre message..." rows="5" required
      style="padding:0.9rem 1.2rem;border-radius:1rem;border:1px solid rgb(163,163,163);font-family:'Poppins',sans-serif;font-size:1rem;resize:vertical;"><?php echo $form_message; ?></textarea>

    <button type="submit" name="contact_submit" class="btn btn-color-1" style="align-self:center;width:auto;padding:1rem 2rem;">
      Envoyer
    </button>
  </form>
</section>

</main>

<footer>
  <p>Copyright © 2026 Yosra Mesbahi</p>
</footer>

</body>
</html>