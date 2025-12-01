<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tessnim Hadjredjem | Portfolio</title>

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- Bootstrap -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <!-- AOS animation -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="/mes_projet/portfolio_mvc/Public/CSS/style.css" />

</head>

<body>
  <!-- ===== NAVBAR ===== -->
  <nav class="navbar navbar-expand-lg fixed-top custom-navbar shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold text-gradient fs-3" href="#home">Tessnim<span>H.</span></a>
      <button
        class="navbar-toggler border-0"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div
        class="collapse navbar-collapse justify-content-end"
        id="navbarNav">
        <ul class="navbar-nav gap-3">
          <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
          <li class="nav-item">
            <a class="nav-link" href="#about">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#projects">Projects</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#skills">Skills</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contact">Contact</a>
          </li>
          <li class="nav-item">
            <button id="langToggle" class="btn btn-sm btn-pink">FR</button>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- ===== HERO ===== -->
  <section id="home" class="hero-section d-flex align-items-center">
    <div
      class="container d-flex flex-column flex-md-row align-items-center justify-content-center text-center text-md-start"
      data-aos="fade-up">
      <!-- Text -->
      <div class="hero-text col-md-6">
        <h1 class="fw-bold mb-3">
          Hi, I'm <span class="text-gradient">Tessnim Hadjredjem</span>
        </h1>
        <h3 class="mb-4">Software Engineer & Web Developer</h3>
        <p class="mb-4">
          I create elegant, responsive, and user-friendly web apps using
          Laravel, PHP, and JavaScript.
        </p>
        <a href="#projects" class="btn btn-pink">View My Work</a>

        <!-- Social Links -->
        <div class="social-icons mt-4">
          <a
            href="https://github.com/Tessnimhdj"
            target="_blank"
            title="GitHub">
            <i class="fab fa-github"></i>
          </a>
          <a
            href="https://www.linkedin.com/in/tessnim-hadjredjem-4aa3a4294/"
            target="_blank"
            title="LinkedIn">
            <i class="fab fa-linkedin"></i>
          </a>
          <a
            href="https://www.facebook.com/Tessnim.hdj"
            target="_blank"
            title="Facebook">
            <i class="fab fa-facebook"></i>
          </a>
        </div>
      </div>

      <!-- Image -->
      <div class="hero-img col-md-5 mt-4 mt-md-0 text-center">
        <img
          src="/mes_projet/portfolio_mvc/Public/images/moi.jpg"
          alt="Tessnim portrait"
          class="img-fluid rounded-circle shadow-lg border border-3 border-pink" />
      </div>
    </div>
  </section>

  <!-- ===== ABOUT ===== -->
  <section id="about" class="py-5 bg-light" data-aos="fade-right">
    <div class="container text-center">
      <h2 class="text-gradient mb-4">About Me</h2>
      <p class="w-75 mx-auto">
        I'm a passionate software engineer specialized in web development. I
        enjoy combining logic and design to create beautiful and efficient
        user experiences. Skilled in Laravel, PHP, and JavaScript, I love
        building interactive, dynamic websites.
      </p>
    </div>
  </section>

  <!-- ===== PROJECTS ===== -->
  <section id="projects" class="py-5" data-aos="fade-up">
    <div class="container">
      <h2 class="text-center text-gradient mb-5">My Projects</h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card project-card">
            <img src="project1.jpg" class="card-img-top" alt="E-learning" />
            <div class="card-body">
              <h5 class="card-title">E-learning Platform</h5>
              <p>
                Educational platform with roles for teachers, students, and
                admins.
              </p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card project-card">
            <img
              src="project2.jpg"
              class="card-img-top"
              alt="Hotel Booking" />
            <div class="card-body">
              <h5 class="card-title">Hotel Booking Website</h5>
              <p>Hotel reservation system with gallery and room details.</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="card project-card">
            <img
              src="project3.jpg"
              class="card-img-top"
              alt="Attendance System" />
            <div class="card-body">
              <h5 class="card-title">Attendance System</h5>
              <p>
                Employee attendance tracking web app with admin dashboard.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== SKILLS ===== -->
  <section id="skills" class="py-5 bg-light" data-aos="fade-left">
    <div class="container text-center">
      <h2 class="text-gradient mb-4">Technical Skills</h2>
      <div class="row">
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li>💻 JavaScript, PHP, Python</li>
            <li>🧩 Laravel, Bootstrap</li>
          </ul>
        </div>
        <div class="col-md-6">
          <ul class="list-unstyled">
            <li>🗄️ MySQL, MongoDB, Firebase</li>
            <li>⚙️ GitHub, VS Code</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CONTACT ===== -->
  <section id="contact" class="py-5" data-aos="fade-up">
    <div class="container text-center">
      <h2 class="text-gradient mb-4">Contact Me</h2>

      <!-- Contact Form -->
      <form id="contactForm" class="w-75 mx-auto mb-4">
        <input
          type="text"
          id="name"
          class="form-control mb-3"
          placeholder="Your Name"
          required />
        <input
          type="email"
          id="email"
          class="form-control mb-3"
          placeholder="Your Email"
          required />
        <textarea
          id="message"
          class="form-control mb-3"
          rows="4"
          placeholder="Your Message"
          required></textarea>
        <button type="submit" class="btn btn-pink">Send Message</button>
        <p id="msgStatus" class="mt-3"></p>
      </form>

      <!-- Contact Info -->
      <div class="contact-info mt-4">
        <p class="fw-bold">
          📞 Phone 1: <span class="text-gradient">+213 561 129 251</span><br>
          📞 Phone 2: <span class="text-gradient">+213 675 192 165</span>
        </p>
        <p class="fw-bold">
          📧 Email:
          <a href="mailto:tessnimhdj0@gmail.com" class="text-gradient">tessnimhdj0@gmail.com</a>
        </p>
      </div>

      <!-- Social Links -->
      <div class="social-icons mt-4">
        <a
          href="https://github.com/Tessnimhdj"
          target="_blank"
          title="GitHub">
          <i class="fab fa-github"></i>
        </a>
        <a
          href="https://www.linkedin.com/in/tessnim-hadjredjem-4aa3a4294/"
          target="_blank"
          title="LinkedIn">
          <i class="fab fa-linkedin"></i>
        </a>
        <a
          href="https://www.facebook.com/Tessnim.hdj"
          target="_blank"
          title="Facebook">
          <i class="fab fa-facebook"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  <footer class="text-center py-3 bg-light">
    <p>© 2025 Tessnim Hadjredjem 💖 All Rights Reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/emailjs-com@3/dist/email.min.js"></script>
  <script src="/mes_projet/portfolio_mvc/Public/JS/script.js"></script>
</body>

</html>