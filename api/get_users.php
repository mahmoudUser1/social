<?php
session_start();
include "../contact.php";

if (!isset($_SESSION["email"])) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $con->prepare("SELECT id, name, email FROM users ORDER BY name ASC");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
