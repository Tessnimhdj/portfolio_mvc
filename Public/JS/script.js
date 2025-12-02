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


document.getElementById('submit').addEventListener('click', function(e) {
    e.preventDefault();

    let form = document.getElementById('contactForm');
    let data = new FormData(form);
    
    let name = data.get('name');
    let email = data.get('email');
    let message = data.get('message');

    fetch('http://localhost/mes_projet/portfolio_mvc/contact/sendEmail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            email: email,
            message: message
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            alert('تم إرسال الرسالة بنجاح!');
            form.reset();
        } else {
            alert('حدث خطأ: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في الاتصال');
    });
});