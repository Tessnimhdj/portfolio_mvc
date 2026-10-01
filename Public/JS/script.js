if (typeof AOS !== "undefined") {
  AOS.init({ duration: 1000, once: true });
}

if (window.location.hash) {
  history.replaceState(null, "", window.location.pathname + window.location.search);
}

document.querySelectorAll('a[href^="#"]').forEach(function (link) {
  link.addEventListener("click", function (event) {
    const id = this.getAttribute("href").slice(1);
    const target = document.getElementById(id);
    if (!target) {
      return;
    }
    event.preventDefault();
    target.scrollIntoView({ behavior: "smooth", block: "start" });
  });
});

window.addEventListener("scroll", function () {
  const navbar = document.querySelector(".navbar");
  if (navbar) {
    navbar.classList.toggle("scrolled", window.scrollY > 50);
  }
});

const submitBtn = document.getElementById("submit");
if (submitBtn) {
  submitBtn.addEventListener("click", function (event) {
    event.preventDefault();

    const form = document.getElementById("contactForm");
    const data = new FormData(form);
    const name = data.get("name");
    const email = data.get("email");
    const message = data.get("message");
    const t = (key) => (window.I18N ? window.I18N.t(key) : key);

    if (!name || !email || !message) {
      alert(t("contact.required"));
      return;
    }

    const recaptchaToken = grecaptcha.getResponse();
    if (!recaptchaToken) {
      alert(t("contact.captcha"));
      return;
    }

    submitBtn.disabled = true;
    submitBtn.textContent = t("contact.sending");

    fetch("/mes_projet/portfolio_mvc/services/ContactController.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        name: name,
        email: email,
        message: message,
        recaptcha_token: recaptchaToken,
      }),
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.json();
      })
      .then((result) => {
        if (result.success) {
          alert(t("contact.success"));
          form.reset();
          grecaptcha.reset();
        } else {
          alert(t("contact.error") + result.message);
          grecaptcha.reset();
        }
        submitBtn.disabled = false;
        submitBtn.textContent = t("contact.send");
      })
      .catch(() => {
        alert(t("contact.network"));
        grecaptcha.reset();
        submitBtn.disabled = false;
        submitBtn.textContent = t("contact.send");
      });
  });
}
