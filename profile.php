<?php

$pageTitle = "profile";

session_start();

include "initials.php";

if (isset($_SESSION["email"])) {

    $stmt = $con->prepare("
        SELECT *
        FROM users 
        WHERE email = ? 
    ");

    $stmt->execute([$_SESSION["email"]]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    $date = date("j - F - Y", strtotime($user['created-at']));

        ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-4 col-md-5">
        </div>
        <div class="col-12 col-lg-7 col-md-6">

            <div class="p-3 card">
                <div class="profile-info">
                    <div class="
                        profile-avatar
                        text-light 
                        text-capitalize 
                        mb-1
                        mx-auto 
                        bg-primary 
                        rounded-circle
                        d-flex 
                        justify-content-center
                        align-items-center 
                        fw-bolder 
                    "><?= $user['name'][0] ?></div>
                    <div class="text-dark fs-3 text-capitalize"><?= $user['name'] ?></div>
                </div>
                <div class="p-2 info">
                    <p><span>البريد الالكتروني : </span> <?= $user['email'] ?></p>
                    <p><span>تاريخ الانضمام : </span> <?= $date ?></p>
                </div>
            </div>

        </div>
    </div>
    <?php

} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
