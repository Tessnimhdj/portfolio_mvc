// Init animations
AOS.init({ duration: 1000, once: true });


// Navbar scroll effect
window.addEventListener("scroll", function() {
  const navbar = document.querySelector(".navbar");
  navbar.classList.toggle("scrolled", window.scrollY > 50);
});


// Language toggle (simple demo)
document.getElementById("langToggle").addEventListener("click", function() {
  if (this.textContent === "FR") {
    this.textContent = "EN";
    alert("French mode (you can later translate your content)");
  } else {
    this.textContent = "FR";
    alert("English mode");
  }
});


// EmailJS integration
(function() {
  emailjs.init("YOUR_PUBLIC_KEY"); // Replace with your EmailJS public key
})();

document.getElementById("contactForm").addEventListener("submit", function(e) {
  e.preventDefault();

  emailjs.send("YOUR_SERVICE_ID", "YOUR_TEMPLATE_ID", {
    from_name: document.getElementById("name").value,
    from_email: document.getElementById("email").value,
    message: document.getElementById("message").value
  })
  .then(() => {
    document.getElementById("msgStatus").innerText = "Message sent successfully 💌";
    this.reset();
  })
  .catch(() => {
    document.getElementById("msgStatus").innerText = "Oops! Something went wrong 😢";
  });
});