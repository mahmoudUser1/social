<?php

session_start();

// Only allow access if the user is authenticated
if (!isset($_SESSION['email'])) {
    header('Location: index.php');
    exit;
}

// Unset only user-specific session keys so admin sessions remain intact
$userKeys = [
    'email',
    'name',
    'user_id',
    'chat_bg',
    'app_lang',
    'message',
    'error'
];
foreach ($userKeys as $key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

// Redirect to homepage after logging out the user
header('Location: index.php');
exit();