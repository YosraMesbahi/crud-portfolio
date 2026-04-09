<?php include_once('social-media.php'); ?>
<?php include('profil.php'); ?>
<?php include('competences.php'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Portfolio MMI | Yosra Mesbahi</title>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="mediaqueries.css" />
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

    <nav id="hamburger-nav">
      <div>
        <a href="index.php" class="logo">Yosra Mesbahi</a>
      </div>
      <div class="hamburger-menu">
        <div class="hamburger-icon" onclick="toggleMenu()">
          <span></span><span></span><span></span>
        </div>
        <div class="menu-links">
          <ul>
            <?php
            if ($menuItems) {
                mysqli_data_seek($menuItems, 0);
                while ($item = mysqli_fetch_assoc($menuItems)) {
                    echo '<li><a href="' . htmlspecialchars($item['ancre']) . '" onclick="toggleMenu()">'
                         . htmlspecialchars($item['menu_item']) . '</a></li>';
                }
            }
            ?>
          </ul>
        </div>
      </div>
    </nav>
  </header>

  <main>
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
          $query = "SELECT * FROM socials";
          $result = mysqli_query($conn, $query);
          if ($result && mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                  echo '<a href="' . htmlspecialchars($row['url']) . '" target="_blank" rel="noopener noreferrer">';
                  echo '<img src="' . htmlspecialchars($row['icon_path']) . '" alt="' . htmlspecialchars($row['nom']) . '" class="icon" />';
                  echo '</a>';
              }
          }
          ?>
        </div>
      </div>
    </section>

<section id="experience">
  <p class="section__text__p1">Découvrez</p>
  <h1 class="title">Mes compétences</h1>
  <div class="experience-details-container">
    <div class="about-containers">

      <?php
      // Récupération des types distincts de compétences
      $types = ['Front-end', 'Back-End', 'Outils', 'Design'];

      foreach ($types as $type) {
          // On vérifie s'il y a des compétences pour ce type
          $type_escape = mysqli_real_escape_string($conn, $type);
          $req = mysqli_query($conn, "SELECT * FROM competences WHERE type = '$type_escape' ORDER BY id ASC");

          if (mysqli_num_rows($req) > 0) {
              echo '<div class="details-container">';
              echo '<h2 class="experience-sub-title">' . htmlspecialchars($type) . '</h2>';
              echo '<div class="article-container">';

              while ($competence = mysqli_fetch_assoc($req)) {
                  echo '<article>';
                  echo '<img src="./assets/checkmark.png" alt="Check" class="icon" />';
                  echo '<div>';
                  echo '<h3>' . htmlspecialchars($competence['tech']) . '</h3>';
                  echo '<p>' . htmlspecialchars($competence['niveau']) . '</p>';
                  echo '</div>';
                  echo '</article>';
              }

              echo '</div>';
              echo '</div>';
          }
      }
      ?>

    </div>
  </div>
  <img src="./assets/arrow.png" alt="Arrow" class="icon arrow" onclick="location.href='#projects'" />
</section>

    <section id="projects">
      <p class="section__text__p1">Découvrez</p>
      <h1 class="title">Mes projets</h1>
      <div class="experience-details-container">
        <div class="about-containers">
          <div class="details-container color-container">
            <div class="article-container">
              <img src="./assets/Projet-3.png" alt="Projet 1" class="project-img" />
            </div>
            <h2 class="experience-sub-title project-title">Dynamisation d'un site</h2>
            <div class="btn-container">
              <button class="btn btn-color-2 project-btn" onclick="window.open('https://github.com/YosraMesbahi/gestion-adherents-club', '_blank')">Github</button>
              <button class="btn btn-color-2 project-btn" onclick="window.open('https://mesbahi.alwaysdata.net/backoffice-crud/login.php', '_blank')">Live Demo</button>
            </div>
          </div>
          <div class="details-container color-container">
            <div class="article-container">
              <img src="./assets/Projet-4.png" alt="Projet 2" class="project-img" />
            </div>
            <h2 class="experience-sub-title project-title">Visualisations de données</h2>
            <div class="btn-container">
              <button class="btn btn-color-2 project-btn" onclick="window.open('https://github.com/YosraMesbahi/SAE303', '_blank')">Github</button>
              <button class="btn btn-color-2 project-btn" onclick="window.open('https://yosramesbahi.github.io/SAE303/', '_blank')">Live Demo</button>
            </div>
          </div>
        </div>
      </div>
      <img src="./assets/arrow.png" alt="Arrow" class="icon arrow" onclick="location.href='#contact'" />
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
    <nav>
      <div class="nav-links-container">
        <ul class="nav-links">
          <?php
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
    <p>Copyright &#169; 2026 Yosra Mesbahi. Tous droits réservés.</p>
  </footer>

  <script src="script.js"></script>
</body>
<?php mysqli_close($conn); ?>
</html>