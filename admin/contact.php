<?php 

$dns = "mysql:host=localhost;dbname=social";
$user ="root";
$password = "";
$option = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
);


try {
    $con = new PDO($dns, $user, $password);

    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo " ==== ". $e->getMessage() ." ==== ";
}