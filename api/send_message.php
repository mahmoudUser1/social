<?php
session_start();
include "../contact.php";

if (!isset($_SESSION["email"]) || !isset($_POST['receiver_id']) || !isset($_POST['message'])) {
    echo json_encode(['success' => false]);
    exit;
}

$receiverId = $_POST['receiver_id'];
$message = $_POST['message'];

try {
    // الحصول على معرف المستخدم الحالي
    $stmtUser = $con->prepare("SELECT id FROM users WHERE email = ?");
    $stmtUser->execute(array($_SESSION["email"]));
    $currentUser = $stmtUser->fetch();
    $currentUserId = $currentUser['id'];

    // إدخال الرسالة في قاعدة البيانات
    $stmt = $con->prepare("INSERT INTO chat (`from-id`, `to-id`, `messages`, `created-at`) VALUES (?, ?, ?, NOW())");
    $stmt->execute(array($currentUserId, $receiverId, $message));
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
