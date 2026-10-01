<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tessnim Hadjredjem | Portfolio</title>
  <script>
    (function () {
      var lang = localStorage.getItem("site_lang") || "en";
      if (lang !== "ar" && lang !== "fr") {
        lang = "en";
      }
      document.documentElement.lang = lang;
      document.documentElement.dir = lang === "ar" ? "rtl" : "ltr";
    })();
  </script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&family=Poppins:wght@400;500;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= BASE_URL ?>/CSS/style.css?v=9" />
  <script src="https://www.google.com/recaptcha/api.js?hl=en" async defer></script>
</head>
<body>
  <div class="site-bg" aria-hidden="true"></div>

  <nav class="navbar navbar-expand-lg fixed-top custom-navbar shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-gradient fs-3" href="#home">Tessnim<span>H.</span></a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav gap-3">
          <li class="nav-item"><a class="nav-link" href="#home" data-i18n="nav.home">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#about" data-i18n="nav.about">About</a></li>
          <li class="nav-item"><a class="nav-link" href="#projects" data-i18n="nav.projects">Projects</a></li>
          <li class="nav-item"><a class="nav-link" href="#skills" data-i18n="nav.skills">Skills</a></li>
          <?php if (!empty($cv['file_url'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="<?= htmlspecialchars($cv['file_url']) ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.cv">CV</a>
          </li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="#contact" data-i18n="nav.contact">Contact</a></li>
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
    </div>
  </nav>

  <section id="home" class="hero-section d-flex align-items-center">
    <div class="container d-flex flex-column flex-md-row align-items-center justify-content-center text-center text-md-start">
      <div class="hero-text col-md-6">
        <h1 class="fw-bold mb-3" data-aos="fade-up">
          <span data-i18n="hero.hi">Hi, I'm</span> <span class="text-gradient">Tessnim Hadjredjem</span>
        </h1>
        <h3 class="mb-4" data-aos="fade-up" data-aos-delay="100" data-i18n="hero.role">Software Engineer & Web Developer</h3>
        <p class="mb-4" data-aos="fade-up" data-aos-delay="150" data-i18n="hero.desc">
          I create elegant, responsive, and user-friendly web apps using Laravel, PHP, and JavaScript.
        </p>
        <a href="#projects" id="viewWorkBtn" class="btn btn-pink" data-i18n="hero.cta">View My Work</a>
        <?php include __DIR__ . '/partials/social.php'; ?>
      </div>
      <div class="hero-img col-md-5 mt-4 mt-md-0 text-center">
        <img src="<?= BASE_URL ?>/images/anime.jpg" alt="Tessnim portrait" class="img-fluid rounded-circle shadow-lg border border-3 border-pink" />
      </div>
    </div>
  </section>

  <section id="about" class="section-bg" data-aos="fade-right">
    <div class="container text-center">
      <h2 class="text-gradient mb-4" data-i18n="about.title">About Me</h2>
      <p class="w-75 mx-auto" data-i18n="about.text">
        I'm a passionate software engineer specialized in web development. I enjoy combining logic and design to create beautiful and efficient user experiences. Skilled in Laravel, PHP, and JavaScript, I love building interactive, dynamic websites.
      </p>
    </div>
  </section>

  <section id="projects" class="section-bg">
    <div class="container">
      <h2 class="text-center text-gradient mb-5" data-i18n="projects.title">My Projects</h2>
      <?php if (!empty($projects)): ?>
      <div class="row g-4">
        <?php foreach ($projects as $project): ?>
        <div class="col-md-6 col-lg-4">
          <a href="<?= BASE_URL ?>/Projects/show?id=<?= $project['id'] ?>" class="text-decoration-none text-dark">
            <div class="card project-card">
              <img src="<?= htmlspecialchars($project['cover_image'] ?? '') ?>" class="card-img-top" alt="<?= htmlspecialchars($project['title'] ?? '') ?>" />
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($project['title'] ?? '') ?></h5>
                <p><?= htmlspecialchars($project['description'] ?? '') ?></p>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <section id="skills" class="section-bg" data-aos="fade-left">
    <div class="container">
      <h2 class="text-center text-gradient mb-5" data-i18n="skills.title">Technical Skills</h2>
      <?php if (!empty($skills)): ?>
      <div class="row g-4">
        <?php foreach ($skills as $skill): ?>
        <div class="col-md-6 col-lg-4">
          <div class="card project-card">
            <?php if (!empty($skill['cover_image'])): ?>
            <img src="<?= htmlspecialchars($skill['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($skill['title'] ?? '') ?>" />
            <?php endif; ?>
            <div class="card-body">
              <?php if (!empty($skill['category'])): ?>
              <p class="text-muted small mb-1"><?= htmlspecialchars($skill['category']) ?></p>
              <?php endif; ?>
              <h5 class="card-title"><?= htmlspecialchars($skill['title'] ?? '') ?></h5>
              <p><?= htmlspecialchars($skill['description'] ?? '') ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <section id="contact" data-aos="fade-up">
    <div class="container text-center">
      <h2 class="text-gradient mb-4" data-i18n="contact.title">Contact Me</h2>
      <form id="contactForm" class="w-75 mx-auto mb-4">
        <input type="text" id="name" name="name" class="form-control mb-3" placeholder="Your Name" data-i18n-placeholder="contact.name" required />
        <input type="email" id="email" name="email" class="form-control mb-3" placeholder="Your Email" data-i18n-placeholder="contact.email" required />
        <textarea id="message" name="message" class="form-control mb-3" rows="4" placeholder="Your Message" data-i18n-placeholder="contact.message" required></textarea>
        <div class="recaptcha-wrapper">
          <div class="g-recaptcha" data-sitekey="6Le-AiQsAAAAAIxzVQ9HWlMxv35Hqe_GYMiILt_8" data-theme="light"></div>
        </div>
        <button type="submit" class="btn btn-pink" id="submit" data-i18n="contact.send">Send Message</button>
        <p id="msgStatus" class="mt-3"></p>
      </form>
      <div class="contact-info mt-4">
        <p class="fw-bold">
          <span data-i18n="contact.phone1">Phone 1:</span> <span class="text-gradient">+213 561 129 251</span><br>
          <span data-i18n="contact.phone2">Phone 2:</span> <span class="text-gradient">+213 675 192 165</span>
        </p>
        <p class="fw-bold">
          <span data-i18n="contact.emailLabel">Email:</span>
          <a href="mailto:tessnimhdj0@gmail.com" class="text-gradient">tessnimhdj0@gmail.com</a>
        </p>
      </div>
      <?php include __DIR__ . '/partials/social.php'; ?>
    </div>
  </section>

  <footer class="text-center py-3 bg-light">
    <p>&copy; 2025 Tessnim Hadjredjem. <span data-i18n="footer.copy">All Rights Reserved.</span></p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="<?= BASE_URL ?>/JS/i18n.js?v=10"></script>
  <script src="<?= BASE_URL ?>/JS/script.js?v=10"></script>
</body>
</html>
