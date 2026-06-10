<?php

$pageTitle = "users";

session_start();

include "initials.php";

if (isset($_SESSION["email_admin"])) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'users';

    function renderUserBadge($role)
    {
        $label = $role == 1 ? lang('ADMIN') : lang('USER');
        $variant = $role == 1 ? 'bg-success' : 'bg-info';
        return '<span class="badge rounded-pill text-uppercase ' . $variant . ' py-2 px-3">' . $label . '</span>';
    }

    function renderUserCard($user)
    {
        echo '<div class="col">';
        echo '<div class="card h-100 border rounded-4 shadow-sm overflow-hidden">';
        echo '<div class="card-body d-flex flex-column">';
        echo '<div class="d-flex justify-content-between align-items-start gap-3 mb-3 flex-column flex-sm-row">';
        echo '<div class="min-w-0">';
        echo '<h5 class="card-title mb-1 fw-semibold">' . htmlspecialchars($user['name']) . '</h5>';
        echo '<p class="card-text text-muted mb-0 text-break">' . htmlspecialchars($user['email']) . '</p>';
        echo '</div>';
        echo '<div class="flex-shrink-0">' . renderUserBadge($user['role']) . '</div>';
        echo '</div>';
        echo '</div>';
        echo '<div class="card-footer bg-white border-top d-flex justify-content-between align-items-center gap-2 flex-wrap">';
        echo '<small class="text-muted">' . lang('ID') . ': ' . intval($user['id']) . '</small>';
        echo '<a href="users.php?page=profile&user_id=' . intval($user['id']) . '" class="btn btn-sm btn-primary">' . lang('P_VIEW_PROFILE') . '</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-3 col-md-4">
        </div>
        <div class="col-12 col-lg-9 col-md-8">


            <?php
            if ($page == 'profile') {
                if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
                    $user_id = $_GET['user_id'];
                    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();

                    if (!$user) {
                        echo "<div class='alert alert-danger'>User Not Found</div>";
                    } else {
                        $user_role = $user['role'] == 1
                            ? '<span class="badge bg-success text-light rounded-pill py-2 px-3"><i class="fa-solid fa-user-tie me-1"></i> ' . lang('ADMIN') . '</span>'
                            : '<span class="badge bg-info text-light rounded-pill py-2 px-3"><i class="fa-solid fa-user me-1"></i> ' . lang('USER') . '</span>';

                        $stmt = $con->prepare("SELECT COUNT(*) AS total_posts FROM posts WHERE `user-id` = ?");
                        $stmt->execute([$user['id']]);
                        $postCount = $stmt->fetch(PDO::FETCH_ASSOC);

                        $stmt = $con->prepare("SELECT * FROM posts WHERE `user-id` = ? ORDER BY `created-at` DESC");
                        $stmt->execute([$user['id']]);
                        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                                    <div class="min-w-0">
                                        <h3 class="card-title mb-1 fw-bold"><?= htmlspecialchars($user['name']) ?></h3>
                                        <p class="text-muted mb-0 text-break"><?= htmlspecialchars($user['email']) ?></p>
                                    </div>
                                    <div class="flex-shrink-0"><?= $user_role ?></div>
                                </div>
                            </div>
                            <div class="card-body bg-light p-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-4">
                                        <div class="bg-white rounded-4 p-3 h-100 shadow-sm">
                                            <p class="text-uppercase text-secondary small mb-2"><?= lang('PR_JOIN_DATE') ?></p>
                                            <p class="mb-0 fw-semibold" style="direction: ltr;"> <?= date("j F Y", strtotime($user['created-at'])) ?></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="bg-white rounded-4 p-3 h-100 shadow-sm">
                                            <p class="text-uppercase text-secondary small mb-2"><?= lang('PR_POST_COUNT') ?></p>
                                            <p class="mb-0 fw-semibold"><?= intval($postCount['total_posts']) ?></p>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <div class="bg-white rounded-4 p-3 h-100 shadow-sm">
                                            <p class="text-uppercase text-secondary small mb-2"><?= lang('ID') ?></p>
                                            <p class="mb-0 fw-semibold"><?= intval($user['id']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-top d-flex flex-column flex-md-row justify-content-between gap-2">
                                <a href="users.php" class="btn btn-outline-secondary"><?= lang('P_USERS') ?></a>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="users.php?page=editUser&id=<?= $user['id'] ?>" class="btn btn-primary"><i class="fa-solid fa-pen-to-square me-1"></i> <?= lang('BTN_EDIT') ?></a>
                                    <a href="users.php?page=deleUser&id=<?= $user['id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash-can me-1"></i> <?= lang('BTN_DELETE') ?></a>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4">
                            <div class="card-header bg-white border-bottom py-3 d-flex flex-column flex-md-row justify-content-between align-items-start gap-2">
                                <div>
                                    <h5 class="mb-1 fw-semibold"><?= lang('PR_POSTS') ?></h5>
                                    <small class="text-muted"><?= intval($postCount['total_posts']) ?> <?= lang('PR_POSTS') ?></small>
                                </div>
                                <span class="badge bg-secondary rounded-pill py-2 px-3"><?= intval($postCount['total_posts']) ?> <?= lang('PR_POSTS') ?></span>
                            </div>
                            <div class="card-body p-3">
                                <?php if (count($posts) > 0): ?>
                                    <div class="row row-cols-1 row-cols-lg-2 g-4">
                                        <?php foreach ($posts as $post): ?>
                                            <div class="col">
                                                <div class="card h-100 border rounded-4 shadow-sm overflow-hidden d-flex flex-column">
                                                    <div class="card-body flex-grow-1 p-3">
                                                        <p class="text-muted mb-4 text-break" style="white-space: pre-wrap; word-break: break-word; overflow-wrap: anywhere; line-height: 1.75;"><?= htmlspecialchars($post['content']) ?></p>
                                                    </div>
                                                    <div class="card-footer bg-white border-top d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                                                        <span class="text-secondary small" style="direction: ltr;"><?= date("j F Y", strtotime($post['created-at'])) ?></span>
                                                        <a href="users.php?page=deletePost&id=<?= $post['id'] ?>" class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash-can me-1"></i> <?= lang('BTN_DELETE') ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="p-4 text-center text-muted"><?= lang('PR_NO_POSTS') ?></div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<div class='alert alert-danger'>Invalid User ID</div>";
                }

            } elseif ($page == 'editUser') {

                if (isset($_GET['id']) && is_numeric($_GET['id'])) {

                    $user_id = $_GET['id'];
                    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {

                        $role = $_POST['role'];

                        if ($role !== "0" && $role !== "1") {

                            $_SESSION['error'] = lang('M_C_S_R');

                        } else {

                            $stmt = $con->prepare("
                                    UPDATE users
                                    SET role = ?
                                    WHERE id = ?
                                ");

                            $stmt->execute([$role, $user_id]);

                            header("Location: ?page=profile&user_id=" . $user_id);
                            exit();
                        }
                    }


                    if ($user) {
                        ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="card-title"><strong><?= $user['name'] ?></strong></h3>
                                <p class="card-text"><strong><?= lang('INPUT_EMAIL') ?>:</strong> <?= $user['email'] ?></p>
                                <p class="card-text"><strong><?= lang('PR_JOIN_DATE') ?>:</strong>
                                    <span class="d-inline-block"
                                        style="direction: ltr !important"><?= date("j F Y", strtotime($user['created-at'])); ?></span>
                                </p>
                                <form action="?page=editUser&id=<?= $user['id'] ?>" method="post">
                                    <select class="form-select" name="role">

                                        <option value="0" <?= $user['role'] == 0 ? 'selected' : '' ?>>
                                            <?= lang('USER') ?>
                                        </option>

                                        <option value="1" <?= $user['role'] == 1 ? 'selected' : '' ?>>
                                            <?= lang('ADMIN') ?>
                                        </option>

                                    </select>
                                    <button type="submit" class="btn btn-primary p-1 m-2"><i class="fa-solid fa-pen-to-square me-1"></i> <?= lang('BTN_EDIT') ?></button>
                                </form>
                            </div>
                        </div>
                        <?php
                    } else {
                        echo "<div class='alert alert-danger'>User Not Found</div>";
                    }

                } else {
                    echo "<div class='alert alert-danger'>Invalid User ID</div>";
                }
            } elseif ($page == 'deletePost') {

                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $post_id = intval($_GET['id']);

                    $stmt = $con->prepare("SELECT `user-id` FROM posts WHERE id = ?");
                    $stmt->execute([$post_id]);
                    $post = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($post) {
                        $stmt = $con->prepare("DELETE FROM posts WHERE id = ?");
                        $stmt->execute([$post_id]);

                        $_SESSION['message'] = 'Post deleted successfully';
                        header("Location: ?page=profile&user_id=" . $post['user-id']);
                        exit();
                    }
                }

                header("Location: users.php");
                exit();
            } elseif ($page == 'deleUser') {

                if (isset($_GET['id']) && is_numeric($_GET['id'])) {

                    $user_id = intval($_GET['id']);
                    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();

                    if (!$user) {
                        echo "<div class='alert alert-danger'>User Not Found</div>";
                    } else {

                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                            $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : 'no';

                            if ($confirm === 'yes') {
                                $stmt = $con->prepare("DELETE FROM chat WHERE `to-id` = ? OR `from-id` = ?");

                                $stmt->execute([$user_id, $user_id]);

                                $stmt = $con->prepare("DELETE FROM posts WHERE `user-id` = ?");

                                $stmt->execute([$user_id]);

                                $stmt = $con->prepare("DELETE FROM users WHERE id = ?");

                                $stmt->execute([$user_id]);

                                $_SESSION['message'] = 'User deleted successfully';

                                header("Location: users.php");
                                exit();
                            } else {
                                header("Location: ?page=profile&user_id=" . $user_id);
                                exit();
                            }
                        }

                        ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h3 class="card-title"><strong><?= lang('BTN_DELETE') ?>                 <?= lang('USER') ?>:
                                        <?= htmlspecialchars($user['name']) ?></strong></h3>
                                <p class="card-text"><?= lang('A_Y_D') ?></p>
                                <form method="post" action="?page=deleUser&id=<?= $user['id'] ?>">
                                    <button type="submit" name="confirm" value="yes"
                                        class="btn btn-danger"><i class="fa-solid fa-trash-can me-1"></i> <?= lang('BTN_DELETE') ?></button>
                                    <button type="submit" name="confirm" value="no"
                                        class="btn btn-secondary"><?= lang('BTN_CANCEL') ?></button>
                                </form>
                            </div>
                        </div>
                        <?php
                    }

                } else {
                    echo "<div class='alert alert-danger'>Invalid User ID</div>";
                }

            } else {
                $stmt = $con->prepare("SELECT * FROM users WHERE email != ?");
                $stmt->execute([$_SESSION["email_admin"]]);
                $users = $stmt->fetchAll();

                if ($users) {
                    echo '<div class="card border-0 shadow-sm rounded-4 mb-4">';
                    echo '    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">';
                    echo '        <div>';
                    echo '            <h5 class="mb-1 fw-bold">' . lang('P_USERS') . '</h5>';
                    echo '            <p class="text-muted mb-0">All active users are listed here for quick review.</p>';
                    echo '        </div>';
                    echo '        <span class="badge bg-secondary rounded-pill py-2 px-3">' . count($users) . ' ' . lang('P_USERS') . '</span>';
                    echo '    </div>';
                    echo '</div>';
                    echo '<div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">';
                    foreach ($users as $user) {
                        renderUserCard($user);
                    }
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info text-center" role="alert">
                    No users found.
                </div>';
                }
            }
            ?>


        </div>
    </div>
    <?php

} else {
    header("Location: index.php");
    exit;
}


include $temp . "footer.php";