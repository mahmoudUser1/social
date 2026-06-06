<?php

include "contact.php";

$temp = "include/templates/";
$lang = "include/lang/";
$func = "include/functions/";



$css = "layout/css/";
$js = "layout/js/";
$img = "layout/images/";

if (!isset($_SESSION['app_lang'])) {
    $_SESSION['app_lang'] = 'en.php';
} else {
    include $lang . $_SESSION['app_lang'];
}
include $func . "getTitle.php";

include $temp . "header.php";


if (!isset($noNavbar)) {
    include $temp . "navbar.php";
}
if (isset($_SESSION['chat_bg'])) {
    $image_chat = $img . $_SESSION['chat_bg'];
} else {
    $image_chat = $img . "cubes.png";
}