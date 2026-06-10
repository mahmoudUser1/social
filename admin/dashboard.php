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
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3 mb-5">
                <div>
                    <h2 class="mb-2 fw-bold"><?= lang('P_DASHBOARD') ?></h2>
                    <p class="text-muted mb-0"><i class="fa-solid fa-chart-line me-2"></i><?= lang('D_SUMMARY') ?></p>
                </div>
                <div>
                    <a href="dashboard.php?page=createPost" class="btn btn-primary px-5 py-2 rounded-3"><?= lang('H_NEW_POST') ?></a>
                </div>
            </div>

            <?php if ($page === 'createPost'): ?>
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white border-bottom py-4 px-4">
                        <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                            <i class="fa-solid fa-pen-to-square text-primary"></i>
                            <?= lang('H_NEW_POST') ?>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="post" action="dashboard.php?page=createPost">
                            <div class="mb-4">
                                <label class="form-label fw-semibold"><?= lang('H_POST_CONTENT') ?></label>
                                <textarea name="content" class="form-control rounded-3 py-2" rows="5" required style="resize: vertical;"></textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-success px-5 py-2 rounded-3">
                                    <i class="fa-solid fa-paper-plane me-2"></i><?= lang('H_POST_PUBLISH') ?>
                                </button>
                                <a href="dashboard.php" class="btn btn-secondary px-5 py-2 rounded-3">
                                    <i class="fa-solid fa-xmark me-2"></i><?= lang('BTN_CANCEL') ?>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($page !== 'createPost'): ?>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden">
                            <div class="card-body text-center text-white p-5 bg-primary position-relative">
                                <div class="position-absolute top-0 end-0 p-3 fs-1 opacity-10">
                                    <i class="fa-solid fa-users"></i>
                                </div>
                                <div class="mb-3 fs-1">
                                    <i class="fa-solid fa-users text-white"></i>
                                </div>
                                <h6 class="text-white-50 text-uppercase fw-semibold mb-2 small"><?= lang('D_TOTAL_USERS') ?></h6>
                                <p class="display-4 mb-0 fw-bolder"><?= intval($totalUsers) ?></p>
                            </div>
                            <div class="card-footer bg-white border-0 p-3 text-center">
                                <small class="text-primary fw-semibold"><i class="fa-solid fa-arrow-trend-up me-1"></i><?= lang('D_ACTIVE_USERS') ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden">
                            <div class="card-body text-center text-white p-5 bg-danger position-relative">
                                <div class="position-absolute top-0 end-0 p-3 fs-1 opacity-10">
                                    <i class="fa-solid fa-crown"></i>
                                </div>
                                <div class="mb-3 fs-1">
                                    <i class="fa-solid fa-crown text-white"></i>
                                </div>
                                <h6 class="text-white-50 text-uppercase fw-semibold mb-2 small"><?= lang('D_TOTAL_ADMINS') ?></h6>
                                <p class="display-4 mb-0 fw-bolder"><?= intval($totalAdmins) ?></p>
                            </div>
                            <div class="card-footer bg-white border-0 p-3 text-center">
                                <small class="text-danger fw-semibold"><i class="fa-solid fa-shield me-1"></i><?= lang('D_SYSTEM_MANAGERS') ?></small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card border-0 shadow-lg rounded-4 h-100 overflow-hidden">
                            <div class="card-body text-center text-white p-5 bg-success position-relative">
                                <div class="position-absolute top-0 end-0 p-3 fs-1 opacity-10">
                                    <i class="fa-solid fa-newspaper"></i>
                                </div>
                                <div class="mb-3 fs-1">
                                    <i class="fa-solid fa-newspaper text-white"></i>
                                </div>
                                <h6 class="text-white-50 text-uppercase fw-semibold mb-2 small"><?= lang('D_TOTAL_POSTS') ?></h6>
                                <p class="display-4 mb-0 fw-bolder"><?= intval($totalPosts) ?></p>
                            </div>
                            <div class="card-footer bg-white border-0 p-3 text-center">
                                <small class="text-success fw-semibold"><i class="fa-solid fa-message me-1"></i><?= lang('D_USER_POSTS') ?></small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 mt-5">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-4 px-4">
                                            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                                <i class="fa-solid fa-users text-primary"></i>
                                                <?= lang('D_LAST_USERS') ?>
                                            </h5>
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
                                    <div class="p-3"><em><?= lang('NO_USERS_FOUND') ?></em></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-4 px-4">
                                    <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-shield-halved text-danger"></i>
                                    <?= lang('D_ADMINS') ?>
                                </h5>
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
                                    <div class="p-3"><em><?= lang('NO_ADMINS_FOUND') ?></em></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                            <div class="card-header bg-white border-bottom py-4 px-4">
                                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-newspaper text-info"></i>
                                    <?= lang('D_LAST_POSTS') ?>
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <?php if ($recentPosts): ?>
                                    <?php foreach ($recentPosts as $post): ?>
                                        <div class="mb-4 pb-3 border-bottom">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 32px; height: 32px; font-size: 12px;">
                                                    <?= strtoupper(substr($post['author'] ?: 'U', 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <p class="mb-0 fw-bold"><?= htmlspecialchars($post['author'] ?: 'Unknown') ?></p>
                                                    <p class="mb-0 text-muted small" style="direction: ltr;"><?= date('j F Y H:i', strtotime($post['created-at'])) ?></p>
                                                </div>
                                            </div>
                                            <p class="mb-0 text-secondary" style="line-height: 1.6;"><?= nl2br(htmlspecialchars(mb_strimwidth($post['content'], 0, 120, '...'))) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted text-center py-4">
                                        <i class="fa-solid fa-inbox me-2"></i><?= lang('NO_POSTS_FOUND') ?>
                                    </p>
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
