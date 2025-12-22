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


// ============================================
// 🔒 نموذج الاتصال مع reCAPTCHA
// ============================================
document.getElementById('submit').addEventListener('click', function(e) {
    e.preventDefault();

    let form = document.getElementById('contactForm');
    let data = new FormData(form);
    
    let name = data.get('name');
    let email = data.get('email');
    let message = data.get('message');
    
    // التحقق من البيانات
    if (!name || !email || !message) {
        alert('⚠️ الرجاء ملء جميع الحقول');
        return;
    }
    
    // ✅ الحصول على توكن reCAPTCHA
    let recaptchaToken = grecaptcha.getResponse();
    
    // ✅ التحقق من أن المستخدم حل الكابتشا
    if (!recaptchaToken) {
        alert('⚠️ يرجى إكمال التحقق من reCAPTCHA أولاً');
        return;
    }
    
    // تعطيل زر الإرسال أثناء المعالجة
    let submitBtn = document.getElementById('submit');
    let originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = '⏳ جاري الإرسال...';

    // ✅ إرسال البيانات مع التوكن
    // المسار المباشر للملف في services
    fetch('/mes_projet/portfolio_mvc/services/ContactController.php', {
    // fetch('http://localhost/mes_projet/portfolio_mvc/services/ContactController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            email: email,
            message: message,
            recaptcha_token: recaptchaToken
        })
    })
    .then(response => {
        // التحقق من أن الاستجابة صحيحة
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('Response:', data);
        if (data.success) {
            alert('✅ تم إرسال الرسالة بنجاح!');
            form.reset();
            grecaptcha.reset();
        } else {
            alert('❌ حدث خطأ: ' + data.message);
            grecaptcha.reset();
        }
        
        // إعادة تفعيل الزر
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('❌ حدث خطأ في الاتصال. تأكد من أن الخادم يعمل.');
        grecaptcha.reset();
        
        // إعادة تفعيل الزر
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});