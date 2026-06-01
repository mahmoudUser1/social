<?php

$pageTitle = "settings";

session_start();

include "initials.php";

if (isset($_SESSION["email"])) {



    $page = isset($_GET['page']) ? $_GET['page'] : 'profile';

    $stmt = $con->prepare("
        SELECT *
        FROM users 
        WHERE email = ?
    ");
    $stmt->execute(array($_SESSION["email"]));
    $user = $stmt->fetch();

    ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-3 col-md-4">
        </div>
        <div class="col-12 col-lg-9 col-md-8">


            <div class="row m-0 g-2">
                <div class="modal fade" id="messageModal" style="z-index: 999999999;" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p id="modalMessage"></p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="control card p-3 sticky-top">
                        <h5 class="mb-3">الإعدادات</h5>
                        <div class="list-group">
                            <a href="?page=profile"
                                class="list-group-item list-group-item-action <?= $page == 'profile' ? 'active' : '' ?>">
                                <i class="fa-solid fa-user"></i> البيانات الشخصية
                            </a>
                            <a href="?page=password"
                                class="list-group-item list-group-item-action <?= $page == 'password' ? 'active' : '' ?>">
                                <i class="fa-solid fa-lock"></i> تغيير كلمة المرور
                            </a>
                            <a href="?page=chat"
                                class="list-group-item list-group-item-action <?= $page == 'chat' ? 'active' : '' ?>">
                                <i class="fa-solid fa-comments"></i> إعدادات الدردشة
                            </a>
                            <a href="?page=SE_APP"
                                class="list-group-item list-group-item-action <?= $page == 'SE_APP' ? 'active' : '' ?>">
                                <i class="fa-solid fa-sliders"></i> إعدادات التطبيق
                            </a>
                            <!-- <a href="?page=privacy"
                                class="list-group-item list-group-item-action <?= $page == 'privacy' ? 'active' : '' ?>">
                                <i class="fa-solid fa-shield"></i> الخصوصية
                            </a> -->
                            <!-- <a href="?page=notifications"
                                class="list-group-item list-group-item-action <?= $page == 'notifications' ? 'active' : '' ?>">
                                <i class="fa-solid fa-bell"></i> الإشعارات
                            </a> -->
                            <a href="?page=about"
                                class="list-group-item list-group-item-action <?= $page == 'about' ? 'active' : '' ?>">
                                <i class="fa-solid fa-info-circle"></i> حول التطبيق
                            </a>
                        </div>
                    </div>
                </div>

                <?php

                if ($page == 'profile') {

                    // معالجة تحديث البيانات الشخصية
                    if (isset($_POST['action']) && $_POST['action'] == 'update_profile') {
                        $name = $_POST['name'] ?? '';

                        if (empty($name)) {
                            $_SESSION['error'] = 'الرجاء إدخال الاسم';
                        } else {
                            $stmt = $con->prepare("UPDATE users SET name = ? WHERE email = ?");
                            $stmt->execute([$name, $_SESSION["email"]]);
                            $_SESSION['message'] = 'تم تحديث البيانات الشخصية بنجاح';

                            // تحديث بيانات المستخدم
                            $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
                            $stmt->execute([$_SESSION["email"]]);
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        }
                    }

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-user"></i> البيانات الشخصية</h4>

                            <div class="profile-avatar text-light text-capitalize mb-3 mx-auto bg-primary rounded-circle d-flex justify-content-center align-items-center fw-bolder"
                                style="width: 100px; height: 100px; font-size: 40px;">
                                <?= $user['name'][0] ?? 'U' ?>
                            </div>

                            <form method="POST" action="?page=profile">
                                <input type="hidden" name="action" value="update_profile">

                                <div class="mb-3">
                                    <label class="form-label">الاسم</label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?= htmlspecialchars($user['name']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                                        disabled>
                                    <small class="text-muted">لا يمكن تغيير البريد الإلكتروني</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تاريخ الانضمام</label>
                                    <input type="text" style="direction: ltr !important" class="form-control"
                                        value="<?= date('j F Y', strtotime($user['created-at'])) ?>" disabled>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save"></i> حفظ التغييرات
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php

                } elseif ($page == 'password') {
                    if (isset($_POST['action']) && $_POST['action'] == 'change_password') {
                        $current_password = $_POST['current_password'] ?? '';
                        $new_password = $_POST['new_password'] ?? '';
                        $confirm_password = $_POST['confirm_password'] ?? '';

                        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                            $_SESSION['error'] = 'الرجاء ملء جميع الحقول';
                        } elseif ($new_password !== $confirm_password) {
                            $_SESSION['error'] = 'كلمات المرور الجديدة غير متطابقة';
                        } elseif (strlen($new_password) < 6) {
                            $_SESSION['error'] = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
                        } elseif (sha1($current_password) !== $user['password']) {
                            $_SESSION['error'] = 'كلمة المرور الحالية غير صحيحة';
                        } else {
                            $hashed_password = sha1($new_password);
                            $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
                            $stmt->execute([$hashed_password, $_SESSION["email"]]);
                            $_SESSION['message'] = 'تم تغيير كلمة المرور بنجاح';
                        }
                    }
                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-lock"></i> تغيير كلمة المرور</h4>

                            <form method="POST" action="?page=password">
                                <input type="hidden" name="action" value="change_password">

                                <div class="mb-3">
                                    <label class="form-label">كلمة المرور الحالية</label>
                                    <input type="password" class="form-control" name="current_password">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control" name="new_password">
                                    <small class="text-muted">يجب أن تكون 6 أحرف على الأقل</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تأكيد كلمة المرور الجديدة</label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-key"></i> تحديث كلمة المرور
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php
                } elseif ($page == 'chat') {

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $chat_bg = $_POST['chat_bg'];
                        $_SESSION['chat_bg'] = $chat_bg;
                        $_SESSION['message'] = 'تم تحديث خلفية الدردشة بنجاح';
                    }
                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-comments"></i> إعدادات الدردشة</h4>
                            <form method="POST" class="m-0 p-0" action="?page=chat">
                                <h5>
                                    <i class="fa-solid fa-palette"></i> اختيار الثيم
                                </h5>
                                <div class="row g-3 m-0 p-0">

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="arabesque.png">
                                            <img src="layout/images/arabesque.png" class="img-fluid rounded border">
                                            <span>Arabesque</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="argyle.png">
                                            <img src="layout/images/argyle.png" class="img-fluid rounded border">
                                            <span>Argyle</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="black-linen.png">
                                            <img src="layout/images/black-linen.png" class="img-fluid rounded border">
                                            <span>Black Linen</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="black-thread-light.png">
                                            <img src="layout/images/black-thread-light.png" class="img-fluid rounded border">
                                            <span>Black Thread Light</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="black-thread.png">
                                            <img src="layout/images/black-thread.png" class="img-fluid rounded border">
                                            <span>Black Thread</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="bright-squares.png">
                                            <img src="layout/images/bright-squares.png" class="img-fluid rounded border">
                                            <span>Bright Squares</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="cubes.png">
                                            <img src="layout/images/cubes.png" class="img-fluid rounded border">
                                            <span>Cubes</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="gplay.png">
                                            <img src="layout/images/gplay.png" class="img-fluid rounded border">
                                            <span>GPlay</span>
                                        </label>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="bg-option">
                                            <input type="radio" name="chat_bg" value="light-gray.png">
                                            <img src="layout/images/light-gray.png" class="img-fluid rounded border">
                                            <span>Light gray</span>
                                        </label>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i
                                        class="fa-solid fa-arrow-rotate-right"></i> تحديث</button>
                            </form>
                        </div>
                    </div>
                    <?php
                } elseif ($page == 'SE_APP') {

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $app_lang = $_POST['app_lang'];
                        $_SESSION['app_lang'] = $app_lang;
                        $_SESSION['message'] = 'تم تحديث لغة التطبيق بنجاح';
                    }

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-sliders"></i> إعدادات التطبيق</h4>
                            <form method="POST" class="m-0 p-0" action="?page=SE_APP">
                                <h5>اللغة</h5>
                                <div class="s_lang_ar_en">
                                    <label class="d-block mb-2">
                                        <input type="radio" name="app_lang" value="ar.php" <?= (isset($_SESSION['app_lang']) && $_SESSION['app_lang'] == 'ar.php') ? 'checked' : '' ?>>
                                        العربية
                                    </label>
                                    <label class="d-block mb-2">
                                        <input type="radio" name="app_lang" value="en.php" <?= (isset($_SESSION['app_lang']) && $_SESSION['app_lang'] == 'en.php') ? 'checked' : '' ?>>
                                        English
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i
                                        class="fa-solid fa-arrow-rotate-right"></i> تحديث</button>
                            </form>
                        </div>
                    </div>
                    <?php
                } elseif ($page == 'about') {

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-info-circle"></i> حول التطبيق</h4>

                            <div class="mb-3">
                                <h5>المجتمع - الشبكة الاجتماعية</h5>
                                <p class="text-muted">نسخة 1.0</p>
                            </div>

                            <div class="mb-3">
                                <h6>المميزات:</h6>
                                <ul class="list-unstyled">
                                    <!-- <li><i class="fa-solid fa-check text-success"></i> إنشاء ومشاركة المنشورات</li> -->
                                    <!-- <li><i class="fa-solid fa-check text-success"></i> التفاعل مع المنشورات (إعجاب وتعليق)</li> -->
                                    <li><i class="fa-solid fa-check text-success"></i> نظام الأصدقاء</li>
                                    <li><i class="fa-solid fa-check text-success"></i> الرسائل الخاصة</li>
                                    <li><i class="fa-solid fa-check text-success"></i> الإشعارات</li>
                                    <!-- <li><i class="fa-solid fa-check text-success"></i> إعدادات الخصوصية</li> -->
                                </ul>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted">شكراً لاستخدامك تطبيقنا. نتمنى لك تجربة ممتعة!</p>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    // معالجة تحديث البيانات الشخصية
                    if (isset($_POST['action']) && $_POST['action'] == 'update_profile') {
                        $name = $_POST['name'] ?? '';

                        if (empty($name)) {
                            $_SESSION['error'] = 'الرجاء إدخال الاسم';
                        } else {
                            $stmt = $con->prepare("UPDATE users SET name = ? WHERE email = ?");
                            $stmt->execute([$name, $_SESSION["email"]]);
                            $_SESSION['message'] = 'تم تحديث البيانات الشخصية بنجاح';

                            // تحديث بيانات المستخدم
                            $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
                            $stmt->execute([$_SESSION["email"]]);
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        }
                    }

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-user"></i> البيانات الشخصية</h4>

                            <div class="profile-avatar text-light text-capitalize mb-3 mx-auto bg-primary rounded-circle d-flex justify-content-center align-items-center fw-bolder"
                                style="width: 100px; height: 100px; font-size: 40px;">
                                <?= $user['name'][0] ?? 'U' ?>
                            </div>

                            <form method="POST" action="?page=profile">
                                <input type="hidden" name="action" value="update_profile">

                                <div class="mb-3">
                                    <label class="form-label">الاسم</label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?= htmlspecialchars($user['name']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                                        disabled>
                                    <small class="text-muted">لا يمكن تغيير البريد الإلكتروني</small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">تاريخ الانضمام</label>
                                    <input type="text" style="direction: ltr !important" class="form-control"
                                        value="<?= date('j F Y', strtotime($user['created-at'])) ?>" disabled>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save"></i> حفظ التغييرات
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php

                }

                ?>

            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['message']) || isset($_SESSION['error'])): ?>

        <script>
            document.addEventListener("DOMContentLoaded", function () {

                let modalMessage = document.getElementById("modalMessage");

                <?php if (isset($_SESSION['message'])): ?>
                    modalMessage.textContent = "<?= addslashes($_SESSION['message']) ?>";
                    <?php unset($_SESSION['message']); endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    modalMessage.textContent = "<?= addslashes($_SESSION['error']) ?>";
                    <?php unset($_SESSION['error']); endif; ?>

                let modal = new bootstrap.Modal(
                    document.getElementById("messageModal")
                );

                modal.show();
            });
        </script>

    <?php endif; ?>

    <?php

} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
