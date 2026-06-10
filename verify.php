<?php
session_start();
$noNavbar = '';
$pageTitle = 'Verify Account';
include "initials.php";

if (!isset($_SESSION["email"])) {
    header("Location: log.php");
    exit;
}

// التأكد من أن المستخدم لم يفعل حسابه بالفعل
$stmtCheck = $con->prepare("SELECT is_verified FROM users WHERE email = ?");
$stmtCheck->execute([$_SESSION["email"]]);
$userStatus = $stmtCheck->fetch();

if ($userStatus && $userStatus['is_verified'] == 1) {
    header("Location: home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = trim($_POST['v_code']);
    $email = $_SESSION["email"];

    $stmt = $con->prepare("SELECT id FROM users WHERE email = ? AND verify_code = ?");
    $stmt->execute([$email, $code]);

    if ($stmt->rowCount() > 0) {
        // تحديث حالة الحساب لتفعيل
        $update = $con->prepare("UPDATE users SET is_verified = 1, verify_code = NULL WHERE email = ?");
        if ($update->execute([$email])) {
            header("Location: home.php");
            exit;
        } else {
            $error = "حدث خطأ أثناء تفعيل الحساب.";
        }
    } else {
        $error = "كود التحقق غير صحيح، يرجى المحاولة مرة أخرى.";
    }
}
?>

<div class="vh-100 pro-log">
    <div class="rounded-3 card col-12 col-md-5 p-4 mx-auto box-page text-center shadow">
        <h2 class="mb-4"><?= lang('REGISTER') ?> - تفعيل الحساب</h2>
        <p class="text-muted">أدخل الكود المكون من 6 أرقام المرسل إلى بريدك الإلكتروني</p>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <input name="v_code" type="text" maxlength="6" placeholder="000000"
                    class="form-control text-center fs-1 fw-bold tracking-widest" style="letter-spacing: 10px;" required
                    autocomplete="off" />
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fs-5">تأكيد الكود</button>
            <div class="mt-3">
                <a href="logout.php" class="text-decoration-none text-muted small">تسجيل الخروج</a>
            </div>
        </form>
    </div>
</div>

<style>
    .tracking-widest {
        letter-spacing: 0.5em;
    }

    input::placeholder {
        letter-spacing: normal;
    }
</style>

<?php include $temp . "footer.php"; ?>