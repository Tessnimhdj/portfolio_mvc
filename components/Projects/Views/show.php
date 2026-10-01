<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($project['title'] ?? 'Project') ?> | Tessnim Hadjredjem</title>
  <script>
    (function () {
      var lang = localStorage.getItem("site_lang") || "en";
      if (lang !== "ar" && lang !== "fr") lang = "en";
      document.documentElement.lang = lang;
      document.documentElement.dir = lang === "ar" ? "rtl" : "ltr";
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/CSS/style.css?v=9" />
</head>

<body>
  <nav class="navbar navbar-expand-lg fixed-top custom-navbar shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-gradient fs-3" href="<?= BASE_URL ?>/">Tessnim<span>H.</span></a>
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <a class="nav-link" href="<?= BASE_URL ?>/" data-i18n="nav.back">Back to Home</a>
        </li>
        <li class="nav-item lang-switch">
          <button type="button" id="langToggle" class="btn btn-sm btn-pink">EN</button>
          <ul class="lang-options">
            <li><button type="button" data-lang="en">EN</button></li>
            <li><button type="button" data-lang="fr">FR</button></li>
            <li><button type="button" data-lang="ar">AR</button></li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>

  <section class="py-5" style="margin-top: 80px;">
    <?php if (!empty($project['cover_image'])): ?>
      <img
        src="<?= htmlspecialchars($project['cover_image']) ?>"
        alt="<?= htmlspecialchars($project['title'] ?? '') ?>"
        class="w-100"
        style="max-height: 480px; object-fit: cover;" />
    <?php endif; ?>

    <div class="container py-5">
      <h1 class="text-gradient mb-4"><?= htmlspecialchars($project['title'] ?? '') ?></h1>

      <div class="mb-4">
        <?= nl2br(htmlspecialchars($project['details'] ?? '')) ?>
      </div>

      <?php
      $technologies = $project['technologies'] ?? [];
      if (!is_array($technologies)) {
          $technologies = [];
      }
      $technologies = array_filter($technologies, fn($tech) => trim((string) $tech) !== '');
      ?>

      <?php if (!empty($technologies)): ?>
        <div class="mb-4">
          <?php foreach ($technologies as $tech): ?>
            <span class="badge rounded-pill bg-secondary me-1 mb-1"><?= htmlspecialchars($tech) ?></span>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="d-flex flex-wrap gap-2">
        <?php if (!empty($project['demo_url'])): ?>
          <a href="<?= htmlspecialchars($project['demo_url']) ?>" class="btn btn-pink" target="_blank" rel="noopener noreferrer" data-i18n="project.demo">Live Demo</a>
        <?php endif; ?>
        <?php if (!empty($project['github_url'])): ?>
          <a href="<?= htmlspecialchars($project['github_url']) ?>" class="btn btn-outline-dark" target="_blank" rel="noopener noreferrer" data-i18n="project.github">View on GitHub</a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="<?= BASE_URL ?>/JS/i18n.js?v=9"></script>
</body>

</html>
