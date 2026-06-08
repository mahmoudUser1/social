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

    $date = date("j F Y", strtotime($user['created-at']));

    $stmt = $con->prepare("
        SELECT COUNT(*) AS total_posts
FROM posts
WHERE `user-id` = (
    SELECT id
    FROM users
    WHERE email = ?
);
    ");

    $stmt->execute([$_SESSION["email"]]);
    $postCount = $stmt->fetch(PDO::FETCH_ASSOC);
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
                    <p><span> <?= lang('INPUT_EMAIL') ?> : </span> <?= $user['email'] ?></p>
                    <p><span> <?= lang('PR_JOIN_DATE') ?> : </span><span class="d-inline-block"
                            style="direction: ltr !important"><?= $date ?></span></p>
                    <p><span> <?= lang('PR_POST_COUNT') ?> : </span><?= $postCount['total_posts'] ?></p>
                </div>
                <div class="control d-flex justify-content-between gap-2">
                    <a href="settings.php" class="btn btn-primary w-100"><?= lang('PR_EDIT_PROFILE') ?></a>
                    <a href="logout.php" class="btn btn-danger w-100"><?= lang('PR_LOGOUT') ?></a>
                    <a href="home.php?page=addPost" class="btn btn-secondary w-100"><?= lang('PR_CREATE_POST') ?></a>
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

                    $stmt->execute([$_SESSION["email"]]);
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
                                        <a href="home.php?page=editPost&id=<?= $post['id'] ?>"
                                            class="btn btn-outline-primary btn-sm" title="<?= lang('PR_EDIT') ?>">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                            <span class="visually-hidden"><?= lang('PR_EDIT') ?></span>
                                        </a>
                                        <a href="home.php?page=deletePost&id=<?= $post['id'] ?>"
                                            class="btn btn-outline-danger btn-sm" title="<?= lang('PR_DELETE') ?>">
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span class="visually-hidden"><?= lang('PR_DELETE') ?></span>
                                        </a>
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

        </div>
    </div>
    <?php

} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
