// ملف messages.js لإدارة منطق الرسائل باستخدام Firebase

document.addEventListener("DOMContentLoaded", () => {
    // التأكد من أن Firebase تم تهيئته (يجب أن يتم في HTML قبل هذا السكربت)
    if (typeof firebase === 'undefined' || !firebase.database) {
        console.error("Firebase SDK لم يتم تحميله أو تهيئته بشكل صحيح!");
        alert("خطأ في الاتصال بخدمة الرسائل. يرجى التأكد من إعدادات Firebase.");
        return;
    }
    const db = firebase.database(); // الحصول على مرجع قاعدة البيانات

    // التأكد من أن المستخدم مسجل دخوله
    if (!currentUser) {
        console.log("المستخدم غير مسجل الدخول، يجب أن يتم التعامل مع هذا في app.js");
        // لا حاجة لإعادة التوجيه هنا، يفترض أن app.js يعالجها
        // return;
    }

    const usersListDiv = document.getElementById("usersList");
    const chatHeader = document.getElementById("chatHeader");
    const messagesDisplay = document.getElementById("messagesDisplay");
    const messageInputArea = document.getElementById("messageInputArea");
    const messageInput = document.getElementById("messageInput");
    const sendMessageBtn = document.getElementById("sendMessageBtn");
    const menuToggle = document.getElementById("menuToggle");
    const navLinks = document.getElementById("navLinks");
    const logoutBtnMobile = document.getElementById("logoutBtnMobile");
    const logoutBtnSidebar = document.getElementById("logoutBtnSidebar");

    let allUsers = JSON.parse(localStorage.getItem("users")) || [];
    let selectedUserId = null;
    let messagesListener = null; // لتخزين المستمع النشط وإزالته عند تغيير المستخدم

    // --- 1. تحميل وعرض قائمة المستخدمين --- 
    function loadUsers() {
        usersListDiv.innerHTML = ""; // مسح القائمة الحالية
        const otherUsers = allUsers.filter(user => user.id !== currentUser.id);

        if (otherUsers.length === 0) {
            usersListDiv.innerHTML = "<p>لا يوجد مستخدمون آخرون.</p>";
            return;
        }

        otherUsers.forEach(user => {
            const userElement = document.createElement("div");
            userElement.className = "user-item";
            userElement.dataset.userId = user.id;
            userElement.innerHTML = `
                <div class="avatar-small">${user.username.charAt(0).toUpperCase()}</div>
                <span>${user.username}</span>
            `;
            userElement.addEventListener("click", () => selectUser(user.id, user.username));
            usersListDiv.appendChild(userElement);
        });
    }

    // --- 2. اختيار مستخدم وبدء المحادثة ---
    function selectUser(userId, username) {
        selectedUserId = userId;
        chatHeader.textContent = `المحادثة مع ${username}`;
        messageInputArea.style.display = "flex"; // إظهار منطقة الإدخال
        messagesDisplay.innerHTML = ""; // مسح الرسائل القديمة

        // إزالة المستمع القديم إذا كان موجودًا
        if (messagesListener) {
            messagesListener.off();
        }

        // تمييز المستخدم المحدد في القائمة
        document.querySelectorAll(".user-item").forEach(item => {
            item.classList.remove("active");
            if (item.dataset.userId == userId) {
                item.classList.add("active");
            }
        });

        listenForMessages(userId);
    }

    // --- 3. الاستماع للرسائل الجديدة من Firebase --- 
    function listenForMessages(otherUserId) {
        const conversationKey = getConversationKey(currentUser.id, otherUserId);
        const messagesRef = db.ref(`messages/${conversationKey}`);

        messagesDisplay.innerHTML = '<p style="text-align: center; color: #888;">جاري تحميل الرسائل...</p>';

        // إزالة المستمع القديم قبل إضافة الجديد (احتياطي)
        if (messagesListener) {
            messagesListener.off();
        }

        // الاستماع لأي رسالة جديدة تضاف (child_added)
        messagesListener = messagesRef.orderByChild('timestamp'); // ترتيب حسب الوقت
        messagesListener.on('child_added', (snapshot) => {
            // إزالة رسالة "جاري التحميل" عند وصول أول رسالة
            if (messagesDisplay.querySelector('p')) {
                 messagesDisplay.innerHTML = '';
            }
            const msg = snapshot.val();
            displayMessage(msg);
            scrollToBottom(); // التمرير لأسفل مع كل رسالة جديدة
        });

        // يمكنك إضافة معالجة لحالة عدم وجود رسائل بعد فترة
        messagesRef.once('value', snapshot => {
            if (!snapshot.exists()) {
                messagesDisplay.innerHTML = '<p style="text-align: center; color: #888;">لا توجد رسائل بعد. ابدأ المحادثة!</p>';
            }
        });
    }

    // --- 4. عرض رسالة واحدة --- 
    function displayMessage(msg) {
        const messageElement = document.createElement("div");
        messageElement.classList.add("message");
        // التحقق من وجود currentUser قبل الوصول لـ id
        messageElement.classList.add(currentUser && msg.senderId === currentUser.id ? "sent" : "received");

        const time = msg.timestamp ? new Date(msg.timestamp).toLocaleTimeString("ar-EG", { hour: "2-digit", minute: "2-digit" }) : '';

        messageElement.innerHTML = `
            <div>${escapeHTML(msg.text)}</div>
            <span class="timestamp">${time}</span>
        `;
        messagesDisplay.appendChild(messageElement);
    }

    // --- 5. إرسال رسالة جديدة إلى Firebase --- 
    function sendMessage() {
        const text = messageInput.value.trim();
        if (text && selectedUserId && currentUser) { // التأكد من وجود المستخدم الحالي
            const conversationKey = getConversationKey(currentUser.id, selectedUserId);
            const messagesRef = db.ref(`messages/${conversationKey}`);
            
            const newMessage = {
                senderId: currentUser.id,
                receiverId: selectedUserId,
                text: text,
                timestamp: firebase.database.ServerValue.TIMESTAMP // استخدام وقت الخادم
            };

            // إرسال الرسالة إلى Firebase
            messagesRef.push(newMessage)
                .then(() => {
                    messageInput.value = ""; // مسح حقل الإدخال
                    messageInput.style.height = 'auto'; // إعادة ضبط ارتفاع حقل الإدخال
                    scrollToBottom(); // التمرير لأسفل بعد الإرسال
                })
                .catch((error) => {
                    console.error("خطأ في إرسال الرسالة: ", error);
                    showAlert("حدث خطأ أثناء إرسال الرسالة.", "error");
                });
        }
    }

    // --- 6. الحصول على مفتاح المحادثة (لضمان الترتيب بغض النظر عن المرسل/المستقبل) ---
    function getConversationKey(userId1, userId2) {
        // التأكد من أن المعرفات هي سلاسل نصية أو أرقام قابلة للفرز
        const id1 = String(userId1);
        const id2 = String(userId2);
        return [id1, id2].sort().join("_");
    }

    // --- 7. التمرير لأسفل منطقة الرسائل ---
    function scrollToBottom() {
        // تأخير بسيط للسماح للمتصفح برسم العنصر الجديد قبل التمرير
        setTimeout(() => {
             messagesDisplay.scrollTop = messagesDisplay.scrollHeight;
        }, 50);
    }

    // --- 8. التعامل مع إدخال النص وتغيير حجم textarea ---
    messageInput.addEventListener("input", () => {
        messageInput.style.height = "auto"; // إعادة الضبط للسماح بالتقلص
        messageInput.style.height = `${messageInput.scrollHeight}px`; // التمدد ليناسب المحتوى
    });

    // --- 9. ربط الأحداث --- 
    sendMessageBtn.addEventListener("click", sendMessage);
    messageInput.addEventListener("keypress", (e) => {
        // إرسال عند الضغط على Enter (بدون Shift)
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault(); // منع إدخال سطر جديد
            sendMessage();
        }
    });

    // --- 10. التعامل مع قائمة الموبايل --- 
    if (menuToggle && navLinks) {
        menuToggle.addEventListener("click", () => {
            navLinks.classList.toggle("open");
        });
    }

    // --- 11. التعامل مع تسجيل الخروج (قد يكون مكررًا مع app.js) ---
    function handleLogout() {
        localStorage.removeItem("currentUser");
        // لا حاجة لمسح رسائل localStorage الآن
        window.location.href = "index1.html"; // أو index.html حسب التدفق
    }

    if (logoutBtnMobile) {
        logoutBtnMobile.addEventListener("click", handleLogout);
    }
    // زر الشريط الجانبي يتم التعامل معه في app.js

    // --- 12. دالة مساعدة لتجنب XSS --- 
    function escapeHTML(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // --- 13. التحميل الأولي --- 
    if (currentUser) { // تأكد مرة أخرى قبل التحميل
        loadUsers();
    } else {
        // ربما عرض رسالة خطأ أو إعادة توجيه مؤكدة
        console.error("لا يمكن تحميل الرسائل بدون مستخدم مسجل الدخول.");
        // قد تحتاج لإخفاء واجهة الرسائل بالكامل هنا
        document.querySelector('.messages-section').style.display = 'none';
        document.querySelector('.main-content h2').textContent = 'الرجاء تسجيل الدخول أولاً';
    }

});

