<?php

session_start();
include("db.php");

if (!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location: my_tasks.php");
    exit();
}

$id = intval($_GET['id']);

$update = mysqli_query(
$conn,
"UPDATE food_donations
SET status='Distributed'
WHERE id='$id'"
);

if($update)
{
    echo "<script>
    alert('Food marked as Distributed successfully!');
    window.location='my_tasks.php';
    </script>";
}
else
{
    echo "<script>
    alert('Error updating status!');
    window.location='my_tasks.php';
    </script>";
}

?>