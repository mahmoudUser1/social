<?php
session_start();

$noNavbar = '';

$pageTitle = "log in";
if (isset($_SESSION["email_admin"])) {
    header("Location: dashboard.php");
    exit;
}

include "initials.php";

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["mail"];
    $pass = $_POST["pass"];
    $password = sha1($pass);

    $stmt = $con->prepare("SELECT * FROM users WHERE email = ? AND password = ? AND role = 1");
    $stmt->execute([$email, $password]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION["email_admin"] = $email;
        header("Location: dashboard.php");
        exit;
    } else {
        // إضافة رسالة الخطأ هنا
        $_SESSION['error'] = lang('INVALID_CREDENTIALS');
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
        </form>
    </div>
</div>

<?php 
include $temp . "footer.php";
