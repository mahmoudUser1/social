<?php
session_start();
include "../contact.php";

if (!isset($_SESSION["email"]) || !isset($_GET['receiver_id'])) {
    echo json_encode([]);
    exit;
}

$receiverId = $_GET['receiver_id'];

try {
    // الحصول على معرف المستخدم الحالي
    $stmtUser = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmtUser->execute(array($_SESSION["email"]));
    $currentUser = $stmtUser->fetch();
    $currentUserId = $currentUser['id'];

    // جلب الرسائل المتبادلة
    $stmt = $con->prepare("SELECT * FROM chat 
                           WHERE (`from-id` = ? AND `to-id` = ?) 
                           OR (`from-id` = ? AND `to-id` = ?) 
                           ORDER BY `created-at` ASC");
    $stmt->execute(array($currentUserId, $receiverId, $receiverId, $currentUserId));
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($messages);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
