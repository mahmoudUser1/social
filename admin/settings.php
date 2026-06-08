<?php

$pageTitle = "settings";

session_start();

include "initials.php";

if (isset($_SESSION["email_admin"])) {



    $page = isset($_GET['page']) ? $_GET['page'] : 'profile';

    $stmt = $con->prepare("
        SELECT *
        FROM users 
        WHERE email = ?
    ");
    $stmt->execute(array($_SESSION["email_admin"]));
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
                        <h5 class="mb-3"><?= lang('P_SETTINGS') ?></h5>
                        <div class="list-group">
                            <a href="?page=profile"
                                class="list-group-item list-group-item-action <?= $page == 'profile' ? 'active' : '' ?>">
                                <i class="fa-solid fa-user"></i> <?= lang('SE_PERSONAL_DATA') ?>
                            </a>
                            <a href="?page=password"
                                class="list-group-item list-group-item-action <?= $page == 'password' ? 'active' : '' ?>">
                                <i class="fa-solid fa-lock"></i> <?= lang('SE_CHANGE_PASSWORD') ?>
                            </a>
                            <!-- <a href="?page=chat"
                                class="list-group-item list-group-item-action <?= $page == 'chat' ? 'active' : '' ?>">
                                <i class="fa-solid fa-comments"></i> <?= lang('SE_CHAT_SETTINGS') ?>
                            </a> -->
                            <a href="?page=SE_APP"
                                class="list-group-item list-group-item-action <?= $page == 'SE_APP' ? 'active' : '' ?>">
                                <i class="fa-solid fa-sliders"></i> <?= lang('SE_APP_SETTINGS') ?>
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
                                <i class="fa-solid fa-info-circle"></i> <?= lang('SE_ABOUT') ?>
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
                            $_SESSION['error'] = lang('SE_ENTER_NAME');
                        } else {
                            $stmt = $con->prepare("UPDATE users SET name = ? WHERE email = ?");
                            $stmt->execute([$name, $_SESSION["email_admin"]]);
                            $_SESSION['message'] = lang('SE_PROFILE_UPDATED');
                            // تحديث بيانات المستخدم
                            $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
                            $stmt->execute([$_SESSION["email_admin"]]);
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        }
                    }

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-user"></i> <?= lang('SE_PERSONAL_DATA') ?></h4>

                            <div class="profile-avatar text-light text-capitalize mb-3 mx-auto bg-primary rounded-circle d-flex justify-content-center align-items-center fw-bolder"
                                style="width: 100px; height: 100px; font-size: 40px;">
                                <?= $user['name'][0] ?? 'U' ?>
                            </div>

                            <form method="POST" action="?page=profile">
                                <input type="hidden" name="action" value="update_profile">

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('INPUT_NAME') ?></label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?= htmlspecialchars($user['name']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('INPUT_EMAIL') ?></label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                                        disabled>
                                    <small class="text-muted"><?= lang('SE_NO_CHANGE_EMAIL') ?></small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('PR_JOIN_DATE') ?></label>
                                    <input type="text" style="direction: ltr !important" class="form-control"
                                        value="<?= date('j F Y', strtotime($user['created-at'])) ?>" disabled>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save"></i> <?= lang('SE_SAVE_CHANGES') ?>
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
                            $_SESSION['error'] = lang('SE_FILL_ALL_FIELDS');
                        } elseif ($new_password !== $confirm_password) {
                            $_SESSION['error'] = lang('SE_PASSWORDS_DO_NOT_MATCH');
                        } elseif (strlen($new_password) < 6) {
                            $_SESSION['error'] = lang('SE_PASSWORD_LENGTH');
                        } elseif (sha1($current_password) !== $user['password']) {
                            $_SESSION['error'] = lang('SE_CURRENT_PASSWORD_INCORRECT');
                        } else {
                            $hashed_password = sha1($new_password);
                            $stmt = $con->prepare("UPDATE users SET password = ? WHERE email = ?");
                            $stmt->execute([$hashed_password, $_SESSION["email_admin"]]);
                            $_SESSION['message'] = lang('SE_PASSWORD_CHANGED_SUCCESSFULLY');
                        }
                    }
                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-lock"></i> <?= lang('SE_CHANGE_PASSWORD') ?></h4>

                            <form method="POST" action="?page=password">
                                <input type="hidden" name="action" value="change_password">

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('SE_CURRENT_PASSWORD') ?></label>
                                    <input type="password" class="form-control" name="current_password">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('SE_NEW_PASSWORD') ?></label>
                                    <input type="password" class="form-control" name="new_password">
                                    <small class="text-muted"><?= lang('SE_PASSWORD_LENGTH') ?></small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('SE_CONFIRM_PASSWORD') ?></label>
                                    <input type="password" class="form-control" name="confirm_password">
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-key"></i> <?= lang('SE_UPDATE_PASSWORD') ?>
                                </button>
                            </form>
                        </div>
                    </div>
                    <?php
                } elseif ($page == 'SE_APP') {

                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                        $app_lang_admin = $_POST['app_lang_admin'];
                        $_SESSION['app_lang_admin'] = $app_lang_admin;
                        $_SESSION['message'] = lang('SE_APP_LANGUAGE_UPDATED');
                    }

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-sliders"></i> <?= lang('SE_APP_SETTINGS') ?></h4>
                            <form method="POST" class="m-0 p-0" action="?page=SE_APP">
                                <h5> <?= lang('SE_LANGUAGE') ?></h5>
                                <div class="s_lang_ar_en">
                                    <label class="d-block mb-2">
                                        <input type="radio" name="app_lang_admin" value="ar.php" <?= (isset($_SESSION['app_lang_admin']) && $_SESSION['app_lang_admin'] == 'ar.php') ? 'checked' : '' ?>>
                                        العربية
                                    </label>
                                    <label class="d-block mb-2">
                                        <input type="radio" name="app_lang_admin" value="en.php" <?= (isset($_SESSION['app_lang_admin']) && $_SESSION['app_lang_admin'] == 'en.php') ? 'checked' : '' ?>>
                                        English
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3"><i
                                        class="fa-solid fa-arrow-rotate-right"></i> <?= lang('SE_UPDATE') ?></button>
                            </form>
                        </div>
                    </div>
                    <?php
                } elseif ($page == 'about') {

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-info-circle"></i> <?= lang('SE_ABOUT') ?></h4>

                            <div class="mb-3">
                                <h5><?= lang('SE_APP_NAME') ?></h5>
                                <p class="text-muted"><?= lang('SE_VERSION') ?> <?= $version ?></p>
                            </div>

                            <div class="mb-3">
                                <h6><?= lang('SE_FEATURES') ?>:</h6>
                                <ul class="list-unstyled">
                                    <!-- <li><i class="fa-solid fa-check text-success"></i> إنشاء ومشاركة المنشورات</li> -->
                                    <!-- <li><i class="fa-solid fa-check text-success"></i> التفاعل مع المنشورات (إعجاب وتعليق)</li> -->
                                    <li><i class="fa-solid fa-check text-success"></i> <?= lang('SE_FRIENDS_SYSTEM') ?></li>
                                    <li><i class="fa-solid fa-check text-success"></i> <?= lang('SE_PRIVATE_MESSAGES') ?></li>
                                    <li><i class="fa-solid fa-check text-success"></i> <?= lang('SE_NOTIFICATIONS') ?></li>
                                    <!-- <li><i class="fa-solid fa-check text-success"></i> إعدادات الخصوصية</li> -->
                                </ul>
                            </div>

                            <div class="mb-3">
                                <p class="text-muted"><?= lang('SE_THANK_YOU') ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                } else {
                    // معالجة تحديث البيانات الشخصية
                    if (isset($_POST['action']) && $_POST['action'] == 'update_profile') {
                        $name = $_POST['name'] ?? '';

                        if (empty($name)) {
                            $_SESSION['error'] = lang('SE_ENTER_NAME');
                        } else {
                            $stmt = $con->prepare("UPDATE users SET name = ? WHERE email = ?");
                            $stmt->execute([$name, $_SESSION["email"]]);
                            $_SESSION['message'] = lang('SE_PROFILE_UPDATED');
                            // تحديث بيانات المستخدم
                            $stmt = $con->prepare("SELECT * FROM users WHERE email = ?");
                            $stmt->execute([$_SESSION["email"]]);
                            $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        }
                    }

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card p-4">
                            <h4 class="mb-4"><i class="fa-solid fa-user"></i> <?= lang('SE_PERSONAL_DATA') ?></h4>

                            <div class="profile-avatar text-light text-capitalize mb-3 mx-auto bg-primary rounded-circle d-flex justify-content-center align-items-center fw-bolder"
                                style="width: 100px; height: 100px; font-size: 40px;">
                                <?= $user['name'][0] ?? 'U' ?>
                            </div>

                            <form method="POST" action="?page=profile">
                                <input type="hidden" name="action" value="update_profile">

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('INPUT_NAME') ?></label>
                                    <input type="text" class="form-control" name="name"
                                        value="<?= htmlspecialchars($user['name']) ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('INPUT_EMAIL') ?></label>
                                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>"
                                        disabled>
                                    <small class="text-muted"><?= lang('SE_NO_CHANGE_EMAIL') ?></small>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><?= lang('PR_JOIN_DATE') ?></label>
                                    <input type="text" style="direction: ltr !important" class="form-control"
                                        value="<?= date('j F Y', strtotime($user['created-at'])) ?>" disabled>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa-solid fa-save"></i> <?= lang('SE_SAVE_CHANGES') ?>
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

    <?php

} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
