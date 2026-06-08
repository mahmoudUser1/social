<?php

$pageTitle = "dashboard";

session_start();

include "initials.php";

if (isset($_SESSION["email_admin"])) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
    $message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
    unset($_SESSION['message'], $_SESSION['error']);

    if ($page === 'createPost' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $content = trim($_POST['content'] ?? '');

        if ($content === '') {
            $_SESSION['error'] = 'Post content cannot be empty.';
            header('Location: dashboard.php?page=createPost');
            exit();
        }

        $stmt = $con->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$_SESSION['email_admin']]);
        $adminUser = $stmt->fetch(PDO::FETCH_ASSOC);
        $adminId = $adminUser ? intval($adminUser['id']) : 0;

        $stmt = $con->prepare("INSERT INTO posts (`user-id`, content) VALUES (?, ?)");
        $stmt->execute([$adminId, $content]);

        $_SESSION['message'] = 'Post published successfully.';
        header('Location: dashboard.php');
        exit();
    }

    $stmt = $con->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    $totalUsers = $stmt->fetchColumn();

    $stmt = $con->prepare("SELECT COUNT(*) FROM users WHERE role = 1");
    $stmt->execute();
    $totalAdmins = $stmt->fetchColumn();

    $stmt = $con->prepare("SELECT COUNT(*) FROM posts");
    $stmt->execute();
    $totalPosts = $stmt->fetchColumn();

    $stmt = $con->prepare("SELECT id, name, email, role, `created-at` FROM users ORDER BY id DESC LIMIT 10");
    $stmt->execute();
    $lastUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $con->prepare("SELECT id, name, email, `created-at` FROM users WHERE role = 1 ORDER BY id DESC LIMIT 10");
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $con->prepare("SELECT p.id, p.content, p.`created-at`, u.name AS author FROM posts p LEFT JOIN users u ON p.`user-id` = u.id ORDER BY p.`created-at` DESC LIMIT 5");
    $stmt->execute();
    $recentPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h2 class="mb-1"><?= lang('P_DASHBOARD') ?></h2>
                    <p class="text-muted mb-0">Last data summary for users, admins, and posts.</p>
                </div>
                <div>
                    <a href="dashboard.php?page=createPost" class="btn btn-primary px-4 py-2"><?= lang('H_NEW_POST') ?></a>
                </div>
            </div>

            <?php if ($page === 'createPost'): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><?= lang('H_NEW_POST') ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="dashboard.php?page=createPost">
                            <div class="mb-3">
                                <label class="form-label"><?= lang('H_POST_CONTENT') ?></label>
                                <textarea name="content" class="form-control" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success"><?= lang('H_POST_PUBLISH') ?></button>
                            <a href="dashboard.php" class="btn btn-secondary ms-2"><?= lang('BTN_CANCEL') ?></a>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($page !== 'createPost'): ?>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h6>Total users</h6>
                                <p class="display-6 mb-0"><?= intval($totalUsers) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h6>Total admins</h6>
                                <p class="display-6 mb-0"><?= intval($totalAdmins) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-body text-center">
                                <h6>Total posts</h6>
                                <p class="display-6 mb-0"><?= intval($totalPosts) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-4">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Last 10 Users</h5>
                            </div>
                            <div class="card-body p-0">
                                <?php if ($lastUsers): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Role</th>
                                                    <th>Joined</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($lastUsers as $user): ?>
                                                    <tr>
                                                        <td><?= intval($user['id']) ?></td>
                                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                                        <td><?= htmlspecialchars($user['role'] == 1 ? lang('ADMIN') : lang('USER')) ?></td>
                                                        <td><?= date('j F Y', strtotime($user['created-at'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="p-3"><em>No users found.</em></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Admins</h5>
                            </div>
                            <div class="card-body p-0">
                                <?php if ($admins): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Joined</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($admins as $admin): ?>
                                                    <tr>
                                                        <td><?= intval($admin['id']) ?></td>
                                                        <td><?= htmlspecialchars($admin['name']) ?></td>
                                                        <td><?= htmlspecialchars($admin['email']) ?></td>
                                                        <td><?= date('j F Y', strtotime($admin['created-at'])) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php else: ?>
                                    <div class="p-3"><em>No admins found.</em></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Last 5 Posts</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($recentPosts): ?>
                                    <?php foreach ($recentPosts as $post): ?>
                                        <div class="mb-3 pb-2 border-bottom">
                                            <p class="mb-1"><strong><?= htmlspecialchars($post['author'] ?: 'Unknown') ?></strong></p>
                                            <p class="mb-2 text-muted small" style="direction: ltr;"><?= date('j F Y', strtotime($post['created-at'])) ?></p>
                                            <p class="mb-0"><?= nl2br(htmlspecialchars(mb_strimwidth($post['content'], 0, 120, '...'))) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">No posts found.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
} else {
    header("Location: index.php");
    exit;
}

include $temp . "footer.php";
