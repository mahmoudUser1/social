<?php

$pageTitle = 'home';

session_start();

include "initials.php";

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
if (isset($_SESSION["email"])) {

    ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-4 col-md-5">
        </div>
        <div class="col-12 col-lg-7 col-md-6">

            <?php

            if ($page == 'home') {

                ?>
                <div class="p-2 m-3 card flex-row d-flex justify-content-between align-items-center rounded-5">
                    <h5><?= lang('H_CREATE_POST') ?></h5>
                    <a href="?page=addPost"
                        class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 40px; height: 40px;"><i class="fa-solid fa-plus"></i></a>
                </div>

                <?php

                $stmt = $con->prepare("SELECT * FROM posts ORDER BY id DESC");
                $stmt->execute();
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($posts) {
                    foreach ($posts as $post1) {

                        $stmt = $con->prepare("SELECT name FROM users WHERE id = ?");
                        $stmt->execute([$post1['user-id']]);
                        $userPost = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>

                        <div class="card  bg-warning bg-opacity-10 p-2 mb-2">
                            <div class="post-header border-bottom border-secondary d-flex justify-content-start align-items-center ">
                                <div class="
                                post-avatar
                                text-light 
                                text-capitalize 
                                m-2
                                bg-primary 
                                rounded-circle
                                d-flex 
                                justify-content-center
                                align-items-center 
                                fw-bolder
                                fs-1
                            "><?= $userPost['name'][0] ?></div>
                                <div>
                                    <div class="post-user"><?= $userPost['name'] ?></div>
                                    <div class="post-time"><?= date("(A h:i) j/n/Y", strtotime($post1['created-at'])) ?></div>
                                </div>
                            </div>
                            <div class="post-content m-2 py-2 overflow-hidden">
                                <pre class="text-post overflow-hidden"><?= htmlspecialchars($post1['content']) ?></pre>
                            </div>
                            <!-- <div class="comment-section d-flex flex-wrap gap-2 w-100 p-2 border-top">

                                <button type="button" class="btn btn-primary">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>

                                <button type="button" class="btn btn-danger">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </button>

                                <button type="button" class="btn btn-light">
                                    <i class="fa-solid fa-heart text-danger"></i>
                                </button>

                                <button type="button" class="btn btn-dark opacity-50">
                                    <i class="fa-solid fa-face-laugh text-warning opacity-100"></i>
                                </button>

                                <button type="button" class="btn btn-dark opacity-50">
                                    <i class="fa-solid fa-face-angry text-danger opacity-100"></i>
                                </button>

                            </div> -->
                        </div>

                        <?php
                    }
                }

            } elseif ($page == 'addPost') {

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $post = $_POST['post'];

                    $stmt = $con->prepare("SELECT id FROM users WHERE email = ? ");

                    $stmt->execute([$_SESSION["email"]]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);


                    $stmt = $con->prepare("INSERT INTO posts (`user-id`, content) VALUES (?, ?)");

                    $stmt->execute([$user['id'], $post]);

                    header("Location: home.php");
                    exit;
                }
                ?>

                <div class="card p-3">
                    <form action="?page=addPost" method="post">
                        <h5><?= lang('H_NEW_POST') ?></h5>
                        <textarea class="form-control mb-3" name="post" placeholder="<?= lang('H_POST_CONTENT') ?>"></textarea>
                        <button type="submit" class="btn btn-primary"><?= lang('H_POST_PUBLISH') ?></button>
                    </form>
                </div>

                <?php

            } elseif ($page == 'editPost') {

                if (!isset($_GET['id'])) {
                    header("Location: profile.php");
                    exit;
                }

                $postId = $_GET['id'];

                $stmt = $con->prepare("SELECT * FROM posts WHERE id = ?");
                $stmt->execute([$postId]);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$post) {
                    header("Location: profile.php");
                    exit;
                }

                if ($_SERVER["REQUEST_METHOD"] == "POST") {

                    $postContent = $_POST['post'];

                    $stmt = $con->prepare("UPDATE posts SET content = ? WHERE id = ?");
                    $stmt->execute([$postContent, $postId]);

                    header("Location: profile.php");
                    exit;
                }
                ?>
                <div class="card p-3">
                    <form action="?page=editPost&id=<?= $postId ?>" method="post">
                        <h5><?= lang('H_EDIT_POST') ?></h5>
                        <textarea class="form-control mb-3" name="post"><?= htmlspecialchars($post['content']) ?></textarea>
                        <button type="submit" class="btn btn-primary"><?= lang('H_POST_PUBLISH') ?></button>
                    </form>
                </div>
                <?php
            } elseif ($page == 'deletePost') {

                if (!isset($_GET['id'])) {
                    header("Location: profile.php");
                    exit;
                }

                $postId = $_GET['id'];

                $stmt = $con->prepare("SELECT * FROM posts WHERE id = ?");
                $stmt->execute([$postId]);
                $post = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$post) {
                    header("Location: profile.php");
                    exit;
                }

                $stmt = $con->prepare("DELETE FROM posts WHERE id = ?");
                $stmt->execute([$postId]);

                header("Location: profile.php");
                exit;
            } else {

                ?>
                <div class="p-2 m-3 card flex-row d-flex justify-content-between align-items-center rounded-5">
                    <h5><?= lang('H_CREATE_POST') ?></h5>
                    <a href="?page=addPost"
                        class="btn btn-primary rounded-circle d-flex justify-content-center align-items-center"
                        style="width: 40px; height: 40px;"><i class="fa-solid fa-plus"></i></a>
                </div>

                <?php

                $stmt = $con->prepare("SELECT * FROM posts ORDER BY id DESC");
                $stmt->execute();
                $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if ($posts) {
                    foreach ($posts as $post1) {

                        $stmt = $con->prepare("SELECT name FROM users WHERE id = ?");
                        $stmt->execute([$post1['user-id']]);
                        $userPost = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>

                        <div class="card  bg-warning bg-opacity-10 p-2 mb-2">
                            <div class="post-header border-bottom border-secondary d-flex justify-content-start align-items-center ">
                                <div class="
                                post-avatar
                                text-light 
                                text-capitalize 
                                m-2
                                bg-primary 
                                rounded-circle
                                d-flex 
                                justify-content-center
                                align-items-center 
                                fw-bolder
                                fs-1
                            "><?= $userPost['name'][0] ?></div>
                                <div>
                                    <div class="post-user"><?= $userPost['name'] ?></div>
                                    <div class="post-time"><?= date("(A h:i) j/n/Y", strtotime($post1['created-at'])) ?></div>
                                </div>
                            </div>
                            <div class="post-content m-2 py-2 overflow-hidden">
                                <pre class="text-post overflow-hidden"><?= htmlspecialchars($post1['content']) ?></pre>
                            </div>
                            <!-- <div class="comment-section d-flex flex-wrap gap-2 w-100 p-2 border-top">

                                <button type="button" class="btn btn-primary">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                </button>

                                <button type="button" class="btn btn-danger">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                </button>

                                <button type="button" class="btn btn-light">
                                    <i class="fa-solid fa-heart text-danger"></i>
                                </button>

                                <button type="button" class="btn btn-dark opacity-50">
                                    <i class="fa-solid fa-face-laugh text-warning opacity-100"></i>
                                </button>

                                <button type="button" class="btn btn-dark opacity-50">
                                    <i class="fa-solid fa-face-angry text-danger opacity-100"></i>
                                </button>

                            </div> -->
                        </div>

                        <?php
                    }
                }

            }
} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
