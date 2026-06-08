<?php

$version = "26.1.0";

include "contact.php";

$temp = "include/templates/";
$lang = "include/lang/";
$func = "include/functions/";



$css = "layout/css/";
$js = "layout/js/";
$img = "layout/images/";

if (!isset($_SESSION['app_lang_admin'])) {
    $_SESSION['app_lang_admin'] = 'ar.php';
} else {
    include $lang . $_SESSION['app_lang_admin'];
}
include $func . "getTitle.php";

include $temp . "header.php";


if (!isset($noNavbar)) {
    include $temp . "navbar.php";
}
