<?php

$stmt = $con->prepare("
        SELECT name
        FROM users 
        WHERE email = ? 
    ");

$stmt->execute([$_SESSION["email"]]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);


?>

<nav class="col-12 col-lg-3 col-md-4 bg-secondary position-fixed top-0">
    <button class="btn m-1 menuToggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
    <div class="p-2 user-info">
        <div class="
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
                user-avatar
            ">
            <?= $user['name'][0] ?>
        </div>
        <div class="text-light text-capitalize"><?= $user['name'] ?></div>
    </div>
    <div class="link-nav-1" id="link-navber">
        <a class="text-decoration-none text-info d-block p-3 text-center 
        <?php echo ($_SERVER['SCRIPT_NAME'] === '/MAHMOUD_MAHER/social-media/home.php') ? 'active-link' : ''; ?>" href="home.php"><i
                class="fa-solid fa-home"></i>
            <?= lang('P_HOME') ?></a>
        <a class="text-decoration-none text-info d-block p-3 text-center 
        <?php echo ($_SERVER['SCRIPT_NAME'] === '/MAHMOUD_MAHER/social-media/profile.php') ? 'active-link' : ''; ?>" href="profile.php"><i
                class="fa-solid fa-user"></i>
            <?= lang('P_PROFILE') ?></a>
        <a class="text-decoration-none text-info d-block p-3 text-center 
        <?php echo ($_SERVER['SCRIPT_NAME'] === '/MAHMOUD_MAHER/social-media/chat.php') ? 'active-link' : ''; ?>" href="chat.php"><i
                class="fa-solid fa-comment-dots"></i>
            <?= lang('P_MESSAGES') ?></a>
        <a class="text-decoration-none text-info d-block p-3 text-center <?php echo ($_SERVER['SCRIPT_NAME'] === '/MAHMOUD_MAHER/social-media/settings.php') ? 'active-link' : ''; ?>" href="settings.php"><i class="fa-solid fa-gear"></i>
            <?= lang('P_SETTINGS') ?></a>
        <!-- <a href="logout.php" class="btn btn-danger d-block m-2">تسجيل الخروج</a> -->
    </div>
</nav>