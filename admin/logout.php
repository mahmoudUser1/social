<?php

session_start();

// Only allow access if the user is authenticated
if (!isset($_SESSION['email_admin'])) {
    header('Location: index.php');
    exit;
}

// Unset only admin-specific session keys so regular user sessions remain intact
$userKeys = [
    'email_admin',
    'name',
    'app_lang_admin',
    'message',
    'error'
];
foreach ($userKeys as $key) {
    if (isset($_SESSION[$key])) {
        unset($_SESSION[$key]);
    }
}

// Redirect to the admin login page after logging out
header('Location: index.php');
exit();