<?php

$pageTitle = "users";

session_start();

include "initials.php";

if (isset($_SESSION["email_admin"])) {
    $page = isset($_GET['page']) ? $_GET['page'] : 'chat';
    ?>
    <div class="row pt-6 p-md-2 m-0">
        <div class="d-none d-md-block col-lg-3 col-md-4">
        </div>
        <div class="col-12 col-lg-9 col-md-8">


            <?php

            if ($page == 'chat') {

                $stmt = $con->prepare("
                    SELECT c.*, 
                           u1.name as from_name, 
                           u2.name as to_name 
                    FROM chat c
                    LEFT JOIN users u1 ON c.`from-id` = u1.id
                    LEFT JOIN users u2 ON c.`to-id` = u2.id
                    ORDER BY c.`created-at` DESC
                ");
                $stmt->execute();
                $chat = $stmt->fetchAll();

                if ($chat) {
                    echo '<div class="row">';
                    foreach ($chat as $message) {

                        ?>

                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <p><strong><?= lang('FROM') ?>:</strong> <?= htmlspecialchars($message['from_name']) ?></p>
                                    <p><strong><?= lang('TO') ?>:</strong> <?= htmlspecialchars($message['to_name']) ?></p>
                                    <p><strong><?= lang('DATE') ?>:</strong> <span style="direction: ltr !important"><?= date("j F Y", strtotime($message['created-at'])) ?></span></p>
                                </div>
                                <div class="card-body border-top">
                                    <pre class="text-post overflow-hidden"><?= htmlspecialchars($message['messages']) ?></pre>
                                </div>
                                <div class="card-footer">
                                    <a href="chat.php?page=deleteMessage&id=<?= $message['id'] ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i> <?= lang('BTN_DELETE') ?></a>
                                </div>
                            </div>
                        </div>

                        <?php
                    }
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-info text-center">No messages found.</div>';
                }
            } elseif ($page == 'deleteMessage') {

                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    $message_id = intval($_GET['id']);

                    $stmt = $con->prepare("DELETE FROM chat WHERE id = ?");
                    $stmt->execute([$message_id]);

                    $_SESSION['message'] = 'Message deleted successfully';
                    header("Location: chat.php");
                    exit();
                }

                header("Location: chat.php");
                exit();
            } else {

                $stmt = $con->prepare("
                    SELECT c.*, 
                           u1.name as from_name, 
                           u2.name as to_name 
                    FROM chat c
                    LEFT JOIN users u1 ON c.`from-id` = u1.id
                    LEFT JOIN users u2 ON c.`to-id` = u2.id
                    ORDER BY c.`created-at` DESC
                ");
                $stmt->execute();
                $chat = $stmt->fetchAll();

                if ($chat) {
                    echo '<div class="row">';
                    foreach ($chat as $message) {

                        ?>

                        <div class="col-md-12 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <p><strong><?= lang('FROM') ?>:</strong> <?= htmlspecialchars($message['from_name']) ?></p>
                                    <p><strong><?= lang('TO') ?>:</strong> <?= htmlspecialchars($message['to_name']) ?></p>
                                    <p><strong><?= lang('DATE') ?>:</strong> <span style="direction: ltr !important"><?= date("j F Y", strtotime($message['created-at'])) ?></span></p>
                                </div>
                                <div class="card-body border-top">
                                    <pre class="text-post overflow-hidden"><?= htmlspecialchars($message['messages']) ?></pre>
                                </div>
                                <div class="card-footer">
                                    <a href="chat.php?page=deleteMessage&id=<?= $message['id'] ?>" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash-can"></i> <?= lang('BTN_DELETE') ?></a>
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


        </div>
    </div>
    <?php

} else {
    header("Location: index.php");
    exit;
}


include $temp . "footer.php";