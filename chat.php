<?php

$pageTitle = "الدردشة";

session_start();

include "initials.php";

if (isset($_SESSION["email"])) {
    // الحصول على معرف المستخدم الحالي
    $stmt = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute(array($_SESSION["email"]));
    $currentUser = $stmt->fetch();
    $currentUserId = $currentUser['id'];
    ?>
    
    <div class="chat-main-container">
        <div class="row m-0 h-100">
            <!-- مساحة جانبية لليسار (كما في التصميم الأصلي) -->
            <div class="d-none d-md-block col-lg-3 col-md-4"></div>
            
            <!-- منطقة المحتوى الرئيسية -->
            <div class="col-12 col-lg-9 col-md-8 p-0 p-md-3">
                <div class="chat-card shadow-lg">
                    <div class="row m-0 h-100 overflow-hidden">
                        
                        <!-- عمود قائمة المستخدمين -->
                        <div class="col-12 col-lg-4 p-0 border-end users-sidebar d-flex flex-column">
                            <div class="sidebar-header p-3 border-bottom bg-white">
                                <h5 class="m-0 fw-bold text-primary"><i class="fas fa-users me-2"></i>المستخدمون</h5>
                            </div>
                            <div class="users-list-scroll flex-grow-1 overflow-auto" id="usersList">
                                <div class="text-center p-5">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </div>
                            </div>
                        </div>

                        <!-- عمود منطقة الدردشة -->
                        <div class="col-12 col-lg-8 p-0 d-flex flex-column chat-content-area bg-white">
                            <!-- رأس الدردشة (ثابت) -->
                            <div class="chat-header-fixed p-3 border-bottom d-flex align-items-center bg-white" id="chatHeader">
                                <div class="text-muted"><i class="fas fa-comment-medical me-2"></i>ابدأ محادثة جديدة</div>
                            </div>

                            <!-- عرض الرسائل (سكرول مستقل) -->
                            <div class="messages-container flex-grow-1 p-3 overflow-auto d-flex flex-column" id="messagesDisplay">
                                <div class="welcome-screen h-100 d-flex align-items-center justify-content-center text-center flex-column opacity-50">
                                    <i class="fas fa-comments fa-5xl mb-4 text-primary"></i>
                                    <h3>مرحباً بك في الرسائل</h3>
                                    <p>اختر صديقاً من القائمة الجانبية لبدء المحادثة</p>
                                </div>
                            </div>

                            <!-- صندوق الكتابة (ثابت في الأسفل) -->
                            <div class="message-input-fixed p-3 border-top bg-white" id="inputArea" style="display: none;">
                                <form id="chatForm" class="d-flex align-items-end gap-2">
                                    <input type="hidden" id="receiverId" value="">
                                    <div class="flex-grow-1">
                                        <textarea class="form-control chat-textarea" id="messageText" placeholder="اكتب رسالتك هنا..." rows="1"></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary send-btn" id="sendMessageBtn">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* التنسيقات العامة */
        :root {
            --primary-color: #0084ff;
            --bg-light: #f0f2f5;
            --border-color: #dee2e6;
        }

        .chat-main-container {
            height: 100vh;
            padding-top: 60px; /* تعويض ارتفاع النافبار */
            background-color: #f0f2f5;
        }

        .chat-card {
            height: calc(100vh - 100px);
            background: white;
            border-radius: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* قائمة المستخدمين */
        .users-sidebar {
            background-color: #ffffff;
            height: 100%;
        }
        
        .users-list-scroll {
            height: 100%;
        }

        .user-item {
            padding: 15px;
            border-bottom: 1px solid #f8f9fa;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .user-item:hover { background-color: #f0f2f5; }
        .user-item.active { background-color: #e7f3ff; border-right: 4px solid var(--primary-color); }

        .avatar-circle {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #6c757d;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-left: 12px;
            flex-shrink: 0;
        }

        /* منطقة الدردشة */
        .chat-content-area {
            height: 100%;
            position: relative;
        }

        .chat-header-fixed {
            height: 70px;
            z-index: 10;
        }

        .messages-container {
            background-color: #f8f9fa;
            background-image: url('https://www.transparenttextures.com/patterns/cubes.png');
            scroll-behavior: smooth;
        }

        /* فقاعات الرسائل */
        .msg-bubble {
            max-width: 70%;
            margin-bottom: 10px;
            padding: 10px 15px;
            border-radius: 18px;
            font-size: 0.95rem;
            position: relative;
            word-wrap: break-word;
        }

        .msg-sent {
            align-self: flex-end;
            background-color: var(--primary-color);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .msg-received {
            align-self: flex-start;
            background-color: white;
            color: #1c1e21;
            border-bottom-left-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .msg-time {
            font-size: 0.7rem;
            margin-top: 4px;
            display: block;
            opacity: 0.7;
        }

        /* صندوق الكتابة */
        .message-input-fixed {
            background: white;
            min-height: 70px;
        }

        .chat-textarea {
            border-radius: 20px;
            border: 1px solid #ddd;
            background-color: #f0f2f5;
            padding: 10px 15px;
            resize: none;
            max-height: 120px;
            transition: all 0.2s;
        }

        .chat-textarea:focus {
            background-color: white;
            box-shadow: none;
            border-color: var(--primary-color);
        }

        .send-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            flex-shrink: 0;
        }

        /* للهواتف */
        @media (max-width: 991px) {
            .chat-main-container { padding-top: 50px; }
            .chat-card { height: calc(100vh - 60px); border-radius: 0; }
            .users-sidebar { height: 30% !important; border-bottom: 1px solid #ddd; }
            .chat-content-area { height: 70% !important; }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentUserId = <?php echo $currentUserId; ?>;
            const usersList = document.getElementById('usersList');
            const messagesDisplay = document.getElementById('messagesDisplay');
            const chatHeader = document.getElementById('chatHeader');
            const inputArea = document.getElementById('inputArea');
            const chatForm = document.getElementById('chatForm');
            const messageText = document.getElementById('messageText');
            const receiverIdInput = document.getElementById('receiverId');
            
            let activeReceiverId = null;
            let refreshInterval = null;

            // جلب المستخدمين
            function fetchUsers() {
                fetch('api/get_users.php')
                    .then(res => res.json())
                    .then(users => {
                        if (!Array.isArray(users)) return;
                        usersList.innerHTML = '';
                        users.forEach(user => {
                            if (user.id == currentUserId) return;
                            const div = document.createElement('div');
                            div.className = `user-item ${activeReceiverId == user.id ? 'active' : ''}`;
                            div.innerHTML = `
                                <div class="avatar-circle bg-primary">${user.name.charAt(0).toUpperCase()}</div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <div class="fw-bold text-dark text-truncate">${user.name}</div>
                                    <div class="small text-muted text-truncate">${user.email}</div>
                                </div>
                            `;
                            div.onclick = () => selectUser(user, div);
                            usersList.appendChild(div);
                        });
                    });
            }

            // اختيار مستخدم
            function selectUser(user, element) {
                activeReceiverId = user.id;
                receiverIdInput.value = user.id;
                
                document.querySelectorAll('.user-item').forEach(el => el.classList.remove('active'));
                element.classList.add('active');
                
                chatHeader.innerHTML = `
                    <div class="avatar-circle bg-primary" style="width:40px; height:40px; margin-left:10px;">${user.name.charAt(0).toUpperCase()}</div>
                    <div>
                        <h6 class="m-0 fw-bold">${user.name}</h6>
                        <small class="text-success"><i class="fas fa-circle me-1" style="font-size:0.6rem"></i>متصل الآن</small>
                    </div>
                `;
                
                inputArea.style.display = 'block';
                fetchMessages();
                
                if (refreshInterval) clearInterval(refreshInterval);
                refreshInterval = setInterval(fetchMessages, 3000);
                
                // تركيز على الكتابة
                messageText.focus();
            }

            // جلب الرسائل
            function fetchMessages() {
                if (!activeReceiverId) return;
                fetch(`api/get_messages.php?receiver_id=${activeReceiverId}`)
                    .then(res => res.json())
                    .then(messages => {
                        const isAtBottom = messagesDisplay.scrollHeight - messagesDisplay.scrollTop <= messagesDisplay.clientHeight + 100;
                        
                        if (messages.length === 0) {
                            messagesDisplay.innerHTML = '<div class="h-100 d-flex align-items-center justify-content-center text-muted">لا توجد رسائل بعد. ابدأ المحادثة!</div>';
                            return;
                        }
                        
                        messagesDisplay.innerHTML = '';
                        messages.forEach(msg => {
                            const isSent = msg['from-id'] == currentUserId;
                            const div = document.createElement('div');
                            div.className = `msg-bubble ${isSent ? 'msg-sent' : 'msg-received'}`;
                            div.innerHTML = `
                                <div>${msg.messages}</div>
                                <span class="msg-time">${msg['created-at']}</span>
                            `;
                            messagesDisplay.appendChild(div);
                        });
                        
                        if (isAtBottom) {
                            messagesDisplay.scrollTop = messagesDisplay.scrollHeight;
                        }
                    });
            }

            // إرسال رسالة
            chatForm.onsubmit = function(e) {
                e.preventDefault();
                const text = messageText.value.trim();
                if (!text || !activeReceiverId) return;
                
                const formData = new FormData();
                formData.append('receiver_id', activeReceiverId);
                formData.append('message', text);
                
                messageText.value = '';
                messageText.style.height = 'auto';
                
                fetch('api/send_message.php', { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(res => { if(res.success) fetchMessages(); });
            };

            // تحسينات التفاعل
            messageText.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            messageText.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    chatForm.dispatchEvent(new Event('submit'));
                }
            });

            fetchUsers();
        });
    </script>

    <?php
} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
?>
