<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT');

$conn = mysqli_connect(
    $host,
    $user,
    $pass,
    $db,
    (int)$port
);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>