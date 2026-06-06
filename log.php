<?php
// 1. يجب أن تكون session_start في أول السطر لضمان عمل الرسائل
session_start();

$noNavbar = '';
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// 2. هيكل الـ Modal (بدون السكريبت لأنك أضفته في الفوتر)
?>
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

<?php

// --- صفحة تسجيل الدخول (Login) ---
if ($page == "login") {

    $pageTitle = "log in";

    if (isset($_SESSION["email"])) {
        header("Location: home.php");
        exit;
    }

    include "initials.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["mail"];
        $pass = $_POST["pass"];
        $password = sha1($pass);

        $stmt = $con->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["email"] = $email;
            header("Location: home.php");
            exit;
        } else {
            // إضافة رسالة الخطأ هنا
            $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة!";
        }
    }

    ?>
    <div class="vh-100 pro-log">
        <div class="rounded-3 card col-12 col-md-5 p-3 mx-auto box-page">
            <h2 class="text-center"><?= lang('REGISTER') ?></h2>
            <form action="?page=login" method="post">
                <input name="mail" type="email" placeholder="<?= lang('INPUT_EMAIL') ?>" class="form-control my-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="<?= lang('INPUT_PASSWORD') ?>" class="form-control my-3"
                    autocomplete="new-password" />
                <button type="submit" class="btn btn-primary w-100"><?= lang('R_LOGIN') ?></button>
                <a href="index.php" class="d-block m-3 text-center"><?= lang('R_HOME') ?></a>
            </form>
        </div>
    </div>
    <?php

    // --- صفحة تسجيل حساب جديد (New Login) ---
} elseif ($page == "newlogin") {

    $pageTitle = "new log in";

    if (isset($_SESSION["email"])) {
        header("Location: home.php");
        exit;
    }

    include "initials.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = trim($_POST["name"]);
        $email = $_POST["mail"];
        $pass = $_POST["pass"];
        $password = sha1($pass);

        // التحقق إذا كان البريد موجود مسبقاً
        $check = $con->prepare("SELECT email FROM users WHERE email = ?");
        $check->execute([$email]);

        if ($check->rowCount() > 0) {
            $_SESSION['error'] = "هذا البريد الإلكتروني مسجل بالفعل، جرب تسجيل الدخول.";
        } else {
            $stmt = $con->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$name, $email, $password])) {
                $_SESSION["email"] = $email;
                $_SESSION['message'] = "تم إنشاء الحساب بنجاح!";
                header("Location: home.php");
                exit;
            } else {
                $_SESSION['error'] = "حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.";
            }
        }
    }

    ?>
    <div class="vh-100 pro-log">
        <div class="rounded-3 card col-12 col-md-5 p-3 mx-auto box-page">
            <h2 class="text-center"><?= lang('REGISTER_NEW') ?></h2>
            <form action="?page=newlogin" method="post">
                <input name="name" type="text" placeholder="<?= lang('INPUT_NAME') ?>" class="form-control my-3"
                    autocomplete="off" />
                <input name="mail" type="email" placeholder="<?= lang('INPUT_EMAIL') ?>" class="form-control my-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="<?= lang('INPUT_PASSWORD') ?>" class="form-control my-3"
                    autocomplete="new-password" />
                <button type="submit" class="btn btn-primary w-100"><?= lang('R_N_LOGIN') ?></button>
                <a href="index.php" class="d-block m-3 text-center"><?= lang('R_HOME') ?></a>
            </form>
        </div>
    </div>
    <?php

    // --- الحالة الافتراضية (Else) ---
} else {

    $pageTitle = "log in";

    if (isset($_SESSION["email"])) {
        header("Location: home.php");
        exit;
    }

    include "initials.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["mail"];
        $pass = $_POST["pass"];
        $password = sha1($pass);

        $stmt = $con->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["email"] = $email;
            header("Location: home.php");
            exit;
        } else {
            $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة!";
        }
    }

    ?>
    <div class="vh-100 pro-log">
        <div class="rounded-3 card col-12 col-md-5 p-3 mx-auto box-page">
            <h2 class="text-center"><?= lang('REGISTER') ?></h2>
            <form action="?page=login" method="post">
                <input name="mail" type="email" placeholder="<?= lang('INPUT_EMAIL') ?>" class="form-control my-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="<?= lang('INPUT_PASSWORD') ?>" class="form-control my-3"
                    autocomplete="new-password" />
                <button type="submit" class="btn btn-primary w-100"><?= lang('R_LOGIN') ?></button>
                <a href="index.php" class="d-block m-3 text-center"><?= lang('R_HOME') ?></a>
            </form>
        </div>
    </div>
    <?php
}

include $temp . "footer.php";
?>