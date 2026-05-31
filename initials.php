<?php

include "contact.php"; 

$temp = "include/templates/";
$lang = "include/lang/";
$func = "include/functions/";



$css = "layout/css/";
$js = "layout/js/";
$img = "layout/images/";


include $func . "getTitle.php";

include $temp . "header.php";


if (!isset($noNavbar)) {
    include $temp . "navbar.php";
}