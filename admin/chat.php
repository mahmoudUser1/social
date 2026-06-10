<?php

$pageTitle = "messages";

session_start();

include "initials.php";

if (isset($_SESSION["email_admin"])) {
    $page = isset($_GET['page']) ? trim($_GET['page']) : 'chat';

    function renderChatMessages($con)
    {
        $stmt = $con->prepare(
            "SELECT c.*, 
                   u1.name as from_name, 
                   u1.email as from_email,
                   u2.name as to_name, 
                   u2.email as to_email 
            FROM chat c
            LEFT JOIN users u1 ON c.`from-id` = u1.id
            LEFT JOIN users u2 ON c.`to-id` = u2.id
            ORDER BY c.`created-at` DESC"
        );
        $stmt->execute();
        $chat = $stmt->fetchAll();

        if ($chat) {
            echo '<div class="row">';
            foreach ($chat as $message) {
                ?>
                <div class="col-md-12 mb-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-body p-4 bg-white">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-start">
                                <div class="d-flex flex-column gap-3 w-100">
                                    <div class="d-flex flex-wrap gap-3 align-items-center">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-primary py-2 px-3 rounded-pill"><?= lang('FROM') ?></span>
                                            <div>
                                                <h6 class="mb-1 fw-semibold mb-0"><?= htmlspecialchars($message['from_name']) ?></h6>
                                                <p class="mb-0 text-muted small"><?= htmlspecialchars($message['from_email']) ?></p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-success py-2 px-3 rounded-pill"><?= lang('TO') ?></span>
                                            <div>
                                                <h6 class="mb-1 fw-semibold mb-0"><?= htmlspecialchars($message['to_name']) ?></h6>
                                                <p class="mb-0 text-muted small"><?= htmlspecialchars($message['to_email']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-md-end text-start">
                                    <span class="badge bg-secondary py-2 px-3 rounded-pill mb-2 d-inline-block"><?= lang('DATE') ?></span>
                                    <p class="mb-0 text-muted small" style="direction: ltr !important"><?= date("j F Y", strtotime($message['created-at'])) ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-light p-4">
                            <div class="rounded-4 border border-1 border-light bg-white p-3" style="white-space: pre-wrap; word-break: break-word; overflow-wrap: anywhere; line-height: 1.7;"><?= htmlspecialchars($message['messages']) ?></div>
                        </div>
                        <div class="card-footer border-top bg-white d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                            <span class="text-muted small">Message #<?= $message['id'] ?></span>
                            <a href="chat.php?page=deleteMessage&id=<?= $message['id'] ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can me-1"></i> <?= lang('BTN_DELETE') ?></a>
                        </div>
                    </div>
                </div>
                <?php
            }
            echo '</div>';
        } else {
            echo '<div class="alert alert-info text-center">No messages found.</div>';
        }
    }
    ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-3 col-md-4">
        </div>
        <div class="col-12 col-lg-9 col-md-8">


            <?php
            if ($page === 'deleteMessage') {
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $message_id = intval($_GET['id']);

                    $stmt = $con->prepare("DELETE FROM chat WHERE id = ?");
                    $stmt->execute([$message_id]);

                    $_SESSION['message'] = 'Message deleted successfully';
                }

                header("Location: chat.php");
                exit();
            }

            renderChatMessages($con);
            ?>


        </div>
    </div>
    <?php

} else {
    header("Location: index.php");
    exit;
}


include $temp . "footer.php";