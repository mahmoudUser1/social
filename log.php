<?php
$noNavbar = '';
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

if ($page == "login") {

    $pageTitle = "log in";

    session_start();

    if (isset($_SESSION["email"])) {
        header("Location: home.php");
        exit;
    }

    include "initials.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["mail"];
        $pass = $_POST["pass"];
        $password = sha1($pass);

        $stmt = $con->prepare("
        SELECT * 
        FROM users 
        WHERE email = ? AND password = ?
    ");

        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["email"] = $email;
            header("Location: home.php");
            exit;
        }
    }

    ?>

    <div class="vh-100 pro-log">

        <div class="rounded-3 card col-12 col-md-5 p-3 mx-auto box-page">
            <h2 class="text-center">تسجيل الدخول</h2>
            <form action="?page=login" method="post">
                <input name="mail" type="email" placeholder="البريد الإلكتروني" class="form-control my-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="كلمة السر" class="form-control my-3"
                    autocomplete="new-password" />
                <button type="submit" class="btn btn-primary w-100">دخول</button>

                <a href="index.php" class="d-block m-3 text-center">الرجوع للصفحة الرئيسية</a>
            </form>
        </div>

    </div>

    <?php

} elseif ($page == "newlogin") {

    $pageTitle = "new log in";

    session_start();

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

        $stmt = $con->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");

        $stmt->execute([$name, $email, $password]);

        $_SESSION["email"] = $email;

        header("Location: home.php");
        exit;
    }

    ?>

    <div class="vh-100 pro-log">

        <div class="rounded-3 card col-12 col-md-5 p-3 mx-auto box-page">
            <h2 class="text-center">تسجيل حساب جديد</h2>
            <form action="?page=newlogin" method="post">
                <input name="name" type="text" placeholder="الاسم" class="form-control my-3" autocomplete="off" />
                <input name="mail" type="email" placeholder="البريد الإلكتروني" class="form-control my-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="كلمة السر" class="form-control my-3"
                    autocomplete="new-password" />
                <button type="submit" class="btn btn-primary w-100">تسجيل</button>

                <a href="index.php" class="d-block m-3 text-center">الرجوع للصفحة الرئيسية</a>
            </form>
        </div>

    </div>

    <?php

} else {

    $pageTitle = "log in";

    session_start();

    if (isset($_SESSION["email"])) {
        header("Location: home.php");
        exit;
    }

    include "initials.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["mail"];
        $pass = $_POST["pass"];
        $password = sha1($pass);

        $stmt = $con->prepare("
        SELECT * 
        FROM users 
        WHERE email = ? AND password = ?
    ");

        $stmt->execute([$email, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION["email"] = $email;
            header("Location: home.php");
            exit;
        }
    }

    ?>

    <div class="vh-100 pro-log">

        <div class="rounded-3 card col-12 col-md-5 p-3 mx-auto box-page">
            <h2 class="text-center">تسجيل الدخول</h2>
            <form action="?page=login" method="post">
                <input name="mail" type="email" placeholder="البريد الإلكتروني" class="form-control my-3"
                    autocomplete="off" />
                <input name="pass" type="password" placeholder="كلمة السر" class="form-control my-3"
                    autocomplete="new-password" />
                <button type="submit" class="btn btn-primary w-100">دخول</button>

                <a href="index.php" class="d-block m-3 text-center">الرجوع للصفحة الرئيسية</a>
            </form>
        </div>

    </div>

    <?php

}

include $temp . "footer.php";