<?php

$pageTitle = "users";

session_start();

include "initials.php";

if (isset($_SESSION["email_admin"])) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'users';
    ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-3 col-md-4">
        </div>
        <div class="col-12 col-lg-9 col-md-8">


            <?php
            if ($page == 'users') {
                $stmt = $con->prepare("SELECT * FROM users WHERE email != ?");
                $stmt->execute([$_SESSION["email_admin"]]);
                $users = $stmt->fetchAll();

                if ($users) {
                    echo '<div class="row">';
                    foreach ($users as $user) {

                        ?>


                        <div class="col-md-12 col-lg-6">
                            <div class="card mb-3 overflow-hidden">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $user['name'] ?></h5>
                                    <p class="card-text"><?= $user['email'] ?></p>
                                </div>
                                <div class="card-footer row">
                                    <div class="col-6">
                                        <a href="users.php?page=profile&user_id=<?= $user['id'] ?>"
                                            class="btn btn-primary"><?= lang('P_VIEW_PROFILE') ?></a>
                                    </div>

                                    <div class="col-6 m-0 p-0">
                                        <small><?= lang('ID') ?> : <?= $user['id'] ?> </small>
                                        <p>
                                            <?php
                                            if ($user['role'] == 1) {
                                                echo '<i class="fa-solid fa-user-tie"></i> ' . lang('ADMIN');
                                            } else {
                                                echo '<i class="fa-solid fa-user"></i> ' . lang('USER');
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <?php
                    }
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info text-center" role="alert">
                    No users found.
                </div>';
                }
            } elseif ($page == 'profile') {
                if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
                    $user_id = $_GET['user_id'];
                    $stmt = $con->prepare("SELECT * FROM users WHERE id = ?");
                    $stmt->execute([$user_id]);
                    $user = $stmt->fetch();

                    if ($user['role'] == 1) {
                        $user_role = '<span class="text-light fw-bolder bg-success p-1 my-1 rounded-3 d-inline-block"><i class="fa-solid fa-user-tie"></i> adnin</span>';
                    } else {
                        $user_role = '<span class="text-light fw-bolder bg-info p-1 my-1 rounded-3 d-inline-block"><i class="fa-solid fa-user"></i> user</span>';
                    }

                    $stmt = $con->prepare("SELECT COUNT(*) AS total_posts FROM posts WHERE `user-id` = ( SELECT id FROM users WHERE email = ?);");
                    $stmt->execute([$user['email']]);
                    $postCount = $stmt->fetch(PDO::FETCH_ASSOC);

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
                                <p class="card-text"><strong><?= lang('PR_POST_COUNT') ?>:</strong> <?= $postCount['total_posts'] ?></p>
                                <div><?= $user_role ?></div>
                            </div>
                            <div class="card-footer">
                                <a href="users.php" class="btn btn-secondary"><?= lang('P_USERS') ?></a>
                                <a href="users.php?page=editUser&id=<?= $user['id'] ?>"
                                    class="btn btn-primary"><i class="fa-solid fa-pen-to-square me-1"></i> <?= lang('BTN_EDIT') ?></a>
                                <a href="users.php?page=deleUser&id=<?= $user['id'] ?>" class="btn btn-danger"><i class="fa-solid fa-trash-can me-1"></i> <?= lang('BTN_DELETE') ?>
                                </a>
                            </div>
                        </div>

                        <div class="card p-3 my-2">
                            <h5 class="card-title"><?= lang('PR_POSTS') ?></h5>
                            <div class="card-body">
                                <?php
                                $stmt = $con->prepare("
                        SELECT *
                        FROM posts
                        WHERE `user-id` = (
                            SELECT id
                            FROM users
                            WHERE email = ?
                        )
                        ORDER BY `created-at` DESC
                    ");

                                $stmt->execute([$user['email']]);
                                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                if (count($posts) > 0) {
                                    foreach ($posts as $post) {
                                        ?>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <p class="text-post">
                                                <pre class="text-post overflow-hidden"><?= htmlspecialchars($post['content']) ?></pre>
                                                </p>
                                                <p class="text-muted text-end mb-0 d-inline-block"
                                                    style="font-size: 14px;direction: ltr !important">
                                                    <?= date("j F Y", strtotime($post['created-at'])) ?>
                                                </p>
                                                <div class="comment-section d-flex gap-2 w-100 p-2 border-top">
                                                    <a href="users.php?page=deletePost&id=<?= $post['id'] ?>"
                                                        class="btn btn-danger w-100"><i class="fa-solid fa-trash-can me-1"></i> <?= lang('BTN_DELETE') ?></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                } else {
                                    ?>
                                    <p class="text-muted"><?= lang('PR_NO_POSTS') ?></p>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>

                        <?php
                    } else {
                        echo "<div class='alert alert-danger'>User Not Found</div>";
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
                    echo '<div class="row">';
                    foreach ($users as $user) {

                        ?>


                        <div class="col-md-12 col-lg-6">
                            <div class="card mb-3 overflow-hidden">
                                <div class="card-body">
                                    <h5 class="card-title"><?= $user['name'] ?></h5>
                                    <p class="card-text"><?= $user['email'] ?></p>
                                </div>
                                <div class="card-footer row">
                                    <div class="col-6">
                                        <a href="users.php?page=profile&user_id=<?= $user['id'] ?>"
                                            class="btn btn-primary"><?= lang('P_VIEW_PROFILE') ?></a>
                                    </div>

                                    <div class="col-6 m-0 p-0">
                                        <small><?= lang('ID') ?> : <?= $user['id'] ?> </small>
                                        <p>
                                            <?php
                                            if ($user['role'] == 1) {
                                                echo '<i class="fa-solid fa-user-tie"></i> ' . lang('ADMIN');
                                            } else {
                                                echo '<i class="fa-solid fa-user"></i> ' . lang('USER');
                                            }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
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