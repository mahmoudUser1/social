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
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">Confirm Delete Post</h5>
                </div>
                <div class="card-body">
                    <p><strong>Author:</strong> <?= htmlspecialchars($post['author'] ?: 'Unknown') ?></p>
                    <p><strong>Date:</strong> <span style="direction: ltr;"><?= date('j F Y', strtotime($post['created-at'])) ?></span></p>
                    <div class="mb-3">
                        <label class="form-label">Post Content</label>
                        <div class="border rounded p-3 bg-light"><pre class="m-0 text-post overflow-hidden"><?= htmlspecialchars($post['content']) ?></pre></div>
                    </div>
                    <form method="post" action="posts.php?page=deletePost&id=<?= $postId ?>">
                        <button type="submit" name="confirm" value="yes" class="btn btn-danger"><?= lang('BTN_DELETE') ?></button>
                        <a href="posts.php" class="btn btn-secondary ms-2"><?= lang('BTN_CANCEL') ?></a>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <?php
            $stmt = $con->prepare("SELECT p.id, p.content, p.`created-at`, u.name AS author, u.email AS author_email FROM posts p LEFT JOIN users u ON p.`user-id` = u.id ORDER BY p.`created-at` DESC");
            $stmt->execute();
            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            ?>

            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?= lang('PR_POSTS') ?></h5>
                    <span class="badge bg-secondary"><?= count($posts) ?> posts</span>
                </div>
                <div class="card-body p-0">
                    <?php if ($posts): ?>
                        <div class="table-responsive">
                            <table class="table table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?= lang('INPUT_EMAIL') ?></th>
                                        <th>Author</th>
                                        <th>Content</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($posts as $postItem): ?>
                                        <tr>
                                            <td><?= intval($postItem['id']) ?></td>
                                            <td><?= htmlspecialchars($postItem['author_email'] ?: '-') ?></td>
                                            <td><?= htmlspecialchars($postItem['author'] ?: 'Unknown') ?></td>
                                            <td><?= nl2br(htmlspecialchars(mb_strimwidth($postItem['content'], 0, 120, '...'))) ?></td>
                                            <td style="direction: ltr; white-space: nowrap;"><?= date('j F Y', strtotime($postItem['created-at'])) ?></td>
                                            <td>
                                                <a href="posts.php?page=deletePost&id=<?= intval($postItem['id']) ?>" class="btn btn-sm btn-danger">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="p-3"><em>No posts found.</em></div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
include $temp . "footer.php";

