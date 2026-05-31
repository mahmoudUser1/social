// بيانات التطبيق
let users = JSON.parse(localStorage.getItem("users")) || [];
let currentUser = JSON.parse(localStorage.getItem("currentUser")) || null;

// عرض التنبيهات
function showAlert(message, type = "info") {
  const alertDiv = document.createElement("div");
  alertDiv.style.position = "fixed";
  alertDiv.style.top = "20px";
  alertDiv.style.left = "50%";
  alertDiv.style.transform = "translateX(-50%)";
  alertDiv.style.padding = "15px 25px";
  alertDiv.style.borderRadius = "8px";
  alertDiv.style.zIndex = "1000";
  alertDiv.style.fontWeight = "bold";
  
  if (type === "error") {
    alertDiv.style.backgroundColor = "#ffebee";
    alertDiv.style.color = "#c62828";
  } else if (type === "success") {
    alertDiv.style.backgroundColor = "#e8f5e9";
    alertDiv.style.color = "#2e7d32";
  } else {
    alertDiv.style.backgroundColor = "#e3f2fd";
    alertDiv.style.color = "#1565c0";
  }
  
  alertDiv.textContent = message;
  document.body.appendChild(alertDiv);
  
  setTimeout(() => {
    alertDiv.remove();
  }, 3000);
}

// تسجيل الدخول
if (document.getElementById("loginForm")) {
  document.getElementById("loginForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const email = document.getElementById("loginEmail").value;
    const password = document.getElementById("loginPassword").value;
    
    if (!email || !password) {
      showAlert("الرجاء إدخال البريد الإلكتروني وكلمة السر", "error");
      return;
    }
    
    // البحث عن المستخدم المطابق
    const user = users.find(u => u.email === email && u.password === password);
    
    if (user) {
      currentUser = user;
      localStorage.setItem("currentUser", JSON.stringify(currentUser));
      showAlert("تم تسجيل الدخول بنجاح", "success");
      setTimeout(() => {
        // تصحيح اسم الملف هنا
        window.location.href = "thesociety.html"; 
      }, 1500);
    } else {
      showAlert("البريد الإلكتروني أو كلمة السر غير صحيحة", "error");
    }
  });
}

// التسجيل
if (document.getElementById("registerForm")) {
  document.getElementById("registerForm").addEventListener("submit", function(e) {
    e.preventDefault();
    
    const username = document.getElementById("registerName").value;
    const email = document.getElementById("registerEmail").value;
    const password = document.getElementById("registerPassword").value;
    
    if (!username || !email || !password) {
      showAlert("الرجاء ملء جميع الحقول", "error");
      return;
    }
    
    if (password.length < 6) {
      showAlert("كلمة السر يجب أن تكون على الأقل 6 أحرف", "error");
      return;
    }
    
    // التحقق من وجود البريد الإلكتروني مسبقًا
    if (users.some(u => u.email === email)) {
      showAlert("هذا البريد الإلكتروني مسجل مسبقاً", "error");
    } else {
      const newUser = { 
        id: Date.now(), 
        username, 
        email, 
        password,
        joinDate: new Date().toLocaleDateString()
      };
      
      users.push(newUser);
      localStorage.setItem("users", JSON.stringify(users));
      
      currentUser = newUser;
      localStorage.setItem("currentUser", JSON.stringify(currentUser));
      
      showAlert("تم التسجيل بنجاح", "success");
      setTimeout(() => {
        // تصحيح اسم الملف هنا أيضًا
        window.location.href = "thesociety.html"; 
      }, 1500);
    }
  });
}

