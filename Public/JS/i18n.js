const translations = {
  en: {
    "nav.home": "Home",
    "nav.about": "About",
    "nav.projects": "Projects",
    "nav.skills": "Skills",
    "nav.cv": "CV",
    "nav.contact": "Contact",
    "nav.back": "Back to Home",
    "hero.hi": "Hi, I'm",
    "hero.role": "Software Engineer & Web Developer",
    "hero.desc": "I create elegant, responsive, and user-friendly web apps using Laravel, PHP, and JavaScript.",
    "hero.cta": "View My Work",
    "about.title": "About Me",
    "about.text": "I'm a passionate software engineer specialized in web development. I enjoy combining logic and design to create beautiful and efficient user experiences. Skilled in Laravel, PHP, and JavaScript, I love building interactive, dynamic websites.",
    "projects.title": "My Projects",
    "skills.title": "Technical Skills",
    "contact.title": "Contact Me",
    "contact.name": "Your Name",
    "contact.email": "Your Email",
    "contact.message": "Your Message",
    "contact.send": "Send Message",
    "contact.phone1": "Phone 1:",
    "contact.phone2": "Phone 2:",
    "contact.emailLabel": "Email:",
    "contact.required": "Please fill in all fields.",
    "contact.captcha": "Please complete the reCAPTCHA first.",
    "contact.sending": "Sending...",
    "contact.success": "Message sent successfully!",
    "contact.error": "An error occurred: ",
    "contact.network": "Connection error. Please make sure the server is running.",
    "footer.copy": "All Rights Reserved.",
    "project.demo": "Live Demo",
    "project.github": "View on GitHub"
  },
  fr: {
    "nav.home": "Accueil",
    "nav.about": "À propos",
    "nav.projects": "Projets",
    "nav.skills": "Compétences",
    "nav.cv": "CV",
    "nav.contact": "Contact",
    "nav.back": "Retour à l'accueil",
    "hero.hi": "Bonjour, je suis",
    "hero.role": "Ingénieure logiciel et développeuse web",
    "hero.desc": "Je crée des applications web élégantes, responsives et agréables à utiliser avec Laravel, PHP et JavaScript.",
    "hero.cta": "Voir mes projets",
    "about.title": "À propos de moi",
    "about.text": "Je suis une ingénieure logiciel passionnée, spécialisée dans le développement web. J'aime allier logique et design pour créer des expériences belles et efficaces. Compétente en Laravel, PHP et JavaScript, je construis des sites interactifs et dynamiques.",
    "projects.title": "Mes projets",
    "skills.title": "Compétences techniques",
    "contact.title": "Me contacter",
    "contact.name": "Votre nom",
    "contact.email": "Votre email",
    "contact.message": "Votre message",
    "contact.send": "Envoyer",
    "contact.phone1": "Téléphone 1 :",
    "contact.phone2": "Téléphone 2 :",
    "contact.emailLabel": "Email :",
    "contact.required": "Veuillez remplir tous les champs.",
    "contact.captcha": "Veuillez d'abord compléter le reCAPTCHA.",
    "contact.sending": "Envoi...",
    "contact.success": "Message envoyé avec succès !",
    "contact.error": "Une erreur s'est produite : ",
    "contact.network": "Erreur de connexion. Vérifiez que le serveur fonctionne.",
    "footer.copy": "Tous droits réservés.",
    "project.demo": "Démo en ligne",
    "project.github": "Voir sur GitHub"
  },
  ar: {
    "nav.home": "الرئيسية",
    "nav.about": "نبذة عني",
    "nav.projects": "المشاريع",
    "nav.skills": "المهارات",
    "nav.cv": "السيرة الذاتية",
    "nav.contact": "تواصل",
    "nav.back": "العودة للرئيسية",
    "hero.hi": "مرحباً، أنا",
    "hero.role": "مهندسة برمجيات ومطوّرة ويب",
    "hero.desc": "أصمّم تطبيقات ويب أنيقة ومتجاوبة وسهلة الاستخدام باستخدام Laravel وPHP وJavaScript.",
    "hero.cta": "شاهد أعمالي",
    "about.title": "نبذة عني",
    "about.text": "أنا مهندسة برمجيات شغوفة متخصصة في تطوير الويب. أحب الجمع بين المنطق والتصميم لصنع تجارب جميلة وفعّالة. متمكّنة من Laravel وPHP وJavaScript، وأستمتع ببناء مواقع تفاعلية وديناميكية.",
    "projects.title": "مشاريعي",
    "skills.title": "المهارات التقنية",
    "contact.title": "تواصل معي",
    "contact.name": "اسمك",
    "contact.email": "بريدك الإلكتروني",
    "contact.message": "رسالتك",
    "contact.send": "إرسال الرسالة",
    "contact.phone1": "الهاتف 1:",
    "contact.phone2": "الهاتف 2:",
    "contact.emailLabel": "البريد:",
    "contact.required": "يرجى ملء جميع الحقول.",
    "contact.captcha": "يرجى إكمال التحقق من reCAPTCHA أولاً.",
    "contact.sending": "جاري الإرسال...",
    "contact.success": "تم إرسال الرسالة بنجاح!",
    "contact.error": "حدث خطأ: ",
    "contact.network": "حدث خطأ في الاتصال. تأكد من أن الخادم يعمل.",
    "footer.copy": "جميع الحقوق محفوظة.",
    "project.demo": "عرض مباشر",
    "project.github": "عرض على GitHub"
  }
};

const I18N = {
  translations,
  current: "en",

  t(key) {
    const pack = this.translations[this.current] || this.translations.en;
    return pack[key] || this.translations.en[key] || key;
  },

  getSaved() {
    const saved = localStorage.getItem("site_lang");
    return this.translations[saved] ? saved : "en";
  },

  apply(lang) {
    if (!this.translations[lang]) {
      lang = "en";
    }

    this.current = lang;
    localStorage.setItem("site_lang", lang);
    document.documentElement.lang = lang;
    document.documentElement.dir = lang === "ar" ? "rtl" : "ltr";

    document.querySelectorAll("[data-i18n]").forEach((el) => {
      const key = el.getAttribute("data-i18n");
      if (key) {
        el.textContent = this.t(key);
      }
    });

    document.querySelectorAll("[data-i18n-placeholder]").forEach((el) => {
      const key = el.getAttribute("data-i18n-placeholder");
      if (key) {
        el.setAttribute("placeholder", this.t(key));
      }
    });

    const toggle = document.getElementById("langToggle");
    if (toggle) {
      toggle.textContent = lang.toUpperCase();
    }

    document.querySelectorAll(".lang-options button").forEach((btn) => {
      btn.classList.toggle("active", btn.getAttribute("data-lang") === lang);
    });
  }
};

window.I18N = I18N;

I18N.apply(I18N.getSaved());

document.querySelectorAll(".lang-switch").forEach((switcher) => {
  const toggle = switcher.querySelector("#langToggle, .btn-pink");
  const options = switcher.querySelector(".lang-options");
  if (!toggle || !options) {
    return;
  }

  toggle.addEventListener("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    switcher.classList.toggle("open");
    options.classList.toggle("open");
  });

  options.querySelectorAll("button").forEach((btn) => {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      I18N.apply(this.getAttribute("data-lang"));
      switcher.classList.remove("open");
      options.classList.remove("open");
    });
  });
});

document.addEventListener("click", function () {
  document.querySelectorAll(".lang-switch").forEach((switcher) => {
    switcher.classList.remove("open");
    const options = switcher.querySelector(".lang-options");
    if (options) {
      options.classList.remove("open");
    }
  });
});
