// بيانات التطبيق
let users = JSON.parse(localStorage.getItem('users')) || [];
let currentUser = JSON.parse(localStorage.getItem('currentUser')) || null;
let posts = JSON.parse(localStorage.getItem('posts')) || [];

// تحميل التطبيق الرئيسي
function loadMainApp() {
  if (!currentUser) {
    window.location.href = 'index.html';
    return;
  }
  
  // عرض معلومات المستخدم
  if (document.getElementById('userName')) {
    document.getElementById('userName').textContent = currentUser.username;
    document.getElementById('userAvatar').textContent = currentUser.username.charAt(0).toUpperCase();
  }
  
  // تسجيل الخروج
  if (document.getElementById('logoutBtn')) {
    document.getElementById('logoutBtn').addEventListener('click', function() {
      localStorage.removeItem('currentUser');
      window.location.href = 'index.html';
    });
  }
  
  // عرض المنشورات
  if (document.getElementById('postsContainer')) {
    renderPosts();
    
    // إضافة منشور جديد
    document.getElementById('newPostForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const content = this.querySelector('textarea').value.trim();
      
      if (content) {
        const newPost = {
          id: Date.now(),
          userId: currentUser.id,
          content,
          timestamp: Date.now()
        };
        
        posts.unshift(newPost);
        localStorage.setItem('posts', JSON.stringify(posts));
        this.querySelector('textarea').value = '';
        renderPosts();
        showAlert('تم نشر منشورك بنجاح', 'success');
      }
    });
  }
  
  // عرض معلومات الملف الشخصي
  if (document.getElementById('profileInfo')) {
    document.getElementById('profileInfo').innerHTML = `
      <div class="profile-header">
        <div class="post-avatar">${currentUser.username.charAt(0).toUpperCase()}</div>
        <h3>${currentUser.username}</h3>
      </div>
      <div class="profile-details">
        <p><strong>البريد الإلكتروني:</strong> ${currentUser.email}</p>
        <p><strong>تاريخ الانضمام:</strong> ${currentUser.joinDate || 'غير معروف'}</p>
      </div>
    `;
  }
}

// عرض المنشورات
function renderPosts() {
  const postsContainer = document.getElementById('postsContainer');
  postsContainer.innerHTML = '';
  
  if (posts.length === 0) {
    postsContainer.innerHTML = '<p style="text-align: center; padding: 20px;">لا توجد منشورات بعد. كن أول من ينشر!</p>';
    return;
  }
  
  posts.forEach(post => {
    const user = users.find(u => u.id === post.userId) || { username: 'مستخدم مجهول' };
    const postDate = new Date(post.timestamp).toLocaleString();
    
    const postElement = document.createElement('div');
    postElement.className = 'post';
    postElement.innerHTML = `
      <div class="post-header">
        <div class="post-avatar small">${user.username.charAt(0).toUpperCase()}</div>
        <div>
          <div class="post-user">${user.username}</div>
          <div class="post-time">${postDate}</div>
        </div>
      </div>
      <div class="post-content">${post.content}</div>
    `;
    
    postsContainer.appendChild(postElement);
  });
}

// تحميل التطبيق عند بدء التشغيل
window.addEventListener('DOMContentLoaded', loadMainApp);

