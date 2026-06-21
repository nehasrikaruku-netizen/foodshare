<?php

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];

mysqli_query($conn,
"DELETE FROM food_donations WHERE id='$id'");

header("Location:view_donations.php");
exit();

?>