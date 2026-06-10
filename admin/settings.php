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
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 rounded-4 shadow-lg">

                            <div class="modal-header bg-light border-bottom">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body p-4">
                                <p id="modalMessage" class="mb-0 fs-5"></p>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="control card border-0 shadow-sm rounded-4 p-3 sticky-top">
                        <h5 class="mb-3 fw-bold d-flex align-items-center gap-2">
                            <i class="fa-solid fa-gears text-primary"></i>
                            <?= lang('P_SETTINGS') ?>
                        </h5>
                        <div class="list-group list-group-flush">
                            <a href="?page=profile"
                                class="list-group-item list-group-item-action border-0 rounded-3 mb-2 <?= $page == 'profile' ? 'bg-primary text-white' : '' ?>">
                                <i class="fa-solid fa-user"></i> <?= lang('SE_PERSONAL_DATA') ?>
                            </a>
                            <a href="?page=password"
                                class="list-group-item list-group-item-action border-0 rounded-3 mb-2 <?= $page == 'password' ? 'bg-primary text-white' : '' ?>">
                                <i class="fa-solid fa-lock"></i> <?= lang('SE_CHANGE_PASSWORD') ?>
                            </a>
                            <a href="?page=SE_APP"
                                class="list-group-item list-group-item-action border-0 rounded-3 mb-2 <?= $page == 'SE_APP' ? 'bg-primary text-white' : '' ?>">
                                <i class="fa-solid fa-sliders"></i> <?= lang('SE_APP_SETTINGS') ?>
                            </a>
                            <a href="?page=about"
                                class="list-group-item list-group-item-action border-0 rounded-3 mb-2 <?= $page == 'about' ? 'bg-primary text-white' : '' ?>">
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
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-4 px-4">
                                <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-user text-primary"></i>
                                    <?= lang('SE_PERSONAL_DATA') ?>
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="profile-avatar text-light text-capitalize mb-4 mx-auto bg-primary rounded-circle d-flex justify-content-center align-items-center fw-bolder"
                                    style="width: 120px; height: 120px; font-size: 48px;">
                                    <?= $user['name'][0] ?? 'U' ?>
                                </div>

                                <form method="POST" action="?page=profile">
                                    <input type="hidden" name="action" value="update_profile">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= lang('INPUT_NAME') ?></label>
                                        <input type="text" class="form-control rounded-3 py-2" name="name"
                                            value="<?= htmlspecialchars($user['name']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= lang('INPUT_EMAIL') ?></label>
                                        <input type="email" class="form-control rounded-3 py-2" value="<?= htmlspecialchars($user['email']) ?>"
                                            disabled>
                                        <small class="text-muted"><?= lang('SE_NO_CHANGE_EMAIL') ?></small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold"><?= lang('PR_JOIN_DATE') ?></label>
                                        <input type="text" style="direction: ltr !important" class="form-control rounded-3 py-2"
                                            value="<?= date('j F Y', strtotime($user['created-at'])) ?>" disabled>
                                    </div>

                                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3">
                                        <i class="fa-solid fa-save me-2"></i> <?= lang('SE_SAVE_CHANGES') ?>
                                    </button>
                                </form>
                            </div>
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
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-4 px-4">
                                <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-lock text-primary"></i>
                                    <?= lang('SE_CHANGE_PASSWORD') ?>
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" action="?page=password">
                                    <input type="hidden" name="action" value="change_password">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= lang('SE_CURRENT_PASSWORD') ?></label>
                                        <input type="password" class="form-control rounded-3 py-2" name="current_password">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold"><?= lang('SE_NEW_PASSWORD') ?></label>
                                        <input type="password" class="form-control rounded-3 py-2" name="new_password">
                                        <small class="text-muted"><?= lang('SE_PASSWORD_LENGTH') ?></small>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label fw-semibold"><?= lang('SE_CONFIRM_PASSWORD') ?></label>
                                        <input type="password" class="form-control rounded-3 py-2" name="confirm_password">
                                    </div>

                                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3">
                                        <i class="fa-solid fa-key me-2"></i> <?= lang('SE_UPDATE_PASSWORD') ?>
                                    </button>
                                </form>
                            </div>
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
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-4 px-4">
                                <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-sliders text-primary"></i>
                                    <?= lang('SE_APP_SETTINGS') ?>
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                <form method="POST" class="m-0 p-0" action="?page=SE_APP">
                                    <h5 class="mb-3 fw-semibold"> <?= lang('SE_LANGUAGE') ?></h5>
                                    <div class="s_lang_ar_en">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="app_lang_admin" value="ar.php" id="lang_ar" <?= (isset($_SESSION['app_lang_admin']) && $_SESSION['app_lang_admin'] == 'ar.php') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="lang_ar">
                                                العربية
                                            </label>
                                        </div>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="app_lang_admin" value="en.php" id="lang_en" <?= (isset($_SESSION['app_lang_admin']) && $_SESSION['app_lang_admin'] == 'en.php') ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="lang_en">
                                                English
                                            </label>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary px-5 py-2 rounded-3 mt-3">
                                        <i class="fa-solid fa-arrow-rotate-right me-2"></i> <?= lang('SE_UPDATE') ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php
                } elseif ($page == 'about') {

                    ?>
                    <div class="col-12 col-lg-7">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-4 px-4">
                                <h4 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-info-circle text-primary"></i>
                                    <?= lang('SE_ABOUT') ?>
                                </h4>
                            </div>
                            <div class="card-body p-4">
                                <div class="mb-4">
                                    <h5 class="fw-bold"><?= lang('SE_APP_NAME') ?></h5>
                                    <p class="text-muted"><?= lang('SE_VERSION') ?> <?= $version ?></p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold mb-3"><?= lang('SE_FEATURES') ?>:</h6>
                                    <ul class="list-unstyled ms-2">
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <?= lang('SE_FRIENDS_SYSTEM') ?></li>
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <?= lang('SE_PRIVATE_MESSAGES') ?></li>
                                        <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> <?= lang('SE_NOTIFICATIONS') ?></li>
                                    </ul>
                                </div>

                                <div>
                                    <p class="text-muted mb-0"><?= lang('SE_THANK_YOU') ?></p>
                                </div>
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
