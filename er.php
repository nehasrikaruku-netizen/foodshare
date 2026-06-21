<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

echo "Emergency page is working!";
echo "<br>User: " . $_SESSION['fullname'];
?>