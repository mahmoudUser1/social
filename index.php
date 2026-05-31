<?php
$noNavbar = '';
$pageTitle = "page hello";

session_start();

if (isset($_SESSION["email"])) {
    header("Location: home.php");
    exit;
}

include "initials.php";
?>

<div class="vh-100 pro-log">

    <div class="text-center rounded-3 card col-12 col-md-5 p-3 mx-auto box-page">
        <h2 class="mb-4">مرحباً بك في المجتمع</h2>
        <p>أول مرة تستخدم البرنامج؟</p>
        <a href="log.php?page=newlogin" class="btn btn-primary mb-3">تسجيل حساب جديد</a>
        <p>مسجل من قبل؟</p>
        <a href="log.php?page=login" class="btn btn-primary">تسجيل الدخول</a>
    </div>
</div>

<?php include $temp . "footer.php"; ?>