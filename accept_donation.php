<?php

session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location: available_donations.php");
    exit();
}

$donation_id  = intval($_GET['id']);
$volunteer_id = $_SESSION['user_id'];

$check = mysqli_query(
$conn,
"SELECT * FROM food_donations
WHERE id='$donation_id'"
);

if(!$check || mysqli_num_rows($check) == 0)
{
    echo "<script>
    alert('Donation not found!');
    window.location='available_donations.php';
    </script>";
    exit();
}

$data = mysqli_fetch_assoc($check);

if($data['status'] != 'Available')
{
    echo "<script>
    alert('This donation has already been accepted!');
    window.location='available_donations.php';
    </script>";
    exit();
}

$update = mysqli_query(
$conn,
"UPDATE food_donations
SET status='Accepted'
WHERE id='$donation_id'"
);

if($update)
{
    echo "<script>
    alert('Donation accepted successfully!');
    window.location='my_tasks.php';
    </script>";
}
else
{
    echo "<script>
    alert('Error accepting donation!');
    window.location='available_donations.php';
    </script>";
}

?>