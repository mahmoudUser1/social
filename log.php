<?php
// 1. يجب أن تكون session_start في أول السطر لضمان عمل الرسائل

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'include/library/PHPMailer/src/Exception.php';
require_once 'include/library/PHPMailer/src/PHPMailer.php';
require_once 'include/library/PHPMailer/src/SMTP.php';

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
            if ($user['is_verified'] == 0) {
                header("Location: verify.php");
            } else {
                header("Location: home.php");
            }
            exit;
        } else {
            // إضافة رسالة الخطأ هنا
            $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة!";
        }
    }

    ?>
    <div class="vh-100 pro-log">
        <div class="rounded-4 card col-12 col-md-5 p-3 mx-auto box-page shadow-sm">
            <h2 class="text-center"><?= lang('REGISTER') ?></h2>
            <form action="?page=login" method="post">
                <input name="mail" type="email" placeholder="<?= lang('INPUT_EMAIL') ?>" class="form-control my-3 rounded-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="<?= lang('INPUT_PASSWORD') ?>" class="form-control my-3 rounded-3"
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
            // توليد كود التحقق
            $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            
            $stmt = $con->prepare("INSERT INTO users (name, email, password, verify_code, is_verified) VALUES (?, ?, ?, ?, 0)");
            if ($stmt->execute([$name, $email, $password, $verificationCode])) {
                
                // إرسال الإيميل
                $to = $email;
                $subject = "كود التحقق الخاص بك - Social Network";
                $message = "أهلاً بك يا " . $name . "\r\n";
                $message .= "كود التحقق الخاص بك هو: " . $verificationCode . "\r\n";
                $headers = "From: tea0mah2009@gmail.com" . "\r\n" . "Content-Type: text/plain; charset=UTF-8";
                
                $mail = new PHPMailer(true);

                try {
                    // إعدادات الخادم
                    $mail->isSMTP();
                    $mail->Host = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'b6e9ffd04c6ef0';
                    $mail->Password = 'db9845e902c646';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 2525;

                    // المستلمون
                    $mail->setFrom('tea0mah2009@gmail.com', 'Social Network');
                    $mail->addAddress($to, $name);

                    // المحتوى
                    $mail->isHTML(false);
                    $mail->Subject = $subject;
                    $mail->Body    = $message;

                    $mail->send();
                } catch (Exception $e) {
                    $_SESSION['error'] = "فشل إرسال كود التحقق. Mailer Error: {$mail->ErrorInfo}";
                    // يمكنك تسجيل الخطأ هنا للمراجعة
                }

                $_SESSION["email"] = $email;
                header("Location: verify.php");
                exit;
            } else {
                $_SESSION['error'] = "حدث خطأ غير متوقع، يرجى المحاولة لاحقاً.";
            }
        }
    }

    ?>
    <div class="vh-100 pro-log">
        <div class="rounded-4 card col-12 col-md-5 p-3 mx-auto box-page shadow-sm">
            <h2 class="text-center"><?= lang('REGISTER_NEW') ?></h2>
            <form action="?page=newlogin" method="post">
                <input name="name" type="text" placeholder="<?= lang('INPUT_NAME') ?>" class="form-control my-3 rounded-3"
                    autocomplete="off" />
                <input name="mail" type="email" placeholder="<?= lang('INPUT_EMAIL') ?>" class="form-control my-3 rounded-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="<?= lang('INPUT_PASSWORD') ?>" class="form-control my-3 rounded-3"
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
            if ($user['is_verified'] == 0) {
                header("Location: verify.php");
            } else {
                header("Location: home.php");
            }
            exit;
        } else {
            $_SESSION['error'] = "البريد الإلكتروني أو كلمة المرور غير صحيحة!";
        }
    }

    ?>
    <div class="vh-100 pro-log">
        <div class="rounded-4 card col-12 col-md-5 p-3 mx-auto box-page shadow-sm">
            <h2 class="text-center"><?= lang('REGISTER') ?></h2>
            <form action="?page=login" method="post">
                <input name="mail" type="email" placeholder="<?= lang('INPUT_EMAIL') ?>" class="form-control my-3 rounded-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="<?= lang('INPUT_PASSWORD') ?>" class="form-control my-3 rounded-3"
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