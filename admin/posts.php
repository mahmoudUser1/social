<?php

$pageTitle = "posts";

session_start();

include "initials.php";

if (!isset($_SESSION["email_admin"])) {
    header("Location: index.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'posts';
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['message'], $_SESSION['error']);

if ($page === 'deletePost' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $postId = intval($_GET['id']);
    $stmt = $con->prepare("SELECT p.id, p.content, p.`created-at`, u.name AS author FROM posts p LEFT JOIN users u ON p.`user-id` = u.id WHERE p.id = ?");
    $stmt->execute([$postId]);
    $post = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$post) {
        $_SESSION['error'] = 'Post not found.';
        header('Location: posts.php');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            $stmt = $con->prepare("DELETE FROM posts WHERE id = ?");
            $stmt->execute([$postId]);
            $_SESSION['message'] = 'Post deleted successfully.';
        }
        header('Location: posts.php');
        exit;
    }
}
?>
<div class="row pt-6 p-md-2 m-0">
    <div class="d-none d-md-block col-lg-3 col-md-4"></div>
    <div class="col-12 col-lg-9 col-md-8">
        <?php if ($message): ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($page === 'deletePost' && isset($post) && $post): ?>
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">Confirm Delete Post</h5>
                            <small class="text-muted">Please confirm before removing this post permanently.</small>
                        </div>
                        <span class="badge bg-danger">Delete</span>
                    </div>
                </div>
                <div class="card-body p-4 bg-light">
                    <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <div class="bg-white rounded-3 p-3 shadow-sm h-100">
                                <p class="mb-1 text-uppercase small text-secondary">Author</p>
                                <h6 class="mb-2 fw-semibold"><?= htmlspecialchars($post['author'] ?: 'Unknown') ?></h6>
                                <p class="mb-0 text-muted small"><?= htmlspecialchars($post['author'] ?: 'Unknown') ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="bg-white rounded-3 p-3 shadow-sm h-100">
                                <p class="mb-1 text-uppercase small text-secondary">Date</p>
                                <p class="mb-0 fw-semibold"><?= date('j F Y', strtotime($post['created-at'])) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="rounded-4 border border-1 border-secondary-subtle bg-white p-4 mb-4">
                        <h6 class="mb-3">Post Content</h6>
                        <p class="mb-0 text-muted" style="white-space: pre-wrap; word-break: break-word; overflow-wrap: anywhere;"><?= htmlspecialchars($post['content']) ?></p>
                    </div>
                    <form method="post" action="posts.php?page=deletePost&id=<?= $postId ?>">
                        <button type="submit" name="confirm" value="yes" class="btn btn-danger me-2 px-4"><?= lang('BTN_DELETE') ?></button>
                        <a href="posts.php" class="btn btn-outline-secondary px-4"><?= lang('BTN_CANCEL') ?></a>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <?php
            $stmt = $con->prepare("SELECT p.id, p.content, p.`created-at`, u.name AS author, u.email AS author_email FROM posts p LEFT JOIN users u ON p.`user-id` = u.id ORDER BY p.`created-at` DESC");
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <div>
                        <h5 class="mb-1"><?= lang('PR_POSTS') ?></h5>
                        <small class="text-muted">Manage published posts and remove content as needed.</small>
                    </div>
                    <span class="badge bg-secondary py-2 px-3 rounded-pill"><?= count($posts) ?> posts</span>
                </div>
                <div class="card-body p-0">
                    <?php if ($posts): ?>
                        <div class="row row-cols-1 row-cols-sm-1 row-cols-xl-2 g-4 p-3">
                            <?php foreach ($posts as $postItem): ?>
                                <div class="col">
                                    <div class="card h-100 border rounded-4 shadow-sm overflow-hidden">
                                        <div class="card-header bg-white border-bottom py-3">
                                            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
<div>
<h6 class="mb-1 fw-semibold text-primary"><?= htmlspecialchars($postItem['author'] ?: 'Unknown') ?></h6>
<p class="mb-1 text-muted small mb-0"><?= htmlspecialchars($postItem['author_email'] ?: '-') ?></p>
</div>
<span class="badge bg-secondary rounded-pill"><?= date('j F Y', strtotime($postItem['created-at'])) ?></span>
                                            </div>
                                        </div>
                                        <div class="card-body py-3">
                                            <p class="text-muted mb-0" style="white-space: pre-wrap; word-break: break-word; overflow-wrap: anywhere; line-height: 1.6;"><?= htmlspecialchars(mb_strimwidth($postItem['content'], 0, 180, '...')) ?></p>
                                        </div>
                                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                            <span class="text-secondary small">#<?= intval($postItem['id']) ?></span>
                                            <a href="posts.php?page=deletePost&id=<?= intval($postItem['id']) ?>" class="btn btn-sm btn-outline-danger px-3">
                                                <i class="fa-solid fa-trash-can me-1"></i> <?= lang('BTN_DELETE') ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center text-muted"><em>No posts found.</em></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
include $temp . "footer.php";

